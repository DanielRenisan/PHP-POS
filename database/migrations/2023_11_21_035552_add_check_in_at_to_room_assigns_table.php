<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCheckInAtToRoomAssignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('room_assigns', function (Blueprint $table) {
            $table->dateTime('check_in_at')->nullable()->after('status');
            $table->dateTime('check_out_at')->nullable()->after('check_in_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('room_assigns', function (Blueprint $table) {
            //
        });
    }
}
