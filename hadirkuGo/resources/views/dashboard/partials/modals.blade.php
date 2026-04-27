<!-- What is HadirkuGO Section -->
<div class="col-12 col-md-12">
    <div class="card shadow-sm mb-4 animate-card"
         style="background: linear-gradient(135deg, #3256bd, #1eafa3);
            border-radius: 15px; padding: 20px; color: white; border: none;">
    <div class="card-body">
        <div class="text-center mb-3">
            <h5 class="fw-bold">What is HadirkuGO?</h5>
        </div>
        <div class="text-center">
            <p class="text-light">
                HadirkuGO transforms attendance into a fun, game-like experience. Earn points, collect badges, and climb the ranks while making your presence a meaningful part of your life. Be the MVP every day!
            </p>
        </div>
        <div class="text-center mt-4">
            <button id="discoverBtn"
                    style="background: linear-gradient(135deg, #1e3a8a, #3b82f6); border: none; border-radius: 12px; padding: 12px 24px; font-size: 16px; font-weight: bold; color: white; cursor: pointer; position: relative; overflow: hidden; transition: transform 0.2s, box-shadow 0.2s;"
                    onclick="window.location.href='https://bisnisdigital.raharja.ac.id/hadirkugo-gamifikasi-absensi-ubah-kerja-seru-seperti-main-game/'">
                <span style="position: relative; z-index: 2;">Discover the Answer</span>
                <span class="btn-shine"></span>
            </button>
        </div>
    </div>
    </div>
</div>

<!-- Modals -->
<!-- Tesla Info Modal -->
<div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg" style="border-radius: 15px; overflow: hidden; background: linear-gradient(to bottom, #001f3f, #004080);">
            <div class="modal-header text-white" style="border-bottom: none; background: linear-gradient(to right, #001f3f, #004080);">
                <h5 class="modal-title fw-bold glow-effect" id="infoModalLabel">What are {{ __('Tesla Points') }}?</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center text-light">
                <img src="https://drive.pastibisa.app/1737014429_6788bc9d741d3.png" alt="Tesla Icon" style="width: 80px; height: auto; margin-bottom: 20px; filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.5));">
                <p class="fs-5">{{ __('Tesla Points') }} are your reward for consistent attendance and engagement!</p>
                <p class="mb-0">⚡ Earn points by checking in and staying longer at available locations.</p>
                <p>⚡ Use them to level up and unlock exclusive rewards!</p>
                <div class="mt-4">
                    <i class="fas fa-trophy fa-3x" style="color: #f59e0b;"></i>
                    <p class="mt-2 mb-0">"Every check-in brings you closer to greatness!"</p>
                </div>
            </div>
            <div class="modal-footer" style="border-top: none; background: linear-gradient(to bottom, #001f3f, #004080);">
                <button type="button" class="btn btn-light w-100 fw-bold" data-bs-dismiss="modal" style="border-radius: 15px;">Got It!</button>
            </div>
        </div>
    </div>
</div>

<!-- Team Required Modal -->
<div class="modal fade" id="teamRequiredModal" tabindex="-1" aria-labelledby="teamRequiredModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 15px;">
            <div class="modal-header">
                <h5 class="modal-title" id="teamRequiredModalLabel">Team Required</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>You must <strong>join a team</strong> first before you can access the {{ __('Leaderboard') }}.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" style="border-radius: 12px;">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- {{ __('Check-In') }} Required Modal -->
<div class="modal fade" id="checkinRequiredModal" tabindex="-1" aria-labelledby="checkinRequiredModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 15px;">
            <div class="modal-header">
                <h5 class="modal-title" id="checkinRequiredModalLabel">{{ __('Check-In') }} Required</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>You must <strong>{{ __('Check-In') }}</strong> first before you can {{ __('Check-Out') }}.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" style="border-radius: 12px;">OK</button>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-shine {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 300%;
        height: 300%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.4), rgba(255, 255, 255, 0) 70%);
        transform: translate(-50%, -50%) rotate(45deg);
        opacity: 0;
        transition: opacity 0.5s;
        pointer-events: none;
    }
    #discoverBtn:hover .btn-shine {
        opacity: 1;
    }
</style>
