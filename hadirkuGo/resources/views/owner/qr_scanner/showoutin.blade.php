<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Scanner - HadirquGO</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        html, body {
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(to right, #1e3a8a, #2563eb);
            margin: 0;
            padding: 0;
            height: 100vh;
            color: #333;
        }

        .scanner-app-container {
            max-width: 100%;
            margin: 0 auto;
            padding: 20px;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .header {
            text-align: center;
            color: #fff;
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .location-info {
            text-align: center;
            color: #fff;
            margin-bottom: 20px;
        }

        .location-info h2 {
            font-size: 1.8rem;
            margin-bottom: 10px;
        }

        .qr-reader-container {
            width: 100%;
            height: 100%;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            position: relative;
        }

        #reader {
            width: 100%;
            height: 100%;
            position: relative;
        }

        #reader video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        #switchCameraButton {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10;
            background: #2563eb;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s ease;
        }

        #switchCameraButton:hover {
            background: #1e3a8a;
        }

        /* Modal Styles */
        .modal-content {
            border-radius: 15px;
            background: linear-gradient(145deg, #ffffff, #f0f4ff);
            padding: 30px;
            text-align: center;
            border: none;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-icon {
            font-size: 5rem;
            margin-bottom: 20px;
            color: #2563eb;
        }

        .modal-icon.success {
            color: #28a745;
        }

        .modal-icon.error {
            color: #dc3545;
        }

        .modal-icon.warning {
            color: #ffc107;
        }

        .modal-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 20px auto;
            object-fit: cover;
            border: 3px solid #2563eb;
            transition: transform 0.3s ease, border-color 0.3s ease;
        }

        .modal-avatar:hover {
            transform: scale(1.1);
            border-color: #1e3a8a;
        }

        .modal-message {
            font-size: 1.3rem;
            color: #333;
            margin-top: 20px;
            font-weight: 600;
        }

        .modal-footer {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .modal-footer button {
            padding: 10px 20px;
            border-radius: 25px;
            border: none;
            background: #2563eb;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .modal-footer button:hover {
            background: #1e3a8a;
        }
    </style>
</head>
<body>
<div class="scanner-app-container">
    <!-- Header -->
    <div class="header">
        <i class="fas fa-qrcode"></i> HadirquGO
    </div>

    <!-- Location Info -->
    <div class="location-info">
        <h5>Location:</h5>
        <h2>{{ $location->name }}</h2>
        <p class="text-light">
            Tips: Aim your camera at the QR code and ensure sufficient lighting for optimal results.
        </p>
    </div>

    <!-- QR Reader -->
    <div class="qr-reader-container">
        <div id="reader"></div>
        <button id="switchCameraButton">Switch Camera</button>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <i id="statusModalIcon" class="modal-icon"></i>
            <img id="statusModalAvatar" class="modal-avatar" src="https://via.placeholder.com/100" alt="User Avatar">
            <p id="statusModalMessage" class="modal-message"></p>
            <div class="modal-footer">
                <button type="button" class="btn-close-modal" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
<script>
    const qrCodeScanner = new Html5Qrcode("reader");
    let currentCameraId = null;
    let cameras = [];
    let isBackCamera = true;
    let scanningEnabled = true;

    function startScanner(cameraId = null) {
        const config = cameraId ? { deviceId: { exact: cameraId } } : { facingMode: "environment" };

        qrCodeScanner.start(
            config,
            { fps: 5, formatsToSupport: [Html5QrcodeSupportedFormats.QR_CODE] }, // Mengurangi fps
            qrCodeMessage => {
                if (scanningEnabled) {
                    scanningEnabled = false;
                    processAttendance(qrCodeMessage);
                    setTimeout(() => scanningEnabled = true, 3000);
                }
            },
            errorMessage => {
                // Mengurangi logging yang tidak perlu
                if (errorMessage !== "QR code parse error, error = No barcode or QR code detected.") {
                    console.warn(`QR Code Error: ${errorMessage}`);
                }
            }
        ).catch(err => console.error(`Scanner error: ${err}`));
    }

    function stopScanner() {
        return qrCodeScanner.stop().then(() => {
            console.log("QR Code scanning stopped.");
        }).catch(err => {
            console.error("Failed to stop scanning.", err);
        });
    }

    function switchCamera() {
        stopScanner().then(() => {
            isBackCamera = !isBackCamera;
            const camera = cameras[isBackCamera ? 0 : 1];
            currentCameraId = camera.id;
            startScanner(currentCameraId);
        }).catch(err => {
            console.error("Failed to stop scanner before switching camera.", err);
        });
    }

    function getCameras() {
        Html5Qrcode.getCameras().then(devices => {
            if (devices && devices.length > 0) {
                cameras = devices;
                currentCameraId = devices[0].id;
                startScanner(currentCameraId);

                // Sembunyikan tombol jika hanya ada satu kamera
                if (devices.length === 1) {
                    document.getElementById("switchCameraButton").style.display = "none";
                }
            } else {
                console.error("No cameras found.");
            }
        }).catch(err => {
            console.error("Failed to get cameras.", err);
        });
    }

    document.addEventListener("DOMContentLoaded", () => {
        getCameras();
        document.getElementById("switchCameraButton").addEventListener("click", switchCamera);
    });

    function processAttendance(qrCodeMessage) {
        console.log("[DEBUG] QR Code scanned:", qrCodeMessage);

        const endpoint = `/api/scanner/{{ $location->id }}/processCheckoutCheckin`;
        const requestData = {
            qrCode: qrCodeMessage
        };

        fetch(endpoint, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            body: JSON.stringify(requestData)
        })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(html => {
                        throw new Error(`Server returned HTML: ${html}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                const modalIcon = document.getElementById("statusModalIcon");
                const modalMessage = document.getElementById("statusModalMessage");
                const modalAvatar = document.getElementById("statusModalAvatar");
                const modal = new bootstrap.Modal(document.getElementById('statusModal'));

                modalIcon.className = "modal-icon";
                modalAvatar.src = data.user_avatar || "https://via.placeholder.com/100";

                if (data.status === "checked_in_checked_out") {
                    modalIcon.classList.add("success", "fas", "fa-check-circle");
                    modalMessage.textContent = `Goodbye and Welcome back, ${data.user_name}! ${data.message}`;
                } else {
                    modalIcon.classList.add("error", "fas", "fa-times-circle");
                    modalMessage.textContent = data.message || "An error occurred.";
                }

                modal.show();
                setTimeout(() => modal.hide(), 2500);
            })
            .catch(error => {
                console.error("[DEBUG] Attendance error:", error);

                const modalIcon = document.getElementById("statusModalIcon");
                const modalMessage = document.getElementById("statusModalMessage");
                const modal = new bootstrap.Modal(document.getElementById('statusModal'));

                modalIcon.className = "modal-icon error fas fa-times-circle";
                modalMessage.textContent = "Failed to process attendance. Please try again.";
                modal.show();
                setTimeout(() => modal.hide(), 2500);
            });
    }

    // Menghentikan pemindaian saat modal ditampilkan
    document.getElementById('statusModal').addEventListener('show.bs.modal', function () {
        stopScanner();
    });

    // Memulai kembali pemindaian saat modal disembunyikan
    document.getElementById('statusModal').addEventListener('hidden.bs.modal', function () {
        startScanner(currentCameraId);
    });
</script>
</body>
</html>