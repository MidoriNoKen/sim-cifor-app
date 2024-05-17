<?php

namespace App\Enums;

class ProjectStatusEnum
{
    public const BACKLOG = 'Backlog';
    public const PLANNING = 'On Planning';
    public const PROGRESS = 'On Progress';
    public const REVIEW = 'On Review';
    public const DONE = 'Done';
    public const CANCELLED = 'Cancelled';

    public const STATUSES = [
        self::BACKLOG,
        self::PLANNING,
        self::PROGRESS,
        self::REVIEW,
        self::DONE,
        self::CANCELLED
    ];
}
