@extends('layout.owner')

@section('title', 'Owner Dashboard')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h1 class="display-6 fw-bold text-primary mb-3">Welcome, {{ Auth::user()->name }}</h1>
                <p class="text-muted">This is your dashboard, where you can manage your businesses, view reports, and customize settings for your account.</p>
            </div>
        </div>

        <!-- Dashboard Cards Section -->
        <div class="row mt-4">
            <!-- Businesses Managed Card -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0" style="border-radius: 10px;">
                    <div class="card-body text-center">
                        <i class="fas fa-briefcase fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Manage Businesses</h5>
                        <p class="card-text">View, add, or edit the businesses you own.</p>
                        <a href="{{ route('owner.businesses.index') }}" class="btn btn-outline-primary w-100" style="border-radius: 8px;">Go to Businesses</a>
                    </div>
                </div>
            </div>

            <!-- Reports Card -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0" style="border-radius: 10px;">
                    <div class="card-body text-center">
                        <i class="fas fa-chart-line fa-3x text-success mb-3"></i>
                        <h5 class="card-title">View Reports</h5>
                        <p class="card-text">Analyze the performance of your businesses.</p>
                        <a href="#" class="btn btn-outline-success w-100" style="border-radius: 8px;">View Reports</a>
                    </div>
                </div>
            </div>

            <!-- Settings Card -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0" style="border-radius: 10px;">
                    <div class="card-body text-center">
                        <i class="fas fa-cogs fa-3x text-info mb-3"></i>
                        <h5 class="card-title">Account Settings</h5>
                        <p class="card-text">Customize your profile and account settings.</p>
                        <a href="#" class="btn btn-outline-info w-100" style="border-radius: 8px;">Go to Settings</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
