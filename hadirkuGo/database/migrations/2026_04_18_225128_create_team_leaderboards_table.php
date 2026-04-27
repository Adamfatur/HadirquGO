<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_leaderboards', function (Blueprint $table) {
            $table->id();
            $table->string('category')->index();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->decimal('score', 15, 2)->default(0);
            $table->integer('current_rank')->index();
            $table->integer('previous_rank')->nullable();
            $table->timestamps();

            $table->unique(['category', 'team_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_leaderboards');
    }
};
