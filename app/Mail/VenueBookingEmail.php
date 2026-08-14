<?php

namespace App\Mail;

use App\Models\VenueBooking;
use App\Models\SiteSetting;
use App\Models\RestaurantPhoneNumber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VenueBookingEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $site_settings;
    public $companyPhone;

    public function __construct(VenueBooking $booking)
    {
        $this->booking = $booking;
        $this->site_settings = SiteSetting::latest()->first();
        $this->companyPhone = RestaurantPhoneNumber::first()?->phone_number;
    }

    public function build()
    {
        return $this->view('emails.venue_booking')
                    ->subject('Your Venue Booking Confirmation')
                    ->with([
                        'booking' => $this->booking,
                        'site_settings' => $this->site_settings,
                        'companyPhone' => $this->companyPhone,
                    ]);
    }
}
