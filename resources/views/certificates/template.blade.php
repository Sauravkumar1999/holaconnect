<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Share Certificate</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Great+Vibes&family=Libre+Baskerville:wght@400;700&family=Montserrat:wght@400;700&display=swap');

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-family: 'Libre Baskerville', serif;
            background-repeat: no-repeat;
            background-size: cover;
        }

        /* 
           DomPDF does not support standard background-size: cover on body well with base64.
           We'll rely on the container.
        */

        .certificate-container {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            text-align: center;
            /* Replaces flex-direction column + align-items center */
            color: #0c2044;
        }

        /* Helper for vertical spacing since we lost flex gap */
        .spacer-10 {
            height: 10px;
        }

        .spacer-20 {
            height: 20px;
        }

        .spacer-40 {
            height: 40px;
        }

        .spacer-60 {
            height: 60px;
        }

        /* Logo Area */
        .logo-container {
            margin-top: 45px;
            /* Reduced from 50px */
            margin-bottom: 5px;
            /* Reduced from 20px */
            width: 100%;
            text-align: center;
        }

        /* ... logo-box styles ... */
        .logo-box {
            width: 60px;
            /* Slightly smaller */
            height: 65px;
            background-color: #ffd800;
            border-radius: 12px;
            margin: 0 auto;
            padding-top: 12px;
            box-sizing: border-box;
        }

        /* ... */

        /* Texts */
        .title {
            font-size: 38px;
            /* Reduced from 42px */
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 5px;
            margin-bottom: 5px;
            color: #1a3c6c;
            width: 100%;
        }

        .company-name {
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 20px;
            /* Reduced from 30px */
        }

        .certify-text {
            font-size: 16px;
            /* Reduced from 18px */
            font-style: italic;
            margin-bottom: 5px;
        }

        /* User Name */
        .user-name {
            font-family: 'Great Vibes', cursive;
            font-size: 50px;
            /* Reduced from 60px */
            margin: 5px auto;
            color: #1a3c6c;
            line-height: 1;
        }

        .name-underline {
            width: 500px;
            height: 2px;
            background-color: #1a3c6c;
            margin: 0 auto 15px auto;
            /* Reduced bottom margin */
            position: relative;
        }

        /* ... dots ... */

        /* Description Body */
        .description {
            width: 80%;
            margin: 0 auto;
            text-align: center;
            font-size: 15px;
            /* Reduced from 16px */
            line-height: 1.6;
        }

        /* Bottom Section */
        .bottom-section {
            margin-top: 40px;
            /* Reduced from 80px to pull everything up */
            width: 100%;
            position: relative;
            height: 150px;
        }

        .meta-data {
            position: absolute;
            left: 80px;
            /* Adjusted X */
            bottom: 50px;
            /* Increased bottom to move UP inside border */
            text-align: left;
            font-size: 13px;
            font-weight: bold;
        }

        .meta-item {
            margin-bottom: 6px;
        }

        /* Seal - Center Bottom */
        .seal-wrapper {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 40px;
            /* Moved UP */
            margin: 0 auto;
            width: 100px;
            height: 120px;
            text-align: center;
        }

        /* Signature - Right Bottom */
        .signature-section {
            position: absolute;
            right: 80px;
            /* Adjusted X */
            bottom: 50px;
            /* Increased bottom to move UP inside border */
            width: 250px;
            text-align: center;
        }

        .signature-name {
            font-family: 'Great Vibes', cursive;
            font-size: 32px;
            color: #1a3c6c;
            margin-bottom: 5px;
        }

        .signature-line {
            border-top: 1px solid #0c2044;
            padding-top: 5px;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
        }
    </style>
</head>
@php
    // Background Image
    $bgPath = public_path('images/certificate-bg.jpg');
    // Ensure we have a fallback or valid path. 
    // DomPDF works best with full system paths for local files, but we are using base64 here which is safe.
    $bgImage = file_exists($bgPath) ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($bgPath)) : '';

    // Dynamic Logo
    $logoImage = null;
    if (!empty($companyLogo)) {
        $fullLogoPath = public_path($companyLogo);
        if (file_exists($fullLogoPath)) {
            $logoData = base64_encode(file_get_contents($fullLogoPath));
            $mimeType = mime_content_type($fullLogoPath);
            $logoImage = 'data:' . $mimeType . ';base64,' . $logoData;
        }
    }

    // Dynamic Signature
    $signatureImage = null;
    if (!empty($directorSignature)) {
        $fullSigPath = public_path($directorSignature);
        if (file_exists($fullSigPath)) {
            $sigData = base64_encode(file_get_contents($fullSigPath));
            $sigMimeType = mime_content_type($fullSigPath);
            $signatureImage = 'data:' . $sigMimeType . ';base64,' . $sigData;
        }
    }
@endphp

<body>
    <!-- Background Image Container -->
    @if($bgImage)
        <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -1;">
            <img src="{{ $bgImage }}" style="width: 100%; height: 100%;">
        </div>
    @endif

    <div class="certificate-container">

        <!-- Logo -->
        <div class="logo-container">
            @if($logoImage)
                <img src="{{ $logoImage }}" style="max-height: 80px; max-width: 150px;">
            @else
                <div class="logo-box">
                    <div class="icon">
                        <!-- Simple Shape for PDF compatibility since SVG can be tricky -->
                        <span style="font-size: 20px;">&#9679;</span>
                    </div>
                    <div class="label">HOLA TAXI<br>Driver</div>
                </div>
            @endif
        </div>

        <!-- Header -->
        <div class="title">SHARE CERTIFICATE</div>
        <div class="company-name">COMPANY NAME: {{ $companyName }}</div>

        <div class="certify-text">THIS IS TO CERTIFY THAT</div>

        <!-- User Name -->
        <div class="user-name">{{ $userName }}</div>

        <!-- Underline with Dots -->
        <div class="name-underline">
            <div class="name-underline-dot-left"></div>
            <div class="name-underline-dot-right"></div>
        </div>

        <!-- Description -->
        <div class="description">
            of <span class="highlight">{{ $licenseNumber }}</span> holding license number is the registered holder
            of <span class="highlight">{{ number_format($shares) }}</span><br>
            Class A Ordinary Shares in the capital of {{ $companyName }}.
        </div>

        <!-- Bottom Section: Meta, Seal, Signature -->
        <div class="bottom-section">

            <!-- Left: Meta Data -->
            <div class="meta-data">
                <div class="meta-item">CERTIFICATE NO: {{ $certificateNumber }}</div>
                <div class="meta-item">DATE OF ISSUE: {{ $issuedDate }}</div>
            </div>

            <!-- Center: Seal -->
            <div class="seal-wrapper">
                <div class="ribbon-left"></div>
                <div class="ribbon-right"></div>
                <div class="seal"></div>
            </div>

            <!-- Right: Signature -->
            <div class="signature-section">
                @if($signatureImage)
                    <div style="margin-bottom: 5px;">
                        <img src="{{ $signatureImage }}" style="max-height: 70px; max-width: 200px;">
                    </div>
                @else
                    <div class="signature-name">{{ $directorName }}</div>
                @endif
                <div class="signature-line">DIRECTOR</div>
                <div class="director-text">{{ $directorName }}</div>
            </div>

        </div>
    </div>
</body>

</html>