<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderNoToTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('order_no')->nullable()->after('tax_amount');
            $table->decimal('gift_card', 20,2)->nullable()->after('order_no');
            $table->decimal('loyality_points', 20,2)->nullable()->after('gift_card');
            $table->decimal('coupon', 20,2)->nullable()->after('loyality_points');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            //
        });
    }
}
