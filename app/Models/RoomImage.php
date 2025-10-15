<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomImage extends Model
{
    protected $fillable = ['room_id', 'image_path', 'is_featured', 'position'];

    protected $casts = [
        'is_featured' => 'boolean',
        'position' => 'integer',
    ];

    public function scopeOrdered($query)
    {
        return $query->orderBy('position')->orderBy('id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    // Add image URL accessor
    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }
        return asset('images/default.jpg');
    }
}

