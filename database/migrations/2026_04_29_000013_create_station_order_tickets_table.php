<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStationOrderTicketsTable extends Migration
{
    public function up()
    {
        Schema::create('station_order_tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('transaction_id');
            $table->unsignedInteger('station_id');
            $table->unsignedInteger('printer_id')->nullable();

            $table->string('ticket_code', 16);
            $table->string('ticket_name', 191);
            $table->string('ticket_no', 64)->nullable();

            $table->enum('status', ['pending', 'printed', 'failed', 'cancelled', 'reprinted'])
                ->default('pending');

            $table->json('payload_json')->nullable();
            $table->text('failed_reason')->nullable();
            $table->unsignedInteger('retry_count')->default(0);
            $table->timestamp('printed_at')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index('transaction_id');
            $table->index('station_id');
            $table->index('status');
            $table->index('ticket_code');

            $table->foreign('station_id')
                ->references('id')->on('stations')
                ->onDelete('cascade');
            $table->foreign('printer_id')
                ->references('id')->on('printers')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('station_order_tickets');
    }
}
