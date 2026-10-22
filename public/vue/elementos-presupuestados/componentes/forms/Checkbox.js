export default Vue.component('form-checkbox', {
    props: {
        label: {
            type: String,
            default: ''
        },
        name: {
            type: String,
            default: ''
        },
        type: {
            type: String,
            default: 'text'
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
        error: {
            type: [String, Object],
            default: ''
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
});