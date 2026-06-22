<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->bigInteger('contact_type_id')->unsigned();
            $table->bigInteger('customer_type_id')->unsigned()->nullable();
            $table->string('business_name')->nullable();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('address_one');
            $table->string('address_two')->nullable();
            $table->string('city')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('event_type')->nullable();
            $table->date('event_date')->nullable();
            $table->string('mobile_no');
            $table->string('telephone_no')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('tax_no')->nullable();
            $table->string('contact_group')->nullable();
            $table->string('professional')->nullable();
            $table->string('nationality_type')->nullable();
            $table->string('national_id')->nullable();
            $table->text('police_info')->nullable();
            $table->string('nationality')->nullable();
            $table->string('passport_no')->nullable();
            $table->string('purpose_of_visit')->nullable();
            $table->string('visa_no')->nullable();
            $table->string('payment_settle_days')->nullable();
            $table->decimal('credit_payment', 20,2)->nullable();
            $table->decimal('open_balance', 20,2)->nullable();
            $table->string('image')->nullable();
            $table->string('status')->default('Active');
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
        Schema::dropIfExists('contacts');
    }
}
