<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserLeaderboard;

class UpdateLeaderboardStylesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = ['top_levels', 'top_points'];
        foreach ($categories as $cat) {
            $entries = UserLeaderboard::where('category', $cat)->orderBy('current_rank', 'asc')->take(50)->get();
            foreach ($entries as $entry) {
                $rank = $entry->current_rank;
                $frameColor = '#3b82f6'; // Default Blue
                $frameType = 'solid';
                $title = $entry->title;

                if ($rank == 1) {
                    $frameColor = '#fbbf24'; // Gold
                    $frameType = 'premium';
                    $title = $title ?? 'Supreme Champion';
                } elseif ($rank == 2) {
                    $frameColor = '#9ca3af'; // Silver
                    $frameType = 'glow';
                    $title = $title ?? 'Elite Silver';
                } elseif ($rank == 3) {
                    $frameColor = '#cd7f32'; // Bronze
                    $frameType = 'glow';
                    $title = $title ?? 'Heroic Bronze';
                } elseif ($rank <= 5) {
                    $frameColor = '#ef4444'; // Red
                    $frameType = 'solid';
                    $title = $title ?? 'Elite Challenger';
                } elseif ($rank <= 10) {
                    $frameColor = '#10b981'; // Green
                    $frameType = 'solid';
                    $title = $title ?? 'Rising Star';
                }

                $entry->update([
                    'frame_color' => $frameColor,
                    'frame_type' => $frameType,
                    'title' => $title
                ]);
            }
        }
    }
}
