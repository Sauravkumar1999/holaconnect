<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Share Certificate</title>
    <!-- Use Google Fonts for better rendering in Browsershot -->
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Libre+Baskerville:wght@400;700&family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    @php
        // We can inline the image as base64 to ensure it renders in tools like Browsershot/DomPDF without path issues
        $bgPath = public_path('images/certificate-bg.jpg');
        $bgData = base64_encode(file_get_contents($bgPath));
        $bgImage = 'data:image/jpeg;base64,' . $bgData;
    @endphp
    <style>
        body {
            margin: 0;
            padding: 0;
            width: 1024px;
            height: 724px;
            overflow: hidden;
            font-family: 'Libre Baskerville', serif;
            background-color: #fff;
        }

        .certificate-container {
            width: 1024px;
            height: 724px;
            position: relative;
            background-image: url('{{ $bgImage }}');
            background-size: 100% 100%;
            background-position: center;
            background-repeat: no-repeat;
            color: #0c2044; /* Deep Navy from image */
        }

        .content {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 40px;
        }

        /* Logo Placeholder (CSS based) */
        .logo-box {
            width: 70px;
            height: 75px;
            background-color: #ffd800; /* Yellow from logo */
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .logo-box .icon {
            color: #0c2044;
            font-weight: bold;
            font-size: 24px;
            line-height: 1;
        }

        .logo-box .label {
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            color: #0c2044;
        }

        .title {
            font-size: 52px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 5px;
            margin-bottom: 5px;
            color: #1a3c6c;
        }

        .company-name {
            font-size: 16px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 40px;
        }

        .certify-text {
            font-size: 20px;
            font-style: italic;
            margin-bottom: 10px;
        }

        .user-name-wrapper {
            position: relative;
            margin-bottom: 25px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .user-name {
            font-family: 'Great Vibes', cursive;
            font-size: 82px;
            margin: 0;
            padding: 0 40px;
            color: #1a3c6c;
            line-height: 0.8;
            z-index: 2;
        }

        .name-underline {
            width: 600px;
            height: 1.5px;
            background-color: #1a3c6c;
            position: relative;
            margin-top: -5px;
        }

        .name-underline::before,
        .name-underline::after {
            content: '';
            position: absolute;
            width: 8px;
            height: 8px;
            background-color: #1a3c6c;
            border-radius: 50%;
            top: -3.5px;
        }

        .name-underline::before { left: 0; }
        .name-underline::after { right: 0; }

        .description {
            width: 80%;
            text-align: center;
            font-size: 17px;
            line-height: 2;
            margin-top: 10px;
        }

        .highlight {
            font-weight: bold;
            border-bottom: 1px solid #0c2044;
            padding: 0 15px;
            margin: 0 2px;
            display: inline-block;
            min-width: 60px;
        }

        .meta-data {
            position: absolute;
            bottom: 100px;
            left: 140px;
            text-align: left;
            font-size: 15px;
            font-weight: bold;
        }

        .meta-item {
            margin-bottom: 10px;
            letter-spacing: 0.5px;
        }

        .seal-container {
            position: absolute;
            bottom: 80px;
            left: 50%;
            transform: translateX(-50%);
        }

        /* Gold Seal Ribbon (CSS based) */
        .seal {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #fcd34d 0%, #d97706 100%);
            border-radius: 50%;
            border: 4px double #fff;
            position: relative;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            z-index: 5;
        }

        .ribbon {
            position: absolute;
            width: 30px;
            height: 60px;
            background-color: #fcd34d;
            top: 50px;
            clip-path: polygon(0 0, 100% 0, 100% 100%, 50% 80%, 0 100%);
            z-index: 4;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .ribbon-left { left: 10px; transform: rotate(15deg); }
        .ribbon-right { right: 10px; transform: rotate(-15deg); }

        .signature-section {
            position: absolute;
            bottom: 100px;
            right: 140px;
            text-align: center;
            width: 250px;
        }

        .signature-line {
            border-top: 1px solid #0c2044;
            margin-top: 5px;
            padding-top: 5px;
            font-weight: bold;
            font-size: 16px;
            text-transform: uppercase;
        }

        .signature-name {
            font-family: 'Great Vibes', cursive;
            font-size: 38px;
            margin-bottom: -10px;
            color: #1a3c6c;
        }
    </style>
</head>

<body>
    <div class="certificate-container">
        <div class="content">
            <!-- Logo -->
            <div class="logo-box">
                <div class="icon">
                   <svg width="40" height="40" viewBox="0 0 100 100">
                       <path d="M20 20 Q50 80 80 20" stroke="#0c2044" stroke-width="8" fill="none" />
                   </svg>
                </div>
                <div class="label">HOLA TAXI<br>Driver</div>
            </div>

            <!-- Header -->
            <div class="title">SHARE CERTIFICATE</div>
            <div class="company-name">COMPANY NAME: HOLA TAXI IRELAND LIMITED</div>

            <!-- Certification Text -->
            <div class="certify-text">THIS IS TO CERTIFY THAT</div>

            <!-- User Name -->
            <div class="user-name-wrapper">
                <h1 class="user-name">{{ $userName }}</h1>
                <div class="name-underline"></div>
            </div>

            <!-- Description -->
            <div class="description">
                of <span class="highlight">{{ $licenseNumber }}</span> holding license number is the registered holder of <span class="highlight">{{ number_format($shares) }}</span><br>
                Class A Ordinary Shares in the capital of Hola Taxi Ireland Limited.
            </div>

            <!-- Meta Data (Left) -->
            <div class="meta-data">
                <div class="meta-item">CERTIFICATE NO: {{ $certificateNumber }}</div>
                <div class="meta-item">DATE OF ISSUE: {{ $issuedDate }}</div>
            </div>

            <!-- Seal (Center) -->
            <div class="seal-container">
                <div class="seal"></div>
                <div class="ribbon ribbon-left"></div>
                <div class="ribbon ribbon-right"></div>
            </div>

            <!-- Signature (Right) -->
            <div class="signature-section">
                <div class="signature-name">Kamal S Gill</div>
                <div class="signature-line">DIRECTOR</div>
            </div>
        </div>
    </div>
</body>

</html>