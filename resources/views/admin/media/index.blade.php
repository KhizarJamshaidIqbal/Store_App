@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-4" x-data="{
    view: 'grid',
    searchQuery: '',
    selectedType: 'all',
    selectedSize: 'all',
    selectedDate: 'all',
    showFilters: false,
    sortBy: 'newest',
    selectedItems: [],

    filterMedia(item) {
        const name = item.getAttribute('data-name').toLowerCase();
        const type = item.getAttribute('data-mime-type').toLowerCase();
        const size = parseInt(item.getAttribute('data-size'));
        const date = item.getAttribute('data-created-at');

        const matchesSearch = name.includes(this.searchQuery.toLowerCase());
        const matchesType = this.selectedType === 'all' || type.startsWith(this.selectedType);
        const matchesSize = this.selectedSize === 'all' || this.checkSize(size, this.selectedSize);
        const matchesDate = this.selectedDate === 'all' || this.checkDate(date, this.selectedDate);

        return matchesSearch && matchesType && matchesSize && matchesDate;
    },

    checkSize(size, filter) {
        const kb = size / 1024;
        switch(filter) {
            case 'small': return kb < 500;
            case 'medium': return kb >= 500 && kb < 2048;
            case 'large': return kb >= 2048;
            default: return true;
        }
    },

    checkDate(date, filter) {
        const now = new Date();
        const itemDate = new Date(date);
        const diff = now - itemDate;
        const days = diff / (1000 * 60 * 60 * 24);

        switch(filter) {
            case 'today': return days < 1;
            case 'week': return days < 7;
            case 'month': return days < 30;
            default: return true;
        }
    }
}">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Media Library</h1>
                <p class="mt-2 text-gray-600">Manage your uploaded files and images</p>
            </div>
            <div class="flex items-center gap-3">
                <button @click="showFilters = !showFilters"
                        class="px-4 py-2 bg-white text-gray-700 rounded-lg border border-gray-200 hover:bg-gray-50 transition flex items-center gap-2">
                    <i class="fas fa-filter"></i>
                    <span>Filters</span>
                    <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full text-xs"
                          x-show="selectedType !== 'all' || selectedSize !== 'all' || selectedDate !== 'all'">
                        Active
                    </span>
                </button>

                <a href="{{ route('admin.media.create') }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                    <i class="fas fa-plus"></i>
                    Upload Files
                </a>
            </div>
        </div>
    </div>

    <!-- Add this right after the header section -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-200 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-200 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Filters Panel -->
    <div x-show="showFilters"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <div class="relative">
                    <input type="text"
                           x-model="searchQuery"
                           class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Search files...">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
            </div>

            <!-- File Type Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">File Type</label>
                <select x-model="selectedType"
                        class="w-full border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="all">All Types</option>
                    <option value="image/">Images</option>
                    <option value="video/">Videos</option>
                    <option value="application/pdf">PDFs</option>
                    <option value="application/">Documents</option>
                </select>
            </div>

            <!-- Size Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">File Size</label>
                <select x-model="selectedSize"
                        class="w-full border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="all">All Sizes</option>
                    <option value="small">Small (<500KB)</option>
                    <option value="medium">Medium (500KB-2MB)</option>
                    <option value="large">Large (>2MB)</option>
                </select>
            </div>

            <!-- Date Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Upload Date</label>
                <select x-model="selectedDate"
                        class="w-full border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="all">All Time</option>
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                </select>
            </div>
        </div>

        <!-- View Toggle & Sort -->
        <div class="flex justify-between items-center mt-6 pt-6 border-t border-gray-100">
            <div class="flex items-center gap-4">
                <button @click="view = 'grid'"
                        :class="{'text-blue-600': view === 'grid'}"
                        class="p-2 hover:bg-gray-100 rounded-lg transition">
                    <i class="fas fa-grid-2 text-lg"></i>
                </button>
                <button @click="view = 'list'"
                        :class="{'text-blue-600': view === 'list'}"
                        class="p-2 hover:bg-gray-100 rounded-lg transition">
                    <i class="fas fa-list text-lg"></i>
                </button>
            </div>

            <select x-model="sortBy"
                    class="border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="newest">Newest First</option>
                <option value="oldest">Oldest First</option>
                <option value="name">Name (A-Z)</option>
                <option value="size">Size</option>
            </select>
        </div>
    </div>

    <!-- Media Content -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        @if($media->isEmpty())
            <!-- Empty State -->
            <div class="text-center py-12">
                <i class="fas fa-images text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No files uploaded yet</h3>
                <p class="text-gray-600 mb-4">Get started by uploading your first file</p>
                <a href="{{ route('admin.media.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-plus"></i>
                    Upload Files
                </a>
            </div>
        @else
            <!-- Grid View -->
            <div x-show="view === 'grid'"
                 class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($media as $item)
                    <div x-show="filterMedia($el)"
                         data-name="{{ $item->name }}"
                         data-mime-type="{{ $item->mime_type }}"
                         data-size="{{ $item->size }}"
                         data-created-at="{{ $item->created_at }}"
                         class="relative group bg-gray-50 rounded-xl overflow-hidden border border-gray-100 hover:border-blue-500 transition-all duration-300 hover:shadow-md">
                        <!-- Selection Overlay -->
                        <div class="absolute top-2 left-2 z-10">
                            <input type="checkbox"
                                   x-model="selectedItems"
                                   :value="{{ $item->id }}"
                                   class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </div>

                        <!-- File Preview -->
                        <div class="aspect-square">
                            @if(Str::startsWith($item->mime_type, 'image/'))
                                <img src="{{ Storage::url($item->path) }}"
                                     alt="{{ $item->name }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                    <i class="fas fa-file-alt text-4xl text-gray-400"></i>
                                </div>
                            @endif
                        </div>

                        <!-- File Info -->
                        <div class="p-3 bg-white border-t border-gray-100">
                            <p class="text-sm font-medium truncate" title="{{ $item->name }}">
                                {{ $item->name }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ number_format($item->size / 1024, 2) }} KB
                            </p>
                        </div>

                        <!-- Hover Actions -->
                        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300">
                            <a href="{{ Storage::url($item->path) }}"
                               target="_blank"
                               class="p-2 bg-white/10 hover:bg-white/20 rounded-lg text-white transition">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button class="p-2 bg-white/10 hover:bg-white/20 rounded-lg text-white transition"
                                    @click="$clipboard('{{ Storage::url($item->path) }}')">
                                <i class="fas fa-link"></i>
                            </button>
                            <form action="{{ route('admin.media.destroy', $item) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="p-2 bg-white/10 hover:bg-red-500/50 rounded-lg text-white transition"
                                        onclick="return confirm('Are you sure you want to delete this file?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- List View -->
            <div x-show="view === 'list'" class="divide-y divide-gray-100">
                @foreach($media as $item)
                    <div x-show="filterMedia($el)"
                         data-name="{{ $item->name }}"
                         data-mime-type="{{ $item->mime_type }}"
                         data-size="{{ $item->size }}"
                         data-created-at="{{ $item->created_at }}"
                         class="flex items-center py-3 hover:bg-gray-50 transition-colors">
                        <input type="checkbox"
                               x-model="selectedItems"
                               :value="{{ $item->id }}"
                               class="ml-4 w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">

                        <div class="flex-1 flex items-center gap-4 px-4">
                            <!-- File Icon/Preview -->
                            <div class="w-10 h-10">
                                @if(Str::startsWith($item->mime_type, 'image/'))
                                    <img src="{{ Storage::url($item->path) }}"
                                         alt="{{ $item->name }}"
                                         class="w-full h-full object-cover rounded">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-100 rounded">
                                        <i class="fas fa-file-alt text-gray-400"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- File Info -->
                            <div class="flex-1">
                                <p class="text-sm font-medium">{{ $item->name }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ number_format($item->size / 1024, 2) }} KB ·
                                    {{ $item->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-2 px-4">
                            <a href="{{ Storage::url($item->path) }}"
                               target="_blank"
                               class="p-2 text-gray-400 hover:text-gray-600 transition">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button class="p-2 text-gray-400 hover:text-gray-600 transition"
                                    @click="$clipboard('{{ Storage::url($item->path) }}')">
                                <i class="fas fa-link"></i>
                            </button>
                            <form action="{{ route('admin.media.destroy', $item) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="p-2 text-gray-400 hover:text-red-500 transition"
                                        onclick="return confirm('Are you sure you want to delete this file?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Bulk Actions -->
            <div x-show="selectedItems.length > 0"
                 class="fixed bottom-0 inset-x-0 bg-white border-t border-gray-200 p-4 flex items-center justify-between"
                 x-transition>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-600">
                        <span x-text="selectedItems.length"></span> items selected
                    </span>
                    <button @click="selectedItems = []"
                            class="text-sm text-gray-600 hover:text-gray-800">
                        Clear
                    </button>
                </div>
                <div class="flex items-center gap-2">
                    <form action="{{ route('admin.media.bulk-destroy') }}"
                          method="POST"
                          @submit.prevent="
                            if (!confirm('Are you sure you want to delete these files?')) return;
                            $el.submit();
                          ">
                        @csrf
                        <template x-for="id in selectedItems" :key="id">
                            <input type="hidden" name="ids[]" :value="id">
                        </template>
                        <button type="submit"
                                class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition flex items-center gap-2">
                            <i class="fas fa-trash"></i>
                            Delete Selected
                        </button>
                    </form>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $media->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Add clipboard functionality
    function $clipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            // You could add a toast notification here
            alert('URL copied to clipboard!');
        });
    }
</script>
@endpush
