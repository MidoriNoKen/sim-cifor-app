<?php
namespace App\Http\Services;

use App\Enums\ApprovalStatusEnum;
use App\Mail\ApplicationMail;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    public function sendMail($sender, $receiver, $status, $type)
    {
        Mail::to($receiver->email)->send(new ApplicationMail($sender, $receiver, $status, ApprovalStatusEnum::STATUSES, $type));
    }
}