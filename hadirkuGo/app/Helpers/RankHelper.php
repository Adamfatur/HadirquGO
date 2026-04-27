<?php

namespace App\Helpers;

class RankHelper
{
    /**
     * Get level number for a user based on their total points.
     */
    public static function getLevelNumber($user): int
    {
        if (!$user) return 0;
        
        // Cache to prevent multiple queries for the same level list
        static $levels = null;
        if ($levels === null) {
            $levels = \App\Models\Level::orderBy('minimum_points', 'asc')->get();
        }
        
        $userPoints = $user->pointSummary->total_points ?? 0;
        
        $levelNumber = 0;
        foreach ($levels as $index => $level) {
            if ($userPoints >= $level->minimum_points) {
                $levelNumber = $index + 1;
            } else {
                break;
            }
        }
        
        return $levelNumber;
    }

    /**
     * The canonical Top 50 rank template — single source of truth.
     * Returns frame_color, frame_type, and title for a given rank.
     * Users outside Top 50 receive null values (no exclusive frame/title).
     */
    public static function getTop50Template(int $rank): array
    {
        if ($rank === 1)  return ['frame_color' => '#fbbf24', 'frame_type' => 'gold_supreme',    'title' => 'Supreme Champion'];
        if ($rank === 2)  return ['frame_color' => '#9ca3af', 'frame_type' => 'silver_elite',     'title' => 'Elite Grandmaster'];
        if ($rank === 3)  return ['frame_color' => '#cd7f32', 'frame_type' => 'bronze_flame',     'title' => 'Grandmaster'];
        if ($rank <= 5)   return ['frame_color' => '#ef4444', 'frame_type' => 'crimson_hero',     'title' => 'Master Elite'];
        if ($rank <= 10)  return ['frame_color' => '#10b981', 'frame_type' => 'emerald_sage',     'title' => 'Grand Scholar'];
        if ($rank <= 20)  return ['frame_color' => '#3b82f6', 'frame_type' => 'sapphire_knight',  'title' => 'Scholar Knight'];
        if ($rank <= 30)  return ['frame_color' => '#8b5cf6', 'frame_type' => 'violet_guardian',  'title' => 'Rising Guardian'];
        if ($rank <= 50)  return ['frame_color' => '#6366f1', 'frame_type' => 'indigo_pioneer',   'title' => 'Elite Pioneer'];
        return ['frame_color' => null, 'frame_type' => null, 'title' => null];
    }

    /**
     * Build an evaluation URL for a given user (by member_id).
     * Returns null if user has no member_id.
     */
    public static function getEvaluationUrl($user): ?string
    {
        if (!$user || !$user->member_id) return null;
        return route('lecturer.evaluation.show', ['member_id' => $user->member_id]);
    }

    public static function getRankStyle($rank, $frameColor = null)
    {
        $frameColor = $frameColor ?? '#3b82f6';
        
        // Default style
        $style = [
            'class' => 'border-primary shadow-sm',
            'glow' => "border: 2px solid {$frameColor}; box-shadow: 0 0 10px " . self::adjustBrightness($frameColor, -20) . "33;",
            'badge' => "background: #eff6ff; color: #1d4ed8; border: 1px solid #93c5fd;",
            'iconColor' => 'text-primary',
        ];

        if ($rank == 1) {
            $style['class'] = 'border-warning shadow-lg';
            $style['glow'] = "border: 4px solid #fbbf24; box-shadow: 0 0 20px #fbbf24, inset 0 0 12px #fbbf24; padding: 2px; background: linear-gradient(45deg, #fef3c7, #f59e0b);";
            $style['badge'] = "background: linear-gradient(135deg, #fef3c7, #fde68a); color: #b45309; border: 1px solid #fbbf24; box-shadow: 0 2px 8px rgba(251,191,36,0.5); font-weight: 800;";
            $style['iconColor'] = 'text-warning';
        } elseif ($rank == 2) {
            $style['class'] = 'border-secondary shadow-lg';
            $style['glow'] = "border: 4px solid #9ca3af; box-shadow: 0 0 18px #9ca3af, inset 0 0 10px #9ca3af; padding: 2px; background: linear-gradient(45deg, #f3f4f6, #9ca3af);";
            $style['badge'] = "background: linear-gradient(135deg, #f3f4f6, #e5e7eb); color: #4b5563; border: 1px solid #9ca3af; font-weight: 800;";
            $style['iconColor'] = 'text-secondary';
        } elseif ($rank == 3) {
            $style['class'] = 'shadow-lg';
            $style['glow'] = "border: 4px solid #cd7f32; box-shadow: 0 0 15px #cd7f32, inset 0 0 8px #cd7f32; padding: 2px; background: linear-gradient(45deg, #fdf5e6, #cd7f32);";
            $style['badge'] = "background: linear-gradient(135deg, #ffedd5, #fcd34d); color: #b45309; border: 1px solid #d97706; font-weight: 800;";
            $style['iconColor'] = 'text-warning';
        } elseif ($rank <= 10) {
            $style['class'] = 'border-danger shadow-sm';
            $style['glow'] = "border: 3px solid {$frameColor}; box-shadow: 0 0 15px {$frameColor}aa; padding: 2px;";
            $style['badge'] = "background: #fef2f2; color: #b91c1c; border: 1px solid #fca5a5; font-weight: 700;";
            $style['iconColor'] = 'text-danger';
        } elseif ($rank <= 50) {
            $style['class'] = 'border-primary shadow-sm';
            $style['glow'] = "border: 3px solid {$frameColor}; box-shadow: 0 0 10px {$frameColor}88; padding: 2px;";
            $style['badge'] = "background: #eff6ff; color: #1d4ed8; border: 1px solid #93c5fd; font-weight: 600;";
            $style['iconColor'] = 'text-primary';
        }

        return $style;
    }

    public static function getTitleBadge($title, $rank, $customFrameColor = null) {
        if (!$title) return '';
        $style = self::getRankStyle($rank, $customFrameColor);
        
        $extraClass = '';
        $cleanTitle = strtolower(trim($title));
        
        if (str_contains($cleanTitle, 'supreme champion')) {
            $extraClass = 'rank-shining title-supreme';
        } elseif (str_contains($cleanTitle, 'elite grandmaster')) {
            $extraClass = 'rank-shining title-elite-grandmaster';
        } elseif (str_contains($cleanTitle, 'grandmaster')) {
            $extraClass = 'rank-shining title-grandmaster';
        } elseif (str_contains($cleanTitle, 'master elite')) {
            $extraClass = 'rank-shining title-master-elite';
        }

        return '<div class="small mt-1 px-3 py-1 rounded-pill d-inline-flex align-items-center ' . $extraClass . '" style="font-size: 0.8rem; font-weight: 600; ' . $style['badge'] . '">
                    <i class="fas fa-award me-2" style="font-size: 0.85rem;"></i> ' . e($title) . '
                </div>';
    }

    /**
     * Helper to adjust brightness of a hex color
     */
    public static function adjustBrightness($hex, $steps) {
        $steps = max(-255, min(255, $steps));
        $hex = str_replace('#', '', $hex);
        if (strlen($hex) == 3) {
            $hex = str_repeat(substr($hex, 0, 1), 2).str_repeat(substr($hex, 1, 1), 2).str_repeat(substr($hex, 2, 1), 2);
        }
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $r = max(0, min(255, $r + $steps));
        $g = max(0, min(255, $g + $steps));
        $b = max(0, min(255, $b + $steps));

        $r_hex = str_pad(dechex($r), 2, '0', STR_PAD_LEFT);
        $g_hex = str_pad(dechex($g), 2, '0', STR_PAD_LEFT);
        $b_hex = str_pad(dechex($b), 2, '0', STR_PAD_LEFT);

        return '#' . $r_hex . $g_hex . $b_hex;
    }
}

