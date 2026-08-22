<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Suspended – Payment Required</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #0a0a0f;
            color: #e4e4e7;
            overflow: hidden;
            position: relative;
        }

        /* Animated gradient background */
        body::before {
            content: '';
            position: fixed;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: 
                radial-gradient(ellipse at 20% 50%, rgba(120, 40, 200, 0.08) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 20%, rgba(255, 50, 50, 0.06) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 80%, rgba(255, 140, 0, 0.05) 0%, transparent 50%);
            animation: bgShift 20s ease-in-out infinite alternate;
            z-index: 0;
        }

        @keyframes bgShift {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(-5%, -5%) rotate(3deg); }
        }

        /* Noise texture overlay */
        body::after {
            content: '';
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.03'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
        }

        .container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 580px;
            padding: 20px;
        }

        .card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(40px);
            -webkit-backdrop-filter: blur(40px);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 24px;
            padding: 48px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
            box-shadow: 
                0 0 0 1px rgba(255, 255, 255, 0.03),
                0 20px 50px rgba(0, 0, 0, 0.4),
                0 0 100px rgba(120, 40, 200, 0.05);
        }

        /* Top accent line */
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, 
                transparent, 
                rgba(239, 68, 68, 0.6) 20%, 
                rgba(251, 146, 60, 0.8) 50%, 
                rgba(239, 68, 68, 0.6) 80%, 
                transparent);
        }

        .icon-wrapper {
            width: 80px;
            height: 80px;
            margin: 0 auto 28px;
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.15), rgba(251, 146, 60, 0.1));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(239, 68, 68, 0.15);
            animation: iconPulse 3s ease-in-out infinite;
        }

        @keyframes iconPulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.1); }
            50% { box-shadow: 0 0 30px 5px rgba(239, 68, 68, 0.08); }
        }

        .icon-wrapper svg {
            width: 36px;
            height: 36px;
            color: #ef4444;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-radius: 100px;
            font-size: 12px;
            font-weight: 600;
            color: #ef4444;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .status-badge .dot {
            width: 6px;
            height: 6px;
            background: #ef4444;
            border-radius: 50%;
            animation: blink 2s ease-in-out infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        h1 {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #ffffff;
            margin-bottom: 12px;
            line-height: 1.2;
        }

        .subtitle {
            font-size: 15px;
            color: #a1a1aa;
            line-height: 1.7;
            margin-bottom: 32px;
            max-width: 420px;
            margin-left: auto;
            margin-right: auto;
        }

        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.08), transparent);
            margin: 0 -40px 28px;
        }

        .info-box {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 14px;
            padding: 20px 24px;
            margin-bottom: 28px;
            text-align: left;
        }

        .info-box .label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #71717a;
            margin-bottom: 10px;
        }

        .info-box .detail {
            font-size: 14px;
            color: #d4d4d8;
            line-height: 1.8;
        }

        .info-box .detail strong {
            color: #fbbf24;
            font-weight: 600;
        }

        .contact-section {
            margin-top: 8px;
        }

        .contact-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 28px;
            background: linear-gradient(135deg, rgba(251, 146, 60, 0.15), rgba(239, 68, 68, 0.1));
            border: 1px solid rgba(251, 146, 60, 0.2);
            border-radius: 12px;
            color: #fb923c;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .contact-link:hover {
            background: linear-gradient(135deg, rgba(251, 146, 60, 0.25), rgba(239, 68, 68, 0.15));
            border-color: rgba(251, 146, 60, 0.4);
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(251, 146, 60, 0.1);
        }

        .contact-link svg {
            width: 16px;
            height: 16px;
        }

        .footer-text {
            margin-top: 32px;
            font-size: 12px;
            color: #52525b;
        }

        .footer-text .ref {
            font-family: 'Courier New', monospace;
            color: #71717a;
            background: rgba(255,255,255,0.03);
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .card {
                padding: 36px 24px;
            }

            h1 {
                font-size: 22px;
            }

            .subtitle {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <!-- Lock Icon -->
            <div class="icon-wrapper">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                </svg>
            </div>

            <!-- Status Badge -->
            <div class="status-badge">
                <span class="dot"></span>
                Service Suspended
            </div>

            <h1>This Website Is<br>Currently Unavailable</h1>

            <p class="subtitle">
                This website has been temporarily suspended due to an outstanding payment with the development team. 
                Service will be fully restored once the balance is settled.
            </p>

            <div class="divider"></div>

            <div class="info-box">
                <div class="label">What Happened?</div>
                <div class="detail">
                    The development and hosting services for this website require payment to remain active. 
                    The current invoice is <strong>overdue</strong>. 
                    Once payment is confirmed, the site will be back online immediately.
                </div>
            </div>

            <div class="contact-section">
                <a href="mailto:izeremubarak05@gmail.com" class="contact-link">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                    </svg>
                    Contact Developer
                </a>
            </div>

            <div class="footer-text">
                <p>Ref: <span class="ref">ASG-{{ date('Y') }}-{{ str_pad(date('md'), 4, '0') }}</span></p>
            </div>
        </div>
    </div>
</body>
</html>
