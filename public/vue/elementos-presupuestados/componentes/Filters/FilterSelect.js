const FilterSelect = Vue.component('filter-select', {
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
        }
    },
    data() {
        return {
            searchQuery: '',
            results: [],
            showList: false,
            loading: false,
            debounceTimer: null,
            hasResults: true, // para mostrar mensaje si no hay resultados
        };
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
        async fetchData() {
            this.loading = true;
            try {
                const res = await axios.get(this.endpoint, {
                    params: { query: this.searchQuery }
                });

                this.results = res.data.data;
                this.showList = true;
                this.hasResults = this.results.length > 0;
            } catch (err) {
                console.error('Error al buscar:', err);
                this.results = [];
                this.showList = false;
            } finally {
                this.loading = false;
            }
        },
        selectOption(item) {
            this.$emit('input', item[this.idField]);
            this.searchQuery = item[this.valueField];
            this.showList = false;
            this.hasResults = true;
        },
        close() {
            this.showList = false;
        },
        hideList() {
            setTimeout(() => {
                this.showList = false;
                this.hasResults = true; // Resetea el estado de resultados al cerrar
            }, 200); // pequeño retraso para evitar el cierre inmediato al seleccionar
        },
        resetField() {
            this.searchQuery = '';
            this.results = [];
            this.showList = false;
            this.hasResults = true; // Resetea el estado de resultados
            this.$emit('input', ''); // opcional, también limpiar v-model
        },
    },
    watch: {
        value(val) {
            // Si el padre pone el valor vacío, limpia el input también
            if (!val) {
                this.searchQuery = '';
            }
        }
    },
});

export default FilterSelect;