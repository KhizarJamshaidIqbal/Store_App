@extends('admin.layouts.app')

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
                        <label for="description" class="block text-sm font-medium text-gray-700">
                            Description <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="4" 
                                  required
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                  placeholder="Enter product description"></textarea>
                    </div>

                    <div class="col-span-2">
                        <label for="highlights" class="block text-sm font-medium text-gray-700">
                            Highlights
                            <span class="text-gray-500 text-xs">(Optional)</span>
                        </label>
                        <textarea name="highlights" 
                                  id="highlights" 
                                  rows="4" 
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                  placeholder="Enter key features or highlights of the product"></textarea>
                        <p class="mt-1 text-sm text-gray-500">Add key features to make your product stand out</p>
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
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700">
                            Regular Price <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" 
                                   name="price" 
                                   id="price" 
                                   required
                                   step="0.01" 
                                   min="0"
                                   placeholder="0.00"
                                   class="mt-1 block w-full pl-7 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="special_price" class="block text-sm font-medium text-gray-700">
                            Special Price
                            <span class="text-gray-500 text-xs">(Optional)</span>
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" 
                                   name="special_price" 
                                   id="special_price" 
                                   step="0.01" 
                                   min="0"
                                   placeholder="0.00"
                                   class="mt-1 block w-full pl-7 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Set a special discounted price</p>
                    </div>

                    <div>
                        <label for="stock" class="block text-sm font-medium text-gray-700">
                            Stock <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="stock" 
                               id="stock" 
                               required
                               min="0"
                               placeholder="Enter stock quantity"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
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
                            Package Weight (kg)
                            <span class="text-gray-500 text-xs">(Optional)</span>
                        </label>
                        <input type="number" 
                               name="package_weight" 
                               id="package_weight"
                               step="0.01"
                               min="0"
                               placeholder="Enter package weight"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="dangerous_goods" class="block text-sm font-medium text-gray-700">
                            Dangerous Goods <span class="text-red-500">*</span>
                        </label>
                        <select name="dangerous_goods" 
                                id="dangerous_goods" 
                                required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="none">None</option>
                            <option value="battery">Battery</option>
                            <option value="flammable">Flammable</option>
                            <option value="liquid">Liquid</option>
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
            .replace(/^-+/, '')
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
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
