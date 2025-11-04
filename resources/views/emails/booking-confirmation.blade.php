<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .content {
            padding: 30px;
        }
        .booking-details {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #666;
        }
        .detail-value {
            color: #333;
            font-weight: 500;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            font-size: 14px;
            color: #666;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #667eea;
            color: white !important;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: 600;
        }
        .highlight {
            background: #fff3cd;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            border-left: 4px solid #ffc107;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Header -->
    <div class="header">
        <h1>🎉 Booking Confirmed!</h1>
        <p style="margin: 10px 0 0 0; font-size: 16px;">Thank you for choosing Shores Hotel</p>
    </div>

    <!-- Content -->
    <div class="content">
        <p>Dear <strong>{{ $booking->guest_name ?? 'Valued Guest' }}</strong>,</p>

        <p>We are delighted to confirm your reservation at <strong>Shores Hotel</strong>. Your comfort is our priority, and we look forward to making your stay memorable!</p>

        <!-- Booking Details -->
        <div class="booking-details">
            <h3 style="margin-top: 0; color: #667eea;">📋 Booking Details</h3>

            <div class="detail-row">
                <span class="detail-label">Booking Reference:</span>
                <span class="detail-value">#{{ $booking->id ?? 'N/A' }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Guest Name:</span>
                <span class="detail-value">{{ $booking->guest_name ?? 'N/A' }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Check-in Date:</span>
                <span class="detail-value">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('l, F d, Y') }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Check-out Date:</span>
                <span class="detail-value">{{ \Carbon\Carbon::parse($booking->check_out_date)->format('l, F d, Y') }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Number of Nights:</span>
                <span class="detail-value">{{ \Carbon\Carbon::parse($booking->check_in_date)->diffInDays($booking->check_out_date) }} nights</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Room Type:</span>
                <span class="detail-value">{{ $booking->room_type ?? 'Standard Room' }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Number of Guests:</span>
                <span class="detail-value">{{ $booking->number_of_guests ?? '1' }}</span>
            </div>

            @if(isset($booking->total_amount))
                <div class="detail-row">
                    <span class="detail-label">Total Amount:</span>
                    <span class="detail-value" style="font-size: 18px; color: #667eea;">₦{{ number_format($booking->total_amount, 2) }}</span>
                </div>
            @endif
        </div>

        <!-- Important Information -->
        <div class="highlight">
            <strong>⏰ Check-in & Check-out Times:</strong><br>
            Check-in: 2:00 PM<br>
            Check-out: 12:00 PM (Noon)
        </div>

        <!-- What to Bring -->
        <h3 style="color: #667eea;">📌 What to Bring</h3>
        <ul style="line-height: 1.8;">
            <li>Valid government-issued ID (Driver's License, Passport, or National ID)</li>
            <li>Credit/Debit card for incidentals (if applicable)</li>
            <li>Booking confirmation (this email)</li>
        </ul>

        <!-- Hotel Information -->
        <h3 style="color: #667eea;">📍 Hotel Information</h3>
        <p>
            <strong>Shores Hotel</strong><br>
            {{ config('app.hotel_address', '123 Hotel Street, Lagos, Nigeria') }}<br>
            Phone: {{ config('app.hotel_phone', '+234 XXX XXX XXXX') }}<br>
            Email: {{ config('mail.from.address') }}
        </p>

        <!-- Call to Action -->
        <div style="text-align: center;">
            <a href="{{ url('/') }}" class="button">View Your Booking</a>
        </div>

        <p>If you have any questions or need to make changes to your reservation, please don't hesitate to contact us.</p>

        <p style="margin-top: 30px;">
            <strong>We can't wait to welcome you!</strong><br>
            The Shores Hotel Team
        </p>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p style="margin: 0;">
            © {{ date('Y') }} Shores Hotel. All rights reserved.<br>
            <a href="{{ url('/') }}" style="color: #667eea; text-decoration: none;">Visit our website</a> |
            <a href="{{ url('/contact') }}" style="color: #667eea; text-decoration: none;">Contact Us</a>
        </p>
    </div>
</div>
</body>
</html>
