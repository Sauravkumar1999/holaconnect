<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shareholder Application Approved</title>
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
        .highlight-box {
            background-color: #f8f9ff;
            border-left: 4px solid #667eea;
            padding: 15px 20px;
            margin: 20px 0;
        }
        .highlight-box ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .highlight-box li {
            margin: 8px 0;
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
            <h1>🎉 Congratulations, {{ $user->name }}!</h1>
        </div>
        
        <div class="email-body">
            <h2>Dear Shareholder,</h2>
            
            <p>Congratulations and welcome to the Hola Taxi family! 🎉</p>
            
            <p>We're delighted to inform you that your <strong>Shareholder Application Form</strong> has been successfully verified and approved.</p>
            
            <p>By becoming a shareholder, you are no longer just a user of the platform — you are now a <strong>co-owner of Hola Taxi</strong>. This means you own a real part of the company, and as Hola Taxi grows, the value of your ownership grows with it. Your success and the company's success are directly connected.</p>
            
            <p>As a mark of this partnership and trust, your official <strong>Hola Taxi Shareholder Certificate</strong> has now been issued. Please find it attached to this email. We recommend downloading and securely storing it for your records and future reference.</p>
            
            <hr class="divider">
            
            <h3>What This Means for You</h3>
            <div class="highlight-box">
                <ul>
                    <li>You hold ownership in a <strong>driver-owned company</strong>, built to put drivers first.</li>
                    <li>As the company expands, strengthens, and succeeds, your share represents <strong>growing long-term value</strong>, not just short-term benefits.</li>
                    <li>Hola Taxi is designed so that wealth created by the platform <strong>stays with the drivers</strong> — the people who actually run it every day.</li>
                </ul>
            </div>
            
            <hr class="divider">
            
            <h3>Important Information</h3>
            <div class="highlight-box">
                <ul>
                    <li>This certificate has been issued based on the information and documents provided during your application process.</li>
                    <li>Any future discrepancy identified in submitted details may require corrective action in line with company policies.</li>
                    <li>Kindly review all certificate details carefully upon receipt.</li>
                </ul>
            </div>
            
            <p>If you have any questions about your shareholder status, ownership, or the future direction of Hola Taxi, our support team is always here for you.</p>
            
            <p>Once again, thank you for believing in a better, fairer way forward. Together, as one Hola Taxi family, we are building a company that is <strong>owned by drivers, grows with drivers, and creates lasting value</strong> for our community.</p>
        </div>
        
        <div class="email-footer">
            <p><strong>Warm Regards,</strong><br>
            <strong>Hola Taxi Support Team</strong></p>
            
            <div class="contact-info">
                <p><strong>Contact:</strong> +353 87 194 1067</p>
                <p><strong>Email:</strong> <a href="mailto:support@holaconnect.ie">support@holaconnect.ie</a></p>
                <p><strong>Address:</strong> Mespil House, Sussex Rd, Ballsbridge, Dublin 4, Ireland</p>
                <p><strong>Website:</strong> <a href="https://holataxi.ie/" target="_blank">https://holataxi.ie/</a></p>
            </div>
            
            <div class="social-links">
                <p><strong>Join Our Community:</strong></p>
                <a href="https://whatsapp.com/channel/0029VbBwCMUKgsO2rASJe143" target="_blank">📢 Follow WhatsApp Channel</a>
                <a href="https://chat.whatsapp.com/FiEfmUrT1JkIF0IKCRvVbo?mode=gi_c" target="_blank">💬 Join WhatsApp Group</a>
            </div>
            
            <p style="margin-top: 20px;">&copy; {{ date('Y') }} Hola Taxi Ireland Limited. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
