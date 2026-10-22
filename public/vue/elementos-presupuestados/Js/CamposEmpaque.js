export default {
    empaqueCatalogos: {
        // EMPAQUE
        sustratos: [],
        gramajes: [],
        tintasFrente: [],
        tintasVuelta: [],
        medidasFinal: [],
        medidasExtendida: [],
        barnicesFrente: [],
        barnicesVuelta: [],
        sustratosParaEmpalme: [],
        requieresVentana: [],
        puntajesVentana: [],
        otrosEmpaque: [],

        // ACABADOS
        metalizadosFrente: [],
        metalizadosVuelta: [],
        laminadosFrente: [],
        laminadosVuelta: [],
        barnicesSakuraiFrente: [],
        barnicesSakuraiVuelta: [],
        realces: [],
        hotStampings: [],
        coloresHotStamping: [],
        suajes: [],
        otrosAcabados: [],

        medidasFinales: [],
        medidasExtendidas: [],

        // ADICIONALES
        tiposPeguesArmado: [],
        empaques: [],
        entregas: [],
        lugarEntregas: [],

        suajadosList: [],
    },
    // Valores que se envían al servidor
    empaque: {
        sustrato: '',
        gramaje: '',
        tinta_frente: '',
        tinta_vuelta: '',
        medida_final: '',
        medida_final_ancho_general: '',
        medida_final_largo_general: '',
        medida_final_alto_general: '',

        medida_extendida: '',
        medida_extendida_ancho_general: '',
        medida_extendida_largo_general: '',
        medida_extendida_alto_general: '',
        barniz_frente: '',
        barniz_vuelta: '',
        sustrato_empalme: '',
        requiere_ventana: '',
        puntaje_ventana: '',
        otros_empaque: '',
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
        otros_acabados: '',

        sustrato_t1: '',
        gramaje_t1: '',
        tinta_frente_t1: '',
        tinta_vuelta_t1: '',
        medida_final_t1: '',
        medida_final_ancho_t1: '',
        medida_final_largo_t1: '',
        medida_final_alto_t1: '',

        medida_extendida_t1: '',
        medida_extendida_ancho_t1: '',
        medida_extendida_largo_t1: '',
        medida_extendida_alto_t1: '',

        barniz_frente_t1: '',
        barniz_vuelta_t1: '',
        sustrato_empalme_t1: '',
        requiere_ventana_t1: '',
        puntaje_ventana_t1: '',
        otros_empaque_t1: '',
        metalizado_frente_t1: '',
        metalizado_vuelta_t1: '',
        laminado_frente_t1: '',
        laminado_vuelta_t1: '',
        barniz_sakurai_frente_t1: '',
        barniz_sakurai_vuelta_t1: '',
        realce_t1: '',
        placa_realce_t1: '',
        hot_stamping_t1: '',
        placa_hot_stamping_t1: '',
        color_hot_stamping_t1: '',
        suaje_t1: '',
        otros_acabados_t1: '',

        sustrato_t2: '',
        gramaje_t2: '',
        tinta_frente_t2: '',
        tinta_vuelta_t2: '',
        medida_final_t2: '',
        medida_final_ancho_t2: '',
        medida_final_largo_t2: '',
        medida_final_alto_t2: '',

        medida_extendida_t2: '',
        medida_extendida_ancho_t2: '',
        medida_extendida_largo_t2: '',
        medida_extendida_alto_t2: '',

        barniz_frente_t2: '',
        barniz_vuelta_t2: '',
        sustrato_empalme_t2: '',
        requiere_ventana_t2: '',
        puntaje_ventana_t2: '',
        otros_empaque_t2: '',
        metalizado_frente_t2: '',
        metalizado_vuelta_t2: '',
        laminado_frente_t2: '',
        laminado_vuelta_t2: '',
        barniz_sakurai_frente_t2: '',
        barniz_sakurai_vuelta_t2: '',
        realce_t2: '',
        placa_realce_t2: '',
        hot_stamping_t2: '',
        placa_hot_stamping_t2: '',
        color_hot_stamping_t2: '',
        suaje_t2: '',
        otros_acabados_t2: '',

        tipo_pegue_armado: '',
        empaque: '',
        entrega: '',
        lugar_entrega: '',
        observaciones: '',
        observacionesExtras: [''],
        // observaciones_presupuesto: '',
        suajado: '',
    },
    limpiarCampos() {
        this.empaque = {
            sustrato: '',
            gramaje: '',
            tinta_frente: '',
            tinta_vuelta: '',
            medida_final: '',
            medida_final_ancho: '',
            medida_final_largo: '',
            medida_final_alto: '',

            medida_extendida: '',
            medida_extendida_ancho: '',
            medida_extendida_largo: '',
            medida_extendida_alto: '',

            barniz_frente: '',
            barniz_vuelta: '',
            sustrato_empalme: '',
            requiere_ventana: '',
            puntaje_ventana: '',
            otros_empaque: '',
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
            otros_acabados: '',

            sustrato_t1: '',
            gramaje_t1: '',
            tinta_frente_t1: '',
            tinta_vuelta_t1: '',
            medida_final_t1: '',
            medida_final_ancho_t1: '',
            medida_final_largo_t1: '',
            medida_final_alto_t1: '',
            
            medida_extendida_t1: '',
            medida_extendida_ancho_t1: '',
            medida_extendida_largo_t1: '',
            medida_extendida_alto_t1: '',
            
            barniz_frente_t1: '',
            barniz_vuelta_t1: '',
            sustrato_empalme_t1: '',
            requiere_ventana_t1: '',
            puntaje_ventana_t1: '',
            otros_empaque_t1: '',
            metalizado_frente_t1: '',
            metalizado_vuelta_t1: '',
            laminado_frente_t1: '',
            laminado_vuelta_t1: '',
            barniz_sakurai_frente_t1: '',
            barniz_sakurai_vuelta_t1: '',
            realce_t1: '',
            placa_realce_t1: '',
            hot_stamping_t1: '',
            placa_hot_stamping_t1: '',
            color_hot_stamping_t1: '',
            suaje_t1: '',
            otros_acabados_t1: '',

            sustrato_t2: '',
            gramaje_t2: '',
            tinta_frente_t2: '',
            tinta_vuelta_t2: '',
            medida_final_t2: '',
            medida_final_ancho_t2: '',
            medida_final_largo_t2: '',
            medida_final_alto_t2: '',

            medida_extendida_t2: '',
            medida_extendida_ancho_t2: '',
            medida_extendida_largo_t2: '',
            medida_extendida_alto_t2: '',
            
            barniz_frente_t2: '',
            barniz_vuelta_t2: '',
            sustrato_empalme_t2: '',
            requiere_ventana_t2: '',
            puntaje_ventana_t2: '',
            otros_empaque_t2: '',
            metalizado_frente_t2: '',
            metalizado_vuelta_t2: '',
            laminado_frente_t2: '',
            laminado_vuelta_t2: '',
            barniz_sakurai_frente_t2: '',
            barniz_sakurai_vuelta_t2: '',
            realce_t2: '',
            placa_realce_t2: '',
            hot_stamping_t2: '',
            placa_hot_stamping_t2: '',
            color_hot_stamping_t2: '',
            suaje_t2: '',
            otros_acabados_t2: '',

            tipo_pegue_armado: '',
            suaje: '',
            empaque: '',
            entrega: '',
            lugar_entrega: '',
            observaciones: '',
            observacionesExtras: [''],
            // observaciones_presupuesto: '',
            suajado: '',
        };
        this.empaqueCatalogos = {
            sustratos: [],
            gramajes: [],
            tintasFrente: [],
            tintasVuelta: [],
            medidasFinal: [],
            medidasExtendida: [],
            barnicesFrente: [],
            barnicesVuelta: [],
            sustratosParaEmpalme: [],
            requieresVentana: [],
            puntajesVentana: [],
            otrosEmpaque: [],
            metalizadosFrente: [],
            metalizadosVuelta: [],
            laminadosFrente: [],
            laminadosVuelta: [],
            barnicesSakuraiFrente: [],
            barnicesSakuraiVuelta: [],
            realces: [],
            hotStampings: [],
            medidasFinales: [],
            medidasExtendidas: [],
            coloresHotStamping: [],
            suajes: [],
            otrosAcabados: [],
            tiposPeguesArmado: [],
            empaques: [],
            entregas: [],
            lugarEntregas: [],
            suajadosList: [],
        };
    },
    asignarValoresCampos(empaque) {
        this.empaque.sustrato = empaque.sustrato || '';
        this.empaque.gramaje = empaque.gramaje || '';
        this.empaque.tinta_frente = empaque.tinta_frente || '';
        this.empaque.tinta_vuelta = empaque.tinta_vuelta || '';
        this.empaque.medida_final = empaque.medida_final || '';
        this.empaque.medida_final_ancho_general = empaque.medida_final_ancho_general || '';
        this.empaque.medida_final_largo_general = empaque.medida_final_largo_general || '';
        this.empaque.medida_final_alto_general = empaque.medida_final_alto_general || '';

        this.empaque.medida_extendida = empaque.medida_extendida || '';
        this.empaque.medida_extendida_ancho_general = empaque.medida_extendida_ancho_general || '';
        this.empaque.medida_extendida_largo_general = empaque.medida_extendida_largo_general || '';
        this.empaque.medida_extendida_alto_general = empaque.medida_extendida_alto_general || '';

        this.empaque.barniz_frente = empaque.barniz_frente || '';
        this.empaque.barniz_vuelta = empaque.barniz_vuelta || '';
        this.empaque.sustrato_empalme = empaque.sustrato_empalme || '';
        this.empaque.requiere_ventana = empaque.requiere_ventana || '';
        this.empaque.puntaje_ventana = empaque.puntaje_ventana || '';
        this.empaque.otros_empaque = empaque.otros_empaque || '';
        this.empaque.metalizado_frente = empaque.metalizado_frente || '';
        this.empaque.metalizado_vuelta = empaque.metalizado_vuelta || '';
        this.empaque.laminado_frente = empaque.laminado_frente || '';
        this.empaque.laminado_vuelta = empaque.laminado_vuelta || '';
        this.empaque.barniz_sakurai_frente = empaque.barniz_sakurai_frente || '';
        this.empaque.barniz_sakurai_vuelta = empaque.barniz_sakurai_vuelta || '';
        this.empaque.realce = empaque.realce || '';
        this.empaque.placa_realce = empaque.placa_realce || '';
        this.empaque.hot_stamping = empaque.hot_stamping || '';
        this.empaque.placa_hot_stamping = empaque.placa_hot_stamping || '';
        this.empaque.color_hot_stamping = empaque.color_hot_stamping || '';
        this.empaque.suaje = empaque.suaje || '';
        this.empaque.suajado = empaque.suajado || '';
        this.empaque.otros_acabados = empaque.otros_acabados || '';

        this.empaque.sustrato_t1 = empaque.sustrato_t1 || '';
        this.empaque.gramaje_t1 = empaque.gramaje_t1 || '';
        this.empaque.tinta_frente_t1 = empaque.tinta_frente_t1 || '';
        this.empaque.tinta_vuelta_t1 = empaque.tinta_vuelta_t1 || '';
        this.empaque.medida_final_t1 = empaque.medida_final_t1 || '';
        this.empaque.medida_final_ancho_t1 = empaque.medida_final_ancho_t1 || '';
        this.empaque.medida_final_largo_t1 = empaque.medida_final_largo_t1 || '';
        this.empaque.medida_final_alto_t1 = empaque.medida_final_alto_t1 || '';

        this.empaque.medida_extendida_t1 = empaque.medida_extendida_t1 || '';
        this.empaque.medida_extendida_ancho_t1 = empaque.medida_extendida_ancho_t1 || '';
        this.empaque.medida_extendida_largo_t1 = empaque.medida_extendida_largo_t1 || '';
        this.empaque.medida_extendida_alto_t1 = empaque.medida_extendida_alto_t1 || '';
        
        this.empaque.barniz_frente_t1 = empaque.barniz_frente_t1 || '';
        this.empaque.barniz_vuelta_t1 = empaque.barniz_vuelta_t1 || '';
        this.empaque.sustrato_empalme_t1 = empaque.sustrato_empalme_t1 || '';
        this.empaque.requiere_ventana_t1 = empaque.requiere_ventana_t1 || '';
        this.empaque.puntaje_ventana_t1 = empaque.puntaje_ventana_t1 || '';
        this.empaque.otros_empaque_t1 = empaque.otros_empaque_t1 || '';
        this.empaque.metalizado_frente_t1 = empaque.metalizado_frente_t1 || '';
        this.empaque.metalizado_vuelta_t1 = empaque.metalizado_vuelta_t1 || '';
        this.empaque.laminado_frente_t1 = empaque.laminado_frente_t1 || '';
        this.empaque.laminado_vuelta_t1 = empaque.laminado_vuelta_t1 || '';
        this.empaque.barniz_sakurai_frente_t1 = empaque.barniz_sakurai_frente_t1 || '';
        this.empaque.barniz_sakurai_vuelta_t1 = empaque.barniz_sakurai_vuelta_t1 || '';
        this.empaque.realce_t1 = empaque.realce_t1 || '';
        this.empaque.placa_realce_t1 = empaque.placa_realce_t1 || '';
        this.empaque.hot_stamping_t1 = empaque.hot_stamping_t1 || '';
        this.empaque.placa_hot_stamping_t1 = empaque.placa_hot_stamping_t1 || '';
        this.empaque.color_hot_stamping_t1 = empaque.color_hot_stamping_t1 || '';
        this.empaque.suaje_t1 = empaque.suaje_t1 || '';
        this.empaque.suajado_t1 = empaque.suajado_t1 || '';
        this.empaque.otros_acabados_t1 = empaque.otros_acabados_t1 || '';
        
        this.empaque.sustrato_t2 = empaque.sustrato_t2 || '';
        this.empaque.gramaje_t2 = empaque.gramaje_t2 || '';
        this.empaque.tinta_frente_t2 = empaque.tinta_frente_t2 || '';
        this.empaque.tinta_vuelta_t2 = empaque.tinta_vuelta_t2 || '';
        this.empaque.medida_final_t2 = empaque.medida_final_t2 || '';
        this.empaque.medida_final_ancho_t2 = empaque.medida_final_ancho_t2 || '';
        this.empaque.medida_final_largo_t2 = empaque.medida_final_largo_t2 || '';
        this.empaque.medida_final_alto_t2 = empaque.medida_final_alto_t2 || '';

        this.empaque.medida_extendida_t2 = empaque.medida_extendida_t2 || '';
        this.empaque.medida_extendida_ancho_t2 = empaque.medida_extendida_ancho_t2 || '';
        this.empaque.medida_extendida_largo_t2 = empaque.medida_extendida_largo_t2 || '';
        this.empaque.medida_extendida_alto_t2 = empaque.medida_extendida_alto_t2 || '';

        this.empaque.barniz_frente_t2 = empaque.barniz_frente_t2 || '';
        this.empaque.barniz_vuelta_t2 = empaque.barniz_vuelta_t2 || '';
        this.empaque.sustrato_empalme_t2 = empaque.sustrato_empalme_t2 || '';
        this.empaque.requiere_ventana_t2 = empaque.requiere_ventana_t2 || '';
        this.empaque.puntaje_ventana_t2 = empaque.puntaje_ventana_t2 || '';
        this.empaque.otros_empaque_t2 = empaque.otros_empaque_t2 || '';
        this.empaque.metalizado_frente_t2 = empaque.metalizado_frente_t2 || '';
        this.empaque.metalizado_vuelta_t2 = empaque.metalizado_vuelta_t2 || '';
        this.empaque.laminado_frente_t2 = empaque.laminado_frente_t2 || '';
        this.empaque.laminado_vuelta_t2 = empaque.laminado_vuelta_t2 || '';
        this.empaque.barniz_sakurai_frente_t2 = empaque.barniz_sakurai_frente_t2 || '';
        this.empaque.barniz_sakurai_vuelta_t2 = empaque.barniz_sakurai_vuelta_t2 || '';
        this.empaque.realce_t2 = empaque.realce_t2 || '';
        this.empaque.placa_realce_t2 = empaque.placa_realce_t2 || '';
        this.empaque.hot_stamping_t2 = empaque.hot_stamping_t2 || '';
        this.empaque.placa_hot_stamping_t2 = empaque.placa_hot_stamping_t2 || '';
        this.empaque.color_hot_stamping_t2 = empaque.color_hot_stamping_t2 || '';
        this.empaque.suaje_t2 = empaque.suaje_t2 || '';
        this.empaque.suajado_t2 = empaque.suajado_t2 || '';
        this.empaque.otros_acabados_t2 = empaque.otros_acabados_t2 || '';

        this.empaque.tipo_pegue_armado = empaque.tipo_pegue_armado || '';
        this.empaque.empaque = empaque.empaque || '';
        this.empaque.entrega = empaque.entrega || '';
        this.empaque.lugar_entrega = empaque.lugar_entrega || '';
        this.empaque.observaciones = empaque.observaciones || '';
        // this.empaque.observaciones_presupuesto = empaque.observaciones_presupuesto || '';
    },

    camposConTipo: [
        [
            'sustrato',
            'minimos',
            'select',
            'empaque',
            'Sustrato'
        ],
        [
            'tinta_frente',
            'minimos',
            'select',
            'empaque',
            'Tinta Frente'
        ],
        [
            'tinta_vuelta',
            'minimos',
            'select',
            'empaque',
            'Tinta Vuelta'
        ],
        [
            'medida_final',
            'especificaciones',
            'input',
            'empaque',
            'Medida final'
        ],
        [
            'medida_final_ancho_general',
            'especificaciones',
            'input',
            'empaque',
            'Medida Final Ancho'
        ],
        [
            'medida_final_largo_general',
            'especificaciones',
            'input',
            'empaque',
            'Medida Final Largo'
        ],
        [
            'medida_final_alto_general',
            'especificaciones',
            'input',
            'empaque',
            'Medida Final Alto'
        ],
        [
            'medida_extendida',
            'especificaciones',
            'input',
            'empaque',
            'Medida extendida'
        ],
        [
            'medida_extendida_ancho_general',
            'especificaciones',
            'input',
            'empaque',
            'Medida Extendida Ancho'
        ],
        [
            'medida_extendida_largo_general',
            'especificaciones',
            'input',
            'empaque',
            'Medida Extendida Largo'
        ],
        [
            'medida_extendida_alto_general',
            'especificaciones',
            'input',
            'empaque',
            'Medida Extendida Alto'
        ],
        [
            'barniz_frente',
            'minimos',
            'select',
            'empaque',
            'Barniz Frente'
        ],
        [
            'barniz_vuelta',
            'minimos',
            'select',
            'empaque',
            'Barniz Vuelta'
        ],
        [
            'sustrato_empalme',
            'minimos',
            'select',
            'empaque',
            'Sustrato Empalme'
        ],
        [
            'requiere_ventana',
            'especificaciones',
            'select',
            'empaque',
            'Requiere ventana'
        ],
        [
            'puntaje_ventana',
            'especificaciones',
            'select',
            'empaque',
            'Puntaje ventana'
        ],
        [
            'otros_empaque',
            'especificaciones',
            'select',
            'empaque',
            'Otros Empaque'
        ],
        [
            'metalizado_frente',
            'especificaciones',
            'select',
            'empaque',
            'Metalizado Frente'
        ],
        [
            'metalizado_vuelta',
            'especificaciones',
            'select',
            'empaque',
            'Metalizado Vuelta'
        ],
        [
            'laminado_frente',
            'minimos',
            'select',
            'empaque',
            'Laminado Frente'
        ],
        [
            'laminado_vuelta',
            'minimos',
            'select',
            'empaque',
            'Laminado Vuelta'
        ],
        [
            'barniz_sakurai_frente',
            'minimos',
            'select',
            'empaque',
            'Barniz Sakurai Frente'
        ],
        [
            'barniz_sakurai_vuelta',
            'minimos',
            'select',
            'empaque',
            'Barniz Sakurai Vuelta'
        ],
        [
            'realce',
            'especificaciones',
            'select',
            'empaque',
            'Realce'
        ],
        [
            'placa_realce',
            'especificaciones',
            'input',
            'empaque',
            'Placa Realce'
        ],
        [
            'hot_stamping',
            'especificaciones',
            'select',
            'empaque',
            'Hot Stamping'
        ],
        [
            'placa_hot_stamping',
            'especificaciones',
            'input',
            'empaque',
            'Placa Hot Stamping'
        ],
        [
            'color_hot_stamping',
            'especificaciones',
            'select',
            'empaque',
            'Color Hot Stamping'
        ],
        [
            'suaje',
            'especificaciones',
            'select',
            'empaque',
            'Suaje'
        ],
        [
            'otros_acabados',
            'especificaciones',
            'select',
            'empaque',
            'Otros Acabados'
        ],
        [
            'sustrato_t1',
            'minimos',
            'select',
            'empaque',
            'Sustrato T1'
        ],
        [
            'tinta_frente_t1',
            'minimos',
            'select',
            'empaque',
            'Tinta Frente T1'
        ],
        [
            'tinta_vuelta_t1',
            'minimos',
            'select',
            'empaque',
            'Tinta Vuelta T1'
        ],
        [
            'medida_final_t1',
            'especificaciones',
            'input',
            'empaque',
            'Medida Final T1'
        ],
        [
            'medida_final_ancho_t1',
            'especificaciones',
            'input',
            'empaque',
            'Medida Final Ancho T1'
        ],
        [
            'medida_final_largo_t1',
            'especificaciones',
            'input',
            'empaque',
            'Medida Final Largo T1'
        ],
        [
            'medida_final_alto_t1',
            'especificaciones',
            'input',
            'empaque',
            'Medida Final Alto T1'
        ],
        [
            'medida_extendida_t1',
            'especificaciones',
            'input',
            'empaque',
            'Medida Extendida T1'
        ],
        [
            'medida_extendida_ancho_t1',
            'especificaciones',
            'input',
            'empaque',
            'Medida Extendida Ancho T1'
        ],
        [
            'medida_extendida_largo_t1',
            'especificaciones',
            'input',
            'empaque',
            'Medida Extendida Largo T1'
        ],
        [
            'medida_extendida_alto_t1',
            'especificaciones',
            'input',
            'empaque',
            'Medida Extendida Alto T1'
        ],
        [
            'barniz_frente_t1',
            'minimos',
            'select',
            'empaque',
            'Barniz Frente T1'
        ],
        [
            'barniz_vuelta_t1',
            'minimos',
            'select',
            'empaque',
            'Barniz Vuelta T1'
        ],
        [
            'sustrato_empalme_t1',
            'minimos',
            'select',
            'empaque',
            'Sustrato Empalme T1'
        ],
        [
            'requiere_ventana_t1',
            'especificaciones',
            'select',
            'empaque',
            'Requiere Ventana T1'
        ],
        [
            'puntaje_ventana_t1',
            'especificaciones',
            'select',
            'empaque',
            'Puntaje Ventana T1'
        ],
        [
            'otros_empaque_t1',
            'especificaciones',
            'select',
            'empaque',
            'Otros Empaque T1'
        ],
        [
            'metalizado_frente_t1',
            'especificaciones',
            'select',
            'empaque',
            'Metalizado Frente T1'
        ],
        [
            'metalizado_vuelta_t1',
            'especificaciones',
            'select',
            'empaque',
            'Metalizado Vuelta T1'
        ],
        [
            'laminado_frente_t1',
            'minimos',
            'select',
            'empaque',
            'Laminado Frente T1'
        ],
        [
            'laminado_vuelta_t1',
            'minimos',
            'select',
            'empaque',
            'Laminado Vuelta T1'
        ],
        [
            'barniz_sakurai_frente_t1',
            'minimos',
            'select',
            'empaque',
            'Barniz Sakurai Frente T1'
        ],
        [
            'barniz_sakurai_vuelta_t1',
            'minimos',
            'select',
            'empaque',
            'Barniz Sakurai Vuelta T1'
        ],
        [
            'realce_t1',
            'especificaciones',
            'select',
            'empaque',
            'Realce T1'
        ],
        [
            'placa_realce_t1',
            'especificaciones',
            'input',
            'empaque',
            'Placa Realce T1'
        ],
        [
            'hot_stamping_t1',
            'especificaciones',
            'select',
            'empaque',
            'Hot Stamping T1'
        ],
        [
            'placa_hot_stamping_t1',
            'especificaciones',
            'input',
            'empaque',
            'Placa Hot Stamping T1'
        ],
        [
            'color_hot_stamping_t1',
            'especificaciones',
            'select',
            'empaque',
            'Color Hot Stamping T1'
        ],
        [
            'suaje_t1',
            'especificaciones',
            'select',
            'empaque',
            'Suaje T1'
        ],
        [
            'otros_acabados_t1',
            'especificaciones',
            'select',
            'empaque',
            'Otros Acabados T1'
        ],
        [
            'sustrato_t2',
            'minimos',
            'select',
            'empaque',
            'Sustrato T2'
        ],
        [
            'tinta_frente_t2',
            'minimos',
            'select',
            'empaque',
            'Tinta Frente T2'
        ],
        [
            'tinta_vuelta_t2',
            'minimos',
            'select',
            'empaque',
            'Tinta Vuelta T2'
        ],
        [
            'medida_final_t2',
            'especificaciones',
            'input',
            'empaque',
            'Medida Final T2'
        ],
        [
            'medida_final_ancho_t2',
            'especificaciones',
            'input',
            'empaque',
            'Medida Final Ancho T2'
        ],
        [
            'medida_final_largo_t2',
            'especificaciones',
            'input',
            'empaque',
            'Medida Final Largo T2'
        ],
        [
            'medida_final_alto_t2',
            'especificaciones',
            'input',
            'empaque',
            'Medida Final Alto T2'
        ],
        [
            'medida_extendida_t2',
            'especificaciones',
            'input',
            'empaque',
            'Medida Extendida T2'
        ],
        [
            'medida_extendida_ancho_t2',
            'especificaciones',
            'input',
            'empaque',
            'Medida Extendida Ancho T2'
        ],
        [
            'medida_extendida_largo_t2',
            'especificaciones',
            'input',
            'empaque',
            'Medida Extendida Largo T2'
        ],
        [
            'medida_extendida_alto_t2',
            'especificaciones',
            'input',
            'empaque',
            'Medida Extendida Alto T2'
        ],
        [
            'barniz_frente_t2',
            'minimos',
            'select',
            'empaque',
            'Barniz Frente T2'
        ],
        [
            'barniz_vuelta_t2',
            'minimos',
            'select',
            'empaque',
            'Barniz Vuelta T2'
        ],
        [
            'sustrato_empalme_t2',
            'minimos',
            'select',
            'empaque',
            'Sustrato Empalme T2'
        ],
        [
            'requiere_ventana_t2',
            'especificaciones',
            'select',
            'empaque',
            'Requiere Ventana T2'
        ],
        [
            'puntaje_ventana_t2',
            'especificaciones',
            'select',
            'empaque',
            'Puntaje Ventana T2'
        ],
        [
            'otros_empaque_t2',
            'especificaciones',
            'select',
            'empaque',
            'Otros Empaque T2'
        ],
        [
            'metalizado_frente_t2',
            'especificaciones',
            'select',
            'empaque',
            'Metalizado Frente T2'
        ],
        [
            'metalizado_vuelta_t2',
            'especificaciones',
            'select',
            'empaque',
            'Metalizado Vuelta T2'
        ],
        [
            'laminado_frente_t2',
            'minimos',
            'select',
            'empaque',
            'Laminado Frente T2'
        ],
        [
            'laminado_vuelta_t2',
            'minimos',
            'select',
            'empaque',
            'Laminado Vuelta T2'
        ],
        [
            'barniz_sakurai_frente_t2',
            'minimos',
            'select',
            'empaque',
            'Barniz Sakurai Frente T2'
        ],
        [
            'barniz_sakurai_vuelta_t2',
            'minimos',
            'select',
            'empaque',
            'Barniz Sakurai Vuelta T2'
        ],
        [
            'realce_t2',
            'especificaciones',
            'select',
            'empaque',
            'Realce T2'
        ],
        [
            'placa_realce_t2',
            'especificaciones',
            'input',
            'empaque',
            'Placa Realce T2'
        ],
        [
            'hot_stamping_t2',
            'especificaciones',
            'select',
            'empaque',
            'Hot Stamping T2'
        ],
        [
            'placa_hot_stamping_t2',
            'especificaciones',
            'input',
            'empaque',
            'Placa Hot Stamping T2'
        ],
        [
            'color_hot_stamping_t2',
            'especificaciones',
            'select',
            'empaque',
            'Color Hot Stamping T2'
        ],
        [
            'suaje_t2',
            'especificaciones',
            'select',
            'empaque',
            'Suaje T2'
        ],
        [
            'otros_acabados_t2',
            'especificaciones',
            'select',
            'empaque',
            'Otros Acabados T2'
        ],
        [
            'tipo_pegue_armado',
            'especificaciones',
            'select',
            'empaque',
            'Tipo Pegue Armado'
        ],
        [
            'empaque',
            'especificaciones',
            'select',
            'empaque',
            'Empaque'
        ],
        [
            'entrega',
            'especificaciones',
            'select',
            'empaque',
            'Entrega'
        ],
        [
            'lugar_entrega',
            'especificaciones',
            'select',
            'empaque',
            'Lugar Entrega'
        ],
        [
            'suajado',
            'especificaciones',
            'select',
            'empaque',
            'Suajado'
        ]
    ],
}