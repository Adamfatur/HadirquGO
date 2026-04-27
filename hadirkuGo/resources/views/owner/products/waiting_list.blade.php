@extends('layout.owner')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="..." crossorigin="anonymous" referrerpolicy="no-referrer" />

    <div class="container mt-5 pt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-primary" style="font-size: 2rem; margin-bottom: 0;"><i class="fas fa-list-check me-2"></i> Manage Requests</h1>
            <a href="{{ route('owner.products.index') }}" class="btn btn-outline-secondary rounded-pill shadow-sm" style="padding: 0.5rem 1rem; font-size: 0.9rem; transition: background-color 0.3s ease-in-out;">
                <i class="fas fa-arrow-left-circle me-2"></i> Back to Products
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded shadow-sm mb-4" role="alert" style="border-radius: 0.5rem; box-shadow: 0 3px 7px rgba(0, 0, 0, 0.05); background-color: #e6f7ec; border-color: #c3e8cd; color: #2d4b3a;">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show rounded shadow-sm mb-4" role="alert" style="border-radius: 0.5rem; box-shadow: 0 3px 7px rgba(0, 0, 0, 0.05); background-color: #f8d7da; border-color: #f1caca; color: #842029;">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm mb-4 rounded" style="border-radius: 0.75rem; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.07); transition: box-shadow 0.3s ease-in-out;">
            <div class="row g-0">
                @if($product->image)
                    <div class="col-md-4">
                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded-start" style="height: 100%; object-fit: cover; border-radius: 0.75rem 0 0 0.75rem;">
                    </div>
                @endif
                <div class="col-md-8">
                    <div class="card-body" style="padding: 2rem;">
                        <h5 class="card-title text-primary" style="font-size: 1.5rem; font-weight: 500;">{{ $product->name }}</h5>
                        <p class="text-muted mb-2" style="font-size: 0.95rem;">{{ $product->description }}</p>
                        <p class="mb-2" style="font-size: 0.95rem;"><strong style="color: #555;">Stock:</strong> {{ $product->stock_quantity }}</p>
                        <p class="mb-0" style="font-size: 0.95rem;"><strong style="color: #555;">Points Required:</strong> {{ $product->points_required }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <button type="button" class="btn btn-primary rounded-pill shadow-sm me-2" data-bs-toggle="modal" data-bs-target="#approvedRequestsModal" style="padding: 0.5rem 1rem; font-size: 0.9rem; transition: background-color 0.3s ease-in-out;">
                    <i class="fas fa-check-circle me-2"></i> View Approved
                </button>
                <button type="button" class="btn btn-secondary rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#rejectedRequestsModal" style="padding: 0.5rem 1rem; font-size: 0.9rem; transition: background-color 0.3s ease-in-out;">
                    <i class="fas fa-xmark-circle me-2"></i> View Rejected
                </button>
            </div>
            <div>
                <button type="button" class="btn btn-success rounded-pill shadow-sm me-2" data-bs-toggle="modal" data-bs-target="#bulkApproveModal" style="padding: 0.5rem 1rem; font-size: 0.9rem; transition: background-color 0.3s ease-in-out;">
                    <i class="fas fa-check-circle me-2"></i> Approve Selected
                </button>
                <button type="button" class="btn btn-danger rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#bulkRejectModal" style="padding: 0.5rem 1rem; font-size: 0.9rem; transition: background-color 0.3s ease-in-out;">
                    <i class="fas fa-xmark-circle me-2"></i> Reject Selected
                </button>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded" style="border-radius: 0.75rem; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.07); transition: box-shadow 0.3s ease-in-out;">
            <div class="card-body" style="padding: 1.5rem;">
                <form id="bulkActionForm" method="POST">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-hover table-borderless align-middle table-sm mb-0" style="border-collapse: separate; border-spacing: 0 8px;">
                            <thead class="thead-light" style="background-color: #f8f9fa;">
                            <tr style="border-bottom: 1px solid #e0e0e0;">
                                <th class="text-center" style="padding: 0.75rem 0.5rem;">
                                    <input type="checkbox" id="selectAll">
                                </th>
                                <th style="padding: 0.75rem 0.5rem;">User</th>
                                <th style="padding: 0.75rem 0.5rem;">Requested At</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($waitingList as $request)
                                <tr style="background-color: white; border-radius: 0.5rem; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.03); transition: box-shadow 0.3s ease-in-out;">
                                    <td class="text-center" style="padding: 0.75rem 0.5rem;">
                                        <input type="checkbox" name="request_ids[]" value="{{ $request->id }}" class="select-item">
                                    </td>
                                    <td style="padding: 0.75rem 0.5rem;">
                                        <strong style="color: #555;">{{ $request->user->name }}</strong>
                                    </td>
                                    <td style="padding: 0.75rem 0.5rem;">
                                        {{ $request->requested_at->format('d M Y, H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">No requests found.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>


        <div class="d-flex justify-content-center mt-4">
            {{ $waitingList->links('pagination::bootstrap-4') }}
        </div>

        <div class="modal fade" id="approvedRequestsModal" tabindex="-1" aria-labelledby="approvedRequestsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content rounded shadow" style="border-radius: 0.75rem; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);">
                    <div class="modal-header bg-success text-white rounded-top" style="background-color: #28a745 !important; border-radius: 0.75rem 0.75rem 0 0; padding: 1.25rem;">
                        <h5 class="modal-title" id="approvedRequestsModalLabel" style="font-size: 1.25rem;">Approved Requests</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="padding: 1.5rem;">
                        @if($approvedList->isEmpty())
                            <p class="text-center text-muted">No approved requests.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover table-borderless align-middle table-sm mb-0" style="border-collapse: separate; border-spacing: 0 8px;">
                                    <thead class="thead-light" style="background-color: #f8f9fa;">
                                    <tr style="border-bottom: 1px solid #e0e0e0;">
                                        <th style="padding: 0.75rem 0.5rem;">User</th>
                                        <th style="padding: 0.75rem 0.5rem;">Approved At</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($approvedList as $approved)
                                        <tr style="background-color: white; border-radius: 0.5rem; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.03); transition: box-shadow 0.3s ease-in-out;">
                                            <td style="padding: 0.75rem 0.5rem;">{{ $approved->user->name }}</td>
                                            <td style="padding: 0.75rem 0.5rem;">{{ $approved->updated_at->format('d M Y, H:i') }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center mt-3">
                                {{ $approvedList->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="rejectedRequestsModal" tabindex="-1" aria-labelledby="rejectedRequestsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content rounded shadow" style="border-radius: 0.75rem; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);">
                    <div class="modal-header bg-danger text-white rounded-top" style="background-color: #dc3545 !important; border-radius: 0.75rem 0.75rem 0 0; padding: 1.25rem;">
                        <h5 class="modal-title" id="rejectedRequestsModalLabel" style="font-size: 1.25rem;">Rejected Requests</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="padding: 1.5rem;">
                        @if($rejectedList->isEmpty())
                            <p class="text-center text-muted">No rejected requests.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover table-borderless align-middle table-sm mb-0" style="border-collapse: separate; border-spacing: 0 8px;">
                                    <thead class="thead-light" style="background-color: #f8f9fa;">
                                    <tr style="border-bottom: 1px solid #e0e0e0;">
                                        <th style="padding: 0.75rem 0.5rem;">User</th>
                                        <th style="padding: 0.75rem 0.5rem;">Rejected At</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($rejectedList as $rejected)
                                        <tr style="background-color: white; border-radius: 0.5rem; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.03); transition: box-shadow 0.3s ease-in-out;">
                                            <td style="padding: 0.75rem 0.5rem;">{{ $rejected->user->name }}</td>
                                            <td style="padding: 0.75rem 0.5rem;">{{ $rejected->updated_at->format('d M Y, H:i') }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center mt-3">
                                {{ $rejectedList->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="bulkApproveModal" tabindex="-1" aria-labelledby="bulkApproveModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded shadow" style="border-radius: 0.75rem; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);">
                    <div class="modal-header bg-success text-white rounded-top" style="background-color: #28a745 !important; border-radius: 0.75rem 0.75rem 0 0; padding: 1.25rem;">
                        <h5 class="modal-title" id="bulkApproveModalLabel" style="font-size: 1.25rem;">Approve Selected Requests</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center" style="padding: 1.5rem;">
                        <i class="fas fa-check-circle fs-2 text-success mb-3"></i>
                        <p style="font-size: 0.95rem;">Are you sure you want to approve the selected requests?</p>
                    </div>
                    <div class="modal-footer justify-content-center" style="padding: 1.25rem;">
                        <button type="button" class="btn btn-secondary rounded-pill shadow-sm" data-bs-dismiss="modal" style="padding: 0.5rem 1rem; font-size: 0.9rem; border-radius: 0.5rem; transition: background-color 0.3s ease-in-out;">Cancel</button>
                        <button type="button" class="btn btn-success rounded-pill shadow-sm" id="confirmBulkApprove" style="padding: 0.5rem 1rem; font-size: 0.9rem; border-radius: 0.5rem; transition: background-color 0.3s ease-in-out;">Approve</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="bulkRejectModal" tabindex="-1" aria-labelledby="bulkRejectModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded shadow" style="border-radius: 0.75rem; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);">
                    <div class="modal-header bg-danger text-white rounded-top" style="background-color: #dc3545 !important; border-radius: 0.75rem 0.75rem 0 0; padding: 1.25rem;">
                        <h5 class="modal-title" id="bulkRejectModalLabel" style="font-size: 1.25rem;">Reject Selected Requests</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center" style="padding: 1.5rem;">
                        <i class="fas fa-xmark-circle fs-2 text-danger mb-3"></i>
                        <p style="font-size: 0.95rem;">Are you sure you want to reject the selected requests?</p>
                    </div>
                    <div class="modal-footer justify-content-center" style="padding: 1.25rem;">
                        <button type="button" class="btn btn-secondary rounded-pill shadow-sm" data-bs-dismiss="modal" style="padding: 0.5rem 1rem; font-size: 0.9rem; border-radius: 0.5rem; transition: background-color 0.3s ease-in-out;">Cancel</button>
                        <button type="button" class="btn btn-danger rounded-pill shadow-sm" id="confirmBulkReject" style="padding: 0.5rem 1rem; font-size: 0.9rem; border-radius: 0.5rem; transition: background-color 0.3s ease-in-out;">Reject</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* General Card Hover Effect */
        .card.shadow.mb-4.rounded, .card.shadow.rounded {
            transition: box-shadow 0.3s ease-in-out;
        }
        .card.shadow.mb-4.rounded:hover, .card.shadow.rounded:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        /* Button Hover Effects */
        .btn-primary.rounded-pill.shadow-sm:hover,
        .btn-secondary.rounded-pill.shadow-sm:hover,
        .btn-success.rounded-pill.shadow-sm:hover,
        .btn-danger.rounded-pill.shadow-sm:hover,
        .btn-outline-secondary.rounded-pill.shadow-sm:hover {
            opacity: 0.9;
            transform: scale(1.03);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Table Row Hover Effect */
        .table-responsive > .table > tbody > tr:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            transform: translateY(-2px);
            background-color: #f8fafa !important; /* or another light background */
        }
        .table-responsive > .table > tbody > tr {
            transition: box-shadow 0.3s ease-in-out, transform 0.3s ease-in-out, background-color 0.3s ease-in-out;
        }

        /* Modal Content Hover Effect (subtle if needed) */
        .modal-content.rounded.shadow {
            transition: box-shadow 0.3s ease-in-out;
        }
        .modal-content.rounded.shadow:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12);
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const selectAllCheckbox = document.getElementById('selectAll');
            const itemCheckboxes = document.querySelectorAll('.select-item');
            const form = document.getElementById('bulkActionForm');

            // Toggle all checkboxes
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', () => {
                    itemCheckboxes.forEach(checkbox => {
                        checkbox.checked = selectAllCheckbox.checked;
                    });
                });
            }

            // Function to handle bulk actions
            const handleBulkAction = (actionUrl) => {
                const selectedIds = Array.from(itemCheckboxes)
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => checkbox.value);

                if (selectedIds.length === 0) {
                    alert('Please select at least one request.');
                    return;
                }

                // Set the form action to the specified URL
                form.action = actionUrl;

                // Remove existing request_ids[] inputs, but keep CSRF token
                const existingInputs = form.querySelectorAll('input[name="request_ids[]"]');
                existingInputs.forEach(input => input.remove());

                // Append hidden inputs for selected IDs
                selectedIds.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'request_ids[]';
                    input.value = id;
                    form.appendChild(input);
                });

                // Submit the form
                form.submit();
            };

            // Approve Selected
            const confirmBulkApproveBtn = document.getElementById('confirmBulkApprove');
            if (confirmBulkApproveBtn) {
                confirmBulkApproveBtn.addEventListener('click', () => {
                    handleBulkAction("{{ route('owner.products.bulk_approve', $product->product_code) }}");
                });
            }

            // Reject Selected
            const confirmBulkRejectBtn = document.getElementById('confirmBulkReject');
            if (confirmBulkRejectBtn) {
                confirmBulkRejectBtn.addEventListener('click', () => {
                    handleBulkAction("{{ route('owner.products.bulk_reject', $product->product_code) }}");
                });
            }
        });

        $(document).ready(function() {
            // Button hover effects
            $('.btn-primary.rounded-pill.shadow-sm, .btn-secondary.rounded-pill.shadow-sm, .btn-success.rounded-pill.shadow-sm, .btn-danger.rounded-pill.shadow-sm, .btn-outline-secondary.rounded-pill.shadow-sm').hover(function() {
                $(this).css({'opacity': '0.9', 'transform': 'scale(1.03)', 'box-shadow': '0 4px 8px rgba(0, 0, 0, 0.1)'});
            }, function() {
                $(this).css({'opacity': '1', 'transform': 'scale(1)', 'box-shadow': 'none'});
            });

            // Table row hover effect
            $('.table-responsive > .table > tbody > tr').hover(function() {
                $(this).css({'box-shadow': '0 4px 8px rgba(0, 0, 0, 0.05)', 'transform': 'translateY(-2px)', 'backgroundColor': '#f8fafa'});
            }, function() {
                $(this).css({'box-shadow': 'none', 'transform': 'translateY(0)', 'backgroundColor': 'white'});
            });

            // Modal content hover
            $('.modal-content.rounded.shadow').hover(function() {
                $(this).css('box-shadow', '0 8px 16px rgba(0, 0, 0, 0.12)');
            }, function() {
                $(this).css('box-shadow', '0 6px 12px rgba(0, 0, 0, 0.1)');
            });
        });
    </script>
@endsection