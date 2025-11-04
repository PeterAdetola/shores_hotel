<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Mail;

class BookingEmailHelper
{
    /**
     * Send email to booking guest
     *
     * @param object $booking - Booking model instance with guest_email, guest_name, etc.
     * @param string $subject
     * @param string $message
     * @param array $attachments - Optional array of file paths
     * @return bool
     */
    public static function sendToGuest($booking, $subject, $message, $attachments = [])
    {
        try {
            // Assuming your booking table has: guest_email, guest_name columns
            $guestEmail = $booking->guest_email ?? $booking->email;
            $guestName = $booking->guest_name ?? $booking->name;

            if (empty($guestEmail)) {
                throw new \Exception('Guest email not found');
            }

            Mail::send([], [], function ($mail) use ($guestEmail, $guestName, $subject, $message, $attachments) {
                $mail->to($guestEmail, $guestName)
                    ->subject($subject)
                    ->html($message);

                // Attach files if any
                foreach ($attachments as $file) {
                    if (file_exists($file)) {
                        $mail->attach($file);
                    }
                }
            });

            return true;

        } catch (\Exception $e) {
            \Log::error('Booking Email Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send booking confirmation email
     */
    public static function sendBookingConfirmation($booking)
    {
        $subject = 'Booking Confirmation - ' . config('app.name');

        $message = view('emails.booking-confirmation', compact('booking'))->render();

        return self::sendToGuest($booking, $subject, $message);
    }

    /**
     * Send booking reminder email
     */
    public static function sendBookingReminder($booking)
    {
        $subject = 'Booking Reminder - ' . config('app.name');

        $message = view('emails.booking-reminder', compact('booking'))->render();

        return self::sendToGuest($booking, $subject, $message);
    }

    /**
     * Send custom email to guest
     */
    public static function sendCustomEmail($booking, $subject, $messageContent)
    {
        return self::sendToGuest($booking, $subject, $messageContent);
    }

    /**
     * Send WhatsApp message (using API like Twilio, WhatsApp Business API)
     * Note: You'll need to integrate with a WhatsApp provider
     */
    public static function sendWhatsApp($booking, $message)
    {
        // Assuming your booking has whatsapp_number or phone column
        $phoneNumber = $booking->whatsapp_number ?? $booking->phone;

        if (empty($phoneNumber)) {
            return false;
        }

        // Example using Twilio (you'll need to install: composer require twilio/sdk)
        // Uncomment and configure when ready

        /*
        try {
            $twilio = new \Twilio\Rest\Client(
                config('services.twilio.sid'),
                config('services.twilio.token')
            );

            $twilio->messages->create(
                "whatsapp:{$phoneNumber}",
                [
                    'from' => 'whatsapp:' . config('services.twilio.whatsapp_number'),
                    'body' => $message
                ]
            );

            return true;
        } catch (\Exception $e) {
            \Log::error('WhatsApp Error: ' . $e->getMessage());
            return false;
        }
        */

        return false;
    }
}
