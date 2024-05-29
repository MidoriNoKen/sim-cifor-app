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
        'officer_id',
        'supervisor_reject_reasons',
        'manager_id',
        'manager_reject_reasons',
        'finance_id',
        'finance_reject_reasons',
        'unit_id',
        'transport_type',
        'start_date',
        'end_date',
        'accumulation',
        'travel_reasons',
    ];

    public function applicant()
    {
        return $this->belongsTo(User::class, 'applicant_id');
    }

    public function officer()
    {
        return $this->belongsTo(User::class, 'officer_id');
    }

    public function HR()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function finance()
    {
        return $this->belongsTo(User::class, 'finance_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function AccommodationDetails()
    {
        return $this->hasMany(AccommodationDetail::class);
    }
}
