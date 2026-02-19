<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache; // Import Cache

class RoomController extends Controller
{
    /**
     * Helper to clear all accommodation-related cache
     */
    private function clearRoomCache()
    {
        Cache::forget('accommodation_all');
        Cache::forget('accommodation_available');
        Cache::forget('rooms_list_basic');
        Cache::forget('rooms_type_0');
        Cache::forget('rooms_type_1');
        Log::info('Room cache cleared by ' . auth()->user()->name);
    }

    /**
     * Get the correct upload path based on environment
     */
    private function getUploadPath($subfolder = '')
    {
        $parentDir = dirname(base_path());
        $publicHtmlPath = $parentDir . '/public_html/uploads';

        if (File::exists($parentDir . '/public_html')) {
            $basePath = $publicHtmlPath;
        } else {
            $basePath = public_path('uploads');
        }

        return $subfolder ? $basePath . '/' . $subfolder : $basePath;
    }

    /**
     * Resize and store an uploaded image
     */
    private function processImage($file, $folder = 'rooms', $width = 1500, $height = 844)
    {
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        $relativePath = "{$folder}/{$filename}";
        $uploadPath = $this->getUploadPath($folder);

        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        $fullPath = $uploadPath . '/' . $filename;
        $manager = new ImageManager(new Driver());
        $image = $manager->read($file->getRealPath())->cover($width, $height)->encode();
        File::put($fullPath, (string)$image);

        return $relativePath;
    }

    private function getImageFullPath($relativePath)
    {
        return $this->getUploadPath() . '/' . $relativePath;
    }

    /**
     * Display all rooms (Cached for Admin too)
     */
    public function index()
    {
        // Cache the admin view list for 10 minutes to make dashboard snappy
        $rooms = Cache::remember('admin_rooms_index', 600, function() {
            return Room::with(['category', 'galleryImages' => function ($query) {
                $query->where('is_featured', true)->limit(1);
            }, 'facilities'])->orderBy('position', 'asc')->get();
        });

        return view('admin.room.room_management', compact('rooms'));
    }

    /**
     * Room reorder (AJAX).
     */
    public function reorder(Request $request)
    {
        try {
            $request->validate([
                'order' => 'required|array',
                'order.*.id' => 'required|exists:rooms,id',
                'order.*.position' => 'required|integer|min:1'
            ]);

            DB::beginTransaction();
            foreach ($request->order as $item) {
                Room::where('id', $item['id'])->update(['position' => $item['position']]);
            }
            DB::commit();

            $this->clearRoomCache(); // Clear Cache

            return response()->json(['message' => 'Rooms reordered', 'type' => 'success', 'status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to reorder: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update availability (AJAX).
     */
    public function updateAvailability(Request $request)
    {
        $request->validate(['id' => 'required|exists:rooms,id', 'availability' => 'required|boolean']);

        $room = Room::findOrFail($request->id);
        $room->availability = $request->availability;
        $room->save();

        $this->clearRoomCache(); // Clear Cache

        return response()->json(['status' => 'success', 'room_category' => $room->category->name ?? 'Room']);
    }

    /**
     * Update price per night (AJAX).
     */
    public function updatePrice(Request $request, Room $room)
    {
        $validated = $request->validate(['price_per_night' => 'required|numeric|min:0']);
        $room->update(['price_per_night' => $validated['price_per_night']]);

        $this->clearRoomCache(); // Clear Cache

        return response()->json(['success' => true, 'message' => 'Price updated.', 'new_price' => $room->price_per_night]);
    }

    /**
     * Store a newly created room.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_type' => 'required|in:0,1',
            'room_category_id' => 'required|exists:room_categories,id',
            'price_per_night' => 'required|numeric|min:0',
            'num_units' => 'required|integer|min:1',
            'adult_max' => 'required|integer|min:1',
            'children_max' => 'nullable|integer|min:0',
            'availability' => 'required|boolean',
            'description' => 'nullable|string',
            'facilities' => 'array|nullable',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $room = Room::create($validated);
            if (!empty($validated['facilities'])) { $room->facilities()->sync($validated['facilities']); }

            if ($request->hasFile('image')) {
                $featuredPath = $this->processImage($request->file('image'));
                $room->galleryImages()->create(['image_path' => $featuredPath, 'is_featured' => true, 'position' => 1]);
            }

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $img) {
                    $path = $this->processImage($img);
                    $room->galleryImages()->create(['image_path' => $path, 'is_featured' => false]);
                }
            }

            DB::commit();
            $this->clearRoomCache(); // Clear Cache
            return notification('Room created', 'success', false);
        } catch (\Exception $e) {
            DB::rollBack();
            return notification('Error creating room', 'error', false);
        }
    }

    /**
     * Update room info
     */
    public function updateInfo(Request $request, Room $room)
    {
        $data = $request->validate([
            'room_type' => 'required|in:0,1',
            'room_category_id' => 'required|exists:room_categories,id',
            'price_per_night' => 'required|numeric|min:0',
            'num_units' => 'required|integer|min:1',
            'adult_max' => 'required|integer|min:1',
            'children_max' => 'required|integer|min:0',
            'availability' => 'sometimes|boolean',
        ]);

        $room->update(array_merge($data, ['availability' => $request->boolean('availability')]));

        $this->clearRoomCache(); // Clear Cache
        return notification('Room information updated', 'success');
    }

    /**
     * Update facilities
     */
    public function updateFacilities(Request $request, Room $room)
    {
        $room->facilities()->sync($request->facilities ?? []);
        $room->update(['description' => $request->description]);

        $this->clearRoomCache(); // Clear Cache
        return notification('Amenities updated', 'success');
    }

    /**
     * Remove the specified room.
     */
    public function destroy(Room $room)
    {
        DB::beginTransaction();
        try {
            foreach ($room->galleryImages as $image) {
                $fullPath = $this->getImageFullPath($image->image_path);
                if (File::exists($fullPath)) { File::delete($fullPath); }
            }
            $room->delete();
            DB::commit();

            $this->clearRoomCache(); // Clear Cache
            return notification('Room deleted!', 'success', false, [], 'room_management');
        } catch (\Exception $e) {
            DB::rollBack();
            return notification('Error deleting room', 'error', false);
        }
    }

    /**
     * Image Management Actions
     */
    public function addImage(Request $request, Room $room)
    {
        $request->validate(['image' => 'required|image|mimes:jpg,jpeg,png|max:2048']);
        $path = $this->processImage($request->file('image'));
        $room->galleryImages()->create(['image_path' => $path, 'is_featured' => false, 'position' => ($room->galleryImages()->max('position') ?? 0) + 1]);

        $this->clearRoomCache(); // Clear Cache
        return notification('Image added', 'success');
    }

    public function updateGalleryImage(Request $request, Room $room, RoomImage $roomImage)
    {
        $request->validate(['image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048']);
        if ($roomImage->room_id != $room->id) abort(403);

        $oldPath = $this->getImageFullPath($roomImage->image_path);
        if (File::exists($oldPath)) { File::delete($oldPath); }

        $path = $this->processImage($request->file('image'));
        $roomImage->update(['image_path' => $path]);

        $this->clearRoomCache(); // Clear Cache
        return notification('Image updated', 'success');
    }

    public function deleteImage(Room $room, $image)
    {
        $roomImage = RoomImage::where('id', $image)->where('room_id', $room->id)->firstOrFail();
        $fullPath = $this->getImageFullPath($roomImage->image_path);
        if (File::exists($fullPath)) { File::delete($fullPath); }

        $roomImage->delete();

        $this->clearRoomCache(); // Clear Cache
        return notification('Image deleted successfully', 'success');
    }

    public function edit($id)
    {
        $room = Room::with(['galleryImages', 'featuredImage'])->findOrFail($id);
        return view('admin.room.edit_room', compact('room'));
    }

    public function manageGallery(Room $room)
    {
        $room->load('galleryImages');
        return view('admin.room.manage_gallery', compact('room'));
    }

    public function reorderImages(Request $request, Room $room)
    {
        $data = $request->validate(['order' => 'required|array', 'order.*' => 'integer|exists:room_images,id']);
        DB::transaction(function () use ($data, $room) {
            foreach ($data['order'] as $index => $id) {
                DB::table('room_images')->where('id', $id)->where('room_id', $room->id)->update(['position' => $index + 1]);
            }
        });
        $this->clearRoomCache();
        return response()->json(['status' => 'ok']);
    }
}
