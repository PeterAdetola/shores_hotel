<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Booking Request - {{ $senderName }}</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; line-height: 1.6; color: #333333; background-color: #ffffff; padding: 0; margin: 0;">
<div style="max-width: 600px; margin: 0 auto; background-color: #ffffff;">

    <!-- Header -->
    <div style="background-color: #f8f9fa; padding: 20px; text-align: center; border-radius: 5px; margin-bottom: 20px;">
        <h1 style="font-size: 24px; color: #2c3e50; margin: 0 0 10px 0; font-weight: bold;">🏨 New Booking Request</h1>
        <p style="font-size: 14px; color: #666666; margin: 0;">{{ now()->format('F j, Y \a\t g:i A') }}</p>
    </div>

    <!-- Customer Information -->
    <div style="background-color: #f9f9f9; padding: 15px; border-radius: 5px; margin: 15px 0; border: 1px solid #e0e0e0;">
        <h2 style="font-size: 18px; color: #2c3e50; margin: 0 0 15px 0; font-weight: bold; border-bottom: 2px solid #3498db; padding-bottom: 8px;">👤 Customer Information</h2>

        <div style="margin: 10px 0;">
            <div style="padding: 8px 0; border-bottom: 1px solid #e0e0e0;">
                <span style="font-weight: bold; color: #2c3e50; display: inline-block; min-width: 140px;">Name:</span>
                <span style="color: #555555;">{{ $booking->customer_name }}</span>
            </div>
            <div style="padding: 8px 0; border-bottom: 1px solid #e0e0e0;">
                <span style="font-weight: bold; color: #2c3e50; display: inline-block; min-width: 140px;">Email:</span>
                <span style="color: #555555;">
                        <a href="mailto:{{ $booking->customer_email }}?subject={{ urlencode('Reply regarding your booking - Ref: ' . $booking->id) }}" style="color: #3498db; text-decoration: none;">
                            {{ $booking->customer_email }}
                        </a>
                    </span>
            </div>
            <div style="padding: 8px 0;">
                <span style="font-weight: bold; color: #2c3e50; display: inline-block; min-width: 140px;">Phone:</span>
                <span style="color: #555555;">
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $booking->customer_phone) }}" style="color: #3498db; text-decoration: none;">
                            {{ $booking->customer_phone }}
                        </a>
                    </span>
            </div>
        </div>

        <div style="margin-top: 15px;">
            <a href="mailto:{{ $booking->customer_email }}?subject={{ urlencode('Reply regarding your booking - Ref: ' . $booking->id) }}" style="display: inline-block; background-color: #3498db; color: #ffffff; padding: 10px 20px; border-radius: 4px; text-decoration: none; font-weight: bold; margin: 5px 5px 5px 0;">
                ✉️ Send Email
            </a>
            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $booking->customer_phone) }}" style="display: inline-block; background-color: #28a745; color: #ffffff; padding: 10px 20px; border-radius: 4px; text-decoration: none; font-weight: bold; margin: 5px 5px 5px 0;">
                📞 Call Customer
            </a>
        </div>
    </div>

    <!-- Booking Details -->
    <div style="background-color: #f9f9f9; padding: 15px; border-radius: 5px; margin: 15px 0; border: 1px solid #e0e0e0;">
        <h2 style="font-size: 18px; color: #2c3e50; margin: 0 0 15px 0; font-weight: bold; border-bottom: 2px solid #3498db; padding-bottom: 8px;">📋 Booking Details</h2>

        <div style="margin: 10px 0;">
            <div style="padding: 8px 0; border-bottom: 1px solid #e0e0e0;">
                <span style="font-weight: bold; color: #2c3e50; display: inline-block; min-width: 140px;">Room Category:</span>
                <span style="color: #555555;">{{ $room->category->name }}</span>
            </div>
            <div style="padding: 8px 0; border-bottom: 1px solid #e0e0e0;">
                <span style="font-weight: bold; color: #2c3e50; display: inline-block; min-width: 140px;">Check-in Date:</span>
                <span style="color: #555555;">{{ \Carbon\Carbon::parse($booking->check_in)->format('l, F j, Y') }}</span>
            </div>
            <div style="padding: 8px 0; border-bottom: 1px solid #e0e0e0;">
                <span style="font-weight: bold; color: #2c3e50; display: inline-block; min-width: 140px;">Check-out Date:</span>
                <span style="color: #555555;">{{ \Carbon\Carbon::parse($booking->check_out)->format('l, F j, Y') }}</span>
            </div>
            <div style="padding: 8px 0; border-bottom: 1px solid #e0e0e0;">
                <span style="font-weight: bold; color: #2c3e50; display: inline-block; min-width: 140px;">Duration:</span>
                <span style="color: #555555;">{{ $nights }} {{ $nights == 1 ? 'night' : 'nights' }}</span>
            </div>
            <div style="padding: 8px 0; border-bottom: 1px solid #e0e0e0;">
                <span style="font-weight: bold; color: #2c3e50; display: inline-block; min-width: 140px;">Guests:</span>
                <span style="color: #555555;">
                        {{ $booking->adults }} {{ $booking->adults == 1 ? 'adult' : 'adults' }}@if($booking->children > 0), {{ $booking->children }} {{ $booking->children == 1 ? 'child' : 'children' }}@endif
                    </span>
            </div>
            <div style="padding: 8px 0;">
                <span style="font-weight: bold; color: #2c3e50; display: inline-block; min-width: 140px;">Booking Status:</span>
                <span style="display: inline-block; padding: 5px 12px; border-radius: 3px; font-weight: bold; font-size: 13px; text-transform: uppercase; background-color: #fff3cd; color: #856404;">
                        {{ ucfirst($booking->status) }}
                    </span>
            </div>
        </div>
    </div>

    <!-- Price Calculation -->
    <div style="background-color: #e9f7ef; padding: 15px; border-radius: 5px; margin: 15px 0; border-left: 4px solid #28a745;">
        <h2 style="font-size: 18px; color: #28a745; margin: 0 0 15px 0; font-weight: bold;">💰 Booking Calculation</h2>

        <div style="margin: 10px 0;">
            <div style="padding: 8px 0; border-bottom: 1px solid #c3e6cb;">
                <span style="font-weight: bold; color: #155724; display: inline-block; min-width: 140px;">Duration:</span>
                <span style="color: #155724;">{{ $nights }} {{ $nights == 1 ? 'night' : 'nights' }}</span>
            </div>
            <div style="padding: 8px 0; border-bottom: 1px solid #c3e6cb;">
                <span style="font-weight: bold; color: #155724; display: inline-block; min-width: 140px;">Price per Night:</span>
                <span style="color: #155724;">₦{{ number_format($room->price_per_night, 2) }}</span>
            </div>
            <div style="padding: 8px 0; border-bottom: 1px solid #c3e6cb;">
                <span style="font-weight: bold; color: #155724; display: inline-block; min-width: 140px;">Calculation:</span>
                <span style="color: #155724;">{{ $nights }} × ₦{{ number_format($room->price_per_night, 2) }}</span>
            </div>
        </div>

        <p style="font-size: 20px; font-weight: bold; color: #28a745; margin-top: 12px; padding-top: 12px; border-top: 2px solid #28a745;">
            <span style="font-weight: bold; color: #155724; display: inline-block; min-width: 140px;">Total Amount:</span>
            ₦{{ number_format($booking->total_amount, 2) }}
        </p>
    </div>

    <!-- Action Required -->
    <div style="background-color: #f9f9f9; padding: 15px; border-radius: 5px; margin: 15px 0; border: 1px solid #e0e0e0;">
        <h2 style="font-size: 18px; color: #2c3e50; margin: 0 0 15px 0; font-weight: bold; border-bottom: 2px solid #3498db; padding-bottom: 8px;">⚠️ Action Required</h2>

        <p style="margin: 8px 0; font-size: 14px; line-height: 1.6; color: #333333;">Please review this booking request and contact the customer to:</p>

        <ul style="margin: 10px 0 10px 20px; padding: 0; color: #333333;">
            <li style="margin: 5px 0; font-size: 14px;">Confirm availability for the requested dates</li>
            <li style="margin: 5px 0; font-size: 14px;">Provide payment instructions and details</li>
            <li style="margin: 5px 0; font-size: 14px;">Answer any questions they may have</li>
            <li style="margin: 5px 0; font-size: 14px;">Update the booking status in the system</li>
        </ul>

        <div style="margin-top: 15px;">
            <a href="mailto:{{ $booking->customer_email }}?subject={{ urlencode('Reply regarding your booking - Ref: ' . $booking->id) }}&body={{ urlencode('Dear ' . $booking->customer_name . ',

Thank you for your booking request. We are pleased to confirm the following details:

Room: ' . $room->category->name . '
Check-in: ' . \Carbon\Carbon::parse($booking->check_in)->format('F j, Y') . '
Check-out: ' . \Carbon\Carbon::parse($booking->check_out)->format('F j, Y') . '
Total Amount: ₦' . number_format($booking->total_amount, 2) . '

To confirm your reservation, please...

Best regards,
The Management') }}" style="display: inline-block; background-color: #3498db; color: #ffffff; padding: 10px 20px; border-radius: 4px; text-decoration: none; font-weight: bold; margin: 5px 5px 5px 0;">
                📧 Reply to Customer
            </a>
        </div>
    </div>

    <!-- Footer -->
    <div style="margin-top: 30px; padding: 20px 0; border-top: 1px solid #e0e0e0; text-align: center;">
        <p style="font-size: 12px; color: #666666; margin: 5px 0;"><strong>Booking Reference:</strong> {{ $booking->id }}</p>
        <p style="font-size: 12px; color: #666666; margin: 5px 0;">This email was automatically generated by the booking system</p>
        <p style="font-size: 12px; color: #666666; margin: 5px 0;">{{ config('app.name', 'Shores Hotel') }} | {{ now()->year }}</p>
    </div>

</div>
</body>
</html>
