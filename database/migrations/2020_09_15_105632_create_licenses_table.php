<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLicensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subscriber_id');
            $table->unsignedBigInteger('user_id');
            $table->string('license_type');
            $table->date('start_bonus_date')->nullable();
            $table->date('end_bonus_date')->nullable();
            $table->integer('bonus_days')->nullable();
            $table->date('start_discount_date')->nullable();
            $table->date('end_discount_date')->nullable();
            $table->integer('discount_days')->nullable();
            $table->string('status')->default("forecast");

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('subscriber_id')->references('id')->on('subscribers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('licenses');
    }
}
