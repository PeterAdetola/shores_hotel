<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'rooms';
    protected $fillable = [
        'room_category_id',
        'price_per_night',
        'num_units',
        'adult_max',
        'children_max',
        'availability',
        'description'
    ];

    public function facilities()
    {
        return $this->belongsToMany(Facility::class);
    }

    public function images()
    {
        return $this->hasMany(RoomImage::class);
    }
}

