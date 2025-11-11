<!DOCTYPE html>
<html>
<head>
    <title>Booking Request Received - {{ $senderName }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .booking-details { background: #f9f9f9; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .footer { margin-top: 30px; padding: 20px 0; border-top: 1px solid #f0f0f0; font-size: 14px; color: #666; }
    </style>
</head>
<body>
<h2>Dear {{ $booking->customer_name }},</h2>

<p>Thank you for your booking application at <strong>{{ $senderName }}</strong>!</p>

<div class="booking-details">
    <h3>Your Booking Details:</h3>
    <ul>
        <li><strong>Room:</strong> {{ $room->category->name }}</li>
        <li><strong>Check-in:</strong> {{ \Carbon\Carbon::parse($booking->check_in)->format('F j, Y') }}</li>
        <li><strong>Check-out:</strong> {{ \Carbon\Carbon::parse($booking->check_out)->format('F j, Y') }}</li>
        <li><strong>Duration:</strong> {{ $nights }} nights</li>
        <li><strong>Guests:</strong> {{ $booking->adults }} adults, {{ $booking->children }} children</li>
        <li><strong>Price per Night:</strong> ₦{{ number_format($room->price_per_night, 2) }}</li>
        <li><strong>Total Amount:</strong> ₦{{ number_format($booking->total_amount, 2) }}</li>
    </ul>
</div>

<p><strong>Thank you for coming this far. Our management will reach out to you through your email and WhatsApp with payment details. Payment confirms your reservation.</strong></p>

<div class="footer">
    <p>Best regards,<br>
        <strong>{{ $senderName }} Team</strong></p>

    @if($senderName == 'Shores Hotel')
        <p>Email: book_hotel@shoreshotelng.com</p>
    @else
        <p>Email: book_apartment@shoreshotelng.com</p>
    @endif
</div>
</body>
</html>
