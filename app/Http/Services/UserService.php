<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

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

    public function getUserByIdWithRole($id)
    {
        return User::find($id)->load('role');
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
}
