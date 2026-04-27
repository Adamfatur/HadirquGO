<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spin Reward</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* Spin Wheel Styling */
        .spin-wheel {
            position: relative;
            width: 300px;
            height: 300px;
            margin: 0 auto;
            border: 10px solid #4a90e2;
            border-radius: 50%;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(74, 144, 226, 0.5);
            background: linear-gradient(45deg, #4a90e2, #6bb9f0);
        }

        .wheel {
            width: 100%;
            height: 100%;
            position: relative;
            transform: rotate(0deg);
            transition: transform 3s ease-out;
        }

        .wheel-item {
            position: absolute;
            width: 100%;
            height: 100%;
            clip-path: polygon(50% 50%, 100% 0, 100% 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transform-origin: 50% 50%;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            font-size: 14px;
            text-align: center;
        }

        .wheel-item .reward-icon {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .wheel-item .reward-name {
            font-weight: bold;
        }

        /* Spin Button Styling */
        .btn-spin {
            font-size: 20px;
            padding: 15px 30px;
            border-radius: 50px;
            background: linear-gradient(45deg, #4a90e2, #6bb9f0);
            border: none;
            box-shadow: 0 4px 15px rgba(74, 144, 226, 0.6);
            transition: all 0.3s ease;
        }

        .btn-spin:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(74, 144, 226, 0.8);
        }

        /* Result Display Styling */
        #result {
            font-size: 24px;
            font-weight: bold;
            color: #4a90e2;
            text-shadow: 0 0 10px rgba(74, 144, 226, 0.7);
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container text-center mt-5">
    <h1>🎮 SPIN TO WIN 🎮</h1>
    <p>Welcome, {{ $user->name }}! Spin the wheel and claim your rewards!</p>

    <!-- Spin Wheel -->
    <div class="spin-wheel mb-4">
        <div id="wheel" class="wheel">
            @foreach($rewards as $reward)
                <div class="wheel-item" data-reward="{{ $reward->name }}" data-probability="{{ $reward->probability }}">
                    <span class="reward-icon">🎁</span>
                    <span class="reward-name">{{ $reward->name }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Spin Button -->
    <button id="spin-button" class="btn btn-spin">
        <i class="fas fa-sync-alt"></i> Spin Now!
    </button>

    <!-- Result Display -->
    <div id="result" class="mt-4"></div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const spinButton = document.getElementById('spin-button');
        const wheel = document.getElementById('wheel');
        const resultDiv = document.getElementById('result');

        if (!spinButton || !wheel || !resultDiv) {
            console.error("One or more elements are missing!");
            return;
        }

        spinButton.addEventListener('click', function () {
            console.log("Spin button clicked!"); // Debugging

            // Disable button during spin
            spinButton.disabled = true;
            spinButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Spinning...';

            // Fetch API to process spin
            fetch("{{ route('lecturer.reward.processSpin', $user->id) }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok");
                    }
                    return response.json();
                })
                .then(data => {
                    console.log("Spin result:", data); // Debugging

                    if (data.success) {
                        // Calculate rotation based on reward probability
                        const rewards = {!! json_encode($rewards) !!};
                        const selectedReward = rewards.find(reward => reward.id === data.reward.id);
                        const rewardIndex = rewards.indexOf(selectedReward);
                        const rotation = 360 * 5 + (rewardIndex * (360 / rewards.length));

                        // Apply rotation to wheel
                        wheel.style.transform = `rotate(${rotation}deg)`;

                        // Show result after spin animation
                        setTimeout(() => {
                            resultDiv.innerHTML = `
                                <div class="alert alert-success">
                                    🎉 Congratulations! You won: <strong>${data.reward.name}</strong> 🎉
                                </div>
                            `;
                            spinButton.disabled = false;
                            spinButton.innerHTML = '<i class="fas fa-sync-alt"></i> Spin Again!';
                        }, 3000);
                    } else {
                        resultDiv.innerHTML = `
                            <div class="alert alert-danger">
                                ${data.message}
                            </div>
                        `;
                        spinButton.disabled = false;
                        spinButton.innerHTML = '<i class="fas fa-sync-alt"></i> Spin Again!';
                    }
                })
                .catch(error => {
                    console.error("Error during spin:", error); // Debugging
                    resultDiv.innerHTML = `
                        <div class="alert alert-danger">
                            An error occurred while processing your spin.
                        </div>
                    `;
                    spinButton.disabled = false;
                    spinButton.innerHTML = '<i class="fas fa-sync-alt"></i> Spin Again!';
                });
        });
    });
</script>
</body>
</html>