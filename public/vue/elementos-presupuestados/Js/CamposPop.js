export default {
    popCatalogos: {
        // POP
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

        // ACABADOS
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

        // ADICIONALES
        adicionales: [],

        // EMPAQUE
        empaques: [],

        // ENTREGA
        entregas: [],

        // LUGAR DE ENTREGA
        lugarEntregas: [],
        suajadosList: [],
    },
    // Valores que se envían al servidor
    pop: {
        sustrato: '',
        gramaje: '',
        tinta_frente: '',
        tinta_vuelta: '',
        medida_final: '',
        medida_extendida: '',
        barniz_frente: '',
        barniz_vuelta: '',
        num_cambios: '',
        trazo_cliente: '',
        trazo_estructural: '',
        sustrato_empalme: '',
        otros_pop: '',
        metalizado_frente: '',
        metalizado_vuelta: '',
        laminado_frente: '',
        laminado_vuelta: '',
        barniz_sakurai_frente: '',
        barniz_sakurai_vuelta: '',
        realce: '',
        placa_realce: '',
        hot_stamping: '',
        placa_hot_stamping: '',
        color_hot_stamping: '',
        suaje: '',
        termodoblez: '',
        hilo_monofilamento: '',
        cm_hilo_x_pieza: '',
        cinta_doble_cara: '',
        cantidad_cintas_x_pieza: '',
        medida_cada_cinta: '',
        otros_acabados: '',
        adicional: '',
        empaque: '',
        entrega: '',
        lugar_entrega: '',
        observaciones: '',
        observacionesExtras: [''],
        // observaciones_presupuesto: '',
        suajado: '',
    },
    limpiarCampos() {
        this.pop = {
            sustrato: '',
            gramaje: '',
            tinta_frente: '',
            tinta_vuelta: '',
            medida_final: '',
            medida_extendida: '',
            barniz_frente: '',
            barniz_vuelta: '',
            num_cambios: '',
            trazo_cliente: '',
            trazo_estructural: '',
            sustrato_empalme: '',
            otros_pop: '',
            metalizado_frente: '',
            metalizado_vuelta: '',
            laminado_frente: '',
            laminado_vuelta: '',
            barniz_sakurai_frente: '',
            barniz_sakurai_vuelta: '',
            realce: '',
            placa_realce: '',
            hot_stamping: '',
            placa_hot_stamping: '',
            color_hot_stamping: '',
            suaje: '',
            termodoblez: '',
            hilo_monofilamento: '',
            cm_hilo_x_pieza: '',
            cinta_doble_cara: '',
            cantidad_cintas_x_pieza: '',
            medida_cada_cinta: '',
            otros_acabados: '',
            adicional: '',
            empaque: '',
            entrega: '',
            lugar_entrega: '',
            observaciones: '',
            observacionesExtras: [''],
            // observaciones_presupuesto: '',
            suajado: '',
        };
        this.popCatalogos = {
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

    asignarValoresCampos(pop) {
        this.pop.sustrato = pop.sustrato || '';
        this.pop.gramaje = pop.gramaje || '';
        this.pop.tinta_frente = pop.tinta_frente || '';
        this.pop.tinta_vuelta = pop.tinta_vuelta || '';
        this.pop.medida_final = pop.medida_final || '';
        this.pop.medida_extendida = pop.medida_extendida || '';
        this.pop.barniz_frente = pop.barniz_frente || '';
        this.pop.barniz_vuelta = pop.barniz_vuelta || '';
        this.pop.num_cambios = pop.num_cambios || '';
        this.pop.trazo_cliente = pop.trazo_cliente || '';
        this.pop.trazo_estructural = pop.trazo_estructural || '';
        this.pop.sustrato_empalme = pop.sustrato_empalme || '';
        this.pop.otros_pop = pop.otros_pop || '';
        this.pop.metalizado_frente = pop.metalizado_frente || '';
        this.pop.metalizado_vuelta = pop.metalizado_vuelta || '';
        this.pop.laminado_frente = pop.laminado_frente || '';
        this.pop.laminado_vuelta = pop.laminado_vuelta || '';
        this.pop.barniz_sakurai_frente = pop.barniz_sakurai_frente || '';
        this.pop.barniz_sakurai_vuelta = pop.barniz_sakurai_vuelta || '';
        this.pop.realce = pop.realce || '';
        this.pop.placa_realce = pop.placa_realce || '';
        this.pop.hot_stamping = pop.hot_stamping || '';
        this.pop.placa_hot_stamping = pop.placa_hot_stamping || '';
        this.pop.color_hot_stamping = pop.color_hot_stamping || '';
        this.pop.suaje = pop.suaje || '';
        this.pop.termodoblez = pop.termodoblez || '';
        this.pop.hilo_monofilamento = pop.hilo_monofilamento || '';
        this.pop.cm_hilo_x_pieza = pop.cm_hilo_x_pieza || '';
        this.pop.cinta_doble_cara = pop.cinta_doble_cara || '';
        this.pop.cantidad_cintas_x_pieza = pop.cantidad_cintas_x_pieza || '';
        this.pop.medida_cada_cinta = pop.medida_cada_cinta || '';
        this.pop.otros_acabados = pop.otros_acabados || '';
        this.pop.adicional = pop.adicional || '';
        this.pop.empaque = pop.empaque || '';
        this.pop.entrega = pop.entrega || '';
        this.pop.lugar_entrega = pop.lugar_entrega || '';
        this.pop.observaciones = pop.observaciones || '';
        // this.pop.observaciones_presupuesto = pop.observaciones_presupuesto || '';
        this.pop.suajado = pop.suajado || '';
    },

    camposConTipo: [
        [
            'sustrato',
            'minimos',
            'select',
            'pop',
            'Sustrato'
        ],
        [
            'tinta_frente',
            'minimos',
            'select',
            'pop',
            'Tinta Frente'
        ],
        [
            'tinta_vuelta',
            'minimos',
            'select',
            'pop',
            'Tinta Vuelta'
        ],
        [
            'medida_final',
            'especificaciones',
            'input',
            'pop',
            'Medida Final'
        ],
        [
            'medida_extendida',
            'especificaciones',
            'input',
            'pop',
            'Medida Extendida'
        ],
        [
            'barniz_frente',
            'minimos',
            'select',
            'pop',
            'Barniz Frente'
        ],
        [
            'barniz_vuelta',
            'minimos',
            'select',
            'pop',
            'Barniz Vuelta'
        ],
        [
            'num_cambios',
            'especificaciones',
            'select',
            'pop',
            'Número de Cambios'
        ],
        [
            'trazo_cliente',
            'especificaciones',
            'select',
            'pop',
            'Trazo Cliente'
        ],
        [
            'trazo_estructural',
            'especificaciones',
            'select',
            'pop',
            'Trazo Estructural'
        ],
        [
            'sustrato_empalme',
            'minimos',
            'select',
            'pop',
            'Sustrato Empalme'
        ],
        [
            'otros_pop',
            'especificaciones',
            'select',
            'pop',
            'Otros POP'
        ],
        [
            'metalizado_frente',
            'especificaciones',
            'select',
            'pop',
            'Metalizado Frente'
        ],
        [
            'metalizado_vuelta',
            'especificaciones',
            'select',
            'pop',
            'Metalizado Vuelta'
        ],
        [
            'laminado_frente',
            'minimos',
            'select',
            'pop',
            'Laminado Frente'
        ],
        [
            'laminado_vuelta',
            'minimos',
            'select',
            'pop',
            'Laminado Vuelta'
        ],
        [
            'barniz_sakurai_frente',
            'minimos',
            'select',
            'pop',
            'Barniz Sakurai Frente'
        ],
        [
            'barniz_sakurai_vuelta',
            'minimos',
            'select',
            'pop',
            'Barniz Sakurai Vuelta'
        ],
        [
            'realce',
            'especificaciones',
            'select',
            'pop',
            'Realce'
        ],
        [
            'placa_realce',
            'especificaciones',
            'input',
            'pop',
            'Placa Realce'
        ],
        [
            'hot_stamping',
            'especificaciones',
            'select',
            'pop',
            'Hot Stamping'
        ],
        [
            'placa_hot_stamping',
            'especificaciones',
            'input',
            'pop',
            'Placa Hot Stamping'
        ],
        [
            'color_hot_stamping',
            'especificaciones',
            'select',
            'pop',
            'Color Hot Stamping'
        ],
        [
            'suaje',
            'especificaciones',
            'select',
            'pop',
            'Suaje'
        ],
        [
            'termodoblez',
            'especificaciones',
            'select',
            'pop',
            'Termodoblez'
        ],
        [
            'hilo_monofilamento',
            'especificaciones',
            'select',
            'pop',
            'Hilo Monofilamento'
        ],
        [
            'cm_hilo_x_pieza',
            'especificaciones',
            'input',
            'pop',
            'CM Hilo x Pieza'
        ],
        [
            'cinta_doble_cara',
            'especificaciones',
            'select',
            'pop',
            'Cinta Doble Cara'
        ],
        [
            'cantidad_cintas_x_pieza',
            'especificaciones',
            'select',
            'pop',
            'Cantidad Cintas x Pieza'
        ],
        [
            'medida_cada_cinta',
            'especificaciones',
            'input',
            'pop',
            'Medida Cada Cinta'
        ],
        [
            'otros_acabados',
            'especificaciones',
            'select',
            'pop',
            'Otros Acabados'
        ],
        [
            'adicional',
            'especificaciones',
            'select',
            'pop',
            'Adicional'
        ],
        [
            'empaque',
            'especificaciones',
            'select',
            'pop',
            'Empaque'
        ],
        [
            'entrega',
            'especificaciones',
            'select',
            'pop',
            'Entrega'
        ],
        [
            'lugar_entrega',
            'especificaciones',
            'select',
            'pop',
            'Lugar Entrega'
        ],
        [
            'suajado',
            'especificaciones',
            'select',
            'pop',
            'Suajado'
        ],
    ]
}