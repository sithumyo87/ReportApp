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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->string('Attn');
            $table->string('Company_name');
            $table->string('Contact_phone',50);
            $table->text('Address')->nullable();
            $table->string('Sub');
            $table->string('Serial_No');
            $table->date('Date');
            $table->float('Discount')->nullable()->unsigned();
            $table->string('Refer_No')->nullable();
            $table->integer('Refer_status')->nullable()->unsigned();
            $table->string('Currency_type');
            $table->integer('SubmitStatus')->nullable()->unsigned();
            $table->string('sign_name',100)->nullable();
            $table->string('file_name',100)->nullable();
            $table->string('Date_INT')->nullable();
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
        Schema::dropIfExists('quotations');
    }
};
