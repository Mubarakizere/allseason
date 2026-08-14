<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuRecipe extends Model
{
    protected $fillable = [
        'menu_id', 'stock_item_id', 'quantity'
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function stockItem()
    {
        return $this->belongsTo(StockItem::class);
    }
}
