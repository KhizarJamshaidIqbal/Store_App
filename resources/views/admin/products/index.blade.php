@extends('admin.layouts.app')

@section('content')
<div class="p-8 bg-gray-50 min-h-screen">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Products -->
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Products</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($statistics['total_products']) }}</h3>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-box text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Active Products -->
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Active Products</p>
                    <h3 class="text-2xl font-bold text-green-600 mt-1">{{ number_format($statistics['active_products']) }}</h3>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Low Stock Products -->
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Low Stock Alert</p>
                    <h3 class="text-2xl font-bold text-yellow-600 mt-1">{{ number_format($statistics['low_stock_products']) }}</h3>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>

         <!-- Inactive Products -->
         <div class="bg-white rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Inactive Products</p>
                    <h3 class="text-2xl font-bold text-gray-500 mt-1">{{ number_format($statistics['inactive_products']) }}</h3>
                </div>
                <div class="bg-gray-100 p-3 rounded-full">
                    <i class="fas fa-pause-circle text-gray-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>


    <!-- Header Section -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Products</h2>
                <p class="text-gray-600">Manage your product inventory</p>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
                <a href="{{ route('admin.products.create') }}"
                   class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i>
                    Add New Product
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="mt-6">
            <form action="{{ route('admin.products.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                <div class="relative flex-grow">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Search products...">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </div>

                <select name="category"
                        class="border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 px-4 py-2">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                <button type="submit"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors duration-200">
                    Filter
                </button>
            </form>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($products as $product)
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
                <!-- Product Image -->
                <div class="aspect-w-16 aspect-h-9 bg-gray-100">
                    @if($product->images && $product->images->count() > 0)
                        @php
                            $primaryImage = $product->images->where('is_primary', true)->first() ?? $product->images->first();
                        @endphp
                        <img src="{{ asset('storage/' . $primaryImage->image_path) }}"
                             alt="{{ $product->name }}"
                             class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 flex items-center justify-center text-gray-400">
                            <i class="fas fa-image text-4xl"></i>
                        </div>
                    @endif
                </div>

                <!-- Product Info -->
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2 truncate">{{ $product->name }}</h3>

                    <div class="space-y-2 mb-4">
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-barcode w-5 mr-2"></i>
                            <span>{{ $product->sku }}</span>
                        </div>

                        @if($product->category)
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-tag w-5 mr-2"></i>
                            <span>{{ $product->category->name }}</span>
                        </div>
                        @endif

                        <div class="flex items-center text-sm">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $product->stock > 10 ? 'bg-blue-100 text-blue-800' : ($product->stock > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                <i class="fas fa-box mr-1"></i>
                                {{ $product->stock }} in stock
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 mb-4">
                        @if($product->special_price)
                            <span class="text-xl font-semibold text-red-600">${{ number_format($product->special_price, 2) }}</span>
                            <span class="text-sm text-gray-400 line-through">${{ number_format($product->price, 2) }}</span>
                        @else
                            <span class="text-xl font-semibold text-gray-800">${{ number_format($product->price, 2) }}</span>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="grid grid-cols-2 gap-2 mt-4 pt-4 border-t border-gray-100">
                        <a href="{{ route('admin.products.edit', $product) }}"
                           class="inline-flex items-center justify-center px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg transition-colors duration-200">
                            <i class="fas fa-edit mr-2"></i>
                            Edit
                        </a>
                        <form action="{{ route('admin.products.destroy', $product) }}"
                              method="POST"
                              class="inline-block"
                              onsubmit="return confirm('Are you sure you want to delete this product?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="w-full inline-flex items-center justify-center px-3 py-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition-colors duration-200">
                                <i class="fas fa-trash-alt mr-2"></i>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $products->links() }}
    </div>
</div>
@endsection
