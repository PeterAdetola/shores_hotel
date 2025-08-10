<?php
use Illuminate\Support\Facades\Auth;
use App\Models\RoomCategory;
use App\Models\Facility;


// Get user's id
if (!function_exists('getCurrentUser')) {
    function getCurrentUser()
    {
        $userId = Auth::id();

        return $userId;
    }
}

// Extract initials from user's name
if (!function_exists('getUserInitial')) {
    function getUserInitial()
    {
        $user = auth()->user();
        if ($user) {
            $name = $user->name;
            $initials = [];

            $nameParts = explode(' ', trim($name));
            $firstName = array_shift($nameParts);
            $lastName = array_pop($nameParts);
            $initials[$name] = (
                mb_substr($firstName,0,1) .
                mb_substr($lastName,0,1)
            );

            $initials = implode('', $initials);
            return $initials;
        } else {
            return redirect('login');
        }
    }
}

// Get user's name
if (!function_exists('getUserName')) {
    function getUserName()
    {
        $user = auth()->user();
        if ($user) {
            $name = $user->name;
            return $name;
        } else {
            return redirect('login');
        }
    }
}

// Get room categories
if (!function_exists('getRoomCategories')) {
    function getRoomCategories() {
        return RoomCategory::all();
    }
}

if (!function_exists('getFacilities')) {
    function getFacilities()
    {
        return Facility::orderBy('position')->get();
    }
}


if (!function_exists('redirect_with_notification')) {

    function redirect_with_notification(
        string $message,
        string $type = 'success',
        array $additional = [],
        string $route = null
    ) {
        $notification = array_merge([
            'message' => $message,
            'type' => $type
        ], $additional);

        return $route
            ? redirect()->route($route)->with($notification)
            : redirect()->back()->with($notification);
    }
}
