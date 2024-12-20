@props(['categories'])

<div x-data="{
    search: '',
    selectedPath: [],
    isOpen: false,
    categories: @js($categories),
    firstColumn: [],
    secondColumn: [],
    thirdColumn: [],
    fourthColumn: [],
    selectedFirstLevel: null,
    selectedSecondLevel: null,
    selectedThirdLevel: null,
    selectedFourthLevel: null,
    firstFilter: '',
    secondFilter: '',
    thirdFilter: '',
    fourthFilter: '',
    
    init() {
        this.firstColumn = this.categories.filter(cat => !cat.parent_id);
    },

    getFilteredCategories(list, searchTerm = '') {
        if (!list) return [];
        return searchTerm 
            ? list.filter(cat => cat.name.toLowerCase().includes(searchTerm.toLowerCase()))
            : list;
    },

    selectFirstLevel(category) {
        this.selectedFirstLevel = category;
        this.selectedSecondLevel = null;
        this.selectedThirdLevel = null;
        this.selectedFourthLevel = null;
        this.secondColumn = this.categories.filter(cat => cat.parent_id === category.id);
        this.thirdColumn = [];
        this.fourthColumn = [];
        this.updateSelectedPath();
    },

    selectSecondLevel(category) {
        this.selectedSecondLevel = category;
        this.selectedThirdLevel = null;
        this.selectedFourthLevel = null;
        this.thirdColumn = this.categories.filter(cat => cat.parent_id === category.id);
        this.fourthColumn = [];
        this.updateSelectedPath();
    },

    selectThirdLevel(category) {
        this.selectedThirdLevel = category;
        this.selectedFourthLevel = null;
        this.fourthColumn = this.categories.filter(cat => cat.parent_id === category.id);
        this.updateSelectedPath();
    },

    selectFourthLevel(category) {
        this.selectedFourthLevel = category;
        this.updateSelectedPath();
    },

    updateSelectedPath() {
        this.selectedPath = [
            this.selectedFirstLevel,
            this.selectedSecondLevel,
            this.selectedThirdLevel,
            this.selectedFourthLevel
        ].filter(Boolean);

        if (this.selectedPath.length > 0) {
            const lastSelected = this.selectedPath[this.selectedPath.length - 1];
            this.$refs.input.value = this.selectedPath.map(c => c.name).join(' > ');
            this.$refs.categoryId.value = lastSelected.id;
        }
    }
}" class="relative">
    <div class="mt-1 relative">
        <input type="text"
               x-ref="input"
               readonly
               @focus="isOpen = true"
               @click="isOpen = true"
               class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
               placeholder="Select category">
        
        <input type="hidden" name="category_id" x-ref="categoryId">
    </div>

    <!-- Dropdown -->
    <div x-show="isOpen" 
         @click.away="isOpen = false"
         class="absolute z-10 mt-1 w-[800px] bg-white shadow-lg rounded-md py-1 text-base ring-1 ring-black ring-opacity-5">
        
        <!-- Category Grid -->
        <div class="grid grid-cols-4 gap-0 divide-x h-[400px]">
            <!-- First Column -->
            <div class="p-2">
                <div class="relative mb-2">
                    <input type="text" 
                           placeholder="Filter..."
                           class="w-full pl-8 pr-4 py-1 text-sm border-gray-300 rounded-md"
                           x-model="firstFilter">
                    <div class="absolute inset-y-0 left-2 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
                <div class="overflow-y-auto h-[340px]">
                    <template x-for="category in getFilteredCategories(firstColumn, firstFilter)" :key="category.id">
                        <div @click="selectFirstLevel(category)"
                             :class="{'bg-gray-100': selectedFirstLevel?.id === category.id}"
                             class="cursor-pointer select-none relative py-2 px-3 hover:bg-gray-50 flex items-center justify-between">
                            <span x-text="category.name"></span>
                            <svg x-show="categories.some(c => c.parent_id === category.id)" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Second Column -->
            <div class="p-2">
                <div class="relative mb-2">
                    <input type="text" 
                           placeholder="Filter..."
                           class="w-full pl-8 pr-4 py-1 text-sm border-gray-300 rounded-md"
                           x-model="secondFilter">
                    <div class="absolute inset-y-0 left-2 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
                <div class="overflow-y-auto h-[340px]">
                    <template x-for="category in getFilteredCategories(secondColumn, secondFilter)" :key="category.id">
                        <div @click="selectSecondLevel(category)"
                             :class="{'bg-gray-100': selectedSecondLevel?.id === category.id}"
                             class="cursor-pointer select-none relative py-2 px-3 hover:bg-gray-50 flex items-center justify-between">
                            <span x-text="category.name"></span>
                            <svg x-show="categories.some(c => c.parent_id === category.id)" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Third Column -->
            <div class="p-2">
                <div class="relative mb-2">
                    <input type="text" 
                           placeholder="Filter..."
                           class="w-full pl-8 pr-4 py-1 text-sm border-gray-300 rounded-md"
                           x-model="thirdFilter">
                    <div class="absolute inset-y-0 left-2 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
                <div class="overflow-y-auto h-[340px]">
                    <template x-for="category in getFilteredCategories(thirdColumn, thirdFilter)" :key="category.id">
                        <div @click="selectThirdLevel(category)"
                             :class="{'bg-gray-100': selectedThirdLevel?.id === category.id}"
                             class="cursor-pointer select-none relative py-2 px-3 hover:bg-gray-50 flex items-center justify-between">
                            <span x-text="category.name"></span>
                            <svg x-show="categories.some(c => c.parent_id === category.id)" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Fourth Column -->
            <div class="p-2">
                <div class="relative mb-2">
                    <input type="text" 
                           placeholder="Filter..."
                           class="w-full pl-8 pr-4 py-1 text-sm border-gray-300 rounded-md"
                           x-model="fourthFilter">
                    <div class="absolute inset-y-0 left-2 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
                <div class="overflow-y-auto h-[340px]">
                    <template x-for="category in getFilteredCategories(fourthColumn, fourthFilter)" :key="category.id">
                        <div @click="selectFourthLevel(category)"
                             :class="{'bg-gray-100': selectedFourthLevel?.id === category.id}"
                             class="cursor-pointer select-none relative py-2 px-3 hover:bg-gray-50">
                            <span x-text="category.name"></span>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Current Selection -->
        <template x-if="selectedPath.length > 0">
            <div class="px-4 py-2 bg-gray-50 border-t">
                <div class="text-sm text-gray-600">
                    Current selection: 
                    <span class="text-orange-500" x-text="selectedPath.map(c => c.name).join(' > ')"></span>
                </div>
            </div>
        </template>

        <!-- Footer -->
        <div class="px-4 py-3 bg-gray-50 border-t flex justify-end space-x-2">
            <button type="button" 
                    @click="isOpen = false"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                Cancel
            </button>
            <button type="button" 
                    @click="isOpen = false"
                    x-bind:disabled="!selectedPath.length"
                    :class="{'opacity-50 cursor-not-allowed': !selectedPath.length}"
                    class="px-4 py-2 text-sm font-medium text-white bg-orange-500 border border-transparent rounded-md hover:bg-orange-600">
                Confirm
            </button>
        </div>
    </div>
</div>
