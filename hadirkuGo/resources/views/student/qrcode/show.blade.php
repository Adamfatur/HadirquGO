<!-- resources/views/student/qrcode/show.blade.php -->

@extends('layout.student')

@section('content')
    <div class="container" style="padding: 20px; max-width: 400px; margin: auto; text-align: center; font-family: Arial, sans-serif;">
        <div class="card shadow" style="border-radius: 15px; overflow: hidden; background: #ffffff; padding: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
            <h2 style="color: #4A4A4A; font-weight: bold; margin-bottom: 20px;">Check-in QR Codexssss</h2>

            <div style="background-color: #f8f9fa; padding: 20px; border-radius: 10px;">
                <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code" style="width: 200px; height: 200px; border-radius: 10px; box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);">
            </div>

            <!-- Expiration Time with Modern Styling -->
            <p style="color: #6c757d; font-size: 16px; margin-top: 15px;">
                <strong style="color: #ff6b6b;">Expires:</strong> {{ $expiresAt }} (Jakarta Time)
            </p>

            <p style="color: #4A4A4A; font-size: 14px; margin-top: 15px; line-height: 1.6;">
                Ensure this QR code is clearly visible for a smooth check-in process.
                If the code expires, please generate a new one.
            </p>

            <!-- Button with a Modern Style -->
            <button onclick="window.history.back()" style="display: inline-block; margin-top: 20px; padding: 10px 20px; background-color: #007bff; color: #fff; border-radius: 5px; text-decoration: none; font-weight: bold; transition: background-color 0.3s; border: none; cursor: pointer;">
                Go Back
            </button>
        </div>
    </div>
@endsection

<style>
    .container {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .card {
        background-color: #f9f9f9;
    }
    .btn-back:hover {
        background-color: #0056b3;
    }
    /* Add a shadow to make the card pop */
    .shadow {
        box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.15);
    }
    /* Add some custom styling for the button */
    button:hover {
        background-color: #0056b3;
    }
</style>
