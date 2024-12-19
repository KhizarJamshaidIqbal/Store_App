@props(['specifications' => []])

<div class="specifications-section">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Product Specifications</h3>
    <div class="specifications-list space-y-4" x-data="{ specs: @json($specifications) }">
        <template x-for="(spec, index) in specs" :key="index">
            <div class="spec-item flex items-center gap-4">
                <div class="flex-1">
                    <input type="text" 
                           x-model="spec.key" 
                           name="specifications[][key]" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           placeholder="Key">
                </div>
                <div class="flex-1">
                    <input type="text" 
                           x-model="spec.value" 
                           name="specifications[][value]" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           placeholder="Value">
                </div>
                <button type="button" 
                        @click="specs.splice(index, 1)" 
                        class="p-2 text-red-600 hover:text-red-800 focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </template>
        
        <button type="button"
                @click="specs.push({key: '', value: ''})"
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Add Specification
        </button>
    </div>
</div>
