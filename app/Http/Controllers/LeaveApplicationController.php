<?php

namespace App\Http\Controllers;

use App\Enums\ApprovalStatusEnum;
use App\Enums\PositionEnum;
use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Services\LeaveApplicationService;
use App\Http\Services\UserService;
use App\Models\LeaveApplication;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LeaveApplicationController extends Controller
{
    private $leaveApplicationService;
    private $userService;

    public function __construct(LeaveApplicationService $leaveApplicationService, UserService $userService)
    {
        $this->leaveApplicationService = $leaveApplicationService;
        $this->userService = $userService;
    }

    public function index()
    {
        $loggedId = $this->userService->getLoggedId();
        $loggedRole = $this->userService->getLoggedRole();
        $leaveApplications = $this->leaveApplicationService->getAllByLoggedPosition();

        $leaveApplications->each(function ($leaveApplication) use ($loggedId) {
            $this->leaveApplicationService->formattedData($leaveApplication, $loggedId);
        });

        return Inertia::render('LeaveApplication/Index')->with(['leaveApplications' => $leaveApplications, 'loggedRole' => $loggedRole]);
    }

    public function show($id)
    {
        $loggedId = $this->userService->getLoggedId();
        $loggedRole = $this->userService->getLoggedRole();
        $leaveApplication = $this->leaveApplicationService->getById($id);
        $user = $this->userService->getUserById($leaveApplication->applicant_id);
        $checkAccess = $this->leaveApplicationService->checkAccess($leaveApplication, $loggedId);

        if (!$checkAccess) {
            return redirect()->back()->withErrors(['error', $checkAccess]);
        }

        $this->leaveApplicationService->formattedData($leaveApplication, $loggedId);

        return Inertia::render('LeaveApplication/Show')->with(['user' => $user, 'leaveApplication' => $leaveApplication, 'loggedRole' => $loggedRole]);
    }

    public function create()
    {
        return Inertia::render('LeaveApplication/Create');
    }

    public function store(Request $request)
    {
        $this->leaveApplicationService->store($request);
        return redirect()->route('leaveApplications.index');
    }

    public function approveBySupervisor($id)
    {
        $loggedId = $this->userService->getLoggedId();
        $loggedUser = $this->userService->getLoggedUser();
        $leaveApplication = $this->leaveApplicationService->getById($id);

        $checkAccess = $this->leaveApplicationService->checkAccess($leaveApplication, $loggedId);

        if (!$checkAccess) {
            return redirect()->back()->withErrors(['error', $checkAccess]);
        }

        $result = $this->leaveApplicationService->approval($leaveApplication, $loggedUser, RoleEnum::STAFF, PositionEnum::SENIOR, ApprovalStatusEnum::SUPERVISOR_PENDING, ApprovalStatusEnum::MANAGER_PENDING);

        if ($result) {
            return Inertia::location(route('leaveApplications.index'));
        }
    }

    public function disapproveBySupervisor($id)
    {
        $loggedId = $this->userService->getLoggedId();
        $loggedUser = $this->userService->getLoggedUser();
        $leaveApplication = $this->leaveApplicationService->getById($id);

        $checkAccess = $this->leaveApplicationService->checkAccess($leaveApplication, $loggedId);

        if (!$checkAccess) {
            return redirect()->back()->withErrors(['error', $checkAccess]);
        }

        $result = $this->leaveApplicationService->approval($leaveApplication, $loggedUser, RoleEnum::STAFF, PositionEnum::SENIOR, ApprovalStatusEnum::MANAGER_PENDING, ApprovalStatusEnum::SUPERVISOR_PENDING);

        if ($result) {
            return Inertia::location(route('leaveApplications.index'));
        }
    }


    public function approveByManager($id)
    {
        $loggedId = $this->userService->getLoggedId();
        $loggedUser = $this->userService->getLoggedUser();
        $leaveApplication = $this->leaveApplicationService->getById($id);

        $checkAccess = $this->leaveApplicationService->checkAccess($leaveApplication, $loggedId);

        if (!$checkAccess) {
            return redirect()->back()->withErrors(['error', $checkAccess]);
        }

        $result = $this->leaveApplicationService->approval($leaveApplication, $loggedUser, RoleEnum::MANAGER, PositionEnum::MANAGER, ApprovalStatusEnum::MANAGER_PENDING, ApprovalStatusEnum::APPROVED);

        if ($result) {
            return Inertia::location(route('leaveApplications.index'));
        }
    }

    public function disapproveByManager($id)
    {
        $loggedId = $this->userService->getLoggedId();
        $loggedUser = $this->userService->getLoggedUser();
        $leaveApplication = $this->leaveApplicationService->getById($id);

        $checkAccess = $this->leaveApplicationService->checkAccess($leaveApplication, $loggedId);

        if (!$checkAccess) {
            return redirect()->back()->withErrors(['error', $checkAccess]);
        }

        $result = $this->leaveApplicationService->approval($leaveApplication, $loggedUser, RoleEnum::MANAGER, PositionEnum::MANAGER, ApprovalStatusEnum::APPROVED, ApprovalStatusEnum::MANAGER_PENDING);

        if ($result) {
            return Inertia::location(route('leaveApplications.index'));
        }
    }

    public function rejectPage($id)
    {
        $loggedId = $this->userService->getLoggedId();
        $leaveApplication = $this->leaveApplicationService->getById($id);

        $checkAccess = $this->leaveApplicationService->checkAccess($leaveApplication, $loggedId);

        if (!$checkAccess) {
            return redirect()->back()->withErrors(['error', $checkAccess]);
        }

        $this->leaveApplicationService->formattedData($leaveApplication, $loggedId);
        return Inertia::render('LeaveApplication/Reject')->with(['leaveApplication' => $leaveApplication]);
    }

    public function reject(Request $request, $id)
    {
        $loggedRole = $this->userService->getLoggedRole();
        $loggedPosition = $this->userService->getLoggedPosition();
        $leaveApplication = $this->leaveApplicationService->getById($id);

        $this->leaveApplicationService->reject($loggedRole, $loggedPosition, $leaveApplication, $request);
        return Inertia::location(route('leaveApplications.index'));
    }

    public function unreject($id)
    {
        $loggedRole = $this->userService->getLoggedRole();
        $loggedPosition = $this->userService->getLoggedPosition();
        $leaveApplication = $this->leaveApplicationService->getById($id);

        $this->leaveApplicationService->unreject($loggedRole, $loggedPosition, $leaveApplication);
        return Inertia::location(route('leaveApplications.index'));
    }
}
