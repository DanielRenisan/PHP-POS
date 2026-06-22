<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomFacility extends Model
{
    use HasFactory;

    public static function forDropdown($show_none = false)
    {
        $query = RoomFacility::orderBy('id');

        $facilities = $query->pluck('name', 'id');
        if ($show_none) {
            $facilities->prepend(__('messages.please_select'), '');
        }
        
        return $facilities;
    }
}
