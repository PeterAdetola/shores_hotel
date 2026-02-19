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

// or use Intervention\Image\Drivers\Imagick\Driver;

class RoomController extends Controller
{
    /**
     * Resize and store an uploaded image.
     */
    private function processImage($file, $folder = 'rooms', $width = 1500, $height = 844)
    {
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        $path = "{$folder}/{$filename}";

        // Create ImageManager instance with GD driver
        $manager = new ImageManager(new Driver());

        $image = $manager->read($file->getRealPath())
            ->cover($width, $height) // cover replaces fit in v3
            ->encode();

        Storage::disk('public_uploads')->put($path, (string)$image);

        return $path;
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
//        dd($rooms->toArray());
    }


//    public function toggleFeatured(Room $room, RoomImage $roomImage)
//    {
//        // Verify authorization
//        if ($roomImage->room_id != $room->id) {
//            return response()->json([
//                'success' => false,
//                'message' => 'Unauthorized'
//            ], 403);
//        }
//
//        try {
//            // If setting this as featured, unfeatured all others in this room first
//            if (!$roomImage->is_featured) {
//                RoomImage::where('room_id', $room->id)
//                    ->where('id', '!=', $roomImage->id)
//                    ->update(['is_featured' => false]);
//
//                $roomImage->update(['is_featured' => true]);
//                $featured = true;
//            } else {
//                $roomImage->update(['is_featured' => false]);
//                $featured = false;
//            }
//
//            return response()->json([
//                'success' => true,
//                'featured' => $featured,
//                'message' => $featured ? 'Image Featured!' : 'Image unfeatured'
//            ]);
//
//        } catch (\Exception $e) {
//            \Log::error('Toggle featured error: ' . $e->getMessage());
//
//            return response()->json([
//                'success' => false,
//                'message' => 'Error updating featured image'
//            ], 500);
//        }
//    }


    /**
     * Room reorder (AJAX).
     */
    public function reorder(Request $request)
    {
        try {
            // Validate the incoming request
            $request->validate([
                'order' => 'required|array',
                'order.*.id' => 'required|exists:rooms,id',
                'order.*.position' => 'required|integer|min:1'
            ]);

            // Start transaction for atomic updates
            \DB::beginTransaction();

            foreach ($request->order as $item) {
                \App\Models\Room::where('id', $item['id'])
                    ->update(['position' => $item['position']]);
            }

            // Commit the transaction
            \DB::commit();

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
            // Rollback transaction on error
            \DB::rollBack();

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
            'new_price' => $room->price_per_night // Optional: Return updated price
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
//    public function edit($id)
//    {
//        $room = Room::findOrFail($id);
//        return view('admin.room.edit_room', compact('room'));
//    }
    public function edit($id)
    {
        $room = Room::with(['galleryImages', 'featuredImage'])->findOrFail($id);
        return view('admin.room.edit_room', compact('room'));
    }


    /**
     * Gallery management - list images
     */
//    public function galleryIndex($roomId)
//    {
//        $room = Room::with('images')->findOrFail($roomId);
//        return view('admin.room.gallery_management', compact('room'));
//    }
    public function reorderImages(Request $request, Room $room)
    {
        // optional: $this->authorize('update', $room);

        $data = $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:room_images,id',
        ]);

        $ids = $data['order'];

        DB::transaction(function () use ($ids, $room) {
            foreach ($ids as $index => $id) {
                // ensure image belongs to this room
                DB::table('room_images')
                    ->where('id', $id)
                    ->where('room_id', $room->id)
                    ->update(['position' => $index + 1]);
            }
        });

        return response()->json(['status' => 'ok']);
//        return notification('Room Images Reordered', 'success');
    }

    /**
     * Add images to gallery
     */
//    public function galleryStore(Request $request, $roomId)
//    {
//        $request->validate([
//            'images.*' => 'required|image|mimes:jpg,jpeg,png|max:2048',
//        ]);
//
//        $room = Room::findOrFail($roomId);
//
//        foreach ($request->file('images') as $img) {
//            $path = $this->processImage($img);
//            $room->images()->create([
//                'image_path' => $path,
//                'is_featured' => false
//            ]);
//        }
//
//        return notification('Images added to gallery.', 'success');
//    }

    /**
     * Replace a single gallery image
     */
//    public function galleryUpdate(Request $request, $imageId)
//    {
//        $request->validate([
//            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
//        ]);
//
//        $image = RoomImage::findOrFail($imageId);
//        Storage::disk('public')->delete($image->image_path);
//
//        $newPath = $this->processImage($request->file('image'));
//        $image->update(['image_path' => $newPath]);
//
//        return notification('Gallery image updated.', 'success');
//    }

    /**
     * Delete a single gallery image
     */
//    public function galleryDestroy($imageId)
//    {
//        $image = RoomImage::findOrFail($imageId);
//        Storage::disk('public')->delete($image->image_path);
//        $image->delete();
//
//        return notification('Gallery image deleted.', 'success');
//    }

    /**
     * Clear all gallery images for a room
     */
//    public function galleryClear($roomId)
//    {
//        $room = Room::with('images')->findOrFail($roomId);
//
//        foreach ($room->images as $img) {
//            Storage::disk('public')->delete($img->image_path);
//        }
//        $room->images()->delete();
//
//        return notification('All gallery images cleared.', 'success');
//    }

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

//        dd($request->all());

        $room->update(array_merge($data, [
            'availability' => $request->boolean('availability'),
        ]));

//        return back()->with('success', 'Room information updated');
        return notification('Room information updated', 'success');
    }


    public function manageGallery(Room $room)
    {
        // Eager load gallery images with the room
        $room->load('galleryImages');

        return view('admin.room.manage_gallery', compact('room'));
    }

    public function updateFacilities(Request $request, Room $room)
    {
        $room->facilities()->sync($request->facilities ?? []);
        $room->update([
            'description' => $request->description,
        ]);

//        return back()->with('success', 'Amenities updated');
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
                if (Storage::disk('public_uploads')->exists($image->image_path)) {
                    Storage::disk('public_uploads')->delete($image->image_path);
                }
            }

            // Delete featured image if exists
            if ($room->featuredImage && Storage::disk('public_uploads')->exists($room->featuredImage->image_path)) {
                Storage::disk('public_uploads')->delete($room->featuredImage->image_path);
            }

            $room->delete();

            DB::commit();


            return notification(
                'Room deleted!',
                'success',
                false,
                [],
                'room_management' // or 'admin.rooms.index'
            );

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Room deletion failed', [
                'room_id' => $room->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Redirect back with error message
            return notification('Error deleting room', 'error', false, [
                'error_message' => $e->getMessage(),
            ]);
        }
    }

//    public function addImages(Request $request, Room $room)
//    {
//        $request->validate([
//            'images.*' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
//        ]);
//
//        foreach ($request->file('images') as $file) {
//            $path = $file->store('rooms', 'public');
//
//            $position = ($room->images()->max('position') ?? 0) + 1;
//
//            $room->images()->create([
//                'image_path' => $path,
//                'is_featured' => false,
//                'position' => $position,
//            ]);
//        }
//
////        return response()->json(['status' => 'ok', 'message' => 'Images added']);
//        return notification('Images Added', 'success');
//    }

    public function addImage(Request $request, Room $room)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $path = $request->file('image')->store('rooms', 'public_uploads');

        $maxPosition = $room->galleryImages()->max('position') ?? 0;

        $room->galleryImages()->create([
            'image_path' => $path,
            'is_featured' => false,
            'position' => $maxPosition + 1
        ]);

//        return redirect()
//            ->route('rooms.manage_gallery', $room->id)
//            ->with('success', 'Image added successfully');
        return notification('Image added', 'success');
    }


//    public function updateGalleryImage(Request $request, Room $room, RoomImage $image)
//    {
//        $request->validate([
//            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
//        ]);
//
//        if ($image->room_id !== $room->id) {
//            abort(403, 'Unauthorized');
//        }
//
//        // Delete old file
//        Storage::disk('public')->delete($image->image_path);
//
//        // Store new one
//        $path = $request->file('image')->store('rooms', 'public');
//
//        $image->update(['image_path' => $path]);
//
//        return notification('Image updated', 'success');
//    }

    public function updateGalleryImage(Request $request, Room $room, RoomImage $roomImage)
    {
        // Debug - remove after fixing
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
        if ($roomImage->room_id != $room->id) {  // Changed from !== to !=
            \Log::error('Authorization failed', [
                'expected_room' => $room->id,
                'actual_room' => $roomImage->room_id,
            ]);
            abort(403, 'This image does not belong to this room');
        }

        try {
            // Delete old file if exists
            if ($roomImage->image_path && Storage::disk('public_uploads')->exists($roomImage->image_path)) {
                Storage::disk('public_uploads')->delete($roomImage->image_path);
            }

            // USE processImage() like store() does - THIS IS THE FIX
            $path = $this->processImage($request->file('image'));

            // Log for debugging
            \Log::info('Image update', [
                'old_path' => $roomImage->image_path,
                'new_path' => $path,
                'full_path' => Storage::disk('public_uploads')->path($path),
                'exists' => Storage::disk('public_uploads')->exists($path),
            ]);

            $roomImage->update(['image_path' => $path]);

            return notification('Image updated', 'success');

        } catch (\Exception $e) {
            Log::error('Upload error: ' . $e->getMessage());
            return notification('Upload failed: ' . $e->getMessage(), 'error');
        }
    }

    public function deleteImage(Room $room, RoomImage $image)
    {
        // Delete the image file from storage if necessary
        if ($image->image_path && \Storage::disk('public_uploads')->exists($image->image_path)) {
            \Storage::disk('public_uploads')->delete($image->image_path);
        }

        $wasFeatured = $image->is_featured;

        // Delete the record
        $image->delete();

        // If it was featured, assign a new one
        if ($wasFeatured) {
            $nextImage = $room->galleryImages()->first(); // pick the first available one
            if ($nextImage) {
                $nextImage->is_featured = true;
                $nextImage->save();
            }
        }

        return redirect()->back()->with('success', 'Image deleted successfully.');
    }


}
