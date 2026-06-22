<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Floor extends Model
{
    use HasFactory;

    public static function forDropdown($show_none = false)
    {
        $query = Floor::orderBy('id');

        $floors = $query->pluck('name', 'id');
        if ($show_none) {
            $floors->prepend(__('Please Select'), '');
        }
        
        return $floors;
    }
}
