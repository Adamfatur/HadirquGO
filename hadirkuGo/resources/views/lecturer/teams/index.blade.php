@extends('layout.lecturer')

@section('title', 'My Teams')

@section('content')
    <div class="container mt-4">
        <h1 class="fw-bold" style="color: #ffffff;">{{ __('Teams Overviews') }}</h1>

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

        <!-- Teams Led by Lecturer -->
        <div class="mt-4">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                <h2 class="fw-bold mb-0" style="color: #ffffff;">{{ __('Teams You Lead') }}</h2>
                <div class="mt-2 mt-md-0" style="min-width: 250px;">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" class="form-control border-start-0" id="searchTeamsLed" placeholder="{{ __('Search teams you lead...') }}" onkeyup="filterTeams('teamsLedContainer', this.value)">
                    </div>
                </div>
            </div>

            <div class="row" id="teamsLedContainer">
            @forelse($teamsLed as $team)
                <div class="col-md-6 col-lg-4 mb-4 team-card" data-name="{{ strtolower($team->name) }}" data-id="{{ strtolower($team->team_unique_id) }}">
                    <div class="card shadow-sm h-100" style="border-radius: 10px; border-left: 5px solid #ffc107;">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title fw-bold mb-0" style="color: #153e75; font-size: 1.1rem; line-height: 1.4;">{{ $team->name }}</h5>
                                <button class="btn btn-outline-warning btn-sm ms-2"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editTeamModal-{{ $team->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                            
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2 flex-wrap">
                                    <i class="fas fa-id-badge me-2 text-muted"></i>
                                    <span class="text-muted small">ID:</span>
                                    <span class="fw-bold ms-2 small">{{ $team->team_unique_id }}</span>
                                    <button class="btn btn-link btn-sm p-0 ms-2 text-decoration-none"
                                            onclick="copyToClipboard('{{ $team->team_unique_id }}')"
                                            title="Copy Team ID">
                                        <i class="fas fa-copy small text-muted"></i>
                                    </button>
                                </div>

                                <div class="d-flex align-items-center">
                                    <i class="fas fa-users me-2 text-muted"></i>
                                    <span class="text-muted small">Members:</span>
                                    <span class="fw-bold ms-2 small">{{ $team->members->count() }}</span>
                                </div>
                            </div>

                            <div class="mt-auto">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('lecturer.attendance.index', $team->team_unique_id) }}"
                                       class="btn btn-info btn-sm">
                                        <i class="fas fa-calendar-check"></i> {{ __('View Attendance') }}
                                    </a>

                                    <div class="d-flex gap-2">
                                        @if($team->leader_id === Auth::id())
                                            <button class="btn btn-warning btn-sm flex-grow-1"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#transferLeaderModal-{{ $team->id }}">
                                                <i class="fas fa-exchange-alt"></i> {{ __('Transfer') }}
                                            </button>
                                        @endif

                                        <button class="btn btn-primary btn-sm flex-grow-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#addMemberModalLeader-{{ $team->id }}">
                                            <i class="fas fa-user-plus"></i> {{ __('Add Member') }}
                                        </button>
                                    </div>
                                    
                                    <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMembers-{{ $team->id }}" aria-expanded="false">
                                        <i class="fas fa-users-cog"></i> {{ __('Manage Members') }}
                                    </button>

                                    @if(($team->business && $team->business->owner_id === Auth::id()) || $team->leader_id === Auth::id())
                                        <button class="btn btn-outline-danger btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#dissolveTeamModal-{{ $team->id }}">
                                            <i class="fas fa-trash-alt"></i> {{ __('Dissolve Team') }}
                                        </button>
                                    @endif
                                </div>
                            </div>

                            <div class="collapse mt-3" id="collapseMembers-{{ $team->id }}">
                                <div class="p-2 border rounded bg-light">
                                    <div class="input-group input-group-sm mb-2">
                                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                                        <input type="text" class="form-control border-start-0" placeholder="Filter members..." onkeyup="filterMembers('{{ $team->id }}', this.value)">
                                    </div>
                                    <ul class="list-group list-group-flush shadow-sm" id="member-list-{{ $team->id }}" style="max-height: 150px; overflow-y: auto; border-radius: 5px;">
                                        @foreach($team->members as $member)
                                            <li class="list-group-item d-flex align-items-center justify-content-between py-2 member-item" data-name="{{ strtolower($member->name) }}" style="font-size: 0.85rem;">
                                            <div class="d-flex align-items-center flex-grow-1">
                                                <img src="{{ $member->avatar ?? asset('images/default-avatar.png') }}"
                                                     alt="{{ $member->name }}"
                                                     class="rounded-circle me-2"
                                                     style="width: 25px; height: 25px; object-fit: cover;">
                                                <div class="text-truncate" style="max-width: 120px;">
                                                    <span class="fw-medium">{{ $member->name }}</span>
                                                </div>
                                            </div>
                                            @if(Auth::id() === $team->leader_id || ($team->managers->contains(Auth::id()) && $member->id !== $team->leader_id && !$team->managers->contains($member->id)))
                                                <button class="btn btn-link text-danger btn-sm p-0"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#removeMemberModal-{{ $team->id }}-{{ $member->id }}">
                                                    <i class="fas fa-user-minus"></i>
                                                </button>
                                            @endif
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modals for Teams Led -->
                <div class="modal fade" id="editTeamModal-{{ $team->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form action="{{ route('lecturer.teams.updateName', $team->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="modal-header">
                                    <h5 class="modal-title">{{ __('Edit') }} Team Name</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">New Team Name</label>
                                        <input type="text" name="name" class="form-control" value="{{ $team->name }}" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-warning">{{ __('Save Changes') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="transferLeaderModal-{{ $team->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form action="{{ route('lecturer.teams.transferLeader', $team->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="modal-header">
                                    <h5 class="modal-title">Transfer Leadership</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Select New Leader</label>
                                        <select name="new_leader_id" class="form-control" required>
                                            @foreach($team->business->staff as $staff)
                                                @if($staff->user_id !== $team->leader_id)
                                                    <option value="{{ $staff->user_id }}">{{ $staff->user->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-warning">Transfer Leadership</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="addMemberModalLeader-{{ $team->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form action="{{ route('lecturer.teams.addMember', $team->id) }}" method="POST">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title">Add Member to {{ $team->name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Search Member</label>
                                        <input type="text" id="member-search-leader-{{ $team->id }}" class="form-control" placeholder="Enter student name or ID" onkeyup="searchMemberLeader('{{ $team->id }}')">
                                        <ul id="member-results-leader-{{ $team->id }}" class="list-group mt-2" style="max-height: 200px; overflow-y: auto;"></ul>
                                    </div>
                                </div>
                                <input type="hidden" name="user_id" id="selected-user-id-leader-{{ $team->id }}">
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Add Member</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                @foreach($team->members as $member)
                    @if(Auth::id() === $team->leader_id || ($team->managers->contains(Auth::id()) && $member->id !== $team->leader_id && !$team->managers->contains($member->id)))
                        <div class="modal fade" id="removeMemberModal-{{ $team->id }}-{{ $member->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">{{ __('Confirm') }} Removal</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to remove <strong>{{ $member->name }}</strong> from the team?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <form action="{{ route('lecturer.teams.removeMember', [$team->id, $member->id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Remove</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach

                {{-- Dissolve Team Modal (only for business owner) --}}
                @if(($team->business && $team->business->owner_id === Auth::id()) || $team->leader_id === Auth::id())
                    <div class="modal fade" id="dissolveTeamModal-{{ $team->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Dissolve Team</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to dissolve <strong>{{ $team->name }}</strong>?</p>
                                    <div class="alert alert-warning small mb-3">
                                        <i class="fas fa-info-circle me-1"></i>
                                        This will permanently remove the team, all member associations, and related leaderboard data. This action cannot be undone.
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Type the team name to confirm:</label>
                                        <input type="text" class="form-control" id="confirmDissolve-{{ $team->id }}" placeholder="{{ $team->name }}" autocomplete="off">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <form action="{{ route('lecturer.teams.dissolve', $team->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" id="dissolveBtn-{{ $team->id }}" disabled>
                                            <i class="fas fa-trash-alt me-1"></i> Dissolve
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            @empty
                <div class="col-12 text-center">
                    <div class="card shadow-sm" style="border-radius: 10px;">
                        <div class="card-body py-5">
                            <i class="fas fa-users-slash fa-3x mb-3 text-muted"></i>
                            <h3 class="fw-bold text-muted">No Teams Found</h3>
                            <p class="text-muted">You are currently not leading any teams.</p>
                        </div>
                    </div>
                </div>
            @endforelse
            </div>
        </div>

        <!-- Teams Joined by Lecturer -->
        <div class="mt-5">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                <h2 class="fw-bold mb-0" style="color: #ffffff;">{{ __("Teams You're a Member Of") }}</h2>
                <div class="mt-2 mt-md-0" style="min-width: 250px;">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" class="form-control border-start-0" id="searchTeamsJoined" placeholder="{{ __('Search joined teams...') }}" onkeyup="filterTeams('teamsJoinedContainer', this.value)">
                    </div>
                </div>
            </div>

            <div class="row" id="teamsJoinedContainer">
            @forelse($teamsJoined as $team)
                <div class="col-md-6 col-lg-4 mb-4 team-card" data-name="{{ strtolower($team->name) }}" data-id="{{ strtolower($team->team_unique_id) }}">
                    <div class="card shadow-sm h-100" style="border-radius: 10px; border-left: 5px solid #17a2b8;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold mb-3" style="color: #153e75; font-size: 1.1rem; line-height: 1.4;">{{ $team->name }}</h5>

                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2 flex-wrap">
                                    <i class="fas fa-id-badge me-2 text-muted"></i>
                                    <span class="text-muted small">ID:</span>
                                    <span class="fw-bold ms-2 small">{{ $team->team_unique_id }}</span>
                                    <button class="btn btn-link btn-sm p-0 ms-2 text-decoration-none"
                                            onclick="copyToClipboard('{{ $team->team_unique_id }}')"
                                            title="Copy Team ID">
                                        <i class="fas fa-copy small text-muted"></i>
                                    </button>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-crown me-2 text-warning"></i>
                                    <span class="text-muted small">Leader:</span>
                                    <span class="fw-bold ms-2 small">{{ $team->leader->name ?? 'No Leader' }}</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-users me-2 text-muted"></i>
                                    <span class="text-muted small">Members:</span>
                                    <span class="fw-bold ms-2 small">{{ $team->members->count() }}</span>
                                </div>
                            </div>

                            <div class="mt-auto">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('lecturer.attendance.index', $team->team_unique_id) }}"
                                       class="btn btn-info btn-sm">
                                        <i class="fas fa-calendar-check"></i> {{ __('View Attendance') }}
                                    </a>

                                    @if($team->managers->contains(Auth::id()))
                                        <button class="btn btn-primary btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#addMemberModalManager-{{ $team->id }}">
                                            <i class="fas fa-user-plus"></i> {{ __('Add Member') }}
                                        </button>
                                    @endif
                                    
                                    <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMembersJoined-{{ $team->id }}" aria-expanded="false">
                                        <i class="fas fa-users"></i> {{ __('Show Members') }}
                                    </button>
                                </div>
                            </div>

                            <div class="collapse mt-3" id="collapseMembersJoined-{{ $team->id }}">
                                <div class="p-2 border rounded bg-light">
                                    <div class="input-group input-group-sm mb-2">
                                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                                        <input type="text" class="form-control border-start-0" placeholder="Filter members..." onkeyup="filterMembers('{{ $team->id }}', this.value)">
                                    </div>
                                    <ul class="list-group list-group-flush shadow-sm" id="member-list-{{ $team->id }}" style="max-height: 150px; overflow-y: auto; border-radius: 5px;">
                                        @foreach($team->members as $member)
                                            <li class="list-group-item d-flex align-items-center justify-content-between py-2 member-item" data-name="{{ strtolower($member->name) }}" style="font-size: 0.85rem;">
                                            <div class="d-flex align-items-center flex-grow-1">
                                                <img src="{{ $member->avatar ?? asset('images/default-avatar.png') }}"
                                                     alt="{{ $member->name }}"
                                                     class="rounded-circle me-2"
                                                     style="width: 25px; height: 25px; object-fit: cover;">
                                                <div>
                                                    <span class="fw-medium">{{ $member->name }}</span>
                                                    @if($member->id === $team->leader_id)
                                                        <span class="badge bg-warning text-dark ms-1" style="font-size: 0.65rem;">Leader</span>
                                                    @endif
                                                </div>
                                            </div>
                                            @if(Auth::id() === $team->leader_id || ($team->managers->contains(Auth::id()) && $member->id !== $team->leader_id && !$team->managers->contains($member->id)))
                                                <button class="btn btn-link text-danger btn-sm p-0"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#removeMemberModal-{{ $team->id }}-{{ $member->id }}">
                                                    <i class="fas fa-user-minus"></i>
                                                </button>
                                            @endif
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modals for Joined Teams -->
                <div class="modal fade" id="addMemberModalManager-{{ $team->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form action="{{ route('lecturer.teams.addMember', $team->id) }}" method="POST">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title">Add Member to {{ $team->name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Search Member</label>
                                        <input type="text" id="member-search-manager-{{ $team->id }}" class="form-control" placeholder="Enter student name or ID" onkeyup="searchMemberManager('{{ $team->id }}')">
                                        <ul id="member-results-manager-{{ $team->id }}" class="list-group mt-2" style="max-height: 200px; overflow-y: auto;"></ul>
                                    </div>
                                </div>
                                <input type="hidden" name="user_id" id="selected-user-id-manager-{{ $team->id }}">
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Add Member</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                @foreach($team->members as $member)
                    @if(Auth::id() === $team->leader_id || ($team->managers->contains(Auth::id()) && $member->id !== $team->leader_id && !$team->managers->contains($member->id)))
                        <div class="modal fade" id="removeMemberModal-{{ $team->id }}-{{ $member->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">{{ __('Confirm') }} Removal</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to remove <strong>{{ $member->name }}</strong> from the team?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <form action="{{ route('lecturer.teams.removeMember', [$team->id, $member->id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Remove</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @empty
                <div class="col-12 text-center">
                    <div class="card shadow-sm" style="border-radius: 10px;">
                        <div class="card-body py-5">
                            <i class="fas fa-users-slash fa-3x mb-3 text-muted"></i>
                            <h3 class="fw-bold text-muted">No Teams Joined</h3>
                            <p class="text-muted">You are currently not a member of any teams.</p>
                        </div>
                    </div>
                </div>
            @endforelse
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text)
                .then(() => {
                    // Using a more subtle toast would be better but keeping consistency
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

        function searchMemberLeader(teamId) {
            let query = document.getElementById(`member-search-leader-${teamId}`).value;
            let resultsContainer = document.getElementById(`member-results-leader-${teamId}`);
            if (query.length < 2) {
                resultsContainer.innerHTML = '';
                return;
            }
            fetch(`/lecturer/teams/search-members?query=${query}`)
                .then(response => response.ok ? response.json() : Promise.reject('Network error'))
                .then(data => {
                    resultsContainer.innerHTML = '';
                    data.forEach(member => {
                        let listItem = document.createElement('li');
                        listItem.classList.add('list-group-item', 'list-group-item-action', 'd-flex', 'align-items-center');
                        listItem.style.cursor = 'pointer';
                        let avatar = member.avatar || '/images/default-avatar.png';
                        listItem.innerHTML = `
                            <img src="${avatar}" alt="${member.name}" class="rounded-circle me-2" style="width: 30px; height: 30px; object-fit: cover;">
                            <span style="font-size: 0.9rem;">${member.name}</span>
                        `;
                        listItem.onclick = () => selectMemberLeader(teamId, member.id, member.name);
                        resultsContainer.appendChild(listItem);
                    });
                })
                .catch(error => console.error('Error fetching member data:', error));
        }

        function selectMemberLeader(teamId, userId, userName) {
            document.getElementById(`selected-user-id-leader-${teamId}`).value = userId;
            document.getElementById(`member-search-leader-${teamId}`).value = userName;
            document.getElementById(`member-results-leader-${teamId}`).innerHTML = '';
        }

        function searchMemberManager(teamId) {
            let query = document.getElementById(`member-search-manager-${teamId}`).value;
            let resultsContainer = document.getElementById(`member-results-manager-${teamId}`);
            if (query.length < 2) {
                resultsContainer.innerHTML = '';
                return;
            }
            fetch(`/lecturer/teams/search-members?query=${query}`)
                .then(response => response.ok ? response.json() : Promise.reject('Network error'))
                .then(data => {
                    resultsContainer.innerHTML = '';
                    data.forEach(member => {
                        let listItem = document.createElement('li');
                        listItem.classList.add('list-group-item', 'list-group-item-action', 'd-flex', 'align-items-center');
                        listItem.style.cursor = 'pointer';
                        let avatar = member.avatar || '/images/default-avatar.png';
                        listItem.innerHTML = `
                            <img src="${avatar}" alt="${member.name}" class="rounded-circle me-2" style="width: 30px; height: 30px; object-fit: cover;">
                            <span style="font-size: 0.9rem;">${member.name}</span>
                        `;
                        listItem.onclick = () => selectMemberManager(teamId, member.id, member.name);
                        resultsContainer.appendChild(listItem);
                    });
                })
                .catch(error => console.error('Error fetching member data:', error));
        }

        function selectMemberManager(teamId, userId, userName) {
            document.getElementById(`selected-user-id-manager-${teamId}`).value = userId;
            document.getElementById(`member-search-manager-${teamId}`).value = userName;
            document.getElementById(`member-results-manager-${teamId}`).innerHTML = '';
        }

        function filterMembers(teamId, query) {
            const list = document.getElementById(`member-list-${teamId}`);
            const items = list.getElementsByClassName("member-item");
            const q = query.toLowerCase();
            
            for (let item of items) {
                const name = item.getAttribute("data-name");
                if (name.includes(q)) {
                    item.classList.remove("d-none");
                    item.classList.add("d-flex");
                } else {
                    item.classList.remove("d-flex");
                    item.classList.add("d-none");
                }
            }
        }

        // Dissolve team confirmation: enable button only when team name matches
        document.querySelectorAll('[id^="confirmDissolve-"]').forEach(input => {
            const teamId = input.id.replace('confirmDissolve-', '');
            const btn = document.getElementById(`dissolveBtn-${teamId}`);
            const teamName = input.getAttribute('placeholder');
            input.addEventListener('input', function() {
                btn.disabled = this.value !== teamName;
            });
        });
    </script>

    <style>
        .team-card .card {
            transition: transform 0.2s ease-in-out, shadow 0.2s ease-in-out;
        }
        .team-card .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }
        .collapse.show {
            visibility: visible;
        }
        @media (max-width: 768px) {
            .container { padding: 0 15px; }
            h1 { font-size: 1.75rem; }
            h2 { font-size: 1.4rem; }
            .btn-sm { padding: 0.4rem 0.8rem; }
        }
    </style>
@endsection
