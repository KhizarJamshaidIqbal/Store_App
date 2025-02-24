<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::latest()->paginate(10);
        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'meta_title' => 'nullable|max:255',
            'meta_description' => 'nullable',
            'status' => 'required|in:published,draft',
            'slug' => 'nullable|unique:pages,slug'
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        Page::create($validated);

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Page created successfully.');
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'meta_title' => 'nullable|max:255',
            'meta_description' => 'nullable',
            'status' => 'required|in:published,draft',
            'slug' => 'nullable|unique:pages,slug,' . $page->id
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $page->update($validated);

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Page updated successfully.');
    }

    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Page deleted successfully.');
    }

    public function duplicate(Page $page)
    {
        // Create a new page instance
        $newPage = $page->replicate();

        // Modify the title and slug to indicate it's a copy
        $newPage->title = $page->title . ' (Copy)';
        $newPage->slug = $page->slug . '-copy-' . time();
        $newPage->status = 'draft'; // Set status to draft by default

        // Save the new page
        $newPage->save();

        return redirect()
            ->route('admin.pages.edit', $newPage)
            ->with('success', 'Page duplicated successfully. You are now editing the copy.');
    }
}
