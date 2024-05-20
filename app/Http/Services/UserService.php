<?php

namespace App\Http\Services;

use App\Enums\PositionEnum;
use App\Enums\RoleEnum;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class UserService
{
    public function getAll()
    {
        return User::all();
    }

    public function getUserById($id)
    {
        return User::find($id);
    }

    public function getUserByIdWithRelations($id)
    {
        $user = User::find($id);
        $supervisor = User::find($user->supervisor_id);
        $manager = User::find($user->manager_id);
        $user->supervisor = $supervisor ? $supervisor->name : null;
        $user->manager = $manager ? $manager->name : null;
        $user->born_date = Carbon::parse($user->born_date)->format('d F Y');
        return $user;
    }

    public function getUserByIdWithRole($id)
    {
        return User::find($id)->load('role');
    }

    public function getProjectManager() {
        $admin = Role::where('name', RoleEnum::ADMIN)->value('id');
        return User::whereNotIn('role_id', [$admin])
            ->whereNotIn('position', [PositionEnum::JUNIOR])
            ->get();
    }

    public function getLoggedUser()
    {
        return Auth::user();
    }

    public function getLoggedId()
    {
        return $this->getLoggedUser()->id;
    }

    public function getLoggedRole()
    {
        return $this->getLoggedUser()->role->name;
    }

    public function getLoggedPosition()
    {
        return $this->getLoggedUser()->position;
    }

    public function formattedData() {
        $users = User::all();
        $users->each(function ($user) {
            $user->role = $user->role_id ? Role::find($user->role_id)->name : null;
            $user->supervisor = $user->supervisor_id ? User::find($user->supervisor_id)->name : null;
            $user->manager = $user->manager_id ? User::find($user->manager_id)->name : null;
            $user->born_date = Carbon::parse($user->born_date)->format('d F Y');
        });

        return $users;
    }

    public function getSupervisors() {
        $supervisors = User::whereIn('role_id', function ($query) {
            $query->select('id')
                ->from('roles')
                ->where('name', RoleEnum::STAFF);
        })
            ->where('position', PositionEnum::SENIOR)
            ->where('id', '!=', Auth::id())
            ->get();

        return $supervisors;
    }

    public function getManagers() {
        $managers = User::whereIn('role_id', function ($query) {
            $query->select('id')
                ->from('roles')
                ->where('name', RoleEnum::MANAGER);
        })
            ->where('position', PositionEnum::MANAGER)
            ->where('id', '!=', Auth::id())
            ->get();

        return $managers;
    }

    public function getFinances() {
        $finances = User::where('position', PositionEnum::FINANCE)
            ->where('id', '!=', Auth::id())
            ->get();

        return $finances;
    }

    public function store($request) {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
                'password' => ['required', 'confirmed', Password::defaults()],
                'password_confirmation' => 'required|string',
                'role_id' => 'required|string',
                'position_id' => 'required|string',
                'supervisor_id' => 'nullable|string',
                'manager_id' => 'nullable|string',
                'born_date' => 'required|date',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'password_confirmation' => $request->password_confirmation,
                'role_id' => $request->role_id,
                'position' => $request->position_id,
                'supervisor_id' => $request->supervisor_id,
                'manager_id' => $request->manager_id,
                'born_date' => $request->born_date
            ]);

            if (!$user) {
                Throw new Exception("An error occurred while storing the leave application.");
            }
        } catch (Exception $e) {
            Throw new Exception("An error occurred while storing the user.");
        }
    }
}