<!DOCTYPE html>
<html>
<head>
    <title>Booking Request</title>
</head>
<body>
<h2>Dear {{ $booking->customer_name }},</h2>

<p>Thank you for your booking application at {{ $senderName }}!</p>

<h3>Your Booking Details:</h3>
<ul>
    <li><strong>Check-in:</strong> {{ $booking->check_in }}</li>
    <li><strong>Check-out:</strong> {{ $booking->check_out }}</li>
    <li><strong>Guests:</strong> {{ $booking->adults }} adults, {{ $booking->children }} children</li>
    <li><strong>Total Amount:</strong> ₦{{ number_format($booking->total_amount, 2) }}</li>
</ul>

<p><strong>Thank you for coming this far. Our management will reach out to you through your email and WhatsApp with payment details. Payment confirms your reservation.</strong></p>

<p>Best regards,<br>{{ $senderName }}</p>
</body>
</html>
