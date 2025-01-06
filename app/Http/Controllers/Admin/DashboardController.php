<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
{
    $stats = [
        'total_categories' => Category::count(),
        'active_categories' => Category::where('status', 1)->count(),
        'total_products' => Product::count(),
        'active_products' => Product::where('status', 'active')->count(),  // Changed to match the actual status value
    ];

    return view('admin.dashboard.index', compact('stats'));
}
}
