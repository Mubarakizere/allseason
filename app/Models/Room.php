<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'price',
        'capacity',
        'deposit_percentage'
    ];

    public function images()
    {
        return $this->hasMany(RoomImage::class);
    }

    public function bookings()
    {
        return $this->hasMany(RoomBooking::class);
    }

    public function features()
    {
        return $this->hasMany(RoomFeature::class);
    }
}
