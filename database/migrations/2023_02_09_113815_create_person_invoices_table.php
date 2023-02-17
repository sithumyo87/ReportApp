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
        Schema::create('person_invoices', function (Blueprint $table) {
            $table->id();
            $table->integer('type')->default(1);
            $table->string('name',100);
            $table->string('position',100)->nullable();
            $table->string('phone',100);
            $table->string('phone_other',100)->nullable();
            $table->string('company');
            $table->string('email',100);
            $table->text('address');
            $table->tinyInteger('action')->default(1);
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
        Schema::dropIfExists('person_invoices');
    }
};
