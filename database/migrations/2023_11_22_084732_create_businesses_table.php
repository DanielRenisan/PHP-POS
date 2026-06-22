<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->string('business_code')->unique();
            $table->string('name', 256);
            $table->integer('currency_id')->unsigned();
            $table->date('start_date')->nullable();
            $table->string('logo')->nullable();
            $table->string('country')->nullable();
            $table->float('default_profit_percent', 5, 2)->default(0);
            $table->string('time_zone')->default('Asia/Kolkata');
            $table->string('reg_doc_no')->nullable();
            $table->string('fax_no')->nullable();
            $table->string('website')->nullable();
            $table->tinyInteger('fy_start_month')->default(1);
            $table->enum('accounting_method', ['fifo', 'lifo', 'avco'])->default('fifo');
            $table->text('address')->nullable();
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
        Schema::dropIfExists('businesses');
    }
}
