<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver; // or use Intervention\Image\Drivers\Imagick\Driver;

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

        Storage::disk('public')->put($path, (string) $image);

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


    /**
     * Update price per night (AJAX).
     */
    public function updateImage(Request $request, Room $room)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            if ($request->hasFile('image')) {
                // Delete old featured image if exists
                if ($room->featuredImage) {
                    Storage::delete('public/' . $room->featuredImage->image_path);
                    $room->featuredImage->delete();
                }

                // Store new image
                $path = $request->file('image')->store('rooms', 'public');

                // Save as new featured image
                $room->featuredImage()->create([
                    'image_path' => $path,
                    'is_featured' => 1,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Featured image updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating image: ' . $e->getMessage()
            ], 500);
        }
    }


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
        $validated = $request->validate([
            'room_category_id' => 'required|exists:room_categories,id',
            'price_per_night'  => 'required|numeric|min:0',
            'num_units'        => 'required|integer|min:1',
            'adult_max'        => 'required|integer|min:1',
            'children_max'     => 'nullable|integer|min:0',
            'availability'     => 'required|boolean',
            'description'      => 'nullable|string',
            'facilities'       => 'array|nullable',
            'facilities.*'     => 'exists:facilities,id',
            'image'            => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'images.*'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $room = Room::create($validated);

            if (!empty($validated['facilities'])) {
                $room->facilities()->sync($validated['facilities']);
            }

            if ($request->hasFile('image')) {
                $featuredPath = $this->processImage($request->file('image'));
                $room->images()->create([
                    'image_path' => $featuredPath,
                    'is_featured' => true
                ]);
            }

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $img) {
                    $path = $this->processImage($img);
                    $room->images()->create([
                        'image_path' => $path,
                        'is_featured' => false
                    ]);
                }
            }

            DB::commit();
            return notification('Room created.','success');
        } catch (\Exception $e) {
            DB::rollBack();
            return notification('Error creating room.','error',['error_message' => $e->getMessage()]);
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
     * Update the specified room in storage.
     */
    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'room_category_id' => 'required|exists:room_categories,id',
            'price_per_night'  => 'required|numeric|min:0',
            'num_units'        => 'required|integer|min:1',
            'adult_max'        => 'required|integer|min:1',
            'children_max'     => 'nullable|integer|min:0',
            'availability'     => 'required|boolean',
            'description'      => 'nullable|string',
            'facilities'       => 'array|nullable',
            'facilities.*'     => 'exists:facilities,id',
            'image'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $room->update($validated);
            $room->facilities()->sync($validated['facilities'] ?? []);

            if ($request->hasFile('image')) {
                $oldFeatured = $room->galleryImages()->where('is_featured', true)->first();
                if ($oldFeatured) {
                    Storage::disk('public')->delete($oldFeatured->image_path);
                    $oldFeatured->delete();
                }

                $featuredPath = $this->processImage($request->file('image'));
                $room->images()->create([
                    'image_path' => $featuredPath,
                    'is_featured' => true
                ]);
            }

            DB::commit();
            return notification('Room updated.', 'success');

        } catch (\Exception $e) {
            DB::rollBack();
            return notification('Error updating room','error',['error_message' => $e->getMessage()]);
        }
    }

    /**
     * Gallery management - list images
     */
    public function galleryIndex($roomId)
    {
        $room = Room::with('images')->findOrFail($roomId);
        return view('admin.room.gallery_management', compact('room'));
    }

    /**
     * Add images to gallery
     */
    public function galleryStore(Request $request, $roomId)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $room = Room::findOrFail($roomId);

        foreach ($request->file('images') as $img) {
            $path = $this->processImage($img);
            $room->images()->create([
                'image_path' => $path,
                'is_featured' => false
            ]);
        }

        return notification('Images added to gallery.', 'success');
    }

    /**
     * Replace a single gallery image
     */
    public function galleryUpdate(Request $request, $imageId)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $image = RoomImage::findOrFail($imageId);
        Storage::disk('public')->delete($image->image_path);

        $newPath = $this->processImage($request->file('image'));
        $image->update(['image_path' => $newPath]);

        return notification('Gallery image updated.', 'success');
    }

    /**
     * Delete a single gallery image
     */
    public function galleryDestroy($imageId)
    {
        $image = RoomImage::findOrFail($imageId);
        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return notification('Gallery image deleted.', 'success');
    }

    /**
     * Clear all gallery images for a room
     */
    public function galleryClear($roomId)
    {
        $room = Room::with('images')->findOrFail($roomId);

        foreach ($room->images as $img) {
            Storage::disk('public')->delete($img->image_path);
        }
        $room->images()->delete();

        return notification('All gallery images cleared.', 'success');
    }

    public function updateInfo(Request $request, Room $room)
    {
        $data = $request->validate([
            'room_category_id' => 'required|exists:room_categories,id',
            'price_per_night' => 'required|numeric|min:0',
            'num_units' => 'required|integer|min:1',
            'adult_max' => 'required|integer|min:1',
            'children_max' => 'required|integer|min:0',
        ]);

        $room->update($data);

        return back()->with('success', 'Room information updated');
    }

    public function updateFeaturedImage(Request $request, Room $room)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($room->featured_image) {
                Storage::delete($room->featured_image);
            }

            $path = $request->file('image')->store('rooms/featured');
            $room->update(['featured_image' => $path]);
        }

        return back()->with('success', 'Featured image updated');
    }

    public function updateFacilities(Request $request, Room $room)
    {
        $room->facilities()->sync($request->facilities ?? []);
        $room->update([
            'availability' => $request->has('availability'),
            'description' => $request->description,
        ]);

        return back()->with('success', 'Amenities updated');
    }


    /**
     * Remove the specified room from storage.
     */
    public function destroy(Room $room)
    {
        DB::beginTransaction();
        try {
            foreach ($room->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }
            $room->delete();
            DB::commit();
            return notification('Room deleted.', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            return notification('Error deleting room','error',['error_message' => $e->getMessage()]);
        }
    }
}
