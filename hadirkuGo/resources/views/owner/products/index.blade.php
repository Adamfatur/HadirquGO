@extends('layout.owner')

@section('content')
    <div class="container mt-5 pt-5">
        <div class="card shadow mb-4 rounded" style="border: none; border-radius: 0.75rem; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.07); transition: box-shadow 0.3s ease-in-out;">
            <div class="card-body" style="padding: 2rem;">
                <div class="card-header bg-transparent border-0 pb-3" style="padding-bottom: 1.5rem; margin-bottom: 1.5rem; border-bottom: 1px solid #e8e8e8;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0" style="font-size: 1.5rem; color: #333; font-weight: 500;">
                            <i class="fas fa-box me-2" style="color: #007bff;"></i>
                            <span class="text-primary fw-semibold" style="color: #007bff; font-weight: 600;">Product Management</span>
                        </h4>
                        <button type="button" class="btn btn-primary btn-sm rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#addProductModal" style="padding: 0.5rem 1rem; font-size: 0.9rem; background-color: #007bff; border: none; transition: background-color 0.3s ease-in-out;">
                            <i class="fas fa-plus-circle me-2"></i> Add Product
                        </button>
                    </div>
                </div>

                <form action="{{ route('owner.products.index') }}" method="GET" class="my-3">
                    <div class="input-group input-group-lg rounded shadow-sm" style="border-radius: 0.5rem; box-shadow: 0 3px 7px rgba(0, 0, 0, 0.05);">
                        <input type="text" name="search" class="form-control border-0" placeholder="Search products..." value="{{ request('search') }}" autofocus style="border: none; font-size: 0.9rem; padding: 0.8rem 1rem;">
                        <button class="btn btn-primary border-0 rounded-end" type="submit" style="background-color: #007bff; border: none; padding: 0.8rem 1rem; transition: background-color 0.3s ease-in-out;">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>

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
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover table-borderless align-middle table-sm mb-0" style="border-collapse: separate; border-spacing: 0 8px;">
                        <thead class="thead-light" style="background-color: #f8f9fa;">
                        <tr style="border-bottom: 1px solid #e0e0e0;">
                            <th class="text-center" style="padding: 0.75rem 0.5rem;">Image</th>
                            <th class="text-center" style="padding: 0.75rem 0.5rem;">Code</th>
                            <th style="padding: 0.75rem 0.5rem;">Name</th>
                            <th style="padding: 0.75rem 0.5rem;">Description</th>
                            <th class="text-center" style="padding: 0.75rem 0.5rem;">Stock</th>
                            <th class="text-center" style="padding: 0.75rem 0.5rem;">Points</th>
                            <th class="text-center" style="padding: 0.75rem 0.5rem;">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($products as $product)
                            <tr style="background-color: white; border-radius: 0.5rem; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.03); transition: box-shadow 0.3s ease-in-out;">
                                <td class="text-center" style="padding: 0.75rem 0.5rem;">
                                    @if($product->image)
                                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded shadow-sm" style="max-width: 60px; max-height: 60px; border-radius: 0.3rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.07);">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center bg-light rounded" style="width: 60px; height: 60px; border-radius: 0.3rem;">
                                            <i class="fas fa-image text-muted fs-5"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="text-center" style="padding: 0.75rem 0.5rem;"><strong style="color: #555;">{{ $product->product_code }}</strong></td>
                                <td style="padding: 0.75rem 0.5rem; color: #333;">{{ $product->name }}</td>
                                <td style="padding: 0.75rem 0.5rem; color: #666;">{{ Str::limit($product->description, 75) }}</td>
                                <td class="text-center" style="padding: 0.75rem 0.5rem; color: #555;">{{ $product->stock_quantity }}</td>
                                <td class="text-center" style="padding: 0.75rem 0.5rem; color: #555;">{{ $product->points_required }}</td>
                                <td class="text-center" style="padding: 0.75rem 0.5rem;">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-warning btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#editProductModal{{ $product->product_code }}" style="padding: 0.3rem 0.75rem; font-size: 0.8rem; background-color: #ffc107; border: none; transition: background-color 0.3s ease-in-out; margin-right: 0.25rem;">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#deleteProductModal{{ $product->product_code }}" style="padding: 0.3rem 0.75rem; font-size: 0.8rem; background-color: #dc3545; border: none; transition: background-color 0.3s ease-in-out; margin-right: 0.25rem;">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                        <a href="{{ route('owner.products.waiting_list', $product->product_code) }}" class="btn btn-info btn-sm rounded-pill" style="padding: 0.3rem 0.75rem; font-size: 0.8rem; background-color: #17a2b8; border: none; transition: background-color 0.3s ease-in-out; color: white;">
                                            <i class="fas fa-list-ul"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-info-circle fs-4 me-2"></i> No products found.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $products->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>

        <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content rounded shadow" style="border-radius: 0.75rem; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);">
                    <div class="modal-header bg-primary text-white rounded-top" style="background-color: #007bff !important; border-radius: 0.75rem 0.75rem 0 0; padding: 1.25rem;">
                        <h5 class="modal-title" style="font-size: 1.25rem;">
                            <i class="fas fa-plus-circle me-2"></i> Add New Product
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('owner.products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body" style="padding: 1.5rem;">
                            <div class="mb-3">
                                <label for="name" class="form-label" style="font-size: 0.95rem; color: #555;">Product Name</label>
                                <input type="text" class="form-control rounded-pill" id="name" name="name" required style="border-radius: 0.5rem; padding: 0.75rem 1rem; font-size: 0.9rem; border-color: #ced4da;">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label" style="font-size: 0.95rem; color: #555;">Description</label>
                                <textarea class="form-control rounded" id="description" name="description" rows="3" style="border-radius: 0.5rem; padding: 0.75rem 1rem; font-size: 0.9rem; border-color: #ced4da;"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label" style="font-size: 0.95rem; color: #555;">Image</label>
                                <input type="file" class="form-control rounded-pill" id="image" name="image" style="border-radius: 0.5rem; padding: 0.75rem 1rem; font-size: 0.9rem; border-color: #ced4da;">
                            </div>
                            <div class="mb-3">
                                <label for="stock" class="form-label" style="font-size: 0.95rem; color: #555;">Stock Quantity</label>
                                <input type="number" class="form-control rounded-pill" id="stock" name="stock_quantity" required style="border-radius: 0.5rem; padding: 0.75rem 1rem; font-size: 0.9rem; border-color: #ced4da;">
                            </div>
                            <div class="mb-3">
                                <label for="points" class="form-label" style="font-size: 0.95rem; color: #555;">Points Required</label>
                                <input type="number" class="form-control rounded-pill" id="points" name="points_required" required style="border-radius: 0.5rem; padding: 0.75rem 1rem; font-size: 0.9rem; border-color: #ced4da;">
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label" style="font-size: 0.95rem; color: #555;">Status</label>
                                <select class="form-select rounded-pill" id="status" name="status" required style="border-radius: 0.5rem; padding: 0.75rem 1rem; font-size: 0.9rem; border-color: #ced4da;">
                                    <option value="ready">Ready</option>
                                    <option value="waiting_list">Waiting List</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer" style="padding: 1.25rem; display: flex; justify-content: flex-end;">
                            <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal" style="padding: 0.5rem 1rem; font-size: 0.9rem; border-radius: 0.5rem; transition: background-color 0.3s ease-in-out;">Cancel</button>
                            <button type="submit" class="btn btn-primary rounded-pill" style="padding: 0.5rem 1rem; font-size: 0.9rem; border-radius: 0.5rem; background-color: #007bff; border: none; transition: background-color 0.3s ease-in-out; margin-left: 0.5rem;">Add Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @foreach($products as $product)
            <div class="modal fade" id="editProductModal{{ $product->product_code }}" tabindex="-1" aria-labelledby="editProductModalLabel{{ $product->product_code }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content rounded shadow" style="border-radius: 0.75rem; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);">
                        <div class="modal-header bg-warning text-white rounded-top" style="background-color: #ffc107 !important; border-radius: 0.75rem 0.75rem 0 0; padding: 1.25rem;">
                            <h5 class="modal-title" style="font-size: 1.25rem;">
                                <i class="fas fa-pencil-alt me-2"></i> Edit Product: {{ $product->name }}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('owner.products.update', $product->product_code) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="modal-body" style="padding: 1.5rem;">
                                <div class="mb-3">
                                    <label for="nameEdit" class="form-label" style="font-size: 0.95rem; color: #555;">Product Name</label>
                                    <input type="text" class="form-control rounded-pill" id="nameEdit" name="name" value="{{ $product->name }}" required style="border-radius: 0.5rem; padding: 0.75rem 1rem; font-size: 0.9rem; border-color: #ced4da;">
                                </div>
                                <div class="mb-3">
                                    <label for="descriptionEdit" class="form-label" style="font-size: 0.95rem; color: #555;">Description</label>
                                    <textarea class="form-control rounded" id="descriptionEdit" name="description" rows="3" style="border-radius: 0.5rem; padding: 0.75rem 1rem; font-size: 0.9rem; border-color: #ced4da;">{{ $product->description }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="imageEdit" class="form-label" style="font-size: 0.95rem; color: #555;">Image</label>
                                    <input type="file" class="form-control rounded-pill" id="imageEdit" name="image" style="border-radius: 0.5rem; padding: 0.75rem 1rem; font-size: 0.9rem; border-color: #ced4da;">
                                    @if($product->image)
                                        <img src="{{ asset($product->image) }}" class="img-thumbnail mt-2 rounded shadow-sm" width="150" alt="{{ $product->name }}" style="border-radius: 0.5rem; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);">
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label for="stockEdit" class="form-label" style="font-size: 0.95rem; color: #555;">Stock Quantity</label>
                                    <input type="number" class="form-control rounded-pill" id="stockEdit" name="stock_quantity" value="{{ $product->stock_quantity }}" required style="border-radius: 0.5rem; padding: 0.75rem 1rem; font-size: 0.9rem; border-color: #ced4da;">
                                </div>
                                <div class="mb-3">
                                    <label for="pointsEdit" class="form-label" style="font-size: 0.95rem; color: #555;">Points Required</label>
                                    <input type="number" class="form-control rounded-pill" id="pointsEdit" name="points_required" value="{{ $product->points_required }}" required style="border-radius: 0.5rem; padding: 0.75rem 1rem; font-size: 0.9rem; border-color: #ced4da;">
                                </div>
                                <div class="mb-3">
                                    <label for="statusEdit" class="form-label" style="font-size: 0.95rem; color: #555;">Status</label>
                                    <select class="form-select rounded-pill" id="statusEdit" name="status" required style="border-radius: 0.5rem; padding: 0.75rem 1rem; font-size: 0.9rem; border-color: #ced4da;">
                                        <option value="ready" {{ $product->status === 'ready' ? 'selected' : '' }}>Ready</option>
                                        <option value="waiting_list" {{ $product->status === 'waiting_list' ? 'selected' : '' }}>Waiting List</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer" style="padding: 1.25rem; display: flex; justify-content: flex-end;">
                                <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal" style="padding: 0.5rem 1rem; font-size: 0.9rem; border-radius: 0.5rem; transition: background-color 0.3s ease-in-out;">Cancel</button>
                                <button type="submit" class="btn btn-warning rounded-pill" style="padding: 0.5rem 1rem; font-size: 0.9rem; border-radius: 0.5rem; background-color: #ffc107; border: none; transition: background-color 0.3s ease-in-out; margin-left: 0.5rem;">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="deleteProductModal{{ $product->product_code }}" tabindex="-1" aria-labelledby="deleteProductModalLabel{{ $product->product_code }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content rounded shadow" style="border-radius: 0.75rem; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);">
                        <div class="modal-header bg-danger text-white rounded-top" style="background-color: #dc3545 !important; border-radius: 0.75rem 0.75rem 0 0; padding: 1.25rem;">
                            <h5 class="modal-title" style="font-size: 1.25rem;">
                                <i class="fas fa-trash-alt me-2"></i> Delete Product: {{ $product->name }}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('owner.products.destroy', $product->product_code) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <div class="modal-body text-center" style="padding: 1.5rem;">
                                <i class="fas fa-trash-alt fs-2 text-danger mb-3"></i>
                                <h4 class="text-danger" style="font-size: 1.1rem;">Delete Confirmation</h4>
                                <p class="text-muted" style="font-size: 0.9rem;">Are you sure you want to permanently delete this product?</p>
                            </div>
                            <div class="modal-footer justify-content-center" style="padding: 1.25rem;">
                                <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal" style="padding: 0.5rem 1rem; font-size: 0.9rem; border-radius: 0.5rem; transition: background-color 0.3s ease-in-out;">Cancel</button>
                                <button type="submit" class="btn btn-danger rounded-pill" style="padding: 0.5rem 1rem; font-size: 0.9rem; border-radius: 0.5rem; background-color: #dc3545; border: none; transition: background-color 0.3s ease-in-out;">Delete</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@push('styles')
    <style>
        /* General Card Hover Effect */
        .card.shadow.mb-4.rounded {
            transition: box-shadow 0.3s ease-in-out;
        }
        .card.shadow.mb-4.rounded:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        /* Button Hover Effects */
        .btn-primary.rounded-pill.shadow-sm:hover,
        .btn-primary.border-0.rounded-end:hover,
        .btn-secondary.rounded-pill:hover,
        .btn-warning.rounded-pill:hover,
        .btn-danger.rounded-pill:hover,
        .btn-info.rounded-pill:hover {
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
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            const searchTerm = '{{ request('search') }}';
            if (searchTerm) {
                $('td').each(function() {
                    const text = $(this).text();
                    const highlightedText = text.replace(new RegExp(searchTerm, 'gi'),
                        `<span class="text-primary fw-bold" style="color: #007bff; font-weight: bold;">${searchTerm}</span>`);
                    $(this).html(highlightedText);
                });
            }

            // Button hover effects (if not using CSS for all)
            $('.btn-primary.rounded-pill.shadow-sm, .btn-primary.border-0.rounded-end, .btn-secondary.rounded-pill, .btn-warning.rounded-pill, .btn-danger.rounded-pill, .btn-info.rounded-pill').hover(function() {
                $(this).css({'opacity': '0.9', 'transform': 'scale(1.03)', 'box-shadow': '0 4px 8px rgba(0, 0, 0, 0.1)'});
            }, function() {
                $(this).css({'opacity': '1', 'transform': 'scale(1)', 'box-shadow': 'none'});
            });

            // Table row hover effect (if not using CSS for all)
            $('.table-responsive > .table > tbody > tr').hover(function() {
                $(this).css({'box-shadow': '0 4px 8px rgba(0, 0, 0, 0.05)', 'transform': 'translateY(-2px)', 'backgroundColor': '#f8fafa'});
            }, function() {
                $(this).css({'box-shadow': 'none', 'transform': 'translateY(0)', 'backgroundColor': 'white'});
            });

            // Modal content hover (example, can be extended)
            $('.modal-content.rounded.shadow').hover(function() {
                $(this).css('box-shadow', '0 8px 16px rgba(0, 0, 0, 0.12)');
            }, function() {
                $(this).css('box-shadow', '0 6px 12px rgba(0, 0, 0, 0.1)');
            });
        });
    </script>
@endpush