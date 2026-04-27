<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveTeamIdFromWeeklyRankingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('weekly_rankings', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['team_id']);

            // Drop the 'team_id' column
            $table->dropColumn('team_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('weekly_rankings', function (Blueprint $table) {
            // Add back the 'team_id' column
            $table->unsignedBigInteger('team_id')->nullable()->after('user_id');

            // Restore foreign key constraint
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
        });
    }
}
