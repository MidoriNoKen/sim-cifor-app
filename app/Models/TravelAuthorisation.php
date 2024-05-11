<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelAuthorisation extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'applicant_id',
        'status',
        'supervisor_id',
        'supervisor_approval',
        'supervisor_reject_reasons',
        'manager_id',
        'manager_approval',
        'manager_reject_reasons',
        'finance_id',
        'finance_approval',
        'finance_reject_reasons',
        'unit_id',
        'transport_type',
        'start_date',
        'end_date',
        'day_accumulation',
        'accomodation_detail',
        'travel_reasons',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function applicant()
    {
        return $this->belongsTo(User::class, 'applicant_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function financeManager()
    {
        return $this->belongsTo(User::class, 'finance_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}