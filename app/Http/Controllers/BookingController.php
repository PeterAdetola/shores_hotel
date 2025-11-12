<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
use App\Mail\BookingRequestMail;
use App\Mail\BookingReportMail;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    public function storeRoomDetails(Request $request)
    {
        $roomDetails = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'adults' => 'required|integer|min:1',
            'children' => 'integer|min:0',
        ]);

//        dd($request->all(), $roomDetails);
        // Store in session
        session(['booking_step1' => $roomDetails]);

        return redirect()->route('confirmReservation');
    }

    public function storeBooking(Request $request)
    {
        \Log::info("=== START storeBooking ===");

        $guestDetails = $request->validate([
            'customer_name' => 'required|string',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string'
        ]);

        \Log::info("Guest details validated", $guestDetails);

        // Merge with session data
        $roomDetails = session('booking_step1');
        \Log::info("Room details from session", $roomDetails ?? ['session' => 'empty']);

        if (!$roomDetails) {
            \Log::error("No room details found in session!");
            return redirect()->back()->with('error', 'Session expired. Please start over.');
        }

        // Get room with category
        $room = Room::with('category')->findOrFail($roomDetails['room_id']);
        \Log::info("Room found", ['room_id' => $room->id, 'room_type' => $room->room_type]);

        // Debug date values from session
        \Log::info("Raw date values from session", [
            'check_in_raw' => $roomDetails['check_in'],
            'check_out_raw' => $roomDetails['check_out'],
        ]);

        // Parse dates with m/d/Y format (we know this works from logs)
        $checkIn = Carbon::createFromFormat('m/d/Y', $roomDetails['check_in'])->startOfDay();
        $checkOut = Carbon::createFromFormat('m/d/Y', $roomDetails['check_out'])->startOfDay();

        \Log::info("Parsed dates", [
            'check_in' => $checkIn->format('Y-m-d H:i:s'),
            'check_out' => $checkOut->format('Y-m-d H:i:s'),
            'check_in_timestamp' => $checkIn->timestamp,
            'check_out_timestamp' => $checkOut->timestamp
        ]);

        // FIX: Use multiple methods to calculate nights and ensure positive value
        $nights1 = $checkOut->diffInDays($checkIn);
        $nights2 = abs($checkOut->diffInDays($checkIn)); // Absolute value as fallback
        $nights3 = max(0, $checkOut->diffInDays($checkIn)); // Ensure non-negative

        // Manual calculation as final fallback
        $manualNights = (int) ceil(($checkOut->timestamp - $checkIn->timestamp) / (60 * 60 * 24));

        \Log::info("Night calculation methods", [
            'diffInDays' => $nights1,
            'abs_diffInDays' => $nights2,
            'max_diffInDays' => $nights3,
            'manual_calculation' => $manualNights,
            'timestamp_diff_seconds' => ($checkOut->timestamp - $checkIn->timestamp),
            'timestamp_diff_days' => ($checkOut->timestamp - $checkIn->timestamp) / (60 * 60 * 24)
        ]);

        // Use the manual calculation as it's most reliable
        $nights = abs(max(1, $manualNights)); // Ensure at least 1 night
        $total_amount = $room->price_per_night * $nights;

        \Log::info("Final calculation", [
            'nights_used' => $nights,
            'price_per_night' => $room->price_per_night,
            'total_amount' => $total_amount
        ]);

        // Create booking - use the properly formatted dates
        $booking = Booking::create(array_merge($roomDetails, $guestDetails, [
            'check_in' => $checkIn->format('Y-m-d'),
            'check_out' => $checkOut->format('Y-m-d'),
            'total_amount' => $total_amount,
            'status' => 'pending',
            'lodging_type' => $room->category->name,
        ]));

        \Log::info("Booking created", [
            'booking_id' => $booking->id,
            'customer_email' => $booking->customer_email,
            'customer_name' => $booking->customer_name
        ]);

        // Send confirmation email based on accommodation type
        \Log::info("Calling sendBookingRequestEmail...");
        $this->sendBookingRequestEmail($booking, $room);
        \Log::info("Returned from sendBookingRequestEmail");

        // Clear session
        session()->forget('booking_step1');
        \Log::info("Session cleared");

        \Log::info("=== END storeBooking ===");

        return redirect()->route('bookedSuccessfully', $booking->id)
            ->with('success', 'Your booking request has been received. Please check your email for payment details.');
    }

//    private function sendBookingRequestEmail(Booking $booking, Room $room)
    private function sendBookingRequestEmail(Booking $booking, Room $room)
    {
        try {
            \Log::info("=== START sendBookingRequestEmail ===");
            \Log::info("Booking ID: {$booking->id}, Email: {$booking->customer_email}");

            // Determine sender email and name based on room type
            if ($room->room_type == 0) {
                $senderEmail = 'book_hotel@shoreshotelng.com';
                $senderName = 'Shores Hotel';
                $reportEmail = 'book_hotel@shoreshotelng.com'; // Report to hotel
            } else {
                $senderEmail = 'book_apartment@shoreshotelng.com';
                $senderName = 'Shores Apartment';
                $reportEmail = 'book_apartment@shoreshotelng.com'; // Report to apartment
            }

            \Log::info("Using sender: {$senderEmail} ({$senderName})");
            \Log::info("Report will be sent to: {$reportEmail}");

            // Send confirmation email to customer
            Mail::to($booking->customer_email)->send(new BookingRequestMail($booking, $senderEmail, $senderName, $room));
            \Log::info("Confirmation email sent to customer successfully");

            // Send report email to management
            $this->sendBookingReportEmail($booking, $room, $reportEmail, $senderName);
            \Log::info("Report email sent to management successfully");

            // Log the email
            try {
                $emailLog = \App\Models\EmailLog::create([
                    'booking_id' => $booking->id,
                    'recipient' => $booking->customer_email,
                    'subject' => "Booking Application Received - {$senderName}",
                    'message' => "Booking confirmation email sent to {$booking->customer_name} for {$senderName}. Room category: {$room->category->name}",
                    'type' => 'email',
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);
                \Log::info("Email log created with ID: " . ($emailLog->id ?? 'unknown'));
            } catch (\Exception $logException) {
                \Log::warning("Failed to create email log: " . $logException->getMessage());
            }

            \Log::info("=== END sendBookingRequestEmail - SUCCESS ===");

        } catch (\Exception $e) {
            \Log::error('Booking Request Email Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
        }
    }

    /**
     * Send booking report email to management
     * FIXED VERSION - Using Mailable class
     */
    private function sendBookingReportEmail(Booking $booking, Room $room, $reportEmail, $senderName)
    {
        try {
            $nights = abs(\Carbon\Carbon::parse($booking->check_out)->diffInDays(\Carbon\Carbon::parse($booking->check_in)));

            // Use Mailable class for proper HTML handling
            Mail::to($reportEmail)
                ->send(new BookingReportMail($booking, $room, $senderName, $nights));

            \Log::info("Booking report sent to: {$reportEmail}");

        } catch (\Exception $e) {
            \Log::error('Booking Report Email Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
        }
    }

    // Add this to your BookingController
    public function testEmailDelivery($bookingId = null)
    {
        try {
            $booking = $bookingId ? Booking::findOrFail($bookingId) : Booking::latest()->first();
            if (!$booking) {
                return "No bookings found";
            }

            $room = Room::find($booking->room_id);

            \Log::info("=== TEST EMAIL DELIVERY ===");
            \Log::info("Testing with booking ID: " . $booking->id);

            $this->sendBookingRequestEmail($booking, $room);

            \Log::info("=== END TEST ===");

            return "Email test completed for booking ID: " . $booking->id . ". Check logs.";

        } catch (\Exception $e) {
            \Log::error('Test error: ' . $e->getMessage());
            return "Error: " . $e->getMessage();
        }
    }

    // In BookingController.php
    public function testBookingEmail($bookingId)
    {
        try {
            $booking = Booking::findOrFail($bookingId);
            $room = Room::findOrFail($booking->room_id);

            $this->sendBookingRequestEmail($booking, $room);

            return "Booking email sent for booking ID: {$bookingId} to {$booking->customer_email}";
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function getAllBookings()
    {
        // Fetch bookings with the related room & category just in case
        $bookings = Booking::with('room.category')->latest()->get();

        return view('admin.bookings.all_bookings', compact('bookings'));
    }

    public function getProcessedBookings()
    {
        // Fetch only confirmed or cancelled bookings with related room & category
        $bookings = Booking::with('room.category')
            ->whereIn('status', ['confirmed', 'paid', 'cancelled', 'completed'])
            ->latest()
            ->get();

        return view('admin.bookings.processed_bookings', compact('bookings'));
    }

    public function getUnprocessedBookings()
    {
        // Fetch only pending bookings with related room & category
        $bookings = Booking::with('room.category')
            ->whereIn('status', ['pending'])
            ->latest()
            ->get();

        return view('admin.bookings.unprocessed_bookings', compact('bookings'));
    }

    /**
     * Send email to booking guest
     */
    public function sendEmail(Request $request, $id)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'attachments.*' => 'nullable|file|max:10240',
        ]);

        try {
            $booking = \App\Models\Booking::findOrFail($id);

            // Prepare attachments
            $attachments = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $attachments[] = $file->getRealPath();
                }
            }

            // Send email
            $sent = BookingEmailHelper::sendToGuest(
                $booking,
                $validated['subject'],
                $validated['message'],
                $attachments
            );

            if ($sent) {
                // Log the email activity
                \App\Models\EmailLog::create([
                    'booking_id' => $booking->id,
                    'recipient' => $booking->customer_email,
                    'subject' => $validated['subject'],
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Email sent successfully to ' . $booking->customer_email
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send email'
                ], 500);
            }

        } catch (\Exception $e) {
            \Log::error('Booking Email Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }





}
