<?php

namespace App\Http\Controllers\Admin;

use App\Models\Menu;
use App\Models\Category;
use App\Http\Requests\MenuRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Laravel\Facades\Image;
use App\Http\Controllers\Traits\ImageHandlerTrait;
use App\Http\Controllers\Traits\AdminViewSharedDataTrait;

class MenuController extends Controller
{
    use AdminViewSharedDataTrait;
    use ImageHandlerTrait;


    public function __construct()
    {
        $this->shareAdminViewData();
        
    }
    
    public function index()
    {
        $categories = Category::with(['menus.recipes'])->get();  
        $stockItems = \App\Models\StockItem::all();
        return view('admin.menus', compact('categories', 'stockItems'));
    }

    public function store(MenuRequest $request)
    {
        $validated = $request->validated();
        
        if ($request->hasFile('image')) {
            $validated['image'] = $this->handleImageUpload($validated['image'], "menus");
        } else {
            $validated['image'] = '';
        }
    
        $menu = Menu::create($validated);
    
        if ($request->filled('stock_item_id')) {
            \App\Models\MenuRecipe::create([
                'menu_id' => $menu->id,
                'stock_item_id' => $request->stock_item_id,
                'quantity' => 1
            ]);
        }
    
        return back()->with('success', 'Menu created successfully!');
    }
    

    public function update(MenuRequest $request, $id)
    {
        $menu = Menu::findOrFail($id);
        $validated = $request->validated();
    
        if ($request->boolean('remove_image')) {
            if ($menu->image) {
                $imagePath = storage_path('app/public/' . ltrim($menu->image, '/'));
                if (file_exists($imagePath)) {
                    @unlink($imagePath);
                }
            }
            $validated['image'] = '';
        } elseif ($request->hasFile('image')) {
            // Delete old image
            if ($menu->image) {
                $imagePath = storage_path('app/public/' . ltrim($menu->image, '/'));
                if (file_exists($imagePath)) {
                    @unlink($imagePath);
                }
            }
    
            // Handle new image upload
            $validated['image'] = $this->handleImageUpload($validated['image'], "menus");
        }
    
        $menu->update($validated);
    
        $menu->recipes()->delete();
        if ($request->filled('stock_item_id')) {
            \App\Models\MenuRecipe::create([
                'menu_id' => $menu->id,
                'stock_item_id' => $request->stock_item_id,
                'quantity' => 1
            ]);
        }

        return back()->with('success', 'Menu updated successfully!');
    }
    

 

    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        $imagePath = storage_path('app/public/' . ltrim($menu->image, '/'));


            if (file_exists($imagePath)) {
                unlink($imagePath); // Delete the image file
            }
     

        $menu->delete();

        return redirect()->route('admin.menus.index')->with('success', 'Menu deleted successfully!');
    }


}
