<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics for the dashboard
        $stats = [
            'total_categories' => Category::count(),
            'active_categories' => Category::where('status', 1)->count(),
            'total_products' => Product::count(),
            'active_products' => Product::where('status', 1)->count(),
        ];

        return view('admin.dashboard.index', compact('stats'));
    }
}
