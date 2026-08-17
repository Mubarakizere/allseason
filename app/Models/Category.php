<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'stock_category_id'];

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    public function stockCategory()
    {
        return $this->belongsTo(StockCategory::class, 'stock_category_id');
    }
}
