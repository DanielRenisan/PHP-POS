<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    public static function forDropdown($show_none = false)
    {
        $query = Customer::orderBy('id');

        $customers = $query->pluck('first_name', 'id');
        if ($show_none) {
            $customers->prepend(__('Please Select'), '');
        }
        
        return $customers;
    }
}
