@props(['categoriesJson'])

<div x-data="categorySelector({{ $categoriesJson }})" 
     x-init="init()"
     class="relative">
    <div class="mt-1 relative">
        <button type="button" 
                @click="toggleDropdown()"
                class="bg-white relative w-full border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-default focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500">
            <span class="block truncate" x-text="selectedPath || 'Select Category'"></span>
            <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </span>
        </button>

        <!-- Debug info -->
        <div x-show="isOpen" class="mt-2 text-xs text-gray-500">
            <div>Categories Count: <span x-text="categories.length"></span></div>
            <div>Filtered Count: <span x-text="filteredCategories.length"></span></div>
            <div x-show="!categories.length" class="text-red-500">No categories available</div>
        </div>

        <div x-show="isOpen" 
             @click.away="isOpen = false"
             class="absolute mt-1 w-full bg-white shadow-lg max-h-[32rem] rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm z-50">
            
            <!-- Search Box -->
            <div class="px-3 py-2 border-b">
                <input type="text" 
                       x-model="search"
                       @input.debounce.250ms="filterCategories()"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
                       placeholder="Search category...">
            </div>

            <!-- Category Columns -->
            <div class="grid grid-cols-4 divide-x divide-gray-200 min-h-[300px]">
                <!-- Level 1 -->
                <div class="overflow-y-auto">
                    <div class="px-2 py-2 border-b sticky top-0 bg-white">
                        <input type="text" 
                               x-model="search"
                               @input.debounce.250ms="filterCategories()"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
                               placeholder="Search root categories...">
                    </div>
                    <div class="px-2 py-2">
                        <template x-if="filteredCategories.length === 0">
                            <div class="text-sm text-gray-500 px-3 py-2">No categories found</div>
                        </template>
                        <template x-for="category in filteredCategories" :key="category.id">
                            <button @click="selectLevel1(category)"
                                    :class="{'bg-primary-50': category.id === selected.level1?.id}"
                                    class="w-full text-left px-3 py-2 rounded-md hover:bg-gray-100 focus:outline-none focus:bg-gray-100">
                                <span x-text="category.name"></span>
                                <span x-show="category.children_recursive?.length > 0" class="float-right text-gray-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </span>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Level 2 -->
                <div x-show="selected.level1" class="overflow-y-auto">
                    <div class="px-2 py-2 border-b sticky top-0 bg-white">
                        <input type="text" 
                               x-model="level2Search"
                               @input.debounce.250ms="filterLevel2Categories()"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
                               placeholder="Search subcategories...">
                    </div>
                    <div class="px-2 py-2">
                        <template x-if="filteredLevel2Categories.length === 0">
                            <div class="text-sm text-gray-500 px-3 py-2">No subcategories found</div>
                        </template>
                        <template x-for="category in filteredLevel2Categories" :key="category.id">
                            <button @click="selectLevel2(category)"
                                    :class="{'bg-primary-50': category.id === selected.level2?.id}"
                                    class="w-full text-left px-3 py-2 rounded-md hover:bg-gray-100 focus:outline-none focus:bg-gray-100">
                                <span x-text="category.name"></span>
                                <span x-show="category.children_recursive?.length > 0" class="float-right text-gray-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </span>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Level 3 -->
                <div x-show="selected.level2" class="overflow-y-auto">
                    <div class="px-2 py-2 border-b sticky top-0 bg-white">
                        <input type="text" 
                               x-model="level3Search"
                               @input.debounce.250ms="filterLevel3Categories()"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
                               placeholder="Search subcategories...">
                    </div>
                    <div class="px-2 py-2">
                        <template x-if="filteredLevel3Categories.length === 0">
                            <div class="text-sm text-gray-500 px-3 py-2">No subcategories found</div>
                        </template>
                        <template x-for="category in filteredLevel3Categories" :key="category.id">
                            <button @click="selectLevel3(category)"
                                    :class="{'bg-primary-50': category.id === selected.level3?.id}"
                                    class="w-full text-left px-3 py-2 rounded-md hover:bg-gray-100 focus:outline-none focus:bg-gray-100">
                                <span x-text="category.name"></span>
                                <span x-show="category.children_recursive?.length > 0" class="float-right text-gray-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </span>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Level 4 -->
                <div x-show="selected.level3" class="overflow-y-auto">
                    <div class="px-2 py-2 border-b sticky top-0 bg-white">
                        <input type="text" 
                               x-model="level4Search"
                               @input.debounce.250ms="filterLevel4Categories()"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
                               placeholder="Search subcategories...">
                    </div>
                    <div class="px-2 py-2">
                        <template x-if="filteredLevel4Categories.length === 0">
                            <div class="text-sm text-gray-500 px-3 py-2">No subcategories found</div>
                        </template>
                        <template x-for="category in filteredLevel4Categories" :key="category.id">
                            <button @click="selectLevel4(category)"
                                    :class="{'bg-primary-50': category.id === selected.level4?.id}"
                                    class="w-full text-left px-3 py-2 rounded-md hover:bg-gray-100 focus:outline-none focus:bg-gray-100">
                                <span x-text="category.name"></span>
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Current Selection -->
            <div x-show="selectedPath" class="px-3 py-2 border-t">
                <div class="text-xs font-medium text-gray-500 mb-1">Current selection:</div>
                <div class="flex items-center text-sm text-gray-700">
                    <span x-text="selectedPath"></span>
                    <button @click="clearSelection" class="ml-2 text-red-500 hover:text-red-700">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="px-3 py-2 border-t flex justify-end space-x-2">
                <button @click="isOpen = false" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Cancel
                </button>
                <button @click="confirmSelection" 
                        :disabled="!selectedPath"
                        :class="{'opacity-50 cursor-not-allowed': !selectedPath}"
                        class="px-4 py-2 text-sm font-medium text-white bg-primary-600 border border-transparent rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Confirm
                </button>
            </div>
        </div>
    </div>

    <!-- Hidden input for form submission -->
    <input type="hidden" 
           name="category_id" 
           x-model="selectedCategoryId"
           x-ref="categoryInput"
           @change="$dispatch('input', selectedCategoryId); formData.category_id = selectedCategoryId">
</div>

<script>
function categorySelector(dbCategories) {
    return {
        isOpen: false,
        search: '',
        level2Search: '',
        level3Search: '',
        level4Search: '',
        categories: [],
        filteredCategories: [],
        filteredLevel2Categories: [],
        filteredLevel3Categories: [],
        filteredLevel4Categories: [],
        selected: {
            level1: null,
            level2: null,
            level3: null,
            level4: null
        },
        selectedPath: '',
        selectedCategoryId: '',

        init() {
            this.categories = Array.isArray(dbCategories) ? dbCategories : JSON.parse(dbCategories || '[]');
            this.filterCategories();
            
            // Watch for changes in selectedCategoryId
            this.$watch('selectedCategoryId', value => {
                if (value) {
                    // Update form data
                    this.$el.dispatchEvent(new CustomEvent('category-selected', {
                        detail: value,
                        bubbles: true
                    }));
                }
            });
        },

        toggleDropdown() {
            this.isOpen = !this.isOpen;
            if (this.isOpen) {
                this.filterCategories();
            }
        },

        filterCategories() {
            if (!this.search) {
                this.filteredCategories = this.categories;
                return;
            }
            const searchLower = this.search.toLowerCase();
            this.filteredCategories = this.categories.filter(category => 
                category.name.toLowerCase().includes(searchLower)
            );
        },

        filterLevel2Categories() {
            if (!this.selected.level1?.children_recursive) return;
            if (!this.level2Search) {
                this.filteredLevel2Categories = this.selected.level1.children_recursive;
                return;
            }
            const searchLower = this.level2Search.toLowerCase();
            this.filteredLevel2Categories = this.selected.level1.children_recursive.filter(category => 
                category.name.toLowerCase().includes(searchLower)
            );
        },

        filterLevel3Categories() {
            if (!this.selected.level2?.children_recursive) return;
            if (!this.level3Search) {
                this.filteredLevel3Categories = this.selected.level2.children_recursive;
                return;
            }
            const searchLower = this.level3Search.toLowerCase();
            this.filteredLevel3Categories = this.selected.level2.children_recursive.filter(category => 
                category.name.toLowerCase().includes(searchLower)
            );
        },

        filterLevel4Categories() {
            if (!this.selected.level3?.children_recursive) return;
            if (!this.level4Search) {
                this.filteredLevel4Categories = this.selected.level3.children_recursive;
                return;
            }
            const searchLower = this.level4Search.toLowerCase();
            this.filteredLevel4Categories = this.selected.level3.children_recursive.filter(category => 
                category.name.toLowerCase().includes(searchLower)
            );
        },

        selectLevel1(category) {
            this.selected.level1 = category;
            this.selected.level2 = null;
            this.selected.level3 = null;
            this.selected.level4 = null;
            this.level2Search = '';
            this.level3Search = '';
            this.level4Search = '';
            this.filteredLevel2Categories = category.children_recursive || [];
            this.updateSelection();
        },

        selectLevel2(category) {
            this.selected.level2 = category;
            this.selected.level3 = null;
            this.selected.level4 = null;
            this.level3Search = '';
            this.level4Search = '';
            this.filteredLevel3Categories = category.children_recursive || [];
            this.updateSelection();
        },

        selectLevel3(category) {
            this.selected.level3 = category;
            this.selected.level4 = null;
            this.level4Search = '';
            this.filteredLevel4Categories = category.children_recursive || [];
            this.updateSelection();
        },

        selectLevel4(category) {
            this.selected.level4 = category;
            this.updateSelection();
        },

        updateSelection() {
            const parts = [];
            if (this.selected.level1) parts.push(this.selected.level1.name);
            if (this.selected.level2) parts.push(this.selected.level2.name);
            if (this.selected.level3) parts.push(this.selected.level3.name);
            if (this.selected.level4) parts.push(this.selected.level4.name);
            this.selectedPath = parts.join(' > ');
            
            const lastSelected = this.selected.level4 || this.selected.level3 || this.selected.level2 || this.selected.level1;
            this.selectedCategoryId = lastSelected?.id || '';
        },

        clearSelection() {
            this.selected = {
                level1: null,
                level2: null,
                level3: null,
                level4: null
            };
            this.selectedPath = '';
            this.selectedCategoryId = '';
            this.level2Search = '';
            this.level3Search = '';
            this.level4Search = '';
        },

        confirmSelection() {
            if (this.selectedPath) {
                this.isOpen = false;
            }
        }
    }
}
</script>
