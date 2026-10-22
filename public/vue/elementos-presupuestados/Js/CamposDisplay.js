export default {
    displayCatalogos: {
        sustratos: [],
        gramajes: [],
        tintasFrentes: [],
        medidasFinales: [],
        medidasExtendidas: [],
        sustratosParaEmpalme: [],
        otrosDisplays: [],
        metalizadosFrente: [],
        metalizadosVuelta: [],
        laminadosFrente: [],
        laminadosVuelta: [],
        barnicesSakuraiFrente: [],
        barnicesSakuraiVuelta: [],
        otrosMetalizadosFrente: [],
        otrosMetalizadosVuelta: [],
        otrosLaminadosFrente: [],
        otrosLaminadosVuelta: [],
        otrosBarnicesFrente: [],
        otrosBarnicesVuelta: [],
        tipoPegueArmado: [],
        empaquesPrimarios: [],
        empaquesSecundarios: [],
        lugaresEntrega: [],

        suajadosList: [],
    },
    // Valores que se envían al servidor
    display: {
        sustrato: '',
        gramaje: '',
        tinta_frente: '',
        medida_final: '',

        medida_final_ancho: '',
        medida_final_largo: '',
        medida_final_alto: '',

        medida_extendida: '',
        sustrato_empalme: '',
        otros_display: '',
        metalizado_frente: '',
        metalizado_vuelta: '',
        laminado_frente: '',
        laminado_vuelta: '',
        barniz_sakurai_frente: '',
        barniz_sakurai_vuelta: '',
        otros_metalizado_frente: '',
        otros_metalizado_vuelta: '',
        otros_laminado_frente: '',
        otros_laminado_vuelta: '',
        otros_barniz_frente: '',
        otros_barniz_vuelta: '',
        tipo_pegue_armado: '',
        empaque_primario: '',
        empaque_secundario: '',
        lugar_entrega: '',
        observaciones: '',
        observacionesExtras: [''],
        // observaciones_presupuesto: '',
        suajado: '',
    },
    limpiarCampos() {
        this.display = {
            sustrato: '',
            gramaje: '',
            tinta_frente: '',
            medida_final: '',

            medida_final_ancho: '',
            medida_final_largo: '',
            medida_final_alto: '',

            medida_extendida: '',
            sustrato_empalme: '',
            otros_display: '',
            metalizado_frente: '',
            metalizado_vuelta: '',
            laminado_frente: '',
            laminado_vuelta: '',
            barniz_sakurai_frente: '',
            barniz_sakurai_vuelta: '',
            otros_metalizado_frente: '',
            otros_metalizado_vuelta: '',
            otros_laminado_frente: '',
            otros_laminado_vuelta: '',
            otros_barniz_frente: '',
            otros_barniz_vuelta: '',
            tipo_pegue_armado: '',
            empaque_primario: '',
            empaque_secundario: '',
            lugar_entrega: '',
            observaciones: '',
            observacionesExtras: [''],
            // observaciones_presupuesto: '',
            suajado: '',
        };
        this.displayCatalogos = {
            sustratos: [],
            gramajes: [],
            tintasFrentes: [],
            tintasVuelta: [],
            barnicesFrente: [],
            barnicesVuelta: [],
            numCambios: [],
            trazosCliente: [],
            trazosEstructural: [],
            sustratosParaEmpalme: [],
            otrosPop: [],
            metalizadosFrentes: [],
            metalizadosVuelta: [],
            laminadosFrente: [],
            laminadosVuelta: [],
            barnicesSakuraiFrente: [],
            barnicesSakuraiVuelta: [],
            realces: [],
            hotStampings: [],
            coloresHotStamping: [],
            suajes: [],
            termodobleces: [],
            hilosMonofilamentos: [],
            cintasDobleCaras: [],
            cantidadCintasXPiezas: [],
            otrosAcabados: [],
            adicionales: [],
            empaques: [],
            entregas: [],
            lugarEntregas: [],
            suajadosList: [],
        };
    },
    asignarValoresCampos(display) {
        console.log('Asignando valores a los campos de Display:', display);
        this.display.sustrato = display.sustrato || '';
        this.display.gramaje = display.gramaje || '';
        this.display.tinta_frente = display.tinta_frente || '';
        this.display.medida_final = display.medida_final || '';
        this.display.medida_final_ancho = display.medida_final_ancho || '';
        this.display.medida_final_largo = display.medida_final_largo || '';
        this.display.medida_final_alto = display.medida_final_alto || '';
        this.display.medida_extendida = display.medida_extendida || '';
        this.display.medida_extendida_ancho = display.medida_extendida_ancho || '';
        this.display.medida_extendida_largo = display.medida_extendida_largo || '';
        this.display.medida_extendida_alto = display.medida_extendida_alto || '';

        this.display.sustrato_empalme = display.sustrato_empalme || '';
        this.display.otros_display = display.otros_display || '';
        this.display.metalizado_frente = display.metalizado_frente || '';
        this.display.metalizado_vuelta = display.metalizado_vuelta || '';
        this.display.laminado_frente = display.laminado_frente || '';
        this.display.laminado_vuelta = display.laminado_vuelta || '';
        this.display.barniz_sakurai_frente = display.barniz_sakurai_frente || '';
        this.display.barniz_sakurai_vuelta = display.barniz_sakurai_vuelta || '';
        this.display.otros_metalizado_frente = display.otros_metalizado_frente || '';
        this.display.otros_metalizado_vuelta = display.otros_metalizado_vuelta || '';
        this.display.otros_laminado_frente = display.otros_laminado_frente || '';
        this.display.otros_laminado_vuelta = display.otros_laminado_vuelta || '';
        this.display.otros_barniz_frente = display.otros_barniz_frente || '';
        this.display.otros_barniz_vuelta = display.otros_barniz_vuelta || '';
        this.display.tipo_pegue_armado = display.tipo_pegue_armado || '';
        this.display.empaque_primario = display.empaque_primario || '';
        this.display.empaque_secundario = display.empaque_secundario || '';
        this.display.lugar_entrega = display.lugar_entrega || '';
        this.display.observaciones = display.observaciones || '';
        // this.display.observaciones_presupuesto = display.observaciones_presupuesto || '';
        this.display.suajado = display.suajado || '';
    },
    camposConTipo: [
        [
            'sustrato',
            'minimos',
            'select',
            'display',
            'Sustrato'
        ],
        [
            'tinta_frente',
            'minimos',
            'select',
            'display',
            'Tinta Frente'
        ],
        [
            'medida_final',
            'especificaciones',
            'select',
            'display',
            'Medida final'
        ],
        [
            'medida_final_ancho',
            'especificaciones',
            'input',
            'display',
            'Medida Final Ancho'
        ],
        [
            'medida_final_largo',
            'especificaciones',
            'input',
            'display',
            'Medida Final Largo'
        ],
        [
            'medida_final_alto',
            'especificaciones',
            'input',
            'display',
            'Medida Final Alto'
        ],
        [
            'medida_extendida',
            'especificaciones',
            'select',
            'display',
            'Medida extendida'
        ],
        [
            'medida_extendida_ancho',
            'especificaciones',
            'input',
            'display',
            'Medida Extendida Ancho'
        ],
        [
            'medida_extendida_largo',
            'especificaciones',
            'input',
            'display',
            'Medida Extendida Largo'
        ],
        [
            'medida_extendida_alto',
            'especificaciones',
            'input',
            'display',
            'Medida Extendida Alto'
        ],
        [
            'sustrato_empalme',
            'minimos',
            'select',
            'display',
            'Sustrato Empalme'
        ],
        [
            'otros_display',
            'especificaciones',
            'select',
            'display',
            'Otros Display'
        ],
        [
            'metalizado_frente',
            'especificaciones',
            'select',
            'display',
            'Metalizado Frente'
        ],
        [
            'metalizado_vuelta',
            'especificaciones',
            'select',
            'display',
            'Metalizado Vuelta'
        ],
        [
            'laminado_frente',
            'minimos',
            'select',
            'display',
            'Laminado Frente'
        ],
        [
            'laminado_vuelta',
            'minimos',
            'select',
            'display',
            'Laminado Vuelta'
        ],
        [
            'barniz_sakurai_frente',
            'minimos',
            'select',
            'display',
            'Barniz Sakurai Frente'
        ],
        [
            'barniz_sakurai_vuelta',
            'minimos',
            'select',
            'display',
            'Barniz Sakurai Vuelta'
        ],
        [
            'otros_metalizado_frente',
            'especificaciones',
            'select',
            'display',
            'Otros Metalizado Frente'
        ],
        [
            'otros_metalizado_vuelta',
            'especificaciones',
            'select',
            'display',
            'Otros Metalizado Vuelta'
        ],
        [
            'otros_laminado_frente',
            'minimos',
            'select',
            'display',
            'Otros Laminado Frente'
        ],
        [
            'otros_laminado_vuelta',
            'minimos',
            'select',
            'display',
            'Otros Laminado Vuelta'
        ],
        [
            'otros_barniz_frente',
            'minimos',
            'select',
            'display',
            'Otros Barniz Frente'
        ],
        [
            'otros_barniz_vuelta',
            'minimos',
            'select',
            'display',
            'Otros Barniz Vuelta'
        ],
        [
            'tipo_pegue_armado',
            'especificaciones',
            'select',
            'display',
            'Tipo Pegue Armado'
        ],
        [
            'empaque_primario',
            'especificaciones',
            'select',
            'display',
            'Empaque Primario'
        ],
        [
            'empaque_secundario',
            'especificaciones',
            'select',
            'display',
            'Empaque Secundario'
        ],
        [
            'lugar_entrega',
            'especificaciones',
            'select',
            'display',
            'Lugar Entrega'
        ],
        [
            'suajado',
            'mespecificacioness',
            'select',
            'display',
            'Suajado'
        ],
    ],
}