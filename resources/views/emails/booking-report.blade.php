<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Booking Request - {{ $senderName }}</title>
    <style>
        /* Reset and base styles that work with email-body-content wrapper */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #ffffff;
            padding: 0;
            margin: 0;
        }

        /* Main container */
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }

        /* Header section */
        .email-header {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .email-header h1 {
            font-size: 24px;
            color: #2c3e50;
            margin: 0 0 10px 0;
            font-weight: bold;
        }

        .email-header p {
            font-size: 14px;
            color: #666666;
            margin: 0;
        }

        /* Content sections */
        .content-section {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            border: 1px solid #e0e0e0;
        }

        .content-section h2 {
            font-size: 18px;
            color: #2c3e50;
            margin: 0 0 15px 0;
            font-weight: bold;
            border-bottom: 2px solid #3498db;
            padding-bottom: 8px;
        }

        .content-section p {
            margin: 8px 0;
            font-size: 14px;
            line-height: 1.6;
        }

        .content-section strong {
            color: #2c3e50;
            font-weight: bold;
        }

        /* Calculation box with highlight */
        .calculation-box {
            background-color: #e9f7ef;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            border-left: 4px solid #28a745;
        }

        .calculation-box h2 {
            font-size: 18px;
            color: #28a745;
            margin: 0 0 15px 0;
            font-weight: bold;
        }

        .calculation-box p {
            margin: 8px 0;
            font-size: 14px;
        }

        .calculation-box .total {
            font-size: 18px;
            font-weight: bold;
            color: #28a745;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 2px solid #28a745;
        }

        /* Links */
        a {
            color: #3498db;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Action buttons */
        .action-link {
            display: inline-block;
            background-color: #3498db;
            color: #ffffff !important;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            margin: 5px 5px 5px 0;
        }

        .action-link:hover {
            background-color: #2980b9;
            text-decoration: none;
        }

        .action-link.phone {
            background-color: #28a745;
        }

        .action-link.phone:hover {
            background-color: #218838;
        }

        /* Footer */
        .email-footer {
            margin-top: 30px;
            padding: 20px 0;
            border-top: 1px solid #e0e0e0;
            text-align: center;
        }

        .email-footer p {
            font-size: 12px;
            color: #666666;
            margin: 5px 0;
        }

        /* Status badge */
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 13px;
            text-transform: uppercase;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-confirmed {
            background-color: #d4edda;
            color: #155724;
        }

        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }

        /* Info grid for better layout */
        .info-grid {
            margin: 10px 0;
        }

        .info-row {
            padding: 8px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: bold;
            color: #2c3e50;
            display: inline-block;
            min-width: 140px;
        }

        .info-value {
            color: #555555;
        }

        /* Responsive adjustments */
        @media only screen and (max-width: 600px) {
            .email-container {
                padding: 10px;
            }

            .email-header h1 {
                font-size: 20px;
            }

            .content-section,
            .calculation-box {
                padding: 12px;
            }

            .action-link {
                display: block;
                margin: 5px 0;
                text-align: center;
            }
        }
    </style>
</head>
<body>
<div class="email-container">
    <!-- Header -->
    <div class="email-header">
        <h1>🏨 New Booking Request</h1>
        <p>{{ now()->format('F j, Y \a\t g:i A') }}</p>
    </div>

    <!-- Customer Information -->
    <div class="content-section">
        <h2>👤 Customer Information</h2>
        <div class="info-grid">
            <div class="info-row">
                <span class="info-label">Name:</span>
                <span class="info-value">{{ $booking->customer_name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value">
                        <a href="mailto:{{ $booking->customer_email }}?subject={{ urlencode('Reply regarding your booking - Ref: ' . $booking->id) }}">
                            {{ $booking->customer_email }}
                        </a>
                    </span>
            </div>
            <div class="info-row">
                <span class="info-label">Phone:</span>
                <span class="info-value">
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $booking->customer_phone) }}">
                            {{ $booking->customer_phone }}
                        </a>
                    </span>
            </div>
        </div>
        <div style="margin-top: 15px;">
            <a href="mailto:{{ $booking->customer_email }}?subject={{ urlencode('Reply regarding your booking - Ref: ' . $booking->id) }}" class="action-link">
                ✉️ Send Email
            </a>
            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $booking->customer_phone) }}" class="action-link phone">
                📞 Call Customer
            </a>
        </div>
    </div>

    <!-- Booking Details -->
    <div class="content-section">
        <h2>📋 Booking Details</h2>
        <div class="info-grid">
            <div class="info-row">
                <span class="info-label">Room Category:</span>
                <span class="info-value">{{ $room->category->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Check-in Date:</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($booking->check_in)->format('l, F j, Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Check-out Date:</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($booking->check_out)->format('l, F j, Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Duration:</span>
                <span class="info-value">{{ $nights }} {{ $nights == 1 ? 'night' : 'nights' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Guests:</span>
                <span class="info-value">
                        {{ $booking->adults }} {{ $booking->adults == 1 ? 'adult' : 'adults' }}
                    @if($booking->children > 0)
                        , {{ $booking->children }} {{ $booking->children == 1 ? 'child' : 'children' }}
                    @endif
                    </span>
            </div>
            <div class="info-row">
                <span class="info-label">Booking Status:</span>
                <span class="info-value">
                        <span class="status-badge status-{{ strtolower($booking->status) }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </span>
            </div>
        </div>
    </div>

    <!-- Price Calculation -->
    <div class="calculation-box">
        <h2>💰 Booking Calculation</h2>
        <div class="info-grid">
            <div class="info-row">
                <span class="info-label">Duration:</span>
                <span class="info-value">{{ $nights }} {{ $nights == 1 ? 'night' : 'nights' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Price per Night:</span>
                <span class="info-value">₦{{ number_format($room->price_per_night, 2) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Calculation:</span>
                <span class="info-value">{{ $nights }} × ₦{{ number_format($room->price_per_night, 2) }}</span>
            </div>
        </div>
        <p class="total">
            <span class="info-label">Total Amount:</span>
            ₦{{ number_format($booking->total_amount, 2) }}
        </p>
    </div>

    <!-- Action Required -->
    <div class="content-section">
        <h2>⚠️ Action Required</h2>
        <p>Please review this booking request and contact the customer to:</p>
        <ul style="margin: 10px 0 10px 20px; padding: 0;">
            <li style="margin: 5px 0;">Confirm availability for the requested dates</li>
            <li style="margin: 5px 0;">Provide payment instructions and details</li>
            <li style="margin: 5px 0;">Answer any questions they may have</li>
            <li style="margin: 5px 0;">Update the booking status in the system</li>
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
The Management') }}" class="action-link">
                📧 Reply to Customer
            </a>
        </div>
    </div>

    <!-- Footer -->
    <div class="email-footer">
        <p><strong>Booking Reference:</strong> {{ $booking->id }}</p>
        <p>This email was automatically generated by the booking system</p>
        <p>{{ config('app.name', 'Hotel Management System') }} | {{ now()->year }}</p>
    </div>
</div>
</body>
</html>
