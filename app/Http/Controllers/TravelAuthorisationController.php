<?php

namespace App\Http\Controllers;

use App\Enums\ApprovalStatusEnum;
use App\Enums\PositionEnum;
use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Models\TravelAuthorisation;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class TravelAuthorisationController extends Controller
{

    public function index()
    {
        if (Auth::user()->role->name === RoleEnum::STAFF && Auth::user()->position === PositionEnum::JUNIOR)
            $travelAuthorisations = TravelAuthorisation::where("applicant_id", auth()->id())->get();
        else if (Auth::user()->role->name === RoleEnum::STAFF && Auth::user()->position === PositionEnum::SENIOR)
            $travelAuthorisations = TravelAuthorisation::where("supervisor_id", auth()->id())->orWhere("applicant_id", auth()->id())->get();
        else if (Auth::user()->role->name === RoleEnum::MANAGER && Auth::user()->position === PositionEnum::MANAGER)
            $travelAuthorisations = TravelAuthorisation::where("manager_id", auth()->id())->orWhere("applicant_id", auth()->id())->get();
        else
            $travelAuthorisations = TravelAuthorisation::all();

        $travelAuthorisations->each(function ($travelAuthorisation) {
            $travelAuthorisation->applicant = User::find($travelAuthorisation->applicant_id)->name;
            $travelAuthorisation->supervisor = $travelAuthorisation->supervisor_id ? User::find($travelAuthorisation->supervisor_id)->name : null;
            $travelAuthorisation->manager = $travelAuthorisation->manager_id ? User::find($travelAuthorisation->manager_id)->name : null;

            if (Auth::user()->id == $travelAuthorisation->supervisor_id) {
                $travelAuthorisation->isSupervisor = true;
            } else if (Auth::user()->id == $travelAuthorisation->manager_id) {
                $travelAuthorisation->isManager = true;
            } else {
                $travelAuthorisation->isManager = false;
                $travelAuthorisation->isSupervisor = false;
            }
        });

        $loggedRole = Auth::user()->role->name;
        return Inertia::render('TravelAuthorisation/Index')->with(['travelAuthorisations' => $travelAuthorisations, 'loggedRole' => $loggedRole]);
    }

    public function show($id)
    {
        $travelAuthorisation = TravelAuthorisation::find($id);
        $loggedRole = Auth::user()->role->name;
        $user = User::where('id', $travelAuthorisation->applicant_id)->first();

        $travelAuthorisation->applicant = User::find($travelAuthorisation->applicant_id)->name;
        $user->supervisor = $travelAuthorisation->supervisor_id ? User::find($travelAuthorisation->supervisor_id)->name : null;
        $user->manager = $travelAuthorisation->manager_id ? User::find($travelAuthorisation->manager_id)->name : null;

        return Inertia::render('TravelAuthorisation/Show')->with(['user' => $user, 'travelAuthorisation' => $travelAuthorisation, 'loggedRole' => $loggedRole]);
    }

    public function create()
    {
        $loggedRole = Auth::user()->role->name;
        return Inertia::render('TravelAuthorisation/Create')->with(['loggedRole' => $loggedRole]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'transport_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'accomodation_detail' => 'required|string',
            'travel_reasons' => 'required|string',
        ]);

        $travelAuthorisation = TravelAuthorisation::create([
            'applicant_id' => auth()->id(),
            'status' => ApprovalStatusEnum::SUPERVISOR_PENDING,
            'supervisor_id' => auth()->user()->supervisor_id,
            'supervisor_reject_reasons' => null,
            'manager_id' => auth()->user()->manager_id,
            'manager_reject_reasons' => null,
            'finance_id' => null,
            'finance_reject_reasons' => null,
            'unit_id' => null,
            'transport_type' => $request->transport_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'day_accumulation' => date_diff(date_create($request->start_date), date_create($request->end_date))->format('%a') + 1,
            'accomodation_detail' => $request->accomodation_detail,
            'travel_reasons' => $request->travel_reasons,
        ]);

        event(new Registered($travelAuthorisation));

        return Inertia::location(route('leaveApplications.index'));
    }

    public function approveBySupervisor($id)
    {
        $travelAuthorisation = TravelAuthorisation::find($id);
        if (Auth::user()->role->name == RoleEnum::STAFF && Auth::user()->position == PositionEnum::SENIOR && Auth::user()->id == $travelAuthorisation->supervisor_id) {
            if ($travelAuthorisation->status != ApprovalStatusEnum::SUPERVISOR_PENDING)
                return redirect()->back()->withErrors(['This travel authorisation has been approved or rejected.']);
            $travelAuthorisation->status = ApprovalStatusEnum::MANAGER_PENDING;
            $travelAuthorisation->save();

            return Inertia::location(route('leaveApplications.index'));
        }
        return redirect()->back()->withErrors(['error', 'You are not allowed to approve this travel authorisation.']);
    }

    public function disapproveBySupervisor($id)
    {
        $travelAuthorisation = TravelAuthorisation::find($id);
        if (Auth::user()->role->name == RoleEnum::STAFF && Auth::user()->position == PositionEnum::SENIOR && Auth::user()->id == $travelAuthorisation->supervisor_id) {
            if ($travelAuthorisation->status != ApprovalStatusEnum::MANAGER_PENDING)
                return redirect()->back()->withErrors(['This travel authorisation has been approved or rejected.']);
            $travelAuthorisation->status = ApprovalStatusEnum::SUPERVISOR_PENDING;
            $travelAuthorisation->save();

            return Inertia::location(route('leaveApplications.index'));
        }
        return redirect()->back()->withErrors(['error', 'You are not allowed to approve this travel authorisation.']);
    }

    public function approveByManager($id)
    {
        $travelAuthorisation = TravelAuthorisation::find($id);
        if (Auth::user()->role->name == RoleEnum::MANAGER && Auth::user()->position == PositionEnum::MANAGER && Auth::user()->id == $travelAuthorisation->manager_id) {
            if ($travelAuthorisation->status != ApprovalStatusEnum::MANAGER_PENDING)
                return redirect()->back()->withErrors(['This travel authorisation has been approved or rejected.']);
            $travelAuthorisation->status = ApprovalStatusEnum::APPROVED;
            $travelAuthorisation->save();

            return Inertia::location(route('leaveApplications.index'));
        }
        return redirect()->back()->withErrors(['error', 'You are not allowed to approve this travel authorisation.']);
    }

    public function disapproveByManager($id)
    {
        $travelAuthorisation = TravelAuthorisation::find($id);
        if (Auth::user()->role->name == RoleEnum::MANAGER && Auth::user()->position == PositionEnum::MANAGER && Auth::user()->id == $travelAuthorisation->manager_id) {
            if ($travelAuthorisation->status != ApprovalStatusEnum::APPROVED)
                return redirect()->back()->withErrors(['This travel authorisation has been approved or rejected.']);
            $travelAuthorisation->status = ApprovalStatusEnum::MANAGER_PENDING;
            $travelAuthorisation->save();

            return Inertia::location(route('leaveApplications.index'));
        }
        return redirect()->back()->withErrors(['error', 'You are not allowed to approve this travel authorisation.']);
    }

    public function reject($id)
    {
        $travelAuthorisation = TravelAuthorisation::find($id);

        if (Auth::user()->id == $travelAuthorisation->supervisor_id) {
            $travelAuthorisation->isSupervisor = true;
        } else if (Auth::user()->id == $travelAuthorisation->manager_id) {
            $travelAuthorisation->isManager = true;
        } else {
            $travelAuthorisation->isManager = false;
            $travelAuthorisation->isSupervisor = false;
        }

        return Inertia::render('TravelAuthorisation/Reject')->with(['travelAuthorisation' => $travelAuthorisation]);
    }

    public function unreject($id)
    {
        $travelAuthorisation = TravelAuthorisation::find($id);
        if (Auth::user()->role->name == RoleEnum::MANAGER && Auth::user()->position == PositionEnum::MANAGER && Auth::user()->id == $travelAuthorisation->manager_id) {
            if ($travelAuthorisation->status != ApprovalStatusEnum::MANAGER_REJECTED)
                return redirect()->back()->withErrors(['This travel authorisation has been approved or rejected.']);
            $travelAuthorisation->status = ApprovalStatusEnum::MANAGER_PENDING;
            $travelAuthorisation->save();

            return Inertia::location(route('leaveApplications.index'));
        }

        if (Auth::user()->role->name == RoleEnum::STAFF && Auth::user()->position == PositionEnum::SENIOR && Auth::user()->id == $travelAuthorisation->supervisor_id) {
            if ($travelAuthorisation->status != ApprovalStatusEnum::SUPERVISOR_REJECTED)
                return redirect()->back()->withErrors(['This travel authorisation has been approved or rejected.']);
            $travelAuthorisation->status = ApprovalStatusEnum::SUPERVISOR_PENDING;
            $travelAuthorisation->save();

            return Inertia::location(route('leaveApplications.index'));
        }
        return redirect()->back()->withErrors(['error', 'You are not allowed to approve this travel authorisation.']);
    }

    public function ignore(Request $request, $id)
    {
        $travelAuthorisation = TravelAuthorisation::find($id);

        if (Auth::user()->id == $travelAuthorisation->supervisor_id) {
            $travelAuthorisation->status = ApprovalStatusEnum::SUPERVISOR_REJECTED;
            $travelAuthorisation->supervisor_reject_reasons = $request->supervisor_reject_reasons;
        } else if (Auth::user()->id == $travelAuthorisation->manager_id) {
            $travelAuthorisation->status = ApprovalStatusEnum::MANAGER_REJECTED;
            $travelAuthorisation->manager_reject_reasons = $request->manager_reject_reasons;
        }

        $travelAuthorisation->save();

        return Inertia::location(route('leaveApplications.index'));
    }
}
