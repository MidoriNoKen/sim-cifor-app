<?php

namespace App\Repositories;

use App\Models\User;
use App\Interfaces\UserInterface;

class UserRepository implements UserInterface
{
    public function getAll()
    {
        return User::all();
    }

    public function getAllWithPagination($page, $perPage)
    {
        return User::with('role')->paginate($perPage, ['*'], 'page', $page);
    }

    public function getById($id)
    {
        return User::find($id);
    }

    public function getByIdWithRelations($id)
    {
        return User::with('role')->find($id);
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update($id, array $data)
    {
        $user = User::find($id);
        $user->update($data);
        return $user;
    }

    public function delete($id)
    {
        return User::destroy($id);
    }
}