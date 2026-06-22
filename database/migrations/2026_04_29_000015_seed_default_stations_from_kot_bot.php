<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SeedDefaultStationsFromKotBot extends Migration
{
    public function up()
    {
        $now = now();

        $defaults = [
            [
                'name' => 'Kitchen', 'code' => 'KOT',
                'ticket_name' => 'Kitchen Order Ticket', 'slug' => 'kitchen',
                'display_order' => 1,
            ],
            [
                'name' => 'Bar', 'code' => 'BOT',
                'ticket_name' => 'Bar Order Ticket', 'slug' => 'bar',
                'display_order' => 2,
            ],
        ];

        $stationIds = [];
        foreach ($defaults as $row) {
            $existing = DB::table('stations')->where('code', $row['code'])->first();
            if ($existing) {
                $stationIds[$row['code']] = $existing->id;
                continue;
            }

            $stationIds[$row['code']] = DB::table('stations')->insertGetId(array_merge($row, [
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        if (Schema::hasColumn('products', 'is_kot') && isset($stationIds['KOT'])) {
            $kotProducts = DB::table('products')->where('is_kot', 1)->pluck('id');
            $rows = [];
            foreach ($kotProducts as $pid) {
                $rows[] = [
                    'product_id' => $pid,
                    'station_id' => $stationIds['KOT'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            if ($rows) {
                foreach (array_chunk($rows, 500) as $chunk) {
                    DB::table('product_station')->insertOrIgnore($chunk);
                }
            }
        }

        if (Schema::hasColumn('products', 'is_bot') && isset($stationIds['BOT'])) {
            $botProducts = DB::table('products')->where('is_bot', 1)->pluck('id');
            $rows = [];
            foreach ($botProducts as $pid) {
                $rows[] = [
                    'product_id' => $pid,
                    'station_id' => $stationIds['BOT'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            if ($rows) {
                foreach (array_chunk($rows, 500) as $chunk) {
                    DB::table('product_station')->insertOrIgnore($chunk);
                }
            }
        }
    }

    public function down()
    {
        DB::table('product_station')
            ->whereIn('station_id', function ($q) {
                $q->select('id')->from('stations')->whereIn('code', ['KOT', 'BOT']);
            })
            ->delete();

        DB::table('stations')->whereIn('code', ['KOT', 'BOT'])->delete();
    }
}
