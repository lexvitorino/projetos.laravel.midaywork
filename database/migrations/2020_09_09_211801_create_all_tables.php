<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
        });

        Schema::create('working_hours', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subscriber_id');
            $table->unsignedBigInteger('user_id');
            $table->date('work_date')->nullable();
            $table->time('time1')->nullable();
            $table->time('time2')->nullable();
            $table->time('time3')->nullable();
            $table->time('time4')->nullable();
            $table->integer('worked_time')->nullable();

            $table->unique(['user_id', 'work_date']);
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('subscriber_id')->references('id')->on('subscribers');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('subscriber_id')
                ->after('id');

            $table->date('start_date')
                ->after('password')
                ->nullable();

            $table->date('end_date')
                ->after('start_date')
                ->nullable();

            $table->boolean('is_admin')
                ->after('end_date')
                ->default(1);

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
        Schema::dropIfExists('subscribers');
        Schema::dropIfExists('working_hours');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('subscriber_id');
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
            $table->dropColumn('is_admin');
        });
    }
}
