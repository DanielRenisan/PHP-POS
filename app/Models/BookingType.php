<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingType extends Model
{
    use HasFactory;

    public static function forDropdown($show_none = false)
    {
        $query = BookingType::orderBy('id');

        $types = $query->pluck('name', 'id');
        if ($show_none) {
            $types->prepend(__('Please Select'), '');
        }
        
        return $types;
    }
}
