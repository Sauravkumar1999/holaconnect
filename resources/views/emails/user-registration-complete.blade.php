<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Acknowledgement - Hola Taxi</title>
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
            max-width: 650px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }

        .email-header h1 {
            margin: 0;
            font-size: 26px;
        }

        .email-body {
            padding: 30px 25px;
        }

        .email-body h2 {
            color: #667eea;
            margin-top: 0;
            font-size: 20px;
        }

        .email-body h3 {
            color: #333;
            font-size: 18px;
            margin-top: 25px;
            margin-bottom: 10px;
        }

        .email-body p {
            margin: 12px 0;
        }

        .info-box {
            background-color: #f8f9ff;
            border-left: 4px solid #667eea;
            padding: 15px 20px;
            margin: 20px 0;
        }

        .info-box p {
            margin: 8px 0;
        }

        .warning-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px 20px;
            margin: 20px 0;
        }

        .warning-box h3 {
            color: #856404;
            margin-top: 0;
            font-size: 16px;
        }

        .warning-box p,
        .warning-box ul {
            color: #856404;
            margin: 8px 0;
        }

        .warning-box ul {
            padding-left: 20px;
        }

        .warning-box li {
            margin: 5px 0;
        }

        .divider {
            border: 0;
            border-top: 1px solid #e0e0e0;
            margin: 25px 0;
        }

        .email-footer {
            background-color: #f8f9fa;
            padding: 25px 20px;
            text-align: center;
            font-size: 13px;
            color: #6c757d;
            border-top: 1px solid #e9ecef;
        }

        .email-footer p {
            margin: 8px 0;
        }

        .email-footer a {
            color: #667eea;
            text-decoration: none;
        }

        .email-footer a:hover {
            text-decoration: underline;
        }

        .contact-info {
            margin-top: 15px;
        }

        .social-links {
            margin-top: 15px;
        }

        .social-links a {
            display: inline-block;
            margin: 5px 10px;
            padding: 8px 15px;
            background-color: #25D366;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 13px;
        }

        .social-links a:hover {
            background-color: #128C7E;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            <h1>✅ Registration Received</h1>
        </div>

        <div class="email-body">
            <h2>Dear {{ $user->name }},</h2>

            <p>Thank you for successfully submitting your <strong>Shareholder Registration Form</strong> along with the
                required payment on our website.</p>

            <p>We hereby confirm receipt of your submission. Our verification team will now carefully review and
                validate the details and documents provided by you.</p>

            <div class="info-box">
                <p><strong>What Happens Next:</strong></p>
                <p>Once the verification process is completed successfully, your <strong>Shareholder
                        Certificate</strong> will be issued and sent to your registered email address.</p>
                <p>You will be able to download the certificate directly from the email attachment.</p>
            </div>

            <p>If any clarification or additional information is required during the verification process, our team may
                contact you using your registered contact details.</p>

            <hr class="divider">

            <div class="warning-box">
                <h3>⚠️ Important Notice</h3>
                <p><strong>Please note that:</strong></p>
                <ul>
                    <li>If any <strong>incorrect, fake, or misleading information/documents</strong> are found during
                        verification, the application will be <strong>rejected</strong>.</li>
                    <li>In such cases, <strong>no refund will be applicable</strong> under any circumstances.</li>
                </ul>
            </div>

            <p>For any questions, clarification, or support, please feel free to contact us.</p>

            <p>We appreciate your patience during the verification process and look forward to welcoming you as a valued
                shareholder of Hola Taxi.</p>
        </div>

        <div class="email-footer">
            <p><strong>Warm Regards,</strong><br>
                <strong>Hola Taxi Support Team</strong>
            </p>

            <div class="contact-info">
                <p><strong>Contact:</strong> +353 87 194 1067</p>
                <p><strong>Email:</strong> <a href="mailto:support@holaconnect.ie">support@holaconnect.ie</a></p>
                <p><strong>Address:</strong> Mespil House, Sussex Rd, Ballsbridge, Dublin 4, Ireland</p>
                <p><strong>Website:</strong> <a href="https://holataxi.ie/" target="_blank">https://holataxi.ie/</a></p>
            </div>

            <div class="social-links">
                <p><strong>Join Our Community:</strong></p>
                <a href="https://whatsapp.com/channel/0029VbBwCMUKgsO2rASJe143" target="_blank">📢 Follow WhatsApp
                    Channel</a>
                <a href="https://chat.whatsapp.com/FiEfmUrT1JkIF0IKCRvVbo?mode=gi_c" target="_blank">💬 Join WhatsApp
                    Group</a>
            </div>

            <p style="margin-top: 20px;">&copy; {{ date('Y') }} Hola Taxi Ireland Limited. All rights reserved.</p>
        </div>
    </div>
</body>

</html>