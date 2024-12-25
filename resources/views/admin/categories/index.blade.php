<!-- resources/views/admin/categories/index.blade.php -->
@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-xl shadow-sm">
        <!-- Header -->
        <div class="p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-800">Category Management</h1>
                    <p class="mt-1 text-sm text-gray-500">Organize and manage your product categories efficiently</p>
                </div>
                <a href="{{ route('admin.categories.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i>
                    Add New Category
                </a>
            </div>

            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mt-6">
                <div class="p-4 bg-gray-50 rounded-lg flex items-center space-x-3">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <i class="fas fa-layer-group text-blue-500"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-semibold text-gray-800">{{ $totalCategories }}</div>
                        <div class="text-sm text-gray-500">Total Categories</div>
                    </div>
                </div>

                <div class="p-4 bg-gray-50 rounded-lg flex items-center space-x-3">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <i class="fas fa-check-circle text-green-500"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-semibold text-gray-800">{{ $activeCategories }}</div>
                        <div class="text-sm text-gray-500">Active</div>
                    </div>
                </div>

                <div class="p-4 bg-gray-50 rounded-lg flex items-center space-x-3">
                    <div class="p-3 bg-red-100 rounded-lg">
                        <i class="fas fa-times-circle text-red-500"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-semibold text-gray-800">{{ $inactiveCategories }}</div>
                        <div class="text-sm text-gray-500">Inactive</div>
                    </div>
                </div>

                <div class="p-4 bg-gray-50 rounded-lg flex items-center space-x-3">
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <i class="fas fa-folder text-yellow-500"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-semibold text-gray-800">{{ $parentCategories }}</div>
                        <div class="text-sm text-gray-500">Parent Categories</div>
                    </div>
                </div>

                <div class="p-4 bg-gray-50 rounded-lg flex items-center space-x-3">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <i class="fas fa-code-branch text-purple-500"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-semibold text-gray-800">{{ $subCategories }}</div>
                        <div class="text-sm text-gray-500">Sub Categories</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="p-6 border-t border-gray-100">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <!-- Search Box -->
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" 
                           id="searchInput"
                           placeholder="Search categories..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Filter Actions -->
                <div class="flex items-center space-x-2">
                    <div class="relative">
                        <select id="categoryFilter" 
                                class="appearance-none w-44 pl-10 pr-10 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white cursor-pointer">
                            <option value="all">All Categories</option>
                            <option value="active">Active Only</option>
                            <option value="inactive">Inactive Only</option>
                            <option value="parent">Parent Only</option>
                            <option value="sub">Sub Only</option>
                        </select>
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-filter text-gray-400"></i>
                        </div>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-400"></i>
                        </div>
                    </div>

                    <button onclick="expandAll()" 
                            class="inline-flex items-center px-3 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-expand text-gray-400 mr-2"></i>
                        Expand All
                    </button>

                    <button onclick="collapseAll()" 
                            class="inline-flex items-center px-3 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-compress text-gray-400 mr-2"></i>
                        Collapse All
                    </button>
                </div>
            </div>
        </div>

        <!-- Categories List -->
        <div class="p-6 border-t border-gray-100">
            <div class="space-y-2">
                @foreach($categories as $category)
                    @include('admin.categories.partials.category-item', ['category' => $category, 'level' => 0])
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .category-toggle.expanded i {
        transform: rotate(90deg);
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const categoryItems = document.querySelectorAll('.category-item');

    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        
        categoryItems.forEach(item => {
            const categoryName = item.querySelector('span').textContent.toLowerCase();
            const shouldShow = categoryName.includes(searchTerm);
            item.style.display = shouldShow ? 'block' : 'none';
        });
    });

    // Category toggle functionality
    const toggleButtons = document.querySelectorAll('.category-toggle');

    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const categoryItem = this.closest('.category-item');
            const childrenContainer = categoryItem.querySelector('.category-children');
            
            if (childrenContainer) {
                const isHidden = childrenContainer.classList.contains('hidden');
                childrenContainer.classList.toggle('hidden');
                this.classList.toggle('expanded');
            }
        });
    });

    // Expand/Collapse all functionality
    window.expandAll = function() {
        document.querySelectorAll('.category-children').forEach(container => {
            container.classList.remove('hidden');
        });
        document.querySelectorAll('.category-toggle').forEach(button => {
            button.classList.add('expanded');
        });
    }

    window.collapseAll = function() {
        document.querySelectorAll('.category-children').forEach(container => {
            container.classList.add('hidden');
        });
        document.querySelectorAll('.category-toggle').forEach(button => {
            button.classList.remove('expanded');
        });
    }

    // Category filter functionality
    const categoryFilter = document.getElementById('categoryFilter');
    
    categoryFilter.addEventListener('change', function() {
        const filterValue = this.value;
        
        categoryItems.forEach(item => {
            const level = parseInt(item.dataset.level);
            const isActive = item.querySelector('.bg-green-100') !== null;
            
            let shouldShow = true;
            
            switch(filterValue) {
                case 'active':
                    shouldShow = isActive;
                    break;
                case 'inactive':
                    shouldShow = !isActive;
                    break;
                case 'parent':
                    shouldShow = level === 0;
                    break;
                case 'sub':
                    shouldShow = level > 0;
                    break;
            }
            
            item.style.display = shouldShow ? 'block' : 'none';
        });
    });
});
</script>
@endpush
@endsection