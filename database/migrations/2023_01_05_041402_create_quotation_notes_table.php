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
        Schema::create('quotation_notes', function (Blueprint $table) {
            $table->id();
            $table->text('Note')->nullable();
            $table->integer('QuotationId')->nullable();
            $table->integer('InvoiceId')->nullable();
            $table->string('list_file')->nullable();
            $table->string('list_name')->nullable();
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
        Schema::dropIfExists('quotation_notes');
    }
};
