<?php

namespace App\Http\Services;

use App\Enums\ApprovalStatusEnum;
use App\Enums\PositionEnum;
use App\Enums\RoleEnum;
use App\Models\TravelAuthorisation;
use App\Utils\Util;
use Exception;

class TravelAuthorisationService
{
    private $user;

    public function __construct(UserService $userService)
    {
        $this->user = $userService->getLoggedUser();
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

        $query = TravelAuthorisation::query()->with(["applicant", "supervisor", "manager"]);

        if ($roleName === RoleEnum::STAFF && $position === PositionEnum::SENIOR)
        $query->where("applicant_id", $userId)->orWhere("supervisor_id", $userId);

        else if ($roleName === RoleEnum::MANAGER && $position === PositionEnum::MANAGER)
        $query->where("applicant_id", $userId)->orWhere("manager_id", $userId);

        return $query->get();
    }

    public function store($request)
    {
        try {
            $validation = $request->validate([
                'transport_type' => 'required|string',
                'start_date' => 'required|string',
                'end_date' => 'required|string',
                'accomodation_detail' => 'required|string',
                'travel_reasons' => 'required|string',
            ]);

            if (!$validation) {
                Throw new Exception("Invalid input data.");
            }

            $travelAuthorisation = TravelAuthorisation::create([
                'applicant_id' => auth()->id(),
                'status' => ApprovalStatusEnum::SUPERVISOR_PENDING,
                'supervisor_id' => auth()->user()->supervisor_id,
                'supervisor_reject_reasons' => null,
                'manager_id' => auth()->user()->manager_id,
                'manager_reject_reasons' => null,
                'finance_id' => null,
                'finance_reject_reasons' => null,
                'unit_id' => 1,
                'transport_type' => $request->transport_type,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'accumulation' => Util::getDateTimeDifference($request->start_date, $request->end_date),
                'accomodation_detail' => $request->accomodation_detail,
                'travel_reasons' => $request->travel_reasons,
            ]);

            if (!$travelAuthorisation) {
                Throw new Exception("An error occurred while storing the leave application.");
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}