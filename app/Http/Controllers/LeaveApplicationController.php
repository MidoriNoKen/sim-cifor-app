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

    public function index(Request $request)
    {
        $userId = auth()->id();
        $leaveApplications = $this->leaveApplicationService->getByIdWithPagination($request, $userId);

        if ($this->userService->getByIdWithRelations($userId)->position === RoleEnum::ADMIN)
            $leaveApplications = $this->leaveApplicationService->getAll($request);
        else {
            foreach ($leaveApplications as $leaveApplication) {
                $this->approvalService->formattedData($leaveApplication, $userId);
            }
        }

        return Inertia::render('LeaveApplication/Index')->with(['leaveApplications' => $leaveApplications, 'positions' => PositionEnum::POSITIONS, 'roles' => RoleEnum::ROLES]);
    }

    public function show($id)
    {
        $leaveApplication = $this->leaveApplicationService->getById($id);
        $user = $this->userService->getByIdWithRelations($leaveApplication->applicant_id);
        $this->approvalService->formattedData($leaveApplication, $user->id);

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
            $applicant = $this->userService->getByIdWithRelations($result->applicant_id);
            $officer = $this->userService->getByIdWithRelations($applicant->officer_id);
            $this->notificationService->sendMail($officer, $applicant, ApprovalStatusEnum::NEW, 'Leave Application');
            $this->notificationService->sendMail($applicant, $officer, ApprovalStatusEnum::OFFICER_PENDING, 'Leave Application');
        }
        return redirect()->route('leaveApplications.index');
    }

    public function approve($id)
    {
        $leaveApplication = $this->leaveApplicationService->getById($id);
        $applicant = $this->userService->getByIdWithRelations($leaveApplication->applicant_id);
        $officer = $this->userService->getByIdWithRelations($leaveApplication->officer_id);
        $HR = $this->userService->getByIdWithRelations($leaveApplication->hrManager_id);
        $position = auth()->user()->position;

        if ($position === PositionEnum::OFFICER) {
            $this->approvalService->approval($leaveApplication, $leaveApplication->officer_id, RoleEnum::EMPLOYEE, PositionEnum::OFFICER, ApprovalStatusEnum::OFFICER_PENDING, ApprovalStatusEnum::HR_PENDING);
            $this->notificationService->sendMail($officer, $applicant, ApprovalStatusEnum::HR_PENDING, 'Leave Application');
            $this->notificationService->sendMail($applicant, $HR, ApprovalStatusEnum::HR_PENDING, 'Leave Application');
        }

        else if ($position === PositionEnum::HR) {
            $this->approvalService->approval($leaveApplication, $leaveApplication->manager_id, RoleEnum::MANAGER, PositionEnum::HR, ApprovalStatusEnum::HR_PENDING, ApprovalStatusEnum::APPROVED);
            $this->notificationService->sendMail($HR, $applicant, ApprovalStatusEnum::APPROVED, 'Leave Application');
        }

        return Inertia::location(route('leaveApplications.index'));
    }

    public function disapprove($id)
    {
        $leaveApplication = $this->leaveApplicationService->getById($id);
        $position = auth()->user()->position;

        if ($position === PositionEnum::OFFICER)
        $this->approvalService->approval($leaveApplication, $leaveApplication->officer_id, RoleEnum::EMPLOYEE, PositionEnum::OFFICER, ApprovalStatusEnum::HR_PENDING, ApprovalStatusEnum::OFFICER_PENDING);

        else if ($position === PositionEnum::HR)
        $this->approvalService->approval($leaveApplication, $leaveApplication->manager_id, RoleEnum::MANAGER, PositionEnum::HR, ApprovalStatusEnum::APPROVED, ApprovalStatusEnum::HR_PENDING);

        return Inertia::location(route('leaveApplications.index'));
    }

    public function rejectPage($id)
    {
        $leaveApplication = $this->leaveApplicationService->getById($id);

        $this->approvalService->formattedData($leaveApplication, $id);
        return Inertia::render('LeaveApplication/Reject')->with(['leaveApplication' => $leaveApplication]);
    }

    public function reject(Request $request, $id)
    {
        $leaveApplication = $this->leaveApplicationService->getById($id);
        $applicant = $this->userService->getByIdWithRelations($leaveApplication->applicant_id);
        $position = auth()->user()->position;

        if ($position === PositionEnum::OFFICER){
            $officer = $this->userService->getByIdWithRelations($applicant->officer_id);
            $this->approvalService->rejection($leaveApplication, $leaveApplication->officer_id, RoleEnum::EMPLOYEE, PositionEnum::OFFICER, ApprovalStatusEnum::OFFICER_PENDING, ApprovalStatusEnum::OFFICER_REJECTED, $request->reasons);
            $this->notificationService->sendMail($officer, $applicant, ApprovalStatusEnum::OFFICER_REJECTED, 'Leave Application');
        }

        else if ($position === PositionEnum::HR){
            $HR = $this->userService->getByIdWithRelations($applicant->manager_id);
            $this->approvalService->rejection($leaveApplication, $leaveApplication->manager_id, RoleEnum::MANAGER, PositionEnum::HR, ApprovalStatusEnum::HR_PENDING, ApprovalStatusEnum::HR_REJECTED, $request->reasons);
            $this->notificationService->sendMail($HR, $applicant, ApprovalStatusEnum::HR_REJECTED, 'Leave Application');
        }

        return Inertia::location(route('leaveApplications.index'));
    }

    public function unreject($id)
    {
        $leaveApplication = $this->leaveApplicationService->getById($id);
        $position = auth()->user()->position;

        if ($position === PositionEnum::OFFICER)
        $this->approvalService->rejection($leaveApplication, $leaveApplication->officer_id, RoleEnum::EMPLOYEE, PositionEnum::OFFICER, ApprovalStatusEnum::OFFICER_REJECTED, ApprovalStatusEnum::OFFICER_PENDING, null);

        else if ($position === PositionEnum::HR)
        $this->approvalService->rejection($leaveApplication, $leaveApplication->manager_id, RoleEnum::MANAGER, PositionEnum::HR, ApprovalStatusEnum::HR_REJECTED, ApprovalStatusEnum::HR_PENDING, null);

        return Inertia::location(route('leaveApplications.index'));
    }
}