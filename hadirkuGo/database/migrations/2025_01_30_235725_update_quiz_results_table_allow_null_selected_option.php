<?php

// database/migrations/xxxx_xx_xx_update_quiz_results_table_allow_null_selected_option.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateQuizResultsTableAllowNullSelectedOption extends Migration
{
    public function up()
    {
        Schema::table('quiz_results', function (Blueprint $table) {
            $table->string('selected_option')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('quiz_results', function (Blueprint $table) {
            $table->string('selected_option')->nullable(false)->change();
        });
    }
}
