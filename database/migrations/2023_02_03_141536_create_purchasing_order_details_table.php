<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchasing_order_details', function (Blueprint $table) {
            $table->id();
            $table->integer('po_id');
            $table->integer('dealer_id')->nullable();
            $table->text('description')->nullable();
            $table->string('price')->nullable();
            $table->integer('qty')->nullable();
            $table->string('form31_no')->nullable();
            $table->string('invoice_no')->nullable();
            $table->integer('tax')->nullable();
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
        Schema::dropIfExists('purchasing_order_details');
    }
};
