<?php

namespace App\Http\Services;

use App\Enums\ApprovalStatusEnum;
use App\Enums\PositionEnum;
use App\Enums\RoleEnum;
use App\Models\LeaveApplication;
use App\Utils\Util;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class LeaveApplicationService
{
    public function getById($id)
    {
        return LeaveApplication::find($id);
    }

    public function getAllByLoggedPosition()
    {
        $user = Auth::user();
        $roleName = $user->role->name;
        $position = $user->position;
        $userId = $user->id;

        $query = LeaveApplication::query()->with(["applicant", "supervisor", "manager"]);

        if ($roleName === RoleEnum::STAFF) {
            if ($position === PositionEnum::JUNIOR) {
                $query->where("applicant_id", $userId);
            } else if ($position === PositionEnum::SENIOR) {
                $query->whereIn("supervisor_id", [$userId, $userId]);
            }
        } else if ($roleName === RoleEnum::MANAGER && $position === PositionEnum::MANAGER) {
            $query->whereIn("manager_id", [$userId, $userId]);
        }

        return $query->get();
    }

    public function store($request)
    {
        try {
            $validation = $request->validate([
                'leave_type' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
            ]);

            if (!$validation) {
                Throw new Exception("Invalid input data.");
            }

            $leaveApplication = LeaveApplication::create([
                'applicant_id' => auth()->id(),
                'status' => ApprovalStatusEnum::SUPERVISOR_PENDING,
                'supervisor_id' => auth()->user()->supervisor_id,
                'supervisor_reject_reasons' => null,
                'manager_id' => auth()->user()->manager_id,
                'manager_reject_reasons' => null,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'day_accumulation' => date_diff(date_create($request->start_date), date_create($request->end_date))->format('%a') + 1,
                'leave_type' => $request->leave_type,
            ]);

            if (!$leaveApplication) {
                Throw new Exception("An error occurred while storing the leave application.");
            }

            event(new Registered($leaveApplication));
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
