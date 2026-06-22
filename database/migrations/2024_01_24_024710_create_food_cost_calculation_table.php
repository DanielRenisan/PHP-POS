<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodCostCalculationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food_calculation', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id')->nullable();
            $table->string('menu_name')->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('unit_id')->nullable();
            $table->string('unit_name')->nullable();
            $table->decimal('selling_price', 8, 2)->nullable();
            $table->decimal('wastage_cost', 8, 2)->nullable();
            $table->decimal('ingredients_cost', 8, 2)->nullable();
            $table->decimal('service_cost', 8, 2)->nullable();
            $table->decimal('extra_cost', 8, 2)->nullable();
            $table->decimal('gross_profit', 8, 2)->nullable();
            $table->decimal('labour_cost', 8, 2)->nullable();
            $table->decimal('total_cost', 8, 2)->nullable();
            $table->decimal('tax', 8, 2)->nullable();
            $table->decimal('food_cost', 8, 2)->nullable();
            $table->integer('labour_hour')->nullable();
            $table->string('prepare_time')->nullable();
            $table->string('service_time')->nullable();
            $table->string('total_time')->nullable();
            $table->longText('cooking_instruction')->nullable();
            $table->longText('service_instruction')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('food_cost_calculation');
    }
}
