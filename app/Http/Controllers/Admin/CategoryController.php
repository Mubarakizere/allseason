<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Controllers\Traits\AdminViewSharedDataTrait;

class CategoryController extends Controller
{
    use AdminViewSharedDataTrait;

    public function __construct()
    {
        $this->shareAdminViewData();
        
    }
    
   
    public function index()
    {
        $categories = Category::with(['menus', 'stockCategory'])->get();
        $stockCategories = \App\Models\StockCategory::orderBy('name', 'asc')->get();
        return view('admin.categories', compact('categories', 'stockCategories'));
    }

    public function store(CategoryRequest $request)
    {
        $data = $request->validated();
        if (empty($data['type'])) {
            $catName = strtolower($data['name']);
            $barKeywords = ['drink', 'beverage', 'bar', 'wine', 'beer', 'cocktail', 'juice', 'alcohol', 'soda', 'liquor', 'whiskey', 'rum', 'vodka', 'gin', 'champagne', 'cider', 'spirit', 'water'];
            $isBar = false;
            foreach ($barKeywords as $kw) {
                if (str_contains($catName, $kw)) { $isBar = true; break; }
            }
            $data['type'] = $isBar ? 'bar' : 'kitchen';
        }

        Category::create($data);
        return redirect()->back()->with('success', 'Category created successfully.');
    }
    

    public function update(CategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id);
        $data = $request->validated();
        if (empty($data['type'])) {
            $catName = strtolower($data['name']);
            $barKeywords = ['drink', 'beverage', 'bar', 'wine', 'beer', 'cocktail', 'juice', 'alcohol', 'soda', 'liquor', 'whiskey', 'rum', 'vodka', 'gin', 'champagne', 'cider', 'spirit', 'water'];
            $isBar = false;
            foreach ($barKeywords as $kw) {
                if (str_contains($catName, $kw)) { $isBar = true; break; }
            }
            $data['type'] = $isBar ? 'bar' : 'kitchen';
        }

        $category->update($data);
        return redirect()->back()->with('success', 'Category updated successfully.');
    }
    

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->back()->with('success', 'Category deleted successfully.');
    }


}
