<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'rooms';
    protected $fillable = [
        'room_category_id',
        'room_type',
        'price_per_night',
        'discount_percentage',
        'has_discount',
        'num_units',
        'adult_max',
        'children_max',
        'availability',
        'description'
    ];

    protected $casts = [
        'price_per_night' => 'decimal:2',
        'adult_max' => 'integer',
        'children_max' => 'integer',
        'discount_percentage' => 'decimal:2',
        'has_discount' => 'boolean',
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

    /**
     * Get the discounted price per night
     */
    public function getDiscountedPriceAttribute(): float
    {
        if (!$this->has_discount || $this->discount_percentage <= 0) {
            return $this->price_per_night;
        }

        $discount = ($this->price_per_night * $this->discount_percentage) / 100;
        return $this->price_per_night - $discount;
    }

    /**
     * Get the discount amount
     */
    public function getDiscountAmountAttribute(): float
    {
        if (!$this->has_discount || $this->discount_percentage <= 0) {
            return 0;
        }

        return ($this->price_per_night * $this->discount_percentage) / 100;
    }
    /**
     * Check if room has an active discount
     */
    public function hasActiveDiscount(): bool
    {
        return $this->has_discount && $this->discount_percentage > 0;
    }

    /**
     * Get room type name
     */
    public function getRoomTypeNameAttribute(): string
    {
        return $this->room_type ? 'Apartment' : 'Room';
    }

    /**
     * Scope: Get all rooms (room_type = 0)
     */
    public function scopeRooms($query)
    {
        return $query->where('room_type', 0);
    }

    /**
     * Scope: Get all apartments (room_type = 1)
     */
    public function scopeApartments($query)
    {
        return $query->where('room_type', 1);
    }

    /**
     * Check if this is a room
     */
    public function isRoom(): bool
    {
        return $this->room_type === 0 || $this->room_type === false;
    }

    /**
     * Check if this is an apartment
     */
    public function isApartment(): bool
    {
        return $this->room_type === 1 || $this->room_type === true;
    }

//    // Relationships
//    public function category()
//    {
//        return $this->belongsTo(RoomCategory::class, 'room_category_id');
//    }
//
//    public function galleryImages()
//    {
//        return $this->hasMany(RoomGalleryImage::class);
//    }

}

