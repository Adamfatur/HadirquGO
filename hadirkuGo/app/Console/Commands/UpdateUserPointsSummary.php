<?php

namespace App\Console\Commands;

use App\Http\Controllers\UserPointSummaryController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateUserPointsSummary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:user-points';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update user points and team points summaries';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('User points update task started.');

        // Membuat instance dari UserPointSummaryController
        $controller = new UserPointSummaryController();

        // Memanggil fungsi untuk memperbarui poin user dan tim
        $controller->updateUserPoints();

        Log::info('User points update task completed.');
    }
}

