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
        Schema::rename('room_facilities', 'facilities');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('facilities', 'room_facilities');
    }
};
