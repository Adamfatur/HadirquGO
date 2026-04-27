<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueIdToQuizAttemptsTable extends Migration
{
    public function up()
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->string('unique_id')->unique()->after('id'); // Add unique UUID column
        });
    }

    public function down()
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->dropColumn('unique_id');
        });
    }
}