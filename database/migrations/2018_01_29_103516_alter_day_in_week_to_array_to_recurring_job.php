<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDayInWeekToArrayToRecurringJob extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recurring_work_orders', function (Blueprint $table) {
            $table->dropColumn('day_in_week');
            $table->string('days_in_week')->nullable();
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
            $table->dropColumn('days_in_week');
            $table->integer('day_in_week')->nullable();
        });
    }
}
