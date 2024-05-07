<?php

namespace App\Http\Controllers;

use App\Enums\ApprovalStatusEnum;
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
        $leaveApplications = LeaveApplication::with('applicant', 'supervisor', 'manager')->get();
        $loggedRole = Auth::user()->role->name;
        return Inertia::render('LeaveApplication/Index')->with(['leaveApplications', $leaveApplications, 'loggedRole', $loggedRole]);
    }

    public function show($id)
    {
        $leaveApplication = LeaveApplication::find($id);
        $loggedRole = Auth::user()->role->name;

        $leaveApplication->applicant = User::find($leaveApplication->applicant_id)->name;
        $leaveApplication->supervisor = $leaveApplication->supervisor_id ? User::find($leaveApplication->supervisor_id)->name : null;
        $leaveApplication->manager = $leaveApplication->manager_id ? User::find($leaveApplication->manager_id)->name : null;

        return Inertia::render('LeaveApplication/Detail')->with(['leaveApplication', $leaveApplication, 'loggedRole', $loggedRole]);
    }

    public function create()
    {
        $leaveApplication = LeaveApplication::all();
        $loggedRole = Auth::user()->role->name;
        return Inertia::render('LeaveApplication/Create')->with(['leaveApplication', $leaveApplication, 'loggedRole', $loggedRole]);
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
            'manager_id' => auth()->user()->supervisor_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'day_accumulation' => $request->start_date->diffInDays($request->end_date) + 1,
            'leave_type' => $request->leave_type,
        ]);

        event(new Registered($leaveApplication));

        return Redirect::route('leaveApplications.index');
    }
}