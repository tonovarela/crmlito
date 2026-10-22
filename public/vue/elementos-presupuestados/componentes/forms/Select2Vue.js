const Select2Vue = Vue.component('select2', {
    template: '<select></select>',
    props: {
        value: [String, Number, Array],
        options: {
            type: Array,
            default: () => []
        },
        multiple: {
            type: Boolean,
            default: false
        },
        placeholder: {
            type: String,
            default: 'Selecciona una opción'
        },
        ajax: {
            type: Boolean,
            default: false
        },
        url: {
            type: String,
            default: ''
        },
        tagging: {
            type: Boolean,
            default: false
        },
        processItems: {
            type: Function,
            default: null
        },
        api: {
            type: String,
            default: ''
        }

    },
    data() {
        return {
            select2: null
        }
    },
    watch: {
        value(val) {
            if (this.select2) {
                this.select2.val(val).trigger('change');
            }
        },
        options(val) {
            if (this.select2) {
                this.select2.empty();
                this.select2.select2({
                    data: val,
                    ...this.getCommonOptions()
                });
                if (this.value) {
                    this.select2.val(this.value).trigger('change');
                }
            }
        }
    },
    mounted() {
        const vm = this;

        const select2Options = {
            data: this.options,
            ...this.getCommonOptions()
        };

        if (this.ajax && this.url) {
            select2Options.ajax = {
                url: this.url,
                dataType: 'json',
                delay: 250,
                data(params) {
                    return {
                        q: params.term,
                        page: params.page
                    };
                },
                processResults(data, params) {
                    params.page = params.page || 1;

                    let items = data.items || data;
                    if (vm.processItems) {
                        return vm.processItems(items);
                    }

                    return {
                        results: items,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
                cache: true
            };
        }

        this.select2 = $(this.$el)
            .select2(select2Options)
            .val(this.value)
            .trigger('change')
            .on('change', function () {
                const selectedValue = $(this).val();
                vm.$emit('input', selectedValue);
            });
    },
    beforeDestroy() {
        if (this.select2) {
            this.select2.off().select2('destroy');
        }
    }
});

export default Select2Vue;