<!-- resources/views/admin/categories/edit.blade.php -->
@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Left Column - Edit Form -->
            <div class="flex-grow">
                <div class="bg-white rounded-xl shadow-sm">
                    <!-- Header -->
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-2xl font-semibold text-gray-800">Edit Category</h2>
                                <p class="mt-1 text-sm text-gray-500">Update category information</p>
                            </div>
                            <a href="{{ route('admin.categories.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors duration-200">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Back
                            </a>
                        </div>
                    </div>

                    <!-- Form -->
                    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="p-6 space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <!-- Category Name -->
                        <div class="space-y-2">
                            <label for="name" class="block text-sm font-medium text-gray-700">
                                Category Name <span class="text-red-500">*</span>
                            </label>
                            <div class="relative rounded-lg">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-tag text-gray-400"></i>
                                </div>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       value="{{ old('name', $category->name) }}"
                                       class="block w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                                       placeholder="Enter category name">
                            </div>
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Parent Category -->
                        <div class="space-y-2">
                            <label for="parent_id" class="block text-sm font-medium text-gray-700">
                                Parent Category
                            </label>
                            <div class="relative rounded-lg">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-folder-tree text-gray-400"></i>
                                </div>
                                <select name="parent_id" 
                                        id="parent_id" 
                                        class="block w-full pl-10 pr-10 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('parent_id') border-red-500 @enderror">
                                    <option value="">None (Top Level)</option>
                                    @foreach($categories->where('parent_id', null) as $parentCategory)
                                        @if($parentCategory->id !== $category->id)
                                            <option value="{{ $parentCategory->id }}" 
                                                    {{ old('parent_id', $category->parent_id) == $parentCategory->id ? 'selected' : '' }}>
                                                {{ $parentCategory->name }}
                                            </option>
                                            @foreach($parentCategory->children as $childCategory)
                                                @if($childCategory->id !== $category->id)
                                                    <option value="{{ $childCategory->id }}" 
                                                            {{ old('parent_id', $category->parent_id) == $childCategory->id ? 'selected' : '' }}>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;└─ {{ $childCategory->name }}
                                                    </option>
                                                    @foreach($childCategory->children as $grandChild)
                                                        @if($grandChild->id !== $category->id)
                                                            <option value="{{ $grandChild->id }}" 
                                                                    {{ old('parent_id', $category->parent_id) == $grandChild->id ? 'selected' : '' }}>
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└─ {{ $grandChild->name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                            @error('parent_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="space-y-2">
                            <label for="description" class="block text-sm font-medium text-gray-700">
                                Description
                            </label>
                            <div class="relative rounded-lg">
                                <div class="absolute top-3 left-3 pointer-events-none">
                                    <i class="fas fa-align-left text-gray-400"></i>
                                </div>
                                <textarea name="description" 
                                          id="description" 
                                          rows="4" 
                                          class="block w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                                          placeholder="Enter category description">{{ old('description', $category->description) }}</textarea>
                            </div>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status Toggle -->
                        <div class="flex items-center space-x-3 pt-4">
                            <div class="flex items-center">
                                <label for="status" class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="status" value="0">
                                    <input type="checkbox" 
                                           name="status" 
                                           id="status" 
                                           value="1"
                                           class="sr-only peer" 
                                           {{ $category->status ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-500"></div>
                                    <span class="ml-3 text-sm font-medium text-gray-700">Active Status</span>
                                </label>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-100">
                            <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-save mr-2"></i>
                                Update Category
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Column - Category Information and Danger Zone -->
            <div class="lg:w-80">
                <!-- Category Information -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        Category Information
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-center text-sm">
                            <i class="fas fa-calendar-plus text-gray-400 w-5"></i>
                            <span class="text-gray-600 ml-2">Created:</span>
                            <span class="ml-auto text-gray-900">{{ $category->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <i class="fas fa-clock text-gray-400 w-5"></i>
                            <span class="text-gray-600 ml-2">Last Updated:</span>
                            <span class="ml-auto text-gray-900">{{ $category->updated_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <i class="fas fa-circle {{ $category->status ? 'text-green-500' : 'text-gray-400' }} w-5"></i>
                            <span class="text-gray-600 ml-2">Status:</span>
                            <span class="ml-auto">
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $category->status ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $category->status ? 'Active' : 'Inactive' }}
                                </span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Danger Zone -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-red-600 mb-4">
                        <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                        Danger Zone
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">
                        <i class="fas fa-exclamation-circle text-yellow-500 mr-2"></i>
                        Warning: Deleting this category will also delete all its subcategories. 
                        This action cannot be undone.
                    </p>
                    <form action="{{ route('admin.categories.destroy', $category->id) }}" 
                          method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this category? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors duration-200 flex items-center justify-center">
                            <i class="fas fa-trash-alt mr-2"></i>
                            Delete Category
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    /* Custom select styles */
    select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
    }
</style>
@endpush