@extends('layout.student')

@section('title', 'Student Attendance Dashboard')

@section('content')
    @include('dashboard.partials.rank_logic')

    <div class="container mt-4">
        <!-- Banners Section -->
        @include('dashboard.partials.banners')

        <!-- Elite Profile & Journey Header -->
        @include('dashboard.partials.profile_header')

        <!-- Attendance Actions ({{ __('Check-In') }}/Out) -->
        @include('dashboard.partials.attendance_actions')

        <!-- Main Navigation Menu -->
        @include('dashboard.partials.icon_menu')

        <!-- Rank & Rivalry Section -->
        @include('dashboard.partials.rank_rivalry')

        <!-- Quiz Challenge Section -->
        @include('dashboard.partials.quiz_challenge')

        <!-- Hall of Fame Section (Top Players & Highlights) -->
        @include('dashboard.partials.hall_of_fame')

        <!-- Performance Analytics & Notifications -->
        @include('dashboard.partials.performance_insights')

        <!-- Modals & "What is HadirkuGO" -->
        @include('dashboard.partials.modals')

    </div> <!-- /.container -->

    <!-- Footer Section -->
    <div class="text-center py-3" style="background-color: #1e3a8a; color: white; border-radius: 0 0 15px 15px;">
        <p class="mb-0 small">
            <a href="#" data-bs-toggle="modal" data-bs-target="#changelogModal" class="text-white text-decoration-none">
                HadirkuGO Version 1.5 &copy; {{ date('Y') }}. All rights reserved.
            </a>
        </p>
    </div>
@endsection

@push('scripts')
    @include('dashboard.partials.scripts')
@endpush
