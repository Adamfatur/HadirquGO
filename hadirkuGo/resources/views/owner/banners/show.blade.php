{{-- ... (Detail bisnis lainnya) ... --}}

<hr class="mb-4"> {{-- garis pemisah --}}

<div class="banners-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0" style="font-size: 1.3rem; font-weight: 500;">
            <i class="fas fa-images text-primary me-2"></i> Banner Management
        </h4>
        <a href="{{ route('businesses.banners.create', $business) }}"
           class="btn btn-primary rounded-pill shadow-sm px-3" style="padding: 0.5rem 1rem; font-size: 0.9rem; transition: background-color 0.3s ease-in-out;">
            <i class="fas fa-plus-circle me-2"></i> Add Banner
        </a>
    </div>

    @if($business->banners->count() > 0)
        <div class="row">
            @foreach ($business->banners as $banner)
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm rounded" style="border-radius: 0.75rem; box-shadow: 0 3px 7px rgba(0, 0, 0, 0.05); transition: box-shadow 0.3s ease-in-out;">
                        <img src="{{ $banner->banner_url }}" class="card-img-top rounded-top" alt="Banner" style="height: 200px; object-fit: cover; border-radius: 0.75rem 0.75rem 0 0;">
                        <div class="card-body" style="padding: 1.25rem;">
                            <h5 class="card-title" style="font-size: 1.1rem; margin-bottom: 0.75rem;">Banner ID: {{ $banner->id }}</h5>
                            <p class="card-text mb-2">
                                Status: <span class="badge {{ $banner->is_active ? 'bg-success' : 'bg-danger' }} rounded-pill">{{ $banner->is_active ? 'Active' : 'Inactive' }}</span>
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <a href="{{ route('businesses.banners.edit', [$business, $banner]) }}"
                                       class="btn btn-sm btn-outline-primary rounded-pill px-3 me-2" style="padding: 0.3rem 0.75rem; font-size: 0.8rem; border-width: 1px; transition: background-color 0.3s ease-in-out;">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </a>
                                    <form action="{{ route('businesses.banners.destroy', [$business, $banner]) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Are you sure you want to delete this banner?')">
                                            <i class="fas fa-trash-alt me-1"></i> Delete
                                        </button>
                                    </form>
                                </div>
                                <form action="{{ route('businesses.banners.toggleStatus', [$business, $banner]) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-info rounded-pill px-3" style="padding: 0.3rem 0.75rem; font-size: 0.8rem; border-width: 1px; transition: background-color 0.3s ease-in-out;">
                                        <i class="fas fa-power-off me-1"></i> {{ $banner->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info mt-4 rounded shadow-sm" style="border-radius: 0.5rem; box-shadow: 0 3px 7px rgba(0, 0, 0, 0.05); background-color: #e3f2fd; border-color: #bbdefb; color: #0a589b;">
            <i class="fas fa-info-circle me-2"></i>
            No banners found. Add your first banner by clicking the "Add Banner" button.
        </div>
    @endif
</div>


@endsection

@section('styles')
    <style>
        /* General Card Hover Effect */
        .card.shadow-sm.mb-4.rounded, .card.shadow-sm.rounded {
            transition: box-shadow 0.3s ease-in-out;
        }
        .card.shadow-sm.mb-4.rounded:hover, .card.shadow-sm.rounded:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        /* Button Hover Effects */
        .btn-primary.rounded-pill.shadow-sm:hover,
        .btn-outline-secondary.rounded-pill.shadow-sm:hover{
            opacity: 0.9;
            transform: scale(1.03);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-outline-primary.rounded-pill.px-3.me-2:hover,
        .btn-outline-danger.rounded-pill.px-3:hover,
        .btn-info.rounded-pill.px-3:hover {
            opacity: 1;
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Banner Card Specific Styling */
        .card.shadow-sm.rounded .card-img-top.rounded-top {
            border-bottom-left-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
        }
    </style>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Button hover effects - combined for all relevant buttons
            $('.btn-primary.rounded-pill.shadow-sm, .btn-outline-secondary.rounded-pill.shadow-sm, .btn-outline-primary.rounded-pill.px-3.me-2, .btn-outline-danger.rounded-pill.px-3, .btn-info.rounded-pill.px-3').hover(function() {
                $(this).css({'opacity': $(this).hasClass('btn-outline-primary') || $(this).hasClass('btn-outline-danger') || $(this).hasClass('btn-info') ? '1' : '0.9', 'transform': 'scale(1.03)', 'box-shadow': '0 4px 8px rgba(0, 0, 0, 0.1)'});
            }, function() {
                $(this).css({'opacity': '1', 'transform': 'scale(1)', 'box-shadow': 'none'});
            });
        });
    </script>
@endsection