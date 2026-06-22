<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    public function customer()
    {
        return $this->belongsTo(\App\Models\Contact::class, 'contact_id');
    }

    public function type()
    {
        return $this->belongsTo(\App\Models\BookingType::class, 'booking_type_id');
    }

    public function source()
    {
        return $this->belongsTo(\App\Models\BookingSource::class, 'booking_source_id');
    }
}
