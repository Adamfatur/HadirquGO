{{-- Reusable leaderboard search component --}}
{{-- Required: $searchCategory (string) - the leaderboard category to search in --}}
@php
    $rolePrefix = Auth::user()->hasRole('Lecturer') ? 'lecturer' : 'student';
@endphp

<div class="position-relative mb-4">
    <div class="input-group shadow-sm" style="border-radius: 12px; overflow: hidden; max-width: 450px; margin: 0 auto;">
        <span class="input-group-text bg-white border-0" style="padding-left: 16px;">
            <i class="fas fa-search text-muted"></i>
        </span>
        <input type="text"
               id="leaderboardSearchInput"
               class="form-control border-0 py-2"
               placeholder="{{ __('Search name...') }}"
               autocomplete="off"
               style="font-size: 0.9rem; box-shadow: none;">
        <button type="button" id="leaderboardSearchClear" class="btn btn-link text-muted d-none border-0" style="text-decoration: none;">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div id="leaderboardSearchResult" class="d-none" style="max-width: 450px; margin: 12px auto 0;"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('leaderboardSearchInput');
    const clearBtn = document.getElementById('leaderboardSearchClear');
    const resultBox = document.getElementById('leaderboardSearchResult');
    const tableBody = document.querySelector('table.table tbody');
    if (!input || !tableBody) return;

    const allRows = Array.from(tableBody.querySelectorAll('tr'));
    let debounceTimer = null;
    let abortController = null;
    const searchUrl = "{{ route($rolePrefix . '.viewboard.search') }}";
    const evalRouteBase = "{{ url($rolePrefix . '/evaluation') }}";
    const category = @json($searchCategory);

    input.addEventListener('input', function() {
        const q = this.value.trim().toLowerCase();
        clearBtn.classList.toggle('d-none', q.length === 0);

        if (debounceTimer) clearTimeout(debounceTimer);
        if (abortController) { abortController.abort(); abortController = null; }

        if (q.length === 0) {
            allRows.forEach(r => r.style.display = '');
            resultBox.classList.add('d-none');
            resultBox.innerHTML = '';
            return;
        }

        // Step 1: Client-side filter (instant)
        let found = 0;
        allRows.forEach(r => {
            const name = r.textContent.toLowerCase();
            const match = name.includes(q);
            r.style.display = match ? '' : 'none';
            if (match) found++;
        });

        // Step 2: AJAX search — always run when query >= 2 chars to find users outside top 50
        if (q.length >= 2) {
            resultBox.innerHTML = '<div class="text-center py-2"><div class="spinner-border spinner-border-sm text-primary" role="status"></div> <span class="small text-muted ms-1">Searching...</span></div>';
            resultBox.classList.remove('d-none');

            debounceTimer = setTimeout(() => {
                abortController = new AbortController();
                fetch(`${searchUrl}?q=${encodeURIComponent(q)}&category=${encodeURIComponent(category)}`, {
                    signal: abortController.signal,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.length === 0) {
                        resultBox.innerHTML = '<div class="card shadow-sm border-0 p-3 text-center" style="border-radius: 12px; background: #f9f9fb;"><i class="fas fa-user-slash text-muted mb-1"></i><p class="small text-muted mb-0">No user found for "<strong>' + q.replace(/</g,'&lt;') + '</strong>"</p></div>';
                    } else {
                        let html = '<div class="card shadow-sm border-0" style="border-radius: 12px; background: #f9f9fb; overflow: hidden;">';
                        html += '<div class="px-3 py-2" style="background: #1e3a8a; color: white; font-size: 0.8rem; font-weight: 600;"><i class="fas fa-search me-1"></i> Search Results</div>';
                        html += '<div class="list-group list-group-flush">';
                        data.forEach(item => {
                            const avatar = item.avatar || '/images/default-avatar.png';
                            const evalLink = item.member_id ? `${evalRouteBase}/${item.member_id}` : '#';
                            const rankBadge = item.rank ? `#${Number(item.rank).toLocaleString()}` : 'Unranked';
                            const scoreDisplay = item.score_display || (item.score ? Number(item.score).toLocaleString() : '0');
                            const inTop50 = item.rank && item.rank <= 50;
                            const badgeBg = inTop50 ? 'bg-success-subtle text-success' : 'bg-primary-subtle text-primary';
                            html += `<a href="${evalLink}" class="list-group-item list-group-item-action d-flex align-items-center py-2 px-3" style="transition: background 0.2s;">`;
                            html += `<span class="badge bg-light text-dark me-2 fw-bold" style="min-width: 50px; font-size: 0.8rem;">${rankBadge}</span>`;
                            html += `<img src="${avatar}" class="rounded-circle me-2" style="width: 35px; height: 35px; object-fit: cover; border: 2px solid #e2e8f0;">`;
                            html += `<div class="flex-grow-1 overflow-hidden"><div class="fw-bold text-truncate" style="font-size: 0.9rem;">${item.name}</div></div>`;
                            html += `<span class="badge ${badgeBg} rounded-pill ms-2" style="font-size: 0.75rem;">${scoreDisplay}</span>`;
                            html += `</a>`;
                        });
                        html += '</div></div>';
                        resultBox.innerHTML = html;
                    }
                    resultBox.classList.remove('d-none');
                })
                .catch(e => {
                    if (e.name !== 'AbortError') {
                        resultBox.innerHTML = '';
                        resultBox.classList.add('d-none');
                    }
                });
            }, 500);
        } else {
            resultBox.innerHTML = '';
            resultBox.classList.add('d-none');
        }
    });

    clearBtn.addEventListener('click', function() {
        input.value = '';
        input.dispatchEvent(new Event('input'));
        input.focus();
    });
});
</script>
