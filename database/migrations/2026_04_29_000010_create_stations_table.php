<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStationsTable extends Migration
{
    public function up()
    {
        Schema::create('stations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 191);
            $table->string('code', 16)->unique();
            $table->string('ticket_name', 191);
            $table->string('slug', 191)->unique();
            $table->string('description', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0);
            $table->integer('created_by')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('is_active');
            $table->index('display_order');
        });
    }

    public function down()
    {
        Schema::dropIfExists('stations');
    }
}
