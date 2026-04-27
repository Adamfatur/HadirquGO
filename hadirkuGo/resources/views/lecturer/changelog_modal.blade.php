<div class="modal fade" id="changelogModal" tabindex="-1" aria-labelledby="changelogModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg mx-auto" style="max-width: 95%; width: 850px;">
        <div class="modal-content" style="border-radius: 24px; border: none; box-shadow: 0 20px 50px rgba(0,0,0,0.3); overflow: hidden;">
            <!-- Header Section -->
            <div class="modal-header border-0" style="background: linear-gradient(135deg, #1e3a8a, #3b82f6); color: white; padding: 18px 25px;">
                <h5 class="modal-title fw-bold d-flex align-items-center" id="changelogModalLabel">
                    <i class="fas fa-rocket me-2"></i> 
                    <span>HadirkuGO v2.0 — Changelog</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-0" style="background-color: #ffffff;">
                <!-- Language Selector -->
                <div class="px-3 py-2 border-bottom bg-light">
                    <ul class="nav nav-pills nav-justified p-1" id="changelogTab" role="tablist" style="background: #e2e8f0; border-radius: 12px;">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-bold py-2" id="id-tab" data-bs-toggle="tab" data-bs-target="#id-content" type="button" role="tab" aria-controls="id-content" aria-selected="true" style="border-radius: 10px;">
                                🇮🇩 Bahasa Indonesia
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold py-2" id="en-tab" data-bs-toggle="tab" data-bs-target="#en-content" type="button" role="tab" aria-controls="en-content" aria-selected="false" style="border-radius: 10px;">
                                🇺🇸 English
                            </button>
                        </li>
                    </ul>
                </div>

                <!-- Content Area -->
                <div class="tab-content p-3 p-md-4" style="max-height: 60vh; overflow-y: auto;">

                    <!-- ==================== INDONESIAN CONTENT ==================== -->
                    <div class="tab-pane fade show active" id="id-content" role="tabpanel" aria-labelledby="id-tab">
                        
                        <!-- What's New Banner -->
                        <div class="alert mb-4 p-3" style="background: linear-gradient(135deg, #eff6ff, #dbeafe); border: 1px solid #bfdbfe; border-radius: 16px;">
                            <div class="d-flex align-items-center">
                                <span style="font-size: 1.5rem;" class="me-2">🎉</span>
                                <div>
                                    <div class="fw-bold text-primary">Update Besar dari v1.5!</div>
                                    <div class="small text-muted">Berikut semua perubahan penting di HadirkuGO v2.0</div>
                                </div>
                            </div>
                        </div>

                        <!-- ===== ADDED SECTION ===== -->
                        <div class="changelog-section mb-4">
                            <h6 class="fw-bold text-primary mb-3 d-flex align-items-center">
                                <span class="badge bg-primary me-2"><i class="fas fa-plus"></i></span> Ditambahkan
                            </h6>
                            <ul class="list-unstyled ms-2 ps-3 border-start" style="border-width: 3px !important; border-color: #e0e7ff !important;">
                                
                                <!-- SaiQu AI -->
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">🤖 SaiQu — AI Agent Cerdas</div>
                                    <div class="small text-muted">Asisten AI berbasis Gemini yang menjawab pertanyaan seputar sistem HadirkuGO secara real-time.</div>
                                    <ul class="small text-muted ps-3 mt-1">
                                        <li>Floating chat widget tersedia di semua halaman</li>
                                        <li>Mengenal data personal: poin, level, ranking, tim, absensi kamu</li>
                                        <li>RAG (Retrieval-Augmented Generation) — hanya menjawab berdasarkan data sistem</li>
                                        <li>Domain-restricted: menolak pertanyaan di luar cakupan sistem</li>
                                        <li>Proteksi data sensitif (password, token, email privat)</li>
                                        <li>Rate limiting: 50 pertanyaan/hari per user, anti-spam 10/menit</li>
                                        <li>Multi-model fallback: otomatis beralih model jika server sibuk</li>
                                        <li>Quick action buttons untuk pertanyaan populer</li>
                                        <li>Riwayat percakapan per sesi</li>
                                    </ul>
                                </li>

                                <!-- Multi-Language -->
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">🌐 Sistem Multi-Bahasa (ID/EN)</div>
                                    <div class="small text-muted">Seluruh halaman Lecturer dan Student kini mendukung dua bahasa.</div>
                                    <ul class="small text-muted ps-3 mt-1">
                                        <li>200+ string terjemahan (Bahasa Indonesia & English)</li>
                                        <li>Language switcher pill (EN/ID) di pojok kanan atas setiap halaman</li>
                                        <li>Terjemahan mencakup: Dashboard, Teams, Calendar, Achievements, Profile, Attendance, Leaderboard, Quiz, Redeem, Statistics, dan lainnya</li>
                                        <li>Preferensi bahasa tersimpan di session</li>
                                        <li>Middleware SetLocale untuk konsistensi bahasa</li>
                                    </ul>
                                </li>

                                <!-- Motivational Quotes -->
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">💬 100 Motivational Quotes</div>
                                    <div class="small text-muted">Quotes motivasi bertema pendidikan tampil saat check-in dan check-out.</div>
                                    <ul class="small text-muted ps-3 mt-1">
                                        <li>100 quotes unik dalam Bahasa Indonesia dan English</li>
                                        <li>Tampil secara random setiap kali absen</li>
                                        <li>Animasi fade-in yang smooth</li>
                                        <li>Tabel database khusus untuk quotes</li>
                                        <li>Seeder otomatis untuk populate data</li>
                                    </ul>
                                </li>

                                <!-- Name Change -->
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">✏️ Fitur Ganti Nama (1x)</div>
                                    <div class="small text-muted">User dapat mengganti nama satu kali melalui halaman Profile.</div>
                                    <ul class="small text-muted ps-3 mt-1">
                                        <li>Mengatasi masalah nama dari Google yang tidak rapi</li>
                                        <li>Validasi: hanya huruf, spasi, dan titik (min. 3 karakter)</li>
                                        <li>Kolom <code>name_changed</code> di database untuk tracking</li>
                                        <li>Tombol ganti nama hilang setelah digunakan</li>
                                    </ul>
                                </li>

                                <!-- Auto Name Formatting -->
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">🔤 Auto Format Nama (display_name)</div>
                                    <div class="small text-muted">Nama user yang belum diganti otomatis dirapikan saat ditampilkan.</div>
                                    <ul class="small text-muted ps-3 mt-1">
                                        <li>Angka dihilangkan dari nama</li>
                                        <li>Huruf awal setiap kata dikapitalisasi</li>
                                        <li>Spasi berlebih dibersihkan</li>
                                        <li>Berlaku di seluruh sistem via accessor <code>display_name</code></li>
                                    </ul>
                                </li>

                                <!-- Public Journey -->
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">🔗 Journey Publik</div>
                                    <div class="small text-muted">Halaman journey setiap user kini bisa diakses secara publik.</div>
                                    <ul class="small text-muted ps-3 mt-1">
                                        <li>Akses dari halaman evaluasi: <code>/share/journey/{memberId}</code></li>
                                        <li>Menampilkan statistik kehadiran lengkap</li>
                                        <li>Bisa dibagikan ke siapapun tanpa login</li>
                                    </ul>
                                </li>

                                <!-- Interactive Feedback -->
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">📝 Interactive Feedback System</div>
                                    <div class="small text-muted">Sistem feedback baru yang lengkap dan interaktif.</div>
                                    <ul class="small text-muted ps-3 mt-1">
                                        <li>Floating feedback button di semua halaman</li>
                                        <li>CRUD feedback: buat, edit, hapus</li>
                                        <li>Like/unlike system antar user</li>
                                        <li>Status tracking: pending, reviewed, resolved</li>
                                        <li>Halaman feedback khusus dengan daftar semua masukan</li>
                                        <li>Tabel <code>feedbacks</code> dan <code>feedback_likes</code></li>
                                    </ul>
                                </li>

                                <!-- Points Rivalry -->
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">⚔️ Points Rivalry</div>
                                    <div class="small text-muted">Fitur untuk memilih target user sebagai rival dan membandingkan performa.</div>
                                    <ul class="small text-muted ps-3 mt-1">
                                        <li>Pilih rival dari leaderboard</li>
                                        <li>Perbandingan poin real-time</li>
                                        <li>Kolom <code>comparison_user_id</code> di tabel users</li>
                                    </ul>
                                </li>

                                <!-- YOU Standing -->
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">📍 YOU — {{ __('Your Current Standing') }}</div>
                                    <div class="small text-muted">Menampilkan posisi user saat ini meskipun tidak berada di Top 50.</div>
                                </li>

                                <!-- Leaderboard Insight -->
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">📊 {{ __('Leaderboard') }} Standing Insight (Top 50)</div>
                                    <ul class="small text-muted ps-3 mt-1">
                                        <li>Informasi posisi saat ini</li>
                                        <li>User di atas dan di bawah kamu</li>
                                        <li>Selisih poin (tertinggal & melampaui)</li>
                                    </ul>
                                </li>

                                <!-- Exclusive Rewards -->
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">🏆 Hadiah Eksklusif Top 50</div>
                                    <div class="small text-muted">Frame & Gelar Eksklusif untuk Top 50 Global.</div>
                                    <ul class="small text-muted ps-3 mt-1">
                                        <li>Frame/border khusus di foto profil</li>
                                        <li>Gelar (title) yang tampil di leaderboard</li>
                                        <li>Warna frame berdasarkan peringkat</li>
                                        <li>Sinkronisasi otomatis via command <code>SyncLeaderboardFrames</code></li>
                                    </ul>
                                </li>

                                <!-- Search -->
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">🔍 Fitur Pencarian</div>
                                    <div class="small text-muted">Pencarian Teams & Member pada halaman Teams, serta pencarian di Leaderboard.</div>
                                </li>

                                <!-- Custom Range -->
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">📅 Custom Range Attendance Report</div>
                                    <div class="small text-muted">Pilihan baru: 60 hari, 90 hari, 6 bulan, dan 1 tahun.</div>
                                </li>
                            </ul>
                        </div>

                        <!-- ===== IMPROVED SECTION ===== -->
                        <div class="changelog-section mb-4">
                            <h6 class="fw-bold text-info mb-3 d-flex align-items-center">
                                <span class="badge bg-info me-2"><i class="fas fa-sync-alt"></i></span> Peningkatan
                            </h6>
                            <ul class="list-unstyled ms-2 ps-3 border-start" style="border-width: 3px !important; border-color: #e0f2fe !important;">
                                
                                <!-- Profile -->
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">Halaman Profile</div>
                                    <ul class="small text-muted ps-3 mt-1">
                                        <li>Menampilkan level user saat ini</li>
                                        <li>Daftar pencapaian (achievements) yang sudah diraih</li>
                                        <li>Status aktif/inaktif</li>
                                        <li>Statistik ringkas: sesi, badges, poin, rank</li>
                                        <li>Layout responsif untuk semua ukuran layar</li>
                                        <li>Tombol ganti nama (jika belum pernah)</li>
                                    </ul>
                                </li>

                                <!-- Users Page -->
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">Halaman Users (Lecturer)</div>
                                    <ul class="small text-muted ps-3 mt-1">
                                        <li>Frame/border Top 50 tampil di foto profil</li>
                                        <li>Level user ditampilkan</li>
                                        <li>Gelar (title) yang didapat muncul</li>
                                        <li>Filter berdasarkan tim yang dipimpin/diikuti</li>
                                    </ul>
                                </li>

                                <!-- Check-in/out Animation -->
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">Animasi Check-in & Check-out</div>
                                    <ul class="small text-muted ps-3 mt-1">
                                        <li>Animasi super smooth dari proses absen hingga berhasil</li>
                                        <li>Quotes motivasi tampil setelah sukses absen</li>
                                        <li>Efek fade-in dan slide-up pada elemen</li>
                                        <li>Menggantikan tampilan ucapan selamat datang yang lama</li>
                                    </ul>
                                </li>

                                <!-- Last Person & Most Late -->
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">Last Person & Most Late Person</div>
                                    <ul class="small text-muted ps-3 mt-1">
                                        <li><strong>Last Person:</strong> Kini menunjukkan orang yang terakhir checkout (bukan checkin)</li>
                                        <li><strong>Most Late Person:</strong> Orang yang check-in paling terakhir (datang paling telat) di hari itu dalam tim</li>
                                        <li>Berlaku di halaman attendance bulanan dan custom range</li>
                                        <li>Modal detail untuk masing-masing kategori</li>
                                    </ul>
                                </li>

                                <!-- Dashboard -->
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">Dashboard Baru</div>
                                    <ul class="small text-muted ps-3 mt-1">
                                        <li>Tampilan baru dengan informasi EXP dan progress level berikutnya</li>
                                        <li>Live Activity: status check-in real-time</li>
                                        <li>Today's Highlights: ringkasan hari ini</li>
                                        <li>Performance Insights: wawasan performa</li>
                                        <li>Top Global Players podium</li>
                                        <li>Rank Rivalry card</li>
                                        <li>Journey card dengan pesan motivasi</li>
                                        <li>Icon menu navigasi cepat</li>
                                        <li>Banner carousel</li>
                                    </ul>
                                </li>

                                <!-- Leaderboard System -->
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">Sistem {{ __('Leaderboard') }}</div>
                                    <ul class="small text-muted ps-3 mt-1">
                                        <li>Sistem berubah menjadi cut-off per jam</li>
                                        <li>Indikator perubahan peringkat (naik/turun)</li>
                                        <li>Rekap data performa</li>
                                        <li>6 kategori: Top Levels, Sessions, Duration, Locations, Points, Teams</li>
                                        <li>Tabel baru: <code>user_leaderboards</code>, <code>location_leaderboards</code>, <code>team_leaderboards</code></li>
                                        <li>Kolom <code>third_score</code>, <code>title</code>, <code>frame_color</code></li>
                                    </ul>
                                </li>

                                <!-- Top Global -->
                                <li class="mb-2"><strong>Top Global Players:</strong> Tampilan podium lebih modern dan informatif dengan frame eksklusif.</li>

                                <!-- Evaluation -->
                                <li class="mb-2"><strong>{{ __('Evaluation Report') }}:</strong> Lebih detail dan akurat, termasuk narasi motivasi & pertumbuhan.</li>

                                <!-- Teams -->
                                <li class="mb-2"><strong>Halaman Teams:</strong> Dirombak untuk mendukung skala besar, pencarian, dan filter.</li>

                                <!-- Attendance Report -->
                                <li class="mb-2"><strong>Attendance Report:</strong> Lebih informatif dengan periode waktu jelas dan statistik lengkap (total durasi, rata-rata, streak, dll).</li>

                                <!-- Quiz -->
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">Penambahan Soal Quiz</div>
                                    <ul class="small text-muted ps-3 mt-1">
                                        <li>Quiz: +100 soal baru (±300 total)</li>
                                        <li>Super Quiz: +100 soal baru (±200 total)</li>
                                    </ul>
                                </li>
                            </ul>
                        </div>

                        <!-- ===== FIXED SECTION ===== -->
                        <div class="changelog-section mb-4">
                            <h6 class="fw-bold text-warning mb-3 d-flex align-items-center">
                                <span class="badge bg-warning text-dark me-2"><i class="fas fa-bug"></i></span> Perbaikan
                            </h6>
                            <ul class="list-unstyled ms-2 ps-3 border-start" style="border-width: 3px !important; border-color: #fef3c7 !important;">
                                <li class="mb-2 small text-muted">Perbaikan bug pada fitur redeem.</li>
                                <li class="mb-2 small text-muted">Perbaikan bug pada Quiz & Super Quiz.</li>
                                <li class="mb-2 small text-muted">Perbaikan logika team leaders (command <code>FixTeamLeadersCommand</code>).</li>
                                <li class="mb-2 small text-muted">Perbaikan deaktivasi attendance yang expired.</li>
                                <li class="mb-2 small text-muted">Perbaikan stabilitas dan pencegahan bottleneck pada seluruh sistem.</li>
                            </ul>
                        </div>

                        <!-- ===== PERFORMANCE SECTION ===== -->
                        <div class="changelog-section mb-4">
                            <h6 class="fw-bold text-success mb-3 d-flex align-items-center">
                                <span class="badge bg-success me-2"><i class="fas fa-bolt"></i></span> Performa
                            </h6>
                            <ul class="list-unstyled ms-2 ps-3 border-start" style="border-width: 3px !important; border-color: #dcfce7 !important;">
                                <li class="mb-2 small text-muted">{{ __('Leaderboard') }} diperbarui otomatis setiap 1 jam via scheduled command.</li>
                                <li class="mb-2 small text-muted">Implementasi Redis untuk cache dan queue.</li>
                                <li class="mb-2 small text-muted">SaiQu AI menggunakan queue system dan caching untuk menghindari blocking.</li>
                                <li class="mb-2 small text-muted">Cache pada data achievements, rewards, levels, dan statistik.</li>
                                <li class="mb-2 small text-muted">Peningkatan performa dan stabilitas sistem secara keseluruhan.</li>
                            </ul>
                        </div>

                        <!-- ===== TECHNICAL SECTION ===== -->
                        <div class="changelog-section">
                            <h6 class="fw-bold mb-3 d-flex align-items-center" style="color: #6366f1;">
                                <span class="badge me-2" style="background: #6366f1;"><i class="fas fa-code"></i></span> Teknis & Database
                            </h6>
                            <ul class="list-unstyled ms-2 ps-3 border-start" style="border-width: 3px !important; border-color: #e0e7ff !important;">
                                <li class="mb-2 small text-muted">Migrasi baru: <code>user_leaderboards</code>, <code>location_leaderboards</code>, <code>team_leaderboards</code></li>
                                <li class="mb-2 small text-muted">Migrasi baru: <code>feedbacks</code>, <code>feedback_likes</code></li>
                                <li class="mb-2 small text-muted">Migrasi baru: <code>saiqu_conversations</code></li>
                                <li class="mb-2 small text-muted">Kolom baru: <code>name_changed</code> di tabel users</li>
                                <li class="mb-2 small text-muted">Kolom baru: <code>comparison_user_id</code> di tabel users</li>
                                <li class="mb-2 small text-muted">Kolom baru: <code>third_score</code>, <code>title</code>, <code>frame_color</code> di user_leaderboards</li>
                                <li class="mb-2 small text-muted">Artisan commands baru: <code>SyncLeaderboardFrames</code>, <code>UpdateLeaderboards</code>, <code>FixTeamLeaders</code></li>
                                <li class="mb-2 small text-muted">Service layer: <code>GeminiService</code>, <code>KnowledgeService</code>, <code>QuestionValidator</code></li>
                                <li class="mb-2 small text-muted">Konfigurasi SaiQu: <code>config/saiqu.php</code></li>
                                <li class="mb-2 small text-muted">Seeder: <code>QuotesTableSeeder</code> (100 quotes), <code>UpdateLeaderboardStylesSeeder</code></li>
                            </ul>
                        </div>
                    </div>

                    <!-- ==================== ENGLISH CONTENT ==================== -->
                    <div class="tab-pane fade" id="en-content" role="tabpanel" aria-labelledby="en-tab">

                        <!-- What's New Banner -->
                        <div class="alert mb-4 p-3" style="background: linear-gradient(135deg, #eff6ff, #dbeafe); border: 1px solid #bfdbfe; border-radius: 16px;">
                            <div class="d-flex align-items-center">
                                <span style="font-size: 1.5rem;" class="me-2">🎉</span>
                                <div>
                                    <div class="fw-bold text-primary">Major Update from v1.5!</div>
                                    <div class="small text-muted">Here's everything new in HadirkuGO v2.0</div>
                                </div>
                            </div>
                        </div>

                        <!-- ===== ADDED SECTION ===== -->
                        <div class="changelog-section mb-4">
                            <h6 class="fw-bold text-primary mb-3 d-flex align-items-center">
                                <span class="badge bg-primary me-2"><i class="fas fa-plus"></i></span> Added
                            </h6>
                            <ul class="list-unstyled ms-2 ps-3 border-start" style="border-width: 3px !important; border-color: #e0e7ff !important;">
                                
                                <!-- SaiQu AI -->
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">🤖 SaiQu — Smart AI Agent</div>
                                    <div class="small text-muted">Gemini-powered AI assistant that answers questions about the HadirkuGO system in real-time.</div>
                                    <ul class="small text-muted ps-3 mt-1">
                                        <li>Floating chat widget available on all pages</li>
                                        <li>Knows your personal data: points, level, ranking, teams, attendance</li>
                                        <li>RAG (Retrieval-Augmented Generation) — answers based on system data only</li>
                                        <li>Domain-restricted: rejects off-topic questions</li>
                                        <li>Sensitive data protection (passwords, tokens, private emails)</li>
                                        <li>Rate limiting: 50 questions/day per user, anti-spam 10/min</li>
                                        <li>Multi-model fallback: auto-switches model if server is busy</li>
                                        <li>Quick action buttons for popular questions</li>
                                        <li>Per-session conversation history</li>
                                    </ul>
                                </li>

                                <!-- Multi-Language -->
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">🌐 Multi-Language System (ID/EN)</div>
                                    <div class="small text-muted">All Lecturer and Student pages now support two languages.</div>
                                    <ul class="small text-muted ps-3 mt-1">
                                        <li>200+ translation strings (Indonesian & English)</li>
                                        <li>Language switcher pill (EN/ID) at the top-right of every page</li>
                                        <li>Covers: Dashboard, Teams, Calendar, Achievements, Profile, Attendance, Leaderboard, Quiz, Redeem, Statistics, and more</li>
                                        <li>Language preference saved in session</li>
                                        <li>SetLocale middleware for consistency</li>
                                    </ul>
                                </li>

                                <!-- Motivational Quotes -->
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">💬 100 Motivational Quotes</div>
                                    <div class="small text-muted">Education-themed motivational quotes displayed during check-in and check-out.</div>
                                    <ul class="small text-muted ps-3 mt-1">
                                        <li>100 unique quotes in Indonesian and English</li>
                                        <li>Randomly displayed each time you attend</li>
                                        <li>Smooth fade-in animation</li>
                                        <li>Dedicated database table for quotes</li>
                                        <li>Auto-seeder to populate data</li>
                                    </ul>
                                </li>

                                <!-- Name Change -->
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">✏️ One-Time Name Change</div>
                                    <div class="small text-muted">Users can change their name once via the Profile page.</div>
                                    <ul class="small text-muted ps-3 mt-1">
                                        <li>Fixes messy names imported from Google</li>
                                        <li>Validation: letters, spaces, and dots only (min 3 chars)</li>
                                        <li><code>name_changed</code> column in database for tracking</li>
                                        <li>Change button disappears after use</li>
                                    </ul>
                                </li>

                                <!-- Auto Name Formatting -->
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">🔤 Auto Name Formatting (display_name)</div>
                                    <div class="small text-muted">Names that haven't been changed are auto-cleaned when displayed.</div>
                                    <ul class="small text-muted ps-3 mt-1">
                                        <li>Numbers removed from names</li>
                                        <li>First letter of each word capitalized</li>
                                        <li>Extra spaces cleaned up</li>
                                        <li>Applied system-wide via <code>display_name</code> accessor</li>
                                    </ul>
                                </li>

                                <!-- Public Journey -->
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">🔗 Public Journey</div>
                                    <div class="small text-muted">Each user's journey page is now publicly accessible.</div>
                                    <ul class="small text-muted ps-3 mt-1">
                                        <li>Access from evaluation page: <code>/share/journey/{memberId}</code></li>
                                        <li>Shows complete attendance statistics</li>
                                        <li>Shareable with anyone without login</li>
                                    </ul>
                                </li>

                                <!-- Interactive Feedback -->
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">📝 Interactive Feedback System</div>
                                    <div class="small text-muted">Complete and interactive new feedback system.</div>
                                    <ul class="small text-muted ps-3 mt-1">
                                        <li>Floating feedback button on all pages</li>
                                        <li>Full CRUD: create, edit, delete feedback</li>
                                        <li>Like/unlike system between users</li>
                                        <li>Status tracking: pending, reviewed, resolved</li>
                                        <li>Dedicated feedback page with all submissions</li>
                                        <li><code>feedbacks</code> and <code>feedback_likes</code> tables</li>
                                    </ul>
                                </li>

                                <!-- Points Rivalry -->
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">⚔️ Points Rivalry</div>
                                    <div class="small text-muted">Set a target user as a rival and compare performance.</div>
                                    <ul class="small text-muted ps-3 mt-1">
                                        <li>Pick a rival from the leaderboard</li>
                                        <li>Real-time point comparison</li>
                                        <li><code>comparison_user_id</code> column in users table</li>
                                    </ul>
                                </li>

                                <!-- YOU Standing -->
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">📍 YOU — {{ __('Your Current Standing') }}</div>
                                    <div class="small text-muted">Displays your current position even if you're not in the Top 50.</div>
                                </li>

                                <!-- Leaderboard Insight -->
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">📊 {{ __('Leaderboard') }} Standing Insight (Top 50)</div>
                                    <ul class="small text-muted ps-3 mt-1">
                                        <li>Current position information</li>
                                        <li>Users above and below you</li>
                                        <li>Point difference (lagging & surpassing)</li>
                                    </ul>
                                </li>

                                <!-- Exclusive Rewards -->
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">🏆 Exclusive Top 50 Rewards</div>
                                    <div class="small text-muted">Exclusive Frames & Titles for Global Top 50.</div>
                                    <ul class="small text-muted ps-3 mt-1">
                                        <li>Special frame/border on profile photo</li>
                                        <li>Title displayed on leaderboard</li>
                                        <li>Frame color based on ranking</li>
                                        <li>Auto-sync via <code>SyncLeaderboardFrames</code> command</li>
                                    </ul>
                                </li>

                                <!-- Search -->
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">🔍 Search Functionality</div>
                                    <div class="small text-muted">Search for Teams & Members on the Teams page, plus Leaderboard search.</div>
                                </li>

                                <!-- Custom Range -->
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">📅 Custom Range Attendance Report</div>
                                    <div class="small text-muted">New options: 60 days, 90 days, 6 months, and 1 year.</div>
                                </li>
                            </ul>
                        </div>

                        <!-- ===== IMPROVED SECTION ===== -->
                        <div class="changelog-section mb-4">
                            <h6 class="fw-bold text-info mb-3 d-flex align-items-center">
                                <span class="badge bg-info me-2"><i class="fas fa-sync-alt"></i></span> Improved
                            </h6>
                            <ul class="list-unstyled ms-2 ps-3 border-start" style="border-width: 3px !important; border-color: #e0f2fe !important;">
                                
                                <!-- Profile -->
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">Profile Page</div>
                                    <ul class="small text-muted ps-3 mt-1">
                                        <li>Shows current user level</li>
                                        <li>List of earned achievements</li>
                                        <li>Active/inactive status</li>
                                        <li>Quick stats: sessions, badges, points, rank</li>
                                        <li>Responsive layout for all screen sizes</li>
                                        <li>Name change button (if not used yet)</li>
                                    </ul>
                                </li>

                                <!-- Users Page -->
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">Users Page (Lecturer)</div>
                                    <ul class="small text-muted ps-3 mt-1">
                                        <li>Top 50 frame/border on profile photos</li>
                                        <li>User level displayed</li>
                                        <li>Earned titles shown</li>
                                        <li>Filter by teams led/joined</li>
                                    </ul>
                                </li>

                                <!-- Check-in/out Animation -->
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">Check-in & Check-out Animation</div>
                                    <ul class="small text-muted ps-3 mt-1">
                                        <li>Super smooth animation from attendance process to success</li>
                                        <li>Motivational quotes displayed after successful attendance</li>
                                        <li>Fade-in and slide-up effects</li>
                                        <li>Replaces the old welcome message</li>
                                    </ul>
                                </li>

                                <!-- Last Person & Most Late -->
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">Last Person & Most Late Person</div>
                                    <ul class="small text-muted ps-3 mt-1">
                                        <li><strong>Last Person:</strong> Now shows the last person to check out (not check in)</li>
                                        <li><strong>Most Late Person:</strong> Person who checked in last (arrived latest) that day in the team</li>
                                        <li>Applies to monthly and custom range attendance pages</li>
                                        <li>Detail modals for each category</li>
                                    </ul>
                                </li>

                                <!-- Dashboard -->
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">New Dashboard</div>
                                    <ul class="small text-muted ps-3 mt-1">
                                        <li>New look with EXP info and next level progress</li>
                                        <li>Live Activity: real-time check-in status</li>
                                        <li>Today's Highlights: daily summary</li>
                                        <li>Performance Insights: performance overview</li>
                                        <li>Top Global Players podium</li>
                                        <li>Rank Rivalry card</li>
                                        <li>Journey card with motivational message</li>
                                        <li>Quick navigation icon menu</li>
                                        <li>Banner carousel</li>
                                    </ul>
                                </li>

                                <!-- Leaderboard System -->
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">{{ __('Leaderboard') }} System</div>
                                    <ul class="small text-muted ps-3 mt-1">
                                        <li>Changed to hourly cut-off system</li>
                                        <li>Rank change indicators (up/down)</li>
                                        <li>Performance data recap</li>
                                        <li>6 categories: Top Levels, Sessions, Duration, Locations, Points, Teams</li>
                                        <li>New tables: <code>user_leaderboards</code>, <code>location_leaderboards</code>, <code>team_leaderboards</code></li>
                                        <li>Columns: <code>third_score</code>, <code>title</code>, <code>frame_color</code></li>
                                    </ul>
                                </li>

                                <li class="mb-2"><strong>Top Global Players:</strong> More modern podium display with exclusive frames.</li>
                                <li class="mb-2"><strong>{{ __('Evaluation Report') }}:</strong> More detailed and accurate, including motivation & growth narrative.</li>
                                <li class="mb-2"><strong>Teams Page:</strong> Revamped to support large-scale teams with search and filters.</li>
                                <li class="mb-2"><strong>Attendance Report:</strong> More informative with clear time periods and full statistics (total duration, average, streak, etc).</li>
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">New Quiz Content</div>
                                    <ul class="small text-muted ps-3 mt-1">
                                        <li>Quiz: +100 new questions (±300 total)</li>
                                        <li>Super Quiz: +100 new questions (±200 total)</li>
                                    </ul>
                                </li>
                            </ul>
                        </div>

                        <!-- ===== FIXED SECTION ===== -->
                        <div class="changelog-section mb-4">
                            <h6 class="fw-bold text-warning mb-3 d-flex align-items-center">
                                <span class="badge bg-warning text-dark me-2"><i class="fas fa-bug"></i></span> Fixed
                            </h6>
                            <ul class="list-unstyled ms-2 ps-3 border-start" style="border-width: 3px !important; border-color: #fef3c7 !important;">
                                <li class="mb-2 small text-muted">Fixed bugs in the redeem feature.</li>
                                <li class="mb-2 small text-muted">Fixed bugs in Quiz & Super Quiz.</li>
                                <li class="mb-2 small text-muted">Fixed team leaders logic (<code>FixTeamLeadersCommand</code>).</li>
                                <li class="mb-2 small text-muted">Fixed expired attendance deactivation.</li>
                                <li class="mb-2 small text-muted">Stability improvements and bottleneck prevention across the system.</li>
                            </ul>
                        </div>

                        <!-- ===== PERFORMANCE SECTION ===== -->
                        <div class="changelog-section mb-4">
                            <h6 class="fw-bold text-success mb-3 d-flex align-items-center">
                                <span class="badge bg-success me-2"><i class="fas fa-bolt"></i></span> Performance
                            </h6>
                            <ul class="list-unstyled ms-2 ps-3 border-start" style="border-width: 3px !important; border-color: #dcfce7 !important;">
                                <li class="mb-2 small text-muted">{{ __('Leaderboard') }} updated automatically every hour via scheduled command.</li>
                                <li class="mb-2 small text-muted">Redis implementation for cache and queue.</li>
                                <li class="mb-2 small text-muted">SaiQu AI uses queue system and caching to avoid blocking.</li>
                                <li class="mb-2 small text-muted">Caching on achievements, rewards, levels, and statistics data.</li>
                                <li class="mb-2 small text-muted">Overall system performance and stability improvements.</li>
                            </ul>
                        </div>

                        <!-- ===== TECHNICAL SECTION ===== -->
                        <div class="changelog-section">
                            <h6 class="fw-bold mb-3 d-flex align-items-center" style="color: #6366f1;">
                                <span class="badge me-2" style="background: #6366f1;"><i class="fas fa-code"></i></span> Technical & Database
                            </h6>
                            <ul class="list-unstyled ms-2 ps-3 border-start" style="border-width: 3px !important; border-color: #e0e7ff !important;">
                                <li class="mb-2 small text-muted">New migrations: <code>user_leaderboards</code>, <code>location_leaderboards</code>, <code>team_leaderboards</code></li>
                                <li class="mb-2 small text-muted">New migrations: <code>feedbacks</code>, <code>feedback_likes</code></li>
                                <li class="mb-2 small text-muted">New migration: <code>saiqu_conversations</code></li>
                                <li class="mb-2 small text-muted">New column: <code>name_changed</code> in users table</li>
                                <li class="mb-2 small text-muted">New column: <code>comparison_user_id</code> in users table</li>
                                <li class="mb-2 small text-muted">New columns: <code>third_score</code>, <code>title</code>, <code>frame_color</code> in user_leaderboards</li>
                                <li class="mb-2 small text-muted">New artisan commands: <code>SyncLeaderboardFrames</code>, <code>UpdateLeaderboards</code>, <code>FixTeamLeaders</code></li>
                                <li class="mb-2 small text-muted">Service layer: <code>GeminiService</code>, <code>KnowledgeService</code>, <code>QuestionValidator</code></li>
                                <li class="mb-2 small text-muted">SaiQu config: <code>config/saiqu.php</code></li>
                                <li class="mb-2 small text-muted">Seeders: <code>QuotesTableSeeder</code> (100 quotes), <code>UpdateLeaderboardStylesSeeder</code></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Previous Version Link -->
            <div class="px-4 pb-2">
                <details class="small text-muted">
                    <summary style="cursor: pointer;">📋 v1.5 Changelog (ringkasan)</summary>
                    <div class="mt-2 ps-3 border-start" style="border-color: #e2e8f0 !important;">
                        <div class="mb-1"><strong>Added:</strong> Interactive Feedback, Points Rivalry, YOU Standing, Leaderboard Insight, Exclusive Rewards, Search, Custom Range Report</div>
                        <div class="mb-1"><strong>Improved:</strong> Dashboard UI, Leaderboard System, Top Global, Evaluation Report, Teams Page, Quiz Content</div>
                        <div class="mb-1"><strong>Fixed:</strong> Redeem bugs, Quiz & Super Quiz bugs</div>
                        <div><strong>Performance:</strong> Hourly leaderboard, Redis cache & queue</div>
                    </div>
                </details>
            </div>

            <div class="modal-footer border-0 p-3 bg-light">
                <button type="button" class="btn btn-primary w-100 fw-bold py-2" data-bs-dismiss="modal" style="border-radius: 12px; background: linear-gradient(135deg, #1e3a8a, #3b82f6); border: none;">I Understand</button>
            </div>
        </div>
    </div>
</div>

<style>
    #changelogTab .nav-link {
        color: #475569;
        background: transparent;
        border: none;
        transition: all 0.2s ease;
    }
    #changelogTab .nav-link.active {
        color: #1e3a8a;
        background-color: #ffffff;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .changelog-section h6 .badge {
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        padding: 0;
        font-size: 0.75rem;
    }
    .tab-content::-webkit-scrollbar {
        width: 4px;
    }
    .tab-content::-webkit-scrollbar-thumb {
        background: #cbd5e0;
        border-radius: 10px;
    }
</style>
