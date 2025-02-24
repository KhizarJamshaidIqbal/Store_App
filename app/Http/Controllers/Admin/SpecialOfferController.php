<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SpecialOffer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SpecialOfferController extends Controller
{
    public function index()
    {
        $offers = SpecialOffer::latest()->paginate(10);
        return view('admin.special-offers.index', compact('offers'));
    }

    public function create()
    {
        $products = Product::all();
        return view('admin.special-offers.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'discount_amount' => 'nullable|numeric|min:0',
            'discount_percentage' => 'nullable|integer|min:0|max:100',
            'image' => 'nullable|image|max:2048',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:active,inactive,scheduled',
            'is_featured' => 'boolean',
            'applicable_products' => 'nullable|array',
            'terms_conditions' => 'nullable|array'
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('special-offers', 'public');
            $validated['image_path'] = $path;
        }

        $validated['slug'] = Str::slug($validated['title']);

        SpecialOffer::create($validated);

        return redirect()
            ->route('admin.special-offers.index')
            ->with('success', 'Special offer created successfully.');
    }

    public function edit(SpecialOffer $specialOffer)
    {
        return view('admin.special-offers.edit', compact('specialOffer'));
    }

    public function update(Request $request, SpecialOffer $specialOffer)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'discount_amount' => 'nullable|numeric|min:0',
            'discount_percentage' => 'nullable|integer|min:0|max:100',
            'image' => 'nullable|image|max:2048',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:active,inactive,scheduled',
            'is_featured' => 'nullable|boolean',
            'terms_conditions' => 'nullable'
        ]);

        // Handle is_featured checkbox
        $validated['is_featured'] = $request->has('is_featured');

        if ($request->hasFile('image')) {
            // Delete old image
            if ($specialOffer->image) {
                Storage::disk('public')->delete($specialOffer->image);
            }
            $validated['image'] = $request->file('image')->store('special-offers', 'public');
        }

        $specialOffer->update($validated);

        return redirect()
            ->route('admin.special-offers.index')
            ->with('success', 'Special offer updated successfully.');
    }

    public function destroy(SpecialOffer $specialOffer)
    {
        if ($specialOffer->image_path) {
            Storage::disk('public')->delete($specialOffer->image_path);
        }

        $specialOffer->delete();

        return redirect()
            ->route('admin.special-offers.index')
            ->with('success', 'Special offer deleted successfully.');
    }

    public function toggleFeatured(SpecialOffer $specialOffer)
    {
        $specialOffer->update([
            'is_featured' => !$specialOffer->is_featured
        ]);

        return response()->json([
            'success' => true,
            'is_featured' => $specialOffer->is_featured
        ]);
    }

    public function duplicate(SpecialOffer $specialOffer)
    {
        $newOffer = $specialOffer->replicate();
        $newOffer->title = $specialOffer->title . ' (Copy)';
        $newOffer->slug = Str::slug($newOffer->title);

        // If there's an image, duplicate it
        if ($specialOffer->image) {
            $originalPath = $specialOffer->image;
            $newPath = 'special-offers/' . uniqid() . '_' . basename($originalPath);
            Storage::disk('public')->copy($originalPath, $newPath);
            $newOffer->image = $newPath;
        }

        $newOffer->save();

        return response()->json([
            'success' => true,
            'message' => 'Special offer duplicated successfully'
        ]);
    }
}
