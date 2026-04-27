<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HadirquGO Check-out Success</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Georgia', serif; background-color: #f8f9fa; color: #2A2E35;">

<!-- Main Wrapper -->
<table style="width: 100%; background-color: #f8f9fa; padding: 30px 0;" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center">
            <!-- Main Container -->
            <table style="max-width: 640px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08); border: 1px solid #eaeaea;" cellpadding="0" cellspacing="0">

                <!-- Header Section -->
                <tr>
                    <td style="background: #F5F3EF; padding: 30px 20px; text-align: center; position: relative;">
                        <!-- Logo Container -->
                        <div style="display: inline-block; padding: 10px; border: 2px solid #0A2342; border-radius: 8px; background: white; line-height: 0;">
                            <img src="https://drive.pastibisa.app/1731549866_67355aaaea1f0.png"
                                 alt="HadirquGO Logo"
                                 style="width: 280px; height: auto; display: block; margin: 0 auto;"
                                 width="280"
                                 height="48">
                        </div>
                        <!-- Gold Accent Line -->
                        <div style="margin: 20px auto 0; width: 100px; height: 4px; background: #C5A047; border-radius: 2px;"></div>
                    </td>
                </tr>

                <!-- Content Section -->
                <tr>
                    <td style="padding: 40px 32px;">
                        <h2 style="font-size: 24px; color: #C5A047; margin: 0 0 24px 0; font-weight: 500; letter-spacing: 0.5px;">
                            ✔️ Check-out Completed
                        </h2>

                        <!-- Summary Card -->
                        <div style="background: #FFF9EF; border-radius: 8px; padding: 24px; margin-bottom: 32px; position: relative;">
                            <div style="position: absolute; top: -12px; left: 24px; background: #C5A047; padding: 8px 16px; border-radius: 4px; color: white; font-size: 14px;">
                                Session Summary
                            </div>
                            <p style="font-size: 18px; line-height: 1.6; margin: 16px 0; color: #2A2E35;">
                                Hello <strong style="color: #0A2342;">{{ $user->name }}</strong>,
                            </p>
                            <p style="font-size: 18px; line-height: 1.6; margin-bottom: 20px; color: #2A2E35;">
                                Your session at <strong style="color: #0A2342;">{{ $location->name }}</strong><br>
                                lasted for <strong style="color: #C5A047;">{{ $formatted_duration }}</strong><br>
                                ending at <strong style="color: #C5A047;">{{ $sent_at->format('d F Y, H:i') }}</strong>
                            </p>
                            <div style="background: linear-gradient(135deg, #C5A047, #B08D3D); padding: 16px; border-radius: 6px; text-align: center;">
                                <p style="margin: 0; color: white; font-size: 20px; font-weight: 500;">
                                    Earned {{ $points_earned }} Tesla Points
                                </p>
                            </div>
                        </div>

                        <!-- Detail Table -->
                        <table style="width: 100%; border-collapse: collapse; margin: 24px 0;" cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="padding: 16px 0; border-bottom: 1px solid #EEE; color: #6C757D;">
                                    <img src="https://cdn-icons-png.flaticon.com/512/1077/1077114.png" alt="Profile" style="width: 20px; height: 20px; vertical-align: middle; display: inline-block;">
                                    <span style="margin-left: 8px;">Participant</span>
                                </td>
                                <td style="padding: 16px 0; border-bottom: 1px solid #EEE; color: #2A2E35; text-align: right;">
                                    {{ $user->name }}
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 16px 0; border-bottom: 1px solid #EEE; color: #6C757D;">
                                    <img src="https://cdn-icons-png.flaticon.com/512/684/684908.png" alt="Location" style="width: 20px; height: 20px; vertical-align: middle; display: inline-block;">
                                    <span style="margin-left: 8px;">Location</span>
                                </td>
                                <td style="padding: 16px 0; border-bottom: 1px solid #EEE; color: #2A2E35; text-align: right;">
                                    {{ $location->name }}
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 16px 0; border-bottom: 1px solid #EEE; color: #6C757D;">
                                    <img src="https://cdn-icons-png.flaticon.com/512/3612/3612716.png" alt="Time" style="width: 20px; height: 20px; vertical-align: middle; display: inline-block;">
                                    <span style="margin-left: 8px;">Check-out Time</span>
                                </td>
                                <td style="padding: 16px 0; border-bottom: 1px solid #EEE; color: #2A2E35; text-align: right;">
                                    {{ $sent_at->format('H:i') }}<br>
                                    <span style="font-size: 14px; color: #6C757D;">{{ $sent_at->format('d M Y') }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 16px 0; border-bottom: 1px solid #EEE; color: #6C757D;">
                                    <img src="https://cdn-icons-png.flaticon.com/512/1588/1588889.png" alt="Duration" style="width: 20px; height: 20px; vertical-align: middle; display: inline-block;">
                                    <span style="margin-left: 8px;">Duration</span>
                                </td>
                                <td style="padding: 16px 0; border-bottom: 1px solid #EEE; color: #2A2E35; text-align: right;">
                                    {{ $formatted_duration }}
                                </td>
                            </tr>
                        </table>

                        <!-- Footer Text -->
                        <p style="font-size: 16px; line-height: 1.6; color: #6C757D; margin-top: 24px;">
                            Your consistency helps us improve our services.<br>
                            Need assistance? Contact our team:
                            <a href="mailto:mail.hadirqugo@alphabetincubator.id"
                               style="color: #C5A047; text-decoration: none; border-bottom: 1px dotted #C5A047;">
                                mail.hadirqugo@alphabetincubator.id
                            </a>
                        </p>
                    </td>
                </tr>

                <!-- Brand Footer -->
                <tr>
                    <td style="background-color: #0A2342; padding: 24px; text-align: center;">
                        <div style="border-top: 1px solid rgba(197, 160, 71, 0.2); margin-bottom: 16px;"></div>
                        <p style="margin: 8px 0; color: #C5A047; font-size: 14px;">
                            Powered by HadirquGO<br>
                            <span style="color: rgba(255, 255, 255, 0.7); font-size: 12px;">© {{ date('Y') }} HadirquGO. All Rights Reserved.</span>
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

</body>
</html>