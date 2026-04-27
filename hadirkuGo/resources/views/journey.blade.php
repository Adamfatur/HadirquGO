<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="HadirquGO Journey - {{ $stats['user_name'] ?? 'User' }}">
    <meta property="og:title" content="HadirquGO - Journey of {{ $stats['user_name'] ?? 'User' }}">
    <meta property="og:image" content="https://drive.pastibisa.app/1731549866_67355aaaea1f0.png">
    <meta property="og:url" content="{{ url()->current() }}">
    <title>Journey of {{ $stats['user_name'] ?? 'User' }}</title>
    <link rel="icon" href="https://drive.pastibisa.app/1731549860_67355aa46d47e.jpg" type="image/x-icon">
    <meta name="theme-color" content="#1e3a8a">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@700&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        html { overflow: hidden; height: 100%; }
        body { font-family: 'Poppins', sans-serif; color: #e0e0e0; height: 100%; overflow: hidden; }

        /* Preloader */
        #preloader { position: fixed; inset: 0; background: #0f1b2d; display: flex; align-items: center; justify-content: center; flex-direction: column; z-index: 9999; transition: opacity 0.8s ease; }
        #preloader h2 { font-family: 'Cinzel Decorative', cursive; font-size: 2.5rem; color: #fff; text-shadow: 0 0 10px gold, 0 0 20px #ffdf00; animation: flicker 2s infinite; }
        #preloader .spinner { width: 40px; height: 40px; border: 4px solid #ccac00; border-top-color: gold; border-radius: 50%; margin: 20px auto 10px; animation: spin 1s linear infinite; }
        #preloader p { font-size: 0.9rem; color: gold; }
        @keyframes flicker { 0%,100%{opacity:1} 50%{opacity:0.6} }
        @keyframes spin { 100%{transform:rotate(360deg)} }

        /* Lang toggle */
        .lang-toggle { position: fixed; top: 16px; right: 16px; z-index: 100; display: flex; gap: 4px; background: rgba(0,0,0,0.5); border-radius: 20px; padding: 3px; backdrop-filter: blur(8px); }
        .lang-btn { padding: 5px 14px; border: none; border-radius: 16px; font-size: 0.75rem; font-weight: 600; cursor: pointer; transition: all 0.3s; background: transparent; color: rgba(255,255,255,0.6); }
        .lang-btn.active { background: gold; color: #1a1a2e; }

        /* Slide indicator */
        .slide-indicator { position: fixed; right: 16px; top: 50%; transform: translateY(-50%); z-index: 100; display: flex; flex-direction: column; gap: 8px; }
        .slide-dot { width: 8px; height: 8px; border-radius: 50%; background: rgba(255,255,255,0.3); transition: all 0.3s; cursor: pointer; }
        .slide-dot.active { background: gold; transform: scale(1.4); box-shadow: 0 0 8px gold; }

        /* Slides */
        .slides-wrapper { height: 100vh; transition: transform 0.7s cubic-bezier(0.65, 0, 0.35, 1); }
        .slide { height: 100vh; width: 100%; display: flex; align-items: center; justify-content: center; text-align: center; position: relative; overflow: hidden; }
        .slide-bg { position: absolute; inset: 0; background-size: cover; background-position: center; z-index: 0; }
        .slide-bg::after { content: ''; position: absolute; inset: 0; background: rgba(0,0,0,0.55); }
        .slide canvas { position: absolute; inset: 0; width: 100%; height: 100%; z-index: 1; pointer-events: none; }
        .slide-content { position: relative; z-index: 2; max-width: 500px; width: 90%; padding: 28px 24px; background: rgba(15,27,45,0.75); border-radius: 20px; border: 1px solid rgba(255,215,0,0.2); backdrop-filter: blur(12px); }
        .slide-content.visible { animation: slideUp 0.6s ease forwards; }
        @keyframes slideUp { from{opacity:0;transform:translateY(40px)} to{opacity:1;transform:translateY(0)} }

        .slide-title { font-family: 'Cinzel Decorative', cursive; font-size: 1.6rem; color: #f4d03f; margin-bottom: 12px; text-shadow: 1px 1px 3px #000; }
        .slide-icon { font-size: 2.5rem; margin-bottom: 16px; }
        .slide-text { font-size: 0.95rem; line-height: 1.7; color: rgba(255,255,255,0.85); }
        .slide-text strong { color: #f4d03f; }
        .stat-highlight { display: inline-block; background: rgba(244,208,63,0.15); border: 1px solid rgba(244,208,63,0.3); border-radius: 8px; padding: 2px 10px; margin: 2px; font-weight: 600; color: #f4d03f; }
        .level-img { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid gold; box-shadow: 0 0 20px rgba(255,215,0,0.4); margin-bottom: 12px; }

        /* Nav button */
        .nav-btn { position: fixed; bottom: 24px; left: 50%; transform: translateX(-50%); z-index: 100; background: rgba(15,27,45,0.8); color: gold; border: 1.5px solid rgba(255,215,0,0.4); padding: 10px 28px; border-radius: 25px; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 0.85rem; cursor: pointer; backdrop-filter: blur(8px); transition: all 0.3s; display: none; }
        .nav-btn:hover { background: rgba(244,208,63,0.2); }

        /* Start button */
        .start-btn { padding: 14px 36px; background: linear-gradient(135deg, #f4d03f, #e6b800); border: none; color: #1a1a2e; font-family: 'Cinzel Decorative', cursive; font-size: 1.2rem; border-radius: 30px; cursor: pointer; box-shadow: 0 0 25px rgba(255,215,0,0.4); transition: all 0.3s; }
        .start-btn:hover { transform: scale(1.05); box-shadow: 0 0 35px rgba(255,215,0,0.6); }

        /* Share */
        .share-links { display: flex; flex-wrap: wrap; justify-content: center; gap: 8px; margin-top: 16px; }
        .share-links a, .share-links button { display: inline-flex; align-items: center; gap: 6px; padding: 8px 14px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,215,0,0.3); border-radius: 20px; color: gold; text-decoration: none; font-size: 0.8rem; cursor: pointer; transition: background 0.3s; }
        .share-links a:hover, .share-links button:hover { background: rgba(255,255,255,0.2); }

        @media (max-width: 480px) {
            .slide-title { font-size: 1.3rem; }
            .slide-text { font-size: 0.85rem; }
            .slide-icon { font-size: 2rem; }
            .slide-content { padding: 20px 16px; }
            .start-btn { font-size: 1rem; padding: 12px 28px; }
            .level-img { width: 60px; height: 60px; }
        }
    </style>
</head>
<body>

@php
    $memberId = request()->route('memberId');
    $shareUrl = route('journey.stats', ['memberId' => $memberId]);
    $userName = $stats['user_name'] ?? 'User';

    // Collect all available background images: level images from DB + fallbacks
    $lvlImgs = $stats['level_images'] ?? [];
    $fallbackBgs = [
        'https://drive.pastibisa.app/1731905901_673ac96d00623.png',
        'https://drive.pastibisa.app/1731904801_673ac52145424.png',
        'https://drive.pastibisa.app/1731905892_673ac964d51a5.jpeg',
        'https://drive.pastibisa.app/1731905925_673ac985d16dd.png',
        'https://drive.pastibisa.app/1731905938_673ac992a7b30.png',
        'https://drive.pastibisa.app/1731906133_673aca55b5ac2.png',
    ];
    $allBgs = array_merge($lvlImgs, $fallbackBgs);
    // Helper to pick a unique bg per slide index
    $bg = fn($i) => $allBgs[$i % count($allBgs)];

    $levelImg = $stats['level_image'] ?? $bg(2);
    $avgTime = $stats['avg_checkin_time'] ?? '--:--';

    $slides = [
        [
            'bg' => $bg(0), 'particle' => 'stars', 'icon' => 'fas fa-scroll',
            'title_en' => 'Your Journey with HadirquGO', 'title_id' => 'Perjalananmu dengan HadirquGO',
            'text_en' => 'Discover the milestones and memories you\'ve created along the way.',
            'text_id' => 'Temukan pencapaian dan kenangan yang telah kamu ciptakan.',
            'is_welcome' => true,
        ],
        [
            'bg' => $bg(1), 'particle' => 'snow', 'icon' => 'fas fa-compass',
            'title_en' => "Journey of {$userName}", 'title_id' => "Perjalanan {$userName}",
            'text_en' => "{$userName} has spent <strong>{$stats['total_duration']}</strong> across <span class=\"stat-highlight\">{$stats['session_count']} sessions</span> and collected <span class=\"stat-highlight\">" . number_format($stats['total_points']) . " points</span>.",
            'text_id' => "{$userName} telah menghabiskan <strong>{$stats['total_duration']}</strong> dalam <span class=\"stat-highlight\">{$stats['session_count']} sesi</span> dan mengumpulkan <span class=\"stat-highlight\">" . number_format($stats['total_points']) . " poin</span>.",
        ],
        [
            'bg' => $levelImg, 'particle' => 'fireflies', 'icon' => 'fas fa-gem',
            'title_en' => 'Current Level', 'title_id' => 'Level Saat Ini',
            'text_en' => "{$userName} has reached <strong>{$stats['level_name']}</strong> (Level {$stats['level_number']})." . ($stats['next_level_name'] ? " Only <span class=\"stat-highlight\">" . number_format($stats['points_to_next']) . " points</span> to reach <strong>{$stats['next_level_name']}</strong>!" : ' The highest level achieved!'),
            'text_id' => "{$userName} telah mencapai <strong>{$stats['level_name']}</strong> (Level {$stats['level_number']})." . ($stats['next_level_name'] ? " Hanya <span class=\"stat-highlight\">" . number_format($stats['points_to_next']) . " poin</span> lagi menuju <strong>{$stats['next_level_name']}</strong>!" : ' Level tertinggi telah dicapai!'),
            'show_level_img' => true,
        ],
        [
            'bg' => $bg(3), 'particle' => 'fire', 'icon' => 'fas fa-fire',
            'title_en' => 'Dedication & Streak', 'title_id' => 'Dedikasi & Streak',
            'text_en' => ($stats['first_date'] ? "The journey began on <strong>{$stats['first_date']}</strong>. " : '') . "Active for <span class=\"stat-highlight\">{$stats['unique_days']} unique days</span> with a longest streak of <span class=\"stat-highlight\">{$stats['max_streak']} consecutive days</span>.",
            'text_id' => ($stats['first_date'] ? "Perjalanan dimulai pada <strong>{$stats['first_date']}</strong>. " : '') . "Aktif selama <span class=\"stat-highlight\">{$stats['unique_days']} hari unik</span> dengan streak terpanjang <span class=\"stat-highlight\">{$stats['max_streak']} hari berturut-turut</span>.",
        ],
        [
            'bg' => $bg(4), 'particle' => 'green', 'icon' => 'fas fa-map-marker-alt',
            'title_en' => 'Favorite Location', 'title_id' => 'Lokasi Favorit',
            'text_en' => $stats['favorite_location'] ? "{$userName} spent the most time at <strong>{$stats['favorite_location']['name']}</strong> with a total of <span class=\"stat-highlight\">{$stats['favorite_location']['duration']}</span>. Explored <span class=\"stat-highlight\">{$stats['total_locations']} locations</span> in total." : "{$userName} hasn't visited any location yet.",
            'text_id' => $stats['favorite_location'] ? "{$userName} paling banyak menghabiskan waktu di <strong>{$stats['favorite_location']['name']}</strong> dengan total <span class=\"stat-highlight\">{$stats['favorite_location']['duration']}</span>. Menjelajahi <span class=\"stat-highlight\">{$stats['total_locations']} lokasi</span> secara keseluruhan." : "{$userName} belum mengunjungi lokasi manapun.",
        ],
        [
            'bg' => $bg(5), 'particle' => 'embers', 'icon' => 'fas fa-sun',
            'title_en' => 'Morning Person', 'title_id' => 'Si Pagi Hari',
            'text_en' => "{$userName} has been a Morning Person <span class=\"stat-highlight\">{$stats['morning_person_count']}x</span>." . ($stats['earliest_checkin'] ? " Earliest ever: <strong>{$stats['earliest_checkin']}</strong>." : '') . " Average check-in time: <span class=\"stat-highlight\">{$avgTime}</span>.",
            'text_id' => "{$userName} telah menjadi Morning Person <span class=\"stat-highlight\">{$stats['morning_person_count']}x</span>." . ($stats['earliest_checkin'] ? " Paling awal: <strong>{$stats['earliest_checkin']}</strong>." : '') . " Rata-rata waktu check-in: <span class=\"stat-highlight\">{$avgTime}</span>.",
        ],
        [
            'bg' => $bg(6), 'particle' => 'rain', 'icon' => 'fas fa-stopwatch',
            'title_en' => 'Duration Records', 'title_id' => 'Rekor Durasi',
            'text_en' => "Longest session: <span class=\"stat-highlight\">{$stats['longest_duration']}</span>." . ($stats['shortest_duration'] ? " Shortest: <span class=\"stat-highlight\">{$stats['shortest_duration']}</span>." : '') . ($stats['latest_checkin'] ? " Latest check-in ever: <strong>{$stats['latest_checkin']}</strong>." : ''),
            'text_id' => "Sesi terlama: <span class=\"stat-highlight\">{$stats['longest_duration']}</span>." . ($stats['shortest_duration'] ? " Terpendek: <span class=\"stat-highlight\">{$stats['shortest_duration']}</span>." : '') . ($stats['latest_checkin'] ? " Check-in paling akhir: <strong>{$stats['latest_checkin']}</strong>." : ''),
        ],
        [
            'bg' => $bg(7), 'particle' => 'confetti', 'icon' => 'fas fa-trophy',
            'title_en' => 'Achievements', 'title_id' => 'Pencapaian',
            'text_en' => "{$userName} has unlocked <span class=\"stat-highlight\">{$stats['total_achievements']} achievements</span>. With <span class=\"stat-highlight\">" . number_format($stats['total_points']) . " total points</span> and <span class=\"stat-highlight\">{$stats['session_count']} sessions</span>, the dedication speaks for itself.",
            'text_id' => "{$userName} telah membuka <span class=\"stat-highlight\">{$stats['total_achievements']} pencapaian</span>. Dengan <span class=\"stat-highlight\">" . number_format($stats['total_points']) . " total poin</span> dan <span class=\"stat-highlight\">{$stats['session_count']} sesi</span>, dedikasinya berbicara sendiri.",
        ],
        [
            'bg' => $bg(8), 'particle' => 'fireflies', 'icon' => 'fas fa-heart',
            'title_en' => 'Thank You!', 'title_id' => 'Terima Kasih!',
            'text_en' => "Thank you for following the journey of {$userName}. May this inspire you to keep growing!",
            'text_id' => "Terima kasih telah mengikuti perjalanan {$userName}. Semoga ini menginspirasimu untuk terus berkembang!",
            'is_final' => true,
        ],
    ];
    $totalSlides = count($slides);
@endphp

<!-- Preloader -->
<div id="preloader">
    <h2>HadirquGO</h2>
    <div class="spinner"></div>
    <p>Loading your journey...</p>
</div>

<!-- Language Toggle -->
<div class="lang-toggle" id="langToggle" style="display:none;">
    <button class="lang-btn active" data-lang="en" onclick="setLang('en')">EN</button>
    <button class="lang-btn" data-lang="id" onclick="setLang('id')">ID</button>
</div>

<!-- Slide Indicator Dots -->
<div class="slide-indicator" id="slideIndicator" style="display:none;">
    @for($i = 0; $i < $totalSlides; $i++)
        <div class="slide-dot {{ $i === 0 ? 'active' : '' }}" onclick="goToSlide({{ $i }})"></div>
    @endfor
</div>

<!-- Nav Button -->
<button class="nav-btn" id="navBtn" onclick="nextSlide()">Next →</button>

<!-- Audio -->
<audio id="bgMusic" src="https://hadirqugo.raharja.ac.id/music/music-hadirqugo.mp3" loop preload="auto"></audio>

<!-- Slides -->
<div class="slides-wrapper" id="slidesWrapper">
    @foreach($slides as $i => $slide)
        <div class="slide" id="slide{{ $i }}">
            <div class="slide-bg" style="background-image: url('{{ $slide['bg'] }}');"></div>
            <canvas id="canvas{{ $i }}"></canvas>
            <div class="slide-content" id="content{{ $i }}">
                @if(!empty($slide['show_level_img']))
                    <img src="{{ $stats['level_image'] ?? $bg(2) }}" alt="Level" class="level-img">
                @else
                    <div class="slide-icon"><i class="{{ $slide['icon'] }}"></i></div>
                @endif
                <h1 class="slide-title">
                    <span class="lang-en">{{ $slide['title_en'] }}</span>
                    <span class="lang-id" style="display:none;">{{ $slide['title_id'] }}</span>
                </h1>
                @if(!empty($slide['is_welcome']))
                    <p class="slide-text">
                        <span class="lang-en">{!! $slide['text_en'] !!}</span>
                        <span class="lang-id" style="display:none;">{!! $slide['text_id'] !!}</span>
                    </p>
                    <button class="start-btn" onclick="startAdventure()">
                        <span class="lang-en">Explore Your Journey</span>
                        <span class="lang-id" style="display:none;">Jelajahi Perjalananmu</span>
                    </button>
                @elseif(!empty($slide['is_final']))
                    <p class="slide-text">
                        <span class="lang-en">{!! $slide['text_en'] !!}</span>
                        <span class="lang-id" style="display:none;">{!! $slide['text_id'] !!}</span>
                    </p>
                    <div class="share-links">
                        <a href="https://api.whatsapp.com/send?text={{ urlencode('Check out my Journey on HadirquGO: ' . $shareUrl) }}" target="_blank"><i class="fab fa-whatsapp"></i> WhatsApp</a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" target="_blank"><i class="fab fa-facebook-f"></i> Facebook</a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode($shareUrl) }}&text={{ urlencode('My Journey on HadirquGO!') }}" target="_blank"><i class="fab fa-twitter"></i> Twitter</a>
                        <button onclick="shareViaWebAPI()"><i class="fas fa-share-alt"></i> Share</button>
                    </div>
                @else
                    <p class="slide-text">
                        <span class="lang-en">{!! $slide['text_en'] !!}</span>
                        <span class="lang-id" style="display:none;">{!! $slide['text_id'] !!}</span>
                    </p>
                @endif
            </div>
        </div>
    @endforeach
</div>

<script>
const TOTAL = {{ $totalSlides }};
let current = 0;
let lang = 'en';
let isScrolling = false;
const wrapper = document.getElementById('slidesWrapper');
const navBtn = document.getElementById('navBtn');
const dots = document.querySelectorAll('.slide-dot');

/* === PRELOADER === */
window.addEventListener('load', () => {
    initAllCanvases();
    setTimeout(() => {
        const p = document.getElementById('preloader');
        p.style.opacity = '0';
        setTimeout(() => { p.style.display = 'none'; }, 800);
    }, 2000);
});

/* === LANGUAGE === */
function setLang(l) {
    lang = l;
    document.querySelectorAll('.lang-btn').forEach(b => b.classList.toggle('active', b.dataset.lang === l));
    document.querySelectorAll('.lang-en').forEach(el => el.style.display = l === 'en' ? '' : 'none');
    document.querySelectorAll('.lang-id').forEach(el => el.style.display = l === 'id' ? '' : 'none');
    updateNavBtn();
}

/* === NAVIGATION === */
function goToSlide(n) {
    if (n < 0 || n >= TOTAL || isScrolling) return;
    isScrolling = true;
    current = n;
    wrapper.style.transform = `translateY(-${current * 100}vh)`;
    updateUI();
    setTimeout(() => { isScrolling = false; }, 750);
}

function nextSlide() {
    if (current < TOTAL - 1) goToSlide(current + 1);
    else goToSlide(0);
}

function updateUI() {
    dots.forEach((d, i) => d.classList.toggle('active', i === current));
    const content = document.getElementById('content' + current);
    content.classList.remove('visible');
    void content.offsetWidth; // reflow
    content.classList.add('visible');
    updateNavBtn();
    navBtn.style.display = current === 0 ? 'none' : 'block';
}

function updateNavBtn() {
    if (current === TOTAL - 1) {
        navBtn.textContent = lang === 'en' ? '↺ Back to Start' : '↺ Kembali';
    } else {
        navBtn.textContent = lang === 'en' ? 'Next →' : 'Lanjut →';
    }
}

function startAdventure() {
    document.getElementById('bgMusic').play().catch(() => {});
    document.getElementById('langToggle').style.display = 'flex';
    document.getElementById('slideIndicator').style.display = 'flex';
    goToSlide(1);
}

/* === SCROLL CONTROL (debounced, one slide per scroll) === */
let touchStartY = 0;
window.addEventListener('wheel', (e) => {
    e.preventDefault();
    if (isScrolling || current === 0) return;
    if (e.deltaY > 30) goToSlide(current + 1);
    else if (e.deltaY < -30) goToSlide(current - 1);
}, { passive: false });

window.addEventListener('touchstart', (e) => { touchStartY = e.touches[0].clientY; }, { passive: true });
window.addEventListener('touchend', (e) => {
    if (isScrolling || current === 0) return;
    const diff = touchStartY - e.changedTouches[0].clientY;
    if (diff > 50) goToSlide(current + 1);
    else if (diff < -50) goToSlide(current - 1);
}, { passive: true });

window.addEventListener('keydown', (e) => {
    if (current === 0) return;
    if (e.key === 'ArrowDown' || e.key === ' ') { e.preventDefault(); goToSlide(current + 1); }
    if (e.key === 'ArrowUp') { e.preventDefault(); goToSlide(current - 1); }
});

/* === SHARE === */
function shareViaWebAPI() {
    if (navigator.share) {
        navigator.share({ title: 'My Journey on HadirquGO', text: 'Check out this journey!', url: "{{ $shareUrl }}" }).catch(() => {});
    } else {
        navigator.clipboard.writeText("{{ $shareUrl }}").then(() => alert('Link copied!'));
    }
}

/* === PARTICLES === */
const particleTypes = @json(collect($slides)->pluck('particle')->toArray());

function initAllCanvases() {
    for (let i = 0; i < TOTAL; i++) {
        const c = document.getElementById('canvas' + i);
        if (!c) continue;
        c.width = window.innerWidth;
        c.height = window.innerHeight;
        const type = particleTypes[i];
        if (type === 'snow') initParticles(c, 80, 'white', 0, 1.5, 0.5);
        else if (type === 'green') initParticles(c, 60, 'limegreen', 0, -1.5, 0.5);
        else if (type === 'fire') initParticles(c, 70, null, 0, -1.5, 1, ['#ff4500','#ff8c00','#ffd700']);
        else if (type === 'rain') initParticles(c, 60, 'lightgreen', 0, 2, 0.2);
        else if (type === 'fireflies') initFireflies(c);
        else if (type === 'stars') initParticles(c, 80, null, 0, 0.3, 0.3, ['#ffffff','#ffe4b5','#87ceeb','#dda0dd']);
        else if (type === 'confetti') initParticles(c, 70, null, 0, 1.2, 1.5, ['#ff6b6b','#ffd93d','#6bcb77','#4d96ff','#ff6bd6']);
        else if (type === 'embers') initParticles(c, 50, null, 0, -0.8, 0.6, ['#ff6600','#ff3300','#ffcc00','#ff9900']);
    }
}

function initParticles(canvas, count, color, baseSpeedX, baseSpeedY, driftX, colors) {
    const ctx = canvas.getContext('2d');
    const particles = [];
    for (let i = 0; i < count; i++) {
        const c = colors ? colors[Math.floor(Math.random() * colors.length)] : color;
        particles.push({
            x: Math.random() * canvas.width, y: Math.random() * canvas.height,
            r: Math.random() * 3 + 1.5, color: c,
            sx: (Math.random() - 0.5) * driftX, sy: baseSpeedY > 0 ? Math.random() * baseSpeedY + 0.5 : -(Math.random() * Math.abs(baseSpeedY) + 0.5)
        });
    }
    (function draw() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        particles.forEach(p => {
            p.x += p.sx; p.y += p.sy;
            if (p.y > canvas.height + 10) { p.y = -10; p.x = Math.random() * canvas.width; }
            if (p.y < -10) { p.y = canvas.height + 10; p.x = Math.random() * canvas.width; }
            if (p.x < 0) p.x = canvas.width; if (p.x > canvas.width) p.x = 0;
            ctx.beginPath(); ctx.shadowBlur = 10; ctx.shadowColor = p.color;
            ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2); ctx.fillStyle = p.color; ctx.fill();
            ctx.shadowBlur = 0;
        });
        requestAnimationFrame(draw);
    })();
}

function initFireflies(canvas) {
    const ctx = canvas.getContext('2d');
    const colors = ['#00ffff','#00ffb3','#33ffee','#66ffff'];
    const particles = [];
    for (let i = 0; i < 25; i++) {
        particles.push({
            x: Math.random() * canvas.width, y: Math.random() * canvas.height,
            r: Math.random() * 3 + 1.5, color: colors[Math.floor(Math.random() * colors.length)],
            sx: (Math.random() - 0.5) * 0.6, sy: (Math.random() - 0.5) * 0.6,
            phase: Math.random() * Math.PI * 2
        });
    }
    (function draw() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        particles.forEach(p => {
            p.x += p.sx; p.y += p.sy; p.phase += 0.03;
            if (p.x < 0) p.x = canvas.width; if (p.x > canvas.width) p.x = 0;
            if (p.y < 0) p.y = canvas.height; if (p.y > canvas.height) p.y = 0;
            const alpha = 0.5 + 0.5 * Math.sin(p.phase);
            ctx.beginPath(); ctx.shadowBlur = 12; ctx.shadowColor = p.color;
            ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
            ctx.fillStyle = p.color.replace(')', `,${alpha})`).replace('rgb', 'rgba');
            ctx.globalAlpha = alpha; ctx.fill(); ctx.globalAlpha = 1; ctx.shadowBlur = 0;
        });
        requestAnimationFrame(draw);
    })();
}

window.addEventListener('resize', () => {
    for (let i = 0; i < TOTAL; i++) {
        const c = document.getElementById('canvas' + i);
        if (c) { c.width = window.innerWidth; c.height = window.innerHeight; }
    }
});
</script>
</body>
</html>
