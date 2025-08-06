<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RoomFacility;

class RoomFacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facilities = [
            ['name' => 'Free Wi-Fi', 'icon' => 'wifi'],
            ['name' => 'Air Conditioning', 'icon' => 'ac_unit'],
            ['name' => 'Swimming Pool', 'icon' => 'pool'],
            ['name' => 'Smart TV', 'icon' => 'tv'],
        ];

        foreach ($facilities as $facility) {
            RoomFacility::create($facility);
        }
    }
}
