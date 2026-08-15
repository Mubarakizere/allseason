<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price', 'image', 'category_id'];

    protected $appends = ['image_url'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function recipes()
    {
        return $this->hasMany(MenuRecipe::class);
    }

    public function getImageUrlAttribute()
    {
        if (!empty($this->image)) {
            if (str_starts_with($this->image, '/') || str_starts_with($this->image, 'http')) {
                return $this->image;
            }
            if (file_exists(public_path('storage/' . $this->image))) {
                return asset('storage/' . $this->image);
            }
        }
        return asset('assets/images/placeholder.jpg');
    }

    public function getHasRealImageAttribute()
    {
        return !empty($this->image) && (
            str_starts_with($this->image, '/') ||
            str_starts_with($this->image, 'http') ||
            file_exists(public_path('storage/' . $this->image))
        );
    }
}
