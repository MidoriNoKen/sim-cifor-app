<?php

namespace App\Http\Services;

use App\Enums\PositionEnum;
use App\Enums\RoleEnum;
use App\Interfaces\UserInterface;
use App\Utils\Util;
use Exception;

class ApprovalService
{
    private $userRepository;

    public function __construct(UserInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function formattedData($data, $userId)
    {
        $data->start_date = Util::formatDate($data->start_date);
        $data->end_date = Util::formatDate($data->end_date);
        $data->isOfficial = $userId == $data->officer_id ? true : false;
        $data->isHrManager = $userId == $data->hrManager_id ? true : false;
        if ($this->userRepository->getById($userId)->position == PositionEnum::FINANCE)
            $data->isFinance = true;
    }

    public function checkAccess($userId, $role, $position)
    {
        dd($userId, $role, $position);
        $user = auth()->user();
        try {
            return ($user->id == $userId && $user->role_id == $role && $user->position == $position)
                    ? true
                    : throw new Exception("You don't have access to this data.");
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function checkStatus($status, $statusToCheck)
    {
        try {
            return $status == $statusToCheck ? true
            : throw new Exception("This data has been approved or rejected.");
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function approval($data, $userId, $role, $position, $status, $updatedStatus)
    {
        $this->checkAccess($userId, $role, $position);
        $this->checkStatus($data->status, $status);

        $data->status = $updatedStatus;
        $data->save();

        return true;
    }

    public function rejection($data, $userId, $role, $position, $status, $updatedStatus, $reasons)
    {
        $this->checkAccess($userId, $role, $position);
        $this->checkStatus($data->status, $status);
        $position = auth()->user()->position;
        $role = auth()->user()->role_id;

        if ($position == PositionEnum::HR && $role == RoleEnum::MANAGER)
            $data->hrManager_reject_reasons = $reasons;

        else if ($position == PositionEnum::OFFICER && $role == RoleEnum::EMPLOYEE)
            $data->officer_reject_reasons = $reasons;

        else if ($position == PositionEnum::FINANCE && $role == RoleEnum::MANAGER)
            $data->finance_reject_reasons = $reasons;

        $data->status = $updatedStatus;
        $data->save();
    }
}