<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'rooms';
    protected $fillable = [
        'room_type',
        'room_category_id',
        'position',
        'price_per_night',
        'num_units',
        'adult_max',
        'children_max',
        'availability',
        'description',
    ];

    protected $casts = [
        'price_per_night' => 'decimal:2',
        'adult_max' => 'integer',
        'children_max' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(RoomCategory::class, 'room_category_id');
    }

    public function facilities()
    {
        return $this->belongsToMany(Facility::class);
    }

    public function galleryImages()
    {
        return $this->hasMany(RoomImage::class)->orderBy('position')->orderBy('id');
    }

    public function featuredImage()
    {
        return $this->hasOne(RoomImage::class, 'room_id')->where('is_featured', true);
    }

    public function getFeaturedImageUrlAttribute()
    {
        try {
            if ($this->featuredImage && $this->featuredImage->image_path) {
                return asset('storage/' . $this->featuredImage->image_path);
            }

            // Try to get first gallery image
            $firstImage = $this->galleryImages()->first();
            if ($firstImage) {
                return asset('storage/' . $firstImage->image_path);
            }

            // Fallback to default placeholder
            return asset('images/default.jpg');
        } catch (\Exception $e) {
            \Log::error('Error getting featured image: ' . $e->getMessage());
            return asset('images/default.jpg');
        }
    }
}

