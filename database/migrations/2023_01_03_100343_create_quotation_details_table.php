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
        Schema::create('quotation_details', function (Blueprint $table) {
            $table->id();
            $table->integer('Quotation_Id');
            $table->integer('Description')->nullable();
            $table->string('Unit_Price')->nullable();
            $table->integer('Qty')->nullable();
            $table->float('percent');
            $table->integer('dealer_id')->nullable();
            $table->string('form31_no')->nullable();
            $table->string('invoice_no')->nullable();
            $table->string('tax')->nullable();
            $table->integer('tax_amount')->default(0);
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
        Schema::dropIfExists('quotation_details');
    }
};
