@php 
if (!function_exists('getRankStyle')) {
    function getRankStyle($rank) {
        $style = [
            'class' => 'border-primary shadow-sm',
            'glow' => 'border: 2px solid #3b82f6; box-shadow: 0 0 4px rgba(59, 130, 246, 0.3); padding: 2px;',
            'badge' => 'background: #eff6ff; color: #1d4ed8; border: 1px solid #93c5fd;',
            'iconColor' => 'text-primary',
            'crown' => false
        ];
        if ($rank == 1) {
            $style['class'] = 'border-warning shadow-lg';
            $style['glow'] = 'border: 3px solid #fbbf24; box-shadow: 0 0 15px #fbbf24, inset 0 0 10px #fbbf24; padding: 2px; background: linear-gradient(45deg, #fef3c7, #f59e0b);';
            $style['badge'] = 'background: linear-gradient(135deg, #fef3c7, #fde68a); color: #b45309; border: 1px solid #fbbf24; box-shadow: 0 2px 4px rgba(251,191,36,0.3); font-weight: 700;';
            $style['iconColor'] = 'text-warning';
            $style['crown'] = 'text-warning';
        } elseif ($rank == 2) {
            $style['class'] = 'border-secondary shadow-lg';
            $style['glow'] = 'border: 3px solid #9ca3af; box-shadow: 0 0 12px #9ca3af, inset 0 0 8px #9ca3af; padding: 2px; background: linear-gradient(45deg, #f3f4f6, #9ca3af);';
            $style['badge'] = 'background: linear-gradient(135deg, #f3f4f6, #e5e7eb); color: #4b5563; border: 1px solid #9ca3af; font-weight: 700;';
            $style['iconColor'] = 'text-secondary';
            $style['crown'] = 'text-secondary';
        } elseif ($rank == 3) {
            $style['class'] = 'shadow-lg';
            $style['glow'] = 'border: 3px solid #cd7f32; box-shadow: 0 0 10px #cd7f32; padding: 2px; background: linear-gradient(45deg, #fdf5e6, #cd7f32);';
            $style['badge'] = 'background: linear-gradient(135deg, #ffedd5, #fcd34d); color: #b45309; border: 1px solid #d97706; font-weight: 700;';
            $style['iconColor'] = 'text-warning';
            $style['crown'] = 'text-warning';
        } elseif ($rank <= 5) {
            $style['class'] = 'border-danger shadow-sm';
            $style['glow'] = 'border: 2px solid #ef4444; box-shadow: 0 0 8px rgba(239, 68, 68, 0.5); padding: 2px;';
            $style['badge'] = 'background: #fef2f2; color: #b91c1c; border: 1px solid #fca5a5;';
            $style['iconColor'] = 'text-danger';
        } elseif ($rank <= 10) {
            $style['class'] = 'border-success shadow-sm';
            $style['glow'] = 'border: 2px solid #10b981; box-shadow: 0 0 6px rgba(16, 185, 129, 0.4); padding: 2px;';
            $style['badge'] = 'background: #ecfdf5; color: #047857; border: 1px solid #6ee7b7;';
            $style['iconColor'] = 'text-success';
        } else {
            $style['class'] = 'border-primary shadow-sm';
            $style['glow'] = 'border: 2px solid #3b82f6; box-shadow: 0 0 4px rgba(59, 130, 246, 0.3); padding: 2px;';
            $style['badge'] = 'background: #eff6ff; color: #1d4ed8; border: 1px solid #93c5fd;';
            $style['iconColor'] = 'text-primary';
        }
        return $style;
    }
}
@endphp
