@extends('layout.lecturer')

@section('title', 'Redeem Products')

@section('content')
    <div class="container-fluid p-0">
        <!-- Alerts for Success, Error, and Info Messages -->
        <div class="container mt-3 px-3">
            @foreach (['success', 'error', 'info'] as $msg)
                @if(session($msg))
                    <div class="alert alert-{{ $msg }} alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-{{ $msg === 'success' ? 'check-circle' : ($msg === 'error' ? 'exclamation-triangle' : 'info-circle') }} me-2"></i>
                        {{ session($msg) }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Header -->
        <div class="text-center py-3 border-bottom" style="background-color: #1e3a8a; color: white;">
            <h1 class="mb-0 fs-4">
                <i class="fa-solid fa-gift"></i> Redeem Products
            </h1>
        </div>

        <!-- User Points Summary -->
        <div class="container mt-3 px-3">
            <div class="row g-3">
                <!-- Current Points Card -->
                <div class="col-12 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #1e818a, #3b82f6);">
                        <div class="card-body d-flex align-items-center justify-content-between p-4">
                            <div>
                                <h5 class="mb-2 text-white" style="font-size: 1.1rem; font-weight: 500;">
                                    <i class="fa-solid fa-wallet me-2"></i> Current Points
                                </h5>
                                <h2 class="mb-0 text-white" style="font-size: 2.5rem; font-weight: bold;">
                                    {{ $currentPoints }}
                                </h2>
                            </div>
                            <div class="icon-circle bg-white text-primary d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; border-radius: 50%;">
                                <i class="fa-solid fa-coins fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Points Card -->
                <div class="col-12 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #10b981, #22c556);">
                        <div class="card-body d-flex align-items-center justify-content-between p-4">
                            <div>
                                <h5 class="mb-2 text-white" style="font-size: 1.1rem; font-weight: 500;">
                                    <i class="fa-solid fa-trophy me-2"></i> Total Points
                                </h5>
                                <h2 class="mb-0 text-white" style="font-size: 2.5rem; font-weight: bold;">
                                    {{ $totalPoints }}
                                </h2>
                            </div>
                            <div class="icon-circle bg-white text-success d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; border-radius: 50%;">
                                <i class="fa-solid fa-star fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- History Button -->
            <div class="text-center mt-4">
                <button class="btn btn-light rounded-pill px-4 py-2 shadow-sm" type="button" data-bs-toggle="modal" data-bs-target="#redeemHistoryModal" style="font-size: 16px;">
                    <i class="fa-solid fa-history me-2"></i> View Redemption History
                </button>
            </div>
        </div>

        <!-- Redeem History Modal -->
        <div class="modal fade" id="redeemHistoryModal" tabindex="-1" aria-labelledby="redeemHistoryModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content rounded-4">
                    <div class="modal-header" style="background-color: #1e3a8a; color: white;">
                        <h5 class="modal-title" id="redeemHistoryModalLabel">
                            <i class="fa-solid fa-file-alt"></i> Redeem History
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Check if there is redeem history -->
                        @if($allRedemptionRequests->isEmpty())
                            <p class="text-center text-muted">
                                <i class="fa-solid fa-circle-info"></i> No redemption history found.
                            </p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead class="text-white" style="background-color: #102460;">
                                    <tr>
                                        <th>#</th>
                                        <th><i class="fa-solid fa-box"></i> Product Name</th>
                                        <th><i class="fa-solid fa-coins"></i> Points Used</th>
                                        <th><i class="fa-solid fa-circle-check"></i> Status</th>
                                        <th><i class="fa-solid fa-calendar-alt"></i> Requested At</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($allRedemptionRequests as $index => $request)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $request->product->name }}</td>
                                            <td>{{ $request->product->points_required }}</td>
                                            <td>
                                                <span class="badge {{ $request->status === 'approved' ? 'bg-success' : ($request->status === 'rejected' ? 'bg-danger' : ($request->status === 'waiting_list' ? 'bg-info' : 'bg-warning')) }}">
                                                    {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                                </span>
                                            </td>
                                            <td>{{ $request->requested_at->format('Y-m-d H:i') }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Cards -->
        <div class="container mt-3 px-3">
            <div class="row gy-4">
                @forelse($products as $product)
                    <div class="col-12 col-sm-6 col-md-4">
                        <div class="card border-0 shadow-sm rounded-4" style="background-color: white;">
                            <!-- Product Image -->
                            <div class="card-img-top-container position-relative" style="overflow: hidden; padding-top: 56.25%; border-top-left-radius: 16px; border-top-right-radius: 16px;">
                                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;">
                                <!-- Product Status -->
                                <span class="badge position-absolute top-0 start-0 m-3 py-2 px-3 {{ $product->status === 'ready' ? 'bg-success' : 'bg-warning' }}">
                                    {{ $product->status === 'ready' ? 'Ready to Redeem' : 'Waiting List' }}
                                </span>
                            </div>
                            <div class="card-body">
                                <!-- Business Name -->
                                <span class="badge bg-secondary text-light mb-2">
                                    <i class="fa-solid fa-store"></i> {{ $product->business->name }}
                                </span>
                                <!-- Product Name -->
                                <h5 class="card-title mb-1 text-truncate" style="color: #102460;">
                                    {{ $product->name }}
                                </h5>
                                <!-- Product Description -->
                                <p class="card-text small">{{ Str::limit($product->description, 60) }}</p>
                                <!-- Points -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-primary">
                                        <i class="fa-solid fa-coins"></i> {{ $product->points_required }}
                                    </span>
                                </div>
                                <!-- {{ __('View Details') }} Button -->
                                <button type="button" class="btn btn-primary w-100 rounded-pill py-2 d-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#productModal{{ $product->id }}">
                                    <i class="fa-solid fa-info-circle me-2"></i> {{ __('View Details') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Product Modal -->
                    <div class="modal fade" id="productModal{{ $product->id }}" tabindex="-1" aria-labelledby="productModalLabel{{ $product->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-md modal-dialog-centered">
                            <div class="modal-content rounded-4 border-0 shadow">
                                <!-- Modal Header -->
                                <div class="modal-header p-3" style="background-color: #102460; color: white;">
                                    <h5 class="modal-title fs-5" id="productModalLabel{{ $product->id }}">
                                        <i class="fa-solid fa-gift me-2"></i> {{ $product->name }}
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <!-- Modal Body -->
                                <div class="modal-body p-3">
                                    <!-- Product Image -->
                                    <div class="text-center mb-3">
                                        <img src="{{ asset($product->image) }}" class="rounded-3 img-fluid" alt="{{ $product->name }}" style="max-height: 150px;">
                                    </div>

                                    <!-- Product Description -->
                                    <div class="mb-3">
                                        <p class="mb-0 small text-muted">{{ $product->description }}</p>
                                    </div>

                                    <!-- Product Details -->
                                    <div class="row g-2">
                                        <div class="col-12">
                                            <div class="p-2 bg-light rounded-3">
                                                <h6 class="small text-muted mb-1">
                                                    <i class="fa-solid fa-store me-1"></i> Business
                                                </h6>
                                                <p class="mb-0 small">{{ $product->business->name }}</p>
                                            </div>
                                        </div>
                                        <div class="col-12 mt-2">
                                            <div class="p-2 bg-light rounded-3">
                                                <h6 class="small text-muted mb-1">
                                                    <i class="fa-solid fa-coins me-1"></i> Points Required
                                                </h6>
                                                <p class="mb-0 small">{{ $product->points_required }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Footer -->
                                <div class="modal-footer border-0 p-3">
                                    @php
                                        $existingRequest = $redemptionRequests->get($product->id);
                                    @endphp

                                    @if(!$existingRequest || ($existingRequest && $existingRequest->status === 'rejected') || ($existingRequest && $existingRequest->status === 'approved'))
                                        <button type="button" class="btn btn-success w-100 rounded-pill py-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#confirmRedeemModal" data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}" data-product-points="{{ $product->points_required }}" data-redeem-url="{{ route('lecturer.redeem.store', $product->product_code) }}">
                                            <i class="fa-solid fa-check me-2"></i> Redeem for {{ $product->points_required }} Points
                                        </button>
                                    @elseif($existingRequest && in_array($existingRequest->status, ['pending', 'waiting_list']))
                                        <p class="text-center w-100 mb-0 small text-muted">
                                            <i class="fa-solid fa-hourglass-half me-2"></i> Your redemption request is being processed.
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 d-flex justify-content-center">
                        <div class="card text-center bg-dark text-light shadow-lg" style="max-width: 400px; border-radius: 15px;">
                            <div class="card-body py-5">
                                <i class="fa-solid fa-box-open display-1 text-warning"></i>
                                <h5 class="card-title mt-3">No Products Available</h5>
                                <p class="card-text">Currently, there are no products available for redemption. Please check back later!</p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- {{ __('Confirm') }}ation Modal -->
        <div class="modal fade" id="confirmRedeemModal" tabindex="-1" aria-labelledby="confirmRedeemModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4">
                    <div class="modal-header" style="background-color: #1e3a8a; color: white;">
                        <h5 class="modal-title" id="confirmRedeemModalLabel">
                            <i class="fa-solid fa-question-circle"></i> {{ __('Confirm') }} Redemption
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p id="confirmRedeemMessage">Are you sure you want to redeem this product?</p>
                    </div>
                    <div class="modal-footer">
                        <form id="redeemForm" method="POST">
                            @csrf
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Yes, Redeem</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $products->links('pagination::bootstrap-4') }}
        </div>
    </div>

    <!-- JavaScript to Handle {{ __('Confirm') }}ation Modal -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const confirmRedeemModal = document.getElementById('confirmRedeemModal');
            const redeemForm = document.getElementById('redeemForm');
            const confirmRedeemMessage = document.getElementById('confirmRedeemMessage');

            confirmRedeemModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const productId = button.getAttribute('data-product-id');
                const productName = button.getAttribute('data-product-name');
                const productPoints = button.getAttribute('data-product-points');
                const redeemUrl = button.getAttribute('data-redeem-url');

                // Update the confirmation message
                confirmRedeemMessage.textContent = `Are you sure you want to redeem "${productName}" for ${productPoints} points?`;

                // Update the form action
                redeemForm.action = redeemUrl;
            });
        });
    </script>
@endsection