@extends('layout.owner')

@section('title', 'Add New Banner')
@section('page-title', 'Add New Banner')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
          integrity="..." crossorigin="anonymous" referrerpolicy="no-referrer"/>

    <div class="card shadow-sm mb-4 rounded" style="border-radius: 0.75rem; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.07); transition: box-shadow 0.3s ease-in-out; border: none;">
        <div class="card-header bg-primary text-white rounded-top" style="border-radius: 0.75rem 0.75rem 0 0; padding: 1.25rem; border-bottom: none;">
            <h3 class="card-title mb-0" style="font-size: 1.5rem;">
                <i class="fas fa-image me-2"></i> Add New Banner
            </h3>
        </div>
        <div class="card-body" style="padding: 1.5rem;">
            <form action="{{ route('banners.store', ['business_unique_id' => $business->business_unique_id]) }}"
                  method="POST">
                @csrf

                <div class="mb-3">
                    <label for="banner_url" class="form-label">Banner URL <span
                                class="text-muted">(Banner image URL)</span></label>
                    <input type="url"
                           class="form-control @error('banner_url') is-invalid @enderror"
                           id="banner_url"
                           name="banner_url"
                           value="{{ old('banner_url') }}"
                           required
                           placeholder="https://example.com/banner-image.jpg">
                    @error('banner_url')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">
                        Enter the full URL of your banner image. <br>
                        <span class="text-danger">
                            <strong>Image Requirements: Landscape format, maximum resolution 1920x1080, maximum file size 2MB.</strong>
                        </span>
                    </small>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('banners.index', ['business_unique_id' => $business->business_unique_id]) }}"
                       class="btn btn-outline-secondary rounded-pill shadow-sm me-2"
                       style="padding: 0.6rem 1.2rem; font-size: 1rem; transition: background-color 0.3s ease-in-out;">
                        <i class="fas fa-arrow-left me-2"></i> Cancel
                    </a>
                    <button type="submit"
                            class="btn btn-primary rounded-pill shadow-sm"
                            style="padding: 0.6rem 1.2rem; font-size: 1rem; transition: background-color 0.3s ease-in-out;">
                        <i class="fas fa-save me-2"></i> Save Banner
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        /* General Card Hover Effect */
        .card.shadow-sm.mb-4.rounded {
            transition: box-shadow 0.3s ease-in-out;
        }

        .card.shadow-sm.mb-4.rounded:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        /* Button Hover Effects */
        .btn-primary.rounded-pill.shadow-sm:hover,
        .btn-outline-secondary.rounded-pill.shadow-sm:hover {
            opacity: 0.9;
            transform: scale(1.03);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            // Button hover effects
            $('.btn-primary.rounded-pill.shadow-sm, .btn-outline-secondary.rounded-pill.shadow-sm').hover(function () {
                $(this).css({
                    'opacity': '0.9',
                    'transform': 'scale(1.03)',
                    'box-shadow': '0 4px 8px rgba(0, 0, 0, 0.1)'
                });
            }, function () {
                $(this).css({
                    'opacity': '1',
                    'transform': 'scale(1)',
                    'box-shadow': 'none'
                });
            });
        });
    </script>
@endsection