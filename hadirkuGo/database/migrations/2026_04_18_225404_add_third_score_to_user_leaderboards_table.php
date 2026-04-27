<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_leaderboards', function (Blueprint $table) {
            $table->decimal('third_score', 15, 2)->nullable()->after('secondary_score');
        });
    }

    public function down(): void
    {
        Schema::table('user_leaderboards', function (Blueprint $table) {
            $table->dropColumn('third_score');
        });
    }
};
