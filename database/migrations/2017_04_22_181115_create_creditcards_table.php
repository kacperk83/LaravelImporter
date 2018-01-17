<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateCreditcardsTable
 *
 *
 * @author Kacper Kowalski kacperk83@gmail.com
 */
class CreateCreditcardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('creditcards', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->nullable();
            $table->string('number')->nullable();
            $table->string('name')->nullable();
            $table->date('expiration_date')->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::table('creditcards', function ($table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('creditcards');
    }
}
