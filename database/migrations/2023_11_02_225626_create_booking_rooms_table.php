<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_rooms', function (Blueprint $table) {
            $table->id();
            $table->integer('transaction_id')->unsigned();
            $table->integer('booking_id')->unsigned();
            $table->string('room_type');
            $table->string('room_no');
            $table->decimal('adults', 8, 2)->default(0);
            $table->decimal('children', 8, 2)->default(0);
            $table->decimal('rent', 20, 2)->default(0);
            $table->dateTime('check_in_at');
            $table->dateTime('check_out_at');
            $table->decimal('bed_count', 8, 2)->default(0);
            $table->decimal('bed_amount', 20, 2)->default(0);
            $table->decimal('person_count', 8, 2)->default(0);
            $table->decimal('person_amount', 20, 2)->default(0);
            $table->decimal('childs_count', 8, 2)->default(0);
            $table->decimal('child_amount', 20, 2)->default(0);
            $table->integer('complementry_id')->unsigned()->nullable();
            $table->decimal('number', 8, 2)->default(0);
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
        Schema::dropIfExists('booking_rooms');
    }
}
