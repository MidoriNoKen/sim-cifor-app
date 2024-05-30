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
        'officer_id',
        'officer_reject_reasons',
        'hrManager_id',
        'hrManager_reject_reasons',
        'start_date',
        'end_date',
        'accumulation',
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
     * Get the officer who is responsible for approving this leave application.
     */
    public function officer()
    {
        return $this->belongsTo(User::class, 'officer_id');
    }

    /**
     * Get the HR who is responsible for approving this leave application.
     */
    public function HR()
    {
        return $this->belongsTo(User::class, 'hrManager_id');
    }
}
