<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // This is likely missing!
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
        return $this->hasMany(Room::class);
    }
    public function getAllImagesAttribute()
    {
        return RoomImage::whereIn('room_id', $this->rooms()->pluck('id'))->get();
    }
}
