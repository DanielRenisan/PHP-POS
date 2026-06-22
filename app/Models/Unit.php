<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;
    
    public function parent()
    {
        return $this->belongsTo(\App\Models\Unit::class, 'unit_parent_id');
    }
}
