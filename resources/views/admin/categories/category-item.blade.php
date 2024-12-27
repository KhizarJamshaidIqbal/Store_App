<div class="category-item" style="padding-left: {{ $level * 2 }}rem;">
    <div class="group relative flex items-center justify-between p-4 rounded-lg hover:bg-gray-50 transition-all duration-200">
        <!-- Left side with expand/collapse and category info -->
        <div class="flex items-center space-x-4">
            @if($category->children->count() > 0)
                <button class="category-toggle w-6 h-6 flex items-center justify-center rounded-full hover:bg-gray-200 transition-colors duration-200">
                    <i class="fas fa-chevron-right arrow-icon transition-transform duration-200"></i>
                </button>
            @else
                <div class="w-6"></div>
            @endif

            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-lg bg-{{ $category->status ? 'blue' : 'gray' }}-50 flex items-center justify-center">
                        <i class="fas fa-folder text-{{ $category->status ? 'blue' : 'gray' }}-500"></i>
                    </div>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-900">{{ $category->name }}</h3>
                    @if($category->description)
                        <p class="text-xs text-gray-500 mt-0.5 line-clamp-1">{{ $category->description }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right side with status and actions -->
        <div class="flex items-center space-x-4">
            <!-- Status Badge -->
            <span class="status-badge inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category->status ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                {{ $category->status ? 'Active' : 'Inactive' }}
            </span>

            <!-- Actions -->
            <div class="flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-all duration-200">
                <a href="{{ route('admin.categories.edit', $category->id) }}"
                   class="p-2 rounded-lg text-gray-500 hover:text-blue-600 hover:bg-blue-50 transition-all duration-200">
                    <i class="fas fa-edit"></i>
                </a>
                <form action="{{ route('admin.categories.destroy', $category->id) }}"
                      method="POST"
                      class="inline-block"
                      onsubmit="return confirm('Are you sure you want to delete this category?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="p-2 rounded-lg text-gray-500 hover:text-red-600 hover:bg-red-50 transition-all duration-200">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Children categories -->
    @if($category->children->count() > 0)
        <div class="children ml-6 mt-1 space-y-1 hidden">
            @foreach($category->children as $child)
                @include('admin.categories.category-item', ['category' => $child, 'level' => $level + 1])
            @endforeach
        </div>
    @endif
</div>
