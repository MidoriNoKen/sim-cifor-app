<?php

namespace App\Http\Services;

use App\Enums\ApprovalStatusEnum;
use App\Interfaces\TravelAuthorisationInterface;
use App\Utils\Util;
use Carbon\Carbon;
use Exception;

class TravelAuthorisationService
{
    private $travelAuthorisationRepository, $holidayService;

    public function __construct(TravelAuthorisationInterface $travelAuthorisationRepository, HolidayService $holidayService)
    {
        $this->travelAuthorisationRepository = $travelAuthorisationRepository;
        $this->holidayService = $holidayService;
    }

    public function getAll($request)
    {
        [$page, $perPage] = Util::getPagination($request);
        $leaveApplications = $this->travelAuthorisationRepository->getAllWithPagination($page, $perPage);

        foreach ($leaveApplications as $leaveApplication) {
            $leaveApplication->born_date = Carbon::parse($leaveApplication->born_date)->format('d F Y');
        }

        return $leaveApplications;
    }

    public function getById($id)
    {
        return $this->travelAuthorisationRepository->getById($id);
    }

    public function getByIdWithAccomodation($id)
    {
        return $this->travelAuthorisationRepository->getByIdWithAccomodation($id);
    }

    public function store($request)
    {
        try {
            $validation = $request->validate([
                'transport_type' => 'required|string',
                'start_date' => 'required|date|before:end_date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'accommodation_details' => 'required|array',
                'accommodation_details.*.name' => 'required|string',
                'accommodation_details.*.quantity' => 'required|integer',
                'accommodation_details.*.price' => 'required|numeric',
                'accommodation_details.*.description' => 'nullable|string',
                'travel_reasons' => 'required|string',
                'finance_id' => 'required'
            ]);

            if (!$validation) {
                throw new Exception("Invalid input data.");
            }

            $start_date = Carbon::parse($request->start_date)->timezone('Asia/Jakarta');
            $end_date = Carbon::parse($request->end_date)->timezone('Asia/Jakarta');

            if ($start_date->equalTo($end_date) && $this->holidayService->isHoliday($start_date)) {
                throw new Exception("Tanggal " . Util::formatDateToIndonesian($start_date) . " adalah hari Libur Nasional.");
            }

            $holidays = $this->holidayService->getHolidaysInRange($start_date, $end_date);

            if (!$holidays->isEmpty()) {
                $dateRanges = $this->holidayService->splitDateRange($start_date, $end_date, $holidays);

                foreach ($dateRanges as $range) {
                    $travelAuthorisation = $this->travelAuthorisationRepository->create([
                        'applicant_id' => auth()->id(),
                        'status' => ApprovalStatusEnum::OFFICER_PENDING,
                        'officer_id' => auth()->user()->officer_id,
                        'officer_reject_reasons' => null,
                        'hrManager_id' => auth()->user()->hrManager_id,
                        'hrManager_reject_reasons' => null,
                        'finance_id' => $request->finance_id,
                        'finance_reject_reasons' => null,
                        'unit_id' => 1,
                        'transport_type' => $request->transport_type,
                        'start_date' => $range['start_date']->toDateTimeString(),
                        'end_date' => $range['end_date']->toDateTimeString(),
                        'accumulation' => Util::getDateTimeDifference($range['start_date'], $range['end_date']),
                        'travel_reasons' => $request->travel_reasons,
                    ]);

                    if (!$travelAuthorisation) {
                        throw new Exception("An error occurred while storing the travel authorisation.");
                    }
                }
            } else {
                $travelAuthorisation = $this->travelAuthorisationRepository->create([
                    'applicant_id' => auth()->id(),
                    'status' => ApprovalStatusEnum::OFFICER_PENDING,
                    'officer_id' => auth()->user()->officer_id,
                    'officer_reject_reasons' => null,
                    'hrManager_id' => auth()->user()->hrManager_id,
                    'hrManager_reject_reasons' => null,
                    'finance_id' => $request->finance_id,
                    'finance_reject_reasons' => null,
                    'unit_id' => 1,
                    'transport_type' => $request->transport_type,
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'accumulation' => Util::getDateTimeDifference($start_date, $end_date),
                    'travel_reasons' => $request->travel_reasons,
                ]);

                if (!$travelAuthorisation) {
                    throw new Exception("An error occurred while storing the travel authorisation.");
                }
            }

            foreach ($request->accommodation_details as $detail) {
                $travelAuthorisation->accommodationDetails()->create([
                    'name' => $detail['name'],
                    'quantity' => $detail['quantity'],
                    'price' => $detail['price'],
                    'total_price' => $detail['quantity'] * $detail['price'],
                    'description' => $detail['description']
                ]);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}