<form-select-api
    is="form-select-api"
    inline-template

    :label="'{{ $label }}'"
    :name="'{{ $name }}'"
    :error="{{ $error }}"
    :required="{{ $required ? 'true' : 'false' }}"
    v-model="{{ $vModel }}"
    :disabled="{{ $disabled ? 'true' : 'false' }}"
    :id-field="'{{ $idField }}'"
    :value-field="'{{ $valueField }}'"
    :placeholder="'{{ $placeholder }}'"
    :endpoint="'{{ $endpoint }}'"
    :value-concat-field="'{{ $valueConcatField }}'"
>
    <div>
        <label :for="name" class="block text-gray-700 text-sm font-bold mb-1">
            @{{ label }}
            <span v-if="required" class="text-red-500">*</span>
        </label>
        <select
            :class="[
                'border border-gray-300 rounded p-2 w-full text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 focus-next',
                error ? '!border-red-500' : '!border-gray-300'
            ]"
            :id="name"
            :name="name"
            v-bind:value="value"
            @change="$emit('input', $event.target.value)"
            :disabled="disabled"
            @keydown.enter.prevent="focusNext($event)"
        >
            <option value="" selected>
                @{{ placeholder || 'Seleccione una opción' }}
            </option>
            <template v-for="(item, idx) in optionsList" :key="idx">
                <optgroup v-if="item.options" :label="item.label">
                    <option v-for="opt in item.options" :key="opt[idField]" :value="opt[idField] + '-' + opt[valueConcatField]" v-if="opt[valueConcatField] != ''">
                        @{{ opt[valueField] }}
                    </option>
                    <option v-for="opt in item.options" :key="opt[idField]" :value="opt[idField]" v-else>
                        @{{ opt[valueField] }}
                    </option>
                </optgroup>
                <option v-else :value="item[idField]">
                    @{{ item[valueField] }}
                </option>
            </template>
        </select>

        {{--  <div v-if="error" class="text-red-500 text-xs mt-1">
            <span v-text="error[0]"></span>
        </div>  --}}
    </div>
</form-select-api>