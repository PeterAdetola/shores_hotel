<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Booking;

class BookingRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $senderEmail;
    public $senderName;

    public function __construct(Booking $booking, $senderEmail, $senderName)
    {
        $this->booking = $booking;
        $this->senderEmail = $senderEmail;
        $this->senderName = $senderName;
    }

    public function build()
    {
        \Log::info("Building BookingRequestMail for: " . $this->booking->customer_email);

        return $this->from($this->senderEmail, $this->senderName)
            ->replyTo($this->senderEmail, $this->senderName)
            ->subject("Booking Application Received - {$this->senderName}")
            ->view('emails.booking-request') // Make sure this line is correct
            ->with([
                'booking' => $this->booking,
                'senderName' => $this->senderName,
            ]);
    }
}
