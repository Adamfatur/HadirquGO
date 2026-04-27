@extends('layout.owner')

@section('title', 'Banner Management')
@section('page-title', 'Banner Management')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.26/dist/sweetalert2.min.css"/>

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center mb-4 shadow-sm rounded alert-dismissible fade show"
             role="alert"
             style="background-color: #e6f7ec; border-color: #c3e8cd; color: #2d4b3a;">
            <i class="fas fa-check-circle fa-2x me-3"></i>
            <div class="fw-bold">{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger d-flex align-items-center mb-4 shadow-sm rounded alert-dismissible fade show"
             role="alert"
             style="background-color: #f8d7da; border-color: #f1caca; color: #842029;">
            <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
            <div class="fw-bold">{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="banners-section">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0" style="font-size: 1.5rem; font-weight: 500;">
                <i class="fas fa-images text-primary me-2"></i> Banner Management
            </h4>
            <a href="{{ route('banners.create', ['business_unique_id' => $business->business_unique_id]) }}"
               class="btn btn-primary rounded-pill shadow-sm px-3"
               style="padding: 0.6rem 1.2rem; font-size: 1rem; transition: background-color 0.3s ease-in-out;">
                <i class="fas fa-plus-circle me-2"></i> Banner
            </a>
        </div>

        @if($banners->count() > 0)
            <div class="table-responsive shadow-sm rounded"
                 style="border-radius: 0.75rem; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.08);">
                <table class="table table-hover table-borderless align-middle mb-0"
                       style="border-collapse: separate; border-spacing: 0 10px;">
                    <thead class="thead-light" style="background-color: #f8f9fa;">
                    <tr>
                        <th style="padding-left: 1.5rem; width: 50%;">Banner</th>
                        <th class="text-center" style="width: 20%;">Status</th>
                        <th class="text-center" style="width: 30%;">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($banners as $index => $banner)
                        <tr style="background-color: white; border-radius: 0.75rem; box-shadow: 0 3px 8px rgba(0, 0, 0, 0.05); transition: box-shadow 0.3s ease-in-out;">
                            <td style="padding-left: 1.5rem;">
                                <div class="d-flex align-items-center" style="padding: 1rem 0;">
                                    <div class="me-4 position-relative" style="width: 80px; height: auto;">
                                        <a href="{{ $banner->banner_url }}" target="_blank" style="cursor: pointer; display: block;">
                                            <img src="{{ $banner->banner_url }}" alt="Banner Thumbnail"
                                                 style="width: 80px; height: auto; border-radius: 0.5rem; object-fit: cover; display: block;">
                                            <i class="fas fa-eye position-absolute top-50 start-50 translate-middle text-white bg-dark rounded-circle p-1" style="opacity: 0.7;"></i>
                                        </a>
                                    </div>
                                    <div class="">
                                        <a href="{{ $banner->banner_url }}" target="_blank" class="text-body fw-bold"
                                           style="text-decoration: none; display: block; margin-bottom: 0.3rem;">
                                            Banner #{{ $loop->iteration }}
                                        </a>
                                        <a href="{{ $banner->banner_url }}" target="_blank" class="text-muted"
                                           style="text-decoration: none; font-size: 0.9rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 250px; display: block;">
                                            {{ $banner->banner_url }}
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center fw-bold">
                                <span class="badge {{ $banner->is_active ? 'bg-success' : 'bg-danger' }} rounded-pill"
                                      style="padding: 0.6rem 0.8rem; font-size: 0.9rem;">
                                    {{ $banner->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-center" style="padding: 1rem 0;">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('banners.edit', ['business_unique_id' => $business->business_unique_id, 'banner' => $banner->id]) }}"
                                       class="btn btn-sm btn-outline-primary rounded-pill px-3"
                                       style="padding: 0.5rem 1rem; font-size: 0.9rem; border-width: 1px; transition: background-color 0.3s ease-in-out;">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </a>
                                    <form action="{{ route('banners.destroy', ['business_unique_id' => $business->business_unique_id, 'banner' => $banner->id]) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                                style="padding: 0.5rem 1rem; font-size: 0.9rem; border-width: 1px; transition: background-color 0.3s ease-in-out;">
                                            <i class="fas fa-trash-alt me-1"></i> Delete
                                        </button>
                                    </form>
                                    <form action="{{ route('banners.toggleStatus', ['business_unique_id' => $business->business_unique_id, 'banner' => $banner->id]) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-info rounded-pill px-3"
                                                style="padding: 0.5rem 1rem; font-size: 0.9rem; border-width: 1px; transition: background-color 0.3s ease-in-out;">
                                            <i class="fas fa-power-off me-1"></i> {{ $banner->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info mt-4 rounded shadow-sm"
                 style="border-radius: 0.75rem; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.08); background-color: #e3f2fd; border-color: #bbdefb; color: #0a589b;">
                <i class="fas fa-info-circle me-2"></i>
                No banners found. You can add a new banner by clicking the button above.
            </div>
        @endif
    </div>

    <div class="mt-5">
        <a href="{{ url()->previous() }}"
           class="btn btn-outline-secondary rounded-pill shadow-sm"
           style="padding: 0.6rem 1.2rem; font-size: 1rem; transition: background-color 0.3s ease-in-out;">
            <i class="fas fa-arrow-left me-2"></i> Back
        </a>
    </div>

@endsection

@section('styles')
    <style>
        /* General Card and Table Hover Effect */
        .card.shadow-sm.mb-4.rounded,
        .table-responsive.shadow-sm.rounded {
            transition: box-shadow 0.3s ease-in-out;
        }

        .card.shadow-sm.mb-4.rounded:hover,
        .table-responsive.shadow-sm.rounded:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        /* Button Hover Effects */
        .btn-primary.rounded-pill.shadow-sm:hover,
        .btn-outline-secondary.rounded-pill.shadow-sm:hover {
            opacity: 0.9;
            transform: scale(1.03);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-outline-primary.rounded-pill.px-3:hover,
        .btn-outline-danger.rounded-pill.px-3:hover,
        .btn-info.rounded-pill.px-3:hover {
            opacity: 1;
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Table Row Hover Effect */
        .table-responsive > .table > tbody > tr:hover {
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
            background-color: #f8fafa !important; /* or another light background */
        }

        .table-responsive > .table > tbody > tr {
            transition: box-shadow 0.3s ease-in-out, transform 0.3s ease-in-out, background-color 0.3s ease-in-out;
        }
        /* Eye Icon Style */
        .position-relative .fas.fa-eye {
            opacity: 0; /* Initially hidden */
            transition: opacity 0.3s ease-in-out; /* Smooth transition for opacity */
        }

        .position-relative:hover .fas.fa-eye {
            opacity: 0.7; /* Show icon on hover */
        }
    </style>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.26/dist/sweetalert2.all.min.js"></script>
    <script>
        // Tidak ada script JavaScript yang diperlukan lagi untuk delete dan deactivate button
    </script>
@endsection