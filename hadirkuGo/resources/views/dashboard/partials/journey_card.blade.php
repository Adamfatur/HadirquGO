<div class="card shadow-sm mb-4 animate-card"
     style="background: linear-gradient(135deg, #4e54c8, #8f94fb);
    border-radius: 15px; padding: 20px; color: white; border: none;">
    <div class="card-body">
        <!-- Header -->
        <div class="text-center mb-3">
            <h5 class="fw-bold">{{ Auth::user()->name }}'s Journey</h5>
        </div>

        <!-- Motivational Message -->
        <div class="text-center">
            <p class="text-light">
                <em>"Every step counts in your journey."</em><br>
                Discover your progress and achievements by exploring your attendance stats!
            </p>
        </div>

        <!-- Button to View Stats -->
        <div class="text-center mt-4">
            <a href="{{ route('student.attendance.stats', ['memberId' => Auth::user()->member_id]) }}"
               class="btn btn-warning btn-sm fw-bold"
               style="border-radius: 15px;">
                {{ __('View My Journey') }}
            </a>
        </div>
    </div>
</div>
