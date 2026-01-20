<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Successful</title>
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
        .success-message {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .success-icon {
            color: #28a745;
            font-size: 3rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="success-message">
        <div class="success-icon">✓</div>
        <h2>Payment Successful!</h2>
        <p>Your payment has been processed successfully.</p>
        <p>Please wait while we complete your registration...</p>
    </div>
    <script>
        // Notify parent window that payment was successful
        if (window.parent) {
            window.parent.postMessage({
                type: 'payment_success',
                orderCode: '{{ $orderCode }}'
            }, '*');
        }
        
        // Also trigger status check in parent
        setTimeout(function() {
            if (window.parent) {
                window.parent.postMessage({
                    type: 'payment_completed',
                    orderCode: '{{ $orderCode }}'
                }, '*');
            }
        }, 1000);
    </script>
</body>
</html>
