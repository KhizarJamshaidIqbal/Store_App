@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Add New Recommendation</h1>
            <p class="mt-2 text-gray-600">Select products to feature in your recommendations list.</p>
        </div>

        <!-- Product Selection Grid -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6">
                <form action="{{ route('admin.recommended-products.store') }}" method="POST">
                    @csrf

                    <!-- Search and Filter Section -->
                    <div class="mb-6">
                        <div class="flex gap-4">
                            <div class="flex-1">
                                <input type="text"
                                       id="search"
                                       placeholder="Search products..."
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                       oninput="filterProducts(this.value)">
                            </div>
                            <div class="w-48">
                                <select id="category-filter"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                        onchange="filterByCategory(this.value)">
                                    <option value="">All Categories</option>
                                    @foreach($products->pluck('category.name')->unique() as $category)
                                        <option value="{{ $category }}">{{ $category }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="products-grid">
                        @foreach($products as $product)
                        <div class="product-card relative group"
                             data-name="{{ strtolower($product->name) }}"
                             data-category="{{ strtolower($product->category->name ?? '') }}">
                            <label class="block cursor-pointer">
                                <input type="checkbox"
                                       name="product_ids[]"
                                       value="{{ $product->id }}"
                                       class="absolute opacity-0 peer">
                                <div class="border border-gray-200 rounded-xl overflow-hidden transition-all duration-300
                                            hover:border-blue-500 hover:shadow-lg
                                            peer-checked:border-blue-500 peer-checked:shadow-lg">
                                    <!-- Product Image -->
                                    <div class="aspect-w-16 aspect-h-9 bg-gray-100">
                                        @if($product->primary_image)
                                            <img src="{{ asset('storage/' . $product->primary_image) }}"
                                                 alt="{{ $product->name }}"
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="flex items-center justify-center h-full bg-gray-50">
                                                <i class="fas fa-image text-4xl text-gray-400"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Product Info -->
                                    <div class="p-4">
                                        <h3 class="font-semibold text-gray-900 mb-1">{{ $product->name }}</h3>
                                        <div class="text-sm text-gray-600 mb-2">
                                            {{ $product->category->name ?? 'No Category' }}
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-blue-600 font-medium">
                                                ${{ number_format($product->price, 2) }}
                                            </span>
                                            @if($product->stock > 0)
                                                <span class="text-green-600 text-sm">In Stock</span>
                                            @else
                                                <span class="text-red-600 text-sm">Out of Stock</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                        @endforeach
                    </div>

                    <!-- Error Message -->
                    @error('product_ids')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <!-- Submit Button with Selected Count -->
                    <div class="mt-8 flex justify-end">
                        <button type="submit"
                                class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700
                                       transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-star"></i>
                            Add to Recommendations (<span id="selected-count">0</span> selected)
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function filterProducts(searchTerm) {
    searchTerm = searchTerm.toLowerCase();
    const cards = document.querySelectorAll('.product-card');

    cards.forEach(card => {
        const name = card.dataset.name;
        const category = card.dataset.category;
        const categoryFilter = document.getElementById('category-filter').value.toLowerCase();

        const matchesSearch = name.includes(searchTerm);
        const matchesCategory = !categoryFilter || category === categoryFilter;

        card.style.display = matchesSearch && matchesCategory ? 'block' : 'none';
    });
}

function filterByCategory(category) {
    const searchTerm = document.getElementById('search').value.toLowerCase();
    filterProducts(searchTerm);
}

// Add this new function to update the selected count
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('input[type="checkbox"][name="product_ids[]"]');
    const selectedCountSpan = document.getElementById('selected-count');

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const selectedCount = document.querySelectorAll('input[type="checkbox"][name="product_ids[]"]:checked').length;
            selectedCountSpan.textContent = selectedCount;
        });
    });
});
</script>
@endpush
@endsection
