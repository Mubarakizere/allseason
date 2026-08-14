<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenuePackage extends Model
{
    use HasFactory;

    protected $fillable = ['venue_id', 'name', 'price'];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function features()
    {
        return $this->hasMany(VenuePackageFeature::class);
    }

    public function images()
    {
        return $this->hasMany(VenuePackageImage::class);
    }

    public function bookings()
    {
        return $this->hasMany(VenueBooking::class);
    }
}
