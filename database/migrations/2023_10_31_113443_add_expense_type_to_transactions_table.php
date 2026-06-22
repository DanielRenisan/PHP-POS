<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExpenseTypeToTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('expense_type')->after('payment_status')->nullable();
            $table->string('room_no')->after('expense_type')->nullable();
            $table->integer('staff_id')->unsigned()->after('room_no')->nullable();
            $table->integer('category_id')->unsigned()->after('staff_id')->nullable();
            $table->integer('sub_category_id')->unsigned()->after('category_id')->nullable();
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
