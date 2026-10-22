<select-search 
    is="select-search" 
    inline-template 
    v-model="{{ $vmodel }}" 
    endpoint="{{ $endpoint }}"
    :error="{{ $error }}"
    :id-field="'{{ $idField }}'" 
    :value-field="'{{ $valueField }}'" 
    :id-store-field="'{{ $idStoreField }}'"
    aria-placeholder="{{ $placeholder }}" 
    ref="{{ $ref }}" 
    label="{{ $label }}"
    :is-concat-field="'{{ $concatField }}'" 
    :placeholder="'{{ $placeholder }}'"
    :default-value="{{ $defaultValue }}"
    :disabled="{{ $disabled }}"
>
    <div class="relative w-full z-[10]" @click.outside="close">
        <label class="block text-gray-700 text-sm font-bold mb-2">
            @{{ label }}
        </label>
        <input type="text"
            :class="[
                'border border-gray-300 rounded p-2 w-full text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 focus-next',
                error ? '!border-red-500' : '!border-gray-300'
            ]"
            :placeholder="placeholder" v-model="searchQuery" @focus="onInput(); " @input="onInput" @blur="hideList"
            @keydown.down.prevent="moveDown" @keydown.up.prevent="moveUp" @keydown.enter.prevent="selectHighlighted(); focusNext($event);"
            :disabled="disabled"
        />

        <div v-if="loading" class="absolute top-2 right-3 text-xs text-gray-500 transition duration-300 transform">
            <i class="fa-solid fa-spinner fa-spin"></i>
        </div>

        <div v-if="showList && results.length"
            class="absolute z-[9999] mt-16 bg-white border border-gray-300 rounded shadow text-sm max-h-40 overflow-y-auto flex flex-col !w-auto justify-start items-center">
            <p v-for="(item, index) in results" :key="index" @click="selectOption(item)"
                :class="['px-4 py-2 hover:bg-gray-100 cursor-pointer w-full !text-left', { 
                    'bg-sky-100': index === highlightedIndex 
                }]"
                :ref="'option-' + index">
                <span v-if="isConcatField">
                    @{{ item[idField] }} - @{{ item[valueField] }}
                </span>
                <span v-else>
                    @{{ item[valueField] }}
                </span>
            </p>
        </div>

        <ul v-if="!hasResults"
            class="absolute z-[9999] mt-16 bg-white border border-gray-300 rounded shadow text-sm h-auto overflow-y-auto flex flex-col !w-auto justify-start items-center">
            <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer w-full !text-left">
                No se encontraron resultados
            </li>
        </ul>

        {{--  <div v-if="error" class="text-red-500 text-xs mt-1">
            <span v-text="error[0]"></span>
        </div>  --}}
    </div>

</select-search>
