<?php

namespace App\Mail;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $room;
    public $senderName;
    public $nights;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Booking $booking, Room $room, $senderName, $nights)
    {
        $this->booking = $booking;
        $this->room = $room;
        $this->senderName = $senderName;
        $this->nights = $nights;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.booking-report')
            ->subject("New Booking Request - {$this->senderName} - {$this->booking->customer_name}")
            ->replyTo($this->booking->customer_email, $this->booking->customer_name)
            ->with([
                'booking' => $this->booking,
                'room' => $this->room,
                'senderName' => $this->senderName,
                'nights' => $this->nights,
            ]);
    }
}
