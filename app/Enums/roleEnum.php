<?php

namespace App\Enums;

class RoleEnum
{
    public const ADMIN = 'Admin';
    public const EMPLOYEE = 'Employee';
    public const MANAGER = 'Manager';
    public const DIRECTOR = 'Director';
    public const ROLES = [
        self::ADMIN,
        self::EMPLOYEE,
        self::MANAGER,
        self::DIRECTOR
    ];
}