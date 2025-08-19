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


// app/Helpers/NotificationHelper.php
if (!function_exists('notification')) {
    function notification(
        string $message,
        string $type = 'success',
        bool $isAjax = false,
        array $additional = []
    ) {
        $response = [
                'message' => $message,
                'type' => $type,
                'status' => $type === 'success' ? 'success' : 'error'
            ] + $additional;

        return $isAjax
            ? response()->json($response)
            : redirect()->back()->with($response);
    }
}
