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
        Schema::create('advances', function (Blueprint $table) {
            $table->id();
            $table->integer('Invoice_Id')->nullable();
            $table->float('Advance_value')->nullable();
            $table->float('Balance')->nullable();
            $table->date('Date')->nullable();
            $table->date('receipt_date')->nullable()->default(null);
            $table->date('received_date')->nullable();
            $table->integer('nth_time')->nullable();
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
        Schema::dropIfExists('advances');
    }
};
