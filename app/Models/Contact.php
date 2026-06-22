<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    public static function forSupplierDropdown($show_none = false)
    {
        $query = Contact::orderBy('id')->where('contact_type_id', 2)->where('status', "Active");

        $suppliers = $query->pluck('first_name', 'id');
        if ($show_none) {
            $suppliers->prepend(__('Please Select'), '');
        }
        
        return $suppliers;
    }

    public static function forCustomerDropdown($show_none = false)
    {
        $query = Contact::orderBy('id')->where('contact_type_id', 1)->where('status', "Active");

        $suppliers = $query->pluck('first_name', 'id');
        if ($show_none) {
            $suppliers->prepend(__('Please Select'), '');
        }
        
        return $suppliers;
    }
}
