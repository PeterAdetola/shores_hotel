<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('room_images', function (Blueprint $table) {
            $table->unsignedInteger('position')->default(0)->after('room_id');
        });
        // initialize positions per room (1..n), using current id order
        $roomIds = DB::table('room_images')->select('room_id')->distinct()->pluck('room_id');
        foreach ($roomIds as $roomId) {
            $images = DB::table('room_images')
                ->where('room_id', $roomId)
                ->orderBy('id')
                ->pluck('id');
            $p = 1;
            foreach ($images as $imageId) {
                DB::table('room_images')->where('id', $imageId)->update(['position' => $p++]);
            }
        }
        Schema::table('room_images', function (Blueprint $table) {
            $table->index(['room_id', 'position']);
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_images', function (Blueprint $table) {
            // drop index - Laravel accepts dropIndex with columns array
            $table->dropIndex(['room_id', 'position']);
        });

        Schema::table('room_images', function (Blueprint $table) {
            $table->dropColumn('position');
        });
    }
};
