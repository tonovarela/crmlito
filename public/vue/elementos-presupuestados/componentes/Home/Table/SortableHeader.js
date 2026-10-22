const SortableHeader = Vue.component('sortable-header', {
    props: {
        title: {
            type: String,
            required: true
        },
        value: {
            type: String,
            required: true
        }
    },
    methods: {
        sortColumn() {
            this.$emit('sort', this.value);
        }
    }
});

export default SortableHeader;