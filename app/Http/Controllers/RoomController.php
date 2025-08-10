<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomFacility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
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
            // Create room
            $room = Room::create($validated);

            // Sync facilities
            if (!empty($validated['facilities'])) {
                $room->facilities()->sync($validated['facilities']);
            }

            // Featured image
            if ($request->hasFile('image')) {
                $featuredPath = $request->file('image')->store('rooms', 'public');
                $room->images()->create([
                    'image_path' => $featuredPath,
                    'is_featured' => true
                ]);
            }

            // Gallery images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $img) {
                    $path = $img->store('rooms', 'public');
                    $room->images()->create([
                        'image_path' => $path,
                        'is_featured' => false
                    ]);
                }
            }

            DB::commit();

            return redirect_with_notification('Room created.','success');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect_with_notification('Error creating room.',
                'error',
                ['error_message' => $e->getMessage()]
            );
        }
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
            'facilities.*'     => 'exists:room_facilities,id',
            'image'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'images.*'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();
        try {
            // Update room
            $room->update($validated);

            // Sync facilities
            $room->facilities()->sync($validated['facilities'] ?? []);

            // Replace featured image if new one uploaded
            if ($request->hasFile('image')) {
                $oldFeatured = $room->images()->where('is_featured', true)->first();
                if ($oldFeatured) {
                    Storage::disk('public')->delete($oldFeatured->image_path);
                    $oldFeatured->delete();
                }

                $featuredImagePath = $request->file('image')->store('rooms', 'public');
                $room->images()->create([
                    'image_path' => $featuredImagePath,
                    'is_featured' => true,
                ]);
            }

            // Add new gallery images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $galleryImage) {
                    $galleryImagePath = $galleryImage->store('rooms', 'public');
                    $room->images()->create([
                        'image_path' => $galleryImagePath,
                        'is_featured' => false,
                    ]);
                }
            }

            DB::commit();

            return redirect_with_notification('Room updated.', 'success');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect_with_notification(
                'Error updating room',
                'error',
                ['error_message' => $e->getMessage()]
            );
        }
    }

    /**
     * Remove the specified room from storage.
     */
    public function destroy(Room $room)
    {
        DB::beginTransaction();
        try {
            // Delete images from storage
            foreach ($room->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }

            // Delete room
            $room->delete();

            DB::commit();

            return redirect_with_notification('Room deleted.', 'success');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect_with_notification(
                'Error deleting room',
                'error',
                ['error_message' => $e->getMessage()]
            );
        }
    }
}
