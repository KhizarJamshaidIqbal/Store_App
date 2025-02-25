<?php
// app/Http/Controllers/Admin/CategoryController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            // Get root categories with their nested children
            $categories = Category::whereNull('parent_id')
                ->with(['childrenRecursive' => function($query) {
                    $query->whereNull('deleted_at')
                        ->orderBy('sort_order')
                        ->with(['childrenRecursive' => function($q) {
                            $q->whereNull('deleted_at')
                                ->orderBy('sort_order');
                        }]);
                }])
                ->whereNull('deleted_at')
                ->orderBy('sort_order')
                ->paginate(10);

            // Get statistics using Eloquent
            $totalCategories = Category::count();
            $activeCategories = Category::where('status', 1)->count();
            $inactiveCategories = Category::where('status', 0)->count();
            $parentCategories = Category::whereNull('parent_id')->count();
            $subCategories = Category::whereNotNull('parent_id')->count();
            $trashedCategories = Category::onlyTrashed()->count();

            // Get all categories for parent filter
            $allCategories = Category::orderBy('name')->get();

            return view('admin.categories.index', compact(
                'categories',
                'totalCategories',
                'activeCategories',
                'inactiveCategories',
                'parentCategories',
                'subCategories',
                'trashedCategories',
                'allCategories'
            ));
        } catch (\Exception $e) {
            Log::error('Error in CategoryController@index: ' . $e->getMessage());
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'status' => 'boolean'
        ]);

        try {
            $category = Category::create([
                'name' => $validated['name'],
                'parent_id' => $validated['parent_id'],
                'description' => $validated['description'],
                'image' => $validated['image'] ?? null,
                'status' => $validated['status'] ?? true
            ]);

            return redirect()
                ->route('admin.categories.index')
                ->with('success', 'Category created successfully');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to create category. ' . $e->getMessage());
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
                'parent_id' => 'nullable|exists:categories,id',
                'description' => 'nullable|string',
                'status' => 'boolean',
                'image' => 'nullable|string'
            ]);

            $category->update([
                'name' => $validated['name'],
                'parent_id' => $validated['parent_id'],
                'description' => $validated['description'],
                'image' => $validated['image'] ?? $category->image,
                'status' => $validated['status'] ?? true
            ]);

            return redirect()
                ->route('admin.categories.index')
                ->with('success', 'Category updated successfully.');
        } catch (\Exception $e) {
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

    /**
     * Display the specified category.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\View\View
     */
    public function show(Category $category)
    {
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Display a listing of the trashed categories.
     *
     * @return \Illuminate\View\View
     */
    public function trashed()
    {
        $trashedCategories = Category::onlyTrashed()->paginate(10);
        return view('admin.categories.trashed', compact('trashedCategories'));
    }

    /**
     * Restore the specified category from trash.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();

        return redirect()->route('admin.categories.trashed')
            ->with('success', 'Category restored successfully');
    }

    /**
     * Permanently delete the specified category.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forceDelete($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->forceDelete();

        return redirect()->route('admin.categories.trashed')
            ->with('success', 'Category permanently deleted');
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
