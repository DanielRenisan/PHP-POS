<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductStationTable extends Migration
{
    public function up()
    {
        Schema::create('product_station', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedInteger('station_id');
            $table->timestamps();

            $table->unique(['product_id', 'station_id']);
            $table->index('station_id');

            $table->foreign('station_id')
                ->references('id')->on('stations')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_station');
    }
}
