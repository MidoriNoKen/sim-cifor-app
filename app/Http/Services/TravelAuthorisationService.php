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

    public function getAllByLoggedPosition($page, $perPage)
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

        return $query->paginate($perPage, ['*'], 'page', $page);
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

            // Pastikan validasi benar-benar berhasil
            if (!$validation) {
                throw new Exception("Invalid input data.");
            }

            // Ubah timezone dari tanggal
            $start_date = Carbon::parse($request->start_date)->timezone('Asia/Jakarta');
            $end_date = Carbon::parse($request->end_date)->timezone('Asia/Jakarta');

            // Periksa apakah tanggal awal dan akhir sama dan apakah tanggal tersebut adalah hari libur
            if ($start_date->equalTo($end_date) && $this->holidayService->isHoliday($start_date)) {
                throw new Exception("Tanggal " . Util::formatDateToIndonesian($start_date) . " adalah hari Libur Nasional.");
            }

            // Ambil daftar hari libur di antara tanggal awal dan akhir
            $holidays = $this->holidayService->getHolidaysInRange($start_date, $end_date);

            if (!$holidays->isEmpty()) {
                // Bagi rentang tanggal menjadi rentang yang tidak termasuk hari libur
                $dateRanges = $this->holidayService->splitDateRange($start_date, $end_date, $holidays);

                foreach ($dateRanges as $range) {
                    // Buat objek TravelAuthorisation untuk setiap rentang tanggal
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
                        'travel_reasons' => $request->travel_reasons,
                    ]);

                    // Tambahkan detail akomodasi untuk setiap objek TravelAuthorisation
                    foreach ($request->accommodation_details as $detail) {
                        $travelAuthorisation->accommodationDetails()->create([
                            'name' => $detail['name'],
                            'quantity' => $detail['quantity'],
                            'price' => $detail['price'],
                            'total_price' => $detail['quantity'] * $detail['price'],
                            'description' => $detail['description']
                        ]);
                    }

                    // Lakukan pengecekan kembali jika pembuatan TravelAuthorisation gagal
                    if (!$travelAuthorisation) {
                        throw new Exception("An error occurred while storing the travel authorisation.");
                    }
                }
            } else {
                // Jika tidak ada hari libur, buat TravelAuthorisation untuk rentang tanggal tersebut
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
                    'accumulation' => Util::getDateTimeDifference($start_date, $end_date),
                    'travel_reasons' => $request->travel_reasons,
                ]);

                // Tambahkan detail akomodasi untuk TravelAuthorisation
                foreach ($request->accommodation_details as $detail) {
                    $travelAuthorisation->accommodationDetails()->create([
                        'name' => $detail['name'],
                        'quantity' => $detail['quantity'],
                        'price' => $detail['price'],
                        'total_price' => $detail['quantity'] * $detail['price'],
                        'description' => $detail['description']
                    ]);
                }

                // Lakukan pengecekan kembali jika pembuatan TravelAuthorisation gagal
                if (!$travelAuthorisation) {
                    throw new Exception("An error occurred while storing the travel authorisation.");
                }
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}