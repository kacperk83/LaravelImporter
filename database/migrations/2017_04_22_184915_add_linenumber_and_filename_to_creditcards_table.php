<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLinenumberAndFilenameToCreditcardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('creditcards', function (Blueprint $table) {
            $table->string('imported_from');
            $table->string('linenumber');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('creditcards', function (Blueprint $table) {
            $table->dropColumn('imported_from');
            $table->dropColumn('linenumber');
        });
    }
}
