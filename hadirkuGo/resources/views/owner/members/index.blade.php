@extends('layout.owner')

@section('title', 'Business Members List')
@section('page_title', 'Business Members: ' . $business->name)

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="..." crossorigin="anonymous" referrerpolicy="no-referrer" />

    <div class="container-fluid">
        <div class="card shadow mb-4 rounded" style="border-radius: 0.75rem; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.07); transition: box-shadow 0.3s ease-in-out;">
            <div class="card-header py-3 bg-primary text-white rounded-top d-flex justify-content-between align-items-center" style="background-color: #007bff !important; border-radius: 0.75rem 0.75rem 0 0;">
                <h6 class="m-0 font-weight-bold" style="font-size: 1.1rem;">
                    <i class="fas fa-users me-2"></i> Business Members List
                </h6>
                <div class="d-flex align-items-center"> {{-- Bungkus form dan tombol clear filter dalam div flex --}}
                    <form action="{{ route('owner.member.index', $business->business_unique_id) }}" method="GET" class="me-2"> {{-- Tambah margin kanan pada form --}}
                        <div class="input-group">
                            <input type="text" class="form-control form-control-sm" placeholder="Search members..." name="search" value="{{ $searchTerm ?? '' }}">
                            <button class="btn btn-sm btn-light" type="submit" id="button-search">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                    @if($searchTerm) {{-- Tampilkan tombol Clear Filter hanya jika ada searchTerm --}}
                    <a href="{{ route('owner.member.index', $business->business_unique_id) }}" class="btn btn-sm btn-secondary"> {{-- Link untuk Clear Filter --}}
                        <i class="fas fa-times"></i> Clear Filter
                    </a>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    Total Members: <span class="font-weight-bold">{{ $totalUsersCount }}</span>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse ($users as $user)
                        <li class="list-group-item py-3 d-flex align-items-center" style="border-left: none; border-right: none; transition: background-color 0.2s ease-in-out, transform 0.2s ease-in-out, box-shadow: 0.2s ease-in-out;">
                            <div class="me-4">
                                <img src="{{ $user['avatar'] }}" alt="{{ $user['name'] }}" class="rounded-circle shadow-sm" width="50" height="50" style="object-fit: cover;" onerror="this.src='{{ asset('images/default.jpg') }}'; this.onerror=null;">
                            </div>
                            <div class="d-flex flex-column">
                                <h6 class="font-weight-bold mb-1" style="color: #333;">{{ $user['name'] }}</h6>
                                <p class="mb-1 text-muted" style="font-size: 0.9rem;">
                                    <i class="fas fa-envelope fa-fw me-1"></i> <span class="ms-1">{{ $user['email'] }}</span>
                                </p>
                                <div style="font-size: 0.9rem;">
                                    @if ($user['is_team_member'])
                                        <i class="fas fa-user-friends fa-fw text-success me-1"></i> <span class="text-success ms-1">Team Member</span>
                                    @else
                                        <i class="fas fa-user-slash fa-fw text-secondary me-1"></i> <span class="text-secondary ms-1">Not Team Member</span>
                                    @endif
                                    @if ($user['is_team_manager'])
                                        <span class="ms-3"><i class="fas fa-user-shield fa-fw text-info me-1"></i> <span class="text-info ms-1">Team Manager</span></span>
                                    @endif
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item text-center py-4">
                            <div class="alert alert-info d-inline-flex align-items-center shadow-sm mb-0 rounded" role="alert" style="background-color: #e3f2fd; border-color: #bbdefb; color: #0a589b; border-radius: 0.5rem; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);">
                                <i class="fas fa-info-circle fa-2x me-3"></i>
                                <div class="fw-semibold">No members registered for this business.</div>
                            </div>
                        </li>
                    @endforelse
                </ul>

                <div class="mt-4 d-flex justify-content-center">
                    {{ $users->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .card.shadow.mb-4.rounded:hover {
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            }
            .list-group-flush > .list-group-item {
                border-bottom: 1px solid rgba(0, 0, 0, 0.07); /* Subtler border */
            }
            .list-group-flush > .list-group-item:last-child {
                border-bottom: 0; /* Remove border from the last item */
            }

            .list-group-item:hover {
                background-color: #f8f9fa;
                transform: scale(1.01);
                box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.05);
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('.card.shadow.mb-4.rounded').hover(function() {
                    $(this).css('box-shadow', '0 8px 16px rgba(0, 0, 0, 0.1)');
                }, function() {
                    $(this).css('box-shadow', '0 6px 12px rgba(0, 0, 0, 0.07)');
                });

                $('.list-group-item').hover(function() {
                    $(this).css({'backgroundColor': '#f8f9fa', 'transform': 'scale(1.01)', 'boxShadow': '0 0.25rem 0.5rem rgba(0, 0, 0, 0.05)'});
                }, function() {
                    $(this).css({'backgroundColor': 'white', 'transform': 'scale(1)', 'boxShadow': 'none'});
                });
            });
        </script>
    @endpush
@endsection