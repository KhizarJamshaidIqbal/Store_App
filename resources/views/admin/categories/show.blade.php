@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-xl shadow-sm">
        <!-- Header -->
        <div class="p-6 border-b border-gray-100">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-800">Category Details</h1>
                    <p class="mt-1 text-sm text-gray-500">View category information</p>
                </div>
                <div class="space-x-2">
                    <a href="{{ route('admin.categories.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Categories
                    </a>
                    <a href="{{ route('admin.categories.edit', $category) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors duration-200">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Category
                    </a>
                </div>
            </div>
        </div>

        <!-- Category Information -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h2 class="text-lg font-medium text-gray-800 mb-4">Basic Information</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Name</label>
                            <p class="mt-1 text-gray-800">{{ $category->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Slug</label>
                            <p class="mt-1 text-gray-800">{{ $category->slug }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Description</label>
                            <p class="mt-1 text-gray-800">{{ $category->description ?? 'No description available' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Meta Information -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h2 class="text-lg font-medium text-gray-800 mb-4">Meta Information</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Created At</label>
                            <p class="mt-1 text-gray-800">{{ $category->created_at->format('F j, Y g:i A') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Last Updated</label>
                            <p class="mt-1 text-gray-800">{{ $category->updated_at->format('F j, Y g:i A') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status</label>
                            <p class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category->deleted_at ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $category->deleted_at ? 'Deleted' : 'Active' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Products Count -->
                <div class="bg-gray-50 p-6 rounded-lg md:col-span-2">
                    <h2 class="text-lg font-medium text-gray-800 mb-4">Related Products</h2>
                    <div class="flex items-center space-x-2">
                        <span class="text-3xl font-bold text-gray-800">{{ $category->products_count ?? 0 }}</span>
                        <span class="text-gray-500">products in this category</span>
                    </div>
                    @if(($category->products_count ?? 0) > 0)
                        <a href="{{ route('admin.products.index', ['category' => $category->id]) }}" 
                           class="mt-4 inline-flex items-center text-sm text-blue-600 hover:text-blue-700">
                            <span>View all products</span>
                            <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
