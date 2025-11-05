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
            $initials[$name] = mb_substr($firstName, 0, 1) . mb_substr($lastName, 0, 1);

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

if (!function_exists('notification')) {
    function notification(
        string $message,
        string $type = 'success',
        bool $isAjax = false,
        array $additional = [],
        string $redirectRoute = null
    ) {
        $response = [
                'message' => $message,
                'type' => $type,
                'status' => $type === 'success' ? 'success' : 'error'
            ] + $additional;

        if ($isAjax) {
            return response()->json($response);
        }

        // Redirect to specific route if provided, otherwise back
        if ($redirectRoute) {
            return redirect()->route($redirectRoute)->with($response);
        }

        return redirect()->back()->with($response);
    }
}

// Load front matter from YAML files
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

if (!function_exists('getAvailbleAccommodation')) {
    function getAvailbleAccommodation()
    {
        try {
            return App\Models\RoomCategory::with([
                'rooms' => function($query) {
                    $query->where('availability', true)->orderBy('position');
                },
                'rooms.galleryImages',
                'rooms.featuredImage',
                'rooms.category'
            ])
                ->whereHas('rooms', function($query) {
                    $query->where('availability', true);
                })
                ->get();
        } catch (\Exception $e) {
            \Log::error('Error in getAvailbleAccommodation: ' . $e->getMessage());
            return collect();
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
                },
                'rooms.galleryImages',
                'rooms.featuredImage',
                'rooms.category'
            ])
                ->get();
        } catch (\Exception $e) {
            \Log::error('Error in getAllAccommodation: ' . $e->getMessage());
            return collect();
        }
    }
}

if (!function_exists('getRooms')) {
    function getRooms()
    {
        try {
            return App\Models\RoomCategory::with([
                'rooms' => function($query) {
                    $query->where('room_type', 0)->orderBy('position');
                },
                'rooms.galleryImages',
                'rooms.featuredImage',
                'rooms.category'
            ])
                ->whereHas('rooms', function($query) {
                    $query->where('room_type', 0);
                })
                ->get();
        } catch (\Exception $e) {
            \Log::error('Error in getRooms: ' . $e->getMessage());
            return collect();
        }
    }
}

if (!function_exists('getApartments')) {
    function getApartments()
    {
        try {
            return App\Models\RoomCategory::with([
                'rooms' => function($query) {
                    $query->where('room_type', 1)->orderBy('position');
                },
                'rooms.galleryImages',
                'rooms.featuredImage',
                'rooms.category'
            ])
                ->whereHas('rooms', function($query) {
                    $query->where('room_type', 1);
                })
                ->get();
        } catch (\Exception $e) {
            \Log::error('Error in getApartments: ' . $e->getMessage());
            return collect();
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
        $finalColor = $textColor ?: 'black';
        $finalWeight = $fontWeight ?: 'bolder';

        $styleString = "text-decoration: none; color: {$finalColor}; font-weight: {$finalWeight};";

        $url = "https://www.thepacmedia.com";
        $companyName = "Pacmedia Creatives";

        $linkHtml = "<a href=\"{$url}\" style=\"{$styleString}\">{$companyName}</a>";

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

if (!function_exists('getProcessedBookings')) {
    function getProcessedBookings($limit = 10)
    {
        return Booking::with('room.category')
            ->whereIn('status', ['confirmed', 'paid', 'cancelled', 'completed'])
            ->latest()
            ->take($limit)
            ->get();
    }
}

if (!function_exists('getUnprocessedBookings')) {
    function getUnprocessedBookings($limit = 10)
    {
        return Booking::with('room.category')
            ->whereIn('status', ['pending'])
            ->latest()
            ->take($limit)
            ->get();
    }
}

// Private-like helper for extracting contact sections
if (!function_exists('_getContactSection')) {
    function _getContactSection(string $sectionKey): array
    {
        $contactContent = loadFrontMatter('content/contact/contact.md') ?: [];
        return $contactContent[$sectionKey] ?? [];
    }
}

// Hotel Contact Helper
if (!function_exists('hotelContact')) {
    function hotelContact(): array
    {
        return _getContactSection('hotel_info');
    }
}

// Apartment Contact Helper
if (!function_exists('apartmentContact')) {
    function apartmentContact(): array
    {
        return _getContactSection('apartment_info');
    }
}
