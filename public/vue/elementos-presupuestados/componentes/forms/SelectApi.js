const SelectFormApi = Vue.component('form-select-api', {
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
        endpoint: {
            type: String,
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
            type: String,
            default: 'Seleccione una opción'
        },
        error: {
            type: String,
            default: ''
        },
        valueConcatField: {
            type: String,
            default: ''
        }
    },
    data() {
        return {
            optionsList: {},
        };
    },
    methods: {
        fetchOptions() {
            if (this.endpoint) {
                axios.get(this.endpoint)
                    .then(response => {
                        this.optionsList = response.data;
                    })
                    .catch(error => {
                        console.error('Error fetching options:', error);
                    });
            }
        },
        onChange(event) {
            this.$emit('input', event.target.value);
        },
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
    created() {
        this.fetchOptions();
    },
    template: `
        <div>
            <label :for="name" class="block text-gray-700 text-sm font-bold mb-1">
                {{ label }}
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
                    {{ placeholder || 'Seleccione una opción' }}
                </option>
                <template v-for="(item, idx) in optionsList" :key="idx">
                    <optgroup v-if="item.options" :label="item.label">
                        <option v-for="opt in item.options" :key="opt[idField]" :value="opt[idField] + '-' + opt[valueConcatField]" v-if="opt[valueConcatField] != ''">
                            {{ opt[valueField] }}
                        </option>
                        <option v-for="opt in item.options" :key="opt[idField]" :value="opt[idField]" v-else>
                            {{ opt[valueField] }}
                        </option>
                    </optgroup>
                    <option v-else :value="item[idField]">
                        {{ item[valueField] }}
                    </option>
                </template>
            </select>
        </div>
    `
});

export default SelectFormApi;