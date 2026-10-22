const Loader = Vue.component('loader', {
    props: {
        show: {
            type: Boolean,
            default: false
        },
        message: {
            type: String,
            default: 'Cargando...'
        },
        overlay: {
            type: Boolean,
            default: true
        }
    }
});

export default Loader;