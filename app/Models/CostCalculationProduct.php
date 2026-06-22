<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostCalculationProduct extends Model
{
    use HasFactory;

    protected $table = 'cost_calculation_product';

    protected $fillable = [
        'food_calculation_id',
        'ingredient_product_id',
        'ingredient_product_name',
        'ingredient_qty',
        'ingredient_unit_id',
        'ingredient_unit_name',
        'ingredient_cost',
        'wast_qty',
        'wast_unit_id',
        'wast_unit_name',
        'wast_cost',
        'total_cost',
    ];

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class, 'ingredient_product_id')->withTrashed();;
    }

    public function intyUnit()
    {
        return $this->belongsTo(\App\Models\Unit::class, 'ingredient_unit_id');
    }

    public function wastageUnit()
    {
        return $this->belongsTo(\App\Models\Unit::class, 'wast_unit_id');
    }
}
