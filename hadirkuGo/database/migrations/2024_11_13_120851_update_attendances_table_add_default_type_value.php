<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAttendancesTableAddDefaultTypeValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->string('type')->default('checkin')->change(); // Mengatur default value menjadi 'checkin'
        });
    }

    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->string('type')->default(null)->change(); // Menghilangkan nilai default saat rollback
        });
    }
}
