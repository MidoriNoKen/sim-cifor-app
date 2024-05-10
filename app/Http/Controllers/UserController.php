<?php

namespace App\Http\Controllers;

use App\Enums\PositionEnum;
use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        $users->each(function ($user) {
            $user->role = $user->role_id ? Role::find($user->role_id)->name : null;
            $user->supervisor = $user->supervisor_id ? User::find($user->supervisor_id)->name : null;
            $user->manager = $user->manager_id ? User::find($user->manager_id)->name : null;
            $user->born_date = Carbon::parse($user->born_date)->format('d F Y');
        });
        return Inertia::render('User/Index')->with('users', $users);
    }

    public function create()
    {
        $supervisors = User::whereIn('role_id', function ($query) {
            $query->select('id')
                ->from('roles')
                ->where('name', RoleEnum::STAFF);
        })
            ->where('position', PositionEnum::SENIOR)
            ->where('id', '!=', Auth::id())
            ->get();

        $managers = User::whereIn('role_id', function ($query) {
            $query->select('id')
                ->from('roles')
                ->where('name', RoleEnum::MANAGER);
        })
            ->where('position', PositionEnum::MANAGER)
            ->where('id', '!=', Auth::id())
            ->get();

        $loggedRole = Auth::user()->role->name;

        return Inertia::render('User/Create')->with(['loggedRole' => $loggedRole, 'managers' => $managers, 'supervisors' => $supervisors]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Password::defaults()],
            'password_confirmation' => 'required|string',
            'role' => 'required|string',
            'position' => 'required|string',
            'supervisor' => 'nullable|string',
            'manager' => 'nullable|string',
            'born_date' => 'required|date',
        ]);

        $role_id = Role::where('name', $request->role)->value('id');
        $supervisor_id = $request->supervisor ? User::where('name', $request->supervisor)->value('id') : null;
        $manager_id = $request->manager ? User::where('name', $request->manager)->value('id') : null;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'password_confirmation' => $request->password_confirmation,
            'position' => $request->position,
            'supervisor_id' => $supervisor_id,
            'manager_id' => $manager_id,
            'born_date' => $request->born_date,
            'role_id' => $role_id
        ]);

        event(new Registered($user));

        return Redirect::route('users.index');
    }

    public function edit(User $user)
    {
        $managers = User::whereIn('role_id', function ($query) {
            $query->select('id')
                ->from('roles')
                ->where('name', RoleEnum::MANAGER);
        })
            ->where('position', PositionEnum::MANAGER)
            ->where('id', '!=', Auth::id())
            ->get();

        $supervisors = User::whereIn('role_id', function ($query) {
            $query->select('id')
                ->from('roles')
                ->where('name', RoleEnum::STAFF);
        })
            ->where('position', PositionEnum::SENIOR)
            ->where('id', '!=', Auth::id())
            ->get();

        $user->role = Role::find($user->role_id)->name;
        $user->manager = $user->manager_id ? User::find($user->manager_id)->name : null;
        $user->supervisor = $user->supervisor_id ? User::find($user->supervisor_id)->name : null;

        $loggedRole = Auth::user()->role->name;
        return Inertia::render('User/Edit', [
            'loggedRole' => $loggedRole,
            'user' => $user,
            'managers' => $managers,
            'supervisors' => $supervisors
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class . ',email,' . $user->id,
            'role' => 'required|string',
            'position' => 'required|string',
            'supervisor' => 'nullable|string',
            'manager' => 'nullable|string',
            'born_date' => 'required|date',
        ]);

        $role_id = Role::where('name', $request->role)->value('id');
        $supervisor_id = $request->supervisor ? User::where('name', $request->supervisor)->value('id') : null;
        $manager_id = $request->manager ? User::where('name', $request->manager)->value('id') : null;

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $role_id,
            'position' => $request->position,
            'supervisor_id' => $supervisor_id,
            'manager_id' => $manager_id,
            'born_date' => $request->born_date,
        ]);

        return Redirect::route('users.index');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return Redirect::route('users.index');
    }
}
