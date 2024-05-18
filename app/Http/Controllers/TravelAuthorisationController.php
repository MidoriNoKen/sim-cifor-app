<?php

namespace App\Http\Controllers;

use App\Enums\ApprovalStatusEnum;
use App\Enums\PositionEnum;
use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Services\ApprovalService;
use App\Http\Services\TravelAuthorisationService;
use App\Http\Services\UserService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TravelAuthorisationController extends Controller
{
    private $travelAuthorisationService, $approvalService, $userService, $loggedRole, $loggedPosition;

    public function __construct(TravelAuthorisationService $travelAuthorisationService, ApprovalService $approvalService, UserService $userService)
    {
        $this->travelAuthorisationService = $travelAuthorisationService;
        $this->approvalService = $approvalService;
        $this->userService = $userService;
        $this->loggedRole = $userService->getLoggedRole();
        $this->loggedPosition = $userService->getLoggedPosition();
    }

    public function index()
    {
        $travelAuthorisations = $this->travelAuthorisationService->getAllByLoggedPosition();

        $travelAuthorisations->each(function ($travelAuthorisation) {
            $this->approvalService->formattedData($travelAuthorisation);
        });

        return Inertia::render('TravelAuthorisation/Index')->with(['travelAuthorisations' => $travelAuthorisations, 'loggedRole' => $this->loggedRole]);
    }

    public function show($id)
    {
        $travelAuthorisation = $this->travelAuthorisationService->getById($id);
        $user = $this->userService->getUserByIdWithRelations($travelAuthorisation->applicant_id);
        $this->approvalService->formattedData($travelAuthorisation);

        return Inertia::render('TravelAuthorisation/Show')->with(['user' => $user, 'travelAuthorisation' => $travelAuthorisation, 'loggedRole' => $this->loggedRole]);
    }

    public function create()
    {
        return Inertia::render('TravelAuthorisation/Create');
    }

    public function store(Request $request)
    {
        $this->travelAuthorisationService->store($request);
        return redirect()->route('travelAuthorisations.index');
    }

    public function approve($id)
    {
        $travelAuthorisation = $this->travelAuthorisationService->getById($id);
        if ($this->loggedPosition === PositionEnum::SENIOR)
        $this->approvalService->approval($travelAuthorisation, $travelAuthorisation->supervisor_id, RoleEnum::STAFF, PositionEnum::SENIOR, ApprovalStatusEnum::SUPERVISOR_PENDING, ApprovalStatusEnum::MANAGER_PENDING);

        else if ($this->loggedPosition === PositionEnum::MANAGER)
        $this->approvalService->approval($travelAuthorisation, $travelAuthorisation->supervisor_id, RoleEnum::MANAGER, PositionEnum::MANAGER, ApprovalStatusEnum::MANAGER_PENDING, ApprovalStatusEnum::FINANCE_PENDING);

        else if ($this->loggedPosition === PositionEnum::FINANCE)
        $this->approvalService->approval($travelAuthorisation, $travelAuthorisation->supervisor_id, RoleEnum::MANAGER, PositionEnum::MANAGER, ApprovalStatusEnum::FINANCE_PENDING, ApprovalStatusEnum::APPROVED);

        return Inertia::location(route('travelAuthorisations.index'));
    }

    public function disapprove($id)
    {
        $travelAuthorisation = $this->travelAuthorisationService->getById($id);
        if ($this->loggedPosition === PositionEnum::SENIOR)
        $this->approvalService->approval($travelAuthorisation, $travelAuthorisation->supervisor_id, RoleEnum::STAFF, PositionEnum::SENIOR, ApprovalStatusEnum::MANAGER_PENDING, ApprovalStatusEnum::SUPERVISOR_PENDING);

        else if ($this->loggedPosition === PositionEnum::MANAGER)
        $this->approvalService->approval($travelAuthorisation, $travelAuthorisation->supervisor_id, RoleEnum::MANAGER, PositionEnum::MANAGER, ApprovalStatusEnum::FINANCE_PENDING, ApprovalStatusEnum::MANAGER_PENDING);

        else if ($this->loggedPosition === PositionEnum::FINANCE)
        $this->approvalService->approval($travelAuthorisation, $travelAuthorisation->supervisor_id, RoleEnum::MANAGER, PositionEnum::FINANCE, ApprovalStatusEnum::APPROVED, ApprovalStatusEnum::FINANCE_PENDING);

        return Inertia::location(route('travelAuthorisations.index'));
    }

    public function rejectPage($id)
    {
        $travelAuthorisation = $this->travelAuthorisationService->getById($id);

        $this->approvalService->formattedData($travelAuthorisation);
        return Inertia::render('TravelAuthorisation/Reject')->with(['travelAuthorisation' => $travelAuthorisation]);
    }

    public function reject(Request $request, $id)
    {
        $travelAuthorisation = $this->travelAuthorisationService->getById($id);
        if ($this->loggedPosition === PositionEnum::SENIOR)
        $this->approvalService->rejection($travelAuthorisation, $travelAuthorisation->supervisor_id, RoleEnum::STAFF, PositionEnum::SENIOR, ApprovalStatusEnum::SUPERVISOR_PENDING, ApprovalStatusEnum::SUPERVISOR_REJECTED, $request->reasons);

        else if ($this->loggedPosition === PositionEnum::MANAGER)
        $this->approvalService->rejection($travelAuthorisation, $travelAuthorisation->manager_id, RoleEnum::MANAGER, PositionEnum::MANAGER, ApprovalStatusEnum::MANAGER_PENDING, ApprovalStatusEnum::MANAGER_REJECTED, $request->reasons);

        else if ($this->loggedPosition === PositionEnum::FINANCE)
        $this->approvalService->rejection($travelAuthorisation, $travelAuthorisation->manager_id, RoleEnum::MANAGER, PositionEnum::FINANCE, ApprovalStatusEnum::FINANCE_PENDING, ApprovalStatusEnum::FINANCE_REJECTED, $request->reasons);

        return Inertia::location(route('travelAuthorisations.index'));
    }

    public function unreject($id)
    {
        $travelAuthorisation = $this->travelAuthorisationService->getById($id);
        if ($this->loggedPosition === PositionEnum::SENIOR)
        $this->approvalService->rejection($travelAuthorisation, $travelAuthorisation->supervisor_id, RoleEnum::STAFF, PositionEnum::SENIOR, ApprovalStatusEnum::SUPERVISOR_REJECTED, ApprovalStatusEnum::SUPERVISOR_PENDING, null);

        else if ($this->loggedPosition === PositionEnum::MANAGER)
        $this->approvalService->rejection($travelAuthorisation, $travelAuthorisation->manager_id, RoleEnum::MANAGER, PositionEnum::MANAGER, ApprovalStatusEnum::MANAGER_REJECTED, ApprovalStatusEnum::MANAGER_PENDING, null);

        else if ($this->loggedPosition === PositionEnum::FINANCE)
        $this->approvalService->rejection($travelAuthorisation, $travelAuthorisation->manager_id, RoleEnum::MANAGER, PositionEnum::FINANCE, ApprovalStatusEnum::FINANCE_REJECTED, ApprovalStatusEnum::FINANCE_PENDING, null);

        return Inertia::location(route('travelAuthorisations.index'));
    }
}
