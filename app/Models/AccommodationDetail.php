<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccommodationDetail extends Model
{
    use HasFactory;

    protected $fillable = ['travel_authorisation_id', 'name', 'quantity', 'price', 'total_price', 'description'];

    public function travelAuthorisation()
    {
        return $this->belongsTo(TravelAuthorisation::class);
    }
}