<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Scanner Login - HadirquGO</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Global Styles */
        html, body {
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(to right, #1e3a8a, #2563eb);
            margin: 0;
            padding: 0;
            height: 100vh;
            color: #333;
        }

        .login-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        /* Header */
        .header {
            position: absolute;
            top: 20px;
            width: 100%;
            text-align: center;
            color: #fff;
            font-size: 2em;
            font-weight: 600;
            z-index: 1;
        }

        .header i {
            margin-right: 10px;
        }

        /* Card Login */
        .login-card {
            background: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 450px;
            text-align: center;
            /* Fade-in animation */
            opacity: 0;
            transform: translateY(50px);
            transition: all 0.5s ease;
        }

        .login-card.active {
            opacity: 1;
            transform: translateY(0);
        }

        .card-header h4 {
            margin-bottom: 30px;
            color: #1e3a8a;
        }

        .form-group {
            margin-bottom: 20px;
            width: 100%;
            max-width: 350px;
            margin-left: auto;
            margin-right: auto;
        }

        .form-control {
            border: none;
            border-radius: 8px;
            padding: 12px;
            background: #f2f2f2;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control:focus {
            background: #e6e6e6;
            box-shadow: 0 3px 6px rgba(0,0,0,0.15);
        }

        .login-btn {
            background-color: #1e3a8a;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 12px 24px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
            width: 100%;
            max-width: 200px;
        }

        .login-btn:hover {
            background-color: #1c357c;
            transform: scale(1.02);
        }

        .footer-text {
            margin-top: 20px;
            color: #6b7280;
            font-size: 0.9em;
        }

        .footer-text a {
            color: #1e3a8a;
            text-decoration: none;
        }

        .footer-text a:hover {
            text-decoration: underline;
        }

        /* Error Message */
        .error-message {
            opacity: 0;
            transition: opacity 0.3s ease;
            margin-bottom: 20px;
        }

        .error-message.show {
            opacity: 1;
        }

        /* Reduced Motion */
        @media (prefers-reduced-motion: reduce) {
            .login-card,
            .login-btn,
            .form-control,
            .error-message {
                transition: none !important;
            }
        }
    </style>
</head>
<body>
<div class="login-container">
    <!-- Header -->
    <div class="header">
        <i class="fas fa-qrcode"></i> HadirquGO
    </div>

    <!-- Card Login -->
    <div class="login-card active">
        <div class="card-header">
            <h4>QR Scanner Login</h4>
        </div>
        <form action="{{ route('qr_scanner.login_process') }}" method="POST" id="login-form">
            @csrf
            <!-- Error Message -->
            @if ($errors->any())
                <div class="error-message show">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="form-group">
                <label for="unique_id" class="form-label">Unique ID</label>
                <input type="text" class="form-control" id="unique_id" name="unique_id" placeholder="Enter your Unique ID" required>
            </div>
            <button type="submit" class="login-btn" id="login-btn">
                <span class="button-text">Login</span>
                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
            </button>
        </form>
        <div class="footer-text">
            <p>&copy; {{ date('Y') }} HadirquGO. All rights reserved.</p>
        </div>
    </div>
</div>

<script>
    // Show error message when there are errors
    var errorMessages = @json($errors->all());
    if (errorMessages.length > 0) {
        document.querySelector('.error-message').classList.add('show');
    }

    // Show loading indicator on form submission
    document.getElementById('login-form').addEventListener('submit', function() {
        var button = document.getElementById('login-btn');
        var buttonText = button.querySelector('.button-text');
        var spinner = button.querySelector('.spinner-border');

        buttonText.style.display = 'none';
        spinner.classList.remove('d-none');
    });
</script>
</body>
</html>