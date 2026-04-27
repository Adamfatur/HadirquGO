@extends('layout.owner')

@section('title', 'Manage Business')

@section('content')
    <div class="container mt-4">
        <!-- Improved Menu Card -->
        <div class="card mb-4 shadow-sm" style="border: none; border-radius: 0.75rem; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05); transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;">
            <div class="card-header p-3" style="background: linear-gradient(90deg, #4abea3, #1b3483); padding: 1.25rem 1.5rem; border-radius: 0.5rem 0.5rem 0 0;">
                <h4 class="text-white mb-0 text-center" style="color: #f8f9fa; font-weight: 500; font-size: 1.125rem; transition: color 0.3s ease-in-out;">Quick Navigation</h4>
            </div>
            <div class="card-body" style="padding: 1.5rem; display: flex; flex-direction: column; justify-content: center; text-align: center;">
                <div class="row">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <a href="{{ route('owner.products.index') }}" class="text-decoration-none" style="text-decoration: none; display: block; transition: transform 0.3s ease-in-out;">
                            <div class="card border-0 h-100" style="border: none; border-radius: 0.75rem; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); transition: box-shadow 0.3s ease-in-out;">
                                <div class="card-body d-flex flex-column justify-content-center text-center" style="padding: 1.5rem; display: flex; flex-direction: column; justify-content: center; text-align: center; transition: background-color 0.3s ease-in-out;">
                                    <i class="fas fa-box fa-2x text-primary mb-3" style="font-size: 2.5rem; margin-bottom: 1rem; color: #007bff; opacity: 0.8; transition: color 0.3s ease-in-out;"></i>
                                    <h5 class="card-title" style="font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem; color: #333; transition: color 0.3s ease-in-out;">Products</h5>
                                    <p class="card-text text-muted" style="font-size: 0.875rem; color: #777; transition: color 0.3s ease-in-out;">Manage your products efficiently.</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <a href="{{ route('owner.quizzes.index', $business->business_unique_id) }}" class="text-decoration-none" style="text-decoration: none; display: block; transition: transform 0.3s ease-in-out;">
                            <div class="card border-0 h-100" style="border: none; border-radius: 0.75rem; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); transition: box-shadow 0.3s ease-in-out;">
                                <div class="card-body d-flex flex-column justify-content-center text-center" style="padding: 1.5rem; display: flex; flex-direction: column; justify-content: center; text-align: center; transition: background-color 0.3s ease-in-out;">
                                    <i class="fas fa-question-circle fa-2x text-secondary mb-3" style="font-size: 2.5rem; margin-bottom: 1rem; color: #6c757d; opacity: 0.8; transition: color 0.3s ease-in-out;"></i>
                                    <h5 class="card-title" style="font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem; color: #333; transition: color 0.3s ease-in-out;">Quizzes</h5>
                                    <p class="card-text text-muted" style="font-size: 0.875rem; color: #777; transition: color 0.3s ease-in-out;">Access and create quizzes.</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <a href="{{ route('owner.member.index', $business->business_unique_id) }}" class="text-decoration-none" style="text-decoration: none; display: block; transition: transform 0.3s ease-in-out;">
                            <div class="card border-0 h-100" style="border: none; border-radius: 0.75rem; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); transition: box-shadow 0.3s ease-in-out;">
                                <div class="card-body d-flex flex-column justify-content-center text-center" style="padding: 1.5rem; display: flex; flex-direction: column; justify-content: center; text-align: center; transition: background-color 0.3s ease-in-out;">
                                    <i class="fas fa-users fa-2x text-info mb-3" style="font-size: 2.5rem; margin-bottom: 1rem; color: #17a2b8; opacity: 0.8; transition: color 0.3s ease-in-out;"></i> {{-- Ikon Users, warna info --}}
                                    <h5 class="card-title" style="font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem; color: #333; transition: color 0.3s ease-in-out;">Users</h5> {{-- Judul Users --}}
                                    <p class="card-text text-muted" style="font-size: 0.875rem; color: #777; transition: color 0.3s ease-in-out;">Manage business members.</p> {{-- Deskripsi Manage members --}}
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="row mt-3"> {{-- **Diubah menjadi mt-5 di sini** pada row yang berisi card Banners --}}
                    <div class="col-md-4">
                        <a href="{{ route('banners.index', $business->business_unique_id) }}" class="text-decoration-none" style="text-decoration: none; display: block; transition: transform 0.3s ease-in-out;">
                            <div class="card border-0 h-100" style="border: none; border-radius: 0.75rem; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); transition: box-shadow 0.3s ease-in-out;">
                                <div class="card-body d-flex flex-column justify-content-center text-center" style="padding: 1.5rem; display: flex; flex-direction: column; justify-content: center; text-align: center; transition: background-color 0.3s ease-in-out;">
                                    <i class="fas fa-images fa-2x text-warning mb-3" style="font-size: 2.5rem; margin-bottom: 1rem; color: #ffc107; opacity: 0.8; transition: color 0.3s ease-in-out;"></i> {{-- Ikon Banners, warna warning --}}
                                    <h5 class="card-title" style="font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem; color: #333; transition: color 0.3s ease-in-out;">Banners</h5> {{-- Judul Banners --}}
                                    <p class="card-text text-muted" style="font-size: 0.875rem; color: #777; transition: color 0.3s ease-in-out;">Manage your banners display.</p> {{-- Deskripsi Manage banners --}}
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert for Error and Success Messages -->
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Business Details Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fw-bold" style="color: #14274e;">{{ $business->name }}</h1>
            <span class="badge bg-secondary" style="font-size: 1rem;">
                Business ID: {{ $business->business_unique_id }}
            </span>
        </div>

        <!-- Add Lecturer and Team Buttons -->
        <div class="d-flex justify-content-between mb-4">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLecturerModal">
                + Lecturer
            </button>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTeamModal">
                + Team
            </button>
        </div>

        <!-- Lecturers List Section -->
        <div class="card shadow-sm mb-4">
            <div class="card-header text-white" style="background-color: #14274e;">
                <h5 class="mb-0">Current Lecturers</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    @forelse($lecturerList as $lecturer)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <img src="{{ $lecturer->avatar ?? asset('images/default-avatar.png') }}"
                                     alt="{{ $lecturer->name }}"
                                     class="rounded-circle me-3"
                                     style="width: 40px; height: 40px; object-fit: cover;">
                                <div>
                                    <strong>{{ $lecturer->name }}</strong>
                                    <span class="text-muted">({{ $lecturer->email }})</span>
                                </div>
                            </div>
                            <form action="{{ route('owner.businesses.removeStaff', [$business->business_unique_id, $lecturer->id]) }}"
                                  method="POST"
                                  onsubmit="return confirm('Are you sure you want to remove this lecturer?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                            </form>
                        </li>
                    @empty
                        <li class="list-group-item text-center text-muted">No lecturers assigned yet.</li>
                    @endforelse
                </ul>

                <div class="mt-3">
                    {{ $lecturerList->links('pagination::bootstrap-4') }} {{-- Ditambahkan 'pagination::bootstrap-4' di sini --}}
                </div>
            </div>
        </div>

        <!-- Teams List Section -->
        <div class="card shadow-sm mb-5">
            <div class="card-header text-white" style="background-color: #14274e;">
                <h5 class="mb-0"><i class="fas fa-users me-2"></i>Team Management</h5>
            </div>
            <div class="card-body p-0">
                @if($teams->isEmpty())
                    <div class="text-center p-4 bg-light">
                        <i class="fas fa-user-friends fa-2x text-muted mb-3"></i>
                        <p class="text-muted mb-0">No teams created yet</p>
                    </div>
                @else
                    <div class="accordion accordion-flush" id="teamsAccordion">
                        @foreach($teams as $team)
                            <div class="accordion-item border-bottom">
                                <h2 class="accordion-header" id="heading-{{ $team->id }}">
                                    <button class="accordion-button collapsed py-3" type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#collapse-{{ $team->id }}"
                                            aria-expanded="false"
                                            aria-controls="collapse-{{ $team->id }}">
                                        <div class="d-flex flex-column">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-users text-secondary me-2 fs-6"></i>
                                                <span class="fw-bold me-2">{{ $team->name }}</span>
                                            </div>
                                            <small class="text-muted mt-1">Team ID: {{ $team->team_unique_id }}</small>
                                        </div>
                                    </button>
                                </h2>

                                <div id="collapse-{{ $team->id }}"
                                     class="accordion-collapse collapse"
                                     aria-labelledby="heading-{{ $team->id }}"
                                     data-bs-parent="#teamsAccordion">
                                    <div class="accordion-body pt-3">
                                        <!-- Team Leader Section -->
                                        <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <i class="fas fa-crown text-warning"></i>
                                                </div>
                                                <div>
                                                    <p class="mb-0 small text-muted">Team Leader</p>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $team->leader->avatar ?? asset('images/default-avatar.png') }}"
                                                             class="rounded-circle me-2"
                                                             style="width: 28px; height: 28px; object-fit: cover;">
                                                        <span class="fw-medium">{{ $team->leader ? $team->leader->name : 'No Leader' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <form action="{{ route('owner.businesses.deleteTeam', [$business->business_unique_id, $team->id]) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Are you sure you want to delete this team?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash-alt me-1"></i>Delete Team
                                                </button>
                                            </form>
                                        </div>

                                        <!-- Members Section -->
                                        <div class="mb-4">
                                            <h6 class="mb-3 d-flex align-items-center">
                                                <i class="fas fa-user-friends text-muted me-2 fs-6"></i>
                                                Members
                                            </h6>
                                            <div class="ps-3">
                                                @forelse($team->members as $member)
                                                    <div class="d-flex align-items-center mb-2">
                                                        <img src="{{ $member->avatar ?? asset('images/default-avatar.png') }}"
                                                             class="rounded-circle me-2"
                                                             style="width: 32px; height: 32px; object-fit: cover;">
                                                        <div>
                                                            <p class="mb-0">{{ $member->name }}</p>
                                                            <small class="text-muted">{{ $member->role }}</small>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <div class="text-center py-2 bg-light rounded">
                                                        <small class="text-muted fst-italic">No members in this team</small>
                                                    </div>
                                                @endforelse
                                            </div>
                                        </div>

                                        <!-- Managers Section -->
                                        <div class="mb-4">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="mb-0 d-flex align-items-center">
                                                    <i class="fas fa-user-shield text-muted me-2 fs-6"></i>
                                                    Managers
                                                </h6>
                                                <button type="button" class="btn btn-sm btn-success"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#addManagerModal-{{ $team->id }}">
                                                    <i class="fas fa-plus-circle me-1"></i>Add Manager
                                                </button>
                                            </div>

                                            <div class="ps-3">
                                                @forelse($team->managers as $manager)
                                                    <div class="d-flex align-items-center justify-content-between mb-2 p-2 bg-light rounded">
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ $manager->avatar ?? asset('images/default-avatar.png') }}"
                                                                 class="rounded-circle me-2"
                                                                 style="width: 32px; height: 32px; object-fit: cover;">
                                                            <div>
                                                                <p class="mb-0">{{ $manager->name }}</p>
                                                                <small class="text-muted">{{ $manager->type }}</small>
                                                            </div>
                                                        </div>
                                                        <form action="{{ route('owner.businesses.removeManager', [
                                            'business_unique_id' => $business->business_unique_id,
                                            'team_id' => $team->id,
                                            'user_id' => $manager->id
                                        ]) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                @empty
                                                    <div class="text-center py-2 bg-light rounded">
                                                        <small class="text-muted fst-italic">No managers assigned</small>
                                                    </div>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Add Manager Modal -->
                            <div class="modal fade" id="addManagerModal-{{ $team->id }}" tabindex="-1"
                                 aria-labelledby="addManagerModalLabel-{{ $team->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header" style="background-color: #14274e; color: white;">
                                            <h5 class="modal-title">
                                                <i class="fas fa-user-plus me-2"></i>Add Manager
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('owner.businesses.addManager', [$business->business_unique_id, $team->id]) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Select Lecturer</label>
                                                    <select name="user_id" class="form-select" required>
                                                        <option value="" disabled selected>Choose a lecturer...</option>
                                                        @foreach($lecturerListAvailable as $lecturer)
                                                            <option value="{{ $lecturer->id }}">
                                                                {{ $lecturer->name }} (Lecturer)
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-save me-1"></i>Save Changes
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $teams->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            </div>
        </div>


        <!-- Attendance Locations List Section with Add Location and Attendance Index Buttons -->
        <div class="card shadow-sm mb-4">
            <div class="card-header text-white d-flex justify-content-between align-items-center py-3"
                 style="background-color: #14274e;">
                <div class="d-flex align-items-center">
                    <i class="fas fa-map-marker-alt me-2"></i>
                    <h5 class="mb-0">Attendance Locations Management</h5>
                </div>
                <div>
                    <a href="{{ route('owner.attendance_locations.index', $business->business_unique_id) }}"
                       class="btn btn-sm btn-outline-light me-2">
                        <i class="fas fa-list-ol me-1"></i>Manage Locations
                    </a>
                    <a href="{{ route('owner.attendance_locations.create', $business->business_unique_id) }}"
                       class="btn btn-sm btn-light">
                        <i class="fas fa-plus-circle me-1"></i>New Location
                    </a>
                </div>
            </div>

            <div class="card-body p-0">
                @if($business->attendanceLocations->isEmpty())
                    <div class="text-center p-4 bg-light">
                        <i class="fas fa-map-marked-alt fa-2x text-muted mb-3"></i>
                        <p class="text-muted mb-0">No attendance locations configured</p>
                    </div>
                @else
                    <div class="list-group list-group-flush">
                        @foreach($business->attendanceLocations as $location)
                            <div class="list-group-item p-3">
                                <!-- Header Section -->
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="fas fa-location-dot text-primary me-2"></i>
                                            <h6 class="mb-0 fw-bold">{{ $location->name }}</h6>
                                        </div>
                                        <small class="text-muted">
                                            <i class="fas fa-link me-1"></i>Slug: {{ $location->slug }}
                                        </small>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="{{ $location->scanner_url }}"
                                           class="btn btn-sm btn-outline-primary"
                                           data-bs-toggle="tooltip"
                                           title="Open QR Scanner">
                                            <i class="fas fa-qrcode"></i>
                                        </a>
                                        <form method="POST"
                                              action="{{ route('owner.attendance_locations.destroy', [$business->business_unique_id, $location->id]) }}"
                                              onsubmit="return confirm('Are you sure you want to delete this location?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    data-bs-toggle="tooltip"
                                                    title="Delete Location">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Unique ID Section -->
                                <div class="bg-light rounded p-2">
                                    <label class="form-label small text-muted mb-1">Unique Identifier</label>
                                    <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-fingerprint text-muted"></i>
                                </span>
                                        <input type="text"
                                               class="form-control border-start-0"
                                               value="{{ $location->unique_id }}"
                                               id="uniqueId-{{ $location->id }}"
                                               readonly>
                                        <button class="btn btn-outline-secondary"
                                                type="button"
                                                onclick="copyToClipboard('uniqueId-{{ $location->id }}')"
                                                data-bs-toggle="tooltip"
                                                title="Copy to Clipboard">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Copy Success Alert -->
            <div class="alert alert-success alert-dismissible fade show m-3 d-none"
                 id="copyAlert"
                 role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <strong>Success!</strong> Unique ID copied to clipboard
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>

        <!-- Modals for Adding Lecturer and Team -->

        <!-- Modal for Adding New Lecturer -->
        <div class="modal fade" id="addLecturerModal" tabindex="-1"
             aria-labelledby="addLecturerModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('owner.businesses.addStaff', $business->business_unique_id) }}"
                          method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="addLecturerModalLabel">Add New Lecturer</h5>
                            <button type="button" class="btn-close"
                                    data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="lecturer-search" class="form-label">
                                    Search Lecturer
                                </label>
                                <input type="text"
                                       id="lecturer-search"
                                       class="form-control"
                                       placeholder="Enter lecturer name or email"
                                       onkeyup="searchLecturer()">
                                <ul id="lecturer-results" class="list-group mt-2"></ul>
                            </div>
                        </div>
                        <input type="hidden" name="staff_id" id="selected-lecturer-id">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">
                                Close
                            </button>
                            <button type="submit" class="btn btn-primary">
                                Add Lecturer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal for Adding New Team -->
        <div class="modal fade" id="addTeamModal" tabindex="-1"
             aria-labelledby="addTeamModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('owner.businesses.addTeam', $business->business_unique_id) }}"
                          method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="addTeamModalLabel">Add New Team</h5>
                            <button type="button" class="btn-close"
                                    data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="team-name" class="form-label">Team Name</label>
                                <input type="text" name="name" id="team-name"
                                       class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="leader" class="form-label">
                                    Select Leader (Optional)
                                </label>
                                <select name="leader_id" id="leader" class="form-select">
                                    <option value="" selected>No Leader</option>
                                    @foreach($business->lecturers as $lecturer)
                                        <option value="{{ $lecturer->id }}">
                                            {{ $lecturer->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">
                                Close
                            </button>
                            <button type="submit" class="btn btn-primary">
                                Add Team
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        function searchLecturer() {
            let query = document.getElementById('lecturer-search').value;
            let resultsContainer = document.getElementById('lecturer-results');

            if (query.length < 2) {
                resultsContainer.innerHTML = ''; // Clear results if query is too short
                return;
            }

            fetch(`/owner/businesses/go/search-lecturers?query=${query}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    resultsContainer.innerHTML = '';
                    data.forEach(lecturer => {
                        let listItem = document.createElement('li');
                        listItem.classList.add('list-group-item', 'list-group-item-action', 'd-flex', 'align-items-center');
                        listItem.style.cursor = 'pointer';

                        // Gunakan avatar atau default image
                        let avatar = lecturer.avatar ? lecturer.avatar : '/images/default-avatar.png';
                        listItem.innerHTML = `
                            <img src="${avatar}" alt="${lecturer.name}"
                                 class="rounded-circle me-2"
                                 style="width: 35px; height: 35px; object-fit: cover;">
                            <span>${lecturer.name}</span>
                        `;

                        listItem.onclick = () => selectLecturer(lecturer.id, lecturer.name);
                        resultsContainer.appendChild(listItem);
                    });
                })
                .catch(error => {
                    console.error('Error fetching lecturer data:', error);
                });
        }

        function selectLecturer(lecturerId, lecturerName) {
            document.getElementById('selected-lecturer-id').value = lecturerId;
            document.getElementById('lecturer-search').value = lecturerName;
            document.getElementById('lecturer-results').innerHTML = '';
        }
    </script>

    <script>
        function copyToClipboard(elementId) {
            const inputElement = document.getElementById(elementId);

            // Pilih teks di dalam input
            inputElement.select();
            inputElement.setSelectionRange(0, 99999); // Untuk perangkat mobile

            // Eksekusi perintah salin
            document.execCommand("copy");

            // Tampilkan alert Bootstrap
            const copyAlert = document.getElementById('copyAlert');
            copyAlert.classList.remove('d-none');

            // Sembunyikan alert setelah 3 detik
            setTimeout(() => {
                copyAlert.classList.add('d-none');
            }, 3000);
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mainCard = document.querySelector('.card.mb-4.shadow-sm');
            const headerCard = mainCard.querySelector('.card-header');
            // Perbarui selector untuk cardLinks dan innerCards untuk mengakomodasi col-md-4
            const cardLinks = mainCard.querySelectorAll('.col-md-4 > a'); // Ubah selector ke .col-md-4 > a
            const innerCards = mainCard.querySelectorAll('.col-md-4 > a > .card'); // Ubah selector ke .col-md-4 > a > .card
            const cardBodies = mainCard.querySelectorAll('.col-md-4 > a > .card > .card-body'); // Ubah selector ke .col-md-4 > a > .card > .card-body
            const cardIcons = mainCard.querySelectorAll('.col-md-4 > a > .card > .card-body > i'); // Ubah selector ke .col-md-4 > a > .card > .card-body > i
            const cardTitles = mainCard.querySelectorAll('.col-md-4 > a > .card > .card-body > h5'); // Ubah selector ke .col-md-4 > a > .card > .card-body > h5
            const cardTexts = mainCard.querySelectorAll('.col-md-4 > a > .card > .card-body > p'); // Ubah selector ke .col-md-4 > a > .card > .card-body > p

            mainCard.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
                this.style.boxShadow = '0 10px 20px rgba(0, 0, 0, 0.15)';
            });

            mainCard.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.05)';
            });

            headerCard.addEventListener('mouseenter', function() {
                this.querySelector('h4').style.color = '#fff'; // white color on hover
            });

            headerCard.addEventListener('mouseleave', function() {
                this.querySelector('h4').style.color = '#f8f9fa'; // back to original color
            });


            cardLinks.forEach(link => {
                link.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.03)';
                });

                link.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                });
            });


            innerCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.boxShadow = '0 8px 15px rgba(0, 0, 0, 0.2)';
                });
                card.addEventListener('mouseleave', function() {
                    this.style.boxShadow = '0 2px 5px rgba(0, 0, 0, 0.1)';
                });
            });

            cardBodies.forEach(body => {
                body.addEventListener('mouseenter', function() {
                    this.style.backgroundColor = '#f8f9fa'; // Lighten background on hover
                });
                body.addEventListener('mouseleave', function() {
                    this.style.backgroundColor = 'transparent'; // Back to original background
                });
            });


            cardIcons.forEach(icon => {
                icon.addEventListener('mouseenter', function() {
                    this.style.color = '#0056b3'; // Darken icon color on hover for primary icons - adjust as needed for secondary
                });
                icon.addEventListener('mouseleave', function() {
                    if(icon.classList.contains('text-primary')) {
                        icon.style.color = '#007bff'; // Back to original primary color
                    } else if (icon.classList.contains('text-secondary')) {
                        icon.style.color = '#6c757d'; // Back to original secondary color
                    } else if (icon.classList.contains('text-info')) { // Tambahkan kondisi untuk text-info icons (Users card)
                        icon.style.color = '#00869c'; // Darken text-info color on hover - sesuaikan warna yang diinginkan
                    }
                });
            });


            cardTitles.forEach(title => {
                title.addEventListener('mouseenter', function() {
                    this.style.color = '#0056b3'; // Slightly darken title color on hover
                });
                title.addEventListener('mouseleave', function() {
                    this.style.color = '#333'; // Back to original title color
                });
            });


            cardTexts.forEach(text => {
                text.addEventListener('mouseenter', function() {
                    this.style.color = '#555'; // Slightly darken text color on hover
                });
                text.addEventListener('mouseleave', function() {
                    this.style.color = '#777'; // Back to original text color
                });
            });
        });
    </script>
@endsection



