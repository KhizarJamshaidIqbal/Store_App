@props(['categories', 'selectedCategories' => []])

<div
    x-data="{
        selectedCategory: @if($selectedCategories && count($selectedCategories) > 0)
            @json($selectedCategories[0])
        @else
            null
        @endif,
        isOpen: false,
        search: '',
        categories: @json($categories),

        init() {
            this.$watch('selectedCategory', value => {
                if (value) {
                    this.$refs.hiddenInput.value = value.id;
                } else {
                    this.$refs.hiddenInput.value = '';
                }
            });

            // Set initial value
            if (this.selectedCategory) {
                this.$refs.hiddenInput.value = this.selectedCategory.id;
            }
        },

        selectCategory(category) {
            this.selectedCategory = category;
            this.isOpen = false;
            this.search = '';
        },

        removeCategory() {
            this.selectedCategory = null;
        },

        getFilteredCategories() {
            return this.categories.filter(category =>
                category.name.toLowerCase().includes(this.search.toLowerCase())
            );
        }
    }"
    class="relative"
>
    <!-- Hidden input -->
    <input
        type="hidden"
        name="category_id"
        x-ref="hiddenInput"
        :value="selectedCategory ? selectedCategory.id : ''"
        required
    >

    <!-- Selected category display -->
    <div
        @click="isOpen = true"
        class="relative w-full cursor-default rounded-lg bg-white py-2 pl-3 pr-10 text-left border focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-orange-300 sm:text-sm"
    >
        <template x-if="selectedCategory">
            <div class="flex items-center gap-1">
                <span x-text="selectedCategory.name" class="block truncate"></span>
                <button
                    @click.stop="removeCategory()"
                    class="text-gray-500 hover:text-gray-700"
                >
                    &times;
                </button>
            </div>
        </template>
        <template x-if="!selectedCategory">
            <span class="block truncate text-gray-500">Select category</span>
        </template>
    </div>

    <!-- Dropdown -->
    <div
        x-show="isOpen"
        @click.away="isOpen = false"
        class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
    >
        <!-- Search input -->
        <div class="px-3 py-2">
            <input
                x-model="search"
                type="search"
                class="w-full rounded-md border-0 bg-white py-1.5 pl-3 pr-10 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                placeholder="Search categories..."
                @click.stop
            >
        </div>

        <!-- Category list -->
        <template x-for="category in getFilteredCategories()" :key="category.id">
            <div
                @click="selectCategory(category)"
                class="relative cursor-default select-none py-2 pl-3 pr-9 hover:bg-indigo-600 hover:text-white"
                :class="{ 'bg-indigo-600 text-white': selectedCategory && selectedCategory.id === category.id }"
            >
                <span x-text="category.name" class="block truncate"></span>
            </div>
        </template>
    </div>
</div>

@push('styles')
<style>
.category-selector-input:focus {
    box-shadow: none;
    border-color: #e5e7eb;
}
</style>
@endpush
            </template>
        </div>

        <!-- Footer -->
        <div class="px-3 py-2 border-t bg-gray-50 flex justify-end space-x-2">
            <button
                @click="isOpen = false"
                type="button"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                Cancel
            </button>
            <button
                @click="isOpen = false"
                type="button"
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">
                Confirm
            </button>
        </div>
    </div>
</div>
