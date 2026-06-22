<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_type');
            $table->decimal('capacity',20,0)->nullable();
            $table->string('extra_capacity')->nullable();
            $table->decimal('rate',20,0)->nullable();
            $table->decimal('bed_charge',20,2)->nullable();
            $table->decimal('person_charge',20,2)->nullable();
            $table->decimal('room_size',20,0)->nullable();
            $table->integer('room_size_id')->unsign();
            $table->decimal('bed_no',20,0)->nullable();
            $table->integer('bed_id')->unsign();
            $table->decimal('review',20,0)->nullable();
            $table->text('description')->nullable();
            $table->text('condition')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rooms');
    }
}
