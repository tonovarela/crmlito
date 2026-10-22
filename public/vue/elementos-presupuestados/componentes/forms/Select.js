const SelectForm = Vue.component('form-select', {
    props: {
        label: {
            type: String,
            default: ''
        },
        name: {
            type: String,
            default: ''
        },
        value: {
            type: [String, Number], // soporte para v-model
        },
        idField: {
            type: String,
            default: 'id'
        },
        valueField: {
            type: String,
            default: 'name'
        },
        required: {
            type: Boolean,
            default: true
        },
        disabled: {
            type: Boolean,
            default: false
        },
        placeholder: {
            type: [Array, String],
            default: 'Seleccione una opción'
        },
        options: {
            type: [Array, String, Object],
            default: () => []
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
    computed: {
        optionsList() {
            // Si options es un string, resuelve la ruta (soporta 'pop.sustratos', etc.)
            if (typeof this.options === 'string') {
                // Soporta rutas anidadas como 'pop.sustratos'
                return this.options.split('.').reduce((obj, key) => {
                    return obj ? obj[key] : [];
                }, this.$parent);
            }
            // Si es array, lo retorna directo
            return this.options;
        }
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
            <label :for="name" class="block text-gray-700 text-sm font-bold mb-2">
                {{ label }}
                <span v-if="required" class="text-red-500">*</span>
            </label>
            <select
                :class="[
                    'rounded p-2 w-full text-sm focus:outline-none focus-next',
                    error ? '!border-red-500' : '!border-gray-300',
                    isModified ? 'border-2 !border-amber-400 focus:ring-2 focus:ring-amber-400 focus:border-amber-400' : 'border !border-gray-300 focus:ring-2 focus:ring-sky-400 focus:border-sky-400'
                ]"
                :id="name"
                :name="name"
                v-bind:value="value"
                @change="$emit('input', $event.target.value)"
                :disabled="disabled"
                @keydown.enter.prevent="focusNext($event)"
            >
                <option value="" selected>
                    {{ placeholder || 'Seleccione una opción' }}
                </option>
                <option v-for="optionItem in optionsList" :key="optionItem[idField]" :value="optionItem[idField]" v-bind:disabled="optionItem.fromOldVersion">
                    {{ optionItem[valueField] }}
                </option>
            </select>

            <span v-if="isModified" class="absolute top-1 right-2 text-amber-500" title="Campo modificado">
                <i class="fas fa-exclamation-circle"></i>
            </span>
        </div>
    `
});

export default SelectForm;