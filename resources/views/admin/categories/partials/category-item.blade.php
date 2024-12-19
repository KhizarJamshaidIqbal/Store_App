<!-- resources/views/admin/categories/partials/category-item.blade.php -->
@php
    $hasChildren = \App\Models\Category::where('parent_id', $category->id)->count() > 0;
@endphp

<div class="category-item group">
    <div class="border border-gray-100 bg-white rounded-lg hover:bg-gray-50 transition-all duration-200">
        <div class="category-header px-4 py-3 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <!-- Toggle Icon -->
                <div class="w-6 h-6 flex items-center justify-center category-toggle cursor-pointer">
                    @if($hasChildren)
                        <i class="fas fa-chevron-down arrow-icon transition-transform duration-200 text-gray-400 group-hover:text-gray-600"></i>
                    @endif
                </div>

                <!-- Category Name and Badge -->
                <div class="flex items-center gap-3">
                    <span class="category-name text-gray-700 font-medium">
                        {!! str_repeat('- ', $level) !!}{{ $category->name }}
                    </span>
                    @if($category->status)
                        <span class="status-badge inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Active
                        </span>
                    @else
                        <span class="status-badge inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Inactive
                        </span>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                <a href="{{ route('admin.categories.edit', $category->id) }}" 
                   class="inline-flex items-center px-3 py-1.5 text-sm bg-blue-500 hover:bg-blue-600 text-white rounded-md transition-colors">
                    <i class="fas fa-edit mr-1.5"></i>
                    Edit
                </a>
                
                <form action="{{ route('admin.categories.destroy', $category->id) }}" 
                      method="POST" 
                      class="inline-block delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center px-3 py-1.5 text-sm bg-red-500 hover:bg-red-600 text-white rounded-md transition-colors">
                        <i class="fas fa-trash mr-1.5"></i>
                        Delete
                    </button>
                </form>
            </div>
        </div>

        @if($hasChildren)
            <div class="children hidden border-t border-gray-100">
                <div class="pl-8 border-l-2 border-gray-100 ml-7 my-1">
                    @foreach(\App\Models\Category::where('parent_id', $category->id)->orderBy('order')->get() as $childCategory)
                        @include('admin.categories.partials.category-item', [
                            'category' => $childCategory,
                            'level' => $level + 1
                        ])
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>