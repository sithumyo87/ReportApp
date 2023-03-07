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
        Schema::create('purchasing_orders', function (Blueprint $table) {
            $table->id();
            $table->integer('quo_id')->nullable();
            $table->integer('customer_id')->nullable();
            $table->string('Attn')->nullable();
            $table->string('Contact_phone')->nullable();
            $table->string('Company_name')->nullable();
            $table->string('Address')->nullable();
            $table->string('sub')->nullable();
            $table->string('po_code')->nullable();
            $table->integer('currency')->nullable();
            $table->integer('tax')->default(0)->nullable();
            $table->date('date')->nullable();
            $table->string('sign_name')->nullable();
            $table->string('file_name')->nullable();
            $table->integer('refer_no')->nullable();
            $table->integer('submit_status')->default(0);
            $table->date('received_date')->nullable();
            $table->integer('action')->default(1)->nullable();
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
        Schema::dropIfExists('purchasing_orders');
    }
};
