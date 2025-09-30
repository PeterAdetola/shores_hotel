<?php
use Illuminate\Support\Facades\Auth;
use App\Models\RoomCategory;
use App\Models\Room;
use App\Models\Facility;
use Illuminate\Support\Facades\File;
use Symfony\Component\Yaml\Yaml;
use App\Models\Booking;


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

// Get room
if (!function_exists('getRoom')) {
    function getRoom($roomId) {
        return Room::find($roomId);
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

if (!function_exists('loadFrontMatter')) {
    function loadFrontMatter(string $relativePath): array
    {
        $path = storage_path('app/' . ltrim($relativePath, '/'));

        if (!File::exists($path)) {
            return [];
        }

        $raw = File::get($path) ?? '';

        // Extract YAML front matter between ---
        if (preg_match('/^---\s*\R(.*?)\R---\s*\R?/s', $raw, $m)) {
            return Yaml::parse($m[1]) ?? [];
        }

        // Or treat whole file as YAML if no delimiters
        return Yaml::parse($raw) ?? [];
    }
}

if (!function_exists('getContactContent')) {
    function getContactContent(): array
    {
        return loadFrontMatter('content/contact/contact.md') ?: [];
    }
}
//
//if (!function_exists('getAllAccommodation')) {
//    function getAllAccommodation()
//    {
//        return App\Models\RoomCategory::with([
//            'rooms.galleryImages',
//            'rooms.featuredImage'
//        ])->get();
//    }
//}

if (!function_exists('getAvailbleAccommodation')) {
    function getAvailbleAccommodation()
    {
        try {
            return App\Models\RoomCategory::with([
                'rooms' => function($query) {
                    $query->orderBy('position');
                    $query->where('availability', true)->orderBy('position');
                },
                'rooms.galleryImages',
                'rooms.featuredImage',
                'rooms.category' // Ensure category relationship is loaded
            ])
                ->whereHas('rooms', function($query) {
                    $query->where('availability', true);
                })
                ->get();
        } catch (\Exception $e) {
            \Log::error('Error in getAvailbleAccommodation: ' . $e->getMessage());
            return collect(); // Return empty collection on error
        }
    }
}


if (!function_exists('getAllAccommodation')) {
    function getAllAccommodation()
    {
        try {
            return App\Models\RoomCategory::with([
                'rooms' => function($query) {
                    $query->orderBy('position');
                    // Removed availability filter here
                },
                'rooms.galleryImages',
                'rooms.featuredImage',
                'rooms.category' // Ensure category relationship is loaded
            ])
                // Removed whereHas so it no longer filters categories
                ->get();
        } catch (\Exception $e) {
            \Log::error('Error in getAllAccommodation: ' . $e->getMessage());
            return collect(); // Return empty collection on error
        }
    }
}


if (!function_exists('getRooms')) {
    function getRooms()
    {
        try {
            return App\Models\RoomCategory::with([
                'rooms' => function($query) {
                    $query->orderBy('position');
                    $query->where('room_type', 0)->orderBy('position');
                },
                'rooms.galleryImages',
                'rooms.featuredImage',
                'rooms.category' // Ensure category relationship is loaded
            ])
                ->whereHas('rooms', function($query) {
                    $query->where('room_type', 0);
                })
                ->get();
        } catch (\Exception $e) {
            \Log::error('Error in getRooms: ' . $e->getMessage());
            return collect(); // Return empty collection on error
        }
    }
}


if (!function_exists('getApartments')) {
    function getApartments()
    {
        try {
            return App\Models\RoomCategory::with([
                'rooms' => function($query) {
                    $query->orderBy('position');
                    $query->where('room_type', 1)->orderBy('position');
                },
                'rooms.galleryImages',
                'rooms.featuredImage',
                'rooms.category' // Ensure category relationship is loaded
            ])
                ->whereHas('rooms', function($query) {
                    $query->where('room_type', 1);
                })
                ->get();
        } catch (\Exception $e) {
            \Log::error('Error in getApartments: ' . $e->getMessage());
            return collect(); // Return empty collection on error
        }
    }
}

if (!function_exists('getAllRooms')) {
    function getAllRooms() {
        return \App\Models\Room::with('category')->get();
    }
}


if (!function_exists('signature')) {
    function signature($textColor = 'black', $fontWeight = 'bolder'): string
    {
        // Use default values if parameters are null (though Blade usually passes strings)
        $finalColor = $textColor ?: 'black';
        $finalWeight = $fontWeight ?: 'bolder';

        // Link style: No underline, dynamic color, dynamic weight
        $styleString = "text-decoration: none; color: {$finalColor}; font-weight: {$finalWeight};";

        $url = "https://www.thepacmedia.com";
        $companyName = "Pacmedia Creatives";

        // Construct the anchor tag. Note the escaped quotes.
        $linkHtml = "<a href=\"{$url}\" style=\"{$styleString}\">{$companyName}</a>";

        // Wrap the link in a <p> tag and return the complete block
        return "<span>{$linkHtml}</span>";
    }
}

if (!function_exists('getAllBookings')) {
    function getAllBookings($limit = 10)
    {
        return Booking::with('room.category')
            ->latest()
            ->take($limit)
            ->get();
    }
}
