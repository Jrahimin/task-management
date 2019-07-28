<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReccurToAppointment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->integer('is_recurring')->nullable();
            $table->integer('parent_id')->unsigned()->nullable();
            $table->integer('frequency')->nullable();
            $table->integer('no_of_occurance')->nullable();
            $table->integer('end_occurance')->nullable();
            $table->integer('interval')->nullable();
            $table->string('days_in_week')->nullable();
            $table->integer('day_in_month')->nullable();
            $table->integer('part_of_month')->nullable();
            $table->date('recurring_end_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('is_recurring');
            $table->dropColumn('parent_id');
            $table->dropColumn('frequency');
            $table->dropColumn('no_of_occurance');
            $table->dropColumn('end_occurance');
            $table->dropColumn('interval');
            $table->dropColumn('days_in_week');
            $table->dropColumn('day_in_month');
            $table->dropColumn('part_of_month');
            $table->dropColumn('recurring_end_date');
        });
    }
}
