<form-input
    is="form-input"
    inline-template

    :label="'{{ $label }}'"
    :name="'{{ $name }}'"
    :type="'{{ $type }}'"
    :error="{{ $error }}"
    :required="{{ $required ? 'true' : 'false' }}"
    v-model="{{ $vModel }}"
    {{--  :disabled="'{{ $disabled }}'"  --}}
    :disabled="{{ $disabled ? 'true' : 'false' }}"
>
    <div>
        <label :for="name" class="block text-gray-700 text-sm font-bold mb-2">
        @{{ label }}
        <span v-if="required" class="text-red-500">*</span>
        </label>
        <input
            :type="type || 'text'"
            :class="[
                'border border-gray-300 rounded p-2 w-full text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 focus-next !h-[2.4em]',
                error ? '!border-red-500' : '!border-gray-300'
            ]"
            :id="name"
            :name="name"
            v-bind:value="value"
            v-on:input="$emit('input', $event.target.value)"
            :disabled="disabled"
            v-bind:step="type === 'number' ? 'any' : undefined"
            @keydown.enter.prevent="focusNext($event)"
        >
        {{--  <div v-if="error" class="text-red-500 text-xs mt-1">
            <span v-text="error[0]"></span>
        </div>  --}}
    </div>
</form-input>