<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenuePackageImage extends Model
{
    use HasFactory;

    protected $fillable = ['venue_package_id', 'image_path'];

    public function package()
    {
        return $this->belongsTo(VenuePackage::class, 'venue_package_id');
    }
}
