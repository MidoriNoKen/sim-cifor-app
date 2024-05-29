<?php

namespace App\Http\Services;

use App\Enums\PositionEnum;
use App\Enums\RoleEnum;
use App\Interfaces\RoleInterface;
use App\Interfaces\UserInterface;
use App\Models\User;
use App\Utils\Util;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserService
{
    protected $userRepository, $roleRepository;

    public function __construct(UserInterface $userRepository, RoleInterface $roleRepository)
    {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }

    public function getAll($request) {
        [$page, $perPage] = Util::getPagination($request);
        $users = $this->userRepository->getAllWithPagination($page, $perPage);

        foreach ($users as $user) {
            $user->born_date = Carbon::parse($user->born_date)->format('d F Y');
        }

        return $users;
    }

    public function getByIdWithRelations($id)
    {
        $user = $this->userRepository->getByIdWithRelations($id);;
        $user->born_date = Carbon::parse($user->born_date)->format('d F Y');

        return $user;
    }

    public function getUserByIdWithRole($id)
    {
        return User::find($id)->load('role');
    }

    public function getOfficers() {
        $roleId = $this->roleRepository->getByName(RoleEnum::EMPLOYEE)->id;
        return User::where('position', PositionEnum::OFFICER)->andWhere('role_id', $roleId)->get();
    }

    public function getHRManagers() {
        $roleId = $this->roleRepository->getByName(RoleEnum::MANAGER)->id;
        return User::where('position', PositionEnum::HR)->andWhere('role_id', $roleId)->get();
    }
    
    public function getFinanceManagers() {
        $roleId = $this->roleRepository->getByName(RoleEnum::MANAGER)->id;
        return User::where('position', PositionEnum::FINANCE)->andWhere('role_id', $roleId)->get();
    }

    public function store($request) {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Password::defaults()],
            'role_id' => 'required',
            'position' => 'required|string',
            'born_date' => 'required|date',
        ]);

        if (!$validatedData) {
            return redirect()->back()->withErrors($validatedData)->withInput();
        }

        $user = $this->userRepository->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'position' => $request->position,
            'born_date' => $request->born_date
        ]);

        if (!$user) {
            Throw new Exception("An error occurred while storing the leave application.");
        }
    }

    public function update($request, $userId) {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class . ',email,' . $userId,
            'role_id' => 'required',
            'position' => 'required|string',
            'born_date' => 'required|date',
        ]);

        if (!$validatedData) {
            return redirect()->back()->withErrors($validatedData)->withInput();
        }

        $user = $this->userRepository->update($userId, [
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'position' => $request->position,
            'born_date' => $request->born_date
        ]);

        if (!$user) {
            Throw new Exception("An error occurred while updating the user information.");
        }
    }

    public function delete($userId) {
        $user = $this->userRepository->delete($userId);

        if (!$user) {
            Throw new Exception("An error occurred while deleting the user information.");
        }
    }
}