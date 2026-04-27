<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HadirkuGo - Smart Attendance</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F4F7FC;
        }
        .navbar {
            background: linear-gradient(135deg, #007AFF, #0052D4);
        }
        .navbar-menu a {
            color: white !important;
        }
        .navbar-item:hover {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
        }
        .box {
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .title {
            font-weight: 600;
        }
        .button.is-primary {
            background-color: #007AFF;
            border-radius: 8px;
        }
        .chart-container {
            width: 100%;
            max-width: 400px;
            margin: auto;
        }
    </style>
</head>
<body>
<nav class="navbar is-fixed-top" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
        <a class="navbar-item" href="#">
            <i class="fas fa-user-clock"></i> <strong class="ml-2">HadirkuGo</strong>
        </a>
    </div>
</nav>

<section class="section mt-6">
    <div class="container">
        <div class="columns is-multiline">
            <div class="column is-12">
                <div class="box has-background-white">
                    <h3 class="title is-4 has-text-primary">Tim: {{ $team->name }}</h3>
                    <p class="subtitle is-6">Pemimpin: {{ $leader->name }}</p>
                    <p>Manajer: @foreach ($managers as $manager) {{ $manager->name }} @endforeach</p>
                </div>
            </div>
            <div class="column is-12">
                <div class="box has-background-white">
                    <h5 class="title is-5 has-text-primary">Lama Waktu</h5>
                    <div class="chart-container">
                        <canvas id="durationChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('durationChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($sortedMemberDurations->pluck('user.name')),
            datasets: [{
                label: 'Lama Waktu (menit)',
                data: @json($sortedMemberDurations->pluck('total_duration')),
                backgroundColor: '#007AFF',
                borderRadius: 10,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
</body>
</html>
