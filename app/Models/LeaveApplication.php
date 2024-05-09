<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'applicant_id',
        'status',
        'supervisor_id',
        'supervisor_reject_reasons',
        'manager_id',
        'manager_reject_reasons',
        'start_date',
        'end_date',
        'day_accumulation',
        'leave_type',
    ];

    /**
     * Get the user who is the applicant of this leave application.
     */
    public function applicant()
    {
        return $this->belongsTo(User::class, 'applicant_id');
    }

    /**
     * Get the supervisor who is responsible for approving this leave application.
     */
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    /**
     * Get the manager who is responsible for approving this leave application.
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}