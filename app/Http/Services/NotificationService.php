<?php
namespace App\Http\Services;

use App\Enums\ApprovalStatusEnum;
use App\Mail\LeaveApplicationMail;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    public function sendMail($sender, $receiver, $status)
    {
        Mail::to($receiver->email)->send(new LeaveApplicationMail($sender, $receiver, $status, ApprovalStatusEnum::STATUSES));
    }
}