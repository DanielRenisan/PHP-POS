<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StationOrderTicket extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'payload_json' => 'array',
        'printed_at' => 'datetime',
        'retry_count' => 'integer',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_PRINTED = 'printed';
    public const STATUS_FAILED = 'failed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_REPRINTED = 'reprinted';

    public function station()
    {
        return $this->
        belongsTo(Station::class, 'station_id');
    }

    public function printer()
    {
        return $this->belongsTo(Printer::class, 'printer_id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transactions::class, 'transaction_id');
    }

    public function lines()
    {
        return $this->hasMany(StationOrderTicketLine::class, 'station_order_ticket_id');
    }

    public function scopeForStation($query, $stationId)
    {
        return $query->where('station_id', $stationId);
    }

    public function scopeOpen($query)
    {
        return $query->whereNotIn('status', [self::STATUS_CANCELLED]);
    }
}
