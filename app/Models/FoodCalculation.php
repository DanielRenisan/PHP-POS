<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodCalculation extends Model
{
    use HasFactory;

    protected $table = 'food_calculation';

    protected $fillable = [
        'product_id',
        'menu_name',
        'quantity',
        'unit_id',
        'unit_name',
        'selling_price',
        'wastage_cost',
        'ingredients_cost',
        'service_cost',
        'extra_cost',
        'gross_profit',
        'labour_cost',
        'total_cost',
        'tax',
        'food_cost',
        'labour_hour',
        'prepare_time',
        'service_time',
        'total_time',
        'cooking_instruction',
        'service_instruction',
        'status',
    ];

    public function costCalculationProducts()
    {
        return $this->hasMany(CostCalculationProduct::class, 'food_calculation_id');
    }
    
    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class, 'product_id')->withTrashed();;
    }
}
