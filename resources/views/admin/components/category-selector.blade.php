@props(['categories'])

<div x-data="{
    search: '',
    selectedPath: [],
    isOpen: false,
    categories: @js($categories),
    
    init() {
        this.$watch('search', (value) => {
            if (value) {
                this.isOpen = true;
            }
        });
    },

    getFilteredCategories() {
        if (!this.search) return this.categories;
        return this.categories.filter(cat => 
            cat.name.toLowerCase().includes(this.search.toLowerCase())
        );
    },

    selectCategory(category) {
        this.selectedPath = this.getCategoryPath(category);
        this.$refs.input.value = this.selectedPath.map(c => c.name).join(' > ');
        this.$refs.categoryId.value = category.id;
        this.isOpen = false;
    },

    getCategoryPath(category) {
        let path = [category];
        let current = category;
        
        while (current.parent_id) {
            let parent = this.categories.find(c => c.id === current.parent_id);
            if (parent) {
                path.unshift(parent);
                current = parent;
            } else {
                break;
            }
        }
        
        return path;
    }
}" class="relative">
    <div class="mt-1 relative">
        <input type="text"
               x-ref="input"
               x-model="search"
               @focus="isOpen = true"
               @click="isOpen = true"
               class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
               placeholder="Search or select category">
        
        <input type="hidden" name="category_id" x-ref="categoryId">
    </div>

    <!-- Dropdown -->
    <div x-show="isOpen" 
         @click.away="isOpen = false"
         class="absolute z-10 mt-1 w-full bg-white shadow-lg rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto max-h-60">
        
        <!-- Search Results -->
        <div class="px-4 py-2">
            <input type="text" 
                   x-model="search"
                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                   placeholder="Search category...">
        </div>

        <!-- Category List -->
        <template x-for="category in getFilteredCategories()" :key="category.id">
            <div @click="selectCategory(category)"
                 class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-gray-100">
                <div class="flex items-center">
                    <span class="ml-3 block truncate" x-text="category.name"></span>
                </div>
            </div>
        </template>

        <!-- No Results -->
        <div x-show="getFilteredCategories().length === 0"
             class="text-gray-500 text-sm py-2 px-4">
            No categories found
        </div>
    </div>

    <!-- Selected Path Display -->
    <div x-show="selectedPath.length > 0" class="mt-2">
        <div class="flex items-center space-x-2 text-sm text-gray-600">
            <template x-for="(cat, index) in selectedPath" :key="cat.id">
                <div class="flex items-center">
                    <span x-text="cat.name"></span>
                    <template x-if="index < selectedPath.length - 1">
                        <svg class="h-4 w-4 mx-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </template>
                </div>
            </template>
        </div>
    </div>
</div>
