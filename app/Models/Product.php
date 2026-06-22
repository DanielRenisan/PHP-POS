<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function pur_unit()
    {
        return $this->belongsTo(\App\Models\Unit::class, 'purchase_unit_id');
    }
    
    public function unit()
    {
        return $this->belongsTo(\App\Models\Unit::class, 'sale_unit_id');
    }

    public function variation()
    {
        return $this->belongsTo(\App\Models\ProductVariation::class, 'product_variation_id');
    }

    public function mainCategory()
    {
        return $this->belongsTo(\App\Models\ProductCategeory::class, 'category_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(\App\Models\ProductCategeory::class, 'sub_category_id');
    }

    public function brand()
    {
        return $this->belongsTo(\App\Models\Brand::class, 'brand_id');
    }

    public function cusion()
    {
        return $this->belongsTo(\App\Models\Cousine::class, 'cousine_id');
    }
    public function attributes()
    {
        return $this->hasMany(\App\Models\ProductATTAssign::class);
    }

    public function departments()
    {
        return $this->hasMany(\App\Models\ProductDepartment::class);
    }

    public function stations()
    {
        return $this->belongsToMany(\App\Models\Station::class, 'product_station')->withTimestamps();
    }
}
