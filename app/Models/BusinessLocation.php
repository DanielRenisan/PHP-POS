<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class BusinessLocation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'landmark', 'city', 'state', 'country', 'zip_code', 'tin_number', 'reg_doc_no', 'fax_no',
    'mobile', 'alternate_number', 'email'];

    public static function forDropdown($show_all = false, $receipt_printer_type_attribute = false)
    {
        $query = BusinessLocation::orderBy('id');

        $permitted_locations = auth()->user()->permitted_locations();
        if ($permitted_locations != 'all') {
            $query->whereIn('id', $permitted_locations);
        }

        $locations = $query->pluck('name', 'id');

        if ($show_all) {
            $locations->prepend(__('All Locations'), '');
        }

        if ($receipt_printer_type_attribute) {
            $attributes = collect($query->get())->mapWithKeys(function ($item) {
                    return [$item->id => ['data-receipt_printer_type' => $item->receipt_printer_type]];
            })->all();

            return ['locations' => $locations, 'attributes' => $attributes];
        } else {
            return $locations;
        }
    }
}
