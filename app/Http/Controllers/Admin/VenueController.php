<?php

namespace App\Http\Controllers\Admin;

use App\Models\Venue;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ImageHandlerTrait;
use App\Http\Controllers\Traits\AdminViewSharedDataTrait;

class VenueController extends Controller
{
    use AdminViewSharedDataTrait;
    use ImageHandlerTrait;

    public function __construct()
    {
        $this->shareAdminViewData();
    }

    public function index()
    {
        $venues = Venue::with('images')->get();
        return view('admin.venues', compact('venues'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'deposit_percentage' => 'required|numeric|min:0|max:100',
        ]);

        $venue = Venue::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'deposit_percentage' => $validated['deposit_percentage'],
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                $imagePath = $this->handleImageUpload($imageFile, "venues");
                $venue->images()->create(['image_path' => $imagePath]);
            }
        }

        return back()->with('success', 'Venue created successfully!');
    }

    public function update(Request $request, $id)
    {
        $venue = Venue::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'deposit_percentage' => 'required|numeric|min:0|max:100',
        ]);

        $venue->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'deposit_percentage' => $validated['deposit_percentage'],
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                $imagePath = $this->handleImageUpload($imageFile, "venues");
                $venue->images()->create(['image_path' => $imagePath]);
            }
        }

        return back()->with('success', 'Venue updated successfully!');
    }

    public function destroy($id)
    {
        $venue = Venue::findOrFail($id);
        
        foreach ($venue->images as $venueImage) {
            $imagePath = storage_path('app/public/' . ltrim($venueImage->image_path, '/'));
            if (file_exists($imagePath)) {
                @unlink($imagePath);
            }
        }
        
        $venue->delete();

        return redirect()->back()->with('success', 'Venue deleted successfully!');
    }

    public function deleteImage($id)
    {
        $venueImage = \App\Models\VenueImage::findOrFail($id);
        
        $imagePath = storage_path('app/public/' . ltrim($venueImage->image_path, '/'));
        if (file_exists($imagePath)) {
            @unlink($imagePath);
        }
        
        $venueImage->delete();
        
        return back()->with('success', 'Image deleted successfully!');
    }
}
