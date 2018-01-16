<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateCreditcardImportLocationsTable
 *
 *
 * @author Kacper Kowalski kacperk83@gmail.com
 */
class CreateCreditcardImportLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('creditcard_import_locations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('file_hash');
            $table->integer('document_id');
            $table->integer('creditcard_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('creditcard_import_locations', function ($table) {
            $table->foreign('creditcard_id')->references('id')->on('creditcards');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('creditcard_import_locations');
    }
}
