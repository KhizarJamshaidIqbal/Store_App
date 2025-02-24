<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('order')->paginate(10);
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'image_paths' => 'required|json',
            'url' => 'nullable|url',
            'status' => 'boolean',
            'order' => 'integer',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at'
        ]);

        $imagePaths = json_decode($validated['image_paths'], true);

        // Create banner with first image as primary
        Banner::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'image_path' => $imagePaths[0] ?? null, // Primary image
            'url' => $validated['url'],
            'status' => $validated['status'] ?? true,
            'order' => $validated['order'] ?? 0,
            'starts_at' => $validated['starts_at'],
            'ends_at' => $validated['ends_at']
        ]);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner created successfully');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'image_paths' => 'required|json',
            'url' => 'nullable|url',
            'status' => 'boolean',
            'order' => 'integer',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at'
        ]);

        $imagePaths = json_decode($validated['image_paths'], true);

        $banner->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'image_path' => $imagePaths[0] ?? $banner->image_path,
            'url' => $validated['url'],
            'status' => $validated['status'],
            'order' => $validated['order'],
            'starts_at' => $validated['starts_at'],
            'ends_at' => $validated['ends_at']
        ]);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner updated successfully');
    }

    public function destroy(Banner $banner)
    {
        Storage::disk('public')->delete($banner->image_path);
        $banner->delete();

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner deleted successfully');
    }
}
