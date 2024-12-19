<tr class="category-row hover:bg-gray-50/50 transition-colors duration-150" 
    data-parent-id="{{ $category->parent_id }}" 
    data-category-id="{{ $category->id }}">
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="flex items-center">
            <div class="flex items-center" style="margin-left: {{ ($level ?? 0) * 1.5 }}rem;">
                @if($category->childrenRecursive->count() > 0)
                    <button type="button" 
                            class="toggle-btn w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-600 focus:outline-none transition-colors duration-150" 
                            data-id="{{ $category->id }}">
                        <i class="fas fa-plus-square text-lg"></i>
                    </button>
                @else
                    <div class="w-8 h-8"></div>
                @endif
                <span class="ml-2 text-sm font-medium text-gray-900">{{ $category->name }}</span>
            </div>
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <span class="status-badge inline-flex px-2.5 py-1 rounded-full text-xs font-medium {{ $category->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
            {{ $category->status ? 'Active' : 'Inactive' }}
        </span>
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-right">
        <div class="flex items-center justify-end space-x-4">
            <a href="{{ route('admin.categories.edit', $category->id) }}" 
               class="text-blue-600 hover:text-blue-800 transition-colors duration-150">
                <i class="fas fa-edit"></i>
            </a>
            <form action="{{ route('admin.categories.destroy', $category->id) }}" 
                  method="POST" 
                  class="inline-block"
                  onsubmit="return confirm('Are you sure you want to delete this category? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="text-red-600 hover:text-red-800 transition-colors duration-150">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </form>
        </div>
    </td>
</tr>

@if($category->childrenRecursive->count() > 0)
    @foreach($category->childrenRecursive->sortBy('sort_order') as $child)
        @include('admin.categories.partials.category-row', [
            'category' => $child,
            'level' => ($level ?? 0) + 1
        ])
    @endforeach
@endif
