<?php

namespace App\Http\Controllers;

use App\Enums\ApprovalStatusEnum;
use App\Enums\PositionEnum;
use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Services\ApprovalService;
use App\Http\Services\NotificationService;
use App\Http\Services\TravelAuthorisationService;
use App\Http\Services\UserService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TravelAuthorisationController extends Controller
{
    private $travelAuthorisationService, $approvalService, $userService, $loggedPosition, $notificationService;

    public function __construct(TravelAuthorisationService $travelAuthorisationService, ApprovalService $approvalService, UserService $userService, NotificationService $notificationService)
    {
        $this->travelAuthorisationService = $travelAuthorisationService;
        $this->approvalService = $approvalService;
        $this->userService = $userService;
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        $userId = auth()->id();
        $travelAuthorisations = $this->travelAuthorisationService->getAll($request);
        foreach ($travelAuthorisations as $travelAuthorisation) {
            $this->approvalService->formattedData($travelAuthorisation, $userId);
        }

        return Inertia::render('TravelAuthorisation/Index')->with(['travelAuthorisations' => $travelAuthorisations]);
    }

    public function show($id)
    {
        $travelAuthorisation = $this->travelAuthorisationService->getByIdWithAccomodation($id);
        $user = $this->userService->getUserByIdWithRelations($travelAuthorisation->applicant_id);
        $finance = $this->userService->getUserById($travelAuthorisation->finance_id);

        $this->approvalService->formattedData($travelAuthorisation);

        return Inertia::render('TravelAuthorisation/Show')->with(['user' => $user, 'travelAuthorisation' => $travelAuthorisation, 'finance' => $finance]);
    }

    public function create()
    {
        $finances = $this->userService->getFinances();
        return Inertia::render('TravelAuthorisation/Create', ['finances' => $finances]);
    }

    public function store(Request $request)
    {
        $this->travelAuthorisationService->store($request);
        return redirect()->route('travelAuthorisations.index');
    }

    public function approve($id)
    {
        $travelAuthorisation = $this->travelAuthorisationService->getById($id);
        $applicant = $this->userService->getUserByIdWithRelations($travelAuthorisation->applicant_id);
        $officer = $this->userService->getUserById($travelAuthorisation->officer_id);
        $HR = $this->userService->getUserById($travelAuthorisation->manager_id);
        $finance = $this->userService->getUserById($travelAuthorisation->finance_id);

        if ($this->loggedPosition === PositionEnum::SENIOR) {
            $this->approvalService->approval($travelAuthorisation, $travelAuthorisation->officer_id, RoleEnum::STAFF, PositionEnum::SENIOR, ApprovalStatusEnum::SUPERVISOR_PENDING, ApprovalStatusEnum::MANAGER_PENDING);
            $this->notificationService->sendMail($officer, $applicant, ApprovalStatusEnum::MANAGER_PENDING, 'Travel Authorisation');
            $this->notificationService->sendMail($applicant, $HR, ApprovalStatusEnum::MANAGER_PENDING, 'Travel Authorisation');
        }

        else if ($this->loggedPosition === PositionEnum::HR) {
            $this->approvalService->approval($travelAuthorisation, $travelAuthorisation->manager_id, RoleEnum::HR, PositionEnum::HR, ApprovalStatusEnum::MANAGER_PENDING, ApprovalStatusEnum::FINANCE_PENDING);
            $this->notificationService->sendMail($HR, $applicant, ApprovalStatusEnum::FINANCE_PENDING, 'Travel Authorisation');
            $this->notificationService->sendMail($applicant, $finance, ApprovalStatusEnum::FINANCE_PENDING, 'Travel Authorisation');
        }

        else if ($this->loggedPosition === PositionEnum::FINANCE) {
            $this->approvalService->approval($travelAuthorisation, $travelAuthorisation->finance_id, RoleEnum::STAFF, PositionEnum::FINANCE, ApprovalStatusEnum::FINANCE_PENDING, ApprovalStatusEnum::APPROVED);
            $this->notificationService->sendMail($finance, $applicant, ApprovalStatusEnum::APPROVED, 'Travel Authorisation');
        }

        return Inertia::location(route('travelAuthorisations.index'));
    }

    public function disapprove($id)
    {
        $travelAuthorisation = $this->travelAuthorisationService->getById($id);
        if ($this->loggedPosition === PositionEnum::SENIOR)
        $this->approvalService->approval($travelAuthorisation, $travelAuthorisation->officer_id, RoleEnum::STAFF, PositionEnum::SENIOR, ApprovalStatusEnum::MANAGER_PENDING, ApprovalStatusEnum::SUPERVISOR_PENDING);

        else if ($this->loggedPosition === PositionEnum::HR)
        $this->approvalService->approval($travelAuthorisation, $travelAuthorisation->manager_id, RoleEnum::HR, PositionEnum::HR, ApprovalStatusEnum::FINANCE_PENDING, ApprovalStatusEnum::MANAGER_PENDING);

        else if ($this->loggedPosition === PositionEnum::FINANCE)
        $this->approvalService->approval($travelAuthorisation, $travelAuthorisation->finance_id, RoleEnum::STAFF, PositionEnum::FINANCE, ApprovalStatusEnum::APPROVED, ApprovalStatusEnum::FINANCE_PENDING);

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
        $applicant = $this->userService->getUserByIdWithRelations($travelAuthorisation->applicant_id);

        if ($this->loggedPosition === PositionEnum::SENIOR) {
            $officer = $this->userService->getUserById($travelAuthorisation->officer_id);
            $this->approvalService->rejection($travelAuthorisation, $travelAuthorisation->officer_id, RoleEnum::STAFF, PositionEnum::SENIOR, ApprovalStatusEnum::SUPERVISOR_PENDING, ApprovalStatusEnum::SUPERVISOR_REJECTED, $request->reasons);
            $this->notificationService->sendMail($officer, $applicant, ApprovalStatusEnum::SUPERVISOR_REJECTED, 'Travel Authorisation');
        }

        else if ($this->loggedPosition === PositionEnum::HR) {
            $HR = $this->userService->getUserById($travelAuthorisation->manager_id);
            $this->approvalService->rejection($travelAuthorisation, $travelAuthorisation->manager_id, RoleEnum::HR, PositionEnum::HR, ApprovalStatusEnum::MANAGER_PENDING, ApprovalStatusEnum::MANAGER_REJECTED, $request->reasons);
            $this->notificationService->sendMail($HR, $applicant, ApprovalStatusEnum::MANAGER_REJECTED, 'Travel Authorisation');
        }

        else if ($this->loggedPosition === PositionEnum::FINANCE) {
            $finance = $this->userService->getUserById($travelAuthorisation->finance_id);
            $this->approvalService->rejection($travelAuthorisation, $travelAuthorisation->finance_id, RoleEnum::STAFF, PositionEnum::FINANCE, ApprovalStatusEnum::FINANCE_PENDING, ApprovalStatusEnum::FINANCE_REJECTED, $request->reasons);
            $this->notificationService->sendMail($finance, $applicant, ApprovalStatusEnum::FINANCE_REJECTED, 'Travel Authorisation');
        }

        return Inertia::location(route('travelAuthorisations.index'));
    }

    public function unreject($id)
    {
        $travelAuthorisation = $this->travelAuthorisationService->getById($id);
        if ($this->loggedPosition === PositionEnum::SENIOR)
        $this->approvalService->rejection($travelAuthorisation, $travelAuthorisation->officer_id, RoleEnum::STAFF, PositionEnum::SENIOR, ApprovalStatusEnum::SUPERVISOR_REJECTED, ApprovalStatusEnum::SUPERVISOR_PENDING, null);

        else if ($this->loggedPosition === PositionEnum::HR)
        $this->approvalService->rejection($travelAuthorisation, $travelAuthorisation->manager_id, RoleEnum::HR, PositionEnum::HR, ApprovalStatusEnum::MANAGER_REJECTED, ApprovalStatusEnum::MANAGER_PENDING, null);

        else if ($this->loggedPosition === PositionEnum::FINANCE)
        $this->approvalService->rejection($travelAuthorisation, $travelAuthorisation->finance_id, RoleEnum::STAFF, PositionEnum::FINANCE, ApprovalStatusEnum::FINANCE_REJECTED, ApprovalStatusEnum::FINANCE_PENDING, null);

        return Inertia::location(route('travelAuthorisations.index'));
    }
}
