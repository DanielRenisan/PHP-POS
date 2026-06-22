<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStationOrderTicketLinesTable extends Migration
{
    public function up()
    {
        Schema::create('station_order_ticket_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('station_order_ticket_id');
            $table->unsignedBigInteger('transaction_sell_line_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();

            $table->string('product_name', 255);
            $table->string('variation_name', 255)->nullable();
            $table->decimal('quantity', 18, 4)->default(0);
            $table->string('unit_name', 64)->nullable();
            $table->text('notes')->nullable();

            $table->enum('status', [
                'pending', 'accepted', 'ready', 'processing', 'completed', 'cancelled'
            ])->default('pending');

            $table->timestamps();

            $table->index('station_order_ticket_id');
            $table->index('transaction_sell_line_id');
            $table->index('status');

            $table->foreign('station_order_ticket_id', 'sotl_ticket_fk')
                ->references('id')->on('station_order_tickets')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('station_order_ticket_lines');
    }
}
