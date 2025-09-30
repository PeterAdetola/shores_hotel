<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    protected $fillable = [
        'room_id',
        'lodging_type',
        'check_in',
        'check_out',
        'adults',
        'children',
        'customer_name',
        'customer_email',
        'customer_phone',
        'booking_code',
        'status',
        'total_amount',
        'confirmed_at',
        'paid_at',
        'admin_notes',
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'confirmed_at' => 'datetime',
        'paid_at' => 'datetime',
        'total_amount' => 'decimal:2',
    ];

    // Relationships
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Helper methods
    public function generateBookingCode()
    {
        $year = date('Y');
        $lastBooking = self::whereYear('created_at', $year)
            ->whereNotNull('booking_code')
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = $lastBooking ?
            (int)substr($lastBooking->booking_code, -6) + 1 : 1;

        return 'SHA-' . $year . '-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }

    public function getTotalNightsAttribute()
    {
        return $this->check_in->diffInDays($this->check_out);
    }

    public function getStatusLabelAttribute()
    {
        return ucfirst($this->status);
    }
}
