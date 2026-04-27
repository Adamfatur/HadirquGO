<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserLeaderboard;
use App\Helpers\RankHelper;

class SyncLeaderboardFrames extends Command
{
    /**
     * The name and signature of the console command.
     * Usage: php artisan leaderboard:sync-frames [--category=top_points]
     */
    protected $signature = 'leaderboard:sync-frames 
                            {--category=top_points : The leaderboard category to sync (default: top_points)}
                            {--dry-run : Preview changes without saving}';

    /**
     * The console command description.
     */
    protected $description = 'Sync frame_color, frame_type, and title fields in user_leaderboards based on current_rank using RankHelper::getTop50Template()';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $category = $this->option('category');
        $dryRun   = $this->option('dry-run');

        $this->info("🔄 Syncing leaderboard frames for category: [{$category}]" . ($dryRun ? ' [DRY RUN]' : ''));

        $entries = UserLeaderboard::with('user')
            ->where('category', $category)
            ->orderBy('current_rank', 'asc')
            ->get();

        if ($entries->isEmpty()) {
            $this->warn("⚠️  No entries found for category [{$category}]. Nothing to sync.");
            return 0;
        }

        $updated  = 0;
        $cleared  = 0;

        $headers  = ['Rank', 'User', 'Frame Color', 'Frame Type', 'Title'];
        $rows     = [];

        foreach ($entries as $entry) {
            $template = RankHelper::getTop50Template((int) $entry->current_rank);

            $rows[] = [
                '#' . $entry->current_rank,
                $entry->user?->name ?? 'Unknown',
                $template['frame_color'] ?? '—',
                $template['frame_type']  ?? '—',
                $template['title']       ?? '—',
            ];

            if (!$dryRun) {
                $entry->update([
                    'frame_color' => $template['frame_color'],
                    'frame_type'  => $template['frame_type'],
                    'title'       => $template['title'],
                ]);
            }

            if ($template['title'] !== null) {
                $updated++;
            } else {
                $cleared++;
            }
        }

        $this->table($headers, $rows);

        if ($dryRun) {
            $this->line('');
            $this->comment("ℹ️  Dry run complete. {$updated} would be assigned frames/titles, {$cleared} would be cleared.");
        } else {
            $this->line('');
            $this->info("✅ Done! {$updated} entries updated with frames/titles, {$cleared} entries cleared (outside Top 50).");
        }

        return 0;
    }
}
