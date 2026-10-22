<filter-select
    is="filter-select"
    inline-template
    v-model="{{ $vmodel }}"
    endpoint="{{ $endpoint }}"
    idField="{{ $idField }}"
    valueField="{{ $valueField }}"
    aria-placeholder="{{ $placeholder }}"
    ref="{{ $ref }}"
    label="{{ $label }}"
>
    <div class="relative w-full" @click.outside="close">
        <label class="block text-gray-700 text-sm font-bold mb-2">
            @{{ label }}
        </label>
        <input
            type="text"
            class="border border-gray-300 rounded px-4 py-2 w-full text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400"
            :placeholder="placeholder"
            v-model="searchQuery"
            @focus="searchQuery = ''; onInput(); "
            @input="onInput"
            @blur="hideList"
            @keydown.enter="$parent.{{ $action }}()" 
        />

        <div v-if="loading" class="absolute top-2 right-3 text-xs text-gray-500 transition duration-300 transform">
            <i class="fa-solid fa-spinner fa-spin"></i>
        </div>

        <ul v-if="showList && results.length" class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded shadow text-sm max-h-60 overflow-y-auto">
            <li
                v-for="(item, index) in results"
                :key="index"
                @click="selectOption(item)"
                class="px-4 py-2 hover:bg-gray-100 cursor-pointer"
            >
                @{{ item[valueField] }}
            </li>
        </ul>
        
        <ul v-if="!hasResults" class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded shadow text-sm max-h-60 overflow-y-auto">
            <li
                class="px-4 py-2 hover:bg-gray-100"
            >
                No se encontraron resultados
            </li>
        </ul>
    </div>

</filter-select>