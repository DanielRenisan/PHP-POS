<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->integer('transaction_id')->unsigned();
            $table->dateTime('check_in_at');
            $table->dateTime('check_out_at');
            $table->string('arival_from')->nullable();
            $table->integer('booking_type_id')->unsigned();
            $table->integer('booking_source_id')->unsigned()->nullable();
            $table->string('ref_no')->nullable();
            $table->string('purpose')->nullable();
            $table->text('remarks')->nullable();
            $table->integer('contact_id')->unsigned();
            $table->string('discount_type')->nullable();
            $table->decimal('discount_amount', 20, 2)->default(0);
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
        Schema::dropIfExists('bookings');
    }
}
