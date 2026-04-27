<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_user_points_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPointsTable extends Migration
{
    public function up()
    {
        Schema::create('user_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('points')->default(0);
            $table->string('description')->nullable(); // Untuk catatan tambahan
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_points');
    }
}

