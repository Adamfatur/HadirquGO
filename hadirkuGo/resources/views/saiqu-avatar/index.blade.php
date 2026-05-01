@extends(Auth::user()->hasRole('Lecturer') ? 'layout.lecturer' : 'layout.student')

@section('title', 'Sayembara Avatar SaiQu')

@section('content')
<style>
    .sa-hero { background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0f172a 100%); border-radius: 20px; overflow: hidden; position: relative; }
    .sa-hero::before { content:''; position:absolute; inset:0; background:url('/images/PosterSayembarav2.png') center/cover; opacity:0.12; }
    .sa-card { background: rgba(255,255,255,0.05); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; color: #f8fafc; }
    .sa-rule-card { background: linear-gradient(135deg, rgba(30,58,138,0.3), rgba(59,130,246,0.1)); border: 1px solid rgba(59,130,246,0.2); border-radius: 16px; padding: 1.5rem; }
    .sa-badge { display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 99px; font-size: 0.75rem; font-weight: 600; }
    .sa-countdown { font-family: 'Courier New', monospace; font-size: 1.8rem; font-weight: 800; color: #fbbf24; text-shadow: 0 0 20px rgba(251,191,36,0.5); }
    .sa-upload-zone { border: 2px dashed rgba(59,130,246,0.4); border-radius: 16px; padding: 2rem; text-align: center; cursor: pointer; transition: all 0.3s; background: rgba(59,130,246,0.05); }
    .sa-upload-zone:hover { border-color: #3b82f6; background: rgba(59,130,246,0.1); }
    .sa-upload-zone.dragover { border-color: #fbbf24; background: rgba(251,191,36,0.1); }
    .sa-preview { max-width: 200px; max-height: 200px; border-radius: 12px; border: 3px solid rgba(251,191,36,0.5); }
    .sa-submitted { background: linear-gradient(135deg, rgba(16,185,129,0.2), rgba(16,185,129,0.05)); border: 1px solid rgba(16,185,129,0.3); border-radius: 16px; padding: 1.5rem; text-align: center; }

    /* Poster — large, clickable, with hover glow */
    .sa-poster-wrap { cursor: pointer; position: relative; display: inline-block; transition: transform 0.3s; }
    .sa-poster-wrap:hover { transform: scale(1.03); }
    .sa-poster-wrap::after { content:'🔍 Klik untuk zoom'; position:absolute; bottom:12px; left:50%; transform:translateX(-50%); background:rgba(0,0,0,0.7); color:#fbbf24; padding:4px 12px; border-radius:99px; font-size:0.7rem; font-weight:600; opacity:0; transition:opacity 0.3s; pointer-events:none; }
    .sa-poster-wrap:hover::after { opacity:1; }
    .sa-poster { width:100%; max-width:420px; border-radius:16px; box-shadow:0 10px 40px rgba(0,0,0,0.5); border:2px solid rgba(251,191,36,0.3); }

    /* Lightbox */
    .sa-lightbox { position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.92); display:none; align-items:center; justify-content:center; cursor:pointer; backdrop-filter:blur(4px); }
    .sa-lightbox.active { display:flex; animation: saFadeIn 0.3s ease; }
    .sa-lightbox img { max-width:92vw; max-height:92vh; border-radius:12px; box-shadow:0 0 60px rgba(251,191,36,0.3); }
    .sa-lightbox-close { position:absolute; top:16px; right:20px; color:#fff; font-size:1.5rem; cursor:pointer; background:rgba(0,0,0,0.5); width:40px; height:40px; border-radius:50%; display:flex; align-items:center; justify-content:center; transition:background 0.2s; }
    .sa-lightbox-close:hover { background:rgba(251,191,36,0.5); }

    /* Scroll animations */
    @keyframes saFadeIn { from { opacity:0; } to { opacity:1; } }
    @keyframes saSlideUp { from { opacity:0; transform:translateY(30px); } to { opacity:1; transform:translateY(0); } }
    .sa-anim { opacity: 0; }
    .sa-anim.sa-visible { animation: saSlideUp 0.6s cubic-bezier(0.16,1,0.3,1) forwards; }

    @media(max-width:768px) {
        .sa-countdown { font-size: 1.2rem; }
        .sa-hero { border-radius: 12px; }
        .sa-poster { max-width: 100%; }
    }
</style>

<div class="container py-4">
    {{-- Share Link --}}
    <div class="d-flex align-items-center gap-2 mb-3 sa-anim" style="background:rgba(251,191,36,0.08);border:1px solid rgba(251,191,36,0.2);border-radius:12px;padding:10px 14px;">
        <i class="fas fa-share-alt text-warning"></i>
        <code class="flex-grow-1 text-white-50 small" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ route('sayembara.avatar.public') }}</code>
        <button class="btn btn-sm btn-warning rounded-pill px-3 fw-bold" style="font-size:0.7rem;" onclick="navigator.clipboard.writeText('{{ route('sayembara.avatar.public') }}').then(function(){var b=event.target;b.textContent='Tersalin!';setTimeout(function(){b.innerHTML='<i class=\'fas fa-copy me-1\'></i> Salin'},1500)})">
            <i class="fas fa-copy me-1"></i> Salin
        </button>
    </div>

    {{-- Hero --}}
    <div class="sa-hero p-4 p-md-5 mb-4 text-white position-relative sa-anim">
        <div class="position-relative z-1">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="sa-badge bg-warning text-dark mb-3"><i class="fas fa-fire"></i> Sayembara Aktif</div>
                    <h1 class="fw-bold mb-2" style="font-size:2rem;">Sayembara Avatar <span style="color:#fbbf24;">SaiQu</span></h1>
                    <p class="text-white-50 mb-3">Untuk seluruh <strong class="text-info">SAI</strong> (Sohib Alphabet Incubator), tunjukkan kreativitas terbaik kalian!</p>

                    @if($phase === 'submit')
                        <div class="mb-3">
                            <small class="text-white-50">⏳ Deadline submit:</small>
                            <div class="sa-countdown" id="countdown"></div>
                        </div>
                    @elseif($phase === 'voting')
                        <div class="sa-badge bg-success text-white mb-3"><i class="fas fa-vote-yea"></i> Voting Sedang Berlangsung!</div>
                    @elseif($phase === 'ended')
                        <div class="sa-badge bg-secondary text-white mb-3"><i class="fas fa-flag-checkered"></i> Sayembara Telah Berakhir</div>
                    @endif

                    <div class="d-flex gap-2 flex-wrap">
                        <span class="sa-badge" style="background:rgba(59,130,246,0.2);color:#93c5fd;"><i class="fas fa-users"></i> {{ $totalSubmissions }} Karya Masuk</span>
                        <span class="sa-badge" style="background:rgba(251,191,36,0.2);color:#fbbf24;"><i class="fas fa-calendar"></i> 30 Apr — 4 Mei 2026</span>
                    </div>

                    {{-- Gallery button always visible --}}
                    <div class="mt-3">
                        <a href="{{ route('sayembara.avatar.voting') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                            <i class="fas fa-images me-1"></i> Lihat Galeri Karya
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 mt-4 mt-lg-0 text-center">
                    <div class="sa-poster-wrap" onclick="saOpenLightbox('/images/PosterSayembarav2.png')">
                        <img src="/images/PosterSayembarav2.png" alt="Poster Sayembara" class="sa-poster">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Schedule --}}
    <div class="sa-card p-4 mb-4 sa-anim">
        <h5 class="fw-bold text-white mb-3"><i class="fas fa-calendar-alt me-2 text-warning"></i> Jadwal</h5>
        <div class="row g-3">
            <div class="col-md-4">
                <div class="p-3 rounded-3 text-center" style="background:rgba(59,130,246,0.1);border:1px solid rgba(59,130,246,0.2);">
                    <i class="fas fa-upload text-info mb-2" style="font-size:1.5rem;"></i>
                    <div class="fw-bold text-white">Pendaftaran</div>
                    <div class="text-white-50 small">30 April — 4 Mei 2026</div>
                    <div class="text-info small fw-bold">5 Hari (s/d 23:59 WIB)</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 rounded-3 text-center" style="background:rgba(251,191,36,0.1);border:1px solid rgba(251,191,36,0.2);">
                    <i class="fas fa-vote-yea text-warning mb-2" style="font-size:1.5rem;"></i>
                    <div class="fw-bold text-white">Voting</div>
                    <div class="text-white-50 small">5 Mei 2026</div>
                    <div class="text-warning small fw-bold">1 Hari saja (00:00 — 23:59 WIB)</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 rounded-3 text-center" style="background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.2);">
                    <i class="fas fa-trophy text-success mb-2" style="font-size:1.5rem;"></i>
                    <div class="fw-bold text-white">Pengumuman</div>
                    <div class="text-white-50 small">6 Mei 2026</div>
                    <div class="text-success small fw-bold">Pemenang diumumkan!</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Rules --}}
    <div class="row g-4 mb-4">
        <div class="col-md-6 sa-anim">
            <div class="sa-rule-card h-100">
                <h5 class="fw-bold text-white mb-3"><i class="fas fa-palette me-2 text-info"></i> Syarat Avatar</h5>
                <ul class="list-unstyled mb-0" style="color:#cbd5e1;">
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Karakter manusia</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Boleh laki-laki atau perempuan</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Harus memiliki perawakan Indonesia</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Desain harus mencerminkan karakter SaiQu — cerdas, hangat, Gen Z</li>
                    <li class="mb-2"><i class="fas fa-exclamation-triangle text-warning me-2"></i> <strong>Konsep harus jelas, detail, dan tajam</strong> — mudah untuk di-remove/clean background</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Format: PNG atau JPG (maks 2MB)</li>
                </ul>
            </div>
        </div>
        <div class="col-md-6 sa-anim">
            <div class="sa-rule-card h-100" style="border-color:rgba(251,191,36,0.2);background:linear-gradient(135deg,rgba(251,191,36,0.1),rgba(251,191,36,0.02));">
                <h5 class="fw-bold text-white mb-3"><i class="fas fa-scroll me-2 text-warning"></i> Rules</h5>
                <ul class="list-unstyled mb-0" style="color:#cbd5e1;">
                    <li class="mb-2"><i class="fas fa-star text-warning me-2"></i> 1 submission per SAI — tidak bisa diubah</li>
                    <li class="mb-2"><i class="fas fa-robot text-info me-2"></i> Penggunaan AI diperbolehkan (ChatGPT, Gemini, Midjourney, Leonardo AI, dll)</li>
                    <li class="mb-2"><i class="fas fa-fingerprint text-primary me-2"></i> Karya wajib original</li>
                    <li class="mb-2"><i class="fas fa-ban text-danger me-2"></i> Tidak mengandung plagiarisme, SARA, atau NSFW</li>
                    <li class="mb-2"><i class="fas fa-vote-yea text-success me-2"></i> Voting dibuka 1 hari setelah penutupan submit (5 Mei)</li>
                    <li class="mb-2"><i class="fas fa-user-check text-info me-2"></i> Setiap SAI punya 1 suara</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Submit Section --}}
    @if($phase === 'submit')
        @if($submission)
            <div class="sa-submitted mb-4 sa-anim">
                <i class="fas fa-check-circle text-success" style="font-size:2.5rem;"></i>
                <h5 class="fw-bold text-white mt-3">Karya Kamu Sudah Tersubmit! 🎉</h5>
                <p class="text-white-50 mb-3">File: {{ $submission->original_filename }} ({{ round($submission->file_size / 1024) }}KB)</p>
                <img src="{{ asset('storage/' . $submission->image_path) }}" alt="Karya kamu" class="sa-preview" style="cursor:pointer;" onclick="saOpenLightbox('{{ asset('storage/' . $submission->image_path) }}')">
                <p class="text-white-50 mt-3 small">Tidak bisa diubah. Tunggu voting dibuka ya!</p>
            </div>
        @else
            <div class="sa-card p-4 mb-4 sa-anim">
                <h5 class="fw-bold text-white mb-3"><i class="fas fa-cloud-upload-alt me-2 text-info"></i> Submit Karya Kamu</h5>

                @if(session('error'))
                    <div class="alert alert-danger rounded-3">{{ session('error') }}</div>
                @endif
                @if(session('success'))
                    <div class="alert alert-success rounded-3">{{ session('success') }}</div>
                @endif

                <form action="{{ route('sayembara.avatar.submit') }}" method="POST" enctype="multipart/form-data" id="submitForm">
                    @csrf
                    <div class="sa-upload-zone" id="dropZone" onclick="document.getElementById('avatarInput').click()">
                        <i class="fas fa-image text-info" style="font-size:2.5rem;"></i>
                        <p class="text-white-50 mt-2 mb-1">Klik atau drag & drop gambar di sini</p>
                        <small class="text-warning fw-bold">PNG / JPG — Konsep jelas & detail — Maks 2MB</small>
                        <div id="previewWrap" style="display:none;" class="mt-3">
                            <img id="previewImg" class="sa-preview" alt="Preview">
                            <p class="text-info mt-2 small" id="previewName"></p>
                        </div>
                    </div>
                    <input type="file" id="avatarInput" name="avatar" accept=".png,.jpg,.jpeg" style="display:none;">
                    @error('avatar')<div class="text-danger small mt-2">{{ $message }}</div>@enderror

                    {{-- Agreement & Warning --}}
                    <div class="mt-3 p-3 rounded-3" style="background:rgba(251,191,36,0.08);border:1px solid rgba(251,191,36,0.2);">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="agreeTerms" onchange="document.getElementById('submitBtn').disabled = !this.checked || !document.getElementById('avatarInput').files.length">
                            <label class="form-check-label small" for="agreeTerms" style="color:#e2e8f0;">
                                Saya menyetujui bahwa karya ini <strong class="text-warning">akan digunakan sebagai Avatar resmi SaiQu</strong> jika terpilih sebagai pemenang, dan saya memberikan hak penggunaan kepada HadirquGO.
                            </label>
                        </div>
                        <div class="form-check mb-0">
                            <input class="form-check-input" type="checkbox" id="agreeOnce" onchange="document.getElementById('submitBtn').disabled = !document.getElementById('agreeTerms').checked || !this.checked || !document.getElementById('avatarInput').files.length">
                            <label class="form-check-label small" for="agreeOnce" style="color:#e2e8f0;">
                                Saya memahami bahwa <strong class="text-danger">submit hanya bisa dilakukan 1 kali</strong> — tidak ada revisi, penggantian, atau kesempatan kedua. Saya telah memastikan karya ini memenuhi seluruh syarat.
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-warning fw-bold rounded-pill px-4 mt-3" id="submitBtn" disabled onclick="return confirm('⚠️ PERHATIAN!\n\nSubmit hanya bisa dilakukan 1 KALI.\nTidak ada revisi atau penggantian.\n\nKarya yang terpilih akan menjadi Avatar resmi SaiQu.\n\nYakin ingin submit karya ini?')">
                        <i class="fas fa-paper-plane me-2"></i> Submit Karya
                    </button>
                </form>
            </div>
        @endif
    @elseif($phase === 'voting' || $phase === 'ended')
        <div class="text-center mb-4 sa-anim">
            <a href="{{ route('sayembara.avatar.voting') }}" class="btn btn-lg btn-warning fw-bold rounded-pill px-5 shadow">
                <i class="fas fa-vote-yea me-2"></i> {{ $phase === 'voting' ? 'Vote Sekarang!' : 'Lihat Hasil Voting' }}
            </a>
        </div>
    @elseif($phase === 'waiting')
        <div class="sa-card p-4 mb-4 text-center sa-anim">
            <i class="fas fa-hourglass-half text-warning" style="font-size:2rem;"></i>
            <h5 class="fw-bold text-white mt-3">Pendaftaran Ditutup!</h5>
            <p class="text-white-50">Voting akan dibuka pada <strong class="text-warning">5 Mei 2026 pukul 00:00 WIB</strong></p>
            <div class="sa-countdown" id="voteCountdown"></div>
        </div>
    @endif
</div>

{{-- Lightbox --}}
<div class="sa-lightbox" id="saLightbox" onclick="saCloseLightbox()">
    <div class="sa-lightbox-close"><i class="fas fa-times"></i></div>
    <img id="saLightboxImg" src="" alt="Zoom">
</div>

@push('scripts')
<script>
    // ── Lightbox ──
    function saOpenLightbox(src) {
        document.getElementById('saLightboxImg').src = src;
        document.getElementById('saLightbox').classList.add('active');
    }
    function saCloseLightbox() {
        document.getElementById('saLightbox').classList.remove('active');
    }
    document.addEventListener('keydown', function(e) { if (e.key === 'Escape') saCloseLightbox(); });

    // ── Scroll animations ──
    (function() {
        var items = document.querySelectorAll('.sa-anim');
        if (!items.length) return;
        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry, i) {
                if (entry.isIntersecting) {
                    setTimeout(function() { entry.target.classList.add('sa-visible'); }, i * 100);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        items.forEach(function(el) { observer.observe(el); });
    })();

    // ── Countdown ──
    @if($phase === 'submit')
    (function() {
        var end = new Date('{{ $submitEnd->toIso8601String() }}').getTime();
        var el = document.getElementById('countdown');
        if (!el) return;
        function tick() {
            var now = Date.now(), diff = end - now;
            if (diff <= 0) { el.textContent = 'DITUTUP'; return; }
            var d = Math.floor(diff/86400000), h = Math.floor((diff%86400000)/3600000),
                m = Math.floor((diff%3600000)/60000), s = Math.floor((diff%60000)/1000);
            el.textContent = d + 'h ' + h + 'j ' + m + 'm ' + s + 's';
            requestAnimationFrame(tick);
        }
        tick();
    })();
    @endif

    // ── File upload ──
    (function() {
        var input = document.getElementById('avatarInput');
        var zone = document.getElementById('dropZone');
        var btn = document.getElementById('submitBtn');
        if (!input || !zone) return;

        input.addEventListener('change', function() { handleFile(this.files[0]); });
        zone.addEventListener('dragover', function(e) { e.preventDefault(); zone.classList.add('dragover'); });
        zone.addEventListener('dragleave', function() { zone.classList.remove('dragover'); });
        zone.addEventListener('drop', function(e) {
            e.preventDefault(); zone.classList.remove('dragover');
            if (e.dataTransfer.files.length) { input.files = e.dataTransfer.files; handleFile(e.dataTransfer.files[0]); }
        });

        function handleFile(file) {
            if (!file) return;
            if (file.type !== 'image/png' && file.type !== 'image/jpeg') { alert('Format harus PNG atau JPG!'); return; }
            if (file.size > 2*1024*1024) { alert('Ukuran maksimal 2MB!'); return; }
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('previewName').textContent = file.name + ' (' + Math.round(file.size/1024) + 'KB)';
                document.getElementById('previewWrap').style.display = 'block';
                // Enable submit only if both checkboxes checked + file selected
                var a1 = document.getElementById('agreeTerms');
                var a2 = document.getElementById('agreeOnce');
                if (btn) btn.disabled = !(a1 && a1.checked && a2 && a2.checked);
            };
            reader.readAsDataURL(file);
        }
    })();

    // ── Vote countdown (waiting phase) ──
    @if($phase === 'waiting')
    (function() {
        var voteStart = new Date('{{ $voteStart->toIso8601String() }}').getTime();
        var el = document.getElementById('voteCountdown');
        if (!el) return;
        function tick() {
            var diff = voteStart - Date.now();
            if (diff <= 0) { el.textContent = 'VOTING DIBUKA!'; location.reload(); return; }
            var d = Math.floor(diff/86400000), h = Math.floor((diff%86400000)/3600000),
                m = Math.floor((diff%3600000)/60000), s = Math.floor((diff%60000)/1000);
            el.textContent = d + 'h ' + h + 'j ' + m + 'm ' + s + 's';
            requestAnimationFrame(tick);
        }
        tick();
    })();
    @endif
</script>
@endpush
@endsection
