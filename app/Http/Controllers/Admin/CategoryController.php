<?php
// app/Http/Controllers/Admin/CategoryController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            // Get root categories with their nested children
            $categories = Category::whereNull('parent_id')
                ->with(['childrenRecursive' => function($query) {
                    $query->whereNull('deleted_at')->orderBy('sort_order');
                }])
                ->whereNull('deleted_at')
                ->orderBy('sort_order')
                ->get();

            // Get statistics using Eloquent
            $totalCategories = Category::count();
            $activeCategories = Category::where('status', 1)->count();
            $inactiveCategories = Category::where('status', 0)->count();
            $parentCategories = Category::whereNull('parent_id')->count();
            $subCategories = Category::whereNotNull('parent_id')->count();

            // Get all categories for parent filter
            $allCategories = Category::orderBy('name')->get();

            // Debug information
            \Log::info('Categories loaded:', [
                'count' => $categories->count(),
                'first_category' => $categories->first() ? $categories->first()->toArray() : null,
                'stats' => [
                    'total' => $totalCategories,
                    'active' => $activeCategories,
                    'inactive' => $inactiveCategories,
                    'parent' => $parentCategories,
                    'sub' => $subCategories
                ]
            ]);

            return view('admin.categories.index', [
                'categories' => $categories,
                'totalCategories' => $totalCategories,
                'activeCategories' => $activeCategories,
                'inactiveCategories' => $inactiveCategories,
                'parentCategories' => $parentCategories,
                'subCategories' => $subCategories,
                'allCategories' => $allCategories
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in CategoryController@index: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while loading categories.');
        }
    }

    public function create()
    {
        try {
            $categories = Category::whereNull('parent_id')
                ->with('childrenRecursive')
                ->orderBy('sort_order')
                ->get();

            return view('admin.categories.create', compact('categories'));
        } catch (\Exception $e) {
            Log::error('Category Create Error: ' . $e->getMessage());
            return back()->with('error', 'Error loading category form: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'parent_id' => 'nullable|exists:categories,id',
                'description' => 'nullable|string',
                'status' => 'boolean',
                'sort_order' => 'integer|min:0'
            ]);

            $validated['slug'] = $this->generateUniqueSlug($validated['name']);
            
            $category = Category::create($validated);

            return redirect()
                ->route('admin.categories.index')
                ->with('success', 'Category created successfully.');
        } catch (\Exception $e) {
            Log::error('Category Store Error: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Error creating category: ' . $e->getMessage());
        }
    }

    public function edit(Category $category)
    {
        try {
            $categories = Category::whereNull('parent_id')
                ->where('id', '!=', $category->id)
                ->with('childrenRecursive')
                ->orderBy('sort_order')
                ->get();

            return view('admin.categories.edit', compact('category', 'categories'));
        } catch (\Exception $e) {
            Log::error('Category Edit Error: ' . $e->getMessage());
            return back()->with('error', 'Error loading category: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Category $category)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'parent_id' => [
                    'nullable',
                    'exists:categories,id',
                    function ($attribute, $value, $fail) use ($category) {
                        if ($value == $category->id) {
                            $fail('A category cannot be its own parent.');
                        }
                    },
                ],
                'description' => 'nullable|string',
                'status' => 'boolean',
                'sort_order' => 'integer|min:0'
            ]);

            // Handle status explicitly
            $validated['status'] = (bool) $request->input('status', false);

            if ($request->filled('name') && $category->name !== $request->name) {
                $validated['slug'] = $this->generateUniqueSlug($request->name, $category->id);
            }

            $category->update($validated);

            return redirect()
                ->route('admin.categories.index')
                ->with('success', 'Category updated successfully.');
        } catch (\Exception $e) {
            Log::error('Category Update Error: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Error updating category: ' . $e->getMessage());
        }
    }

    public function destroy(Category $category)
    {
        try {
            $category->delete();
            return redirect()
                ->route('admin.categories.index')
                ->with('success', 'Category deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Category Delete Error: ' . $e->getMessage());
            return back()->with('error', 'Error deleting category: ' . $e->getMessage());
        }
    }

    protected function generateUniqueSlug($name, $excludeId = null)
    {
        $slug = Str::slug($name);
        $count = 1;
        
        while (true) {
            $query = Category::where('slug', $slug);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
            
            if (!$query->exists()) {
                break;
            }
            
            $slug = Str::slug($name) . '-' . $count++;
        }
        
        return $slug;
    }
}