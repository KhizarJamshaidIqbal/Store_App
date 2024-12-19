<!-- resources/views/admin/categories/partials/category-options.blade.php -->
@foreach($categories as $category)
    <option value="{{ $category->id }}" {{ isset($selectedId) && $selectedId == $category->id ? 'selected' : '' }}>
        {{ $category->name }}
    </option>
    @if($category->children)
        @foreach($category->children as $childCategory)
            <option value="{{ $childCategory->id }}" {{ isset($selectedId) && $selectedId == $childCategory->id ? 'selected' : '' }}>
                &nbsp;&nbsp;&nbsp;&nbsp;└─ {{ $childCategory->name }}
            </option>
            @if($childCategory->children)
                @foreach($childCategory->children as $grandChild)
                    <option value="{{ $grandChild->id }}" {{ isset($selectedId) && $selectedId == $grandChild->id ? 'selected' : '' }}>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└─ {{ $grandChild->name }}
                    </option>
                    @if($grandChild->children)
                        @foreach($grandChild->children as $greatGrandChild)
                            <option value="{{ $greatGrandChild->id }}" {{ isset($selectedId) && $selectedId == $greatGrandChild->id ? 'selected' : '' }}>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└─ {{ $greatGrandChild->name }}
                            </option>
                        @endforeach
                    @endif
                @endforeach
            @endif
        @endforeach
    @endif
@endforeach