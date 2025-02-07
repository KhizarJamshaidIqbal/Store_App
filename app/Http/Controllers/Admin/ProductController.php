<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $currentTab = $request->get('tab', 'all');
        $query = Product::query();

        // Apply tab filters
        switch ($currentTab) {
            case 'active':
                $query->where('status', 'active')->where('is_draft', false);
                break;
            case 'inactive':
                $query->where('status', 'inactive')->where('is_draft', false);
                break;
            case 'draft':
                $query->where('is_draft', true);
                break;
            case 'trashed':
                $query->onlyTrashed();
                break;
            default:
                // 'all' tab - show everything except trashed
                $query->whereNull('deleted_at');
                break;
        }

        // Apply search filter
        if ($request->filled('search')) {
            $searchTerm = $request->get('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('sku', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        // Apply category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->get('category'));
        }

        // Apply sorting
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'oldest':
                $query->oldest();
                break;
            default: // 'latest'
                $query->latest();
                break;
        }

        // Get counts for tabs
        $totalProducts = Product::count();
        $activeProducts = Product::where('status', 'active')->where('is_draft', false)->count();
        $inactiveProducts = Product::where('status', 'inactive')->where('is_draft', false)->count();
        $draftProducts = Product::where('is_draft', true)->count();
        $trashedProducts = Product::onlyTrashed()->count();
        $lowStockProducts = Product::where('stock', '<', 10)->where('status', 'active')->count();

        // Calculate statistics for the cards
        $statistics = [
            'total_products' => $totalProducts,
            'active_products' => $activeProducts,
            'inactive_products' => $inactiveProducts,
            'low_stock_products' => $lowStockProducts,
            'draft_products' => $draftProducts,
            'trashed_products' => $trashedProducts
        ];

        // Get categories for filtering
        $categories = Category::whereNull('parent_id')
            ->with(['childrenRecursive' => function($query) {
                $query->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();

        // Get products with their relationships
        $perPage = $request->get('per_page', 12);
        $products = $query->with(['category', 'images'])->paginate($perPage);

        // Append query parameters to pagination links
        $products->appends($request->except('page'));

        return view('admin.products.index', compact(
            'products',
            'currentTab',
            'totalProducts',
            'activeProducts',
            'inactiveProducts',
            'draftProducts',
            'trashedProducts',
            'lowStockProducts',
            'statistics',
            'categories'
        ));
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
                'sku' => 'nullable|string|unique:products',
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
                'price' => 'nullable|numeric|min:0',
                'special_price' => 'nullable|numeric|min:0',
                'stock' => 'nullable|integer|min:0',
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

            // Convert express_delivery_countries array to JSON after validation
            if (isset($validated['express_delivery_countries'])) {
                $validated['express_delivery_countries'] = json_encode($validated['express_delivery_countries']);
            }

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
        $categories = Category::whereNull('parent_id')
            ->with('childrenRecursive')
            ->orderBy('sort_order')
            ->get();

        $product->load('category.parent');

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
                'sku' => 'nullable|string|unique:products,sku,' . $product->id,
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
                'price' => 'nullable|numeric|min:0',
                'special_price' => 'nullable|numeric|min:0',
                'stock' => 'nullable|integer|min:0',
                'package_weight' => 'nullable|numeric',
                'package_length' => 'nullable|numeric',
                'package_width' => 'nullable|numeric',
                'package_height' => 'nullable|numeric',
                'dangerous_goods' => 'boolean',
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
                'dangerous_goods' => $validatedData['dangerous_goods'] ?? false,
                'status' => $validatedData['status'],
                'is_draft' => $request->status === 'Draft' ? 1 : 0,
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
            $product = Product::findOrFail($product->id);
            // If status is active, ensure is_draft is false
    if ($request->status === 'active') {
        $request->merge(['is_draft' => 0]);
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

    public function restore($id)
    {
        try {
            $product = Product::withTrashed()->findOrFail($id);
            $product->restore();

            return redirect()->back()->with('success', 'Product restored successfully.');
        } catch (Exception $e) {
            Log::error('Error restoring product: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to restore product.');
        }
    }

    public function forceDelete($id)
    {
        try {
            $product = Product::withTrashed()->findOrFail($id);

            // Delete product images from storage
            foreach ($product->images as $image) {
                if (Storage::exists($image->image_path)) {
                    Storage::delete($image->image_path);
                }
            }

            // Force delete the product and its relationships
            $product->forceDelete();

            return redirect()->back()->with('success', 'Product permanently deleted.');
        } catch (Exception $e) {
            Log::error('Error force deleting product: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to permanently delete product.');
        }
    }

    public function saveAsDraft(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
            ]);

            // Create new product
            $product = new Product();
            $product->name = $validated['name'];
            $product->category_id = $validated['category_id'];
            $product->slug = Str::slug($validated['name']);
            $product->is_draft = true;
            $product->status = 'draft';

            // Optional fields
            if ($request->filled('description')) {
                $product->description = $request->description;
            }
            if ($request->filled('highlights')) {
                $product->highlights = $request->highlights;
            }
            if ($request->filled('price')) {
                $product->price = $request->price;
            }
            if ($request->filled('stock')) {
                $product->stock = $request->stock;
            }

            $product->save();

            return redirect()->route('admin.products.index')
                ->with('success', 'Product saved as draft successfully.');
        } catch (Exception $e) {
            Log::error('Draft save error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to save draft. Please try again.']);
        }
    }

    /**
     * Upload images for a product
     */
    public function uploadImages(Request $request, Product $product)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240'
        ]);

        $uploadedImages = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');

                $productImage = $product->images()->create([
                    'image_path' => $path,
                    'sort_order' => $product->images()->count() + 1,
                    'is_primary' => $product->images()->count() === 0 // First image is primary
                ]);

                $uploadedImages[] = $productImage;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Images uploaded successfully',
            'images' => $uploadedImages
        ]);
    }

    /**
     * Delete a product image
     */
    public function deleteImage(ProductImage $image)
    {
        // Delete the file from storage
        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }

        // If this was the primary image, set another image as primary
        if ($image->is_primary) {
            $nextImage = $image->product->images()->where('id', '!=', $image->id)->first();
            if ($nextImage) {
                $nextImage->update(['is_primary' => true]);
            }
        }

        $image->delete();

        return response()->json([
            'success' => true,
            'message' => 'Image deleted successfully'
        ]);
    }

    /**
     * Set an image as primary
     */
    public function setImageAsPrimary(ProductImage $image)
    {
        // Remove primary status from all other images of this product
        $image->product->images()->where('id', '!=', $image->id)->update(['is_primary' => false]);

        // Set this image as primary
        $image->update(['is_primary' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Primary image set successfully'
        ]);
    }

    /**
     * Reorder product images
     */
    public function reorderImages(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'required|integer|exists:product_images,id'
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->order as $index => $imageId) {
                ProductImage::where('id', $imageId)->update(['sort_order' => $index + 1]);
            }

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to update sort order'], 500);
        }
    }
}
