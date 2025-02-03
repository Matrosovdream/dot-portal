<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Created</title>
</head>
<body>
    <h1>Hi, {{ $user->name }}!</h1>
    <p>Your order has been successfully created.</p>
    <p><strong>Order ID:</strong> {{ $order->id }}</p>
    <p><strong>Order Details:</strong> {{ $order->details }}</p> <!-- Adjust as per actual order fields -->
    <p>We’ll notify you once it’s processed. Thank you for choosing us!</p>
</body>
</html>
