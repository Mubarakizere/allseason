<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenuePackageFeature extends Model
{
    use HasFactory;

    protected $fillable = ['venue_package_id', 'name'];

    public function package()
    {
        return $this->belongsTo(VenuePackage::class, 'venue_package_id');
    }
}
