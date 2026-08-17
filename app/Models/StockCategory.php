<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockCategory extends Model
{
    protected $fillable = ['name', 'description'];

    public function stockItems()
    {
        return $this->hasMany(StockItem::class);
    }

    public function menuCategories()
    {
        return $this->hasMany(Category::class, 'stock_category_id');
    }
}
