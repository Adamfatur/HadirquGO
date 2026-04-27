@extends('layout.student')

@section('title', 'My Teams')

@section('content')
    <div class="container mt-4">
        <h1 class="fw-bold" style="color: #ffffff;">Teams Overviews</h1>

        <!-- Display Success or Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Teams Joined Section -->
        <div class="mt-4">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                <h2 class="fw-bold mb-0" style="color: #ffffff;">Teams You've Joined</h2>
                <div class="mt-2 mt-md-0" style="min-width: 250px;">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" class="form-control border-start-0" id="searchJoinedTeams" placeholder="Search teams..." onkeyup="filterTeams('teamsContainer', this.value)">
                    </div>
                </div>
            </div>

            <div class="row" id="teamsContainer">
                @forelse($teamsJoined as $team)
                    <div class="col-md-6 col-lg-4 mb-4 team-card" data-name="{{ strtolower($team->name) }}" data-id="{{ strtolower($team->team_unique_id) }}">
                        <div class="card shadow-sm h-100" style="border-radius: 15px; border-left: 5px solid #3b82f6; overflow: hidden; border-top: none; border-right: none; border-bottom: none;">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="card-title fw-bold mb-0" style="color: #1e3a8a; font-size: 1.1rem; line-height: 1.4;">{{ $team->name }}</h5>
                                    <span class="badge bg-primary-subtle text-primary rounded-pill px-2 py-1" style="font-size: 0.65rem;">Joined</span>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="d-flex align-items-center mb-2 flex-wrap">
                                        <i class="fas fa-id-badge me-2 text-muted" style="width: 16px;"></i>
                                        <span class="text-muted small">Team ID:</span>
                                        <span class="fw-bold ms-2 small">{{ $team->team_unique_id }}</span>
                                        <button class="btn btn-link btn-sm p-0 ms-2 text-decoration-none"
                                                onclick="copyToClipboard('{{ $team->team_unique_id }}')"
                                                title="Copy Team ID">
                                            <i class="fas fa-copy small text-muted"></i>
                                        </button>
                                    </div>

                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-crown me-2 text-warning" style="width: 16px;"></i>
                                        <span class="text-muted small">Leader:</span>
                                        <span class="fw-bold ms-2 small text-truncate" style="max-width: 150px;">{{ $team->leader->name ?? 'No Leader' }}</span>
                                    </div>

                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-users me-2 text-muted" style="width: 16px;"></i>
                                        <span class="text-muted small">Members:</span>
                                        <span class="fw-bold ms-2 small">{{ $team->members->count() }}</span>
                                    </div>
                                </div>

                                <div class="mt-auto">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('student.attendance.index', $team->team_unique_id) }}"
                                           class="btn btn-info btn-sm fw-bold shadow-sm" style="border-radius: 10px; color: white;">
                                            <i class="fas fa-calendar-check me-1"></i> View Attendance
                                        </a>

                                        <a href="{{ route('student.teams.show', ['teamUniqueId' => $team->team_unique_id]) }}" 
                                           class="btn btn-primary btn-sm fw-bold shadow-sm" style="border-radius: 10px;">
                                            <i class="fas fa-chart-line me-1"></i> Details & Ranking
                                        </a>
                                        
                                        <button class="btn btn-outline-secondary btn-sm fw-bold" type="button" 
                                                data-bs-toggle="collapse" data-bs-target="#collapseMembers-{{ $team->id }}" 
                                                style="border-radius: 10px;">
                                            <i class="fas fa-users me-1"></i> Quick Member List
                                        </button>
                                    </div>
                                </div>

                                <!-- Collapsible Member List -->
                                <div class="collapse mt-3" id="collapseMembers-{{ $team->id }}">
                                    <div class="p-3 border rounded-4 bg-light shadow-inner" style="background: #f8fafc !important;">
                                        <div class="input-group input-group-sm mb-2">
                                            <span class="input-group-text bg-white border-end-0 rounded-start-pill"><i class="fas fa-search text-muted"></i></span>
                                            <input type="text" class="form-control border-start-0 rounded-end-pill" placeholder="Filter members..." onkeyup="filterMembers('{{ $team->id }}', this.value)">
                                        </div>
                                        <ul class="list-group list-group-flush" id="member-list-{{ $team->id }}" style="max-height: 200px; overflow-y: auto;">
                                            @foreach($team->members as $member)
                                                <li class="list-group-item d-flex align-items-center bg-transparent py-2 border-0 member-item" data-name="{{ strtolower($member->name) }}">
                                                    <img src="{{ $member->avatar ? (str_starts_with($member->avatar, 'http') ? $member->avatar : asset($member->avatar)) : asset('images/default-avatar.png') }}"
                                                         alt="{{ $member->name }}"
                                                         class="rounded-circle me-2 border shadow-sm"
                                                         style="width: 32px; height: 32px; object-fit: cover;">
                                                    <div class="overflow-hidden">
                                                        <div class="fw-bold text-dark text-truncate" style="font-size: 0.8rem; line-height: 1.2;">{{ $member->name }}</div>
                                                        @if($member->id === $team->leader_id)
                                                            <span class="badge bg-warning text-dark px-2 py-0" style="font-size: 0.6rem;">LEADER</span>
                                                        @else
                                                            <span class="text-muted" style="font-size: 0.65rem;">Member</span>
                                                        @endif
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="card shadow-sm border-0" style="border-radius: 20px; background: rgba(255,255,255,0.1); backdrop-filter: blur(10px);">
                            <div class="card-body py-5 text-white">
                                <i class="fas fa-users-slash fa-4x mb-3 opacity-50"></i>
                                <h3 class="fw-bold">No Teams Joined</h3>
                                <p class="opacity-75">Search for teams or join one using a Team ID.</p>
                                <a href="{{ route('student.dashboard') }}" class="btn btn-light text-primary fw-bold rounded-pill px-4 mt-3">{{ __('Back to Dashboard') }}</a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4 pb-5">
                {{ $teamsJoined->appends(['search' => request('search')])->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text)
                .then(() => {
                    alert('Team ID copied to clipboard');
                })
                .catch(err => console.error('Could not copy text: ', err));
        }

        function filterTeams(containerId, query) {
            const container = document.getElementById(containerId);
            const cards = container.getElementsByClassName("team-card");
            const q = query.toLowerCase();
            
            for (let card of cards) {
                const name = card.getAttribute("data-name");
                const id = card.getAttribute("data-id");
                if (name.includes(q) || id.includes(q)) {
                    card.style.display = "";
                } else {
                    card.style.display = "none";
                }
            }
        }

        function filterMembers(teamId, query) {
            const list = document.getElementById(`member-list-${teamId}`);
            const items = list.getElementsByClassName("member-item");
            const q = query.toLowerCase();
            
            for (let item of items) {
                const name = item.getAttribute("data-name");
                if (name.includes(q)) {
                    item.style.setProperty('display', 'flex', 'important');
                } else {
                    item.style.setProperty('display', 'none', 'important');
                }
            }
        }
    </script>

    <style>
        .team-card .card {
            transition: all 0.3s ease;
        }
        .team-card .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15) !important;
        }
        .shadow-inner {
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
        }
        .pagination .page-link {
            border-radius: 10px !important;
            margin: 0 2px;
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .pagination .active .page-link {
            background: #3b82f6;
            color: white;
        }
        @media (max-width: 768px) {
            h1 { font-size: 1.8rem; }
            .team-card { margin-bottom: 20px; }
        }
    </style>
@endsection
