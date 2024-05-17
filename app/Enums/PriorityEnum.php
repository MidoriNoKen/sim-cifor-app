<?php

namespace App\Enums;

class PriorityEnum
{
    public const LOW = 'Low';
    public const MEDIUM = 'Medium';
    public const HIGH = 'High';

    public const PRIORITY = [
        self::LOW,
        self::MEDIUM,
        self::HIGH
    ];
}