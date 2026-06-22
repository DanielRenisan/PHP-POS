<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bed extends Model
{
    use HasFactory;

    public static function forDropdown($show_none = false)
    {
        $query = Bed::orderBy('id');

        $beds = $query->pluck('name', 'id');
        if ($show_none) {
            $beds->prepend(__('Please Select'), '');
        }
        
        return $beds;
    }
}
