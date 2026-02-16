<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn(['discount_weekday', 'discount_weekend', 'primary_emoji', 'features', 'start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->text('discount_weekday')->nullable();
            $table->text('discount_weekend')->nullable();
            $table->text('primary_emoji')->nullable();
            $table->text('features')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
        });
    }
};
