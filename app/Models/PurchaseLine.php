<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseLine extends Model
{
    use HasFactory;
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    public function transaction()
    {
        return $this->belongsTo(\App\Models\Transactions::class, 'transaction_id');
    }

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class, 'product_id')->withTrashed();;
    }
}
