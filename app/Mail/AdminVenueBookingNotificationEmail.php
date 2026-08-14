<?php

namespace App\Mail;

use App\Models\VenueBooking;
use App\Models\SiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminVenueBookingNotificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $site_settings;

    public function __construct(VenueBooking $booking)
    {
        $this->booking = $booking;
        $this->site_settings = SiteSetting::latest()->first();
    }

    public function build()
    {
        return $this->view('emails.admin_venue_booking_notification')
                    ->subject('New Venue Booking Received')
                    ->with([
                        'booking' => $this->booking,
                        'site_settings' => $this->site_settings,
                    ]);
    }
}
