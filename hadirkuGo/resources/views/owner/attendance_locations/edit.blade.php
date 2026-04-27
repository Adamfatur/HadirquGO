@extends('layout.owner')

@section('title', 'Edit Attendance Location')

@section('content')
    <div class="container mt-4">
        <!-- Header Section -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
            <div>
                <h1 class="h3 fw-bold text-navy mb-2">
                    <i class="fas fa-map-marker-alt me-2"></i>Edit Location
                </h1>
                <div class="d-flex align-items-center gap-2">
                <span class="badge bg-light-primary text-navy rounded-pill">
                    <i class="fas fa-building me-1"></i>{{ $business->name }}
                </span>
                    <span class="badge bg-light-secondary text-muted rounded-pill">
                    ID: {{ $business->business_unique_id }}
                </span>
                </div>
            </div>
            <a href="{{ route('owner.attendance_locations.index', $business->business_unique_id) }}"
               class="btn btn-outline-secondary btn-lg rounded-circle"
               data-bs-toggle="tooltip"
               title="Back to Locations">
                <i class="fas fa-arrow-left"></i>
            </a>
        </div>

        <!-- Form Card -->
        <div class="card border-0 shadow-lg">
            <div class="card-header bg-navy text-white py-3">
                <h2 class="h5 mb-0">
                    <i class="fas fa-edit me-2"></i>Update Location Details
                </h2>
            </div>

            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger d-flex align-items-center">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                @endif

                <form action="{{ route('owner.attendance_locations.update', [$business->business_unique_id, $location->id]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Location Name -->
                    <div class="mb-4">
                        <label class="form-label fw-bold text-navy">
                            <i class="fas fa-signature me-2"></i>Location Name
                        </label>
                        <input type="text"
                               name="name"
                               class="form-control form-control-lg border-navy"
                               value="{{ old('name', $location->name) }}"
                               required>
                    </div>

                    <!-- Slug Input -->
                    <div class="mb-4">
                        <label class="form-label fw-bold text-navy">
                            <i class="fas fa-link me-2"></i>Location Slug
                        </label>
                        <div class="input-group">
                        <span class="input-group-text bg-light border-navy">
                            <i class="fas fa-hashtag text-navy"></i>
                        </span>
                            <input type="text"
                                   name="slug"
                                   class="form-control form-control-lg border-navy"
                                   value="{{ old('slug', $location->slug) }}"
                                   required>
                        </div>
                        <small class="text-muted mt-1 d-block">Unique identifier for URLs</small>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label class="form-label fw-bold text-navy">
                            <i class="fas fa-align-left me-2"></i>Description
                        </label>
                        <textarea name="description"
                                  class="form-control border-navy"
                                  rows="3">{{ old('description', $location->description) }}</textarea>
                    </div>

                    <!-- Map Section -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <label class="form-label fw-bold text-navy mb-0">
                                <i class="fas fa-map-marked-alt me-2"></i>Location Coordinates
                            </label>
                            <button type="button"
                                    class="btn btn-sm btn-outline-navy"
                                    onclick="locateUser()">
                                <i class="fas fa-location-crosshairs me-1"></i>Use My Location
                            </button>
                        </div>

                        <div id="map" class="leaflet-map rounded-lg shadow-sm"
                             style="height: 400px; background-color: #f8f9fa;">
                            <div class="d-flex h-100 align-items-center justify-content-center text-muted">
                                <i class="fas fa-spinner fa-spin me-2"></i>
                                <span>Loading map...</span>
                            </div>
                        </div>
                        <small class="text-muted mt-2 d-block">Drag marker to adjust coordinates</small>

                        <!-- Hidden Inputs for Coordinates -->
                        <input type="hidden"
                               name="latitude"
                               id="latitude"
                               value="{{ old('latitude', $location->latitude) }}">
                        <input type="hidden"
                               name="longitude"
                               id="longitude"
                               value="{{ old('longitude', $location->longitude) }}">
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex justify-content-end gap-3 mt-5">
                        <a href="{{ route('owner.attendance_locations.index', $business->business_unique_id) }}"
                           class="btn btn-lg btn-outline-secondary px-4">
                            Cancel
                        </a>
                        <button type="submit"
                                class="btn btn-lg btn-navy px-4">
                            <i class="fas fa-save me-2"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Initialize map with current location or default
            const initialLat = {{ $location->latitude ?? -6.200000 }};
            const initialLng = {{ $location->longitude ?? 106.816666 }};

            const map = L.map('map').setView([initialLat, initialLng], 15);

            L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            // Initialize marker
            const marker = L.marker([initialLat, initialLng], {
                draggable: true,
                icon: L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41]
                })
            }).addTo(map);

            // Update coordinates
            marker.on("dragend", (e) => {
                const { lat, lng } = e.target.getLatLng();
                document.getElementById('latitude').value = lat.toFixed(6);
                document.getElementById('longitude').value = lng.toFixed(6);
            });

            // Geolocation
            window.locateUser = () => {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition((pos) => {
                        const { latitude, longitude } = pos.coords;
                        map.setView([latitude, longitude], 17);
                        marker.setLatLng([latitude, longitude]);
                        document.getElementById('latitude').value = latitude.toFixed(6);
                        document.getElementById('longitude').value = longitude.toFixed(6);
                    }, showGeolocationError);
                }
            };
        });

        function showGeolocationError(error) {
            alert(`Geolocation error: ${error.message}`);
        }
    </script>

    <style>
        .text-navy { color: #14274e; }
        .bg-navy { background-color: #14274e; }
        .btn-navy { background-color: #14274e; color: white; }
        .btn-navy:hover { background-color: #0f1d3a; color: white; }
        .border-navy { border-color: #14274e !important; }

        .leaflet-map {
            transition: transform 0.2s, box-shadow 0.2s;
            border: 2px solid #14274e;
        }

        .leaflet-map:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .form-control:focus {
            border-color: #14274e;
            box-shadow: 0 0 0 0.25rem rgba(20, 39, 78, 0.25);
        }
    </style>
@endsection