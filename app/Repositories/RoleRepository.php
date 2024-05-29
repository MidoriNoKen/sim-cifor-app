<?php

namespace App\Repositories;

use App\Models\Role;
use App\Interfaces\RoleInterface;

class RoleRepository implements RoleInterface
{
    public function getAll()
    {
        return Role::all();
    }

    public function getById($id)
    {
        return Role::find($id);
    }

    public function getByName($name)
    {
        return Role::where('name', $name)->first();
    }
}