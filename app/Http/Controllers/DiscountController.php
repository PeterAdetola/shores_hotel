<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    /**
     * Show discount management page
     */
    public function index()
    {
        $roomsCount = Room::rooms()->count();
        $apartmentsCount = Room::apartments()->count();

        // Get current discount status
        $roomsDiscount = Room::rooms()->first();
        $apartmentsDiscount = Room::apartments()->first();

        return view('admin.discounts', compact(
            'roomsCount',
            'apartmentsCount',
            'roomsDiscount',
            'apartmentsDiscount'
        ));
    }

    /**
     * Apply discount to all rooms (room_type = 0)
     */
    public function applyToRooms(Request $request)
    {
        $request->validate([
            'discount_percentage' => 'required|numeric|min:0|max:100'
        ]);

        Room::rooms()->update([
            'has_discount' => true,
            'discount_percentage' => $request->discount_percentage
        ]);

        $count = Room::rooms()->count();

        return redirect()->back()->with([
            'message' => "Discount of {$request->discount_percentage}% applied to all {$count} room(s)!",
            'type' => 'success'
        ]);
    }

    /**
     * Apply discount to all apartments (room_type = 1)
     */
    public function applyToApartments(Request $request)
    {
        $request->validate([
            'discount_percentage' => 'required|numeric|min:0|max:100'
        ]);

        Room::apartments()->update([
            'has_discount' => true,
            'discount_percentage' => $request->discount_percentage
        ]);

        $count = Room::apartments()->count();

        return redirect()->back()->with([
            'message' => "Discount of {$request->discount_percentage}% applied to all {$count} apartment(s)!",
            'type' => 'success'
        ]);
    }

    /**
     * Remove discount from all rooms
     */
    public function removeFromRooms()
    {
        $count = Room::rooms()->count();

        Room::rooms()->update([
            'has_discount' => false,
            'discount_percentage' => 0
        ]);

        return redirect()->back()->with([
            'message' => "Discount removed from all {$count} room(s)!",
            'type' => 'success'
        ]);
    }

    /**
     * Remove discount from all apartments
     */
    public function removeFromApartments()
    {
        $count = Room::apartments()->count();

        Room::apartments()->update([
            'has_discount' => false,
            'discount_percentage' => 0
        ]);

        return redirect()->back()->with([
            'message' => "Discount removed from all {$count} apartment(s)!",
            'type' => 'success'
        ]);
    }

    /**
     * Apply discount to all (both rooms and apartments)
     */
    public function applyToAll(Request $request)
    {
        $request->validate([
            'discount_percentage' => 'required|numeric|min:0|max:100'
        ]);

        Room::query()->update([
            'has_discount' => true,
            'discount_percentage' => $request->discount_percentage
        ]);

        $count = Room::count();

//        return redirect()->back()->with([
//            'message' => "Discount of {$request->discount_percentage}% applied to all {$count} room(s) and apartment(s)!",
//            'type' => 'success'
//        ]);
        return redirect()->back()->with([
            'message' => "Discount applied  to all!",
            'type' => 'success'
        ]);
    }

    /**
     * Remove discount from all
     */
    public function removeFromAll()
    {
        $count = Room::count();

        Room::query()->update([
            'has_discount' => false,
            'discount_percentage' => 0
        ]);

//        return redirect()->back()->with([
//            'message' => "Discount removed from all {$count} room(s) and apartment(s)!",
//            'type' => 'success'
//        ]);
        return redirect()->back()->with([
            'message' => "Discount removed from all!",
            'type' => 'success'
        ]);
    }
}
