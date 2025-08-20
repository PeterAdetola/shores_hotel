<?php
namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomCategory;
use Illuminate\Http\Request;

class GetLodgedController extends Controller
{
    public function index()
    {
        $categories = RoomCategory::all();
        $rooms = Room::with(['category', 'galleryImages' => function ($query) {
            $query->where('is_featured', true)->limit(1);
        }, 'facilities'])
            ->orderBy('position', 'asc')
            ->get();

        return view('getlodged', compact('categories', 'rooms'));
    }

    public function filter(Request $request)
    {
        $query = Room::with(['category', 'galleryImages']);

        if ($request->type) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('type', $request->type);
            });
        }

        $rooms = $query->get();

        // Transform the data to match JavaScript expectations
        $rooms = $rooms->map(function ($room) {
            return [
                'id' => $room->id,
                'adult_max' => $room->adult_max,
                'children_max' => $room->children_max,
                'price_per_night' => $room->price_per_night,
                'category' => $room->category,
                'gallery_images' => $room->galleryImages->map(function ($img) {
                    return [
                        'image_path' => $img->image_path
                    ];
                })
            ];
        });

        return response()->json($rooms);
    }
}
