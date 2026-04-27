<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Login - Hadirkugo</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            font-family: 'Arial', sans-serif;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
            background-color: #ffffff;
        }
        .card-header {
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            color: #ffffff;
            text-align: center;
            padding: 20px;
        }
        .card-header img {
            max-height: 60px;
            margin-bottom: 10px;
        }
        .card-body {
            padding: 30px;
        }
        .card-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
        }
        .card-text {
            font-size: 16px;
            color: #666;
            margin-bottom: 20px;
        }
        .btn-verify {
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            border: none;
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 16px;
            width: 100%;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .btn-verify:hover {
            background: linear-gradient(135deg, #0072ff, #00c6ff);
        }
        .footer-text {
            font-size: 14px;
            color: #666;
            text-align: center;
            margin-top: 20px;
        }
        .footer-text a {
            color: #00c6ff;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="card">
    <!-- Card Header -->
    <div class="card-header">
        <img src="https://drive.pastibisa.app/1731550618_67355d9aecbf5.png" alt="Hadirkugo Logo">
        <h3>Verify Your Login</h3>
    </div>

    <!-- Card Body -->
    <div class="card-body">
        <h5 class="card-title">Hello, {{ $user->name }}!</h5>
        <p class="card-text">
            A login attempt was detected from a new device. Please verify this action to continue.
        </p>

        <!-- Device Details -->
        <div class="mb-4">
            <p><strong>Device:</strong> {{ $device->device_type }}</p>
            <p><strong>Platform:</strong> {{ $device->platform }}</p>
            <p><strong>Browser:</strong> {{ $device->browser }}</p>
        </div>

        <!-- Verify Button -->
        <button class="btn-verify" onclick="verifyDevice()">Verify Login</button>

        <!-- Footer -->
        <div class="footer-text">
            If this wasn't you, <a href="mailto:mail.hadirkugo@alphabetincubator.id">contact us</a> immediately.
        </div>
    </div>
</div>

<!-- Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

<!-- Custom JS -->
<script>
    function verifyDevice() {
        // Redirect to verification URL
        window.location.href = "{{ $verificationUrl }}";
    }
</script>
</body>
</html>