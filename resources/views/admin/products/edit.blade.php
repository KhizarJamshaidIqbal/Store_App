@extends('admin.layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-3xl font-bold leading-tight text-gray-900">
                    Edit Product
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Update information for {{ $product->name }}
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('admin.products.index') }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                    </svg>
                    Back to Products
                </a>
            </div>
        </div>

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="rounded-md bg-green-50 p-4 mb-6 border border-green-200">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-md bg-red-50 p-4 mb-6 border border-red-200">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Please fix the following errors:</h3>
                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Form -->
        <form id="product-form" method="POST" action="{{ url('/admin/products') }}/{{ $product->id }}" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <div class="bg-white shadow-sm rounded-lg divide-y divide-gray-200">
                <!-- Basic Information -->
                <div class="p-6 space-y-6">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                            <svg class="h-5 w-5 text-gray-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Basic Information
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">Basic product details and description.</p>
                    </div>

                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label for="name" class="block text-sm font-medium text-gray-700">Product Name *</label>
                            <div class="mt-1">
                                <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="slug" class="block text-sm font-medium text-gray-700">Slug *</label>
                            <div class="mt-1">
                                <input type="text" name="slug" id="slug" value="{{ old('slug', $product->slug) }}" required
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="category" class="block text-sm font-medium text-gray-700">Category <span class="text-red-500">*</span></label>
                            <div class="mt-1">
                                @php
                                    $selectedCategories = [];
                                    if ($product->category_id && $product->category) {
                                        $selectedCategories[] = [
                                            'id' => $product->category_id,
                                            'name' => $product->category->name
                                        ];
                                    }
                                    if (old('category_id')) {
                                        $oldCategory = $categories->firstWhere('id', old('category_id'));
                                        if ($oldCategory) {
                                            $selectedCategories = [[
                                                'id' => $oldCategory->id,
                                                'name' => $oldCategory->name
                                            ]];
                                        }
                                    }
                                @endphp
                                <x-admin.category-selector 
                                    :categories="$categories"
                                    :selected-categories="$selectedCategories"
                                />
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
                            <div class="mt-1">
                                <select id="status" name="status" required
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="draft" {{ old('status', $product->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                </select>
                            </div>
                        </div>

                        <div class="sm:col-span-6">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description <span class="text-red-500">*</span></label>
                            <div class="mt-1">
                                <textarea id="description" name="description" class="editor">{{ old('description', $product->description) }}</textarea>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">Brief description of the product.</p>
                        </div>

                        <div class="sm:col-span-6">
                            <label for="highlights" class="block text-sm font-medium text-gray-700">Highlights <span class="text-red-500">*</span></label>
                            <div class="mt-1">
                                <textarea id="highlights" name="highlights" class="editor">{{ old('highlights', $product->highlights) }}</textarea>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">Key features and highlights of the product.</p>
                        </div>
                    </div>
                </div>

                <!-- Product Details -->
                <div class="p-6 space-y-6">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                            <svg class="h-5 w-5 text-gray-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Product Details
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">Specific details about the product's characteristics.</p>
                    </div>

                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label for="sku" class="block text-sm font-medium text-gray-700">SKU *</label>
                            <div class="mt-1">
                                <input type="text" name="sku" id="sku" value="{{ old('sku', $product->sku) }}" required
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="shop_sku" class="block text-sm font-medium text-gray-700">Shop SKU</label>
                            <div class="mt-1">
                                <input type="text" name="shop_sku" id="shop_sku" value="{{ old('shop_sku', $product->shop_sku) }}"
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="brand" class="block text-sm font-medium text-gray-700">Brand</label>
                            <div class="mt-1">
                                <input type="text" name="brand" id="brand" value="{{ old('brand', $product->brand) }}"
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="model" class="block text-sm font-medium text-gray-700">Model</label>
                            <div class="mt-1">
                                <input type="text" name="model" id="model" value="{{ old('model', $product->model) }}"
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="texture" class="block text-sm font-medium text-gray-700">Texture</label>
                            <div class="mt-1">
                                <input type="text" name="texture" id="texture" value="{{ old('texture', $product->texture) }}"
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="color_family" class="block text-sm font-medium text-gray-700">Color Family</label>
                            <div class="mt-1">
                                <input type="text" name="color_family" id="color_family" value="{{ old('color_family', $product->color_family) }}"
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="country_of_origin" class="block text-sm font-medium text-gray-700">Country of Origin</label>
                            <div class="mt-1">
                                <input type="text" name="country_of_origin" id="country_of_origin" value="{{ old('country_of_origin', $product->country_of_origin) }}"
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="pack_type" class="block text-sm font-medium text-gray-700">Pack Type</label>
                            <div class="mt-1">
                                <input type="text" name="pack_type" id="pack_type" value="{{ old('pack_type', $product->pack_type) }}"
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="volume" class="block text-sm font-medium text-gray-700">Volume</label>
                            <div class="mt-1">
                                <input type="text" name="volume" id="volume" value="{{ old('volume', $product->volume) }}"
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="weight" class="block text-sm font-medium text-gray-700">Weight (kg)</label>
                            <div class="mt-1">
                                <input type="number" step="0.01" name="weight" id="weight" value="{{ old('weight', $product->weight) }}"
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="material" class="block text-sm font-medium text-gray-700">Material</label>
                            <div class="mt-1">
                                <input type="text" name="material" id="material" value="{{ old('material', $product->material) }}"
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="sm:col-span-6">
                            <label for="features" class="block text-sm font-medium text-gray-700">Features</label>
                            <div class="mt-1">
                                <textarea id="features" name="features" rows="3"
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">{{ old('features', $product->features) }}</textarea>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">List the key features of the product.</p>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="brand_classification" class="block text-sm font-medium text-gray-700">Brand Classification</label>
                            <div class="mt-1">
                                <input type="text" name="brand_classification" id="brand_classification" value="{{ old('brand_classification', $product->brand_classification) }}"
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="shelf_life" class="block text-sm font-medium text-gray-700">Shelf Life</label>
                            <div class="mt-1">
                                <input type="text" name="shelf_life" id="shelf_life" value="{{ old('shelf_life', $product->shelf_life) }}"
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="sm:col-span-6">
                            <label for="express_delivery_countries" class="block text-sm font-medium text-gray-700">Express Delivery Countries</label>
                            <div class="mt-1">
                                <select multiple id="express_delivery_countries" name="express_delivery_countries[]"
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    @foreach(['United States', 'United Kingdom', 'Canada', 'Australia'] as $country)
                                        <option value="{{ $country }}" {{ in_array($country, old('express_delivery_countries', json_decode($product->express_delivery_countries, true) ?? [])) ? 'selected' : '' }}>{{ $country }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">Hold Ctrl/Cmd to select multiple countries</p>
                        </div>

                        <div class="sm:col-span-6">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="dangerous_goods" name="dangerous_goods" type="checkbox" value="1"
                                        {{ old('dangerous_goods', $product->dangerous_goods) ? 'checked' : '' }}
                                        class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="dangerous_goods" class="font-medium text-gray-700">Dangerous Goods</label>
                                    <p class="text-gray-500">Check this if the product contains hazardous materials</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Pricing & Stock -->
                <div class="p-6 space-y-6">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                            <svg class="h-5 w-5 text-gray-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Pricing & Stock
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">Manage product pricing and inventory information.</p>
                    </div>

                    <div class="grid grid-cols-1 gap-y-6 gap-x-6 sm:grid-cols-3">
                        <!-- Regular Price -->
                        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                            <label for="price" class="block text-sm font-medium text-gray-700">Price *</label>
                            <div class="mt-2 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" step="0.01" name="price" id="price"
                                    value="{{ old('price', $product->price) }}" required
                                    class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"
                                    placeholder="0.00">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">USD</span>
                                </div>
                            </div>
                        </div>

                        <!-- Special Price -->
                        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                            <label for="special_price" class="block text-sm font-medium text-gray-700">Special Price</label>
                            <div class="mt-2 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" step="0.01" name="special_price" id="special_price"
                                    value="{{ old('special_price', $product->special_price) }}"
                                    class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"
                                    placeholder="0.00">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">USD</span>
                                </div>
                            </div>
                        </div>

                        <!-- Stock -->
                        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                            <label for="stock" class="block text-sm font-medium text-gray-700">Stock *</label>
                            <div class="mt-2 relative rounded-md shadow-sm">
                                <input type="number" name="stock" id="stock"
                                    value="{{ old('stock', $product->stock) }}" required
                                    min="0" step="1"
                                    class="focus:ring-blue-500 focus:border-blue-500 block w-full pr-10 sm:text-sm border-gray-300 rounded-md"
                                    placeholder="0">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">units</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Images -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                                    <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Product Images</h3>
                                    <p class="text-sm text-gray-500">Upload and manage product images. First image will be used as the primary image.</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ count($product->images) }} Images
                            </span>
                        </div>
                    </div>

                    <div class="p-6 space-y-6">


                        <!-- Upload Section -->
                        <div class="mt-6">
                            <form id="uploadForm" class="w-full" enctype="multipart/form-data">
                                <div class="w-full mx-auto flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors duration-200 cursor-pointer bg-gray-50 group-hover:bg-gray-100"
                                     ondrop="handleDrop(event)"
                                     ondragover="handleDragOver(event)"
                                     ondragleave="handleDragLeave(event)">
                                    <div class="space-y-2 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-blue-500 transition-colors duration-200" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600 group-hover:text-blue-500 transition-colors duration-200">
                                            <label for="images" class="relative cursor-pointer rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                                <span>Upload images</span>
                                                <input id="images" name="images[]" type="file" class="sr-only" multiple accept="image/*" onchange="handleFileSelect(event)">
                                            </label>
                                            <p class="pl-1">or drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                                    </div>
                                </div>

                                <!-- Upload Progress -->
                                <div id="uploadProgress" class="hidden mt-4">
                                    <div class="relative pt-1">
                                        <div class="flex mb-2 items-center justify-between">
                                            <div>
                                                <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-blue-600 bg-blue-200">
                                                    Uploading
                                                </span>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-xs font-semibold inline-block text-blue-600">
                                                    <span id="uploadPercentage">0</span>%
                                                </span>
                                            </div>
                                        </div>
                                        <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-blue-200">
                                            <div id="uploadProgressBar" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-500 transition-all duration-300" style="width: 0%"></div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
<!-- Image Gallery -->
<div id="image-gallery" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    @if($product->images && count($product->images) > 0)
        @foreach($product->images as $index => $image)
            <div class="relative group cursor-move rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-all duration-200" data-id="{{ $image->id }}">
                <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-t-lg bg-gray-200">
                    <img src="{{ Storage::url($image->image_path) }}"
                        alt="Product image {{ $index + 1 }}"
                        class="object-cover object-center w-full h-full transform group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute inset-0 bg-gradient-to-b from-transparent to-black opacity-0 group-hover:opacity-60 transition-opacity duration-300"></div>
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                        <div class="flex items-center space-x-2">
                            <button type="button" onclick="setPrimaryImage('{{ $image->id }}')"
                                class="inline-flex items-center p-2 rounded-full bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform hover:scale-110 transition-all duration-200"
                                title="Set as primary image">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                </svg>
                            </button>
                            <button type="button" onclick="deleteImage({{ $index }}, '{{ $image->id }}')"
                                class="inline-flex items-center p-2 rounded-full bg-red-600 text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transform hover:scale-110 transition-all duration-200">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="p-3 bg-white rounded-b-lg">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Image {{ $index + 1 }}</span>
                        @if($image->is_primary)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Primary
                            </span>
                        @endif
                    </div>
                </div>
                <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                    <div class="flex items-center px-2 py-1 bg-gray-900 rounded-full shadow-lg">
                        <svg class="h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <span class="text-white text-xs ml-1">Drag to reorder</span>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
                        @push('scripts')
                        <script>
                            // Drag and drop handlers
                            function handleDragOver(e) {
                                e.preventDefault();
                                e.stopPropagation();
                                e.currentTarget.classList.add('border-blue-500', 'bg-blue-50');
                            }

                            function handleDragLeave(e) {
                                e.preventDefault();
                                e.stopPropagation();
                                e.currentTarget.classList.remove('border-blue-500', 'bg-blue-50');
                            }

                            function handleDrop(e) {
                                e.preventDefault();
                                e.stopPropagation();
                                e.currentTarget.classList.remove('border-blue-500', 'bg-blue-50');

                                const dt = e.dataTransfer;
                                const files = dt.files;

                                handleFiles(files);
                            }

                            function handleFileSelect(e) {
                                const files = e.target.files;
                                handleFiles(files);
                            }

                            function handleFiles(files) {
                                const formData = new FormData();
                                let totalSize = 0;
                                const maxSize = 10 * 1024 * 1024; // 10MB

                                // Validate files
                                for (const file of files) {
                                    if (!file.type.match('image.*')) {
                                        showNotification('Please upload only image files', 'error');
                                        return;
                                    }
                                    if (file.size > maxSize) {
                                        showNotification(`File ${file.name} is too large. Maximum size is 10MB`, 'error');
                                        return;
                                    }
                                    totalSize += file.size;
                                    formData.append('images[]', file);
                                }

                                if (totalSize > maxSize) {
                                    showNotification('Total file size exceeds 10MB limit', 'error');
                                    return;
                                }

                                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

                                // Show progress bar
                                const progressBar = document.getElementById('uploadProgress');
                                const progressBarFill = document.getElementById('uploadProgressBar');
                                const progressPercentage = document.getElementById('uploadPercentage');
                                progressBar.classList.remove('hidden');

                                // Upload files
                                const xhr = new XMLHttpRequest();
                                xhr.open('POST', '/admin/products/{{ $product->id }}/images', true);

                                xhr.upload.onprogress = function(e) {
                                    if (e.lengthComputable) {
                                        const percentComplete = (e.loaded / e.total) * 100;
                                        progressBarFill.style.width = percentComplete + '%';
                                        progressPercentage.textContent = Math.round(percentComplete);
                                    }
                                };

                                xhr.onload = function() {
                                    if (xhr.status === 200) {
                                        const response = JSON.parse(xhr.responseText);
                                        if (response.success) {
                                            showNotification(response.message, 'success');
                                            // Refresh the page to show uploaded images
                                            setTimeout(() => window.location.reload(), 1000);
                                        } else {
                                            throw new Error(response.message || 'Upload failed');
                                        }
                                    } else {
                                        showNotification('Upload failed', 'error');
                                    }
                                    progressBar.classList.add('hidden');
                                };

                                xhr.onerror = function() {
                                    showNotification('Upload failed', 'error');
                                    progressBar.classList.add('hidden');
                                };

                                xhr.send(formData);
                            }
                        </script>
                        @endpush
                    </div>
                </div>

                <!-- Product Variants -->
                <div class="p-6 space-y-6">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                            <svg class="h-5 w-5 text-gray-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2H6a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            Product Variants
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">Add different variations of the product (e.g., sizes, colors).</p>
                    </div>

                    <div id="variants-container">
                        @if(isset($product->variants))
                            @foreach($product->variants as $index => $variant)
                                <div class="variant-row border rounded-lg p-4 mb-4 bg-white" data-variant-id="{{ $index }}">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Variant Name</label>
                                            <input type="text" name="variants[{{ $index }}][name]" value="{{ $variant->name }}"
                                                class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Value</label>
                                            <input type="text" name="variants[{{ $index }}][value]" value="{{ $variant->value }}"
                                                class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Status</label>
                                            <select name="variants[{{ $index }}][status]"
                                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                <option value="active" {{ $variant->status == 'active' ? 'selected' : '' }}>Active</option>
                                                <option value="inactive" {{ $variant->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Price ($)</label>
                                            <div class="mt-1 relative rounded-md shadow-sm">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 sm:text-sm">$</span>
                                                </div>
                                                <input type="number" step="0.01" name="variants[{{ $index }}][price]" value="{{ $variant->price }}"
                                                    class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" required>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Special Price ($)</label>
                                            <div class="mt-1 relative rounded-md shadow-sm">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 sm:text-sm">$</span>
                                                </div>
                                                <input type="number" step="0.01" name="variants[{{ $index }}][special_price]" value="{{ $variant->special_price }}"
                                                    class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Stock</label>
                                            <input type="number" name="variants[{{ $index }}][stock]" value="{{ $variant->stock }}"
                                                class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                        </div>
                                    </div>
                                    <div class="mt-4 text-right">
                                        <button type="button" onclick="removeVariant({{ $index }})"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Remove Variant
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <div class="mt-4">
                        <button type="button" onclick="addVariant()"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6a2 2 0 002-2V6a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 002 2z" />
                            </svg>
                            Add Variant
                        </button>
                    </div>
                </div>

                @push('scripts')
                <script>
                    // Handle variant form
                    let variantCounter = {{ count($product->variants ?? []) }};

                    // Save product with variants
                    document.getElementById('product-form').addEventListener('submit', async function(e) {
                        e.preventDefault();
                        
                        const form = this;
                        const submitButton = form.querySelector('[type="submit"]');
                        const originalText = submitButton.innerHTML;
                        submitButton.disabled = true;
                        submitButton.innerHTML = 'Updating...';

                        try {
                            // Get the product ID from the URL
                            const productId = {{ $product->id }};
                            const formData = new FormData(form);
                            const jsonData = {};
                            
                            // Convert FormData to object
                            for (let [key, value] of formData.entries()) {
                                if (key === '_token' || key === '_method') continue;
                                
                                if (key.includes('variants[')) {
                                    const matches = key.match(/variants\[(\d+)\]\[([^\]]+)\]/);
                                    if (matches) {
                                        const [_, index, field] = matches;
                                        if (!jsonData.variants) jsonData.variants = [];
                                        if (!jsonData.variants[index]) jsonData.variants[index] = {};
                                        jsonData.variants[index][field] = value;
                                    }
                                } else {
                                    jsonData[key] = value;
                                }
                            }

                            // Clean up variants array
                            if (jsonData.variants) {
                                jsonData.variants = jsonData.variants.filter(Boolean);
                            }

                            // Make the request to the correct URL
                            const url = `{{ url('/admin/products') }}/${productId}`;
                            const response = await fetch(url, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    ...jsonData,
                                    _token: formData.get('_token'),
                                    _method: 'PUT'
                                })
                            });

                            // Handle the response
                            if (!response.ok) {
                                const text = await response.text();
                                console.error('Server response:', text);
                                throw new Error('Server error: ' + response.status);
                            }

                            const result = await response.json();

                            if (result.success) {
                                showNotification(result.message || 'Product updated successfully', 'success');
                                
                                // Update category display if available
                                if (result.data?.category) {
                                    const categorySelector = document.querySelector('[x-data]');
                                    if (categorySelector) {
                                        const alpineData = Alpine.$data(categorySelector);
                                        if (alpineData) {
                                            alpineData.selectedCategory = {
                                                id: result.data.category.id,
                                                name: result.data.category.name
                                            };
                                            // Update the hidden input if it exists
                                            const hiddenInput = categorySelector.querySelector('[x-ref="hiddenInput"]');
                                            if (hiddenInput) {
                                                hiddenInput.value = result.data.category.id;
                                            } else {
                                                console.warn('Hidden input not found');
                                            }
                                        }
                                    }
                                }
                            } else {
                                throw new Error(result.message || 'Failed to update product');
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            showNotification(error.message || 'Failed to update product', 'error');
                        } finally {
                            submitButton.disabled = false;
                            submitButton.innerHTML = originalText;
                        }
                    });

                    function showNotification(message, type = 'success') {
                        const notification = document.createElement('div');
                        notification.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg text-white ${
                            type === 'success' ? 'bg-green-500' : 'bg-red-500'
                        }`;
                        notification.textContent = message;
                        document.body.appendChild(notification);
                        setTimeout(() => notification.remove(), 3000);
                    }
                </script>
                @endpush

                <!-- Package Information -->
                <div class="p-6 space-y-6">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                            <svg class="h-5 w-5 text-gray-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Package Information
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">Enter the physical dimensions and weight of the product package.</p>
                    </div>

                    <div class="grid grid-cols-1 gap-y-6 gap-x-6 sm:grid-cols-2 lg:grid-cols-4">
                        <!-- Package Weight -->
                        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                            <label for="package_weight" class="block text-sm font-medium text-gray-700">Package Weight</label>
                            <div class="mt-2 relative rounded-md shadow-sm">
                                <input type="number" step="0.01" name="package_weight" id="package_weight"
                                    value="{{ old('package_weight', $product->package_weight) }}"
                                    class="focus:ring-blue-500 focus:border-blue-500 block w-full pr-12 sm:text-sm border-gray-300 rounded-md"
                                    placeholder="0.00">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">kg</span>
                                </div>
                            </div>
                        </div>

                        <!-- Package Length -->
                        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                            <label for="package_length" class="block text-sm font-medium text-gray-700">Package Length</label>
                            <div class="mt-2 relative rounded-md shadow-sm">
                                <input type="number" step="0.1" name="package_length" id="package_length"
                                    value="{{ old('package_length', $product->package_length) }}"
                                    class="focus:ring-blue-500 focus:border-blue-500 block w-full pr-12 sm:text-sm border-gray-300 rounded-md"
                                    placeholder="0.0">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">cm</span>
                                </div>
                            </div>
                        </div>

                        <!-- Package Width -->
                        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                            <label for="package_width" class="block text-sm font-medium text-gray-700">Package Width</label>
                            <div class="mt-2 relative rounded-md shadow-sm">
                                <input type="number" step="0.1" name="package_width" id="package_width"
                                    value="{{ old('package_width', $product->package_width) }}"
                                    class="focus:ring-blue-500 focus:border-blue-500 block w-full pr-12 sm:text-sm border-gray-300 rounded-md"
                                    placeholder="0.0">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">cm</span>
                                </div>
                            </div>
                        </div>

                        <!-- Package Height -->
                        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                            <label for="package_height" class="block text-sm font-medium text-gray-700">Package Height</label>
                            <div class="mt-2 relative rounded-md shadow-sm">
                                <input type="number" step="0.1" name="package_height" id="package_height"
                                    value="{{ old('package_height', $product->package_height) }}"
                                    class="focus:ring-blue-500 focus:border-blue-500 block w-full pr-12 sm:text-sm border-gray-300 rounded-md"
                                    placeholder="0.0">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">cm</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="fixed bottom-0 left-0 right-0 bg-gray-50 px-6 py-4 flex justify-end space-x-4 border-t border-gray-200">
                    <button type="button" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cancel
                    </button>
                    <button type="button" class="px-4 py-2 bg-gray-200 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Save as Draft
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Update Product
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<style>
    .ts-wrapper {
        min-height: 38px;
    }
    .ts-control {
        border-radius: 0.375rem;
        border-color: #D1D5DB;
        min-height: 38px;
        padding: 0.375rem 0.75rem;
    }
    .ts-dropdown {
        border-radius: 0.375rem;
        border-color: #D1D5DB;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    .ts-dropdown .active {
        background-color: #2563EB;
        color: white;
    }
    .ts-dropdown .option {
        padding: 0.5rem 0.75rem;
    }
    .ts-control > input {
        min-height: 34px;
    }
    .ts-wrapper.multi .ts-control > div {
        background: #EFF6FF;
        color: #2563EB;
        border: 1px solid #BFDBFE;
        border-radius: 0.25rem;
        padding: 0.125rem 0.5rem;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new TomSelect('#category', {
            plugins: ['remove_button', 'clear_button'],
            maxItems: null,
            valueField: 'value',
            labelField: 'text',
            searchField: ['text'],
            create: false,
            render: {
                option: function(data, escape) {
                    return '<div class="py-2 px-3">' + escape(data.text) + '</div>';
                },
                item: function(data, escape) {
                    return '<div>' + escape(data.text) + '</div>';
                }
            }
        });
    });
</script>
@endpush

@push('scripts')
<script>
    // Function to generate slug from text
    function generateSlug(text) {
        return text
            .toString()
            .toLowerCase()
            .trim()
            .replace(/\s+/g, '-')           // Replace spaces with -
            .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
            .replace(/\-\-+/g, '-')         // Replace multiple - with single -
            .replace(/^-+/, '')             // Trim - from start of text
            .replace(/-+$/, '');            // Trim - from end of text
    }

    // Auto-generate slug from product name
    document.getElementById('name').addEventListener('input', function(e) {
        const slugInput = document.getElementById('slug');
        if (slugInput) {
            slugInput.value = generateSlug(e.target.value);
        }
    });

    // Handle form submission
    document.getElementById('product-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const form = this;
        const submitButton = form.querySelector('[type="submit"]');
        const originalText = submitButton.innerHTML;
        submitButton.disabled = true;
        submitButton.innerHTML = 'Updating...';

        try {
            const formData = new FormData(form);
            const jsonData = {};
            
            // Handle multiple select fields
            const expressDeliverySelect = document.getElementById('express_delivery_countries');
            const selectedCountries = Array.from(expressDeliverySelect.selectedOptions).map(option => option.value);
            
            // Convert FormData to object
            for (let [key, value] of formData.entries()) {
                if (key === '_token' || key === '_method') continue;
                
                if (key.includes('variants[')) {
                    const matches = key.match(/variants\[(\d+)\]\[([^\]]+)\]/);
                    if (matches) {
                        const [_, index, field] = matches;
                        if (!jsonData.variants) jsonData.variants = [];
                        if (!jsonData.variants[index]) jsonData.variants[index] = {};
                        jsonData.variants[index][field] = value;
                    }
                } else if (key === 'express_delivery_countries[]') {
                    // Skip as we'll handle it separately
                    continue;
                } else {
                    jsonData[key] = value;
                }
            }

            // Add express delivery countries
            jsonData.express_delivery_countries = selectedCountries;

            // Clean up variants array
            if (jsonData.variants) {
                jsonData.variants = jsonData.variants.filter(Boolean);
            }

            // Construct the correct URL
            const productId = {{ $product->id }};
            const url = `{{ url('/admin/products') }}/${productId}`;

            console.log('Submitting data:', jsonData); // Debug log

            // Make the request
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    ...jsonData,
                    _method: 'PUT'
                })
            });

            // Handle the response
            if (!response.ok) {
                const text = await response.text();
                console.error('Server response:', text);
                throw new Error('Server error: ' + response.status);
            }

            const result = await response.json();

            if (result.success) {
                showNotification(result.message || 'Product updated successfully', 'success');
                
                // Update category display if available
                if (result.data?.category) {
                    const categorySelector = document.querySelector('[x-data]');
                    if (categorySelector) {
                        const alpineData = Alpine.$data(categorySelector);
                        if (alpineData) {
                            alpineData.selectedCategory = {
                                id: result.data.category.id,
                                name: result.data.category.name
                            };
                        }
                    }
                }
            } else {
                throw new Error(result.message || 'Failed to update product');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification(error.message || 'Failed to update product', 'error');
        } finally {
            submitButton.disabled = false;
            submitButton.innerHTML = originalText;
        }
    });

    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg text-white ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        }`;
        notification.textContent = message;
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }
</script>
@endpush

@push('scripts')
<script>
    // Initialize Sortable for image reordering
    const imageGallery = document.getElementById('image-gallery');
    if (imageGallery) {
        new Sortable(imageGallery, {
            animation: 150,
            ghostClass: 'bg-blue-100',
            onEnd: function(evt) {
                const imageIds = Array.from(imageGallery.children).map((el, index) => ({
                    id: parseInt(el.dataset.id),
                    order: index
                }));

                // Send the new order to the server
                fetch('{{ route("admin.products.images.reorder") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ order: imageIds })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => Promise.reject(err));
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showNotification('Image order updated successfully', 'success');
                    } else {
                        throw new Error(data.message || 'Failed to update image order');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification(error.message || 'Failed to update image order', 'error');
                });
            }
        });
    }

    // Function to set primary image
    function setPrimaryImage(imageId) {
        fetch(`/admin/products/images/${imageId}/set-primary`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Primary image updated successfully', 'success');
                // Refresh the page to show updated primary image
                window.location.reload();
            } else {
                throw new Error(data.message || 'Failed to set primary image');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification(error.message || 'Failed to set primary image', 'error');
        });
    }

    // Function to delete image
    function deleteImage(index, imageId) {
        if (confirm('Are you sure you want to delete this image?')) {
            fetch(`/admin/products/images/${imageId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the image element from the DOM
                    const imageElement = document.querySelector(`[data-id="${imageId}"]`);
                    if (imageElement) {
                        imageElement.remove();
                    }
                    showNotification('Image deleted successfully', 'success');
                } else {
                    throw new Error(data.message || 'Failed to delete image');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification(error.message || 'Failed to delete image', 'error');
            });
        }
    }
</script>
@endpush

@push('scripts')
<script>
    // Initialize TinyMCE for both Description and Highlights fields
    document.addEventListener('DOMContentLoaded', function() {
        tinymce.init({
            selector: '.editor',
            height: 300,
            menubar: false,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'charmap',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'table', 'wordcount'
            ],
            toolbar: 'fontfamily fontsize | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent',
            toolbar_mode: 'wrap',
            font_family_formats: 'Arial=arial,helvetica,sans-serif; Times New Roman=times new roman,times,serif',
            font_size_formats: '8pt 10pt 12pt 14pt 16pt 18pt 24pt 36pt',
            content_style: `
                body {
                    font-family: Arial, sans-serif;
                    font-size: 14px;
                    line-height: 1.6;
                    color: #333;
                    padding: 0.5rem;
                }
            `,
            branding: false,
            statusbar: false,
            resize: false,
            setup: function(editor) {
                editor.on('change', function() {
                    editor.save();
                });
            }
        });
    });
</script>
@endpush

@endsection
