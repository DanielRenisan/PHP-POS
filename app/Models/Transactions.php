<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    use HasFactory;
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    public function purchase_lines()
    {
        return $this->hasMany(\App\Models\TransactionLine::class, 'transaction_id');
    }

    public function lines_of_purchase()
    {
        return $this->hasMany(\App\Models\PurchaseLine::class, 'transaction_id');
    }

    public function payment_lines()
    {
        return $this->hasMany(\App\Models\TransactionPayment::class, 'transaction_id');
    }

    public function sell_lines()
    {
        return $this->hasMany(\App\Models\TransactionSellLine::class, 'transaction_id');
    }

    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class, 'category_id');
    }

    public function supplier()
    {
        return $this->belongsTo(\App\Models\Contact::class, 'contact_id');
    }

    public function lines_of_sell()
    {
        return $this->hasMany(\App\Models\TransactionSellLine::class, 'transaction_id')
        ->where('transaction_sell_lines.status', '!=', 'canceled');
    }

    public function location()
    {
        return $this->belongsTo(\App\Models\BusinessLocation::class, 'location_id');
    }

    public function department()
    {
        return $this->belongsTo(\App\Models\DepartmentPoss::class, 'department_id');
    }

    public function staff()
    {
        return $this->belongsTo(\App\Models\User::class, 'staff_id');
    }

    public function customer()
    {
        return $this->belongsTo(\App\Models\Contact::class, 'contact_id');
    }
    public function table()
    {
        return $this->belongsTo(\App\Models\Table::class, 'table_id');
    }
    public function room()
    {
        return $this->belongsTo(\App\Models\RoomAssign::class, 'room_id');
    }
}
