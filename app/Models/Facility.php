<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $fillable = ['name', 'icon', 'position'];

    public function rooms() {
        return $this->belongsToMany(Room::class, 'facility_room', 'facility_id', 'room_id');
    }


}
