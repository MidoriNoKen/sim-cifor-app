<?php

namespace App\Http\Services;

use App\Enums\PositionEnum;
use App\Utils\Util;
use Exception;

class ApprovalService
{
    private $loggedUser, $loggedId, $loggedRole, $loggedPosition;

    public function __construct(UserService $userService)
    {
        $this->loggedUser = $userService->getLoggedUser();
        $this->loggedId = $this->loggedUser->id;
        $this->loggedRole = $this->loggedUser->role->name;
        $this->loggedPosition = $this->loggedUser->position;
    }

    public function formattedData($data)
    {
        $this->getAccessData($data);
        $data->start_date = Util::formatDate($data->start_date);
        $data->end_date = Util::formatDate($data->end_date);
    }

    public function getAccessData($data)
    {
        $loggedId = $this->loggedId;
        $data->isSupervisor = $loggedId == $data->supervisor_id ? true : false;
        $data->isManager = $loggedId == $data->manager_id ? true : false;
        if ($this->loggedPosition == PositionEnum::FINANCE)
            $data->isFinance = $loggedId == $data->finance_id ? true : false;
    }

    public function checkAccess($userId, $role, $position)
    {
        try {
            return ($this->loggedId == $userId && $this->loggedRole == $role && $this->loggedPosition == $position)
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

        if ($this->loggedRole == PositionEnum::MANAGER)
            $data->manager_reject_reasons = $reasons;

        else if ($this->loggedRole == PositionEnum::SENIOR)
            $data->supervisor_reject_reasons = $reasons;

        else if ($this->loggedRole == PositionEnum::FINANCE)
            $data->finance_reject_reasons = $reasons;

        $data->status = $updatedStatus;
        $data->save();
    }
}
