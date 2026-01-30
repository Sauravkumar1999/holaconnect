<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful</title>
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
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }
        .email-body {
            padding: 30px 20px;
        }
        .email-body h2 {
            color: #667eea;
            margin-top: 0;
        }
        .info-box {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
        }
        .info-box p {
            margin: 5px 0;
        }
        .info-box strong {
            color: #333;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            background-color: #ffc107;
            color: #000;
            border-radius: 4px;
            font-weight: bold;
            font-size: 14px;
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
            background-color: #667eea;
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
            font-weight: bold;
        }
        .button:hover {
            background-color: #5568d3;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>🎉 Registration Successful!</h1>
        </div>
        
        <div class="email-body">
            <h2>Welcome to Hola Taxi Ireland, {{ $user->name }}!</h2>
            
            <p>Thank you for registering with Hola Taxi Ireland Limited. We have successfully received your registration application.</p>
            
            <div class="info-box">
                <p><strong>Name:</strong> {{ $user->name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Phone:</strong> {{ $user->phone }}</p>
                @if($user->psp_number)
                    <p><strong>PSP Number:</strong> {{ $user->psp_number }}</p>
                @endif
                @if($user->taxi_driver_id)
                    <p><strong>Taxi Driver ID:</strong> {{ $user->taxi_driver_id }}</p>
                @endif
                <p><strong>Payment Type:</strong> {{ ucfirst(str_replace('_', ' ', $user->payment_type)) }}</p>
                <p><strong>Application Status:</strong> <span class="status-badge">{{ ucfirst($user->application_status) }}</span></p>
            </div>
            
            <h3>What's Next?</h3>
            <p>Your application is currently under review by our admin team. You will receive another email once your application has been processed.</p>
            
            <p><strong>Application Review Process:</strong></p>
            <ul>
                <li>Our team will verify your submitted documents</li>
                <li>We will review your registration details</li>
                <li>You will be notified via email once a decision is made</li>
                <li>If accepted, you will receive your share certificate</li>
            </ul>
            
            <p style="text-align: center;">
                <a href="{{ route('dashboard') }}" class="button">View Your Dashboard</a>
            </p>
            
            <p>If you have any questions or concerns, please don't hesitate to contact our support team.</p>
            
            <p>Best regards,<br>
            <strong>Hola Taxi Ireland Team</strong></p>
        </div>
        
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} Hola Taxi Ireland Limited. All rights reserved.</p>
            <p>This is an automated email. Please do not reply to this message.</p>
        </div>
    </div>
</body>
</html>
