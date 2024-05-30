<?php

namespace App\Http\Services;

use App\Enums\ApprovalStatusEnum;
use App\Interfaces\LeaveApplicationInterface;
use App\Repositories\UserRepository;
use App\Utils\Util;
use Carbon\Carbon;
use Exception;

class LeaveApplicationService
{
    private $leaveApplicationRepository, $holidayService;

    public function __construct(LeaveApplicationInterface $leaveApplicationRepository, HolidayService $holidayService)
    {
        $this->leaveApplicationRepository = $leaveApplicationRepository;
        $this->holidayService = $holidayService;
    }

    public function getAll($request)
    {
        [$page, $perPage] = Util::getPagination($request);
        $leaveApplications = $this->leaveApplicationRepository->getAllWithPagination($page, $perPage);

        foreach ($leaveApplications as $leaveApplication) {
            $leaveApplication->born_date = Carbon::parse($leaveApplication->born_date)->format('d F Y');
        }

        return $leaveApplications;
    }

    public function getById($id)
    {
        return $this->leaveApplicationRepository->getById($id);
    }

    public function getByIdWithPagination($request, $userId)
    {
        [$page, $perPage] = Util::getPagination($request);
        return $this->leaveApplicationRepository->getByUserId($userId, $page, $perPage);
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
                    $leaveApplication = $this->leaveApplicationRepository->create([
                        'applicant_id' => auth()->id(),
                        'status' => ApprovalStatusEnum::OFFICER_PENDING,
                        'officer_id' => auth()->user()->officer_id,
                        'officer_reject_reasons' => null,
                        'hrManager_id' => auth()->user()->manager_id,
                        'hrManager_reject_reasons' => null,
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
                $leaveApplication = $this->leaveApplicationRepository->create([
                    'applicant_id' => auth()->id(),
                    'status' => ApprovalStatusEnum::OFFICER_PENDING,
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
