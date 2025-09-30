<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;

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
        $guestDetails = $request->validate([
            'customer_name' => 'required|string',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string'
        ]);

        // Merge with session data
        $roomDetails = session('booking_step1');

        // Get room
        $room = Room::with('category')->findOrFail($roomDetails['room_id']);

        // Calculate total amount
        $nights = Carbon::parse($roomDetails['check_out'])->diffInDays(Carbon::parse($roomDetails['check_in']));
        $total_amount = $room->price_per_night * $nights;

        // Create booking
        $booking = Booking::create(array_merge($roomDetails, $guestDetails, [
            'total_amount'   => $total_amount,
            'status'         => 'pending',
            'lodging_type'  => $room->category->name, // ✅ Save category name directly
        ]));

        // Clear session
        session()->forget('booking_step1');

        return redirect()->route('bookedSuccessfully', $booking->id)
            ->with('success', 'Your booking request has been received. Please check your email for payment details.');
    }
    public function getAllBookings()
    {
        // Fetch bookings with the related room & category just in case
        $bookings = Booking::with('room.category')->latest()->get();

        return view('admin.bookings.all_bookings', compact('bookings'));
    }


}
