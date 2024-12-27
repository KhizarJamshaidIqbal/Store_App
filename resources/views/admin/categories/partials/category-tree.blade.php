<!-- resources/views/admin/categories/partials/category-tree.blade.php -->
<ul class="list-unstyled p-3">
    @foreach($categories as $category)
        <li class="mb-3">
            <div class="d-flex align-items-center p-2 bg-light rounded">
                <span class="mr-3">
                    @if($category->children->count())
                        <i class="fas fa-folder"></i>
                    @else
                        <i class="fas fa-file"></i>
                    @endif
                    {{ $category->name }}
                    <span class="badge {{ $category->status ? 'bg-success' : 'bg-danger' }} mx-2">
                        {{ $category->status ? 'Active' : 'Inactive' }}
                    </span>
                </span>
                <div class="ml-auto">
                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-info mr-2">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this category?')">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
            @if($category->children->count())
                <div class="ml-4 mt-2">
                    @include('admin.categories.partials.category-tree', ['categories' => $category->children])
                </div>
            @endif
        </li>
    @endforeach
</ul>
