<?php

namespace App\Http\Controllers;

use App\Enums\ApprovalStatusEnum;
use App\Enums\PositionEnum;
use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Services\ApprovalService;
use App\Http\Services\LeaveApplicationService;
use App\Http\Services\NotificationService;
use App\Http\Services\UserService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LeaveApplicationController extends Controller
{
    private $leaveApplicationService, $approvalService, $userService, $notificationService;

    public function __construct(LeaveApplicationService $leaveApplicationService, ApprovalService $approvalService, UserService $userService, NotificationService $notificationService)
    {
        $this->leaveApplicationService = $leaveApplicationService;
        $this->approvalService = $approvalService;
        $this->userService = $userService;
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        $leaveApplications = $this->leaveApplicationService->getById(auth()->id());

        foreach ($leaveApplications as $leaveApplication) {
            $this->approvalService->formattedData($leaveApplication);
        }

        return Inertia::render('LeaveApplication/Index')->with(['leaveApplications' => $leaveApplications]);
    }

    public function show($id)
    {
        $leaveApplication = $this->leaveApplicationService->getById($id);
        $user = $this->userService->getUserByIdWithRelations($leaveApplication->applicant_id);
        $this->approvalService->formattedData($leaveApplication);

        return Inertia::render('LeaveApplication/Show')->with(['user' => $user, 'leaveApplication' => $leaveApplication]);
    }

    public function create()
    {
        return Inertia::render('LeaveApplication/Create');
    }

    public function store(Request $request)
    {
        $result = $this->leaveApplicationService->store($request);
        if ($result != null) {
            $applicant = $this->userService->getUserByIdWithRelations($result->applicant_id);
            $officer = $this->userService->getUserById($applicant->officer_id);
            $this->notificationService->sendMail($officer, $applicant, ApprovalStatusEnum::NEW, 'Leave Application');
            $this->notificationService->sendMail($applicant, $officer, ApprovalStatusEnum::SUPERVISOR_PENDING, 'Leave Application');
        }
        return redirect()->route('leaveApplications.index');
    }

    public function approve($id)
    {
        $leaveApplication = $this->leaveApplicationService->getById($id);
        $applicant = $this->userService->getUserByIdWithRelations($leaveApplication->applicant_id);
        $officer = $this->userService->getUserById($leaveApplication->officer_id);
        $HR = $this->userService->getUserById($leaveApplication->manager_id);

        if ($this->loggedPosition === PositionEnum::SENIOR){
            $this->approvalService->approval($leaveApplication, $leaveApplication->officer_id, RoleEnum::STAFF, PositionEnum::SENIOR, ApprovalStatusEnum::SUPERVISOR_PENDING, ApprovalStatusEnum::MANAGER_PENDING);
            $this->notificationService->sendMail($officer, $applicant, ApprovalStatusEnum::MANAGER_PENDING, 'Leave Application');
            $this->notificationService->sendMail($applicant, $HR, ApprovalStatusEnum::MANAGER_PENDING, 'Leave Application');
        }

        else if ($this->loggedPosition === PositionEnum::HR) {
            $this->approvalService->approval($leaveApplication, $leaveApplication->manager_id, RoleEnum::HR, PositionEnum::HR, ApprovalStatusEnum::MANAGER_PENDING, ApprovalStatusEnum::APPROVED);
            $this->notificationService->sendMail($HR, $applicant, ApprovalStatusEnum::APPROVED, 'Leave Application');
        }

        return Inertia::location(route('leaveApplications.index'));
    }

    public function disapprove($id)
    {
        $leaveApplication = $this->leaveApplicationService->getById($id);
        if ($this->loggedPosition === PositionEnum::SENIOR)
        $this->approvalService->approval($leaveApplication, $leaveApplication->officer_id, RoleEnum::STAFF, PositionEnum::SENIOR, ApprovalStatusEnum::MANAGER_PENDING, ApprovalStatusEnum::SUPERVISOR_PENDING);

        else if ($this->loggedPosition === PositionEnum::HR)
        $this->approvalService->approval($leaveApplication, $leaveApplication->manager_id, RoleEnum::HR, PositionEnum::HR, ApprovalStatusEnum::APPROVED, ApprovalStatusEnum::MANAGER_PENDING);

        return Inertia::location(route('leaveApplications.index'));
    }

    public function rejectPage($id)
    {
        $leaveApplication = $this->leaveApplicationService->getById($id);

        $this->approvalService->formattedData($leaveApplication);
        return Inertia::render('LeaveApplication/Reject')->with(['leaveApplication' => $leaveApplication]);
    }

    public function reject(Request $request, $id)
    {
        $leaveApplication = $this->leaveApplicationService->getById($id);
        $applicant = $this->userService->getUserByIdWithRelations($leaveApplication->applicant_id);

        if ($this->loggedPosition === PositionEnum::SENIOR){
            $officer = $this->userService->getUserById($applicant->officer_id);
            $this->approvalService->rejection($leaveApplication, $leaveApplication->officer_id, RoleEnum::STAFF, PositionEnum::SENIOR, ApprovalStatusEnum::SUPERVISOR_PENDING, ApprovalStatusEnum::SUPERVISOR_REJECTED, $request->reasons);
            $this->notificationService->sendMail($officer, $applicant, ApprovalStatusEnum::SUPERVISOR_REJECTED, 'Leave Application');
        }

        else if ($this->loggedPosition === PositionEnum::HR){
            $HR = $this->userService->getUserById($applicant->manager_id);
            $this->approvalService->rejection($leaveApplication, $leaveApplication->manager_id, RoleEnum::HR, PositionEnum::HR, ApprovalStatusEnum::MANAGER_PENDING, ApprovalStatusEnum::MANAGER_REJECTED, $request->reasons);
            $this->notificationService->sendMail($HR, $applicant, ApprovalStatusEnum::MANAGER_REJECTED, 'Leave Application');
        }

        return Inertia::location(route('leaveApplications.index'));
    }

    public function unreject($id)
    {
        $leaveApplication = $this->leaveApplicationService->getById($id);
        if ($this->loggedPosition === PositionEnum::SENIOR)
        $this->approvalService->rejection($leaveApplication, $leaveApplication->officer_id, RoleEnum::STAFF, PositionEnum::SENIOR, ApprovalStatusEnum::SUPERVISOR_REJECTED, ApprovalStatusEnum::SUPERVISOR_PENDING, null);

        else if ($this->loggedPosition === PositionEnum::HR)
        $this->approvalService->rejection($leaveApplication, $leaveApplication->manager_id, RoleEnum::HR, PositionEnum::HR, ApprovalStatusEnum::MANAGER_REJECTED, ApprovalStatusEnum::MANAGER_PENDING, null);

        return Inertia::location(route('leaveApplications.index'));
    }
}