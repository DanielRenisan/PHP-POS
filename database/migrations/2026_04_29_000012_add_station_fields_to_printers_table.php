<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStationFieldsToPrintersTable extends Migration
{
    public function up()
    {
        Schema::table('printers', function (Blueprint $table) {
            $table->unsignedInteger('station_id')->nullable()->after('id');
            $table->enum('printer_type', ['receipt', 'station'])->default('receipt')->after('station_id');
            $table->boolean('is_active')->default(true)->after('printer_type');
            $table->boolean('is_default')->default(false)->after('is_active');

            $table->index('station_id');
            $table->index(['printer_type', 'is_active']);

            $table->foreign('station_id')
                ->references('id')->on('stations')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('printers', function (Blueprint $table) {
            $table->dropForeign(['station_id']);
            $table->dropIndex(['station_id']);
            $table->dropIndex(['printer_type', 'is_active']);
            $table->dropColumn(['station_id', 'printer_type', 'is_active', 'is_default']);
        });
    }
}
