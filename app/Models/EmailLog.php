<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    protected $fillable = [
        'booking_id',
        'recipient',
        'subject',
        'message',
        'type',
        'status',
        'sent_at',
        'sender_email'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    // Relationship to booking
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
