@extends('layout.owner')

@section('title', 'My Businesses')

@section('content')
    <style>
        :root {
            --primary-navy: #14274e;
            --accent-gold: #FFD700;
            --hover-gold: #cca300;
        }

        /* Card Business */
        .business-card {
            border: none;
            border-left: 4px solid var(--accent-gold);
            margin-bottom: 1rem;
        }

        /* Mobile Header */
        .mobile-header {
            background: var(--primary-navy);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            z-index: 1050;
        }

        /* Floating Action Button */
        .fab-button {
            background: var(--accent-gold) !important;
            border: none;
            width: 56px;
            height: 56px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
    </style>

    <div class="container-fluid p-0">
        <!-- Mobile Header -->
        <div class="mobile-header fixed-top p-3 d-lg-none">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-white">My Businesses</h5>
                <button class="btn btn-sm text-white" data-bs-toggle="modal" data-bs-target="#addBusinessModal">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>

        <div class="container pt-5 pt-lg-4">
            <!-- Desktop Header -->
            <div class="d-none d-lg-flex justify-content-between align-items-center mb-4">
                <h1 class="fw-bold" style="color: var(--primary-navy);">My Businesses</h1>
                <button type="button" class="btn shadow-sm"
                        style="background: var(--primary-navy); color: white;"
                        data-bs-toggle="modal" data-bs-target="#addBusinessModal">
                    <i class="fas fa-plus me-2"></i> Add New Business
                </button>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mt-3 mt-lg-0" role="alert">
                    <strong>Success!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Business List -->
            <div class="row g-3">
                @foreach($businesses as $business)
                    <div class="col-12">
                        <div class="card business-card shadow-sm">
                            <div class="card-body">
                                <div class="mb-3">
                                    <h6 class="fw-bold" style="color: var(--primary-navy);">
                                        {{ $business->name }}
                                    </h6>
                                    <small class="text-muted">ID: {{ $business->business_unique_id }}</small>
                                </div>
                                <!-- Tombol Aksi (langsung terlihat tanpa dropdown) -->
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="button" class="btn btn-outline-primary btn-sm"
                                            onclick='openViewModal(@json($business))'>
                                        <i class="fas fa-eye me-1"></i>View
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm"
                                            onclick='openEditModal(@json($business))'>
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-sm"
                                            onclick='openDeleteModal(@json($business))'>
                                        <i class="fas fa-trash-alt me-1"></i>Delete
                                    </button>
                                    <a href="{{ route('owner.businesses.manage', $business->business_unique_id) }}"
                                       class="btn btn-outline-warning btn-sm">
                                        <i class="fas fa-cogs me-1"></i>Manage
                                    </a>
                                </div>
                            </div>

                            <!-- Informasi Kontak (khusus mobile) -->
                            <div class="card-footer d-lg-none">
                                <div class="d-flex justify-content-between small">
                                    <span class="text-muted">Contact:</span>
                                    <span style="color: var(--primary-navy);">
                                        {{ $business->contact_phone ?? '-' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Mobile FAB -->
        <div class="d-lg-none fixed-bottom pe-3 pb-3" style="z-index: 1030;">
            <button class="btn fab-button rounded-circle text-white"
                    data-bs-toggle="modal" data-bs-target="#addBusinessModal">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>

    <!-- Add Business Modal -->
    <div class="modal fade" id="addBusinessModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px;">
                <div class="modal-header" style="background: var(--primary-navy);">
                    <h6 class="modal-title text-white">New Business</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('owner.businesses.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small text-muted">Business Name</label>
                            <input type="text" class="form-control form-control-lg" name="name" required
                                   placeholder="e.g. My Coffee Shop">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-muted">Contact Phone</label>
                            <input type="tel" class="form-control form-control-lg" name="contact_phone"
                                   placeholder="+62 812 3456 7890">
                        </div>
                        <button type="submit" class="btn w-100 btn-lg"
                                style="background: var(--accent-gold);">Save Business</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- View Business Modal -->
    <div class="modal fade" id="viewBusinessModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--primary-navy);">
                    <h6 class="modal-title text-white">Business Details</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="list-group">
                        <div class="list-group-item border-0">
                            <small class="text-muted">Business Name</small>
                            <div class="fw-bold" id="viewBusinessName"></div>
                        </div>
                        <div class="list-group-item border-0">
                            <small class="text-muted">Business ID</small>
                            <div class="fw-bold" id="viewBusinessId"></div>
                        </div>
                        <div class="list-group-item border-0">
                            <small class="text-muted">Contact Phone</small>
                            <div class="fw-bold" id="viewContactPhone"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Business Modal -->
    <div class="modal fade" id="editBusinessModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px;">
                <div class="modal-header" style="background: var(--primary-navy);">
                    <h6 class="modal-title text-white">Edit Business</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Form Edit -->
                    <form id="editBusinessForm" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label class="form-label small text-muted">Business Name</label>
                            <input type="text" class="form-control form-control-lg" name="name" id="editBusinessName" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-muted">Contact Phone</label>
                            <input type="tel" class="form-control form-control-lg" name="contact_phone" id="editContactPhone">
                        </div>
                        <button type="submit" class="btn w-100 btn-lg"
                                style="background: var(--accent-gold);">Update Business</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Business Modal -->
    <div class="modal fade" id="deleteBusinessModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px;">
                <div class="modal-header" style="background: var(--primary-navy);">
                    <h6 class="modal-title text-white">Delete Business</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this business?</p>
                    <form id="deleteBusinessForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">Yes, Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Tampilkan modal "View Business" dengan data yang dikirim dari backend
        function openViewModal(business) {
            console.log(business); // Debug
            document.getElementById('viewBusinessName').textContent = business.name;
            document.getElementById('viewBusinessId').textContent = business.business_unique_id;
            document.getElementById('viewContactPhone').textContent = business.contact_phone || '-';
            new bootstrap.Modal(document.getElementById('viewBusinessModal')).show();
        }

        // Tampilkan modal "Edit Business" dan set form action secara dinamis
        function openEditModal(business) {
            console.log(business); // Debug
            const form = document.getElementById('editBusinessForm');
            form.action = '/owner/businesses/' + business.business_unique_id;
            document.getElementById('editBusinessName').value = business.name;
            document.getElementById('editContactPhone').value = business.contact_phone || '';
            new bootstrap.Modal(document.getElementById('editBusinessModal')).show();
        }

        // Tampilkan modal "Delete Business" dan set form action secara dinamis
        function openDeleteModal(business) {
            const form = document.getElementById('deleteBusinessForm');
            form.action = '/owner/businesses/' + business.business_unique_id;
            new bootstrap.Modal(document.getElementById('deleteBusinessModal')).show();
        }

        // Penyesuaian modal agar fullscreen pada perangkat mobile
        function adjustModalForMobile() {
            document.querySelectorAll('.modal-dialog').forEach(modal => {
                if (window.innerWidth <= 768) {
                    modal.classList.add('modal-fullscreen');
                } else {
                    modal.classList.remove('modal-fullscreen');
                }
            });
        }

        window.addEventListener('resize', adjustModalForMobile);
        document.addEventListener('DOMContentLoaded', adjustModalForMobile);
    </script>
@endsection