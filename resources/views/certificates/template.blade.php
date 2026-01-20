<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Share Certificate</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            width: 1920px;
            height: 1080px;
            overflow: hidden;
        }

        .certificate-container {
            width: 1920px;
            height: 1080px;
            position: relative;
            background-image: url('{{ asset('images/certificate-bg.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .certificate-content {
            position: absolute;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .name-field {
            position: absolute;
            top: 395px;
            left: 50%;
            transform: translateX(-50%);
            font-family: 'Times New Roman', serif;
            font-size: 48px;
            font-weight: bold;
            color: #0f2f6f;
            text-align: center;
            width: 800px;
            border-bottom: 2px solid #0f2f6f;
            padding-bottom: 5px;
        }

        .license-field {
            position: absolute;
            top: 432px;
            left: 215px;
            font-family: 'Times New Roman', serif;
            font-size: 24px;
            color: #0f2f6f;
            font-weight: 600;
        }

        .shares-field {
            position: absolute;
            top: 432px;
            right: 455px;
            font-family: 'Times New Roman', serif;
            font-size: 24px;
            color: #0f2f6f;
            font-weight: 600;
        }

        .certificate-number {
            position: absolute;
            top: 565px;
            left: 365px;
            font-family: 'Times New Roman', serif;
            font-size: 20px;
            color: #0f2f6f;
            font-weight: 600;
        }

        .date-field {
            position: absolute;
            top: 600px;
            left: 280px;
            font-family: 'Times New Roman', serif;
            font-size: 20px;
            color: #0f2f6f;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="certificate-container">
        <div class="certificate-content">
            <div class="name-field">{{ $userName }}</div>
            <div class="license-field">{{ $licenseNumber }}</div>
            <div class="shares-field">{{ number_format($shares) }}</div>
            <div class="certificate-number">{{ $certificateNumber }}</div>
            <div class="date-field">{{ $issuedDate }}</div>
        </div>
    </div>
</body>

</html>