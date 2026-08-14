<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'image', 'deposit_percentage'];

    public function packages()
    {
        return $this->hasMany(VenuePackage::class);
    }

    public function bookings()
    {
        return $this->hasMany(VenueBooking::class);
    }

    public function images()
    {
        return $this->hasMany(VenueImage::class);
    }
}
