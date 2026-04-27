<div class="modal fade" id="changelogModal" tabindex="-1" aria-labelledby="changelogModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg mx-auto" style="max-width: 95%; width: 850px;">
        <div class="modal-content" style="border-radius: 24px; border: none; box-shadow: 0 20px 50px rgba(0,0,0,0.3); overflow: hidden;">
            <!-- Header Section -->
            <div class="modal-header border-0" style="background: linear-gradient(135deg, #1e3a8a, #3b82f6); color: white; padding: 18px 25px;">
                <h5 class="modal-title fw-bold d-flex align-items-center" id="changelogModalLabel">
                    <i class="fas fa-rocket me-2"></i> 
                    <span>HadirkuGO v1.5 — Changelog</span>
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
                    <!-- Indonesian Content -->
                    <div class="tab-pane fade show active" id="id-content" role="tabpanel" aria-labelledby="id-tab">
                        
                        <!-- Added Section -->
                        <div class="changelog-section mb-4">
                            <h6 class="fw-bold text-primary mb-3 d-flex align-items-center">
                                <span class="badge bg-primary me-2"><i class="fas fa-plus"></i></span> Ditambahkan
                            </h6>
                            <ul class="list-unstyled ms-2 ps-3 border-start" style="border-width: 3px !important; border-color: #e0e7ff !important;">
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">Interactive Feedback</div>
                                    <div class="small text-muted">Sistem feedback baru yang lebih interaktif dan mudah digunakan oleh user.</div>
                                </li>
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">Points Rivalry</div>
                                    <div class="small text-muted">Fitur untuk memilih target user sebagai rival dan membandingkan performa.</div>
                                </li>
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">YOU – {{ __('Your Current Standing') }}</div>
                                    <div class="small text-muted">Menampilkan posisi user saat ini meskipun tidak berada di Top 50.</div>
                                </li>
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">{{ __('Leaderboard') }} Standing Insight (Top 50)</div>
                                    <ul class="small text-muted ps-3 mt-1">
                                        <li>Informasi posisi saat ini</li>
                                        <li>User di atas dan di bawah</li>
                                        <li>Selisih poin (Poin tertinggal & melampaui)</li>
                                    </ul>
                                </li>
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">Hadiah Eksklusif</div>
                                    <div class="small text-muted">Frame & Gelar Eksklusif untuk Top 50 Global.</div>
                                </li>
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">Fitur Pencarian</div>
                                    <div class="small text-muted">Pencarian Teams & Member pada halaman Teams.</div>
                                </li>
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">Custom range Attendance Report</div>
                                    <div class="small text-muted">Pilihan baru: 60 hari, 90 hari, 6 bulan, dan 1 tahun.</div>
                                </li>
                            </ul>
                        </div>

                        <!-- Improved Section -->
                        <div class="changelog-section mb-4">
                            <h6 class="fw-bold text-info mb-3 d-flex align-items-center">
                                <span class="badge bg-info me-2"><i class="fas fa-sync-alt"></i></span> Peningkatan
                            </h6>
                            <ul class="list-unstyled ms-2 ps-3 border-start" style="border-width: 3px !important; border-color: #e0f2fe !important;">
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">Dashboard Baru</div>
                                    <div class="small text-muted">Tampilan baru dengan informasi EXP dan progress level berikutnya.</div>
                                </li>
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">Sistem {{ __('Leaderboard') }}</div>
                                    <ul class="small text-muted ps-3 mt-1">
                                        <li>Sistem berubah menjadi cut-off per jam</li>
                                        <li>Penambahan indikator perubahan peringkat (naik/turun)</li>
                                        <li>Rekap data performa</li>
                                    </ul>
                                </li>
                                <li class="mb-2"><strong>Top Global Players:</strong> Tampilan lebih modern dan informatif.</li>
                                <li class="mb-2"><strong>{{ __('Evaluation Report') }}:</strong> Lebih detail dan akurat.</li>
                                <li class="mb-2"><strong>Halaman Teams:</strong> Dirombak untuk mendukung skala besar.</li>
                                <li class="mb-2"><strong>Attendance Report:</strong> Lebih informatif dengan periode waktu jelas.</li>
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">Penambahan Soal Quiz</div>
                                    <ul class="small text-muted ps-3 mt-1">
                                        <li>Quiz: +100 (±300 total)</li>
                                        <li>Super Quiz: +100 (±200 total)</li>
                                    </ul>
                                </li>
                            </ul>
                        </div>

                        <!-- Fixed Section -->
                        <div class="changelog-section mb-4">
                            <h6 class="fw-bold text-warning mb-3 d-flex align-items-center">
                                <span class="badge bg-warning text-dark me-2"><i class="fas fa-bug"></i></span> Perbaikan
                            </h6>
                            <ul class="list-unstyled ms-2 ps-3 border-start" style="border-width: 3px !important; border-color: #fef3c7 !important;">
                                <li class="mb-2 small text-muted">Perbaikan bug pada fitur redeem.</li>
                                <li class="mb-2 small text-muted">Perbaikan bug pada Quiz & Super Quiz.</li>
                            </ul>
                        </div>

                        <!-- Performance Section -->
                        <div class="changelog-section">
                            <h6 class="fw-bold text-success mb-3 d-flex align-items-center">
                                <span class="badge bg-success me-2"><i class="fas fa-bolt"></i></span> Performa
                            </h6>
                            <ul class="list-unstyled ms-2 ps-3 border-start" style="border-width: 3px !important; border-color: #dcfce7 !important;">
                                <li class="mb-2 small text-muted">{{ __('Leaderboard') }} diperbarui otomatis setiap 1 jam.</li>
                                <li class="mb-2 small text-muted">Implementasi Redis untuk cache dan queue.</li>
                                <li class="mb-2 small text-muted">Peningkatan performa dan stabilitas sistem secara keseluruhan.</li>
                            </ul>
                        </div>
                    </div>

                    <!-- English Content -->
                    <div class="tab-pane fade" id="en-content" role="tabpanel" aria-labelledby="en-tab">
                        <!-- Added Section -->
                        <div class="changelog-section mb-4">
                            <h6 class="fw-bold text-primary mb-3 d-flex align-items-center">
                                <span class="badge bg-primary me-2"><i class="fas fa-plus"></i></span> Added
                            </h6>
                            <ul class="list-unstyled ms-2 ps-3 border-start" style="border-width: 3px !important; border-color: #e0e7ff !important;">
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">Interactive Feedback</div>
                                    <div class="small text-muted">New interactive feedback system, easier for users to use.</div>
                                </li>
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">Points Rivalry</div>
                                    <div class="small text-muted">Set a target user as a rival and compare performance.</div>
                                </li>
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">YOU – {{ __('Your Current Standing') }}</div>
                                    <div class="small text-muted">Displays current position even if not in the Top 50.</div>
                                </li>
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">{{ __('Leaderboard') }} Standing Insight (Top 50)</div>
                                    <ul class="small text-muted ps-3 mt-1">
                                        <li>Current position information</li>
                                        <li>Users above and below you</li>
                                        <li>Point difference (Points lagging & surpassing)</li>
                                    </ul>
                                </li>
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">Exclusive Rewards</div>
                                    <div class="small text-muted">Exclusive Frames & Titles for Global Top 50.</div>
                                </li>
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">Search Functionality</div>
                                    <div class="small text-muted">Search for Teams & Members on the Teams page.</div>
                                </li>
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">Custom range Attendance Report</div>
                                    <div class="small text-muted">New options: 60 days, 90 days, 6 months, and 1 year.</div>
                                </li>
                            </ul>
                        </div>

                        <!-- Improved Section -->
                        <div class="changelog-section mb-4">
                            <h6 class="fw-bold text-info mb-3 d-flex align-items-center">
                                <span class="badge bg-info me-2"><i class="fas fa-sync-alt"></i></span> Improved
                            </h6>
                            <ul class="list-unstyled ms-2 ps-3 border-start" style="border-width: 3px !important; border-color: #e0f2fe !important;">
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">Dashboard UI</div>
                                    <div class="small text-muted">New look with EXP info and next level progress.</div>
                                </li>
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">{{ __('Leaderboard') }} System</div>
                                    <ul class="small text-muted ps-3 mt-1">
                                        <li>System changed to hourly cut-off</li>
                                        <li>Rank change indicators added (up/down)</li>
                                        <li>Performance data recap</li>
                                    </ul>
                                </li>
                                <li class="mb-2"><strong>Top Global Players:</strong> More modern and informative display.</li>
                                <li class="mb-2"><strong>{{ __('Evaluation Report') }}:</strong> More detailed and accurate.</li>
                                <li class="mb-2"><strong>Teams Page:</strong> Revamped to support large-scale teams.</li>
                                <li class="mb-2"><strong>Attendance Report:</strong> More informative with clear time periods.</li>
                                <li class="mb-3">
                                    <div class="fw-bold text-dark">New Quiz Content</div>
                                    <ul class="small text-muted ps-3 mt-1">
                                        <li>Quiz: +100 (±300 total)</li>
                                        <li>Super Quiz: +100 (±200 total)</li>
                                    </ul>
                                </li>
                            </ul>
                        </div>

                        <!-- Fixed Section -->
                        <div class="changelog-section mb-4">
                            <h6 class="fw-bold text-warning mb-3 d-flex align-items-center">
                                <span class="badge bg-warning text-dark me-2"><i class="fas fa-bug"></i></span> Fixed
                            </h6>
                            <ul class="list-unstyled ms-2 ps-3 border-start" style="border-width: 3px !important; border-color: #fef3c7 !important;">
                                <li class="mb-2 small text-muted">Fixed bugs in the redeem feature.</li>
                                <li class="mb-2 small text-muted">Fixed bugs in Quiz & Super Quiz.</li>
                            </ul>
                        </div>

                        <!-- Performance Section -->
                        <div class="changelog-section">
                            <h6 class="fw-bold text-success mb-3 d-flex align-items-center">
                                <span class="badge bg-success me-2"><i class="fas fa-bolt"></i></span> Performance
                            </h6>
                            <ul class="list-unstyled ms-2 ps-3 border-start" style="border-width: 3px !important; border-color: #dcfce7 !important;">
                                <li class="mb-2 small text-muted">{{ __('Leaderboard') }} updated automatically every 1 hour.</li>
                                <li class="mb-2 small text-muted">Redis implementation for cache and queue.</li>
                                <li class="mb-2 small text-muted">Overall system performance and stability improvements.</li>
                            </ul>
                        </div>
                    </div>
                </div>
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