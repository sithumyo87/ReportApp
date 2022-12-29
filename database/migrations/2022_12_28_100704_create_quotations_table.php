<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use PhpParser\Node\NullableType;

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
            $table->integer('customer_id')->unsigned();
            $table->string('Attn');
            $table->string('Company_name');
            $table->string('Contact_phone',50);
            $table->text('Address')->nullable();
            $table->string('Sub');
            $table->string('Serial_No');
            $table->date('Date');
            $table->float('Discount')->nullable()->unsigned();
            $table->string('Refer_No')->nullable();
            $table->tinyInteger('Refer_status',2)->nullable();
            $table->string('Currency_type');
            $table->tinyInteger('SubmitStatus',1)->nullable();
            $table->string('sign_name',100);
            $table->string('file_name',100);
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
