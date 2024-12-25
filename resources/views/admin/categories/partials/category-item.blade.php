{{-- resources/views/admin/categories/partials/category-item.blade.php --}}
<div class="category-item" data-level="{{ $level }}">
    <div class="flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors duration-200">
        <div class="flex items-center space-x-3">
            @if($category->childrenRecursive->count() > 0)
                <button class="category-toggle w-6 h-6 flex items-center justify-center text-gray-400 hover:text-gray-600">
                    <i class="fas fa-chevron-right transform transition-transform duration-200"></i>
                </button>
            @else
                <div class="w-6 h-6"></div>
            @endif
            
            <i class="fas fa-folder {{ $level == 0 ? 'text-yellow-500' : ($level == 1 ? 'text-blue-500' : 'text-purple-500') }}"></i>
            <span class="font-medium text-gray-700">{{ $category->name }}</span>
            @if($category->status)
                <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Active</span>
            @else
                <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">Inactive</span>
            @endif
        </div>
        
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.categories.edit', $category->id) }}" 
               class="p-2 text-blue-500 hover:bg-blue-100 rounded-lg transition-colors duration-200">
                <i class="fas fa-edit"></i>
            </a>
            <form action="{{ route('admin.categories.destroy', $category->id) }}" 
                  method="POST" 
                  class="inline-block"
                  onsubmit="return confirm('Are you sure you want to delete this category?');">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="p-2 text-red-500 hover:bg-red-100 rounded-lg transition-colors duration-200">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        </div>
    </div>

    @if($category->childrenRecursive->count() > 0)
        <div class="category-children ml-8 mt-2 hidden">
            @foreach($category->childrenRecursive as $child)
                @include('admin.categories.partials.category-item', ['category' => $child, 'level' => $level + 1])
            @endforeach
        </div>
    @endif
</div>