<form-textarea
    is="form-textarea"
    inline-template

    :label="'{{ $label }}'"
    :name="'{{ $name }}'"
    :required="{{ $required ? 'true' : 'false' }}"
    v-model="{{ $vModel }}"
    :error="{{ $error }}"
    disabled="{{ $disabled }}"
    :disabled="{{ $disabled ? 'true' : 'false' }}"
    :placeholder="'{{ $placeholder }}'"
>
    <div>
        <label :for="name" class="block text-gray-700 text-sm font-bold mb-1">
        @{{ label }}
        <span v-if="required" class="text-red-500">*</span>
        </label>
        <textarea
            :class="[
                'border border-gray-300 rounded p-2 w-full text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 focus-next',
                error ? '!border-red-500' : '!border-gray-300'
            ]"
            :id="name"
            :name="name"
            v-bind:value="value"
            v-on:input="$emit('input', $event.target.value)"
            :disabled="disabled"
            @keydown.enter.prevent="focusNext($event)"
            :placeholder="placeholder"
        ></textarea>

        {{--  <div v-if="error" class="text-red-500 text-xs mt-1">
            <span v-text="error[0]"></span>
        </div>  --}}
    </div>
</form-textarea>