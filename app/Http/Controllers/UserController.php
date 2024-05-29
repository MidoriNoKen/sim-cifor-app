<?php

namespace App\Http\Controllers;

use App\Enums\PositionEnum;
use App\Http\Controllers\Controller;
use App\Http\Services\RoleService;
use App\Http\Services\UserService;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class UserController extends Controller
{
    private $userService, $roleService;

    public function __construct(UserService $userService, RoleService $roleService)
    {
        $this->userService = $userService;
        $this->roleService = $roleService;
    }

    public function index(Request $request)
    {
        $users = $this->userService->getAll($request);
        return Inertia::render('User/Index')->with('users', $users);
    }

    public function show(User $user)
    {
        $user = $this->userService->getByIdWithRelations($user->id);

        return Inertia::render('User/Show', [
            'user' => $user,
        ]);
    }

    public function create()
    {
        $role = $this->roleService->getAll();
        $position = PositionEnum::POSITIONS;

        return Inertia::render('User/Create')->with(['roles' => $role, 'positions' => $position]);
    }

    public function store(Request $request)
    {
        $this->userService->store($request);
        return Redirect::route('users.index');
    }

    public function edit(User $user)
    {
        $role = $this->roleService->getAll();
        $positions = PositionEnum::POSITIONS;

        return Inertia::render('User/Edit', [
            'user' => $user,
            'roles' => $role,
            'positions' => $positions,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $this->userService->update($request, $user->id);
        return Redirect::route('users.index');
    }

    public function destroy(User $user)
    {
        $this->userService->delete($user->id);

        return Redirect::route('users.index');
    }
}