<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomSizeType extends Model
{
    use HasFactory;

    public static function forDropdown($show_none = false)
    {
        $query = RoomSizeType::orderBy('id');

        $sizes = $query->pluck('name', 'id');
        if ($show_none) {
            $sizes->prepend(__('Please Select'), '');
        }
        
        return $sizes;
    }
}
