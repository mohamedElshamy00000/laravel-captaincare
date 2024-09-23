<!-- payment.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Payment</title>
</head>
<body>
    <h2>Secure Payment</h2>

    <div id="paymentFrameContainer">
        <!-- Payment iframe will be dynamically inserted here -->
    </div>

    <script>
        function loadPaymobIframe(paymentKey, iframeId) {
            var iframeContainer = document.getElementById('paymentFrameContainer');
            var iframe = document.createElement('iframe');
            iframe.src = 'https://accept.paymob.com/api/acceptance/iframes/' + iframeId + '?payment_token=' + paymentKey;
            iframe.width = '100%';
            iframe.height = '600px'; // Adjust height as needed
            iframe.frameBorder = '0';
            iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
            iframe.allowFullscreen = true;
            iframeContainer.appendChild(iframe);
        }

        var paymentKey = '{{ $payment_key }}'; // Retrieve payment_key passed from controller
        var iframeId = '{{ $iframe_id }}'; // Retrieve iframe_id passed from controller

        loadPaymobIframe(paymentKey, iframeId);
    </script>
</body>
</html>
