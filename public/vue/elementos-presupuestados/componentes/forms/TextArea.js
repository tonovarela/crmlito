export default Vue.component('form-textarea', {
    props: {
        label: {
            type: String,
            default: ''
        },
        name: {
            type: String,
            default: ''
        },
        required: {
            type: Boolean,
            default: true
        },
        value: {
            type: [String, Number],
            default: ''
        },
        disabled: {
            type: Boolean,
            default: false
        },
        placeholder: {
            type: String,
            default: ''
        },
        error: {
            type: String,
            default: ''
        },
        isModified: {
            type: Boolean,
            default: false
        },
    },
    methods: {
        focusNext(event) {
            // Obtiene todos los campos focus-next visibles y habilitados
            const fields = Array.from(document.querySelectorAll('.focus-next'))
                .filter(el => !el.disabled && el.offsetParent !== null);

            const idx = fields.indexOf(event.target);
            if (idx > -1 && idx < fields.length - 1) {
                // Si el siguiente es un select, haz focus y abre el desplegable
                const next = fields[idx + 1];
                next.focus();
            }
        },
    },
    template: `
        <div class="relative">
            <label :for="name" class="block text-gray-700 text-sm font-bold mb-1">
            {{ label }}
            <span v-if="required" class="text-red-500">*</span>
            </label>
            <textarea
                :class="[
                    ' border-gray-300 rounded p-2 w-full text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 focus-next mt-2',
                    error ? '!border-red-500' : '!border-gray-300',
                    isModified ? 'border-2 !border-amber-400 focus:ring-2 focus:ring-amber-400 focus:border-amber-400' : 'border !border-gray-300 focus:ring-2 focus:ring-sky-400 focus:border-sky-400'
                ]"
                :id="name"
                :name="name"
                v-bind:value="value"
                v-on:input="$emit('input', $event.target.value)"
                :disabled="disabled"
                @keydown.enter.prevent="focusNext($event)"
                :placeholder="placeholder"
            ></textarea>
            <span v-if="isModified" class="absolute top-1 right-2 text-amber-500" title="Campo modificado">
                <i class="fas fa-exclamation-circle"></i>
            </span>
        </div>
    `
});