@extends('layout.student')

@section('title', 'My Teams')

@section('content')
    <div class="container mt-4">
        <h1 class="fw-bold" style="color: #14274e;">Teams You're Part Of</h1>

        <!-- Display Success or Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            @forelse($teamsJoined as $team)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card shadow-sm" style="border-radius: 10px;">
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-1" style="color: #14274e;">{{ $team->name }}</h5>
                            <p class="text-muted small">
                                <i class="fas fa-id-badge me-1"></i>Team ID: {{ $team->team_unique_id }}<br>
                                <i class="fas fa-user-tie me-1"></i>Leader: {{ $team->leader->name }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-muted">You are currently not part of any team.</p>
            @endforelse
        </div>
    </div>
@endsection
