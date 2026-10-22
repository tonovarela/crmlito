<filter-input
    inline-template
    v-model="{{ $vmodel }}"
    label="{{ $label }}"
    placeholder="{{ $placeholder }}"
    value-type="{{ $valueType }}"
>
    <div>
        <label class="block text-gray-700 text-sm font-bold mb-2">
            @{{ label }}
        </label>
        <input
            :type="valueType"
            :value="internalValue"
            @input="onInput"
            class="border border-gray-300 rounded px-4 py-2 w-full text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400"
            :placeholder="placeholder"
            @keydown.enter="$parent.{{ $action }}()" 
        />
    </div>
</filter-input>