<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;
    
    public function parent()
    {
        return $this->belongsTo(\App\Models\Tax::class,'group_parent_id');
    }
}
