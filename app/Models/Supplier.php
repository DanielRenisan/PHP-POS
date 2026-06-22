<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Supplier extends Model
{
    use HasFactory;
    use SoftDeletes;
    public static function forDropdown($show_none = false)
    {
        $query = Supplier::orderBy('id');

        $suppliers = $query->pluck('name', 'id');
        if ($show_none) {
            $suppliers->prepend(__('Please Select'), '');
        }
        
        return $suppliers;
    }
}
