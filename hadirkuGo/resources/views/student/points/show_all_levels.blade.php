@extends('layout.student')

@section('content')
    <div class="container my-5" style="max-width: 800px;">
        <div class="card shadow-sm border-0" style="border-radius: 15px; overflow: hidden;">
            <div class="card-header text-center" style="background: linear-gradient(135deg, #1e3a8a, #3b82f6); color: white;">
                <h3 class="mb-0">All Levels</h3>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @foreach($allLevels as $level)
                        <li class="list-group-item d-flex justify-content-between align-items-center hover-effect"
                            style="background: #f9fafb; transition: background 0.3s;">
                            <div>
                                <h5 class="fw-bold">{{ $level->name }}</h5>
                                <p class="mb-1">{{ $level->minimum_points }} - {{ $level->maximum_points }} points</p>
                                <p class="mb-0">{{ $level->description }}</p>
                            </div>
                            @if($level->minimum_points > $totalPoints)
                                <span class="badge bg-secondary">{{ __('Locked') }}</span>
                            @else
                                <span class="badge bg-success">{{ __('Unlocked') }}</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection