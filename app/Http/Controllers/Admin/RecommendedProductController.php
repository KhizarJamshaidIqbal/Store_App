<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\RecommendedProduct;
use Illuminate\Http\Request;

class RecommendedProductController extends Controller
{
    public function index()
    {
        $recommendedProducts = RecommendedProduct::with('product')
            ->orderBy('position')
            ->get();

        return view('admin.recommended-products.index', compact('recommendedProducts'));
    }

    public function create()
    {
        $products = Product::where('is_draft', false)
            ->where('status', 'active')
            ->whereNull('deleted_at')
            ->whereNotIn('id', RecommendedProduct::pluck('product_id'))
            ->with(['category', 'images'])
            ->get();

        return view('admin.recommended-products.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id'
        ]);

        foreach ($request->product_ids as $productId) {
            RecommendedProduct::create([
                'product_id' => $productId
            ]);
        }

        return redirect()->route('admin.recommended-products.index')
            ->with('success', 'Products added to recommendations successfully');
    }

    public function destroy(RecommendedProduct $recommendedProduct)
    {
        $recommendedProduct->delete();

        return redirect()
            ->route('admin.recommended-products.index')
            ->with('success', 'Product removed from recommendations successfully.');
    }

    public function updatePositions(Request $request)
    {
        $positions = $request->validate([
            'positions' => 'required|array',
            'positions.*' => 'required|integer|exists:recommended_products,id',
        ]);

        foreach ($positions['positions'] as $index => $id) {
            RecommendedProduct::where('id', $id)->update(['position' => $index]);
        }

        return response()->json(['success' => true]);
    }
}
