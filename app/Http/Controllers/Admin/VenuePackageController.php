<?php

namespace App\Http\Controllers\Admin;

use App\Models\Venue;
use App\Models\VenuePackage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ImageHandlerTrait;
use App\Http\Controllers\Traits\AdminViewSharedDataTrait;

class VenuePackageController extends Controller
{
    use AdminViewSharedDataTrait;
    use ImageHandlerTrait;

    public function __construct()
    {
        $this->shareAdminViewData();
    }

    public function index()
    {
        $venues = Venue::with('packages.features', 'packages.images')->get();
        return view('admin.venue-packages', compact('venues'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'venue_id' => 'required|exists:venues,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'features' => 'nullable|string', // comma separated or newline separated
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:30720',
        ]);

        $package = VenuePackage::create([
            'venue_id' => $validated['venue_id'],
            'name' => $validated['name'],
            'price' => $validated['price'],
        ]);

        if (!empty($validated['features'])) {
            $features = array_map('trim', explode(',', $validated['features']));
            foreach ($features as $featureName) {
                if ($featureName) {
                    $package->features()->create(['name' => $featureName]);
                }
            }
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                $imagePath = $this->handleImageUpload($imageFile, "venue_packages");
                $package->images()->create(['image_path' => $imagePath]);
            }
        }

        return back()->with('success', 'Package created successfully!');
    }

    public function update(Request $request, $id)
    {
        $package = VenuePackage::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'features' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:30720',
        ]);

        $package->update([
            'name' => $validated['name'],
            'price' => $validated['price'],
        ]);

        // Re-sync features
        $package->features()->delete();
        if (!empty($validated['features'])) {
            $features = array_map('trim', explode(',', $validated['features']));
            foreach ($features as $featureName) {
                if ($featureName) {
                    $package->features()->create(['name' => $featureName]);
                }
            }
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                $imagePath = $this->handleImageUpload($imageFile, "venue_packages");
                $package->images()->create(['image_path' => $imagePath]);
            }
        }

        return back()->with('success', 'Package updated successfully!');
    }

    public function destroy($id)
    {
        $package = VenuePackage::findOrFail($id);
        
        foreach ($package->images as $packageImage) {
            $imagePath = storage_path('app/public/' . ltrim($packageImage->image_path, '/'));
            if (file_exists($imagePath)) {
                @unlink($imagePath);
            }
        }
        
        $package->delete();

        return redirect()->back()->with('success', 'Package deleted successfully!');
    }

    public function deleteImage($id)
    {
        $packageImage = \App\Models\VenuePackageImage::findOrFail($id);
        
        $imagePath = storage_path('app/public/' . ltrim($packageImage->image_path, '/'));
        if (file_exists($imagePath)) {
            @unlink($imagePath);
        }
        
        $packageImage->delete();
        
        return back()->with('success', 'Image deleted successfully!');
    }
}
