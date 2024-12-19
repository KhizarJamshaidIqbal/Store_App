<!-- resources/views/admin/categories/partials/category-tree.blade.php -->
<ul class="list-unstyled">
    @foreach($categories as $category)
        <li class="mb-2">
            <div class="d-flex align-items-center">
                <span class="mr-2">{{ $category->name }}</span>
                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-info mr-1">
                    Edit
                </a>
                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                        Delete
                    </button>
                </form>
            </div>
            @if($category->children->count())
                <div class="ml-4 mt-2">
                    @include('admin.categories.partials.category-tree', ['categories' => $category->children])
                </div>
            @endif
        </li>
    @endforeach
</ul>