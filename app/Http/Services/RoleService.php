<?php

namespace App\Http\Services;

use App\Interfaces\RoleInterface;

class RoleService
{
    protected $roleRepository;

    public function __construct(RoleInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function getAll()
    {
        return $this->roleRepository->getAll();
    }

    public function getById(int $id)
    {
        return $this->roleRepository->getById($id);
    }
}