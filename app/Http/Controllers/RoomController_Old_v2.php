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

class RoomController extends Controller
{
    /**
     * Get the correct upload path based on environment
     *
     * Local:  project_root/public/uploads/
     * Online: server_root/public_html/uploads/ (sibling to shores_website)
     */
    private function getUploadPath($subfolder = '')
    {
        // base_path() returns:
        //   Local:  /path/to/project_root
        //   Online: /path/to/shores_website

        // dirname(base_path()) returns:
        //   Local:  /path/to (parent of project)
        //   Online: /path/to (parent of shores_website, contains public_html)

        $parentDir = dirname(base_path());
        $publicHtmlPath = $parentDir . '/public_html/uploads';

        // Check if public_html exists as a sibling
        if (File::exists($parentDir . '/public_html')) {
            // Production: use public_html/uploads (sibling to shores_website)
            $basePath = $publicHtmlPath;
            \Log::info('Using production path', ['base' => $basePath]);
        } else {
            // Local: use public/uploads (inside project)
            $basePath = public_path('uploads');
            \Log::info('Using local path', ['base' => $basePath]);
        }

        return $subfolder ? $basePath . '/' . $subfolder : $basePath;
    }

    /**
     * Resize and store an uploaded image
     * Works both offline (public/uploads) and online (public_html/uploads)
     */
    private function processImage($file, $folder = 'rooms', $width = 1500, $height = 844)
    {
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        $relativePath = "{$folder}/{$filename}";

        // Get correct upload path based on environment
        $uploadPath = $this->getUploadPath($folder);

        // Create directory if it doesn't exist
        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        // Full file path
        $fullPath = $uploadPath . '/' . $filename;

        // Create ImageManager instance with GD driver
        $manager = new ImageManager(new Driver());

        $image = $manager->read($file->getRealPath())
            ->cover($width, $height)
            ->encode();

        // Save image
        File::put($fullPath, (string)$image);

        // Log for debugging
        \Log::info('Image saved', [
            'relative_path' => $relativePath,
            'full_path' => $fullPath,
            'exists' => File::exists($fullPath),
            'environment' => File::exists(dirname(base_path()) . '/public_html') ? 'production' : 'local'
        ]);

        return $relativePath; // Returns "rooms/filename.jpg"
    }

    /**
     * Get full path to an image file (works for both environments)
     */
    private function getImageFullPath($relativePath)
    {
        return $this->getUploadPath() . '/' . $relativePath;
    }

    /**
     * Display all rooms.
     */
    public function index()
    {
        $rooms = Room::with(['category', 'galleryImages' => function ($query) {
            $query->where('is_featured', true)->limit(1);
        }, 'facilities'])->orderBy('position', 'asc')->get();

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
                Room::where('id', $item['id'])
                    ->update(['position' => $item['position']]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Rooms reordered',
                'type' => 'success',
                'status' => 'success',
                'reload' => false
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation error: ' . $e->getMessage(),
                'type' => 'error',
                'status' => 'error',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to reorder rooms: ' . $e->getMessage(),
                'type' => 'error',
                'status' => 'error'
            ], 500);
        }
    }

    /**
     * Update availability (AJAX).
     */
    public function updateAvailability(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:rooms,id',
            'availability' => 'required|boolean',
        ]);

        $room = Room::findOrFail($request->id);
        $room->availability = $request->availability;
        $room->save();

        return response()->json([
            'status' => 'success',
            'room_category' => $room->category->name ?? 'Room'
        ]);
    }

    /**
     * Update price per night (AJAX).
     */
    public function updatePrice(Request $request, Room $room)
    {
        $validated = $request->validate([
            'price_per_night' => 'required|numeric|min:0',
        ]);

        $room->update([
            'price_per_night' => $validated['price_per_night']
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Price updated.',
            'new_price' => $room->price_per_night
        ]);
    }

    /**
     * Store a newly created room in storage.
     */
    public function store(Request $request)
    {
        \Log::info('Room creation request data:', $request->all());

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
            'facilities.*' => 'exists:facilities,id',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        \Log::info('Validated data:', $validated);

        DB::beginTransaction();
        try {
            $room = Room::create($validated);

            if (!empty($validated['facilities'])) {
                $room->facilities()->sync($validated['facilities']);
            }

            if ($request->hasFile('image')) {
                $featuredPath = $this->processImage($request->file('image'));
                $max = $room->galleryImages()->max('position') ?? 0;
                $room->galleryImages()->create([
                    'image_path' => $featuredPath,
                    'is_featured' => true,
                    'position' => $max + 1,
                ]);
            }

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $img) {
                    $path = $this->processImage($img);
                    $room->galleryImages()->create([
                        'image_path' => $path,
                        'is_featured' => false
                    ]);
                }
            }

            DB::commit();
            return notification('Room created', 'success', false);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Room creation failed: ' . $e->getMessage());
            \Log::error('Error trace: ' . $e->getTraceAsString());

            return notification('Error creating room', 'error', false);
        }
    }

    /**
     * Edit a specified room in storage.
     */
    public function edit($id)
    {
        $room = Room::with(['galleryImages', 'featuredImage'])->findOrFail($id);
        return view('admin.room.edit_room', compact('room'));
    }

    /**
     * Reorder images
     */
    public function reorderImages(Request $request, Room $room)
    {
        $data = $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:room_images,id',
        ]);

        $ids = $data['order'];

        DB::transaction(function () use ($ids, $room) {
            foreach ($ids as $index => $id) {
                DB::table('room_images')
                    ->where('id', $id)
                    ->where('room_id', $room->id)
                    ->update(['position' => $index + 1]);
            }
        });

        return response()->json(['status' => 'ok']);
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

        $room->update(array_merge($data, [
            'availability' => $request->boolean('availability'),
        ]));

        return notification('Room information updated', 'success');
    }

    /**
     * Manage gallery
     */
    public function manageGallery(Room $room)
    {
        $room->load('galleryImages');
        return view('admin.room.manage_gallery', compact('room'));
    }

    /**
     * Update facilities
     */
    public function updateFacilities(Request $request, Room $room)
    {
        $room->facilities()->sync($request->facilities ?? []);
        $room->update([
            'description' => $request->description,
        ]);

        return notification('Amenities updated', 'success');
    }

    /**
     * Remove the specified room from storage.
     */
    public function destroy(Room $room)
    {
        DB::beginTransaction();

        try {
            // Delete all gallery images from storage
            foreach ($room->galleryImages as $image) {
                $fullPath = $this->getImageFullPath($image->image_path);
                if (File::exists($fullPath)) {
                    File::delete($fullPath);
                }
            }

            // Delete featured image if exists
            if ($room->featuredImage) {
                $fullPath = $this->getImageFullPath($room->featuredImage->image_path);
                if (File::exists($fullPath)) {
                    File::delete($fullPath);
                }
            }

            $room->delete();

            DB::commit();

            return notification(
                'Room deleted!',
                'success',
                false,
                [],
                'room_management'
            );

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Room deletion failed', [
                'room_id' => $room->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return notification('Error deleting room', 'error', false, [
                'error_message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Add image to gallery
     */
    public function addImage(Request $request, Room $room)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // Use processImage to resize and save
        $path = $this->processImage($request->file('image'));

        $maxPosition = $room->galleryImages()->max('position') ?? 0;

        $room->galleryImages()->create([
            'image_path' => $path,
            'is_featured' => false,
            'position' => $maxPosition + 1
        ]);

        return notification('Image added', 'success');
    }

    /**
     * Update gallery image
     */
    public function updateGalleryImage(Request $request, Room $room, RoomImage $roomImage)
    {
        \Log::info('Update attempt', [
            'room_id_from_url' => $room->id,
            'image_room_id' => $roomImage->room_id,
            'image_id' => $roomImage->id,
            'match' => $roomImage->room_id === $room->id,
        ]);

        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Verify authorization
        if ($roomImage->room_id != $room->id) {
            \Log::error('Authorization failed', [
                'expected_room' => $room->id,
                'actual_room' => $roomImage->room_id,
            ]);
            abort(403, 'This image does not belong to this room');
        }

        try {
            // Delete old file if exists
            $oldPath = $this->getImageFullPath($roomImage->image_path);
            if (File::exists($oldPath)) {
                File::delete($oldPath);
            }

            // Process and save new image
            $path = $this->processImage($request->file('image'));

            \Log::info('Image update', [
                'old_path' => $roomImage->image_path,
                'new_path' => $path,
                'full_path' => $this->getImageFullPath($path),
                'exists' => File::exists($this->getImageFullPath($path)),
            ]);

            $roomImage->update(['image_path' => $path]);

            return notification('Image updated', 'success');

        } catch (\Exception $e) {
            Log::error('Upload error: ' . $e->getMessage());
            return notification('Upload failed: ' . $e->getMessage(), 'error');
        }
    }

    /**
     * Delete gallery image
     */
    public function deleteImage(Room $room, $image)
    {
        // Find the image
        $roomImage = RoomImage::where('id', $image)
            ->where('room_id', $room->id)
            ->firstOrFail();

        try {
            // Delete the image file from storage
            $fullPath = $this->getImageFullPath($roomImage->image_path);
            if (File::exists($fullPath)) {
                File::delete($fullPath);
                \Log::info('Image file deleted', ['path' => $fullPath]);
            }

            $wasFeatured = $roomImage->is_featured;

            // Delete the record
            $roomImage->delete();

            // If it was featured, assign a new featured image
            if ($wasFeatured) {
                $nextImage = $room->galleryImages()->orderBy('position')->first();
                if ($nextImage) {
                    $nextImage->update(['is_featured' => true]);
                }
            }

            return notification('Image deleted successfully', 'success');

        } catch (\Exception $e) {
            Log::error('Image deletion failed: ' . $e->getMessage());
            return notification('Failed to delete image', 'error');
        }
    }
}
