<?php

namespace App\Enums;

class ApprovalStatusEnum
{
    public const NEW = 'New';
    public const SUPERVISOR_PENDING = 'Need Supervisor Approval';
    public const SUPERVISOR_REJECTED = 'Rejected by Supervisor';
    public const MANAGER_PENDING = 'Need Manager Approval';
    public const MANAGER_REJECTED = 'Rejected by Manager';
    public const FINANCE_PENDING = 'Need Finance Approval';
    public const FINANCE_REJECTED = 'Rejected by Finance';
    public const APPROVED = 'Approved';
}