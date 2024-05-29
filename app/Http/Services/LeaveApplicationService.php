<?php

namespace App\Http\Services;

use App\Enums\ApprovalStatusEnum;
use App\Enums\PositionEnum;
use App\Enums\RoleEnum;
use App\Models\LeaveApplication;
use App\Utils\Util;
use Carbon\Carbon;
use Exception;

class LeaveApplicationService
{
    private $user, $holidayService;

    public function __construct(UserService $userService, HolidayService $holidayService)
    {
        $this->user = $userService;
        $this->holidayService = $holidayService;
    }

    public function getById($id)
    {
        return LeaveApplication::find($id);
    }

    public function getAllByLoggedPosition($page, $perPage) {
        $user = $this->user;
        $roleName = $user->role->name;
        $position = $user->position;
        $userId = $user->id;

        $query = LeaveApplication::query()->with(["applicant", "officer", "HR"]);

        if ($roleName === RoleEnum::STAFF && $position === PositionEnum::SENIOR)
        $query->where("applicant_id", $userId)->orWhere("officer_id", $userId);

        else if ($roleName === RoleEnum::HR && $position === PositionEnum::HR)
        $query->where("applicant_id", $userId)->orWhere("manager_id", $userId);

        $query->orderBy('start_date', 'desc');

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    public function store($request)
    {
        try {
            $validation = $request->validate([
                'leave_type' => 'required|string',
                'start_date' => 'required|string|date',
                'end_date' => 'required|string|date|after_or_equal:start_date',
            ]);

            if (!$validation) {
                throw new Exception("Invalid input data.");
            }

            $start_date = Carbon::parse($request->start_date)->timezone('Asia/Jakarta');
            $end_date = Carbon::parse($request->end_date)->timezone('Asia/Jakarta');

            if ($start_date->equalTo($end_date)) {
                if ($this->holidayService->isHoliday($start_date)) {
                    throw new Exception("Tanggal " . Util::formatDateToIndonesian($start_date) . " adalah hari Libur Nasional.");
                }
            }

            $holidays = $this->holidayService->getHolidaysInRange($start_date, $end_date);

            if (!$holidays->isEmpty()) {
                $dateRanges = $this->holidayService->splitDateRange($start_date, $end_date, $holidays);

                foreach ($dateRanges as $range) {
                    $leaveApplication = LeaveApplication::create([
                        'applicant_id' => auth()->id(),
                        'status' => ApprovalStatusEnum::SUPERVISOR_PENDING,
                        'officer_id' => auth()->user()->officer_id,
                        'supervisor_reject_reasons' => null,
                        'manager_id' => auth()->user()->manager_id,
                        'manager_reject_reasons' => null,
                        'start_date' => $range['start_date']->toDateTimeString(),
                        'end_date' => $range['end_date']->toDateTimeString(),
                        'accumulation' => Util::getDateTimeDifference($range['start_date'], $range['end_date']),
                        'leave_type' => $request->leave_type,
                    ]);

                    if (!$leaveApplication) {
                        throw new Exception("An error occurred while storing the leave application.");
                    }
                }
            } else {
                $leaveApplication = LeaveApplication::create([
                    'applicant_id' => auth()->id(),
                    'status' => ApprovalStatusEnum::SUPERVISOR_PENDING,
                    'officer_id' => auth()->user()->officer_id,
                    'supervisor_reject_reasons' => null,
                    'manager_id' => auth()->user()->manager_id,
                    'manager_reject_reasons' => null,
                    'start_date' => $start_date->toDateTimeString(),
                    'end_date' => $end_date->toDateTimeString(),
                    'accumulation' => Util::getDateTimeDifference($start_date, $end_date),
                    'leave_type' => $request->leave_type,
                ]);

                if (!$leaveApplication) {
                    throw new Exception("An error occurred while storing the leave application.");
                }
            }

            return $leaveApplication;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}