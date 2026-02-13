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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('subtitle')->nullable();
            $table->text('content');
            $table->text('discount_weekday')->nullable();
            $table->text('discount_weekend')->nullable();
            $table->text('features')->nullable(); // JSON field for amenities/features
            $table->string('cta_text')->default('Book Now');
            $table->string('cta_link')->default('#');
            $table->string('border_color')->default('#ff1493');
            $table->string('primary_emoji')->default('💖');
            $table->boolean('is_published')->default(false);
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
