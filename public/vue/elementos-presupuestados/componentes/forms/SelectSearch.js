const SelectSearch = Vue.component('select-search', {
    props: {
        value: [String, Number, Object], // soporte para v-model
        endpoint: {
            type: String,
            required: true
        },
        label: {
            type: String,
            default: 'Selecciona una opcion' // campo que se mostrará
        },
        idField: {
            type: String,
            default: 'id' // campo que se retornará como valor
        },
        idStoreField: {
            type: String,
            default: 'id' // campo que se usará para almacenar el id en el v-model
        },
        valueField: {
            type: String,
            default: 'name' // campo que se retornará como valor
        },
        placeholder: {
            type: String,
            default: 'Buscar...'
        },
        minLength: {
            type: Number,
            default: 2
        },
        isConcatField: {
            type: Boolean,
            default: false // si es true, concatena id y valueField
        },
        error: {
            type: String,
            default: ''
        },
        defaultValue: {
            type: [String, Number, Object],
            default: ''
        },
        disabled: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            searchQuery: '',
            results: [],
            showList: false,
            loading: false,
            debounceTimer: null,
            highlightedIndex: -1,
            hasResults: true, // para mostrar mensaje si no hay resultados
            selectedAFindResult: false
        };
    },

    mounted() {
        // Si hay un valor por defecto, lo asignamos al input
        if (this.defaultValue) {
            this.searchQuery = this.defaultValue;
        }
    },

    created() {
        this.$on('clear', this.resetField);
    },
    methods: {
        onInput() {
            clearTimeout(this.debounceTimer);

            if (this.searchQuery !== '') {
                if (this.searchQuery.length < this.minLength) {
                    this.results = [];
                    this.showList = false;
                    this.hasResults = true; // Resetea el estado de resultados
                    return;
                }
            }

            this.debounceTimer = setTimeout(this.fetchData, 400);
        },
        fetchData() {
            this.loading = true;
            axios.get(this.endpoint, {
                params: { query: this.searchQuery }
            }).then(res => {
                this.results = res.data;
                this.showList = true;
                this.hasResults = this.results.length > 0;
            })
            .catch(err => {
                this.results = [];
                this.showList = false;
                this.hasResults = false; // No hay resultados si falla la petición
            })
            .finally(() => {
                this.loading = false;
            });
        },
        selectOption(item) {
            this.selectedAFindResult = true;

            this.$emit('input', item[this.idStoreField]);
            this.showList = false;
            this.hasResults = true;

            if (this.isConcatField) {
                this.searchQuery = `${item[this.idField]} - ${item[this.valueField]}`;
            } else {
                this.searchQuery = item[this.valueField];
            }
        },
        close() {
            this.showList = false;
        },
        hideList() {
            setTimeout(() => {
                this.showList = false;
                this.hasResults = true; // Resetea el estado de resultados al cerrar
                this.selectedAFindResult = false; // Resetea el estado de selección
                this.highlightedIndex = -1; // Resetea el índice resaltado
            }, 200); // pequeño retraso para evitar el cierre inmediato al seleccionar
        },
        resetField() {
            this.searchQuery = '';
            this.results = [];
            this.showList = false;
            this.hasResults = true; // Resetea el estado de resultados
            this.$emit('input', ''); // opcional, también limpiar v-model
        },
        moveDown() {
            if (!this.showList || !this.results.length) return;
            if (this.highlightedIndex < this.results.length - 1) {
                this.highlightedIndex++;
                this.scrollToHighlighted();
            }
        },
        moveUp() {
            if (!this.showList || !this.results.length) return;
            if (this.highlightedIndex > 0) {
                this.highlightedIndex--;
                this.scrollToHighlighted();
            }
        },
        selectHighlighted() {
            if (this.highlightedIndex >= 0 && this.highlightedIndex < this.results.length) {
                this.selectOption(this.results[this.highlightedIndex]);
            }
        },
        scrollToHighlighted() {
            this.$nextTick(() => {
                const ref = this.$refs['option-' + this.highlightedIndex];
                if (ref && ref.scrollIntoView) {
                    ref.scrollIntoView({ block: 'nearest' }); // ¡Esto es lo que hace Select2!
                }
            });
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
    watch: {
        value(val) {
            // Si el padre pone el valor vacío, limpia el input también
            if (!val) {
                this.searchQuery = '';
            }
        },
        results() {
            this.highlightedIndex = this.results.length ? 0 : -1;
            this.$nextTick(() => this.scrollToHighlighted());
        }
    },
    template: `
        <div class="relative w-full z-[10]" @click.outside="close">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                {{ label }}
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
                        {{ item[idField] }} - {{ item[valueField] }}
                    </span>
                    <span v-else>
                        {{ item[valueField] }}
                    </span>
                </p>
            </div>

            <ul v-if="!hasResults"
                class="absolute z-[9999] mt-16 bg-white border border-gray-300 rounded shadow text-sm h-auto overflow-y-auto flex flex-col !w-auto justify-start items-center">
                <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer w-full !text-left">
                    No se encontraron resultados
                </li>
            </ul>
        </div>
    `
});

export default SelectSearch;