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
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->integer('Invoice_Id')->nullable();
            $table->integer('Quotation_Id')->nullable();
            $table->string('Attn')->nullable();
            $table->string('Sub')->nullable();
            $table->string('Receipt_No')->nullable();
            $table->date('Date')->nullable();
            $table->date('frec_date')->nullable();
            $table->date('srec_date')->nullable();
            $table->float('Discount')->nullable()->unsigned();
            $table->string('Tax')->nullable();
            $table->integer('Advance')->nullable();
            $table->integer('First_Receipt')->nullable();
            $table->integer('Second_Receipt')->nullable();
            $table->integer('Refer_status')->nullable()->unsigned();
            $table->string('Company_name')->nullable();
            $table->string('Contact_phone',50)->nullable();
            $table->text('Address')->nullable();
            $table->string('sign_name')->nullable();
            $table->string('file_name')->nullable();
            $table->string('Date_INT')->nullable();
            $table->string('Currency_type')->nullable();
            $table->date('first_received_date')->nullable();
            $table->date('second_received_date')->nullable();
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
        Schema::dropIfExists('receipts');
    }
};
