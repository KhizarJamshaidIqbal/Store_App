{{-- resources/views/admin/categories/partials/category-item.blade.php --}}
<div class="category-item bg-white hover:bg-gray-50 rounded-lg border border-gray-200 transition-colors duration-200" data-level="{{ $level }}">
    <div class="flex items-center p-3">
        <!-- Toggle Button for Parent Categories -->
        <button class="category-toggle mr-2 w-6 text-gray-400 hover:text-gray-600 transition-transform duration-200 {{ $category->children->count() ? '' : 'invisible' }}">
            <i class="fas fa-chevron-right"></i>
        </button>

        <!-- Category Image/Icon -->
        <div class="w-10 h-10 flex-shrink-0 mr-3">
            @if($category->image)
                <img src="{{ asset('storage/' . $category->image) }}"
                     alt="{{ $category->name }}"
                     class="w-full h-full object-cover rounded-lg"
                     onerror="this.onerror=null; this.src='{{ asset('images/folder-icon.svg') }}';">
            @else
                <div class="w-full h-full bg-gray-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-folder text-gray-400 text-xl"></i>
                </div>
            @endif
        </div>

        <!-- Category Info -->
        <div class="flex-grow">
            <span class="font-medium text-gray-900">{{ $category->name }}</span>
        </div>

        <!-- Status Badge -->
        <div class="ml-4">
            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $category->status ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                {{ $category->status ? 'Active' : 'Inactive' }}
            </span>
        </div>

        <!-- Actions -->
        <div class="ml-4 flex items-center space-x-2">
            <a href="{{ route('admin.categories.edit', $category->id) }}"
               class="text-blue-600 hover:text-blue-700">
                <i class="fas fa-edit"></i>
            </a>
            <form action="{{ route('admin.categories.destroy', $category->id) }}"
                  method="POST"
                  class="inline"
                  onsubmit="return confirm('Are you sure you want to delete this category?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-700">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Children Categories -->
    @if($category->children->count() > 0)
        <div class="category-children hidden ml-8 border-l border-gray-200 pl-4 my-2">
            @foreach($category->children as $child)
                @include('admin.categories.partials.category-item', ['category' => $child, 'level' => $level + 1])
            @endforeach
        </div>
    @endif
</div>
