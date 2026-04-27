<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Congratulations! You Are Today's MVP!</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Arial', sans-serif; background-color: #f4f4f4; color: #333;">

<!-- Main Wrapper -->
<table style="width: 100%; background-color: #f4f4f4; padding: 20px;">
    <tr>
        <td align="center">
            <!-- Main Container -->
            <table style="max-width: 600px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); border: 1px solid #eaeaea;">

                <!-- Header with Gradient -->
                <tr>
                    <td style="background: linear-gradient(135deg, #00c6ff, #0072ff); padding: 20px; text-align: center;">
                        <h1 style="font-size: 24px; font-weight: bold; color: #ffffff; margin: 0;">🎉 Congratulations! You Are Today's MVP! 🎉</h1>
                    </td>
                </tr>

                <!-- Content -->
                <tr>
                    <td style="padding: 30px;">
                        <p style="font-size: 18px; line-height: 1.6; margin-bottom: 20px; color: #333;">
                            Hi <strong>{{ $user->name }}</strong>,
                        </p>
                        <p style="font-size: 18px; line-height: 1.6; margin-bottom: 20px; color: #333;">
                            You are the <strong style="color: #00c6ff;">{{ $metric }} MVP</strong> for the <strong>{{ $period }}</strong> period! 🏆
                        </p>
                        <p style="font-size: 18px; line-height: 1.6; margin-bottom: 30px; color: #333;">
                            Keep up the amazing work and continue to shine! 🌟
                        </p>

                        <p style="font-size: 16px; line-height: 1.6; color: #666;">
                            If you have any questions, feel free to reach out to us. Keep being awesome! 😎
                        </p>
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="background-color: #f9f9f9; text-align: center; padding: 20px; color: #666;">
                        <p style="font-size: 14px; margin: 0;">&copy; {{ date('Y') }} YourAppName. All Rights Reserved.</p>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>

</body>
</html>