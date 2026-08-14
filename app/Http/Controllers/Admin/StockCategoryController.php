<?php

namespace App\Http\Controllers\Admin;

use App\Models\StockCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\AdminViewSharedDataTrait;

class StockCategoryController extends Controller
{
    use AdminViewSharedDataTrait;

    public function __construct()
    {
        $this->shareAdminViewData();
    }

    public function index()
    {
        $categories = StockCategory::all();
        return view('admin.stock.categories', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        StockCategory::create($request->all());
        return redirect()->back()->with('success', 'Category created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $category = StockCategory::findOrFail($id);
        $category->update($request->all());
        return redirect()->back()->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        $category = StockCategory::findOrFail($id);
        $category->delete();
        return redirect()->back()->with('success', 'Category deleted successfully.');
    }
}
