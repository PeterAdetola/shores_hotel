<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Reminder</title>
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
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
        .countdown {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            text-align: center;
            border-radius: 10px;
            margin: 20px 0;
        }
        .countdown-number {
            font-size: 48px;
            font-weight: bold;
            display: block;
            margin: 10px 0;
        }
        .booking-summary {
            background: #f8f9fa;
            border-radius: 5px;
            padding: 20px;
            margin: 20px 0;
        }
        .summary-item {
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        .summary-item:last-child {
            border-bottom: none;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #f5576c;
            color: white !important;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: 600;
        }
        .checklist {
            background: #e8f5e9;
            padding: 20px;
            border-radius: 5px;
            border-left: 4px solid #4caf50;
            margin: 20px 0;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Header -->
    <div class="header">
        <h1>⏰ Your Stay is Coming Up!</h1>
        <p style="margin: 10px 0 0 0; font-size: 16px;">Get ready for an amazing experience</p>
    </div>

    <!-- Content -->
    <div class="content">
        <p>Dear <strong>{{ $booking->guest_name ?? 'Valued Guest' }}</strong>,</p>

        <p>This is a friendly reminder that your stay at <strong>Shores Hotel</strong> is just around the corner! We're excited to welcome you.</p>

        <!-- Countdown -->
        <div class="countdown">
                <span class="countdown-number">
                    {{ \Carbon\Carbon::parse($booking->check_in_date)->diffInDays(now()) }}
                </span>
            <div style="font-size: 18px;">Days Until Check-in</div>
        </div>

        <!-- Booking Summary -->
        <div class="booking-summary">
            <h3 style="margin-top: 0; color: #667eea;">📋 Your Booking Summary</h3>

            <div class="summary-item">
                <strong>Booking Reference:</strong> #{{ $booking->id }}
            </div>

            <div class="summary-item">
                <strong>Check-in:</strong> {{ \Carbon\Carbon::parse($booking->check_in_date)->format('l, F d, Y') }} at 2:00 PM
            </div>

            <div class="summary-item">
                <strong>Check-out:</strong> {{ \Carbon\Carbon::parse($booking->check_out_date)->format('l, F d, Y') }} at 12:00 PM
            </div>

            <div class="summary-item">
                <strong>Room Type:</strong> {{ $booking->room_type ?? 'Standard Room' }}
            </div>

            <div class="summary-item">
                <strong>Number of Guests:</strong> {{ $booking->number_of_guests ?? '1' }}
            </div>
        </div>

        <!-- Pre-Arrival Checklist -->
        <div class="checklist">
            <h3 style="margin-top: 0;">✅ Pre-Arrival Checklist</h3>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li>Valid ID (Passport, Driver's License, or National ID)</li>
                <li>Payment method for incidentals</li>
                <li>This confirmation email</li>
                <li>Any special requests? <a href="mailto:{{ config('mail.from.address') }}" style="color: #667eea;">Let us know</a></li>
            </ul>
        </div>

        <!-- Hotel Location -->
        <h3 style="color: #667eea;">📍 How to Find Us</h3>
        <p>
            <strong>Shores Hotel</strong><br>
            {{ config('app.hotel_address', '123 Hotel Street, Lagos, Nigeria') }}<br>
            <br>
            <strong>Need directions?</strong><br>
            <a href="#" style="color: #667eea;">Get directions on Google Maps</a>
        </p>

        <!-- Contact Information -->
        <h3 style="color: #667eea;">📞 Need Assistance?</h3>
        <p>
            Our team is here to help!<br>
            Phone: {{ config('app.hotel_phone', '+234 XXX XXX XXXX') }}<br>
            Email: {{ config('mail.from.address') }}<br>
            WhatsApp: {{ config('app.hotel_whatsapp', '+234 XXX XXX XXXX') }}
        </p>

        <!-- Call to Action -->
        <div style="text-align: center;">
            <a href="{{ url('/bookings/' . $booking->id) }}" class="button">View Booking Details</a>
        </div>

        <p style="margin-top: 30px; text-align: center;">
            <strong>See you soon!</strong><br>
            We're looking forward to making your stay unforgettable.<br>
            <br>
            The Shores Hotel Team 🌟
        </p>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p style="margin: 0;">
            © {{ date('Y') }} Shores Hotel. All rights reserved.<br>
            <a href="{{ url('/') }}" style="color: #667eea; text-decoration: none;">Visit our website</a> |
            <a href="{{ url('/contact') }}" style="color: #667eea; text-decoration: none;">Contact Us</a>
        </p>
        <p style="margin-top: 10px; font-size: 12px; color: #999;">
            You're receiving this because you have an upcoming booking with us.
        </p>
    </div>
</div>
</body>
</html>
