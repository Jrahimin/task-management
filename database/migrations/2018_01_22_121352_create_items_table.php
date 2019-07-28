<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type');
            $table->string('name');
            $table->string('description')->nullable();
            $table->double('price');
            $table->double('cost');
            $table->integer('status')->nullable();
            $table->integer('taxable')->nullable();
            $table->string('note')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('mfr_item_no')->nullable();
            $table->string('mfr_item_description')->nullable();
            $table->double('list_price')->nullable();
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
        Schema::dropIfExists('items');
    }
}
