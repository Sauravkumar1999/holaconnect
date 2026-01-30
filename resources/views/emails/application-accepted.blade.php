<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Accepted</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: #ffffff;
            padding: 40px 20px;
            text-align: center;
        }

        .email-header h1 {
            margin: 0;
            font-size: 28px;
        }

        .email-header .icon {
            font-size: 48px;
            margin-bottom: 10px;
        }

        .email-body {
            padding: 30px 20px;
        }

        .email-body h2 {
            color: #11998e;
            margin-top: 0;
        }

        .success-box {
            background-color: #d4edda;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .certificate-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }

        .certificate-box h3 {
            margin: 0 0 10px 0;
            font-size: 20px;
        }

        .certificate-number {
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 2px;
            background-color: rgba(255, 255, 255, 0.2);
            padding: 10px 20px;
            border-radius: 4px;
            display: inline-block;
            margin: 10px 0;
        }

        .info-box {
            background-color: #f8f9fa;
            border-left: 4px solid #11998e;
            padding: 15px;
            margin: 20px 0;
        }

        .info-box p {
            margin: 5px 0;
        }

        .info-box strong {
            color: #333;
        }

        .email-footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
            border-top: 1px solid #e9ecef;
        }

        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #11998e;
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
            font-weight: bold;
        }

        .button:hover {
            background-color: #0e7c73;
        }

        .highlight {
            background-color: #fff3cd;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            <div class="icon">🎊</div>
            <h1>Congratulations!</h1>
            <p style="margin: 10px 0 0 0; font-size: 16px;">Your Application Has Been Accepted</p>
        </div>

        <div class="email-body">
            <h2>Dear {{ $user->name }},</h2>

            <div class="success-box">
                <strong>✅ Great News!</strong> We are pleased to inform you that your application has been reviewed and
                <strong>ACCEPTED</strong> by our admin team.
            </div>

            <p>Welcome to the Hola Taxi Ireland family! We are excited to have you as part of our growing community.</p>

            @if ($user->certificate_number)
                <div class="certificate-box">
                    <h3>📜 Your Share Certificate</h3>
                    <p>Certificate Number:</p>
                    <div class="certificate-number">{{ $user->certificate_number }}</div>
                    <p style="font-size: 14px; margin-top: 10px;">Issued on:
                        {{ $user->certificate_issued_date ? $user->certificate_issued_date->format('F d, Y') : 'N/A' }}
                    </p>
                </div>
            @endif

            <h3>Your Registration Details:</h3>
            <div class="info-box">
                <p><strong>Name:</strong> {{ $user->name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Phone:</strong> {{ $user->phone }}</p>
                @if ($user->psp_number)
                    <p><strong>PSP Number:</strong> {{ $user->psp_number }}</p>
                @endif
                @if ($user->taxi_driver_id)
                    <p><strong>Taxi Driver ID:</strong> {{ $user->taxi_driver_id }}</p>
                @endif
                @if ($user->certificate_number)
                    <p><strong>Certificate Number:</strong> <span
                            class="highlight">{{ $user->certificate_number }}</span></p>
                @endif
                <p><strong>Status:</strong> <span style="color: #28a745; font-weight: bold;">✓ Accepted</span></p>
            </div>

            <h3>Next Steps:</h3>
            <ul>
                <li>You can now access your full dashboard</li>
                <li>Your share certificate is available for download</li>
                <li>Keep your certificate number safe for future reference</li>
                <li>Contact us if you need any assistance</li>
            </ul>

            <p style="text-align: center;">
                <a href="{{ route('dashboard') }}" class="button">Access Your Dashboard</a>
            </p>

            <p style="margin-top: 30px;">If you have any questions or need assistance, please don't hesitate to reach
                out to our support team.</p>

            <p>Thank you for choosing Hola Taxi Ireland!</p>

            <p>Warm regards,<br>
                <strong>Hola Taxi Ireland Team</strong>
            </p>
        </div>

        <div class="email-footer">
            <p>&copy; {{ date('Y') }} Hola Taxi Ireland Limited. All rights reserved.</p>
            <p>This is an automated email. Please do not reply to this message.</p>
        </div>
    </div>
</body>

</html>
