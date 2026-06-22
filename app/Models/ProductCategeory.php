<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategeory extends Model
{
    
    use HasFactory;

    public function childs()
    {
        return $this->hasMany(\App\Models\ProductCategeory::class,'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(\App\Models\ProductCategeory::class,'parent_id');
    }
}
