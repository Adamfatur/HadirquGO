@php
$rolePrefix = strtolower(Auth::user()->role) === 'lecturer' ? 'lecturer' : 'student';

// Define menu items based on role
$menuItems = [
    [
        'route' => $rolePrefix . '.points.index',
        'icon' => 'fas fa-star',
        'label' => __('Points'),
        'active' => true
    ],
    [
        'route' => $rolePrefix . '.viewboard.top-levels',
        'icon' => 'fas fa-trophy',
        'label' => __('Leaderboard'),
        'active' => true
    ],
    [
        'route' => $rolePrefix . '.attendance.stats',
        'params' => ['memberId' => Auth::user()->member_id],
        'icon' => 'fas fa-crown',
        'label' => __('Journey'),
        'active' => true
    ],
    [
        'route' => $rolePrefix . '.redeem.index',
        'icon' => 'fas fa-gift',
        'label' => __('Redeem Points'),
        'active' => true
    ]
];

if (strtolower(Auth::user()->role) === 'lecturer') {
    $menuItems[] = [
        'route' => 'lecturer.users.index',
        'icon' => 'fas fa-users',
        'label' => __('Users'),
        'active' => true
    ];
    $menuItems[] = [
        'route' => 'lecturer.teamsrank.index',
        'icon' => 'fas fa-list-ol',
        'label' => __('Team Ranking'),
        'active' => true
    ];
} else {
    $menuItems[] = [
        'route' => 'student.teams.index',
        'icon' => 'fas fa-users',
        'label' => __('Teams'),
        'active' => true
    ];
}
@endphp

<div class="card shadow-sm animate-card mb-4"
     style="border-radius: 15px; padding: 20px;
            background-color: rgba(255, 255, 255, 0.9); border: none;">
    <div class="card-body">
        <div class="container text-center">
            <div class="row row-cols-3 row-cols-md-6 g-3 justify-content-center">
                @foreach($menuItems as $item)
                    @if($item['active'])
                        <div class="col">
                            <a href="{{ route($item['route'], $item['params'] ?? []) }}" class="text-decoration-none d-flex flex-column align-items-center animate-icon">
                                <div class="icon-circle bg-white d-flex justify-content-center align-items-center" style="width: 60px; height: 60px; border-radius: 50%; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);">
                                    <i class="{{ $item['icon'] }} fa-lg" style="color: #1e3a8a;"></i>
                                </div>
                                <span class="small mt-2 text-dark fw-bold" style="font-size: 0.75rem;">{{ $item['label'] }}</span>
                            </a>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
