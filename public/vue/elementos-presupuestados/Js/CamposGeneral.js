export default {
    generalDetail: {
        oportunidad: '',
        vendedor: '',
        contacto: '',
        cliente: '',
    },

    general: {
        oportunidad_id: '',
        vendedor_id: '',
        vendedor_abr: '',
        cliente_id: '',
        contacto_id: '',
        nombre_proyecto: '',
        comision_externa: '0',
        cantidad_cotizar_1: '0',
        cantidad_cotizar_2: '0',
        cantidad_cotizar_3: '0',
        cat_EP_tipoProducto_id: '',
        plantilla_id: '',
        observaciones_presupuesto: '',
    },

    oportunidad_edit: '',

    limpiarCampos() {
        this.generalDetail = {
            oportunidad: '',
            vendedor: '',
            contacto: '',
            cliente: '',
        };
        this.general = {
            oportunidad_id: '',
            vendedor_id: '',
            vendedor_abr: '',
            cliente_id: '',
            contacto_id: '',
            nombre_proyecto: '',
            comision_externa: '0',
            cantidad_cotizar_1: '0',
            cantidad_cotizar_2: '0',
            cantidad_cotizar_3: '0',
            cat_EP_tipoProducto_id: '',
            plantilla_id: '',
            observaciones_presupuesto: '',
        };
    },

    asignarValoresCampos(solicitud) {
        this.generalDetail.oportunidad = solicitud.oportunidad || '';
        this.generalDetail.vendedor = solicitud.vendedor || '';
        this.generalDetail.contacto = solicitud.contacto || '';
        this.generalDetail.cliente = solicitud.cliente || '';

        this.general.vendedor_id = solicitud.vendedor_id || '';
        this.general.vendedor_abr = solicitud.vendedor_abr || '';
        this.general.cliente_id = solicitud.cliente_id || '';
        this.general.contacto_id = solicitud.contacto_id || '';
        this.general.nombre_proyecto = solicitud.nombre_proyecto || '';
        this.general.comision_externa = Number(solicitud.comision_externa).toFixed(2) || '0';
        this.general.cantidad_cotizar_1 = Math.floor(Number(solicitud.cantidad_cotizar_1)) || '0';
        this.general.cantidad_cotizar_2 = Math.floor(Number(solicitud.cantidad_cotizar_2)) || '0';
        this.general.cantidad_cotizar_3 = Math.floor(Number(solicitud.cantidad_cotizar_3)) || '0';

        setTimeout(() => {
            this.general.cat_EP_tipoProducto_id = solicitud.cat_EP_tipoProducto_id + '-' + solicitud.plantilla_id || '';
            this.general.oportunidad_id = solicitud.oportunidad_id || '';
            this.oportunidad_edit = solicitud.folio_oportunidad + ' - ' + solicitud.oportunidad || '';
        }, 2000);
        
        this.general.plantilla_id = solicitud.plantilla_id || '';
        this.general.observaciones_presupuesto = solicitud.observaciones_presupuesto || '';
    },

    camposConTipo: [
        [
            'cantidad_cotizar_1',
            'minimos',
            'input',
            'general',
            'Cantidad a cotizar 1'
        ],
        [
            'cantidad_cotizar_2',
            'minimos',
            'input',
            'general',
            'Cantidad a cotizar 2'
        ],
        [
            'cantidad_cotizar_3',
            'minimos',
            'input',
            'general',
            'Cantidad a cotizar 3'
        ],
    ],
}
