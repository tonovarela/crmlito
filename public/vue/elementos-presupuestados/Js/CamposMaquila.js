export default {
    maquilaCatalogos: {
        empaques: [],
        entregas: [],
        lugarEntregas: [],
        suajadosList: [],
    },
    maquila: {
        empaque: '',
        entrega: '',
        lugar_entrega: '',

        descripcion: '',
        observaciones: '',
        observacionesExtras: [''],
        // observaciones_presupuesto: '',
        instalacion: '',
        proveedor_sugerido: '',
        suajado: '',
    },

    limpiarCampos() {
        this.maquilaCatalogos = {
            empaques: [],
            entregas: [],
            lugarEntregas: [],
            suajadosList: [],
        };
        this.maquila = {
            descripcion: '',
            observaciones: '',
            observacionesExtras: [''],
            // observaciones_presupuesto: '',
            instalacion: '',
            proveedor_sugerido: '',
            suajado: '',
        };
    },

    asignarValoresCampos(maquila) {
        this.maquila.empaque = maquila.empaque || '';
        this.maquila.entrega = maquila.entrega || '';
        this.maquila.lugar_entrega = maquila.lugar_entrega || '';

        this.maquila.descripcion = maquila.descripcion || '';
        this.maquila.observaciones = maquila.observaciones || '';
        // this.maquila.observaciones_presupuesto = maquila.observaciones_presupuesto || '';
        this.maquila.instalacion = maquila.instalacion || '';
        this.maquila.proveedor_sugerido = maquila.proveedor_sugerido || '';
        this.maquila.suajado = maquila.suajado || '';
    },

    camposConTipo: [
        [
            'empaque',
            'especificaciones',
            'select',
            'maquila',
            'Empaque'
        ],
        [
            'entrega',
            'especificaciones',
            'select',
            'maquila',
            'Entrega'
        ],
        [
            'lugar_entrega',
            'especificaciones',
            'select',
            'maquila',
            'Lugar de Entrega'
        ],
        [
            'suajado',
            'especificaciones',
            'select',
            'maquila',
            'Suajado'
        ],
        [
            'descripcion',
            'especificaciones',
            'input',
            'maquila',
            'Descripción'
        ],
        [
            'instalacion',
            'especificaciones',
            'input',
            'maquila',
            'Instalación'
        ],
    ]
}
