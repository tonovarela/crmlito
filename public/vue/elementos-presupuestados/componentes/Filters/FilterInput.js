const FilterInput = Vue.component('filter-input', {
    props: {
        value: {
            type: String,
            default: ''
        },
        placeholder: {
            type: String,
            default: 'Buscar...'
        },
        label: {
            type: String,
            default: 'Buscar Posts'
        },
        valueType: {
            type: String,
            default: 'text'
        }
    },
    data() {
        return {
            internalValue: this.value
        };
    },
    watch: {
        value(newVal) {
            // Si el valor externo cambia, actualizar el interno
            this.internalValue = newVal;
        }
    },
    methods: {
        onInput(event) {
            this.internalValue = event.target.value;
            this.$emit('input', this.internalValue);
        }
    },
    template: `
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2">
                {{ label }}
            </label>
            <input
                :type="valueType"
                :value="internalValue"
                @input="onInput"
                class="border border-gray-300 rounded px-4 py-2 w-full text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400"
                :placeholder="placeholder"
            />
        </div>
    `
});

export default FilterInput;