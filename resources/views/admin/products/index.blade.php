@extends('admin.layouts.app')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

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
                    <i class="fas fa-times-circle text-gray-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="bg-white rounded-xl shadow-sm mb-6">
        <div class="border-b border-gray-200">
            <div class="flex justify-between items-center px-6">
                <nav class="flex -mb-px space-x-8" aria-label="Product tabs">
                    <a href="{{ route('admin.products.index', ['tab' => 'all']) }}"
                       class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $currentTab === 'all' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        All Products
                        <span class="ml-2 py-0.5 px-2.5 text-xs font-medium text-blue-600 bg-blue-100 rounded-full">{{ $statistics['total_products'] }}</span>
                    </a>

                    <a href="{{ route('admin.products.index', ['tab' => 'active']) }}"
                       class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $currentTab === 'active' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Active Products
                        <span class="ml-2 py-0.5 px-2.5 text-xs font-medium text-green-600 bg-green-100 rounded-full">{{ $activeProducts }}</span>
                    </a>

                    <a href="{{ route('admin.products.index', ['tab' => 'inactive']) }}"
                       class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $currentTab === 'inactive' ? 'border-gray-500 text-gray-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Inactive Products
                        <span class="ml-2 py-0.5 px-2.5 text-xs font-medium text-gray-600 bg-gray-200 rounded-full">{{ $inactiveProducts }}</span>
                    </a>

                    <a href="{{ route('admin.products.index', ['tab' => 'draft']) }}"
                       class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $currentTab === 'draft' ? 'border-yellow-500 text-yellow-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Draft Products
                        <span class="ml-2 py-0.5 px-2.5 text-xs font-medium text-yellow-600 bg-yellow-100 rounded-full">{{ $draftProducts }}</span>
                    </a>

                    <a href="{{ route('admin.products.index', ['tab' => 'trashed']) }}"
                       class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $currentTab === 'trashed' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Deleted Products
                        <span class="ml-2 py-0.5 px-2.5 text-xs font-medium text-red-600 bg-red-100 rounded-full">{{ $trashedProducts }}</span>
                    </a>
                </nav>
                <a href="{{ route('admin.products.create') }}"
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 hover:animate-bounce">
                    <i class="fas fa-plus mr-2"></i>
                    Create New Product
                </a>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <form action="{{ route('admin.products.index') }}" method="GET" class="space-y-4">
            <input type="hidden" name="tab" value="{{ $currentTab }}">

            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <!-- Search Input -->
                <div class="md:col-span-5">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Search by name, SKU, or description..."
                               class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent sm:text-sm">
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="md:col-span-4">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1H8a3 3 0 00-3 3v1.5a1.5 1.5 0 01-3 0V6z" clip-rule="evenodd" />
                                <path d="M6 12a2 2 0 012-2h8a2 2 0 012 2v2a2 2 0 01-2 2H8a2 2 0 01-2-2v-2z" />
                            </svg>
                        </div>
                        <select name="category"
                                class="block w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent sm:text-sm appearance-none bg-none">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @foreach($category->childrenRecursive as $child)
                                    <option value="{{ $child->id }}" {{ request('category') == $child->id ? 'selected' : '' }}>
                                        &nbsp;&nbsp;└─ {{ $child->name }}
                                    </option>
                                    @foreach($child->childrenRecursive as $grandchild)
                                        <option value="{{ $grandchild->id }}" {{ request('category') == $grandchild->id ? 'selected' : '' }}>
                                            &nbsp;&nbsp;&nbsp;&nbsp;└─ {{ $grandchild->name }}
                                        </option>
                                    @endforeach
                                @endforeach
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Sort By -->
                <div class="md:col-span-3">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3z" />
                            </svg>
                        </div>
                        <select name="sort"
                                class="block w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent sm:text-sm appearance-none">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name (Z-A)</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price (Low to High)</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price (High to Low)</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3">
                <button type="button"
                        onclick="window.location.href='{{ route('admin.products.index', ['tab' => $currentTab]) }}'"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150">
                    <svg class="h-4 w-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Clear Filters
                </button>
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150">
                    <svg class="h-4 w-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($products as $product)
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden"
                 ondblclick="window.location.href='{{ route('admin.products.edit', $product) }}'"
                 style="cursor: pointer">
                <!-- Product Image -->
                <div class="aspect-w-16 aspect-h-9 bg-gray-100">
                    @if($product->images->isNotEmpty())
                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                         alt="{{ $product->name }}"
                         class="object-cover w-full h-full rounded"
                         loading="lazy"
                         onerror="this.src='{{ asset('images/placeholder.png') }}'">
                @else
                    <div class="flex items-center justify-center h-full bg-gray-100 rounded">
                        <i class="fas fa-image text-gray-400 text-4xl"></i>
                    </div>
                @endif
                </div>

                <!-- Product Details -->
                <div class="p-4">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">{{ $product->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $product->category->name }}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            @if($product->is_draft)
                                <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Draft</span>
                            @else
                                <span class="px-2 py-1 text-xs font-medium {{ $product->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} rounded-full">
                                    {{ ucfirst($product->status) }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">
                                @if($product->special_price)
                                    <span class="line-through text-gray-500">${{ number_format($product->price, 2) }}</span>
                                    <span class="ml-1 text-red-600">${{ number_format($product->special_price, 2) }}</span>
                                @else
                                    ${{ number_format($product->price, 2) }}
                                @endif
                            </p>
                            <p class="text-sm text-gray-500">Stock: {{ $product->stock }}</p>
                        </div>
                        <div class="flex space-x-2">
                            @if($product->trashed())
                                <form action="{{ route('admin.products.restore', $product->id) }}"
                                    method="POST"
                                    class="inline-block">
                                  @csrf
                                  <button type="submit"
                                          class="inline-flex items-center px-3 py-1 border border-transparent shadow-sm text-sm font-medium rounded text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                          title="Restore Product">
                                      <i class="fas fa-trash-restore"></i>
                                  </button>
                              </form>
                                <form action="{{ route('admin.products.force-delete', $product->id) }}"
                                      method="POST"
                                      class="inline-block"
                                      onsubmit="return confirm('Are you sure you want to permanently delete this product? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center px-3 py-1 border border-transparent shadow-sm text-sm font-medium rounded text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                            title="Permanently Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('admin.products.edit', $product) }}"
                                   class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}"
                                      method="POST"
                                      class="inline-block"
                                      onsubmit="return confirm('Are you sure you want to delete this product?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center px-3 py-1 border border-transparent shadow-sm text-sm font-medium rounded text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-4 flex items-center justify-between px-4 py-3 bg-white rounded-xl shadow-sm">
        <div class="flex items-center gap-4">
            <p class="text-sm text-gray-600">
                <span class="bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full">{{ $products->total() }}</span>
                Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }}
            </p>

            <!-- Number of rows dropdown -->
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-600">Number of rows:</span>
                <select name="per_page"
                        class="border border-gray-300 text-sm rounded-md pl-2 pr-8 py-1 bg-white focus:outline-none focus:ring-1 focus:ring-blue-500"
                        onchange="window.location.href = '{{ url()->current() }}?per_page=' + this.value + '&tab={{ request()->get('tab', 'all') }}'">
                    @foreach ([12, 24, 50, 100] as $value)
                        <option value="{{ $value }}" {{ request('per_page', 12) == $value ? 'selected' : '' }}>
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex gap-1">
            <a href="{{ $products->previousPageUrl() }}"
               class="{{ !$products->onFirstPage() ? 'hover:bg-gray-50' : 'opacity-50 cursor-not-allowed' }} p-2 inline-flex items-center text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-100">
                ←
            </a>

            @foreach ($products->getUrlRange(max($products->currentPage() - 2, 1), min($products->currentPage() + 2, $products->lastPage())) as $page => $url)
                @if ($page == $products->currentPage())
                    <span class="px-3.5 py-2 text-sm text-white bg-blue-600 border border-blue-600 rounded-lg">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}" class="px-3.5 py-2 text-sm text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            <a href="{{ $products->nextPageUrl() }}"
               class="{{ $products->hasMorePages() ? 'hover:bg-gray-50' : 'opacity-50 cursor-not-allowed' }} p-2 inline-flex items-center text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-100">
                →
            </a>
        </div>
    </div>
</div>
@endsection
