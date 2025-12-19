<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->decimal('discount_percentage', 5, 2)->default(0)->after('price_per_night');
            $table->boolean('has_discount')->default(false)->after('discount_percentage');
        });
    }

    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn(['discount_percentage', 'has_discount']);
        });
    }
};
