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
        Schema::create('delivery_order_detail_records', function (Blueprint $table) {
            $table->id();
            $table->integer('do_id')->nullable();
            $table->integer('do_detail_id')->nullable();
            $table->integer('qty')->nullable();
            $table->integer('amount')->nullable();
            $table->integer('balance')->nullable();
            $table->date('date')->nullable();
            $table->integer('submit_status')->default(0)->nullable();
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
        Schema::dropIfExists('delivery_order_detail_records');
    }
};
