@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-4" x-data="{
    showMediaModal: false,
    selectedMedia: ['{{ $banner->image_path }}'],
    previews: ['{{ Storage::url($banner->image_path) }}'],
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
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Edit Banner</h1>
            <p class="mt-2 text-gray-600">Update banner information</p>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.banners.update', $banner) }}"
              method="POST"
              class="bg-white rounded-lg shadow-sm p-6">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Title <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="title"
                       value="{{ old('title', $banner->title) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       required>
                @error('title')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Description
                </label>
                <textarea name="description"
                          rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description', $banner->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Media Selection -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Banner Images <span class="text-red-500">*</span>
                </label>

                <input type="hidden" name="image_paths" x-model="JSON.stringify(selectedMedia)" required>

                <!-- Selected Images Preview -->
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mb-4" x-show="previews.length > 0">
                    <template x-for="(preview, index) in previews" :key="index">
                        <div class="relative group">
                            <img :src="preview" class="w-full h-32 object-cover rounded-lg">
                            <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-lg flex items-center justify-center">
                                <button type="button" @click="removeMedia(index)"
                                        class="p-2 bg-red-500 text-white rounded-full hover:bg-red-600 transform hover:scale-110 transition-all duration-200">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Upload Button -->
                <button type="button"
                        @click="showMediaModal = true"
                        class="w-full py-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-blue-500 transition-colors duration-200 flex flex-col items-center justify-center gap-2 group">
                    <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                        <i class="fas fa-images text-2xl text-blue-500"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-600 group-hover:text-blue-600">Choose from Media Library</span>
                </button>
            </div>

            <!-- URL -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    URL
                </label>
                <input type="url"
                       name="url"
                       value="{{ old('url', $banner->url) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="https://">
                @error('url')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status & Order -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status
                    </label>
                    <select name="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="1" {{ old('status', $banner->status) ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status', $banner->status) ? '' : 'selected' }}>Inactive</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Order
                    </label>
                    <input type="number"
                           name="order"
                           value="{{ old('order', $banner->order) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <!-- Duration -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Start Date
                    </label>
                    <input type="date"
                           name="starts_at"
                           value="{{ old('starts_at', $banner->starts_at?->format('Y-m-d')) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        End Date
                    </label>
                    <input type="date"
                           name="ends_at"
                           value="{{ old('ends_at', $banner->ends_at?->format('Y-m-d')) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.banners.index') }}"
                   class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                    Cancel
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Update Banner
                </button>
            </div>
        </form>
    </div>

    <!-- Media Selection Modal -->
    @include('admin.banners._media_modal')
</div>
@endsection
