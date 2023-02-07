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
        Schema::create('delivery_orders', function (Blueprint $table) {
            $table->id();
            $table->string('Attn')->nullable();
            $table->string('Contact_phone')->nullable();
            $table->string('Company_name')->nullable();
            $table->string('Address')->nullable();
            $table->string('sub')->nullable();
            $table->string('do_code')->nullable();
            $table->date('date')->nullable();
            $table->integer('quo_id')->nullable();
            $table->integer('inv_id')->nullable();
            $table->string('po_no')->nullable();
            $table->string('received_name')->nullable();
            $table->string('received_sign')->nullable();
            $table->string('delivered_name')->nullable();
            $table->string('delivered_sign')->nullable();
            $table->integer('submit_status')->default(0);
            $table->integer('disable')->default(0);
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
        Schema::dropIfExists('delivery_orders');
    }
};
