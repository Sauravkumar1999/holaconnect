<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Failed</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: #f5f5f5;
        }
        .error-message {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .error-icon {
            color: #dc3545;
            font-size: 3rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="error-message">
        <div class="error-icon">✗</div>
        <h2>Payment Failed</h2>
        <p>Your payment could not be processed.</p>
        <p>Please try again.</p>
    </div>
    <script>
        // Notify parent window that payment failed
        if (window.parent) {
            window.parent.postMessage({
                type: 'payment_failed',
                orderCode: '{{ $orderCode }}'
            }, '*');
        }
    </script>
</body>
</html>
