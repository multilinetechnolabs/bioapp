<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Under Maintenance</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }

        .container {
            text-align: center;
            padding: 40px 20px;
            max-width: 600px;
        }

        .icon {
            font-size: 80px;
            margin-bottom: 30px;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.8; }
        }

        h1 {
            font-size: 42px;
            font-weight: 700;
            margin-bottom: 16px;
            letter-spacing: -0.5px;
        }

        .subtitle {
            font-size: 20px;
            color: #a0aec0;
            margin-bottom: 30px;
            font-weight: 400;
        }

        .message-box {
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 12px;
            padding: 28px 36px;
            margin-bottom: 36px;
            line-height: 1.8;
            color: #cbd5e0;
            font-size: 15px;
        }

        .message-box p { margin-bottom: 10px; }
        .message-box p:last-child { margin-bottom: 0; }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(245, 158, 11, 0.15);
            border: 1px solid rgba(245, 158, 11, 0.4);
            color: #f6ad55;
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .dot {
            width: 8px;
            height: 8px;
            background: #f6ad55;
            border-radius: 50%;
            animation: blink 1.2s ease-in-out infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.2; }
        }

        .footer {
            margin-top: 40px;
            color: #4a5568;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">🔧</div>

        <h1>We'll Be Right Back</h1>
        <p class="subtitle">The site is temporarily down for maintenance</p>

        <div class="message-box">
            <p>We are performing scheduled maintenance to improve your experience.</p>
            <p>We apologize for any inconvenience and appreciate your patience.</p>
            <p>Please check back shortly — we'll be back online soon.</p>
        </div>

        <div class="status-badge">
            <span class="dot"></span>
            MAINTENANCE IN PROGRESS
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} BioApp &mdash; Thank you for your patience.
        </div>
    </div>
</body>
</html>
