@extends('layout.student')

@section('content')
    <div class="container" style="padding: 20px; max-width: 400px; margin: auto; text-align: center; font-family: Arial, sans-serif;">
        <div class="card shadow" style="border-radius: 15px; overflow: hidden; background: #ffffff; padding: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
            <h2 style="color: #4A4A4A; font-weight: bold; margin-bottom: 20px;">{{ __('Check-Out') }} QR Code</h2>

            <!-- QR Code Container with White Background -->
            <div id="qr-code-container" style="background-color: white; padding: 20px; border-radius: 10px;">
                <img src="data:image/png;base64,{{ $qrCode }}" alt="{{ __('Check-Out') }} QR Code" style="width: 200px; height: 200px; border-radius: 10px;">
            </div>

            <!-- Countdown Timer -->
            <p style="color: #ec3f3f; font-size: 18px; margin-top: 10px;">
                <strong>Time Remaining:</strong> <span id="countdown">00:10</span>
            </p>

            <!-- Additional Instructions and Status Display -->
            <div style="margin-top: 15px; font-size: 13px; color: #1f2937; background-color: #e9ecef; padding: 10px; border-radius: 8px;">
                <p style="margin: 0;"><strong>Status:</strong> <span id="status">{{ __('Active') }}</span></p>
                <p style="margin: 0;">This QR code is valid for <strong>{{ __('Check-Out') }}</strong> only.</p>
                <p style="margin: 0;">You may generate a new QR code after it expires.</p>
            </div>

            <!-- Go Back Button -->
            <button onclick="window.history.back()" style="display: inline-block; margin-top: 20px; padding: 10px 20px; background-color: #007bff; color: #fff; border-radius: 5px; text-decoration: none; font-weight: bold; transition: background-color 0.3s; border: none; cursor: pointer;">
                Go Back
            </button>

{{--            <!-- Refresh Button -->--}}
{{--            <button onclick="window.location.reload()" style="display: inline-block; margin-top: 20px; padding: 10px 20px; background-color: #28a745; color: #fff; border-radius: 5px; text-decoration: none; font-weight: bold; transition: background-color 0.3s; border: none; cursor: pointer;">--}}
{{--                Refresh Page--}}
{{--            </button>--}}
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
    .shadow {
        box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.15);
    }
    button:hover {
        background-color: #0056b3;
    }
    #refresh-button {
        position: fixed;
        bottom: 20px;
        right: 20px;
        padding: 10px 20px;
        background-color: #28a745;
        color: #fff;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
        transition: background-color 0.3s;
        border: none;
        cursor: pointer;
    }
    #refresh-button:hover {
        background-color: #218838;
    }
</style>

<script>
    // Get the expiration time from the blade template and convert it to a JavaScript Date object
    const expirationTime = new Date("{{ $expiresAt }}").getTime();

    function updateCountdown() {
        const currentTime = new Date().getTime();
        const remainingTime = expirationTime - currentTime;

        if (remainingTime <= 0) {
            document.getElementById("countdown").innerHTML = "Expired";
            document.getElementById("status").innerHTML = "Expired";
            clearInterval(interval);

            // Auto-refresh the page after 1 second to generate a new QR code
            setTimeout(() => {
                window.location.reload();
            }, 1000);
            return;
        }

        const minutes = Math.floor((remainingTime % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);

        // Format the time display
        document.getElementById("countdown").innerHTML = `${minutes < 10 ? '0' + minutes : minutes}:${seconds < 10 ? '0' + seconds : seconds}`;
    }

    // Update the countdown every second
    let interval = setInterval(updateCountdown, 1000);

    // Initialize countdown
    updateCountdown();
</script>

<script>
    function checkActiveCheckoutToken() {
        fetch("{{ route('student.qrcode.checkActiveCheckoutToken') }}", {
            method: "GET",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'active') {
                    // Masih aktif => tetap di halaman
                    console.log("Token is still active, stay on page.");
                } else if (data.status === 'inactive') {
                    // Sudah tidak aktif => arahkan ke success
                    window.location.href = "{{ route('student.attendance.success') }}";
                }
            })
            .catch(error => {
                console.error("Error checking active checkout token:", error);
            });
    }

    // Cek status setiap 1.5 detik
    setInterval(checkActiveCheckoutToken, 1500);
</script>

<script>
    // Deteksi mode gelap dan sesuaikan tampilan QR code
    const isDarkMode = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;

    if (isDarkMode) {
        // Tambahkan pesan untuk mode gelap
        const warningMessage = document.createElement('p');
        warningMessage.style.color = 'red';
        warningMessage.style.marginTop = '10px';
        warningMessage.innerHTML = 'Switch to light mode for better QR code scanning.';
        document.querySelector('.card').appendChild(warningMessage);

        // Pastikan latar belakang QR code tetap putih
        document.getElementById('qr-code-container').style.backgroundColor = 'white';
    }
</script>