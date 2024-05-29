<?php

namespace App\Interfaces;

interface RoleInterface
{
    public function getAll();
    public function getById($id);
    public function getByName($name);
}