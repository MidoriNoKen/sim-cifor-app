<?php

namespace App\Enums;

class ApprovalStatusEnum
{
    public const NEW = 'New';
    public const OFFICER_PENDING = 'Need Officer Approval';
    public const OFFICER_REJECTED = 'Rejected by Approval Officer';
    public const HR_PENDING = 'Need HR HR Approval';
    public const HR_REJECTED = 'Rejected by HR HR';
    public const FINANCE_PENDING = 'Need Finance Approval';
    public const FINANCE_REJECTED = 'Rejected by Finance';
    public const APPROVED = 'Approved';
    public const STATUSES = [
        self::NEW,
        self::OFFICER_PENDING,
        self::OFFICER_REJECTED,
        self::HR_PENDING,
        self::HR_REJECTED,
        self::FINANCE_PENDING,
        self::FINANCE_REJECTED,
        self::APPROVED
    ];
}