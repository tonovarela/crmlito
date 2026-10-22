<form-select
    is="form-select"
    inline-template

    :label="'{{ $label }}'"
    :name="'{{ $name }}'"
    :required="{{ $required ? 'true' : 'false' }}"
    v-model="{{ $vModel }}"
    :error="{{ $error }}"
    :disabled="{{ $disabled ? 'true' : 'false' }}"
    :id-field="'{{ $idField }}'"
    :value-field="'{{ $valueField }}'"
    placeholder="---"
    :options="'{{ $options }}'"
>
    <div>
        <label :for="name" class="block text-gray-700 text-sm font-bold mb-2">
            @{{ label }}
            <span v-if="required" class="text-red-500">*</span>
        </label>
        <select
            :class="[
                'border rounded p-2 w-full text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 focus-next',
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
            <option v-for="optionItem in optionsList" :key="optionItem[idField]" :value="optionItem[idField]">
                @{{ optionItem[valueField] }}
            </option>
        </select>

        {{--  <div v-if="error" class="text-red-500 text-xs mt-1">
            <span v-text="error[0]"></span>
        </div>  --}}
    </div>
</form-select>