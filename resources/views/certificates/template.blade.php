<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Share Certificate</title>
    @php
        // We can inline the image as base64 to ensure it renders in tools like Browsershot/DomPDF without path issues
        $bgPath = public_path('images/certificate-bg.jpg');
        $bgData = base64_encode(file_get_contents($bgPath));
        $bgImage = 'data:image/jpeg;base64,' . $bgData;
    @endphp
    <style>
        @font-face {
            font-family: 'Great Vibes';
            src: url('{{ public_path('fonts/GreatVibes.ttf') }}') format('truetype');
        }

        @font-face {
            font-family: 'Times New Roman';
            src: url('{{ public_path('fonts/times.ttf') }}') format('truetype');
        }

        body {
            margin: 0;
            padding: 0;
            width: 1920px;
            height: 1080px;
            overflow: hidden;
            font-family: 'Times New Roman', serif;
        }

        .certificate-container {
            width: 1920px;
            height: 1080px;
            position: relative;
            background-image: url('{{ $bgImage }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: #1a365d;
            /* Dark Blue */
        }

        /* Helper to visualize centering if needed */
        /* .certificate-container::after { content: ''; position: absolute; top: 0; left: 50%; height: 100%; border-left: 1px dashed red; opacity: 0.5; } */

        .text-overlay {
            position: absolute;
            transform: translate(-50%, -50%);
            /* Centers the element on its coordinate */
            text-align: center;
        }

        .name-field {
            top: 42%;
            /* Adjust based on new image */
            left: 50%;
            font-family: 'Great Vibes', cursive;
            /* Use script font for name */
            font-size: 60px;
            width: 800px;
            /* border-bottom: 2px solid #1a365d; */
            line-height: 1.2;
        }

        .details-container {
            position: absolute;
            top: 50%;
            /* Adjust roughly to the middle text area */
            left: 50%;
            transform: translateX(-50%);
            width: 80%;
            font-size: 24px;
            text-align: center;
            line-height: 1.6;
        }

        .highlight-text {
            font-weight: bold;
            border-bottom: 1px dotted #1a365d;
            padding: 0 10px;
            display: inline-block;
            min-width: 50px;
        }

        .certificate-footer {
            position: absolute;
            bottom: 15%;
            left: 50%;
            transform: translateX(-50%);
            width: 80%;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .footer-block {
            text-align: center;
        }

        .signature-line {
            width: 300px;
            border-top: 1px solid #1a365d;
            margin-top: 10px;
            padding-top: 5px;
            font-weight: bold;
            font-size: 18px;
        }

        /* Precise positioning for specific fields if not using flow */

        .pos-name {
            top: 405px;
            left: 50%;
            font-size: 55px;
            font-family: 'Great Vibes', cursive;
            width: 100%;
            text-align: center;
            position: absolute;
            transform: translateX(-50%);
        }

        .pos-license {
            top: 442px;
            left: 240px;
            position: absolute;
            font-weight: bold;
            font-size: 24px;
        }

        .pos-shares {
            top: 442px;
            right: 480px;
            position: absolute;
            font-weight: bold;
            font-size: 24px;
        }

        .pos-cert-num {
            bottom: 180px;
            left: 320px;
            position: absolute;
            font-weight: bold;
            font-size: 22px;
        }

        .pos-date {
            bottom: 140px;
            left: 320px;
            position: absolute;
            font-weight: bold;
            font-size: 22px;
        }

        .pos-director {
            bottom: 140px;
            right: 250px;
            position: absolute;
            text-align: center;
            width: 300px;
        }

        .director-sig {
            font-family: 'Great Vibes', cursive;
            font-size: 40px;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <div class="certificate-container">
        <!-- Name -->
        <div class="pos-name">{{ $userName }}</div>

        <!-- License Number (e.g. Ireland) -->
        <div class="pos-license">{{ $licenseNumber }}</div>

        <!-- Shares Count -->
        <div class="pos-shares">{{ number_format($shares) }}</div>

        <!-- Certificate Number -->
        <div class="pos-cert-num">
            Certificate No: {{ $certificateNumber }}
        </div>

        <!-- Date -->
        <div class="pos-date">
            Date of Issue: {{ $issuedDate }}
        </div>

        <!-- Director Signature -->
        <div class="pos-director">
            <div class="director-sig">Kamal S Gill</div>
            <div class="signature-line">DIRECTOR</div>
        </div>
    </div>
</body>

</html>