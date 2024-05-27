<?php

namespace App\Http\Controllers;

use App\Enums\PositionEnum;
use App\Http\Controllers\Controller;
use App\Http\Services\UserService;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class UserController extends Controller
{
    private $userService, $roleService;

    public function __construct()
    {
        $this->userService = new UserService();
        $this->roleService = new Role();
    }

    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 5);
        $users = $this->userService->formattedData($page, $perPage);
        return Inertia::render('User/Index')->with('users', $users);
    }

    public function create()
    {
        $loggedRole = Auth::user()->role->name;
        $supervisors = $this->userService->getSupervisors();
        $managers = $this->userService->getManagers();
        $role = $this->roleService->all();
        $position = PositionEnum::POSITIONS;

        return Inertia::render('User/Create')->with(['loggedRole' => $loggedRole, 'managers' => $managers, 'supervisors' => $supervisors, 'roles' => $role, 'positions' => $position]);
    }

    public function show(User $user)
    {
        $loggedRole = Auth::user()->role->name;
        $user = $this->userService->getUserByIdWithRelations($user->id);

        return Inertia::render('User/Show', [
            'loggedRole' => $loggedRole,
            'user' => $user,
        ]);
    }

    public function store(Request $request)
    {
        $this->userService->store($request);
        return Redirect::route('users.index');
    }

    public function edit(User $user)
    {
        $loggedRole = Auth::user()->role->name;
        $supervisors = $this->userService->getSupervisors();
        $managers = $this->userService->getManagers();
        $role = $this->roleService->all();
        $position = PositionEnum::POSITIONS;

        return Inertia::render('User/Edit', [
            'loggedRole' => $loggedRole,
            'user' => $user,
            'managers' => $managers,
            'supervisors' => $supervisors,
            'roles' => $role,
            'positions' => $position,
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
