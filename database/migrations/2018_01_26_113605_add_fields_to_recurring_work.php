<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToRecurringWork extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recurring_work_orders', function (Blueprint $table) {
            $table->integer('appointment_day_add')->nullable();
            $table->time('appointment_start_time')->nullable();
            $table->time('appointment_end_time')->nullable();
            $table->integer('is_all_day')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recurring_work_orders', function (Blueprint $table) {
            $table->dropColumn('appointment_day_add')->nullable();
            $table->dropColumn('appointment_start_time')->nullable();
            $table->dropColumn('appointment_end_time')->nullable();
            $table->dropColumn('is_all_day')->nullable();
        });
    }
}
