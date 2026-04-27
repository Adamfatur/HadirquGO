<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - HadirquGO</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; color: #334155; line-height: 1.6; background-color: #f8fafc; }
        h1, h2 { font-family: 'Outfit', sans-serif; color: #1e293b; }
        .container { max-width: 800px; padding: 60px 20px; }
        .content-box { background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .back-link { display: inline-block; margin-bottom: 20px; color: #1e40af; text-decoration: none; font-weight: 600; }
        h2 { margin-top: 30px; font-size: 1.5rem; }
    </style>
</head>
<body>
    <div class="container">
        <a href="/" class="back-link">&larr; Back to Home</a>
        <div class="content-box">
            <h1 class="mb-4">Privacy Policy</h1>
            <p>Last updated: {{ date('F d, Y') }}</p>
            
            <p>At HadirquGO, we value your privacy. This policy explains how we collect and use your information when you use our attendance platform.</p>

            <h2>1. Information We Collect</h2>
            <p>When you log in via Google OAuth, we receive your name and email address. We also collect attendance data (timestamp, location if enabled, and team affiliation) and gamification data (points and levels).</p>

            <h2>2. How We Use Your Data</h2>
            <p>Your data is used strictly for:</p>
            <ul>
                <li>Managing and recording your attendance.</li>
                <li>Calculating gamification points and leaderboard rankings.</li>
                <li>Providing administrators (Lecturers/Owners) with attendance reports.</li>
            </ul>

            <h2>3. Data Security</h2>
            <p>We use dynamic QR code technology and secure server-side tokens to prevent unauthorized attendance records. Your personal data is stored securely and never sold to third parties.</p>

            <h2>4. Your Rights</h2>
            <p>You have the right to view your attendance history and request the deletion of your account at any time by contacting us.</p>

            <h2>5. Contact Us</h2>
            <p>If you have questions about this policy, contact us at: hadirqugo@alphabetincubator.id</p>
        </div>
    </div>
</body>
</html>
