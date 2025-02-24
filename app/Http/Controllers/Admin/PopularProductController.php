<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\PopularProduct;
use Illuminate\Http\Request;

class PopularProductController extends Controller
{
    public function index()
    {
        $popularProducts = PopularProduct::with('product')
            ->orderBy('position')
            ->get();

        return view('admin.popular-products.index', compact('popularProducts'));
    }

    public function create()
    {
        $products = Product::where('is_draft', false)
            ->where('status', 'active')
            ->whereNull('deleted_at')
            ->whereNotIn('id', PopularProduct::pluck('product_id'))
            ->with(['category', 'images'])
            ->get();

        return view('admin.popular-products.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id'
        ]);

        foreach ($request->product_ids as $productId) {
            PopularProduct::create([
                'product_id' => $productId
            ]);
        }

        return redirect()->route('admin.popular-products.index')
            ->with('success', 'Products added to popular products successfully');
    }

    public function destroy(PopularProduct $popularProduct)
    {
        $popularProduct->delete();

        return redirect()
            ->route('admin.popular-products.index')
            ->with('success', 'Product removed from popular products successfully.');
    }

    public function updatePositions(Request $request)
    {
        $positions = $request->validate([
            'positions' => 'required|array',
            'positions.*' => 'required|integer|exists:popular_products,id',
        ]);

        foreach ($positions['positions'] as $index => $id) {
            PopularProduct::where('id', $id)->update(['position' => $index]);
        }

        return response()->json(['success' => true]);
    }
}
