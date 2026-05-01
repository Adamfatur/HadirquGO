<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sayembara Avatar SaiQu — HadirquGO</title>
    <meta name="description" content="Ikuti Sayembara Avatar SaiQu! Desain avatar AI untuk HadirquGO dan menangkan hadiah. Terbuka untuk seluruh anggota Sohib Alphabet Incubator (SAI). Deadline 4 Mei 2026.">
    <meta name="keywords" content="HadirquGO, SaiQu, Sayembara, Avatar, AI, Desain, Kontes, SAI, Sohib Alphabet Incubator">
    <meta name="author" content="HadirquGO">
    <meta property="og:title" content="Sayembara Avatar SaiQu — HadirquGO">
    <meta property="og:description" content="Desain avatar AI terbaik untuk SaiQu! Terbuka untuk seluruh SAI. Deadline 4 Mei 2026, voting 8 Mei.">
    <meta property="og:image" content="{{ asset('images/PosterSayembarav2.png') }}">
    <meta property="og:url" content="{{ route('sayembara.avatar.public') }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="HadirquGO">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Sayembara Avatar SaiQu — HadirquGO">
    <meta name="twitter:description" content="Desain avatar AI terbaik untuk SaiQu! Deadline 4 Mei 2026.">
    <meta name="twitter:image" content="{{ asset('images/PosterSayembarav2.png') }}">
    <link rel="canonical" href="{{ route('sayembara.avatar.public') }}">
    <link rel="icon" href="/images/favion-hadirqugo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --gold: #fbbf24; --blue: #1e3a8a; --blue-light: #2563eb; }
        body { font-family:'Plus Jakarta Sans',sans-serif; background-color:#1e3a8a; color:#f8fafc; margin:0; min-height:100vh; position:relative; }
        body::before { content:""; position:fixed; top:0; left:0; width:100%; height:100%; background:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'%3E%3Cpath fill='%23ffffff' opacity='0.1' d='M0 224h192V32H0v192zM64 96h64v64H64V96zm384-64v192H256V32h192zM448 96h-64v64h64V96zM0 480h192V288H0v192zm64-128h64v64H64v-64zm256 128h192V288H320v192zm128-128h-64v64h64v-64zM256 224h192V32H256v192zm128-128h64v64h-64V96z'/%3E%3C/svg%3E"); opacity:0.08; z-index:0; pointer-events:none; }
        .sp-nav { background:rgba(30,58,138,0.95); backdrop-filter:blur(10px); border-bottom:1px solid rgba(255,255,255,0.1); padding:12px 0; position:sticky; top:0; z-index:100; }
        .sp-hero { background:linear-gradient(135deg,#0f172a,#1e3a5f 50%,#0f172a); border-radius:20px; overflow:hidden; position:relative; }
        .sp-hero::before { content:''; position:absolute; inset:0; background:url('/images/PosterSayembarav2.png') center/cover; opacity:0.15; }
        .sp-poster-wrap { cursor:pointer; display:inline-block; transition:transform 0.3s; position:relative; }
        .sp-poster-wrap:hover { transform:scale(1.02); }
        .sp-poster-wrap::after { content:'🔍 Klik untuk zoom'; position:absolute; bottom:10px; left:50%; transform:translateX(-50%); background:rgba(0,0,0,0.7); color:var(--gold); padding:4px 12px; border-radius:99px; font-size:0.7rem; font-weight:600; opacity:0; transition:opacity 0.3s; }
        .sp-poster-wrap:hover::after { opacity:1; }
        .sp-poster { width:100%; max-width:450px; border-radius:16px; box-shadow:0 10px 40px rgba(0,0,0,0.5); border:2px solid rgba(251,191,36,0.3); }
        .sp-badge { display:inline-flex; align-items:center; gap:6px; padding:6px 14px; border-radius:99px; font-size:0.75rem; font-weight:600; }
        .sp-countdown { font-family:'Courier New',monospace; font-size:1.8rem; font-weight:800; color:var(--gold); text-shadow:0 0 20px rgba(251,191,36,0.5); }
        .sp-card { background:rgba(0,0,0,0.2); border:1px solid rgba(255,255,255,0.08); border-radius:16px; }
        .sp-rule-card { background:linear-gradient(135deg,rgba(15,23,42,0.6),rgba(30,58,138,0.3)); border:1px solid rgba(59,130,246,0.2); border-radius:16px; padding:1.5rem; }
        .sp-gallery-item { border-radius:12px; overflow:hidden; background:rgba(0,0,0,0.2); border:1px solid rgba(255,255,255,0.08); transition:all 0.3s; cursor:pointer; }
        .sp-gallery-item:hover { transform:translateY(-4px); border-color:rgba(251,191,36,0.3); box-shadow:0 8px 25px rgba(0,0,0,0.3); }
        .sp-gallery-item img { width:100%; aspect-ratio:1; object-fit:cover; }
        .sp-share-box { background:linear-gradient(135deg,rgba(251,191,36,0.1),rgba(251,191,36,0.03)); border:1px solid rgba(251,191,36,0.2); border-radius:12px; padding:12px 16px; display:flex; align-items:center; gap:10px; }
        .sp-share-url { flex:1; background:rgba(0,0,0,0.3); border:1px solid rgba(255,255,255,0.1); border-radius:8px; padding:8px 12px; color:#e2e8f0; font-size:0.8rem; font-family:monospace; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
        .sp-lightbox { position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.92); display:none; align-items:center; justify-content:center; cursor:pointer; backdrop-filter:blur(4px); }
        .sp-lightbox.active { display:flex; }
        .sp-lightbox img { max-width:92vw; max-height:92vh; border-radius:12px; box-shadow:0 0 60px rgba(251,191,36,0.3); }
        @keyframes spSlideUp { from{opacity:0;transform:translateY(30px)} to{opacity:1;transform:translateY(0)} }
        .sp-anim { opacity:0; }
        .sp-anim.sp-visible { animation:spSlideUp 0.6s cubic-bezier(0.16,1,0.3,1) forwards; }
        @media(max-width:768px) { .sp-countdown{font-size:1.2rem;} .sp-poster{max-width:100%;} .sp-hero{border-radius:12px;} }
    </style>
</head>
<body>

<!-- Nav -->
<nav class="sp-nav">
    <div class="container d-flex align-items-center justify-content-between">
        <a href="{{ url('/') }}"><img src="/images/logo-hadirqugo-white.png" alt="HadirquGO" style="height:36px;"></a>
        <div class="d-flex gap-2">
            @auth
                <a href="{{ route('sayembara.avatar.index') }}" class="btn btn-sm btn-warning rounded-pill px-3 fw-bold" style="font-size:0.8rem;">
                    <i class="fas fa-paper-plane me-1"></i> Submit Karya
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-sm btn-outline-light rounded-pill px-3" style="font-size:0.8rem;">
                    <i class="fas fa-sign-in-alt me-1"></i> Login untuk Submit
                </a>
            @endauth
        </div>
    </div>
</nav>

<div class="container py-4" style="position:relative;z-index:1;">
    <!-- Share Link -->
    <div class="sp-share-box mb-4 sp-anim">
        <i class="fas fa-share-alt text-warning"></i>
        <div class="sp-share-url" id="shareUrl">{{ route('sayembara.avatar.public') }}</div>
        <button class="btn btn-sm btn-warning rounded-pill px-3 fw-bold" style="font-size:0.75rem;" onclick="copyLink()">
            <i class="fas fa-copy me-1"></i> <span id="copyText">Salin Link</span>
        </button>
    </div>

    <!-- Hero -->
    <div class="sp-hero p-4 p-md-5 mb-4 position-relative sp-anim">
        <div class="position-relative z-1">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="sp-badge bg-warning text-dark mb-3"><i class="fas fa-fire"></i> Sayembara Aktif</div>
                    <h1 class="fw-bold mb-2" style="font-size:2.2rem;">Sayembara Avatar <span style="color:var(--gold);">SaiQu</span></h1>
                    <p class="text-white-50 mb-3" style="font-size:1.05rem;">Untuk seluruh <strong class="text-info">SAI</strong> (Sohib Alphabet Incubator) — tunjukkan kreativitas terbaik kalian!</p>

                    @if($phase === 'submit')
                        <div class="mb-3">
                            <small class="text-white-50">⏳ Deadline submit:</small>
                            <div class="sp-countdown" id="countdown"></div>
                        </div>
                    @elseif($phase === 'voting')
                        <div class="sp-badge bg-success text-white mb-3"><i class="fas fa-vote-yea"></i> Voting Berlangsung Hari Ini!</div>
                    @elseif($phase === 'ended')
                        <div class="sp-badge bg-secondary text-white mb-3"><i class="fas fa-flag-checkered"></i> Sayembara Telah Berakhir</div>
                    @endif

                    <div class="d-flex gap-2 flex-wrap mb-3">
                        <span class="sp-badge" style="background:rgba(59,130,246,0.2);color:#93c5fd;"><i class="fas fa-users"></i> {{ $totalSubmissions }} Karya Masuk</span>
                        <span class="sp-badge" style="background:rgba(251,191,36,0.2);color:var(--gold);"><i class="fas fa-calendar"></i> 30 Apr — 4 Mei 2026</span>
                    </div>

                    @auth
                        <a href="{{ route('sayembara.avatar.index') }}" class="btn btn-warning fw-bold rounded-pill px-4"><i class="fas fa-paper-plane me-2"></i> Submit Karya</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-warning fw-bold rounded-pill px-4"><i class="fas fa-sign-in-alt me-2"></i> Login untuk Ikut</a>
                    @endauth
                </div>
                <div class="col-lg-6 mt-4 mt-lg-0 text-center">
                    <div class="sp-poster-wrap" onclick="spZoom('/images/PosterSayembarav2.png')">
                        <img src="/images/PosterSayembarav2.png" alt="Poster Sayembara Avatar SaiQu HadirquGO" class="sp-poster">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Schedule -->
    <div class="sp-card p-4 mb-4 sp-anim">
        <h5 class="fw-bold mb-3"><i class="fas fa-calendar-alt me-2 text-warning"></i> Jadwal</h5>
        <div class="row g-3">
            <div class="col-md-4">
                <div class="p-3 rounded-3 text-center" style="background:rgba(59,130,246,0.1);border:1px solid rgba(59,130,246,0.2);">
                    <i class="fas fa-upload text-info mb-2" style="font-size:1.5rem;"></i>
                    <div class="fw-bold">Pendaftaran</div>
                    <div class="text-white-50 small">30 April — 4 Mei 2026</div>
                    <div class="text-info small fw-bold">5 Hari (s/d 23:59 WIB)</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 rounded-3 text-center" style="background:rgba(251,191,36,0.1);border:1px solid rgba(251,191,36,0.2);">
                    <i class="fas fa-vote-yea text-warning mb-2" style="font-size:1.5rem;"></i>
                    <div class="fw-bold">Voting</div>
                    <div class="text-white-50 small">5 Mei 2026</div>
                    <div class="text-warning small fw-bold">1 Hari saja (00:00 — 23:59)</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 rounded-3 text-center" style="background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.2);">
                    <i class="fas fa-trophy text-success mb-2" style="font-size:1.5rem;"></i>
                    <div class="fw-bold">Pengumuman</div>
                    <div class="text-white-50 small">6 Mei 2026</div>
                    <div class="text-success small fw-bold">Pemenang diumumkan!</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rules -->
    <div class="row g-4 mb-4">
        <div class="col-md-6 sp-anim">
            <div class="sp-rule-card h-100">
                <h5 class="fw-bold mb-3"><i class="fas fa-palette me-2 text-info"></i> Syarat Avatar</h5>
                <ul class="list-unstyled mb-0" style="color:#cbd5e1;">
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Karakter manusia</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Boleh laki-laki atau perempuan</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Harus memiliki perawakan Indonesia</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Mencerminkan karakter SaiQu — cerdas, hangat, Gen Z</li>
                    <li class="mb-2"><i class="fas fa-exclamation-triangle text-warning me-2"></i> <strong>Konsep harus jelas, detail, dan tajam</strong> — mudah untuk di-remove/clean background</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Format: PNG atau JPG (maks 2MB)</li>
                </ul>
            </div>
        </div>
        <div class="col-md-6 sp-anim">
            <div class="sp-rule-card h-100" style="border-color:rgba(251,191,36,0.15);background:linear-gradient(135deg,rgba(251,191,36,0.08),rgba(251,191,36,0.01));">
                <h5 class="fw-bold mb-3"><i class="fas fa-scroll me-2 text-warning"></i> Rules</h5>
                <ul class="list-unstyled mb-0" style="color:#cbd5e1;">
                    <li class="mb-2"><i class="fas fa-star text-warning me-2"></i> 1 submission per SAI — tidak bisa diubah</li>
                    <li class="mb-2"><i class="fas fa-robot text-info me-2"></i> AI diperbolehkan (ChatGPT, Gemini, Midjourney, dll)</li>
                    <li class="mb-2"><i class="fas fa-fingerprint text-primary me-2"></i> Karya wajib original</li>
                    <li class="mb-2"><i class="fas fa-ban text-danger me-2"></i> Tidak mengandung plagiarisme, SARA, atau NSFW</li>
                    <li class="mb-2"><i class="fas fa-vote-yea text-success me-2"></i> Voting 1 hari setelah penutupan (8 Mei)</li>
                    <li class="mb-2"><i class="fas fa-user-check text-info me-2"></i> 1 orang 1 suara, tidak bisa dibatalkan</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Gallery -->
    @if($submissions->count() > 0)
    <div class="mb-4 sp-anim">
        <h5 class="fw-bold mb-3"><i class="fas fa-images me-2 text-info"></i> Galeri Karya ({{ $submissions->count() }})</h5>
        <div class="row g-3">
            @foreach($submissions as $idx => $sub)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="sp-gallery-item" onclick="spZoom('{{ asset('storage/' . $sub->image_path) }}')">
                    <img src="{{ asset('storage/' . $sub->image_path) }}" alt="Karya {{ $sub->user->display_name ?? 'SAI' }}" loading="lazy">
                    <div class="p-2 text-center">
                        <small class="text-white-50">{{ $sub->user->display_name ?? $sub->user->name }}</small>
                        @if($phase === 'voting' || $phase === 'ended')
                        <div><small class="text-warning fw-bold"><i class="fas fa-heart"></i> {{ $sub->vote_count }}</small></div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- CTA -->
    <div class="text-center py-4 sp-anim">
        @auth
            <a href="{{ route('sayembara.avatar.index') }}" class="btn btn-lg btn-warning fw-bold rounded-pill px-5 shadow"><i class="fas fa-paper-plane me-2"></i> Submit Karya Sekarang</a>
        @else
            <a href="{{ route('login') }}" class="btn btn-lg btn-warning fw-bold rounded-pill px-5 shadow"><i class="fas fa-sign-in-alt me-2"></i> Login untuk Ikut Sayembara</a>
        @endauth
    </div>
</div>

<footer style="background:rgba(0,0,0,0.3);border-top:1px solid rgba(255,255,255,0.05);padding:20px 0;text-align:center;">
    <p class="mb-0 text-white-50 small">&copy; {{ date('Y') }} <strong>HadirquGO</strong>. All rights reserved.</p>
</footer>

<!-- Lightbox -->
<div class="sp-lightbox" id="spLightbox" onclick="this.classList.remove('active')">
    <img id="spLightboxImg" src="" alt="Zoom">
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function spZoom(src){document.getElementById('spLightboxImg').src=src;document.getElementById('spLightbox').classList.add('active')}
document.addEventListener('keydown',function(e){if(e.key==='Escape')document.getElementById('spLightbox').classList.remove('active')});

function copyLink(){
    navigator.clipboard.writeText(document.getElementById('shareUrl').textContent).then(function(){
        var el=document.getElementById('copyText');el.textContent='Tersalin!';
        setTimeout(function(){el.textContent='Salin Link'},2000);
    });
}

// Scroll animations
(function(){
    var items=document.querySelectorAll('.sp-anim');
    var obs=new IntersectionObserver(function(entries){
        entries.forEach(function(e,i){if(e.isIntersecting){setTimeout(function(){e.target.classList.add('sp-visible')},i*80);obs.unobserve(e.target)}});
    },{threshold:0.1});
    items.forEach(function(el){obs.observe(el)});
})();

// Countdown
@if($phase === 'submit')
(function(){
    var end=new Date('{{ $submitEnd->toIso8601String() }}').getTime(),el=document.getElementById('countdown');
    if(!el)return;
    function t(){var d=end-Date.now();if(d<=0){el.textContent='DITUTUP';return}
    var dd=Math.floor(d/864e5),hh=Math.floor(d%864e5/36e5),mm=Math.floor(d%36e5/6e4),ss=Math.floor(d%6e4/1e3);
    el.textContent=dd+'h '+hh+'j '+mm+'m '+ss+'s';requestAnimationFrame(t)}t();
})();
@endif
</script>
</body>
</html>
