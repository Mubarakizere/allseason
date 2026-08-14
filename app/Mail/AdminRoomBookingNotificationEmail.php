<?php

namespace App\Mail;

use App\Models\RoomBooking;
use App\Models\SiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminRoomBookingNotificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $site_settings;

    public function __construct(RoomBooking $booking)
    {
        $this->booking = $booking;
        $this->site_settings = SiteSetting::latest()->first();
    }

    public function build()
    {
        return $this->view('emails.admin_room_booking_notification')
                    ->subject('New Room Booking Received')
                    ->with([
                        'booking' => $this->booking,
                        'site_settings' => $this->site_settings,
                    ]);
    }
}
