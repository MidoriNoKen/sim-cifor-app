<?php

namespace App\Enums;

class TaskStatusEnum
{
    public const ASSIGNED = 'Assigned';
    public const PLANNING = 'On Planning';
    public const PROGRESS = 'On Progress';
    public const REVIEW = 'On Review';
    public const DONE = 'Done';
    public const CANCELLED = 'Cancelled';

    public const STATUSES = [
        self::ASSIGNED,
        self::PLANNING,
        self::PROGRESS,
        self::REVIEW,
        self::DONE,
        self::CANCELLED
    ];
}