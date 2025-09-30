<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Drop the existing FK before adding a new one
            $table->dropForeign(['room_id']);

            // Ensure room_id is nullable and add FK with onDelete('set null')
            $table->unsignedBigInteger('room_id')->nullable()->change();
            $table->foreign('room_id')
                ->references('id')->on('rooms')
                ->onDelete('set null');

            // New fields
            $table->string('customer_name')->after('children');
            $table->string('customer_email')->after('customer_name');
            $table->string('customer_phone')->after('customer_email');
            $table->string('booking_code')->unique()->nullable()->after('customer_phone');
            $table->enum('status', ['pending', 'confirmed', 'paid', 'cancelled', 'completed'])
                ->default('pending')
                ->after('booking_code');
            $table->decimal('total_amount', 10, 2)->after('status');
            $table->timestamp('confirmed_at')->nullable()->after('total_amount');
            $table->timestamp('paid_at')->nullable()->after('confirmed_at');
            $table->text('admin_notes')->nullable()->after('paid_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Drop FK
            $table->dropForeign(['room_id']);

            // Drop new columns
            $table->dropColumn([
                'customer_name',
                'customer_email',
                'customer_phone',
                'booking_code',
                'status',
                'total_amount',
                'confirmed_at',
                'paid_at',
                'admin_notes',
            ]);
        });
    }
};
