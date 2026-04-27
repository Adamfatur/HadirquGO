<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Congratulations! You Have Leveled Up</title>
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
                        <img src="https://drive.pastibisa.app/1731550618_67355d9aecbf5.png" alt="Hadirkugo Logo" style="max-height: 80px; margin-bottom: 10px;">
                        <h1 style="font-size: 24px; font-weight: bold; color: #ffffff; margin: 0;">Congratulations! You Have Leveled Up</h1>
                    </td>
                </tr>

                <!-- Content -->
                <tr>
                    <td style="padding: 30px;">
                        <p style="font-size: 18px; line-height: 1.6; margin-bottom: 20px; color: #333;">
                            Hi <strong>{{ $user->name }}</strong>,
                        </p>
                        <p style="font-size: 18px; line-height: 1.6; margin-bottom: 20px; color: #333;">
                            Congratulations! You have leveled up to <strong style="color: #00c6ff;">{{ $level->name }}</strong>.
                        </p>
                        <p style="font-size: 18px; line-height: 1.6; margin-bottom: 30px; color: #333;">
                            Your current points are <strong style="color: #00c6ff;">{{ $totalPoints }}</strong>. Keep up the great work to reach even higher levels!
                        </p>

                        <!-- Summary Table -->
                        <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
                            <thead>
                            <tr>
                                <th style="padding: 12px; background-color: #00c6ff; color: #ffffff; text-align: left; border-bottom: 2px solid #eaeaea; border-radius: 6px 6px 0 0;">Details</th>
                                <th style="padding: 12px; background-color: #00c6ff; color: #ffffff; text-align: left; border-bottom: 2px solid #eaeaea; border-radius: 6px 6px 0 0;">Value</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td style="padding: 10px; border-bottom: 1px solid #eaeaea; color: #666; background-color: #f9f9f9;">Name</td>
                                <td style="padding: 10px; border-bottom: 1px solid #eaeaea; color: #333;">{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 10px; border-bottom: 1px solid #eaeaea; color: #666; background-color: #f9f9f9;">New Level</td>
                                <td style="padding: 10px; border-bottom: 1px solid #eaeaea; color: #333;">{{ $level->name }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 10px; border-bottom: 1px solid #eaeaea; color: #666; background-color: #f9f9f9;">Points Range</td>
                                <td style="padding: 10px; border-bottom: 1px solid #eaeaea; color: #333;">{{ $level->minimum_points }} - {{ $level->maximum_points }} pts</td>
                            </tr>
                            <tr>
                                <td style="padding: 10px; border-bottom: 1px solid #eaeaea; color: #666; background-color: #f9f9f9;">Current Points</td>
                                <td style="padding: 10px; border-bottom: 1px solid #eaeaea; color: #333;">{{ $totalPoints }}</td>
                            </tr>
                            </tbody>
                        </table>

                        <p style="font-size: 16px; line-height: 1.6; color: #666;">
                            If you have any questions, please email us at <a href="mailto:mail.hadirqugo@alphabetincubator.id" style="color: #00c6ff; text-decoration: none;">mail.hadirqugo@alphabetincubator.id</a>, and do not reply to this message as it is an automated email.
                        </p>
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="background-color: #f9f9f9; text-align: center; padding: 20px; color: #666;">
                        <p style="font-size: 14px; margin: 0;">&copy; {{ date('Y') }} Hadirkugo. All Rights Reserved.</p>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>

</body>
</html>