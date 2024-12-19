@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Add New Product</h1>
        <p class="mt-2 text-sm text-gray-600">Create a new product with all its details and specifications.</p>
    </div>

    <form action="{{ route('admin.products.store') }}" 
          method="POST" 
          enctype="multipart/form-data" 
          class="space-y-8">
        @csrf
        
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 gap-6">
                    <div class="col-span-1">
                        <label for="name" class="block text-sm font-medium text-gray-700">
                            Product Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <div class="col-span-1">
                        <label for="slug" class="block text-sm font-medium text-gray-700">
                            Product Slug
                            <span class="text-gray-500 text-xs">(Auto-generated)</span>
                        </label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <input type="text" 
                                   name="slug" 
                                   id="slug" 
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                   readonly>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Will be automatically generated from the product name</p>
                    </div>

                    <div class="col-span-1">
                        <label for="category_id" class="block text-sm font-medium text-gray-700">
                            Category <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <select name="category_id" 
                                    id="category_id" 
                                    required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-span-1">
                        <label for="description" class="block text-sm font-medium text-gray-700">
                            Description <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="4" 
                                  required
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                    </div>

                    <div class="col-span-1">
                        <label for="highlights" class="block text-sm font-medium text-gray-700">
                            Highlights
                        </label>
                        <textarea name="highlights" 
                                  id="highlights" 
                                  rows="4" 
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                  placeholder="Enter key features or highlights of the product"></textarea>
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700">
                            Package Dimensions (cm)
                            <span class="text-gray-500 text-xs">(Optional)</span>
                        </label>
                        <div class="mt-1 grid grid-cols-3 gap-3">
                            <div>
                                <input type="number" 
                                       name="package_length" 
                                       step="0.01"
                                       min="0"
                                       placeholder="Length"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <input type="number" 
                                       name="package_width" 
                                       step="0.01"
                                       min="0"
                                       placeholder="Width"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <input type="number" 
                                       name="package_height" 
                                       step="0.01"
                                       min="0"
                                       placeholder="Height"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                        </div>
                    </div>

                    <div class="col-span-1">
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

                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700">
                            Product Images <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
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
                        <div id="image-preview" class="mt-4 grid grid-cols-4 gap-4"></div>
                    </div>

                    <!-- Price & Stock -->
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
                                       class="mt-1 block w-full pl-7 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                        </div>

                        <div>
                            <label for="special_price" class="block text-sm font-medium text-gray-700">
                                Special Price
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
                                       class="mt-1 block w-full pl-7 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
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
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div class="col-span-1">
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
                    </div>

                    <!-- Shipping Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.products.index') }}" 
               class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Cancel
            </a>
            <button type="submit" 
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
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
                preview.className = 'relative aspect-w-1 aspect-h-1';
                preview.innerHTML = `
                    <img src="${e.target.result}" alt="Preview" class="object-cover rounded-lg">
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
        dropZone.classList.add('border-indigo-500');
    }

    function unhighlight(e) {
        dropZone.classList.remove('border-indigo-500');
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
