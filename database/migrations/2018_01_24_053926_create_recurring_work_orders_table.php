<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecurringWorkOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recurring_work_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
            $table->integer('type');
            $table->integer('priority');
            $table->string('detailed_description')->nullable();
            $table->integer('is_billable')->nullable();
            $table->integer('bill_to');
            $table->string('purchase_order_no')->nullable();
            $table->integer('status');
            $table->integer('service_manager_id')->nullable();
            $table->integer('account_manager_id')->nullable();
            $table->integer('related_person_id')->nullable();
            $table->integer('frequency');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->integer('no_of_occurance')->nullable();
            $table->integer('end_occurance')->nullable();
            $table->integer('interval')->nullable();
            $table->time('time');
            $table->integer('day_in_week')->nullable();
            $table->integer('day_in_month')->nullable();
            $table->string('week_in_month')->nullable();
            $table->integer('part_of_month')->nullable();
            $table->integer('customer_id');
            $table->integer('contact_id');
            $table->integer('contract_id')->nullable();
            $table->integer('location_id');
            $table->integer('bill_location_id')->nullable();
            $table->integer('tax_code_id')->nullable();
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
        Schema::dropIfExists('recurring_work_orders');
    }
}
