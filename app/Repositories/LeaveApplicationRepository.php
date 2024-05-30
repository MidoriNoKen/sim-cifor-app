<?php

namespace App\Repositories;

use App\Models\LeaveApplication;
use App\Interfaces\LeaveApplicationInterface;

class LeaveApplicationRepository implements LeaveApplicationInterface
{
    public function getAll()
    {
        return LeaveApplication::all();
    }

    public function getAllWithPagination($page, $perPage)
    {
        return LeaveApplication::with('role')->paginate($perPage, ['*'], 'page', $page);
    }

    public function getById($id)
    {
        return LeaveApplication::find($id);
    }

    public function getByUserId($userId, $page, $perPage)
    {
        return LeaveApplication::where('applicant_id', $userId)->paginate($perPage, ['*'], 'page', $page);
    }

    public function create(array $data)
    {
        return LeaveApplication::create($data);
    }

    public function update($id, array $data)
    {
        $user = LeaveApplication::find($id);
        $user->update($data);
        return $user;
    }

    public function delete($id)
    {
        return LeaveApplication::destroy($id);
    }
}