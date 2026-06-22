<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Printer extends Model
{
    use HasFactory;
    use SoftDeletes;
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    public const TYPE_RECEIPT = 'receipt';
    public const TYPE_STATION = 'station';

    public function station()
    {
        return $this->belongsTo(\App\Models\Station::class, 'station_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForStation($query, $stationId)
    {
        return $query->where('station_id', $stationId)->where('printer_type', self::TYPE_STATION);
    }

    public static function printer_types()
    {
        return [
            self::TYPE_RECEIPT => 'Receipt',
            self::TYPE_STATION => 'Station',
        ];
    }

    public static function capability_profiles()
    {
        $profiles = [
            'default' => 'Default',
            'simple' => 'Simple',
            'SP2000' => 'Star Branded',
            'TEP-200M' => 'Espon Tep',
            'P822D' => 'P822D'
        ];

        return $profiles;
    }

    public static function capability_profile_srt($profile)
    {
        $profiles = Printer::capability_profiles();

        return isset($profiles[$profile]) ? $profiles[$profile] : '';
    }

    public static function connection_types()
    {
        $types = [
            'network' => 'Network',
            'windows' => 'Windows',
            'linux' => 'Linux',
            'file' => 'File (Test)',
        ];

        return $types;
    }

    public static function connection_type_str($type)
    {
        $types = Printer::connection_types();

        return isset($types[$type]) ? $types[$type] : '';
    }

    /**
     * Return list of printers for a business
     *
     * @param int $business_id
     * @param boolean $show_select = true
     *
     * @return array
     */
    public static function forDropdown($show_select = true)
    {
        $query = Printer::orderBy('id');

        $printers = $query->pluck('name', 'id');
        if ($show_select) {
            $printers->prepend(__('Please Select'), '');
        }
        return $printers;
    }
}
