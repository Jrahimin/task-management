<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('status');
            $table->dateTime('start_date_time');
            $table->dateTime('end_date_time')->nullable();
            $table->double('hour_limit')->nullable();
            $table->double('money_limit')->nullable();
            $table->integer('unlimited')->nullable();
            $table->integer('overage_allow');
            $table->integer('type');
            $table->string('contract_no')->nullable();
            $table->string('purchase_order_no')->nullable();
            $table->string('description')->nullable();
            $table->string('terms')->nullable();
            $table->string('notes')->nullable();
            $table->double('subtotal')->nullable();
            $table->double('total')->nullable();
            $table->integer('customer_id')->unsigned();
            $table->integer('location_id')->unsigned();
            $table->integer('contact_id')->unsigned();
            $table->integer('tax_code_id')->unsigned()->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('contracts');
    }
}
