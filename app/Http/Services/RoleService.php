<?php

namespace App\Http\Services;

use App\Models\Role;

class RoleService
{
    public function getAll()
    {
        return Role::all();
    }

    public function getById(int $id)
    {
        return Role::find($id);
    }
}
