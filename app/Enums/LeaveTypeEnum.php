<?php

namespace App\Enums;

class LeaveTypeEnum
{
    public const CUTI = 'Cuti';
    public const SAKIT = 'Sakit';
    public const MELAHIRKAN = 'Melahirkan';
    public const TYPES = [
        self::CUTI,
        self::SAKIT,
        self::MELAHIRKAN
    ];
}