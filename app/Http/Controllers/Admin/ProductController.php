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
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['images', 'category']); // Eager load relationships

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Calculate statistics
        $statistics = [
            'total_products' => Product::count(),
            'active_products' => Product::where('status', 'active')->count(),
            'inactive_products' => Product::where('status', 'inactive')->count(),
            'low_stock_products' => Product::where('stock', '<=', 10)->count(),
            'total_sales_value' => Product::sum('price'),
        ];

        $categories = Category::all();
        $products = $query->latest()->paginate(12);

        return view('admin.products.index', compact('products', 'categories', 'statistics'));
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
                'express_delivery_countries' => 'nullable|array',
                'express_delivery_countries.*' => 'string',
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

            // Convert express_delivery_countries array to JSON
            $validated['express_delivery_countries'] = json_encode($validated['express_delivery_countries'] ?? []);

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
        // Get images ordered by is_primary first, then by sort_order
        $product->load(['images' => function($query) {
            $query->orderByDesc('is_primary')->orderBy('sort_order');
        }]);
        
        // Get all categories
        $categories = Category::all();
        
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        try {
            DB::beginTransaction();

            // Validate the request
            $validatedData = $request->validate([
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
                'express_delivery_countries' => 'nullable|array',
                'express_delivery_countries.*' => 'string',
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
                'variants' => 'nullable|array',
                'variants.*.name' => 'required|string|max:255',
                'variants.*.value' => 'required|string|max:255',
                'variants.*.price' => 'required|numeric|min:0',
                'variants.*.special_price' => 'nullable|numeric|min:0',
                'variants.*.stock' => 'required|integer|min:0',
                'variants.*.status' => 'required|in:active,inactive',
            ]);

            // Update product
            $product->update([
                'name' => $validatedData['name'],
                'slug' => $validatedData['slug'],
                'category_id' => $validatedData['category_id'],
                'description' => $validatedData['description'],
                'highlights' => $validatedData['highlights'],
                'sku' => $validatedData['sku'],
                'shop_sku' => $validatedData['shop_sku'],
                'brand' => $validatedData['brand'],
                'model' => $validatedData['model'],
                'texture' => $validatedData['texture'],
                'color_family' => $validatedData['color_family'],
                'country_of_origin' => $validatedData['country_of_origin'],
                'pack_type' => $validatedData['pack_type'],
                'volume' => $validatedData['volume'],
                'weight' => $validatedData['weight'],
                'material' => $validatedData['material'],
                'features' => $validatedData['features'],
                'express_delivery_countries' => json_encode($validatedData['express_delivery_countries'] ?? []),
                'brand_classification' => $validatedData['brand_classification'],
                'shelf_life' => $validatedData['shelf_life'],
                'price' => $validatedData['price'],
                'special_price' => $validatedData['special_price'],
                'stock' => $validatedData['stock'],
                'package_weight' => $validatedData['package_weight'],
                'package_length' => $validatedData['package_length'],
                'package_width' => $validatedData['package_width'],
                'package_height' => $validatedData['package_height'],
                'dangerous_goods' => $request->has('dangerous_goods'),
                'is_draft' => $request->has('is_draft'),
                'status' => $validatedData['status']
            ]);

            // Handle variants
            if (isset($validatedData['variants'])) {
                // Delete existing variants
                $product->variants()->delete();

                // Create new variants
                foreach ($validatedData['variants'] as $variantData) {
                    $product->variants()->create([
                        'name' => $variantData['name'],
                        'value' => $variantData['value'],
                        'price' => $variantData['price'],
                        'special_price' => $variantData['special_price'] ?? null,
                        'stock' => $variantData['stock'],
                        'status' => $variantData['status'],
                    ]);
                }
            }

            DB::commit();

            if ($request->expectsJson()) {
                $product->load(['variants', 'category']);
                return response()->json([
                    'success' => true,
                    'message' => 'Product updated successfully',
                    'data' => [
                        'product' => $product,
                        'category' => [
                            'id' => $product->category->id,
                            'name' => $product->category->name
                        ]
                    ]
                ]);
            }

            return redirect()->route('admin.products.index')
                ->with('success', 'Product updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update product: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()
                ->withErrors(['error' => 'Failed to update product: ' . $e->getMessage()]);
        }
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

    public function saveAsDraft(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $validated['dangerous_goods'] = $request->has('dangerous_goods') ? 1 : 0;

        return redirect()->route('admin.products.index')
        ->with('success', 'Product saved as draft.');
    }

    /**
     * Set image as primary
     */
    public function setImageAsPrimary(ProductImage $image)
    {
        try {
            // Get all images for this product
            $product = $image->product;
            
            // Remove primary flag from all other images
            $product->images()->update(['is_primary' => false]);
            
            // Set this image as primary and update sort order
            $image->update([
                'is_primary' => true,
                'sort_order' => 0
            ]);
            
            // Reorder other images
            $otherImages = $product->images()
                ->where('id', '!=', $image->id)
                ->orderBy('sort_order')
                ->get();
            
            foreach ($otherImages as $index => $otherImage) {
                $otherImage->update(['sort_order' => $index + 1]);
            }

            // Return updated images for frontend
            $updatedImages = $product->images()
                ->orderBy('is_primary', 'desc')
                ->orderBy('sort_order')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Primary image set successfully',
                'images' => $updatedImages
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to set primary image: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete product image
     */
    public function deleteImage(ProductImage $image)
    {
        try {
            $product = $image->product;
            $wasPrimary = $image->is_primary;
            
            // Delete the physical file
            if (Storage::exists($image->image_path)) {
                Storage::delete($image->image_path);
            }
            
            // Delete from database
            $image->delete();

            // If this was the primary image, set the first remaining image as primary
            if ($wasPrimary) {
                $firstImage = $product->images()->first();
                if ($firstImage) {
                    $firstImage->update([
                        'is_primary' => true,
                        'sort_order' => 0
                    ]);
                }
            }

            // Reorder remaining images
            $remainingImages = $product->images()
                ->orderBy('is_primary', 'desc')
                ->orderBy('sort_order')
                ->get();
            
            foreach ($remainingImages as $index => $img) {
                $img->update(['sort_order' => $index]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully',
                'images' => $remainingImages
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete image: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload product images
     */
    public function uploadImages(Request $request, Product $product)
    {
        try {
            $request->validate([
                'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240'
            ]);

            if (!$request->hasFile('images')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No images were uploaded.'
                ], 400);
            }

            $uploadedImages = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                
                // Get the last sort order
                $lastSortOrder = $product->images()->max('sort_order') ?? -1;
                
                // Create image record
                $productImage = $product->images()->create([
                    'image_path' => $path,
                    'sort_order' => $lastSortOrder + 1,
                    'is_primary' => $product->images()->count() === 0 // Set as primary if it's the first image
                ]);

                $uploadedImages[] = $productImage;
            }

            return response()->json([
                'success' => true,
                'message' => count($uploadedImages) . ' images uploaded successfully',
                'images' => $uploadedImages
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload images: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update image order for a product
     */
    public function updateImageOrder(Request $request)
    {
        try {
            $request->validate([
                'order' => 'required|array',
                'order.*.id' => 'required|exists:product_images,id',
                'order.*.order' => 'required|integer|min:0'
            ]);

            $images = $request->input('order');
            
            // Update each image's sort order
            foreach ($images as $image) {
                ProductImage::where('id', $image['id'])->update(['sort_order' => $image['order']]);
            }

            // Get the first image from the order
            if (count($images) > 0) {
                $firstImage = ProductImage::find($images[0]['id']);
                if ($firstImage) {
                    $updatedImages = $firstImage->product->images()
                        ->orderBy('is_primary', 'desc')
                        ->orderBy('sort_order')
                        ->get();

                    return response()->json([
                        'success' => true,
                        'message' => 'Image order updated successfully',
                        'images' => $updatedImages
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Image order updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update image order: ' . $e->getMessage()
            ], 500);
        }
    }
}
