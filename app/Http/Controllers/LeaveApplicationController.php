<?php

namespace App\Http\Controllers;

use App\Enums\ApprovalStatusEnum;
use App\Enums\PositionEnum;
use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Models\LeaveApplication;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class LeaveApplicationController extends Controller
{
    public function index()
    {
        if (Auth::user()->role->name === RoleEnum::STAFF && Auth::user()->position === PositionEnum::JUNIOR)
            $leaveApplications = LeaveApplication::where("applicant_id", auth()->id())->get();
        else if (Auth::user()->role->name === RoleEnum::STAFF && Auth::user()->position === PositionEnum::SENIOR)
            $leaveApplications = LeaveApplication::where("supervisor_id", auth()->id())->orWhere("applicant_id", auth()->id())->get();
        else if (Auth::user()->role->name === RoleEnum::MANAGER && Auth::user()->position === PositionEnum::MANAGER)
            $leaveApplications = LeaveApplication::where("manager_id", auth()->id())->orWhere("applicant_id", auth()->id())->get();
        else
            $leaveApplications = LeaveApplication::all();

        $leaveApplications->each(function ($leaveApplication) {
            $leaveApplication->applicant = User::find($leaveApplication->applicant_id)->name;
            $leaveApplication->supervisor = $leaveApplication->supervisor_id ? User::find($leaveApplication->supervisor_id)->name : null;
            $leaveApplication->manager = $leaveApplication->manager_id ? User::find($leaveApplication->manager_id)->name : null;

            if (Auth::user()->id == $leaveApplication->supervisor_id) {
                $leaveApplication->isSupervisor = true;
            } else if (Auth::user()->id == $leaveApplication->manager_id) {
                $leaveApplication->isManager = true;
            } else {
                $leaveApplication->isManager = false;
                $leaveApplication->isSupervisor = false;
            }
        });

        $loggedRole = Auth::user()->role->name;
        return Inertia::render('LeaveApplication/Index')->with(['leaveApplications' => $leaveApplications, 'loggedRole' => $loggedRole]);
    }

    public function show($id)
    {
        $leaveApplication = LeaveApplication::find($id);
        $loggedRole = Auth::user()->role->name;
        $user = User::where('id', $leaveApplication->applicant_id)->first();

        $leaveApplication->applicant = User::find($leaveApplication->applicant_id)->name;
        $user->supervisor = $leaveApplication->supervisor_id ? User::find($leaveApplication->supervisor_id)->name : null;
        $user->manager = $leaveApplication->manager_id ? User::find($leaveApplication->manager_id)->name : null;

        return Inertia::render('LeaveApplication/Show')->with(['user' => $user, 'leaveApplication' => $leaveApplication, 'loggedRole' => $loggedRole]);
    }

    public function create()
    {
        $loggedRole = Auth::user()->role->name;
        return Inertia::render('LeaveApplication/Create')->with(['loggedRole' => $loggedRole]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'leave_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $leaveApplication = LeaveApplication::create([
            'applicant_id' => auth()->id(),
            'status' => ApprovalStatusEnum::SUPERVISOR_PENDING,
            'supervisor_id' => auth()->user()->supervisor_id,
            'supervisor_reject_reasons' => null,
            'manager_id' => auth()->user()->manager_id,
            'manager_reject_reasons' => null,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'day_accumulation' => date_diff(date_create($request->start_date), date_create($request->end_date))->format('%a') + 1,
            'leave_type' => $request->leave_type,
        ]);

        event(new Registered($leaveApplication));

        return Redirect::route('leaveApplications.index');
    }

    public function approveBySupervisor($id)
    {
        $leaveApplication = LeaveApplication::find($id);
        if (Auth::user()->role->name == RoleEnum::STAFF && Auth::user()->position == PositionEnum::SENIOR && Auth::user()->id == $leaveApplication->supervisor_id) {
            if ($leaveApplication->status != ApprovalStatusEnum::SUPERVISOR_PENDING)
                return redirect()->back()->withErrors(['This leave application has been approved or rejected.']);
            $leaveApplication->status = ApprovalStatusEnum::MANAGER_PENDING;
            $leaveApplication->save();

            return Inertia::location(route('leaveApplications.index'));
        }
        return redirect()->back()->withErrors(['error', 'You are not allowed to approve this leave application.']);
    }

    public function disapproveBySupervisor($id)
    {
        $leaveApplication = LeaveApplication::find($id);
        if (Auth::user()->role->name == RoleEnum::STAFF && Auth::user()->position == PositionEnum::SENIOR && Auth::user()->id == $leaveApplication->supervisor_id) {
            if ($leaveApplication->status != ApprovalStatusEnum::MANAGER_PENDING)
                return redirect()->back()->withErrors(['This leave application has been approved or rejected.']);
            $leaveApplication->status = ApprovalStatusEnum::SUPERVISOR_PENDING;
            $leaveApplication->save();

            return Inertia::location(route('leaveApplications.index'));
        }
        return redirect()->back()->withErrors(['error', 'You are not allowed to approve this leave application.']);
    }

    public function approveByManager($id)
    {
        $leaveApplication = LeaveApplication::find($id);
        if (Auth::user()->role->name == RoleEnum::MANAGER && Auth::user()->position == PositionEnum::MANAGER && Auth::user()->id == $leaveApplication->manager_id) {
            if ($leaveApplication->status != ApprovalStatusEnum::MANAGER_PENDING)
                return redirect()->back()->withErrors(['This leave application has been approved or rejected.']);
            $leaveApplication->status = ApprovalStatusEnum::APPROVED;
            $leaveApplication->save();

            return Inertia::location(route('leaveApplications.index'));
        }
        return redirect()->back()->withErrors(['error', 'You are not allowed to approve this leave application.']);
    }

    public function disapproveByManager($id)
    {
        $leaveApplication = LeaveApplication::find($id);
        if (Auth::user()->role->name == RoleEnum::MANAGER && Auth::user()->position == PositionEnum::MANAGER && Auth::user()->id == $leaveApplication->manager_id) {
            if ($leaveApplication->status != ApprovalStatusEnum::APPROVED)
                return redirect()->back()->withErrors(['This leave application has been approved or rejected.']);
            $leaveApplication->status = ApprovalStatusEnum::MANAGER_PENDING;
            $leaveApplication->save();

            return Inertia::location(route('leaveApplications.index'));
        }
        return redirect()->back()->withErrors(['error', 'You are not allowed to approve this leave application.']);
    }

    public function reject($id)
    {
        $leaveApplication = LeaveApplication::find($id);

        if (Auth::user()->id == $leaveApplication->supervisor_id) {
            $leaveApplication->isSupervisor = true;
        } else if (Auth::user()->id == $leaveApplication->manager_id) {
            $leaveApplication->isManager = true;
        } else {
            $leaveApplication->isManager = false;
            $leaveApplication->isSupervisor = false;
        }

        return Inertia::render('LeaveApplication/Reject')->with(['leaveApplication' => $leaveApplication]);
    }

    public function unreject($id)
    {
        $leaveApplication = LeaveApplication::find($id);
        if (Auth::user()->role->name == RoleEnum::MANAGER && Auth::user()->position == PositionEnum::MANAGER && Auth::user()->id == $leaveApplication->manager_id) {
            if ($leaveApplication->status != ApprovalStatusEnum::MANAGER_REJECTED)
                return redirect()->back()->withErrors(['This leave application has been approved or rejected.']);
            $leaveApplication->status = ApprovalStatusEnum::MANAGER_PENDING;
            $leaveApplication->save();

            return Inertia::location(route('leaveApplications.index'));
        }

        if (Auth::user()->role->name == RoleEnum::STAFF && Auth::user()->position == PositionEnum::SENIOR && Auth::user()->id == $leaveApplication->supervisor_id) {
            if ($leaveApplication->status != ApprovalStatusEnum::SUPERVISOR_REJECTED)
                return redirect()->back()->withErrors(['This leave application has been approved or rejected.']);
            $leaveApplication->status = ApprovalStatusEnum::SUPERVISOR_PENDING;
            $leaveApplication->save();

            return Inertia::location(route('leaveApplications.index'));
        }
        return redirect()->back()->withErrors(['error', 'You are not allowed to approve this leave application.']);
    }

    public function ignore(Request $request, $id)
    {
        $leaveApplication = LeaveApplication::find($id);

        if (Auth::user()->id == $leaveApplication->supervisor_id) {
            $leaveApplication->status = ApprovalStatusEnum::SUPERVISOR_REJECTED;
            $leaveApplication->supervisor_reject_reasons = $request->supervisor_reject_reasons;
        } else if (Auth::user()->id == $leaveApplication->manager_id) {
            $leaveApplication->status = ApprovalStatusEnum::MANAGER_REJECTED;
            $leaveApplication->manager_reject_reasons = $request->manager_reject_reasons;
        }

        $leaveApplication->save();

        return Redirect::route('leaveApplications.index');
    }
}
