<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Station extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_station')->withTimestamps();
    }

    public function printers()
    {
        return $this->hasMany(Printer::class, 'station_id');
    }

    public function activePrinter()
    {
        return $this->hasOne(Printer::class, 'station_id')
            ->where('printer_type', 'station')
            ->where('is_active', true)
            ->orderByDesc('is_default')
            ->orderBy('id');
    }

    public function tickets()
    {
        return $this->hasMany(StationOrderTicket::class, 'station_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public static function generateSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'station';
        $slug = $base;
        $i = 2;
        while (self::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }

    public static function generateUniqueCode(string $name): string
    {
        $base = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $name), 0, 1) . 'OT');
        if (strlen($base) < 3) {
            $base = 'XOT';
        }
        $code = $base;
        $i = 2;
        while (self::where('code', $code)->exists()) {
            $code = $base . $i++;
        }
        return $code;
    }
}
