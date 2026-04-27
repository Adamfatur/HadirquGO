@if(isset($leaderboardContext))
    @php
        $rank = $leaderboardContext['rank'];
        $bannerStyle = '';
        $bannerTitle = '';
        $bannerSub = '';
        $showBanner = false;
        
        if ($rank == 1) {
            $bannerStyle = 'background: linear-gradient(135deg, #FFD700, #FFA500); color: #fff;';
            $bannerTitle = '👑 THE ULTIMATE CHAMPION 👑';
            $bannerSub = 'You are currently the #1 Player! Incredible dedication!';
            $showBanner = true;
        } elseif ($rank <= 3) {
            $bannerStyle = 'background: linear-gradient(135deg, #e5e4e2, #b4b4b4); color: #333;';
            $bannerTitle = '🥈 Podium Finisher 🥉';
            $bannerSub = 'You are in the TOP 3! Keep defending your throne!';
            $showBanner = true;
        } elseif ($rank <= 10) {
            $bannerStyle = 'background: linear-gradient(135deg, #62cff4, #2c67f2); color: #fff;';
            $bannerTitle = '✨ Elite TOP 10 ✨';
            $bannerSub = 'Outstanding! You are among the absolute best players.';
            $showBanner = true;
        } elseif ($rank <= 50) {
            $bannerStyle = 'background: linear-gradient(135deg, #f093fb, #f5576c); color: #fff;';
            $bannerTitle = '🔥 TOP 50 WARRIOR 🔥';
            $bannerSub = 'Great job! You have secured a spot in the official Top 50.';
            $showBanner = true;
        }
    @endphp

    @if($showBanner)
        <div class="card shadow-sm mb-4 animate-card shine-effect" 
             style="border-radius: 15px; border: none; overflow: hidden; {{ $bannerStyle }}">
            <div class="card-body p-3 text-center">
                <h4 class="fw-bold mb-1" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.1); font-size: 1.2rem;">{{ $bannerTitle }}</h4>
                <p class="small mb-0 opacity-90">{{ $bannerSub }}</p>
            </div>
        </div>
    @endif
@endif
