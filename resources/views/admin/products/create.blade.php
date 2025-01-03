@extends('admin.layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-3xl font-bold leading-tight text-gray-900">
                    Create New Product
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Add a new product to your store
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
        @if ($errors->any())
            <div class="rounded-md bg-red-50 p-4 mb-6 border border-red-200">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Form -->
        <form id="product-form" method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-8">
            @csrf

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
                        <p class="mt-1 text-sm text-gray-500">Add the basic information about the product.</p>
                    </div>

                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label for="name" class="block text-sm font-medium text-gray-700">Product Name *</label>
                            <div class="mt-1">
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="slug" class="block text-sm font-medium text-gray-700">Slug *</label>
                            <div class="mt-1">
                                <input type="text" name="slug" id="slug" value="{{ old('slug') }}" required
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="sm:col-span-6">
                            <label for="category" class="block text-sm font-medium text-gray-700">Category <span class="text-red-500">*</span></label>
                            <div class="mt-1">
                                @php
                                    $selectedCategories = [];
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

                        <div class="sm:col-span-6">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <p class="mt-1 text-sm text-gray-500">Brief description of the product.</p>
                            <div class="mt-1">
                                <textarea id="description" name="description" class="editor">{{ old('description') }}</textarea>
                            </div>
                        </div>
                        <div class="sm:col-span-6">
                            <label for="highlights" class="block text-sm font-medium text-gray-700">Highlights</label>
                            <p class="mt-1 text-sm text-gray-500">Key features and highlights of the product.</p>
                            <div class="mt-1">
                                <textarea id="highlights" name="highlights" class="editor">{{ old('highlights') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Details -->
                <div class="p-6 space-y-6">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                            <svg class="h-5 w-5 text-gray-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Product Details
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">Add the product details.</p>
                    </div>

                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label for="sku" class="block text-sm font-medium text-gray-700">SKU</label>
                            <div class="mt-1">
                                <input type="text" name="sku" id="sku" value="{{ old('sku') }}"
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="shop_sku" class="block text-sm font-medium text-gray-700">Shop SKU</label>
                            <div class="mt-1">
                                <input type="text" name="shop_sku" id="shop_sku" value="{{ old('shop_sku') }}"
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="brand" class="block text-sm font-medium text-gray-700">Brand</label>
                            <div class="mt-1">
                                <input type="text" name="brand" id="brand" value="{{ old('brand') }}"
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="model" class="block text-sm font-medium text-gray-700">Model</label>
                            <div class="mt-1">
                                <input type="text" name="model" id="model" value="{{ old('model') }}"
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Specifications -->
                <div class="p-6 space-y-6">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                            <svg class="h-5 w-5 text-gray-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Product Specifications
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">Add the product specifications.</p>
                    </div>

                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label for="texture" class="block text-sm font-medium text-gray-700">Texture</label>
                            <div class="mt-1">
                                <input type="text" name="texture" id="texture" value="{{ old('texture') }}"
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="color_family" class="block text-sm font-medium text-gray-700">Color Family</label>
                            <div class="mt-1">
                                <input type="text" name="color_family" id="color_family" value="{{ old('color_family') }}"
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="material" class="block text-sm font-medium text-gray-700">Material</label>
                            <div class="mt-1">
                                <input type="text" name="material" id="material" value="{{ old('material') }}"
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="sm:col-span-6">
                            <label for="features" class="block text-sm font-medium text-gray-700">Features</label>
                            <div class="mt-1">
                                <textarea id="features" name="features" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border border-gray-300 rounded-md">{{ old('features') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pricing and Stock -->
                <div class="p-6 space-y-6">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                            <svg class="h-5 w-5 text-gray-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Pricing and Stock
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">Add the pricing and stock information.</p>
                    </div>

                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                            <div class="mt-1">
                                <input type="number" step="0.01" name="price" id="price" value="{{ old('price') }}"
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="special_price" class="block text-sm font-medium text-gray-700">Special Price</label>
                            <div class="mt-1">
                                <input type="number" step="0.01" name="special_price" id="special_price" value="{{ old('special_price') }}"
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="stock" class="block text-sm font-medium text-gray-700">Stock</label>
                            <div class="mt-1">
                                <input type="number" name="stock" id="stock" value="{{ old('stock') }}"
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping Information -->
                <div class="p-6 space-y-6">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                            <svg class="h-5 w-5 text-gray-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Shipping Information
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">Add the shipping information.</p>
                    </div>

                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label for="weight" class="block text-sm font-medium text-gray-700">Weight (kg)</label>
                            <div class="mt-1">
                                <input type="number" step="0.01" name="weight" id="weight" value="{{ old('weight') }}"
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="package_weight" class="block text-sm font-medium text-gray-700">Package Weight (kg)</label>
                            <div class="mt-1">
                                <input type="number" step="0.01" name="package_weight" id="package_weight" value="{{ old('package_weight') }}"
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="package_length" class="block text-sm font-medium text-gray-700">Length (cm)</label>
                            <div class="mt-1">
                                <input type="number" step="0.01" name="package_length" id="package_length" value="{{ old('package_length') }}"
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="package_width" class="block text-sm font-medium text-gray-700">Width (cm)</label>
                            <div class="mt-1">
                                <input type="number" step="0.01" name="package_width" id="package_width" value="{{ old('package_width') }}"
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="package_height" class="block text-sm font-medium text-gray-700">Height (cm)</label>
                            <div class="mt-1">
                                <input type="number" step="0.01" name="package_height" id="package_height" value="{{ old('package_height') }}"
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="p-6 space-y-6">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                            <svg class="h-5 w-5 text-gray-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Additional Information
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">Add the additional information.</p>
                    </div>

                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label for="country_of_origin" class="block text-sm font-medium text-gray-700">Country of Origin</label>
                            <div class="mt-1">
                                <input type="text" name="country_of_origin" id="country_of_origin" value="{{ old('country_of_origin') }}"
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="shelf_life" class="block text-sm font-medium text-gray-700">Shelf Life</label>
                            <div class="mt-1">
                                <input type="text" name="shelf_life" id="shelf_life" value="{{ old('shelf_life') }}"
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="sm:col-span-6">
                            <label for="express_delivery_countries" class="block text-sm font-medium text-gray-700">Express Delivery Countries</label>
                            <div class="mt-1">
                                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4"
                                    x-data="{
                                        search: '',
                                        selected: @json(old('express_delivery_countries', [])),
                                        options: [
                                            'United States', 'United Kingdom', 'Canada', 'Australia', 'Germany',
                                            'France', 'Italy', 'Spain', 'Netherlands', 'Belgium', 'Switzerland',
                                            'Sweden', 'Norway', 'Denmark', 'Finland', 'Japan', 'South Korea',
                                            'Singapore', 'Hong Kong', 'New Zealand'
                                        ],
                                        get filteredOptions() {
                                            return this.options.filter(
                                                i => i.toLowerCase().includes(this.search.toLowerCase()) && !this.selected.includes(i)
                                            )
                                        },
                                        addCountry(country) {
                                            this.selected.push(country);
                                            this.search = '';
                                        },
                                        removeCountry(index) {
                                            this.selected.splice(index, 1);
                                        }
                                    }">

                                    <!-- Selected Countries Tags -->
                                    <div class="mb-2 flex flex-wrap gap-2">
                                        <template x-for="(country, index) in selected" :key="index">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                <span x-text="country"></span>
                                                <button type="button" @click="removeCountry(index)" class="ml-1 inline-flex items-center justify-center flex-shrink-0 h-4 w-4 rounded-full text-blue-400 hover:bg-blue-200 hover:text-blue-500 focus:outline-none focus:bg-blue-500 focus:text-white">
                                                    <span class="sr-only">Remove country</span>
                                                    <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </span>
                                        </template>
                                    </div>

                                    <!-- Search Input -->
                                    <div class="relative">
                                        <input type="text"
                                            x-model="search"
                                            @keydown.enter.prevent="if(filteredOptions.length > 0) addCountry(filteredOptions[0])"
                                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                            placeholder="Search countries...">

                                        <!-- Dropdown -->
                                        <div x-show="search.length > 0"
                                            class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm"
                                            x-cloak>
                                            <template x-for="country in filteredOptions" :key="country">
                                                <div @click="addCountry(country)"
                                                    class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-blue-50"
                                                    :class="{'text-blue-900 bg-blue-50': filteredOptions[0] === country}">
                                                    <span x-text="country" class="block truncate"></span>
                                                </div>
                                            </template>
                                            <div x-show="filteredOptions.length === 0"
                                                class="cursor-default select-none relative py-2 pl-3 pr-9 text-gray-500">
                                                No countries found
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Hidden input for form submission -->
                                    <template x-for="country in selected" :key="country">
                                        <input type="hidden" name="express_delivery_countries[]" :value="country">
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div class="sm:col-span-6">
                            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4"
                                x-data="{ enabled: {{ old('dangerous_goods', 0) ? 'true' : 'false' }} }">
                                <div class="flex items-center justify-between">
                                    <label for="dangerous_goods" class="flex-grow block text-sm font-medium text-gray-700">
                                        Dangerous Goods
                                        <p class="mt-1 text-sm text-gray-500">Mark if this product contains dangerous materials</p>
                                    </label>
                                    <button type="button"
                                        @click="enabled = !enabled"
                                        :class="enabled ? 'bg-blue-600' : 'bg-gray-200'"
                                        class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                        role="switch"
                                        :aria-checked="enabled">
                                        <span
                                            :class="enabled ? 'translate-x-5' : 'translate-x-0'"
                                            class="pointer-events-none relative inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200">
                                            <span
                                                :class="enabled ? 'opacity-0 ease-out duration-100' : 'opacity-100 ease-in duration-200'"
                                                class="absolute inset-0 h-full w-full flex items-center justify-center transition-opacity"
                                                aria-hidden="true">
                                                <svg class="h-3 w-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 12 12">
                                                    <path d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </span>
                                            <span
                                                :class="enabled ? 'opacity-100 ease-in duration-200' : 'opacity-0 ease-out duration-100'"
                                                class="absolute inset-0 h-full w-full flex items-center justify-center transition-opacity"
                                                aria-hidden="true">
                                                <svg class="h-3 w-3 text-blue-600" fill="currentColor" viewBox="0 0 12 12">
                                                    <path d="M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414a1 1 0 001.414 1.414l-1.414 1.414a1 1 0 00-1.414 1.414z" />
                                                </svg>
                                            </span>
                                        </span>
                                    </button>
                                </div>
                                <input type="hidden" name="dangerous_goods" :value="enabled ? '1' : '0'">
                            </div>
                        </div>

                        <div class="sm:col-span-6">
                            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4"
                                x-data="{ open: false, selected: '{{ old('status', 'draft') }}' }">
                                <div class="flex items-center justify-between">
                                    <label for="status" class="flex-grow block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                                    <button type="button"
                                        @click="open = !open"
                                        class="bg-white relative w-full border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-pointer focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        aria-haspopup="listbox"
                                        :aria-expanded="open">
                                        <span x-text="selected === 'active' ? 'Active' : (selected === 'draft' ? 'Draft' : (selected === 'archived' ? 'Archived' : 'Select status'))"
                                            class="block truncate capitalize"></span>
                                        <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </button>

                                    <div x-show="open"
                                        @click.away="open = false"
                                        class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm"
                                        x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="transform opacity-100 scale-100"
                                        x-transition:leave-end="transform opacity-0 scale-95">
                                        <div @click="selected = 'active'; open = false"
                                            :class="{ 'bg-blue-50 text-blue-900': selected === 'active' }"
                                            class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-blue-50">
                                            <span class="block truncate">Active</span>
                                            <span x-show="selected === 'active'" class="absolute inset-y-0 right-0 flex items-center pr-4 text-blue-600">
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        </div>
                                        <div @click="selected = 'draft'; open = false"
                                            :class="{ 'bg-blue-50 text-blue-900': selected === 'draft' }"
                                            class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-blue-50">
                                            <span class="block truncate">Draft</span>
                                            <span x-show="selected === 'draft'" class="absolute inset-y-0 right-0 flex items-center pr-4 text-blue-600">
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        </div>
                                        <div @click="selected = 'archived'; open = false"
                                            :class="{ 'bg-blue-50 text-blue-900': selected === 'archived' }"
                                            class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-blue-50">
                                            <span class="block truncate">Archived</span>
                                            <span x-show="selected === 'archived'" class="absolute inset-y-0 right-0 flex items-center pr-4 text-blue-600">
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="status" x-model="selected">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Images -->
                <div class="p-6 space-y-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Product Images</h3>
                                <p class="text-sm text-gray-500">Upload and manage product images. First image will be used as the primary image.</p>
                            </div>
                        </div>
                        <div x-show="files.length > 0" class="text-sm font-medium text-blue-600">
                            <span x-text="files.length"></span> Images
                        </div>
                    </div>

                    <div x-data="{
                        files: [],
                        uploadError: null,
                        draggedItem: null,
                        handleFiles(event) {
                            let newFiles = Array.from(event.target.files || event.dataTransfer.files);
                            this.validateFiles(newFiles);
                        },
                        validateFiles(newFiles) {
                            this.uploadError = null;
                            const validFiles = newFiles.filter(file => {
                                if (!file.type.startsWith('image/')) {
                                    this.uploadError = 'Please upload only image files (PNG, JPG, GIF).';
                                    return false;
                                }
                                if (file.size > 10 * 1024 * 1024) {
                                    this.uploadError = 'Image size should not exceed 10MB.';
                                    return false;
                                }
                                return true;
                            });
                            if (validFiles.length) {
                                this.files = [...this.files, ...validFiles];
                                this.previewFiles();
                            }
                        },
                        previewFiles() {
                            this.files.forEach((file, index) => {
                                if (!file.preview) {
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        file.preview = e.target.result;
                                        this.files = [...this.files];
                                    };
                                    reader.readAsDataURL(file);
                                }
                            });
                        },
                        removeFile(index) {
                            this.files.splice(index, 1);
                            this.files = [...this.files];
                        },
                        handleDragStart(index, event) {
                            this.draggedItem = index;
                            event.target.classList.add('opacity-50');
                        },
                        handleDragEnd(event) {
                            event.target.classList.remove('opacity-50');
                        },
                        handleDragOver(index, event) {
                            event.preventDefault();
                            if (index !== this.draggedItem) {
                                const item = this.files[this.draggedItem];
                                this.files.splice(this.draggedItem, 1);
                                this.files.splice(index, 0, item);
                                this.draggedItem = index;
                            }
                        }
                    }"
                    class="space-y-4">
                        <!-- Empty State Upload Area -->
                        <div x-show="files.length === 0"
                             class="border border-dashed border-gray-300 rounded-lg bg-white"
                             @drop.prevent="handleFiles($event)"
                             @dragover.prevent="$event.target.classList.add('border-blue-500')"
                             @dragleave.prevent="$event.target.classList.remove('border-blue-500')">
                            <div class="p-12 text-center">
                                <input type="file"
                                       name="images[]"
                                       id="empty-state-images"
                                       multiple
                                       accept="image/*"
                                       class="hidden"
                                       @change="handleFiles($event)">

                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>

                                <div class="mt-4">
                                    <label for="empty-state-images" class="cursor-pointer">
                                        <span class="text-blue-600 hover:text-blue-700">Upload Images</span>
                                        <span class="text-gray-600"> or drag and drop</span>
                                    </label>
                                    <p class="mt-1 text-sm text-gray-500">PNG, JPG, GIF up to 10MB</p>
                                </div>
                            </div>
                        </div>

                        <!-- Image Grid -->
                        <div x-show="files.length > 0" class="space-y-4">
                            <!-- Images List -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                <template x-for="(file, index) in files" :key="index">
                                    <div class="relative bg-white rounded-lg shadow-sm overflow-hidden"
                                         draggable="true"
                                         @dragstart="handleDragStart(index, $event)"
                                         @dragend="handleDragEnd($event)"
                                         @dragover="handleDragOver(index, $event)">
                                        <div class="aspect-w-4 aspect-h-3">
                                            <img :src="file.preview"
                                                 :alt="'Image ' + (index + 1)"
                                                 class="object-cover w-full h-full">
                                        </div>

                                        <div class="absolute top-0 right-0 p-2">
                                            <button @click="removeFile(index)"
                                                    class="p-1 bg-white rounded-full shadow-sm hover:bg-gray-100">
                                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </div>

                                        <div class="p-3 flex justify-between items-center">
                                            <div class="flex items-center space-x-2">
                                                <span class="text-sm text-gray-600" x-text="'Image ' + (index + 1)"></span>
                                                <span x-show="index === 0" class="text-xs text-blue-600 bg-blue-50 px-2 py-0.5 rounded">Primary</span>
                                            </div>
                                            <div x-show="files.length > 1" class="text-xs text-gray-500">Drag to reorder</div>
                                        </div>
                                    </div>
                                </template>

                                <!-- Add More Button -->
                                <div class="relative bg-white rounded-lg overflow-hidden border-2 border-dashed border-blue-200 hover:border-blue-400 transition-colors">
                                    <input type="file"
                                           name="images[]"
                                           id="add-more-images"
                                           multiple
                                           accept="image/*"
                                           class="hidden"
                                           @change="handleFiles($event)">

                                    <label for="add-more-images" class="block cursor-pointer">
                                        <div class="aspect-w-4 aspect-h-3 flex items-center justify-center">
                                            <div class="text-center space-y-2">
                                                <div class="mx-auto h-10 w-10 rounded-full bg-blue-50 flex items-center justify-center">
                                                    <svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <span class="block text-sm font-medium text-blue-600">Add More Images</span>
                                                    <span class="block text-xs text-gray-500">or drag and drop</span>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Error Message -->
                        <div x-show="uploadError"
                             x-text="uploadError"
                             class="mt-2 text-sm text-red-600 bg-red-50 border border-red-200 rounded-md p-2"></div>
                    </div>
                </div>

                <!-- Product Variants -->
                <div class="p-6 space-y-6">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                            <svg class="h-5 w-5 text-gray-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Product Variants
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">Add the product variants.</p>
                    </div>

                    <div id="variants-container">
                        <!-- Variant template will be added here -->
                    </div>
                    <button type="button" class="btn btn-secondary" onclick="addVariant()">Add Variant</button>
                </div>

                <!-- Form Actions -->
                <div class="fixed bottom-0 left-0 right-0 bg-gray-50 px-6 py-4 flex justify-end space-x-4 border-t border-gray-200">
                    <button type="button" onclick="window.location.href='{{ route('admin.products.index') }}'" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cancel
                    </button>
                    <button type="button" class="px-4 py-2 bg-gray-200 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Save as Draft
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Create Product
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

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
            .replace(/^\-+/, '')             // Trim - from start of text
            .replace(/-+$/, '');            // Trim - from end of text
    }

    // Auto-generate slug from product name
    document.getElementById('name').addEventListener('input', function(e) {
        const slugInput = document.getElementById('slug');
        if (slugInput) {
            slugInput.value = generateSlug(e.target.value);
        }
    });

    let variantCount = 0;

    // Initialize select2 for express delivery countries
    $(document).ready(function() {
        $('#express_delivery_countries').select2({
            placeholder: 'Select countries',
            allowClear: true
        });
    });

    function addVariant() {
        const container = document.getElementById('variants-container');
        const variantHtml = `
            <div class="variant-item border rounded p-3 mb-3">
                <h5>Variant #${variantCount + 1}</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Variant Name *</label>
                            <input type="text" name="variants[${variantCount}][name]" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Variant Value *</label>
                            <input type="text" name="variants[${variantCount}][value]" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Price *</label>
                            <input type="number" step="0.01" name="variants[${variantCount}][price]" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Special Price</label>
                            <input type="number" step="0.01" name="variants[${variantCount}][special_price]" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Stock *</label>
                            <input type="number" name="variants[${variantCount}][stock]" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Seller SKU</label>
                            <input type="text" name="variants[${variantCount}][seller_sku]" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status *</label>
                            <select name="variants[${variantCount}][status]" class="form-control" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove()">Remove Variant</button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', variantHtml);
        variantCount++;
    }

    // Image preview functionality
    document.getElementById('images').addEventListener('change', function(e) {
        const preview = document.getElementById('imagePreview');
        preview.innerHTML = ''; // Clear existing previews

        Array.from(e.target.files).forEach((file, index) => {
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'col-md-2 col-sm-4 col-6 mb-3';
                    div.innerHTML = `
                        <div class="position-relative">
                            <img src="${e.target.result}" class="img-thumbnail" alt="Preview">
                            ${index === 0 ? '<span class="badge badge-primary position-absolute top-0 start-0 m-2">Primary</span>' : ''}
                        </div>
                    `;
                    preview.appendChild(div);
                }
                reader.readAsDataURL(file);
            }
        });
    });
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
