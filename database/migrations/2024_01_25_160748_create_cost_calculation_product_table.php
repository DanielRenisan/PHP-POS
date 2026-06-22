<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCostCalculationProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cost_calculation_product', function (Blueprint $table) {
            $table->id();
            $table->integer('food_calculation_id');
            $table->integer('ingredient_product_id');
            $table->string('ingredient_product_name');
            $table->decimal('ingredient_qty', 8, 2);
            $table->integer('ingredient_unit_id');
            $table->string('ingredient_unit_name');
            $table->decimal('ingredient_cost', 8, 2);
            $table->decimal('wast_qty', 8, 2);
            $table->integer('wast_unit_id');
            $table->string('wast_unit_name');
            $table->decimal('wast_cost', 8, 2);
            $table->decimal('total_cost', 8, 2);
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
        Schema::dropIfExists('cost_calculation_product');
    }
}
