<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRankingHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('ranking_histories', function (Blueprint $table) {
            $table->bigIncrements('id');

            // ID user (foreign key ke tabel users)
            $table->unsignedBigInteger('user_id');

            // Urutan peringkat
            $table->integer('rank');

            // Jumlah poin saat snapshot
            $table->bigInteger('points');

            // Jenis periode (harian, mingguan, bulanan, tahunan)
            $table->enum('period_type', ['daily','weekly','monthly','yearly']);

            // Tanggal mulai periode
            $table->date('period_start_date');

            // Tanggal akhir periode
            $table->date('period_end_date');

            // timestamp laravel (created_at, updated_at)
            $table->timestamps();

            // Relasi ke tabel users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Index untuk mempercepat query
            $table->index('period_type');
            $table->index(['period_start_date', 'period_end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('ranking_histories');
    }
}
