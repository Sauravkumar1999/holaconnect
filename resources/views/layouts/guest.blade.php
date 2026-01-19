<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Hola Taxi Ireland - Registration Portal">
    <meta name="author" content="Hola Taxi Ireland">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Hola Taxi Ireland')</title>

    <!-- AdminKit CSS -->
    <link href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #3b7ddd;
            --success-color: #1cbb8c;
            --danger-color: #dc3545;
            --warning-color: #fcb92c;
            --info-color: #17a2b8;
            --dark-color: #212529;
            --light-color: #f8f9fa;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 600px;
            width: 100%;
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .auth-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2.5rem 2rem;
            text-align: center;
        }

        .auth-header h1 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .auth-header p {
            margin: 0.5rem 0 0;
            opacity: 0.9;
            font-size: 0.95rem;
        }

        .auth-body {
            padding: 2.5rem 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            display: block;
        }

        .form-control,
        .form-select {
            border: 2px solid #e1e8ed;
            border-radius: 10px;
            padding: 0.625rem 1rem;
            font-size: 0.9375rem;
            transition: all 0.3s ease;
            width: 100%;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 0.85rem;
            margin-top: 0.25rem;
            display: block;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 0.875rem 2rem;
            font-weight: 600;
            font-size: 1rem;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .form-check {
            display: flex;
            align-items: start;
            margin-bottom: 1rem;
        }

        .form-check-input {
            width: 1.25rem;
            height: 1.25rem;
            margin-right: 0.75rem;
            margin-top: 0.125rem;
            border: 2px solid #e1e8ed;
            border-radius: 5px;
            cursor: pointer;
            flex-shrink: 0;
        }

        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }

        .form-check-label {
            font-size: 0.9rem;
            color: #555;
            cursor: pointer;
        }

        .alert {
            border-radius: 10px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            border: none;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .text-center a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .text-center a:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        .file-upload-wrapper {
            position: relative;
        }

        .file-upload-wrapper input[type="file"] {
            cursor: pointer;
        }

        .payment-type-wrapper {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .radio-card {
            flex: 1;
            border: 2px solid #e1e8ed;
            border-radius: 10px;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }

        .radio-card:hover {
            border-color: #667eea;
            background-color: #f8f9ff;
        }

        .radio-card input[type="radio"] {
            margin-right: 0.75rem;
            width: 1.25rem;
            height: 1.25rem;
            cursor: pointer;
        }

        .radio-card input[type="radio"]:checked+label {
            color: #667eea;
            font-weight: 600;
        }

        .radio-card.active {
            border-color: #667eea;
            background-color: #f8f9ff;
        }

        .required-mark {
            color: #dc3545;
            margin-left: 2px;
        }

        @media (max-width: 768px) {
            .payment-type-wrapper {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    @yield('content')

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>