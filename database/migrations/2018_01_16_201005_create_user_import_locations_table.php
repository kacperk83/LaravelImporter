<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateUserImportLocationsTable
 *
 *
 * @author Kacper Kowalski kacperk83@gmail.com
 */
class CreateUserImportLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_import_locations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('file_hash');
            $table->integer('document_id');
            $table->integer('user_id')->nullable()->unsigned();
            $table->timestamps();
        });

        Schema::table('user_import_locations', function ($table) {
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
        Schema::dropIfExists('user_import_locations');
    }
}
