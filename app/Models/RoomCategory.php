<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Room;

class RoomCategory extends Model
{
    protected $fillable = ['name', 'type'];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
