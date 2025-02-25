<!-- resources/views/admin/categories/create.blade.php -->
@extends('admin.layouts.app')
@section('content')
<div class="container mx-auto px-4" x-data="{
    showMediaModal: false,
    selectedMedia: [],
    previews: [],
    searchQuery: '',
    filterType: 'all',
    view: 'grid',

    selectMedia(path, url) {
        if (!this.selectedMedia.includes(path)) {
            this.selectedMedia.push(path);
            this.previews.push(url);
        }
    },

    removeMedia(index) {
        this.selectedMedia.splice(index, 1);
        this.previews.splice(index, 1);
    },

    clearSelection() {
        this.selectedMedia = [];
        this.previews = [];
    }
}">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm">
            <!-- Header -->
            <div class="p-6 border-b border-gray-100">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800">Add New Category</h2>
                        <p class="mt-1 text-sm text-gray-500">Create a new category for your products</p>
                    </div>
                    <a href="{{ route('admin.categories.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back
                    </a>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('admin.categories.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

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
                               value="{{ old('name') }}"
                               class="block w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                               placeholder="Enter category name">
                    </div>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category Image -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Category Image
                    </label>
                    <div class="mt-1 space-y-4">
                        <!-- Update hidden input to use name attribute only -->
                        <input type="hidden"
                               name="image"
                               :value="selectedMedia[0]">

                        <button type="button"
                                @click="showMediaModal = true"
                                class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors duration-200">
                            <i class="fas fa-image mr-2"></i>
                            Select Image
                        </button>

                        <!-- Preview selected image -->
                        <div class="mt-2" x-show="previews.length > 0">
                            <img :src="previews[0]"
                                 class="max-w-xs rounded-lg shadow-sm"
                                 alt="Selected image">
                            <button type="button"
                                    @click="clearSelection()"
                                    class="mt-2 text-sm text-red-600 hover:text-red-700">
                                Remove Image
                            </button>
                        </div>
                    </div>
                    @error('image')
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
                            <option value="">
                                <i class="fas fa-level-up-alt"></i> None (Top Level)
                            </option>
                            @foreach($categories->where('parent_id', null) as $parentCategory)
                                <option value="{{ $parentCategory->id }}"
                                        {{ old('parent_id') == $parentCategory->id ? 'selected' : '' }}>
                                    <i class="fas fa-folder"></i> {{ $parentCategory->name }}
                                </option>
                                @foreach($parentCategory->children as $childCategory)
                                    <option value="{{ $childCategory->id }}"
                                            {{ old('parent_id') == $childCategory->id ? 'selected' : '' }}>
                                        &nbsp;&nbsp;&nbsp;&nbsp;└─ {{ $childCategory->name }}
                                    </option>
                                    @foreach($childCategory->children as $grandChild)
                                        <option value="{{ $grandChild->id }}"
                                                {{ old('parent_id') == $grandChild->id ? 'selected' : '' }}>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└─ {{ $grandChild->name }}
                                        </option>
                                    @endforeach
                                @endforeach
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
                                  placeholder="Enter category description">{{ old('description') }}</textarea>
                    </div>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Toggle -->
                <div class="flex items-center space-x-3 pt-4">
                    <div class="flex items-center">
                        <label for="status" class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox"
                                   name="status"
                                   id="status"
                                   value="1"
                                   class="sr-only peer"
                                   {{ old('status', true) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-500"></div>
                            <span class="ml-3 text-sm font-medium text-gray-700">Active Status</span>
                        </label>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-100">
                    <button type="button"
                            onclick="window.location.href='{{ route('admin.categories.index') }}'"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        Create Category
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- Media Selection Modal -->
    <div x-show="showMediaModal"
         class="fixed inset-0 z-50 overflow-hidden"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <div class="absolute inset-0 bg-gray-500 bg-opacity-75 backdrop-blur-sm"></div>

        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-6xl"
                     @click.away="showMediaModal = false">

                    <!-- Modal Header -->
                    <div class="bg-white px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900">Select Media</h3>
                            <div class="flex items-center gap-4">
                                <!-- View Toggle -->
                                <div class="flex items-center gap-2 bg-gray-100 rounded-lg p-1">
                                    <button @click="view = 'grid'"
                                            :class="{'bg-white shadow': view === 'grid'}"
                                            class="p-2 rounded-lg transition-all duration-200">
                                        <i class="fas fa-grid-2"></i>
                                    </button>
                                    <button @click="view = 'list'"
                                            :class="{'bg-white shadow': view === 'list'}"
                                            class="p-2 rounded-lg transition-all duration-200">
                                        <i class="fas fa-list"></i>
                                    </button>
                                </div>

                                <!-- Search -->
                                <div class="relative">
                                    <input type="text"
                                           x-model="searchQuery"
                                           placeholder="Search media..."
                                           class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                                </div>

                                <!-- Filter -->
                                <select x-model="filterType"
                                        class="border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="all">All Types</option>
                                    <option value="image">Images</option>
                                    <option value="video">Videos</option>
                                    <option value="document">Documents</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="bg-gray-50 p-6">
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 max-h-[60vh] overflow-y-auto">
                            @foreach(\App\Models\Media::where('mime_type', 'like', 'image/%')->latest()->get() as $media)
                                <div class="relative group cursor-pointer bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-all duration-200"
                                     @click="selectMedia('{{ $media->path }}', '{{ Storage::url($media->path) }}')">
                                    <img src="{{ Storage::url($media->path) }}"
                                         alt="{{ $media->name }}"
                                         class="w-full aspect-square object-cover">

                                    <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                                        <div class="transform scale-0 group-hover:scale-100 transition-transform duration-200">
                                            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                                                <i class="fas fa-plus text-blue-600"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="p-2 border-t border-gray-100">
                                        <p class="text-xs font-medium truncate">{{ $media->name }}</p>
                                        <p class="text-xs text-gray-500">{{ number_format($media->size / 1024, 2) }} KB</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-600">
                                <span x-text="selectedMedia.length"></span> items selected
                            </span>
                            <button @click="clearSelection"
                                    x-show="selectedMedia.length > 0"
                                    class="text-sm text-red-600 hover:text-red-700">
                                Clear
                            </button>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.media.create') }}"
                               class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors duration-200">
                                Upload New Media
                            </a>
                            <button type="button"
                                    @click="showMediaModal = false"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors duration-200">
                                Done
                            </button>
                        </div>
                    </div>
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

@push('scripts')
<script>
    document.getElementById('image').addEventListener('change', function(e) {
        const preview = document.getElementById('image-preview');
        const file = e.target.files[0];

        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('hidden');
        } else {
            preview.src = '#';
            preview.classList.add('hidden');
        }
    });
</script>
@endpush
