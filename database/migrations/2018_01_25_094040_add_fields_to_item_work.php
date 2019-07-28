<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToItemWork extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_work_order', function (Blueprint $table) {
            $table->date('date');
            $table->double('quantity');
            $table->double('price')->nullable();
            $table->double('cost')->nullable();
            $table->string('description');
            $table->string('comment')->nullable();
            $table->integer('billing_status');
            $table->integer('tax_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_work_order', function (Blueprint $table) {
            $table->dropColumn('date');
            $table->dropColumn('quantity');
            $table->dropColumn('price');
            $table->dropColumn('cost');
            $table->dropColumn('description');
            $table->dropColumn('comment');
            $table->dropColumn('billing_status');
            $table->dropColumn('tax_status');
        });
    }
}
