@extends('admin.layouts.app')

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/skins/ui/oxide/skin.min.css" rel="stylesheet">
<style>
    /* Hide unnecessary elements */
    .tox-promotion,
    .tox-statusbar__branding,
    .tox-menubar {
        display: none !important;
    }
</style>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Add New Product</h1>
            <p class="mt-2 text-sm text-gray-600">Create a new product with all its details and specifications.</p>
        </div>
        <a href="{{ route('admin.products.index') }}"
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Products
        </a>
    </div>

    <form action="{{ route('admin.products.store') }}"
          method="POST"
          enctype="multipart/form-data"
          x-data="{ activeTab: 'basic' }"
          class="space-y-8">
        @csrf

        <!-- Tabs Navigation -->
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button type="button"
                        @click="activeTab = 'basic'"
                        :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'basic',
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'basic' }"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Basic Information
                </button>
                <button type="button"
                        @click="activeTab = 'specification'"
                        :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'specification',
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'specification' }"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Product Specification
                </button>
                <button type="button"
                        @click="activeTab = 'media'"
                        :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'media',
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'media' }"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Media
                </button>
                <button type="button"
                        @click="activeTab = 'pricing'"
                        :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'pricing',
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'pricing' }"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Pricing & Stock
                </button>
                <button type="button"
                        @click="activeTab = 'shipping'"
                        :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'shipping',
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'shipping' }"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Shipping
                </button>
            </nav>
        </div>

        <!-- Tab Panels -->
        <div class="mt-6">
            <!-- Basic Information -->
            <div x-show="activeTab === 'basic'" class="space-y-6 bg-white rounded-lg shadow p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">
                            Product Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="name"
                               id="name"
                               required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               placeholder="Enter product name">
                    </div>

                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700">
                            Product Slug
                            <span class="text-gray-500 text-xs">(Auto-generated)</span>
                        </label>
                        <input type="text"
                               name="slug"
                               id="slug"
                               class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               readonly>
                        <p class="mt-1 text-sm text-gray-500">Will be automatically generated from the product name</p>
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700">
                            Category <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <x-admin.category-selector :categories="$categories" />
                        </div>
                        @error('category_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="sku" class="block text-sm font-medium text-gray-700">
                            SKU
                            <span class="text-gray-500 text-xs">(Optional)</span>
                        </label>
                        <input type="text"
                               name="sku"
                               id="sku"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               placeholder="Enter product SKU">
                    </div>

                    <div class="col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-4">
                            Description <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description"
                                  id="description"
                                  rows="4"
                                  required
                                  class="tinymce-editor"
                                  placeholder="Enter product description"></textarea>
                    </div>

                    <div class="col-span-2">
                        <label for="highlights" class="block text-sm font-medium text-gray-700 mb-4">
                            Highlights
                            <span class="text-gray-500 text-xs">(Optional)</span>
                        </label>
                        <textarea name="highlights"
                                  id="highlights"
                                  rows="4"
                                  class="tinymce-editor"
                                  placeholder="Enter key features or highlights of the product"></textarea>
                        <p class="mt-1 text-sm text-gray-500">Add key features to make your product stand out</p>
                    </div>
                </div>
            </div>

            <!-- Product Specification -->
            <div x-show="activeTab === 'specification'" class="space-y-6 bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-500">Fill more product specification will increase product searchability.</p>

                <div class="grid grid-cols-2 gap-6">
                    <!-- Brand -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Brand <span class="text-gray-500 text-xs">(Optional)</span>
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm" x-data="{ value: '' }">
                            <input type="text"
                                   name="brand"
                                   x-model="value"
                                   class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pr-8 sm:text-sm border-gray-300 rounded-md"
                                   placeholder="Please Input">
                            <div x-show="value.length > 0"
                                 @click="value = ''"
                                 class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer">
                                <svg class="h-4 w-4 text-gray-400 hover:text-gray-500"
                                     xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 20 20"
                                     fill="currentColor">
                                    <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Texture -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Texture <span class="text-gray-500 text-xs">(Optional)</span>
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm" x-data="{ value: '' }">
                            <input type="text"
                                   name="texture"
                                   x-model="value"
                                   class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pr-8 sm:text-sm border-gray-300 rounded-md"
                                   placeholder="Please Input">
                            <div x-show="value.length > 0"
                                 @click="value = ''"
                                 class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer">
                                <svg class="h-4 w-4 text-gray-400 hover:text-gray-500"
                                     xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 20 20"
                                     fill="currentColor">
                                    <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Model -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Model</label>
                        <input type="text" name="model" placeholder="Please Input" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>

                    <!-- Color Family -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Color Family</label>
                        <div class="mt-1">
                            <input type="text"
                                   name="color_family"
                                   list="color-options"
                                   placeholder="Please Input"
                                   class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            <datalist id="color-options">
                                <option value="Black">
                                <option value="Beige">
                                <option value="Blue">
                                <option value="Brown">
                                <option value="Gold">
                                <option value="Green">
                                <option value="Grey">
                                <option value="Multicolor">
                                <option value="Navy">
                                <option value="Orange">
                                <option value="Pink">
                                <option value="Purple">
                                <option value="Red">
                                <option value="Silver">
                                <option value="White">
                                <option value="Yellow">
                            </datalist>
                        </div>
                    </div>

                    <!-- Country of Origin -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Country of Origin</label>
                        <div class="mt-1 relative rounded-md shadow-sm" x-data="{ value: '' }">
                            <input type="text"
                                   name="country_of_origin"
                                   x-model="value"
                                   class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pr-8 sm:text-sm border-gray-300 rounded-md"
                                   placeholder="Please Input">
                            <div x-show="value.length > 0"
                                 @click="value = ''"
                                 class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer">
                                <svg class="h-4 w-4 text-gray-400 hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Pack Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Pack Type</label>
                        <select name="pack_type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="">Please Input</option>
                            <option value="single">Single-Item</option>
                            <option value="multi">Multi-Pack</option>
                        </select>
                    </div>

                    <!-- Brand Classification -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Brand Classification</label>
                        <div class="mt-1 relative rounded-md shadow-sm" x-data="{ value: '' }">
                            <input type="text"
                                   name="brand_classification"
                                   x-model="value"
                                   class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pr-8 sm:text-sm border-gray-300 rounded-md"
                                   placeholder="Please Input">
                            <div x-show="value.length > 0"
                                 @click="value = ''"
                                 class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer">
                                <svg class="h-4 w-4 text-gray-400 hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Volume -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Volume (ml)</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="number"
                                   name="volume"
                                   min="0"
                                   step="0.01"
                                   class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pr-12 sm:text-sm border-gray-300 rounded-md"
                                   placeholder="Please Input">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">ml</span>
                            </div>
                        </div>
                    </div>

                    <!-- Express Delivery -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Express Delivery Countries</label>
                        <div class="mt-1 relative rounded-md shadow-sm" x-data="{ value: '' }">
                            <input type="text"
                                   name="express_delivery"
                                   x-model="value"
                                   class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pr-8 sm:text-sm border-gray-300 rounded-md"
                                   placeholder="Please Input">
                            <div x-show="value.length > 0"
                                 @click="value = ''"
                                 class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer">
                                <svg class="h-4 w-4 text-gray-400 hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Weight -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Weight (kg)</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="number"
                                   name="weight"
                                   min="0"
                                   step="0.01"
                                   class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pr-12 sm:text-sm border-gray-300 rounded-md"
                                   placeholder="Please Input">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">kg</span>
                            </div>
                        </div>
                    </div>

                    <!-- Shelf Life -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Shelf Life</label>
                        <div class="mt-1 relative rounded-md shadow-sm" x-data="{ value: '' }">
                            <input type="text"
                                   name="shelf_life"
                                   x-model="value"
                                   class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pr-8 sm:text-sm border-gray-300 rounded-md"
                                   placeholder="Please Input">
                            <div x-show="value.length > 0"
                                 @click="value = ''"
                                 class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer">
                                <svg class="h-4 w-4 text-gray-400 hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Material -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Material</label>
                        <div class="mt-1 relative rounded-md shadow-sm" x-data="{ value: '' }">
                            <input type="text"
                                   name="material"
                                   x-model="value"
                                   class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pr-8 sm:text-sm border-gray-300 rounded-md"
                                   placeholder="Please Input">
                            <div x-show="value.length > 0"
                                 @click="value = ''"
                                 class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer">
                                <svg class="h-4 w-4 text-gray-400 hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Features -->
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Features</label>
                        <div class="mt-1">
                            <textarea name="features"
                                      rows="3"
                                      class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                      placeholder="Please Input"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Media -->
            <div x-show="activeTab === 'media'" class="space-y-6 bg-white rounded-lg shadow p-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Product Images <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-indigo-500 transition-colors duration-200">
                        <div class="space-y-2 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 015.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                    <span>Upload images</span>
                                    <input id="images"
                                           name="images[]"
                                           type="file"
                                           class="sr-only"
                                           multiple
                                           accept="image/*"
                                           required>
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                        </div>
                    </div>
                    <div id="image-preview" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4"></div>
                </div>
            </div>

            <!-- Pricing & Stock -->
            <div x-show="activeTab === 'pricing'" class="space-y-6 bg-white rounded-lg shadow p-6">
                <div x-data="{
                    variants: [],
                    variantCount: 0,
                    maxVariants: 1,
                    variantColors: [],
                    dragStartIndex: null,
                    showSpecialPriceDialog: false,
                    currentRowIndex: null,
                    specialPrices: {},
                    addVariant() {
                        if (this.variantCount < this.maxVariants) {
                            this.variants.push({
                                name: '',
                                colors: [''],
                                type: ''
                            });
                            this.variantCount++;
                        }
                    },
                    addColor(variantIndex) {
                        if (!this.variants[variantIndex].colors) {
                            this.variants[variantIndex].colors = [];
                        }
                        this.variants[variantIndex].colors.push('');
                    },
                    dragStart(colorIndex) {
                        this.dragStartIndex = colorIndex;
                    },
                    drop(variantIndex, colorIndex) {
                        if (this.dragStartIndex !== null) {
                            const colors = this.variants[variantIndex].colors;
                            const [movedItem] = colors.splice(this.dragStartIndex, 1);
                            colors.splice(colorIndex, 0, movedItem);
                            this.dragStartIndex = null;
                        }
                    },
                    openSpecialPriceDialog(index) {
                        this.currentRowIndex = index;
                        this.showSpecialPriceDialog = true;
                    },
                    saveSpecialPrice(price) {
                        this.specialPrices[this.currentRowIndex] = {
                            price: price,
                            startDate: '',
                            endDate: ''
                        };
                        this.showSpecialPriceDialog = false;
                    },
                    deleteSpecialPrice(index) {
                        delete this.specialPrices[index];
                    }
                }">
                    <h2 class="text-lg font-medium text-gray-900 mb-2">Price, Stock & Variants</h2>
                    <p class="text-sm text-gray-500 mb-4">You can add variants to a product that has more than one option, such as size or color.</p>

                    <!-- Add Variation Button -->
                    <button @click="addVariant()"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded text-sm text-gray-700 bg-white hover:bg-gray-50 mb-6">
                        + Add Variation(<span x-text="variantCount"></span>/<span x-text="maxVariants"></span>)
                    </button>

                    <!-- Variant Section -->
                    <template x-for="(variant, index) in variants" :key="index">
                        <div class="bg-gray-50 p-6 rounded-lg mb-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-gray-700" x-text="'Variant' + (index + 1)"></h3>
                                <button @click="variants.splice(index, 1); variantCount--" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-7 7-7-7"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block mb-1">
                                        <span class="text-red-500">*</span> Variant Name
                                    </label>
                                    <input type="text"
                                           x-model="variant.name"
                                           class="block w-full border border-gray-300 rounded px-3 py-2"
                                           placeholder="Color">
                                </div>

                                <!-- Color Input Fields -->
                                <div class="space-y-3">
                                    <template x-for="(color, colorIndex) in variant.colors" :key="colorIndex">
                                        <div>
                                            <div class="flex items-center space-x-2"
                                                 draggable="true"
                                                 @dragstart="dragStart(colorIndex)"
                                                 @dragover.prevent
                                                 @drop.prevent="drop(index, colorIndex)">
                                                <input type="text"
                                                       x-model="variant.colors[colorIndex]"
                                                       @input="if(colorIndex === variant.colors.length - 1) addColor(index)"
                                                       class="flex-1 border border-gray-300 rounded px-3 py-2"
                                                       placeholder="red">
                                                <button class="text-gray-400 hover:text-gray-600" @click="variant.colors = variant.colors.filter((_, i) => i !== colorIndex)">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-7 7-7-7"></path>
                                                    </svg>
                                                </button>
                                                <button class="text-gray-400 hover:text-gray-600 cursor-move">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Price & Stock Section -->
                    <div class="mt-4">
                        <div class="mb-4">
                            <span class="text-red-500">*</span> Price & Stock
                        </div>

                        <!-- Top Input Fields -->
                        {{-- <div class="flex space-x-4 mb-4">
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">Rs.</span>
                                <input type="text" class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded">
                            </div>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">Rs.</span>
                                <input type="text" class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded">
                            </div>
                            <input type="text" class="px-3 py-2 border border-gray-300 rounded" placeholder="Stock">
                            <div class="relative">
                                <input type="text" class="pr-16 pl-3 py-2 border border-gray-300 rounded" placeholder="SellerSKU">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">0/200</span>
                            </div>
                            <button class="px-4 py-2 bg-orange-500 text-white rounded hover:bg-orange-600">
                                Apply to All
                            </button>
                        </div> --}}

                        <!-- Price & Stock Table -->
                        <div class="bg-white">
                            <div class="grid grid-cols-5 gap-4 py-3 px-4 bg-gray-50 border-y border-gray-200">
                                <div class="flex items-center">
                                    <span x-text="variants[0]?.name || 'Color'"></span>
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                                <div><span class="text-red-500">*</span> Price</div>
                                <div>Special Price</div>
                                <div>Stock</div>
                                <div>SellerSKU</div>
                            </div>

                            <!-- Dynamic Rows Based on Colors -->
                            <template x-for="(color, index) in variants[0]?.colors || []" :key="index">
                                <div class="grid grid-cols-5 gap-4 py-3 px-4 border-b border-gray-200">
                                    <div class="text-gray-700" x-text="color"></div>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">Rs.</span>
                                        <input type="text" class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded">
                                    </div>
                                    <div>
                                        <template x-if="!specialPrices[index]">
                                            <button @click="openSpecialPriceDialog(index)"
                                                    type="button"
                                                    class="px-3 py-1.5 border border-blue-600 text-blue-600 hover:bg-blue-50 rounded text-sm">
                                                Add
                                            </button>
                                        </template>
                                        <template x-if="specialPrices[index]">
                                            <div class="flex items-center space-x-2">
                                                <span class="text-sm" x-text="'Rs. ' + specialPrices[index].price"></span>
                                                <button @click="openSpecialPriceDialog(index)"
                                                        type="button"
                                                        class="text-blue-600 hover:text-blue-700">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                    </svg>
                                                </button>
                                                <button @click="deleteSpecialPrice(index)"
                                                        type="button"
                                                        class="text-red-600 hover:text-red-700">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-7 7-7-7"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                    <div>
                                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded">
                                    </div>
                                    <div class="relative">
                                        <input type="text" class="w-full pr-16 pl-3 py-2 border border-gray-300 rounded" placeholder="Seller SKU">
                                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">0/200</span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Special Price Dialog -->
                    <div x-show="showSpecialPriceDialog"
                         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
                         @click.self="showSpecialPriceDialog = false">
                        <div class="bg-white rounded-lg p-6 w-[500px]" x-data="{ price: '' }">
                            <h3 class="text-lg font-medium mb-4">Special Price</h3>

                            <!-- Special Price Input -->
                            <div class="mb-4">
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">Rs.</span>
                                    <input type="text"
                                           x-model="price"
                                           class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded">
                                </div>
                                <p class="text-sm text-gray-500 mt-1">
                                    Sales Price for a Product for Promotion. The rate of the special price to the price must be between 0.05 and 1.00
                                </p>
                            </div>

                            <!-- Promotion Date -->
                            <div class="mb-6" x-data="{ showDatePicker: false, dateRange: { start: '', end: '' } }">
                                <label class="block mb-2">Promotion Date</label>
                                <div class="mb-2">
                                    <button @click="showDatePicker = !showDatePicker"
                                            type="button"
                                            class="w-full px-3 py-2 border border-gray-300 rounded text-left flex items-center justify-between">
                                        <span x-text="dateRange.start && dateRange.end ? `${dateRange.start} - ${dateRange.end}` : 'Set Date'">Set Date</span>
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div x-show="showDatePicker" class="relative">
                                    <div class="flex items-center space-x-2">
                                        <div class="relative flex-1">
                                            <input type="date"
                                                   x-model="dateRange.start"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded"
                                                   placeholder="Start Date">
                                        </div>
                                        <div class="relative flex-1">
                                            <input type="date"
                                                   x-model="dateRange.end"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded"
                                                   placeholder="End Date">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex justify-end space-x-2">
                                <button @click="showSpecialPriceDialog = false"
                                        type="button"
                                        class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-50">
                                    Cancel
                                </button>
                                <button @click="saveSpecialPrice(price)"
                                        type="button"
                                        class="px-4 py-2 bg-orange-500 text-white rounded hover:bg-orange-600">
                                    OK
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    {{-- <div class="flex justify-end space-x-4 mt-6">
                        <button class="px-4 py-2 border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button class="px-4 py-2 bg-indigo-600 text-white rounded text-sm hover:bg-indigo-700">
                            Create Product
                        </button>
                    </div> --}}
                </div>
            </div>

            <!-- Shipping -->
            <div x-show="activeTab === 'shipping'" class="space-y-6 bg-white rounded-lg shadow p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Package Dimensions (cm)
                            <span class="text-gray-500 text-xs">(Optional)</span>
                        </label>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label for="package_length" class="sr-only">Length</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number"
                                           name="package_length"
                                           id="package_length"
                                           step="0.01"
                                           min="0"
                                           placeholder="Length"
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>
                            </div>
                            <div>
                                <label for="package_width" class="sr-only">Width</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number"
                                           name="package_width"
                                           id="package_width"
                                           step="0.01"
                                           min="0"
                                           placeholder="Width"
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>
                            </div>
                            <div>
                                <label for="package_height" class="sr-only">Height</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number"
                                           name="package_height"
                                           id="package_height"
                                           step="0.01"
                                           min="0"
                                           placeholder="Height"
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="package_weight" class="block text-sm font-medium text-gray-700">
                            Package Weight (kg) (Optional)
                        </label>
                        <input type="number"
                               name="package_weight"
                               min="0"
                               step="0.01"
                               class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                               placeholder="Enter package weight">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Dangerous Goods (Optional)</label>
                        <select name="dangerous_goods" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="">None</option>
                            <option value="flammable">Flammable</option>
                            <option value="explosive">Explosive</option>
                            <option value="corrosive">Corrosive</option>
                            <option value="toxic">Toxic</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end space-x-3 sticky bottom-0 bg-white p-4 border-t border-gray-200 shadow-lg">
            <a href="{{ route('admin.products.index') }}"
               class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Cancel
            </a>
            <button type="submit"
                    class="inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Create Product
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: '.tinymce-editor',
        plugins: 'lists textcolor code',
        menubar: false,
        toolbar: 'fontfamily fontsize | bold italic strikethrough | forecolor backcolor | alignleft aligncenter alignright | bullist numlist | code',
        toolbar_mode: 'wrap',
        statusbar: false,
        branding: false,
        font_family_formats: 'Arial=arial; Comic Sans MS=comic sans ms; Courier New=courier new; Georgia=georgia; Tahoma=tahoma; Times New Roman=times new roman; Trebuchet MS=trebuchet ms; Verdana=verdana',
        font_size_formats: '8pt 10pt 12pt 14pt 16pt 18pt 24pt',
        height: 500,
        content_style: `
            body {
                font-family: Arial, sans-serif;
                font-size: 14px;
                line-height: 1.6;
                color: #333;
                padding: 1rem;
            }
        `
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    const imageInput = document.getElementById('images');
    const previewContainer = document.getElementById('image-preview');
    const form = document.querySelector('form');

    function generateSlug(text) {
        return text.toString().toLowerCase()
            .replace(/\s+/g, '-')
            .replace(/[^\w\-]+/g, '')
            .replace(/\-\-+/g, '-')
            .replace(/^[-]+/, '')
            .replace(/-+$/, '');
    }

    nameInput.addEventListener('input', function() {
        slugInput.value = generateSlug(this.value);
    });

    // Form submission validation
    form.addEventListener('submit', function(e) {
        const files = imageInput.files;
        if (files.length === 0) {
            e.preventDefault();
            alert('Please select at least one image for the product.');
            return false;
        }
    });

    imageInput.addEventListener('change', handleImagePreview);

    function handleImagePreview(event) {
        previewContainer.innerHTML = '';
        const files = event.target.files;

        Array.from(files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.createElement('div');
                preview.className = 'relative group';
                preview.innerHTML = `
                    <img src="${e.target.result}" alt="Preview" class="w-full h-48 object-cover rounded-lg">
                    <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-lg">
                        <button type="button" class="text-white hover:text-red-500" onclick="this.closest('.relative').remove()">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </div>
                `;
                previewContainer.appendChild(preview);
            }
            reader.readAsDataURL(file);
        });
    }

    // Drag and drop functionality
    const dropZone = document.querySelector('.border-dashed');

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        dropZone.classList.add('border-indigo-500', 'bg-indigo-50');
    }

    function unhighlight(e) {
        dropZone.classList.remove('border-indigo-500', 'bg-indigo-50');
    }

    dropZone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        imageInput.files = files;
        handleImagePreview({target: {files}});
    }
});
</script>
@endpush
@endsection
