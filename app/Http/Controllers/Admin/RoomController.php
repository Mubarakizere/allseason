<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Traits\AdminViewSharedDataTrait;

class RoomController extends Controller
{
    use AdminViewSharedDataTrait;

    public function __construct()
    {
        $this->shareAdminViewData();
    }

    public function index()
    {
        $rooms = Room::with('images', 'features')->get();
        return view('admin.rooms', compact('rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'capacity' => 'required|integer',
            'deposit_percentage' => 'required|numeric',
            'features' => 'nullable|string',
            'image' => 'nullable|image',
            'images.*' => 'nullable|image'
        ]);

        $room = Room::create($request->except(['image', 'images', 'features']));

        if (!empty($request->features)) {
            $features = array_map('trim', explode(',', $request->features));
            foreach ($features as $featureName) {
                if ($featureName) {
                    $room->features()->create(['name' => $featureName]);
                }
            }
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('rooms', 'public');
            $room->update(['image' => $path]);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('rooms', 'public');
                RoomImage::create([
                    'room_id' => $room->id,
                    'image' => $path
                ]);
            }
        }

        return back()->with('success', 'Room created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'capacity' => 'required|integer',
            'deposit_percentage' => 'required|numeric',
            'features' => 'nullable|string',
            'image' => 'nullable|image',
            'images.*' => 'nullable|image'
        ]);

        $room = Room::findOrFail($id);
        $room->update($request->except(['image', 'images', 'features']));

        $room->features()->delete();
        if (!empty($request->features)) {
            $features = array_map('trim', explode(',', $request->features));
            foreach ($features as $featureName) {
                if ($featureName) {
                    $room->features()->create(['name' => $featureName]);
                }
            }
        }

        if ($request->hasFile('image')) {
            if ($room->image && Storage::disk('public')->exists($room->image)) {
                Storage::disk('public')->delete($room->image);
            }
            $path = $request->file('image')->store('rooms', 'public');
            $room->update(['image' => $path]);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('rooms', 'public');
                RoomImage::create([
                    'room_id' => $room->id,
                    'image' => $path
                ]);
            }
        }

        return back()->with('success', 'Room updated successfully.');
    }

    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        
        if ($room->image && Storage::disk('public')->exists($room->image)) {
            Storage::disk('public')->delete($room->image);
        }

        foreach ($room->images as $image) {
            if (Storage::disk('public')->exists($image->image)) {
                Storage::disk('public')->delete($image->image);
            }
        }

        $room->delete();
        return back()->with('success', 'Room deleted successfully.');
    }

    public function deleteImage($id)
    {
        $image = RoomImage::findOrFail($id);
        if (Storage::disk('public')->exists($image->image)) {
            Storage::disk('public')->delete($image->image);
        }
        $image->delete();
        
        return back()->with('success', 'Image deleted successfully.');
    }
}
