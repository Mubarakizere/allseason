<?php

namespace App\Http\Controllers\Admin;

use App\Models\Menu;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CartTrait;
use App\Http\Controllers\Traits\AdminViewSharedDataTrait;

class CartController extends Controller
{
    use AdminViewSharedDataTrait;
    use CartTrait;


    public function __construct()
    {
        $this->shareAdminViewData();
        
    }
    
    public function index()
    {
        $menus = Menu::all();
        $categories = \App\Models\Category::all();
        $waiters = \App\Models\Waiter::where('is_active', true)->get();
        $tables = \App\Models\RestaurantTable::where('is_active', true)
            ->with(['orders' => function($q) {
                $q->where('status', 'pending')->where('order_type', 'instore');
            }])->get();
        return view('admin.pos-index', compact('menus', 'categories', 'waiters', 'tables'));
    } 

}
