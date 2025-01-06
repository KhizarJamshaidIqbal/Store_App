<!-- resources/views/admin/categories/index.blade.php -->
@extends('admin.layouts.app')

@section('content')
<div class="min-h-full">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Category Management</h1>
                <p class="text-gray-600 mt-1">Organize and manage your product categories efficiently</p>
            </div>
            <a href="{{ route('admin.categories.create') }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                <i class="fas fa-plus mr-2"></i> Add New Category
            </a>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 mb-6">
            <div class="bg-blue-50 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-layer-group text-blue-500 text-xl"></i>
                    <div class="ml-3">
                        <div class="text-2xl font-bold text-gray-800">{{ $totalCategories }}</div>
                        <div class="text-sm text-gray-600">Total Categories</div>
                    </div>
                </div>
            </div>

            <div class="bg-green-50 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                    <div class="ml-3">
                        <div class="text-2xl font-bold text-gray-800">{{ $activeCategories }}</div>
                        <div class="text-sm text-gray-600">Active</div>
                    </div>
                </div>
            </div>

            <div class="bg-red-50 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-times-circle text-red-500 text-xl"></i>
                    <div class="ml-3">
                        <div class="text-2xl font-bold text-gray-800">{{ $inactiveCategories }}</div>
                        <div class="text-sm text-gray-600">Inactive</div>
                    </div>
                </div>
            </div>

            <div class="bg-yellow-100 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-folder text-yellow-500 text-xl"></i>
                    <div class="ml-3">
                        <div class="text-2xl font-bold text-gray-800">{{ $parentCategories }}</div>
                        <div class="text-sm text-gray-600">Parent Categories</div>
                    </div>
                </div>
            </div>

            <div class="bg-purple-100 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-code-branch text-purple-500 text-xl"></i>
                    <div class="ml-3">
                        <div class="text-2xl font-bold text-gray-800">{{ $subCategories }}</div>
                        <div class="text-sm text-gray-600">Sub Categories</div>
                    </div>
                </div>
            </div>

            <a href="{{ route('admin.categories.trashed') }}" class="bg-gray-50 hover:bg-gray-100 rounded-lg p-4 flex items-center space-x-3 transition-colors duration-200 hover:animate-bounce">
                <div class="p-3 bg-white rounded-lg">
                    <i class="fas fa-trash-alt text-red-600"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-800">{{ $trashedCategories }}</div>
                    <div class="text-sm text-gray-600">Deleted</div>
                </div>
            </a>
        </div>

        <!-- Search and Filter -->
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6">
            <!-- Search Box -->
            <div class="relative flex-1 w-full">
                <input type="text"
                       id="searchInput"
                       placeholder="Search categories..."
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>

            <!-- Filter Actions -->
            <div class="flex items-center gap-3">
                <div class="relative">
                    <select id="categoryFilter"
                            class="appearance-none w-44 pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white cursor-pointer">
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
                        class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-expand text-gray-400 mr-2"></i>
                    Expand All
                </button>

                <button onclick="collapseAll()"
                        class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-compress text-gray-400 mr-2"></i>
                    Collapse All
                </button>
            </div>
        </div>

        <!-- Categories List -->
        <div class="space-y-3 min-h-[300px]">
            @foreach($categories as $category)
                @include('admin.categories.partials.category-item', ['category' => $category, 'level' => 0])
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $categories->links() }}
        </div>
    </div>
</div>
@endsection

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
