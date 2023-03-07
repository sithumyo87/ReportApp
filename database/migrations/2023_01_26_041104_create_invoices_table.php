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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();   
            $table->integer('Quotation_Id')->default(0)->nullable();
            $table->string('Attn')->nullable();
            $table->string('Sub')->nullable();
            $table->string('Invoice_No')->nullable();
            $table->date('Date')->nullable();
            $table->integer('Discount')->default(0)->nullable();
            $table->integer('Refer_status')->nullable();
            $table->integer('Advance')->nullable();
            $table->integer('FirstInvoice')->nullable();
            $table->integer('SecondInvoice')->nullable();
            $table->string('finv_date')->nullable();
            $table->string('sinv_date')->nullable();
            $table->string('Company_name')->nullable();
            $table->string('Contact_phone')->nullable();
            $table->string('Address')->nullable();
            $table->float('First_payment_amount')->nullable();
            $table->float('Second_payment_amount')->nullable();
            $table->string('sign_name')->nullable();
            $table->string('file_name')->nullable();
            $table->string('Date_INT')->nullable();
            $table->string('tax_id')->default(0);
            $table->text('bank_info')->nullable();
            $table->integer('submit_status')->nullable();
            $table->string('customer_tax_id')->nullable();
            $table->integer('Currency_type');
            $table->string('po_no')->nullable();
            $table->string('vender_id')->nullable();
            $table->string('form31_no')->nullable();
            $table->string('form31_issue_date')->nullable();
            $table->text('form31_files')->nullable();
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
        Schema::dropIfExists('invoices');
    }
};
