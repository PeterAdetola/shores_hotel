<?php
use Illuminate\Support\Facades\Auth;
use App\Models\RoomCategory;
use App\Models\Room;
use App\Models\Facility;
use Illuminate\Support\Facades\File;
use Symfony\Component\Yaml\Yaml;
use App\Models\Booking;
use Illuminate\Support\Facades\Cache;

// Get user's id
if (!function_exists('getCurrentUser')) {
    function getCurrentUser()
    {
        return Auth::id();
    }
}

// Extract initials from user's name
if (!function_exists('getUserInitial')) {
    function getUserInitial()
    {
        $user = auth()->user();
        if ($user) {
            $name = $user->name;
            $nameParts = explode(' ', trim($name));
            $firstName = array_shift($nameParts);
            $lastName = array_pop($nameParts);
            return mb_substr($firstName, 0, 1) . mb_substr($lastName, 0, 1);
        }
        return null;
    }
}

// Get user's name
if (!function_exists('getUserName')) {
    function getUserName()
    {
        return auth()->user() ? auth()->user()->name : null;
    }
}

// Get room categories - CACHED
if (!function_exists('getRoomCategories')) {
    function getRoomCategories() {
        return Cache::remember('room_categories_all', 3600, function() {
            return RoomCategory::all();
        });
    }
}

// Get room
if (!function_exists('getRoom')) {
    function getRoom($roomId) {
        return Room::find($roomId);
    }
}

// Get Facilities - CACHED
if (!function_exists('getFacilities')) {
    function getFacilities()
    {
        return Cache::remember('facilities_list', 3600, function() {
            return Facility::orderBy('position')->get();
        });
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
        if (!File::exists($path)) return [];

        $raw = File::get($path) ?? '';
        if (preg_match('/^---\s*\R(.*?)\R---\s*\R?/s', $raw, $m)) {
            return Yaml::parse($m[1]) ?? [];
        }
        return Yaml::parse($raw) ?? [];
    }
}

if (!function_exists('getContactContent')) {
    function getContactContent(): array
    {
        return loadFrontMatter('content/contact/contact.md') ?: [];
    }
}

// Available Accommodation - CACHED
if (!function_exists('getAvailbleAccommodation')) {
    function getAvailbleAccommodation()
    {
        return Cache::remember('accommodation_available', 3600, function() {
            try {
                return RoomCategory::with([
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
        });
    }
}

// All Accommodation - CACHED
if (!function_exists('getAllAccommodation')) {
    function getAllAccommodation()
    {
        return Cache::remember('accommodation_all', 3600, function() {
            try {
                return RoomCategory::with([
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
        });
    }
}

// Rooms by type - CACHED
if (!function_exists('getRooms')) {
    function getRooms()
    {
        return Cache::remember('rooms_type_0', 3600, function() {
            try {
                return RoomCategory::with([
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
        });
    }
}

// Apartments by type - CACHED
if (!function_exists('getApartments')) {
    function getApartments()
    {
        return Cache::remember('rooms_type_1', 3600, function() {
            try {
                return RoomCategory::with([
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
        });
    }
}

if (!function_exists('getAllRooms')) {
    function getAllRooms() {
        return Cache::remember('rooms_list_basic', 3600, function() {
            return \App\Models\Room::with('category')->get();
        });
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
        return "<span><a href=\"{$url}\" style=\"{$styleString}\">{$companyName}</a></span>";
    }
}

if (!function_exists('getAllBookings')) {
    function getAllBookings($limit = 10)
    {
        return Booking::with('room.category')->latest()->take($limit)->get();
    }
}

if (!function_exists('getProcessedBookings')) {
    function getProcessedBookings($limit = 10)
    {
        return Booking::with('room.category')
            ->whereIn('status', ['confirmed', 'paid', 'cancelled', 'completed'])
            ->latest()->take($limit)->get();
    }
}

if (!function_exists('getUnprocessedBookings')) {
    function getUnprocessedBookings($limit = 10)
    {
        return Booking::with('room.category')
            ->whereIn('status', ['pending'])
            ->latest()->take($limit)->get();
    }
}

if (!function_exists('_getContactSection')) {
    function _getContactSection(string $sectionKey): array
    {
        $contactContent = loadFrontMatter('content/contact/contact.md') ?: [];
        return $contactContent[$sectionKey] ?? [];
    }
}

if (!function_exists('hotelContact')) {
    function hotelContact(): array
    {
        return _getContactSection('hotel_info');
    }
}

if (!function_exists('apartmentContact')) {
    function apartmentContact(): array
    {
        return _getContactSection('apartment_info');
    }
}

//if (!function_exists('getPublishedAnnouncement')) {
//    function getPublishedAnnouncement()
//    {
//        return Cache::remember('published_announcement', 3600, function() {
//            return \App\Models\Announcement::where('is_published', 1)->first();
//        });
//    }
//}

if (!function_exists('getPublishedAnnouncement')) {
    function getPublishedAnnouncement()
    {
        return Cache::remember('published_announcement', 3600, function() {
            return \App\Models\Announcement::where('is_published', 1)->first();
        });
    }
}

// Add this helper to clear the cache when you publish/unpublish
if (!function_exists('clearPublishedAnnouncementCache')) {
    function clearPublishedAnnouncementCache()
    {
        Cache::forget('published_announcement');
    }
}
