<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Traits\AdminViewSharedDataTrait;

class BannerController extends Controller
{
    use AdminViewSharedDataTrait;

    public function __construct()
    {
        $this->shareAdminViewData();
    }

    public function index()
    {
        $banners = Banner::orderBy('sort_order', 'asc')->get();
        return view('admin.banners', compact('banners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,jpg,png,webp|max:5120',
            'btn_text_1' => 'nullable|string|max:100',
            'btn_link_1' => 'nullable|string|max:255',
            'btn_text_2' => 'nullable|string|max:100',
            'btn_link_2' => 'nullable|string|max:255',
            'overlay_class' => 'required|string|max:50',
            'align' => 'required|string|in:left,center,right',
            'sort_order' => 'nullable|integer',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('banners', 'public');
        }

        Banner::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'description' => $request->description,
            'image' => $imagePath,
            'btn_text_1' => $request->btn_text_1,
            'btn_link_1' => $request->btn_link_1,
            'btn_text_2' => $request->btn_text_2,
            'btn_link_2' => $request->btn_link_2,
            'overlay_class' => $request->overlay_class ?? 'overlay_bg_50',
            'align' => $request->align ?? 'center',
            'sort_order' => $request->sort_order ?? (Banner::max('sort_order') + 1),
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.banners.index')->with('success', 'Banner created successfully.');
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
            'btn_text_1' => 'nullable|string|max:100',
            'btn_link_1' => 'nullable|string|max:255',
            'btn_text_2' => 'nullable|string|max:100',
            'btn_link_2' => 'nullable|string|max:255',
            'overlay_class' => 'required|string|max:50',
            'align' => 'required|string|in:left,center,right',
            'sort_order' => 'nullable|integer',
        ]);

        $data = [
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'description' => $request->description,
            'btn_text_1' => $request->btn_text_1,
            'btn_link_1' => $request->btn_link_1,
            'btn_text_2' => $request->btn_text_2,
            'btn_link_2' => $request->btn_link_2,
            'overlay_class' => $request->overlay_class ?? 'overlay_bg_50',
            'align' => $request->align ?? 'center',
            'sort_order' => $request->sort_order ?? $banner->sort_order,
            'is_active' => $request->has('is_active') ? true : false,
        ];

        if ($request->hasFile('image')) {
            // Delete old file if stored in storage/banners
            if (!str_starts_with($banner->image, '/') && !str_starts_with($banner->image, 'http')) {
                Storage::disk('public')->delete($banner->image);
            }
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        $banner->update($data);

        return redirect()->route('admin.banners.index')->with('success', 'Banner updated successfully.');
    }

    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);

        if (!str_starts_with($banner->image, '/') && !str_starts_with($banner->image, 'http')) {
            Storage::disk('public')->delete($banner->image);
        }

        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Banner deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $banner = Banner::findOrFail($id);
        $banner->is_active = !$banner->is_active;
        $banner->save();

        return redirect()->route('admin.banners.index')->with('success', 'Banner status updated.');
    }
}
