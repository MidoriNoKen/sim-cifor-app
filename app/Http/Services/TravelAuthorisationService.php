<?php

namespace App\Http\Services;

use App\Enums\ApprovalStatusEnum;
use App\Enums\PositionEnum;
use App\Enums\RoleEnum;
use App\Models\TravelAuthorisation;
use App\Utils\Util;
use Carbon\Carbon;
use Exception;

class TravelAuthorisationService
{
    private $user, $holidayService;

    public function __construct(UserService $userService, HolidayService $holidayService)
    {
        $this->user = $userService->getLoggedUser();
        $this->holidayService = $holidayService;
    }

    public function getById($id)
    {
        return TravelAuthorisation::find($id);
    }

    public function getAllByLoggedPosition()
    {
        $user = $this->user;
        $roleName = $user->role->name;
        $position = $user->position;
        $userId = $user->id;

        $query = TravelAuthorisation::query()->with(["applicant", "supervisor", "manager", "finance"]);

        if ($roleName === RoleEnum::STAFF && $position === PositionEnum::SENIOR)
        $query->where("applicant_id", $userId)->orWhere("supervisor_id", $userId);

        else if ($roleName === RoleEnum::MANAGER && $position === PositionEnum::MANAGER)
        $query->where("applicant_id", $userId)->orWhere("manager_id", $userId);

        else if ($roleName === RoleEnum::STAFF && $position === PositionEnum::FINANCE)
        $query->where("finance_id", $userId)->orWhere("finance_id", $userId);

        $query->orderBy('start_date', 'desc');

        return $query->get();
    }

    public function store($request)
    {
        try {
            $validation = $request->validate([
                'transport_type' => 'required|string',
                'start_date' => 'required|string|date|before:end_date',
                'end_date' => 'required|string|date|after_or_equal:start_date',
                'accomodation_detail' => 'required|string',
                'travel_reasons' => 'required|string',
                'finance_id' => 'required'
            ]);

            if (!$validation) {
                Throw new Exception("Invalid input data.");
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
                    $travelAuthorisation = TravelAuthorisation::create([
                        'applicant_id' => auth()->id(),
                        'status' => ApprovalStatusEnum::SUPERVISOR_PENDING,
                        'supervisor_id' => auth()->user()->supervisor_id,
                        'supervisor_reject_reasons' => null,
                        'manager_id' => auth()->user()->manager_id,
                        'manager_reject_reasons' => null,
                        'finance_id' => $request->finance_id,
                        'finance_reject_reasons' => null,
                        'unit_id' => 1,
                        'transport_type' => $request->transport_type,
                        'start_date' => $range['start_date']->toDateTimeString(),
                        'end_date' => $range['end_date']->toDateTimeString(),
                        'accumulation' => Util::getDateTimeDifference($range['start_date'], $range['end_date']),
                        'accomodation_detail' => $request->accomodation_detail,
                        'travel_reasons' => $request->travel_reasons,
                    ]);

                    if (!$travelAuthorisation) {
                        throw new Exception("An error occurred while storing the leave application.");
                    }
                }
            } else {
                $travelAuthorisation = TravelAuthorisation::create([
                    'applicant_id' => auth()->id(),
                    'status' => ApprovalStatusEnum::SUPERVISOR_PENDING,
                    'supervisor_id' => auth()->user()->supervisor_id,
                    'supervisor_reject_reasons' => null,
                    'manager_id' => auth()->user()->manager_id,
                    'manager_reject_reasons' => null,
                    'finance_id' => $request->finance_id,
                    'finance_reject_reasons' => null,
                    'unit_id' => 1,
                    'transport_type' => $request->transport_type,
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'accumulation' => Util::getDateTimeDifference($request->start_date, $request->end_date),
                    'accomodation_detail' => $request->accomodation_detail,
                    'travel_reasons' => $request->travel_reasons,
                ]);

                if (!$travelAuthorisation) {
                    throw new Exception("An error occurred while storing the leave application.");
                }
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
