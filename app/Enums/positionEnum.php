<?php

namespace App\Enums;

class PositionEnum
{
    public const ADMIN = 'Admin';
    public const JUNIOR = 'Junior';
    public const SENIOR = 'Senior';
    public const MANAGER = 'Manager';
    public const DIRECTOR = 'Director';
    public const FINANCE = 'Finance';
    public const POSITIONS = [
        self::ADMIN,
        self::JUNIOR,
        self::SENIOR,
        self::MANAGER,
        self::DIRECTOR,
        self::FINANCE
    ];
}