<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['images', 'variants'])->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            Log::info('Product creation started', ['request_data' => $request->all()]);

            // Convert express_delivery_countries array to JSON
            if ($request->has('express_delivery_countries')) {
                $request->merge([
                    'express_delivery_countries' => json_encode($request->express_delivery_countries)
                ]);
            }

            // Set default values for checkboxes if not present
            $request->merge([
                'dangerous_goods' => $request->has('dangerous_goods') ? 1 : 0,
                'is_draft' => $request->has('is_draft') ? 1 : 0
            ]);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:products',
                'category_id' => 'required|exists:categories,id',
                'description' => 'nullable|string',
                'highlights' => 'nullable|string',
                'sku' => 'required|string|unique:products',
                'shop_sku' => 'nullable|string',
                'brand' => 'nullable|string',
                'model' => 'nullable|string',
                'texture' => 'nullable|string',
                'color_family' => 'nullable|string',
                'country_of_origin' => 'nullable|string',
                'pack_type' => 'nullable|string',
                'volume' => 'nullable|string',
                'weight' => 'nullable|numeric',
                'material' => 'nullable|string',
                'features' => 'nullable|string',
                'express_delivery_countries' => 'nullable|json',
                'brand_classification' => 'nullable|string',
                'shelf_life' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'special_price' => 'nullable|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'package_weight' => 'nullable|numeric',
                'package_length' => 'nullable|numeric',
                'package_width' => 'nullable|numeric',
                'package_height' => 'nullable|numeric',
                'dangerous_goods' => 'boolean',
                'is_draft' => 'boolean',
                'status' => 'required|string|in:active,inactive,draft',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'variants' => 'nullable|array',
                'variants.*.name' => 'required_with:variants|string|max:255',
                'variants.*.value' => 'required_with:variants|string|max:255',
                'variants.*.price' => 'required_with:variants|numeric|min:0',
                'variants.*.special_price' => 'nullable|numeric|min:0',
                'variants.*.stock' => 'required_with:variants|integer|min:0',
                'variants.*.seller_sku' => 'nullable|string',
                'variants.*.status' => 'required_with:variants|string|in:active,inactive'
            ]);

            Log::info('Validation passed', ['validated_data' => $validated]);

            // Generate slug if not provided
            if (!$request->filled('slug')) {
                $validated['slug'] = Str::slug($validated['name']);
            }

            // Create the product
            $product = Product::create($validated);
            Log::info('Product created', ['product_id' => $product->id]);

            // Handle image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $key => $image) {
                    $path = $image->store('products', 'public');
                    $product->images()->create([
                        'image_path' => $path,
                        'sort_order' => $key,
                        'is_primary' => $key === 0 // First image is primary
                    ]);
                }
                Log::info('Product images uploaded', ['product_id' => $product->id]);
            }

            // Handle variants
            if ($request->has('variants')) {
                foreach ($request->input('variants') as $variantData) {
                    $product->variants()->create($variantData);
                }
                Log::info('Product variants created', ['product_id' => $product->id]);
            }

            return redirect()->route('admin.products.index')
                ->with('success', 'Product created successfully!');

        } catch (\Exception $e) {
            Log::error('Error creating product', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create product. ' . $e->getMessage()]);
        }
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'highlights' => 'nullable|string',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'shop_sku' => 'nullable|string',
            'brand' => 'nullable|string',
            'model' => 'nullable|string',
            'texture' => 'nullable|string',
            'color_family' => 'nullable|string',
            'country_of_origin' => 'nullable|string',
            'pack_type' => 'nullable|string',
            'volume' => 'nullable|string',
            'weight' => 'nullable|numeric',
            'material' => 'nullable|string',
            'features' => 'nullable|string',
            'express_delivery_countries' => 'nullable|json',
            'brand_classification' => 'nullable|string',
            'shelf_life' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'special_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'package_weight' => 'nullable|numeric',
            'package_length' => 'nullable|numeric',
            'package_width' => 'nullable|numeric',
            'package_height' => 'nullable|numeric',
            'dangerous_goods' => 'boolean',
            'is_draft' => 'boolean',
            'status' => 'required|string|in:active,inactive,draft'
        ]);

        $product->update($validated);

        // Handle new image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $key => $image) {
                $path = $image->store('products', 'public');
                $product->images()->create([
                    'image_path' => $path,
                    'sort_order' => $product->images->count() + $key,
                    'is_primary' => false
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        // Delete associated images from storage
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }

    public function saveAsDraft(Product $product)
    {
        $product->update([
            'is_draft' => true,
            'status' => 'draft'
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product saved as draft.');
    }
}
