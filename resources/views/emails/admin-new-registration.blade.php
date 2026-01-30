<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Registration - Action Required</title>
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
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
            color: #f5576c;
            margin-top: 0;
        }

        .alert-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .info-box {
            background-color: #f8f9fa;
            border-left: 4px solid #f5576c;
            padding: 15px;
            margin: 20px 0;
        }

        .info-box p {
            margin: 5px 0;
        }

        .info-box strong {
            color: #333;
        }

        .documents-list {
            background-color: #e7f3ff;
            padding: 15px;
            border-radius: 4px;
            margin: 15px 0;
        }

        .documents-list p {
            margin: 8px 0;
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
            background-color: #f5576c;
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
            margin: 10px 5px;
            font-weight: bold;
        }

        .button:hover {
            background-color: #e04455;
        }

        .button-secondary {
            background-color: #6c757d;
        }

        .button-secondary:hover {
            background-color: #5a6268;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            <h1>🔔 New Registration Received</h1>
        </div>

        <div class="email-body">
            <h2>Action Required: New User Registration</h2>

            <div class="alert-box">
                <strong>⚠️ Attention Admin:</strong> A new user has completed their registration and is awaiting
                approval.
            </div>

            <h3>Applicant Details:</h3>
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
                <p><strong>Payment Type:</strong> {{ ucfirst(str_replace('_', ' ', $user->payment_type)) }}</p>
                <p><strong>Registration Date:</strong> {{ $user->created_at->format('F d, Y h:i A') }}</p>
                <p><strong>Application Status:</strong> {{ ucfirst($user->application_status) }}</p>
            </div>

            <h3>Submitted Documents:</h3>
            <div class="documents-list">
                @if ($user->document_dashboard_path)
                    <p>📄 <strong>Dashboard Document:</strong> Uploaded</p>
                @endif
                @if ($user->document_identity_path)
                    <p>🆔 <strong>Identity Document:</strong> Uploaded</p>
                @endif
                @if ($user->document_payment_receipt_path)
                    <p>💳 <strong>Payment Receipt:</strong> Uploaded</p>
                @endif
            </div>

            <h3>Required Actions:</h3>
            <p>Please review the applicant's details and submitted documents. You can:</p>
            <ul>
                <li>Review all submitted documents</li>
                <li>Verify the applicant's information</li>
                <li>Accept or reject the application</li>
                <li>Generate share certificate upon acceptance</li>
            </ul>

            <p style="text-align: center; margin-top: 30px;">
                <a href="{{ route('registration.details', $user->id) }}" class="button">Review Application</a>
                <a href="{{ route('registrations') }}" class="button button-secondary">View All Registrations</a>
            </p>

            <p style="margin-top: 30px; font-size: 14px; color: #6c757d;">
                <strong>Note:</strong> Please process this application as soon as possible to ensure a smooth experience
                for the applicant.
            </p>
        </div>

        <div class="email-footer">
            <p>&copy; {{ date('Y') }} Hola Taxi Ireland Limited - Admin Panel</p>
            <p>This is an automated notification email.</p>
        </div>
    </div>
</body>

</html>
