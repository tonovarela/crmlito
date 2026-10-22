<th class="px-6 py-1 cursor-pointer border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider {{ isset($mainAdicionalClasses) ? $mainAdicionalClasses : '' }}">
    {{-- Sortable header component --}}
    <div is="sortable-header" 
        inline-template 
        title="{{ $title }}" 
        value="{{ $value }}" 
        @sort="sortBy"
    >
        <div class="flex items-center justify-between cursor-pointer" @click="sortColumn">
            <span class="text-white w-full block {{ isset($adicionalClasses) ? $adicionalClasses : '' }}">
                {{ $title }}
            </span>
            <i class="fas fa-sort ml-2 text-gray-400 hover:text-gray-600"></i>
        </div>
    </div>
</th>