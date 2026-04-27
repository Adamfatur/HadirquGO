<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFrameColorToUserLeaderboardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_leaderboards', function (Blueprint $table) {
            if (!Schema::hasColumn('user_leaderboards', 'frame_color')) {
                $table->string('frame_color')->nullable()->after('title');
            }
            if (!Schema::hasColumn('user_leaderboards', 'frame_type')) {
                $table->string('frame_type')->nullable()->after('frame_color');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_leaderboards', function (Blueprint $table) {
            $table->dropColumn(['frame_color', 'frame_type']);
        });
    }
}
