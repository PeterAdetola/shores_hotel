<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Room;
use App\Models\RoomImage;

class RoomCategory extends Model
{
    protected $fillable = ['name', 'type', 'slug'];

    protected static function booted()
    {
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name')) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function rooms()
    {
        return $this->hasMany(Room::class, 'room_category_id');
    }

    public function getAllImagesAttribute()
    {
        try {
            $roomIds = $this->rooms()->pluck('id');
            if ($roomIds->isEmpty()) {
                return collect(); // Return empty collection if no rooms
            }
            return RoomImage::whereIn('room_id', $roomIds)->get();
        } catch (\Exception $e) {
            \Log::error('Error getting category images: ' . $e->getMessage());
            return collect();
        }
    }

    // Add this method for better slug handling
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
