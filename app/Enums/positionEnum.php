<?php

namespace App\Enums;

class PositionEnum
{
    public const ADMIN = 'Admin';
    public const STAFF = 'Staff';
    public const OFFICER = 'Approval Officer';
    public const HR = 'Human Resource';
    public const FINANCE = 'Finance';
    public const POSITIONS = [
        self::ADMIN,
        self::STAFF,
        self::OFFICER,
        self::HR,
        self::FINANCE,
    ];
}