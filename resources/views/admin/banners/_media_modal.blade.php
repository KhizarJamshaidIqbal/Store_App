<!-- Media Selection Modal -->
<div x-show="showMediaModal"
     class="fixed inset-0 z-50 overflow-hidden"
     x-data="{
         mediaItems: [],
         async fetchMedia() {
             const response = await fetch(`/admin/media/list?type=${this.filterType}&search=${this.searchQuery}`);
             this.mediaItems = await response.json();
         }
     }"
     @show-modal.window="fetchMedia()"
     x-init="fetchMedia()"
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
                                    <i class="fas fa-th-large"></i>
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
                                       x-model.debounce.300ms="searchQuery"
                                       @input="fetchMedia()"
                                       placeholder="Search media..."
                                       class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>

                            <!-- Filter -->
                            <select x-model="filterType"
                                    @change="fetchMedia()"
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
                    <!-- Grid View -->
                    <div x-show="view === 'grid'"
                         class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 max-h-[60vh] overflow-y-auto">
                        <template x-for="media in mediaItems" :key="media.id">
                            <div class="relative group cursor-pointer bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-all duration-200"
                                 @click="selectMedia(media.path, media.url)">
                                <img :src="media.url"
                                     :alt="media.name"
                                     class="w-full aspect-square object-cover">

                                <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                                    <div class="transform scale-0 group-hover:scale-100 transition-transform duration-200">
                                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                                            <i class="fas fa-plus text-blue-600"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-2 border-t border-gray-100">
                                    <p class="text-xs font-medium truncate" x-text="media.name"></p>
                                    <p class="text-xs text-gray-500" x-text="media.size"></p>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- List View -->
                    <div x-show="view === 'list'"
                         class="max-h-[60vh] overflow-y-auto">
                        <div class="bg-white rounded-lg shadow overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Preview</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Size</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <template x-for="media in mediaItems" :key="media.id">
                                        <tr class="hover:bg-gray-50 cursor-pointer"
                                            @click="selectMedia(media.path, media.url)">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <img :src="media.url" class="h-10 w-10 object-cover rounded">
                                            </td>
                                            <td class="px-6 py-4" x-text="media.name"></td>
                                            <td class="px-6 py-4" x-text="media.type"></td>
                                            <td class="px-6 py-4" x-text="media.size"></td>
                                            <td class="px-6 py-4" x-text="media.date"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
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
