@extends('layout.owner')

@section('title', 'Manage Attendance Locations')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

@section('content')
    <div class="container mt-4">
        <!-- Header Section -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('owner.businesses.manage', $business->business_unique_id) }}"
                   class="btn btn-outline-secondary btn-lg rounded-circle"
                   data-bs-toggle="tooltip"
                   title="Back to Business Management">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="h3 fw-bold mb-1 text-navy">Attendance Locations</h1>
                    <p class="text-muted mb-0">Managing locations for {{ $business->name }}</p>
                </div>
            </div>
            <a href="{{ route('owner.attendance_locations.create', $business->business_unique_id) }}"
               class="btn btn-primary btn-lg px-4">
                <i class="fas fa-plus-circle me-2"></i>New Location
            </a>
        </div>

        <!-- Success Alert -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Locations Card -->
        <div class="card border-0 shadow-lg">
            <div class="card-header bg-navy text-white py-3">
                <div class="d-flex align-items-center">
                    <i class="fas fa-map-marked-alt me-3 fs-5"></i>
                    <h2 class="h5 mb-0">Registered Locations</h2>
                </div>
            </div>

            <div class="card-body p-0">
                @forelse($locations as $location)
                    <div class="list-group list-group-flush">
                        <!-- Location Item -->
                        <div class="list-group-item p-4">
                            <div class="row align-items-center">
                                <!-- Location Info -->
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-square bg-light-primary text-navy rounded-circle me-3">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                        <div>
                                            <h3 class="h6 fw-bold mb-0">{{ $location->name }}</h3>
                                            <small class="text-muted">Slug: {{ $location->slug }}</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Location Metadata -->
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <div class="d-flex flex-wrap gap-2">
                                <span class="badge bg-light text-dark border">
                                    <i class="fas fa-hashtag me-1"></i>{{ $location->unique_id }}
                                </span>
                                        @if($location->latitude && $location->longitude)
                                            <span class="badge bg-light text-dark border">
                                    <i class="fas fa-map-pin me-1"></i>Geo-tagged
                                </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="col-md-4 text-md-end">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <!-- View Button -->
                                        <button class="btn btn-sm btn-outline-primary rounded-pill"
                                                data-bs-toggle="modal"
                                                data-bs-target="#viewLocationModal-{{ $location->id }}">
                                            <i class="fas fa-eye me-1"></i>Details
                                        </button>

                                        <!-- Action Dropdown -->
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary rounded-pill"
                                                    type="button"
                                                    data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-h"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="{{ $location->scanner_url }}">
                                                        <i class="fas fa-qrcode me-2"></i>Scan QR
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item"
                                                       href="{{ route('owner.attendance_locations.edit', [$business->business_unique_id, $location->id]) }}">
                                                        <i class="fas fa-edit me-2"></i>Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li>
                                                    <button class="dropdown-item text-danger"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#deleteLocationModal-{{ $location->id }}">
                                                        <i class="fas fa-trash-alt me-2"></i>Delete
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center p-5 bg-light">
                        <i class="fas fa-map-marked-alt fa-3x text-muted mb-4"></i>
                        <h4 class="h5 text-muted mb-3">No Attendance Locations Found</h4>
                        <p class="text-muted">Start by adding your first location</p>
                        <a href="{{ route('owner.attendance_locations.create', $business->business_unique_id) }}"
                           class="btn btn-primary px-4">
                            <i class="fas fa-plus-circle me-2"></i>Create Location
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($locations->hasPages())
                <div class="card-footer bg-transparent py-3">
                    {{ $locations->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>

    <!-- View Location Modal -->
    @foreach($locations as $location)
        <div class="modal fade" id="viewLocationModal-{{ $location->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header bg-navy text-white">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle me-2"></i>
                            <h5 class="modal-title mb-0">{{ $location->name }} Details</h5>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body">
                        <div class="row g-4">
                            <!-- Location Details -->
                            <div class="col-lg-6">
                                <div class="row g-3">
                                    <!-- Name -->
                                    <div class="col-12">
                                        <label class="form-label text-muted small">Location Name</label>
                                        <p class="fw-bold mb-0">{{ $location->name }}</p>
                                    </div>

                                    <!-- Unique ID -->
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small">Unique ID</label>
                                        <div class="input-group">
                                            <input type="text"
                                                   class="form-control"
                                                   value="{{ $location->unique_id }}"
                                                   readonly>
                                            <button class="btn btn-outline-secondary"
                                                    onclick="copyToClipboard('{{ $location->unique_id }}')">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Slug -->
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small">Slug</label>
                                        <p class="fw-bold mb-0">{{ $location->slug }}</p>
                                    </div>

                                    <!-- Coordinates -->
                                    <div class="col-12">
                                        <div class="card border-0 shadow-sm">
                                            <div class="card-body">
                                                <h6 class="mb-3 fw-bold">
                                                    <i class="fas fa-map-marker-alt me-2"></i>Coordinates
                                                </h6>
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <label class="form-label text-muted small">Latitude</label>
                                                        <p class="fw-bold mb-0">{{ $location->latitude ?? 'N/A' }}</p>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label text-muted small">Longitude</label>
                                                        <p class="fw-bold mb-0">{{ $location->longitude ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Description -->
                                    <div class="col-12">
                                        <label class="form-label text-muted small">Description</label>
                                        <div class="card border-0 bg-light">
                                            <div class="card-body">
                                                {{ $location->description ?? 'No description provided' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Map Section -->
                            <div class="col-lg-6">
                                <div class="card border-0 h-100 shadow-sm">
                                    <div class="card-body p-2">
                                        <div id="map-{{ $location->id }}"
                                             class="leaflet-map rounded-lg"
                                             style="height: 400px; background-color: #f8f9fa;">
                                            <!-- Fallback text -->
                                            @if(!$location->latitude || !$location->longitude)
                                                <div class="d-flex h-100 align-items-center justify-content-center text-muted">
                                                    <i class="fas fa-map-marked-alt fa-2x me-2"></i>
                                                    <span>No coordinates available</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Delete Confirmation Modal -->
    @foreach($locations as $location)
        <div class="modal fade" id="deleteLocationModal-{{ $location->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <h5 class="modal-title mb-0">Confirm Deletion</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center mb-4">
                            <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                            <h5 class="fw-bold mb-2">Delete {{ $location->name }}?</h5>
                            <p class="text-muted">This action cannot be undone. All associated data will be permanently removed.</p>
                        </div>
                        <form action="{{ route('owner.attendance_locations.destroy', [$business->business_unique_id, $location->id]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <div class="d-flex justify-content-center gap-3">
                                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger px-4">
                                    <i class="fas fa-trash-alt me-1"></i>Confirm Delete
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Scripts -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @foreach($locations as $location)
            const viewModal{{ $location->id }} = document.getElementById('viewLocationModal-{{ $location->id }}');
            if(viewModal{{ $location->id }}) {
                viewModal{{ $location->id }}.addEventListener('shown.bs.modal', function() {
                    @if($location->latitude && $location->longitude)
                    const mapElement = document.getElementById('map-{{ $location->id }}');
                    if(mapElement && !mapElement._map) {
                        const map = L.map(mapElement).setView([{{ $location->latitude }}, {{ $location->longitude }}], 18);

                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; OpenStreetMap contributors'
                        }).addTo(map);

                        L.marker([{{ $location->latitude }}, {{ $location->longitude }}])
                            .addTo(map)
                            .bindPopup(`<b>{{ $location->name }}</b>`)
                            .openPopup();

                        mapElement._map = map; // Store map reference
                    }
                    @endif
                });
            }
            @endforeach
        });

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                const alert = document.createElement('div');
                alert.className = 'alert alert-success position-fixed bottom-0 end-0 m-3';
                alert.style.zIndex = 9999;
                alert.innerHTML = `
            <i class="fas fa-check-circle me-2"></i>
            Copied to clipboard!
        `;
                document.body.appendChild(alert);

                setTimeout(() => {
                    alert.remove();
                }, 2000);
            });
        }
    </script>

    <style>
        .text-navy { color: #153e75; }
        .bg-navy { background-color: #153e75; }
        .border-navy { border-color: #153e75; }

        .icon-square {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .leaflet-map {
            min-height: 300px;
            border-radius: 0.5rem;
            transition: transform 0.2s;
        }

        .leaflet-map:hover {
            transform: translateY(-2px);
        }

        .list-group-item {
            transition: background-color 0.2s;
        }
    </style>
@endsection