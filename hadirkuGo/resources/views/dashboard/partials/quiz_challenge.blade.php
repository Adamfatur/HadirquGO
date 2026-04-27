@php
$rolePrefix = Auth::user()->hasRole('Lecturer') ? 'lecturer' : 'student';
@endphp
<div class="card shadow-sm mb-4 animate-card"
     style="background: linear-gradient(135deg, #f09819, #ff512f);
    border-radius: 15px; padding: 20px; color: white; border: none;">
    <div class="card-body">
        <div class="text-center mb-3">
            <h5 class="fw-bold">Fun Quiz Challenge</h5>
        </div>
        <div class="text-center">
            <p class="text-light">
                <em>"Challenge yourself and earn up to 30 bonus points!"</em><br>
                Explore our exciting quizzes, test your knowledge, and get rewarded!
            </p>
        </div>
        <div class="text-center mt-4">
            <a href="{{ route($rolePrefix . '.quizzes.index') }}"
               class="btn btn-warning btn-sm fw-bold"
               style="border-radius: 15px;">
                Take Quiz Now
            </a>
        </div>
    </div>
</div>
