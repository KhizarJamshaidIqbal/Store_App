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

            <div class="bg-white shadow-sm rounded-lg divide-y divide-gray-200 space-y-6">
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

                    <div class="space-y-6">
                        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Product Name <span class="text-red-500">*</span></label>
                            <div class="mt-1">
                                <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                            <label for="slug" class="block text-sm font-medium text-gray-700">Slug <span class="text-red-500">*</span></label>
                            <div class="mt-1">
                                <input type="text" name="slug" id="slug" value="{{ old('slug', $product->slug) }}" required
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                            <label for="category" class="block text-sm font-medium text-gray-700">Category <span class="text-red-500">*</span></label>
                            <div class="mt-1"
                                x-data="initCategorySelector($el, {{ $product->category_id ?? 'null' }}, {{ json_encode($categories) }})"
                                x-init="init()"
                                @click.away="isOpen = false">
                                <div class="relative">
                                    <input type="hidden" name="category_id" x-model="selectedId">
                                    <button type="button"
                                        @click="isOpen = !isOpen"
                                        class="bg-white relative w-full border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-default focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-blue-500"
                                        aria-haspopup="listbox"
                                        :aria-expanded="open">
                                        <span class="block truncate" x-text="getDisplayPath()"></span>
                                        <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </button>

                                    <div x-show="isOpen"
                                        class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-80 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm"
                                        x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="transform opacity-100 scale-100"
                                        x-transition:leave-end="transform opacity-0 scale-95">
                                        <div @click="open = false"
                                            class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-blue-50">
                                            <span class="block truncate">Select category</span>
                                        </div>
                                        <div class="flex">
                                            <!-- Level 1 -->
                                            <div class="w-1/4 border-r">
                                                <div class="p-2">
                                                    <input type="text" x-model="search[0]" placeholder="Filter..."
                                                        class="w-full px-2 py-1 text-sm border rounded">
                                                </div>
                                                <template x-for="category in getFilteredCategories(0)" :key="category.id">
                                                    <div @click="selectCategory(0, category)"
                                                        :class="{'bg-blue-50': selectedPath[0]?.id === category.id}"
                                                        class="px-3 py-2 cursor-pointer hover:bg-gray-100">
                                                        <span x-text="category.name"></span>
                                                        <span x-show="category.children?.length" class="float-right">→</span>
                                                    </div>
                                                </template>
                                            </div>

                                            <!-- Level 2 -->
                                            <div class="w-1/4 border-r" x-show="level2.length">
                                                <div class="p-2">
                                                    <input type="text" x-model="search[1]" placeholder="Filter..."
                                                        class="w-full px-2 py-1 text-sm border rounded">
                                                </div>
                                                <template x-for="category in getFilteredCategories(1)" :key="category.id">
                                                    <div @click="selectCategory(1, category)"
                                                        :class="{'bg-blue-50': selectedPath[1]?.id === category.id}"
                                                        class="px-3 py-2 cursor-pointer hover:bg-gray-100">
                                                        <span x-text="category.name"></span>
                                                        <span x-show="category.children?.length" class="float-right">→</span>
                                                    </div>
                                                </template>
                                            </div>

                                            <!-- Level 3 -->
                                            <div class="w-1/4 border-r" x-show="level3.length">
                                                <div class="p-2">
                                                    <input type="text" x-model="search[2]" placeholder="Filter..."
                                                        class="w-full px-2 py-1 text-sm border rounded">
                                                </div>
                                                <template x-for="category in getFilteredCategories(2)" :key="category.id">
                                                    <div @click="selectCategory(2, category)"
                                                        :class="{'bg-blue-50': selectedPath[2]?.id === category.id}"
                                                        class="px-3 py-2 cursor-pointer hover:bg-gray-100">
                                                        <span x-text="category.name"></span>
                                                        <span x-show="category.children?.length" class="float-right">→</span>
                                                    </div>
                                                </template>
                                            </div>

                                            <!-- Level 4 -->
                                            <div class="w-1/4" x-show="level4.length">
                                                <div class="p-2">
                                                    <input type="text" x-model="search[3]" placeholder="Filter..."
                                                        class="w-full px-2 py-1 text-sm border rounded">
                                                </div>
                                                <template x-for="category in getFilteredCategories(3)" :key="category.id">
                                                    <div @click="selectCategory(3, category)"
                                                        :class="{'bg-blue-50': selectedPath[3]?.id === category.id}"
                                                        class="px-3 py-2 cursor-pointer hover:bg-gray-100">
                                                        <span x-text="category.name"></span>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                            <div class="mt-2" x-data="{ open: false, selected: '{{ old('status', $product->status) }}' }">
                                <div class="relative">
                                    <button type="button"
                                        @click="open = !open"
                                        class="bg-white relative w-full border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-pointer focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-blue-500"
                                        aria-haspopup="listbox"
                                        :aria-expanded="open">
                                        <span x-text="selected === 'active' ? 'Active' : (selected === 'inactive' ? 'InActive' : (selected === 'archived' ? 'Archived' : 'Select status'))"
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
                                        <div @click="selected = 'inactive'; open = false"
                                            :class="{ 'bg-blue-50 text-blue-900': selected === 'inactive' }"
                                            class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-blue-50">
                                            <span class="block truncate">InActive</span>
                                            <span x-show="selected === 'inactive'" class="absolute inset-y-0 right-0 flex items-center pr-4 text-blue-600">
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
                                    <input type="hidden" name="status" x-model="selected">
                                </div>
                            </div></div>

                            <div class="space-y-6">
                                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                    <p class="mt-1 text-sm text-gray-500">Brief description of the product.</p>
                                    <div class="mt-3">
                                        <textarea id="description" name="description" class="editor w-full">{{ old('description', $product->description) }}</textarea>
                                    </div>
                                </div>

                                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                                    <label for="highlights" class="block text-sm font-medium text-gray-700">Highlights</label>
                                    <p class="mt-1 text-sm text-gray-500">Key features and highlights of the product.</p>
                                    <div class="mt-3">
                                        <textarea id="highlights" name="highlights" class="editor w-full">{{ old('highlights', $product->highlights) }}</textarea>
                                    </div>
                                </div>

                                <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4"
                                    x-data="{ on: {{ old('dangerous_goods', $product->dangerous_goods) ? 'true' : 'false' }} }">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <label class="text-sm font-medium text-gray-700">Dangerous Goods</label>
                                            <p class="text-sm text-gray-500">Mark if this product contains dangerous materials</p>
                                        </div>
                                        <button type="button"
                                            @click="on = !on"
                                            :class="{ 'bg-blue-600': on, 'bg-gray-200': !on }"
                                            class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                            role="switch"
                                            :aria-checked="on.toString()">
                                            <span class="sr-only">Dangerous goods toggle</span>
                                            <span
                                                aria-hidden="true"
                                                :class="{ 'translate-x-5': on, 'translate-x-0': !on }"
                                                class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200">
                                            </span>
                                        </button>
                                        <input type="hidden" name="dangerous_goods" :value="on ? 1 : 0">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Details -->
                    <div class="space-y-6 bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                                <svg class="h-5 w-5 text-gray-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Product Details
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">Specific details about the product's characteristics.</p>
                        </div>

                        <div class="space-y-6">
                            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                                <label for="sku" class="block text-sm font-medium text-gray-700">SKU</label>
                                <div class="mt-1">
                                    <input type="text" name="sku" id="sku" value="{{ old('sku', $product->sku) }}"
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>

                            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                                <label for="shop_sku" class="block text-sm font-medium text-gray-700">Shop SKU</label>
                                <div class="mt-1">
                                    <input type="text" name="shop_sku" id="shop_sku" value="{{ old('shop_sku', $product->shop_sku) }}"
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>

                            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                                <label for="brand" class="block text-sm font-medium text-gray-700">Brand</label>
                                <div class="mt-1">
                                    <input type="text" name="brand" id="brand" value="{{ old('brand', $product->brand) }}"
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>

                            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                                <label for="model" class="block text-sm font-medium text-gray-700">Model</label>
                                <div class="mt-1">
                                    <input type="text" name="model" id="model" value="{{ old('model', $product->model) }}"
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>

                            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                                <label for="texture" class="block text-sm font-medium text-gray-700">Texture</label>
                                <div class="mt-1">
                                    <input type="text" name="texture" id="texture" value="{{ old('texture', $product->texture) }}"
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>

                            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                                <label for="color_family" class="block text-sm font-medium text-gray-700">Color Family</label>
                                <div class="mt-1">
                                    <input type="text" name="color_family" id="color_family" value="{{ old('color_family', $product->color_family) }}"
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>

                            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                                <label for="country_of_origin" class="block text-sm font-medium text-gray-700">Country of Origin</label>
                                <div class="mt-1">
                                    <input type="text" name="country_of_origin" id="country_of_origin" value="{{ old('country_of_origin', $product->country_of_origin) }}"
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>

                            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                                <label for="pack_type" class="block text-sm font-medium text-gray-700">Pack Type</label>
                                <div class="mt-1">
                                    <input type="text" name="pack_type" id="pack_type" value="{{ old('pack_type', $product->pack_type) }}"
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>

                            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                                <label for="volume" class="block text-sm font-medium text-gray-700">Volume</label>
                                <div class="mt-1">
                                    <input type="text" name="volume" id="volume" value="{{ old('volume', $product->volume) }}"
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>

                            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                                <label for="weight" class="block text-sm font-medium text-gray-700">Weight (kg)</label>
                                <div class="mt-1">
                                    <input type="number" step="0.01" name="weight" id="weight" value="{{ old('weight', $product->weight) }}"
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>

                            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                                <label for="material" class="block text-sm font-medium text-gray-700">Material</label>
                                <div class="mt-1">
                                    <input type="text" name="material" id="material" value="{{ old('material', $product->material) }}"
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>

                            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                                <label for="features" class="block text-sm font-medium text-gray-700">Features</label>
                                <div class="mt-1">
                                    <textarea id="features" name="features" rows="3"
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">{{ old('features', $product->features) }}</textarea>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">List the key features of the product.</p>
                            </div>

                            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                                <label for="brand_classification" class="block text-sm font-medium text-gray-700">Brand Classification</label>
                                <div class="mt-1">
                                    <input type="text" name="brand_classification" id="brand_classification" value="{{ old('brand_classification', $product->brand_classification) }}"
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>

                            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                                <label for="shelf_life" class="block text-sm font-medium text-gray-700">Shelf Life</label>
                                <div class="mt-1">
                                    <input type="text" name="shelf_life" id="shelf_life" value="{{ old('shelf_life', $product->shelf_life) }}"
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>

                            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                                <label for="express_delivery_countries" class="block text-sm font-medium text-gray-700">Express Delivery Countries</label>
                                <div class="mt-1">
                                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4"
                                        x-data="expressDeliveryCountries()">
                                        <!-- Selected Countries Tags -->
                                        <div class="mb-2 flex flex-wrap gap-2">
                                            <template x-for="(country, index) in selected" :key="index">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                    <span x-text="country"></span>
                                                    <button type="button" @click="removeCountry(index)" class="ml-1 inline-flex items-center justify-center flex-shrink-0 h-4 w-4 rounded-full text-blue-400 hover:bg-blue-200 hover:text-blue-500 focus:outline-none focus:bg-blue-500 focus:text-white">
                                                        <span class="sr-only">Remove country</span>
                                                        <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
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

                                        <!-- Hidden inputs for form submission -->
                                        <template x-for="country in selected" :key="country">
                                            <input type="hidden" name="express_delivery_countries[]" :value="country">
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing & Stock -->
                    <div class="space-y-6 bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                                <svg class="h-5 w-5 text-gray-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Pricing & Stock
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">Manage product pricing and inventory information.</p>
                        </div>

                        <div class="space-y-6">
                            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                                <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                                <div class="mt-2 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <input type="number" step="0.01" name="price" id="price"
                                        value="{{ old('price', $product->price) }}"
                                        class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"
                                        placeholder="0.00">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">USD</span>
                                    </div>
                                </div>
                            </div>

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

                            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                                <label for="stock" class="block text-sm font-medium text-gray-700">Stock</label>
                                <div class="mt-2 relative rounded-md shadow-sm">
                                    <input type="number" name="stock" id="stock"
                                        value="{{ old('stock', $product->stock) }}"
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
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden rounded-lg shadow-sm border border-gray-200">
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
                    <img src="{{ asset('storage/' . $image->image_path) }}"
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
                        <svg class="h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <span class="text-white text-xs ml-1">Drag to reorder</span>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
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
                <div class="space-y-6 bg-white rounded-lg shadow-sm overflow-hidden p-4 border border-gray-200">
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
                    // Function to generate slug from text
                    function generateSlug(text) {
                        return text
                            .toString()
                            .toLowerCase()
                            .trim()
                            .replace(/\s+/g, '-')           // Replace spaces with -
                            .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
                            .replace(/\-\-+/g, '-')         // Replace multiple - with single -
                            .replace(/^[-_]+/, '')             // Trim - and _ from start of text
                            .replace(/[-_]+$/', '');            // Trim - and _ from end of text
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

                            // Handle multiple select fields - with null check
                            const selectedCountries = [];
                            const expressDeliverySelect = document.getElementById('express_delivery_countries');
                            if (expressDeliverySelect && expressDeliverySelect.selectedOptions) {
                                selectedCountries.push(...Array.from(expressDeliverySelect.selectedOptions).map(option => option.value));
                            }

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

                            console.log('Submitting data:', jsonData); // Debug log

                            // Make the request
                            const response = await fetch(form.action, {
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

                    function showNotification(message, type = 'success', productData = null) {
                        const notification = document.createElement('div');
                        notification.className = `fixed bottom-4 right-4 p-4 rounded-lg shadow-lg max-w-sm w-full bg-white border ${
                            type === 'success' ? 'border-green-500' : 'border-red-500'
                        }`;

                        if (type === 'success' && productData) {
                            notification.innerHTML = `
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0 w-16 h-16">
                                        <img src="${productData.image}" alt="${productData.name}"
                                            class="w-full h-full object-cover rounded">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900">
                                            ${message}
                                        </p>
                                        <div class="mt-1 text-sm text-gray-500">
                                            <p class="font-medium">${productData.name}</p>
                                            <p>Price: $${productData.price}</p>
                                            <p>Stock: ${productData.stock}</p>
                                        </div>
                                    </div>
                                    <button type="button" class="flex-shrink-0 ml-4" onclick="this.parentElement.parentElement.remove()">
                                        <svg class="w-4 h-4 text-gray-400 hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            `;
                        } else {
                            notification.innerHTML = `
                                <div class="flex items-center justify-between">
                                    <p class="text-sm ${type === 'success' ? 'text-green-600' : 'text-red-600'}">
                                        ${message}
                                    </p>
                                    <button type="button" class="ml-4" onclick="this.parentElement.parentElement.remove()">
                                        <svg class="w-4 h-4 text-gray-400 hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            `;
                        }

                        document.body.appendChild(notification);
                        setTimeout(() => notification.remove(), 5000);
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

                @push('scripts')
                <script>
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

                            // Handle form data
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

                            // Get express delivery countries from Alpine.js component
                            const expressDeliveryComponent = document.getElementById('express-delivery-component').__x.$data;
                            if (expressDeliveryComponent && expressDeliveryComponent.selected) {
                                jsonData.express_delivery_countries = expressDeliveryComponent.selected;
                            } else {
                                jsonData.express_delivery_countries = [];
                            }

                            console.log('Submitting data:', jsonData); // Debug log

                            const response = await fetch(form.action, {
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

                            if (!response.ok) {
                                const errorData = await response.json();
                                throw new Error(errorData.message || 'Failed to update product');
                            }

                            const result = await response.json();

                            if (result.success) {
                                // Show notification with product details
                                showNotification(result.message, 'success', {
                                    name: result.product.name,
                                    price: result.product.price,
                                    stock: result.product.stock,
                                    image: result.product.image_url || '/placeholder-image.jpg'
                                });

                                // Redirect to products list after a short delay
                                setTimeout(() => {
                                    window.location.href = '{{ route("admin.products.index") }}';
                                }, 2000);
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

                    function showNotification(message, type = 'success', productData = null) {
                        const notification = document.createElement('div');
                        notification.className = `fixed bottom-4 right-4 p-4 rounded-lg shadow-lg max-w-sm w-full bg-white border ${
                            type === 'success' ? 'border-green-500' : 'border-red-500'
                        }`;

                        if (type === 'success' && productData) {
                            notification.innerHTML = `
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0 w-16 h-16">
                                        <img src="${productData.image}" alt="${productData.name}"
                                            class="w-full h-full object-cover rounded">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900">
                                            ${message}
                                        </p>
                                        <div class="mt-1 text-sm text-gray-500">
                                            <p class="font-medium">${productData.name}</p>
                                            <p>Price: $${productData.price}</p>
                                            <p>Stock: ${productData.stock}</p>
                                        </div>
                                    </div>
                                    <button type="button" class="flex-shrink-0 ml-4" onclick="this.parentElement.parentElement.remove()">
                                        <svg class="w-4 h-4 text-gray-400 hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            `;
                        } else {
                            notification.innerHTML = `
                                <div class="flex items-center justify-between">
                                    <p class="text-sm ${type === 'success' ? 'text-green-600' : 'text-red-600'}">
                                        ${message}
                                    </p>
                                    <button type="button" class="ml-4" onclick="this.parentElement.parentElement.remove()">
                                        <svg class="w-4 h-4 text-gray-400 hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            `;
                        }

                        document.body.appendChild(notification);
                        setTimeout(() => notification.remove(), 5000);
                    }
                </script>
                @endpush

                <!-- Package Information -->
                <div class="space-y-6 space-x-6 bg-white rounded-lg shadow-sm overflow-hidden p-4 border border-gray-200">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                            <svg class="h-5 w-5 text-gray-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Package Information
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">Enter the physical dimensions and weight of the product package.</p>
                    </div>

                    <div class="space-y-6">
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
                </div><br><br>

                <!-- Form Actions -->
                <div class="fixed bottom-0 left-0 right-0 bg-gray-50 px-6 py-4 flex justify-end space-x-4 border-t border-gray-200">
                    <button type="button" onclick="window.location.href='{{ url('/admin/products') }}'" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
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

@push('scripts')
<script>
    let variantCounter = {{ isset($product->variants) ? count($product->variants) : 0 }};

    function addVariant() {
        const variantsContainer = document.getElementById('variants-container');
        const newIndex = variantCounter++;

        const variantHtml = `
            <div class="variant-row border rounded-lg p-4 mb-4 bg-white" data-variant-id="${newIndex}">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Variant Name</label>
                        <input type="text" name="variants[${newIndex}][name]" placeholder="Size2"
                            class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Value</label>
                        <input type="text" name="variants[${newIndex}][value]" placeholder="red"
                            class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="variants[${newIndex}][status]"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Price ($)</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" step="0.01" name="variants[${newIndex}][price]" placeholder="300.00"
                                class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Special Price ($)</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" step="0.01" name="variants[${newIndex}][special_price]" placeholder="99.00"
                                class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Stock</label>
                        <input type="number" name="variants[${newIndex}][stock]" placeholder="99"
                            class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                </div>
                <div class="mt-4 text-right">
                    <button type="button" onclick="removeVariant(${newIndex})"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Remove Variant
                    </button>
                </div>
            </div>
        `;

        variantsContainer.insertAdjacentHTML('beforeend', variantHtml);
    }

    function removeVariant(index) {
        const variantElement = document.querySelector(`[data-variant-id="${index}"]`);
        if (variantElement) {
            variantElement.remove();
        }
    }
</script>
@endpush

@push('scripts')
<script>
    function initCategorySelector(el, initialId, categories) {
        return {
            isOpen: false,
            selectedId: initialId,
            allCategories: categories,
            level1: categories,
            level2: [],
            level3: [],
            level4: [],
            search: ['', '', '', ''],
            selectedPath: [],

            init() {
                if (this.selectedId) {
                    this.findAndSetPath(this.selectedId);
                }
            },

            findAndSetPath(targetId) {
                const findPath = (cats, id, path = []) => {
                    for (let cat of cats) {
                        if (cat.id === id) {
                            return [...path, cat];
                        }
                        if (cat.children) {
                            const found = findPath(cat.children, id, [...path, cat]);
                            if (found) return found;
                        }
                    }
                    return null;
                };

                const path = findPath(this.level1, targetId);
                if (path) {
                    this.selectedPath = path;
                    if (path[0].children) this.level2 = path[0].children;
                    if (path[1]?.children) this.level3 = path[1].children;
                    if (path[2]?.children) this.level4 = path[2].children;
                }
            },

            selectCategory(level, category) {
                this.selectedId = category.id;
                this.selectedPath = this.selectedPath.slice(0, level);
                this.selectedPath[level] = category;

                if (level === 0) {
                    this.level2 = category.children || [];
                    this.level3 = [];
                    this.level4 = [];
                } else if (level === 1) {
                    this.level3 = category.children || [];
                    this.level4 = [];
                } else if (level === 2) {
                    this.level4 = category.children || [];
                }
            },

            getFilteredCategories(level) {
                const categories = {
                    0: this.level1,
                    1: this.level2,
                    2: this.level3,
                    3: this.level4
                }[level] || [];

                return categories.filter(cat =>
                    !this.search[level] ||
                    cat.name.toLowerCase().includes(this.search[level].toLowerCase())
                );
            },

            getDisplayPath() {
                return this.selectedPath.length
                    ? this.selectedPath.map(cat => cat.name).join(' > ')
                    : 'Select category';
            }
        };
    }
</script>
@endpush

@push('scripts')
<script>
    function expressDeliveryCountries() {
        return {
            search: '',
            selected: @json(old('express_delivery_countries', $product->express_delivery_countries ? json_decode($product->express_delivery_countries) : [])),
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
        }
    }

    // Form submission
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
                    if (!jsonData.express_delivery_countries) {
                        jsonData.express_delivery_countries = [];
                    }
                    jsonData.express_delivery_countries.push(value);
                } else {
                    jsonData[key] = value;
                }
            }

            // Clean up variants array if it exists
            if (jsonData.variants) {
                jsonData.variants = jsonData.variants.filter(Boolean);
            }

            console.log('Submitting data:', jsonData); // Debug log

            const response = await fetch(form.action, {
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

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Failed to update product');
            }

            const result = await response.json();

            if (result.success) {
                showNotification(result.message, 'success');
                // Redirect to products list
                window.location.href = '{{ route("admin.products.index") }}';
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

    function showNotification(message, type = 'success', productData = null) {
        const notification = document.createElement('div');
        notification.className = `fixed bottom-4 right-4 p-4 rounded-lg shadow-lg max-w-sm w-full bg-white border ${
            type === 'success' ? 'border-green-500' : 'border-red-500'
        }`;

        if (type === 'success' && productData) {
            notification.innerHTML = `
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-16 h-16">
                        <img src="${productData.image}" alt="${productData.name}"
                            class="w-full h-full object-cover rounded">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">
                            ${message}
                        </p>
                        <div class="mt-1 text-sm text-gray-500">
                            <p class="font-medium">${productData.name}</p>
                            <p>Price: $${productData.price}</p>
                            <p>Stock: ${productData.stock}</p>
                        </div>
                    </div>
                    <button type="button" class="flex-shrink-0 ml-4" onclick="this.parentElement.parentElement.remove()">
                        <svg class="w-4 h-4 text-gray-400 hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            `;
        } else {
            notification.innerHTML = `
                <div class="flex items-center justify-between">
                    <p class="text-sm ${type === 'success' ? 'text-green-600' : 'text-red-600'}">
                        ${message}
                    </p>
                    <button type="button" class="ml-4" onclick="this.parentElement.parentElement.remove()">
                        <svg class="w-4 h-4 text-gray-400 hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            `;
        }

        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 5000);
    }
</script>
@endpush
@endsection

@push('scripts')
<script>
    // Initialize TomSelect for category selection
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
