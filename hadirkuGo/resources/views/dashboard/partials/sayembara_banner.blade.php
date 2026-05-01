{{-- Sayembara Avatar SaiQu Banner — only for SAI members --}}
@php
    $saiTeam = \App\Models\Team::where('team_unique_id', 'TEAM-6777B540AD053')->first();
    $isSai = false;
    if ($saiTeam && Auth::check()) {
        $uid = Auth::id();
        $isSai = $saiTeam->leader_id == $uid
            || $saiTeam->members()->where('users.id', $uid)->exists()
            || $saiTeam->managers()->where('users.id', $uid)->exists();
    }
    $now = \Carbon\Carbon::now('Asia/Jakarta');
    $contestActive = $now->between(
        \Carbon\Carbon::parse('2026-04-30 00:00:00', 'Asia/Jakarta'),
        \Carbon\Carbon::parse('2026-05-06 23:59:59', 'Asia/Jakarta')
    );
@endphp

@if($isSai && $contestActive)
<div class="mb-3">
    <a href="{{ route('sayembara.avatar.index') }}" class="text-decoration-none d-block">
        <div style="background:linear-gradient(135deg,#1e3a8a,#2563eb);border:2px solid #fbbf24;border-radius:16px;padding:14px 18px;display:flex;align-items:center;gap:14px;transition:all 0.3s;box-shadow:0 4px 20px rgba(251,191,36,0.25);"
             onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 30px rgba(251,191,36,0.4)'"
             onmouseout="this.style.transform='none';this.style.boxShadow='0 4px 20px rgba(251,191,36,0.25)'">
            <img src="/images/PosterSayembarav2.png" alt="Sayembara" style="width:56px;height:56px;border-radius:12px;object-fit:cover;border:2px solid #fbbf24;box-shadow:0 0 10px rgba(251,191,36,0.4);">
            <div style="flex:1;min-width:0;">
                <div style="font-weight:800;color:#fbbf24;font-size:0.95rem;">🎨 Sayembara Avatar SaiQu</div>
                <div style="color:#e2e8f0;font-size:0.8rem;">Tunjukkan kreativitasmu! <strong style="color:#fff;">Deadline 4 Mei 2026</strong></div>
            </div>
            <div style="background:#fbbf24;color:#1e3a8a;padding:6px 14px;border-radius:99px;font-size:0.75rem;font-weight:700;white-space:nowrap;">
                Ikut Sekarang <i class="fas fa-arrow-right ms-1"></i>
            </div>
        </div>
    </a>
</div>
@endif
