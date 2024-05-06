<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
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
        return Inertia::render('Users/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return Redirect::route('user.index');
    }

    public function edit(User $user)
    {
        return Inertia::render('User/Edit', [
            'user' => $user,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Password::defaults()],
            'password_confirmation' => 'required|string',
            'position' => 'required|string',
            'supervisor_id' => 'nullable|string',
            'manager_id' => 'nullable|string',
            'born_date' => 'required|date',
            'role_id' => 'required',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'password_confirmation' => $request->password_confirmation,
            'position' => $request->position,
            'supervisor_id' => $request->supervisor_id,
            'manager_id' => $request->manager_id,
            'born_date' => $request->born_date,
            'role_id' => $request->role_id
        ]);

        return Redirect::route('user.index');
    }

    public function delete(User $user)
    {
        $user->delete();

        return Redirect::route('user.index');
    }
}