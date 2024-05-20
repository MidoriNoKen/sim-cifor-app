<?php

namespace App\Http\Services;

use App\Enums\ApprovalStatusEnum;
use App\Enums\PositionEnum;
use App\Enums\RoleEnum;
use App\Models\LeaveApplication;
use App\Utils\Util;
use Exception;

class LeaveApplicationService
{
    private $user;

    public function __construct(UserService $userService)
    {
        $this->user = $userService->getLoggedUser();
    }

    public function getById($id)
    {
        return LeaveApplication::find($id);
    }

    public function getAllByLoggedPosition()
    {
        $user = $this->user;
        $roleName = $user->role->name;
        $position = $user->position;
        $userId = $user->id;

        $query = LeaveApplication::query()->with(["applicant", "supervisor", "manager"]);

        if ($roleName === RoleEnum::STAFF && $position === PositionEnum::SENIOR)
        $query->where("applicant_id", $userId)->orWhere("supervisor_id", $userId);

        else if ($roleName === RoleEnum::MANAGER && $position === PositionEnum::MANAGER)
        $query->where("applicant_id", $userId)->orWhere("manager_id", $userId);

        $query->orderBy('start_date', 'desc');

        return $query->get();
    }

    public function store($request)
    {
        try {
            $validation = $request->validate([
                'leave_type' => 'required|string',
                'start_date' => 'required|string|date|before:end_date',
                'end_date' => 'required|string|date|after_or_equal:start_date',
            ]);

            if (!$validation) {
                Throw new Exception("Invalid input data.");
            }

            $start_date = date('Y-m-d H:i:s', strtotime($request->start_date));
            $end_date = date('Y-m-d H:i:s', strtotime($request->end_date));

            $leaveApplication = LeaveApplication::create([
                'applicant_id' => auth()->id(),
                'status' => ApprovalStatusEnum::SUPERVISOR_PENDING,
                'supervisor_id' => auth()->user()->supervisor_id,
                'supervisor_reject_reasons' => null,
                'manager_id' => auth()->user()->manager_id,
                'manager_reject_reasons' => null,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'accumulation' => Util::getDateTimeDifference($request->start_date, $request->end_date),
                'leave_type' => $request->leave_type,
            ]);

            if (!$leaveApplication) {
                Throw new Exception("An error occurred while storing the leave application.");
            }

            return $leaveApplication;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}