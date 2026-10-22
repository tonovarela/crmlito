export default {
    naEditorialForro: false, // Estado para el checkbox de Editorial Forro
    naEditorialGuarda: false, // Estado para el checkbox de Editorial Guarda
    naAcabadoForro: false, // Estado para el checkbox de Acabado Forro
    
    comercialCatalogos: {
        sustratosInteriores: [],
        gramajesInteriores: [],
        tintasFrentesInteriores: [],
        tintasVueltaInteriores: [],
        barnicesFrenteInteriores: [],
        barnicesVueltaInteriores: [],

        metalizadosFrentesInteriores: [],
        metalizadosVueltaInteriores: [],
        laminadosFrenteInteriores: [],
        laminadosVueltaInteriores: [],
        barnicesSakuraiFrenteInteriores: [],
        barnicesSakuraiVueltaInteriores: [],
        doblecesInteriores: [],
        embolsadosRetractiladosInteriores: [],
        perforadosInteriores: [],
        suajesInteriores: [],
        otrosInteriores: [],

        sustratosForro: [],
        gramajesForro: [],
        tintasFrentesForro: [],
        tintasVueltaForro: [],
        barnicesFrenteForro: [],
        barnicesVueltaForro: [],

        metalizadosFrentesForro: [],
        metalizadosVueltaForro: [],
        laminadosFrenteForro: [],
        laminadosVueltaForro: [],
        barnicesSakuraiFrenteForro: [],
        barnicesSakuraiVueltaForro: [],
        realcesForro: [],
        hotStampingsForro: [],
        coloresHotStampingForro: [],
        suajesForro: [],

        sustratosGuardas: [],
        gramajesGuardas: [],
        tintasFrentesGuardas: [],
        tintasVueltaGuardas: [],
        barnicesFrenteGuardas: [],
        barnicesVueltaGuardas: [],

        encuadernados: [],
        empaques: [],
        entregas: [],
        lugarEntregas: [],

        suajadosList: [],
        suajadosForroList: [],
    },
    // Valores que se envían al servidor
    comercial: {
        // Campos de Editorial interiores
        num_paginas_interiores: '',
        sustrato_interiores: '',
        gramaje_interiores: '',
        tinta_frente_interiores: '',
        tinta_vuelta_interiores: '',
        medida_final_interiores: '',
        medida_extendida_interiores: '',
        barniz_frente_interiores: '',
        barniz_vuelta_interiores: '',

        // Campos de Acabado interiores
        metalizado_frente_interiores: '',
        metalizado_vuelta_interiores: '',
        laminado_frente_interiores: '',
        laminado_vuelta_interiores: '',
        barniz_sakurai_frente_interiores: '',
        barniz_sakurai_vuelta_interiores: '',
        doblez: '',
        embolsado_retractilado: '',
        perforado: '',
        suaje_interiores: '',
        otros_interiores: '',

        // Campos de Editorial Forro
        num_paginas_forro: '',
        sustrato_forro: '',
        gramaje_forro: '',
        tinta_frente_forro: '',
        tinta_vuelta_forro: '',
        medida_final_forro: '',
        medida_extendida_forro: '',
        barniz_frente_forro: '',
        barniz_vuelta_forro: '',

        // Campos de Acabado Forro
        metalizado_frente_forro: '',
        metalizado_vuelta_forro: '',
        laminado_frente_forro: '',
        laminado_vuelta_forro: '',
        barniz_sakurai_frente_forro: '',
        barniz_sakurai_vuelta_forro: '',
        realce_forro: '',
        placa_realce_forro: '',
        hot_stamping_forro: '',
        placa_hot_stamping_forro: '',
        color_hot_stamping_forro: '',
        suaje_forro: '',

        // Campos de Editorial Guarda
        num_paginas_guardas: '',
        sustrato_guardas: '',
        gramaje_guardas: '',
        tinta_frente_guardas: '',
        tinta_vuelta_guardas: '',
        medida_final_guardas: '',
        medida_extendida_guardas: '',
        barniz_frente_guardas: '',
        barniz_vuelta_guardas: '',

        // Campos de adicionales
        encuadernado: '',
        empaque: '',
        entrega: '',
        lugar_entrega: '',
        observaciones: '',
        observacionesExtras: [''],
        // observaciones_presupuesto: '',

        suajado: '',
        suajado_forro: '',
    },
    limpiarCampos() {
        this.comercial = {
            num_paginas_forro: '',
            sustrato_forro: '',
            gramaje_forro: '',
            tinta_frente_forro: '',
            tinta_vuelta_forro: '',
            medida_final_forro: '',
            medida_extendida_forro: '',
            barniz_frente_forro: '',
            barniz_vuelta_forro: '',

            num_paginas_interiores: '',
            sustrato_interiores: '',
            gramaje_interiores: '',
            tinta_frente_interiores: '',
            tinta_vuelta_interiores: '',
            medida_final_interiores: '',
            medida_extendida_interiores: '',
            barniz_frente_interiores: '',
            barniz_vuelta_interiores: '',

            num_paginas_guardas: '',
            sustrato_guardas: '',
            gramaje_guardas: '',
            tinta_frente_guardas: '',
            tinta_vuelta_guardas: '',
            medida_final_guardas: '',
            medida_extendida_guardas: '',
            barniz_frente_guardas: '',
            barniz_vuelta_guardas: '',

            metalizado_frente_forro: '',
            metalizado_vuelta_forro: '',
            laminado_frente_forro: '',
            laminado_vuelta_forro: '',
            barniz_sakurai_frente_forro: '',
            barniz_sakurai_vuelta_forro: '',
            realce_forro: '',
            placa_realce_forro: '',
            hot_stamping_forro: '',
            placa_hot_stamping_forro: '',
            color_hot_stamping_forro: '',
            suaje_forro: '',

            metalizado_frente_interiores: '',
            metalizado_vuelta_interiores: '',
            laminado_frente_interiores: '',
            laminado_vuelta_interiores: '',
            barniz_sakurai_frente_interiores: '',
            barniz_sakurai_vuelta_interiores: '',
            doblez: '',
            embolsado_retractilado: '',
            perforado: '',
            suaje_interiores: '',
            otros_interiores: '',

            encuadernado: '',
            empaque: '',
            entrega: '',
            lugar_entrega: '',
            observaciones: '',
            observacionesExtras: [''],
            // observaciones_presupuesto: '',

            suajado: '',
            suajado_forro: '',
        };
        this.comercialCatalogos = {
            sustratosForro: [],
            gramajesForro: [],
            tintasFrentesForro: [],
            tintasVueltaForro: [],
            barnicesFrenteForro: [],
            barnicesVueltaForro: [],

            sustratosInteriores: [],
            gramajesInteriores: [],
            tintasFrentesInteriores: [],
            tintasVueltaInteriores: [],
            barnicesFrenteInteriores: [],
            barnicesVueltaInteriores: [],

            sustratosGuardas: [],
            gramajesGuardas: [],
            tintasFrentesGuardas: [],
            tintasVueltaGuardas: [],
            barnicesFrenteGuardas: [],
            barnicesVueltaGuardas: [],

            metalizadosFrentesForro: [],
            metalizadosVueltaForro: [],
            laminadosFrenteForro: [],
            laminadosVueltaForro: [],
            barnicesSakuraiFrenteForro: [],
            barnicesSakuraiVueltaForro: [],
            realcesForro: [],
            hotStampingsForro: [],
            coloresHotStampingForro: [],
            suajesForro: [],

            metalizadosFrentesInteriores: [],
            metalizadosVueltaInteriores: [],
            laminadosFrenteInteriores: [],
            laminadosVueltaInteriores: [],
            barnicesSakuraiFrenteInteriores: [],
            barnicesSakuraiVueltaInteriores: [],
            doblecesInteriores: [],
            embolsadosRetractiladosInteriores: [],
            perforadosInteriores: [],
            suajesInteriores: [],
            otrosInteriores: [],

            encuadernados: [],
            empaques: [],
            entregas: [],
            lugarEntregas: [],

            suajadosList: [],
            suajadosForroList: [],
        };
    },
    asignarValoresCampos(comercial) {
        this.comercial.num_paginas_forro = comercial.num_paginas_forro || '';
        this.comercial.sustrato_forro = comercial.sustrato_forro || '';
        this.comercial.gramaje_forro = comercial.gramaje_forro || '';
        this.comercial.tinta_frente_forro = comercial.tinta_frente_forro || '';
        this.comercial.tinta_vuelta_forro = comercial.tinta_vuelta_forro || '';
        this.comercial.medida_final_forro = comercial.medida_final_forro || '';
        this.comercial.medida_extendida_forro = comercial.medida_extendida_forro || '';
        this.comercial.barniz_frente_forro = comercial.barniz_frente_forro || '';
        this.comercial.barniz_vuelta_forro = comercial.barniz_vuelta_forro || '';

        this.comercial.num_paginas_interiores = comercial.num_paginas_interiores || '';
        this.comercial.sustrato_interiores = comercial.sustrato_interiores || '';
        this.comercial.gramaje_interiores = comercial.gramaje_interiores || '';
        this.comercial.tinta_frente_interiores = comercial.tinta_frente_interiores || '';
        this.comercial.tinta_vuelta_interiores = comercial.tinta_vuelta_interiores || '';
        this.comercial.medida_final_interiores = comercial.medida_final_interiores || '';
        this.comercial.medida_extendida_interiores = comercial.medida_extendida_interiores || '';
        this.comercial.barniz_frente_interiores = comercial.barniz_frente_interiores || '';
        this.comercial.barniz_vuelta_interiores = comercial.barniz_vuelta_interiores || '';

        this.comercial.num_paginas_guardas = comercial.num_paginas_guardas || '';
        this.comercial.sustrato_guardas = comercial.sustrato_guardas || '';
        this.comercial.gramaje_guardas = comercial.gramaje_guardas || '';
        this.comercial.tinta_frente_guardas = comercial.tinta_frente_guardas || '';
        this.comercial.tinta_vuelta_guardas = comercial.tinta_vuelta_guardas || '';
        this.comercial.medida_final_guardas = comercial.medida_final_guardas || '';
        this.comercial.medida_extendida_guardas = comercial.medida_extendida_guardas || '';
        this.comercial.barniz_frente_guardas = comercial.barniz_frente_guardas || '';
        this.comercial.barniz_vuelta_guardas = comercial.barniz_vuelta_guardas || '';

        this.comercial.metalizado_frente_forro = comercial.metalizado_frente_forro || '';
        this.comercial.metalizado_vuelta_forro = comercial.metalizado_vuelta_forro || '';
        this.comercial.laminado_frente_forro = comercial.laminado_frente_forro || '';
        this.comercial.laminado_vuelta_forro = comercial.laminado_vuelta_forro || '';
        this.comercial.barniz_sakurai_frente_forro = comercial.barniz_sakurai_frente_forro || '';
        this.comercial.barniz_sakurai_vuelta_forro = comercial.barniz_sakurai_vuelta_forro || '';
        this.comercial.realce_forro = comercial.realce_forro || '';
        this.comercial.placa_realce_forro = comercial.placa_realce_forro || '';
        this.comercial.hot_stamping_forro = comercial.hot_stamping_forro || '';
        this.comercial.placa_hot_stamping_forro = comercial.placa_hot_stamping_forro || '';
        this.comercial.color_hot_stamping_forro = comercial.color_hot_stamping_forro || '';
        this.comercial.suaje_forro = comercial.suaje_forro || '';

        this.comercial.metalizado_frente_interiores = comercial.metalizado_frente_interiores || '';
        this.comercial.metalizado_vuelta_interiores = comercial.metalizado_vuelta_interiores || '';
        this.comercial.laminado_frente_interiores = comercial.laminado_frente_interiores || '';
        this.comercial.laminado_vuelta_interiores = comercial.laminado_vuelta_interiores || '';
        this.comercial.barniz_sakurai_frente_interiores = comercial.barniz_sakurai_frente_interiores || '';
        this.comercial.barniz_sakurai_vuelta_interiores = comercial.barniz_sakurai_vuelta_interiores || '';
        this.comercial.doblez = comercial.doblez || '';
        this.comercial.embolsado_retractilado = comercial.embolsado_retractilado || '';
        this.comercial.perforado = comercial.perforado || '';
        this.comercial.suaje_interiores = comercial.suaje_interiores || '';
        this.comercial.otros_interiores = comercial.otros_interiores || '';

        this.comercial.encuadernado = comercial.encuadernado || '';
        this.comercial.empaque = comercial.empaque || '';
        this.comercial.entrega = comercial.entrega || '';
        this.comercial.lugar_entrega = comercial.lugar_entrega || '';
        this.comercial.observaciones = comercial.observaciones || '';
        this.comercial.observaciones_presupuesto = comercial.observaciones_presupuesto || '';

        this.comercial.suajado = comercial.suajado || '';
        this.comercial.suajado_forro = comercial.suajado_forro || '';
    },
    functions: {
        validarCheckEFNA() {
            if (this.comercial.num_paginas_forro > 0 || this.comercial.medida_extendida_forro != '' || this.comercial.medida_final_forro != '') {
                this.naEditorialForro = false;
            } else {
                this.naEditorialForro = true;
            }
        },
        validarCheckEGNA() {
            if (this.comercial.num_paginas_guardas != '' ||
                this.comercial.sustrato_guardas != '' ||
                this.comercial.gramaje_guardas != '' ||
                this.comercial.tinta_frente_guardas != '' ||
                this.comercial.tinta_vuelta_guardas != '' ||
                this.comercial.medida_final_guardas != '' ||
                this.comercial.medida_extendida_guardas != '' ||
                this.comercial.barniz_frente_guardas != '' ||
                this.comercial.barniz_vuelta_guardas != '') {
                this.naEditorialGuarda = false;
            } else {
                this.naEditorialGuarda = true;
            }
        },
        validarCheckAFNA() {
            if (this.comercial.metalizado_frente_forro != '' ||
                this.comercial.metalizado_vuelta_forro != '' ||
                this.comercial.laminado_frente_forro != '' ||
                this.comercial.laminado_vuelta_forro != '' ||
                this.comercial.barniz_sakurai_frente_forro != '' ||
                this.comercial.barniz_sakurai_vuelta_forro != '' ||
                this.comercial.realce_forro != '' ||
                this.comercial.placa_realce_forro != '' ||
                this.comercial.hot_stamping_forro != '' ||
                this.comercial.placa_hot_stamping_forro != '' ||
                this.comercial.color_hot_stamping_forro != '' ||
                this.comercial.suaje_forro != '' ||
                this.comercial.suajado_forro != ''
            ) {
                this.naAcabadoForro = false;
            } else {
                this.naAcabadoForro = true;
            }
        },
    },
    watchers: {
        'comercial.num_paginas_forro'(newValue) {
            if (newValue == 0) {
                this.naEditorialForroEdit = true;
            } else {
                this.naEditorialForroEdit = false;
            }
        },
        'comercial.sustrato_forro'(newValue) {
            if (newValue == '512') {
                this.naEditorialForroEdit = true;
            } else {
                this.naEditorialForroEdit = false;
            }
        },
        'comercial.gramaje_forro'(newValue) {
            if (newValue == '513') {
                this.naEditorialForroEdit = true;
            } else {
                this.naEditorialForroEdit = false;
            }
        },
        'comercial.tinta_frente_forro'(newValue) {
            if (newValue == '276') {
                this.naEditorialForroEdit = true;
            } else {
                this.naEditorialForroEdit = false;
            }
        },
        'comercial.tinta_vuelta_forro'(newValue) {
            if (newValue == '276') {
                this.naEditorialForroEdit = true;
            } else {
                this.naEditorialForroEdit = false;
            }
        },
        'comercial.medida_final_forro'(newValue) {
            if (newValue == '') {
                this.naEditorialForroEdit = true;
            } else {
                this.naEditorialForroEdit = false;
            }
        },
        'comercial.medida_extendida_forro'(newValue) {
            if (newValue == '') {
                this.naEditorialForroEdit = true;
            } else {
                this.naEditorialForroEdit = false;
            }
        },
        'comercial.barniz_frente_forro'(newValue) {
            if (newValue == '93') {
                this.naEditorialForroEdit = true;
            } else {
                this.naEditorialForroEdit = false;
            }
        },
        'comercial.barniz_vuelta_forro'(newValue) {
            if (newValue == '93') {
                this.naEditorialForroEdit = true;
            } else {
                this.naEditorialForroEdit = false;
            }
        },

        'comercial.num_paginas_guardas'(newValue) {
            if (newValue == 0) {
                this.naEditorialGuardaEdit = true;
            } else {
                this.naEditorialGuardaEdit = false;
            }
        },
        'comercial.sustrato_guardas'(newValue) {
            if (newValue == "512") {
                this.naEditorialGuardaEdit = true;
            } else {
                this.naEditorialGuardaEdit = false;
            }
        },
        'comercial.gramaje_guardas'(newValue) {
            if (newValue == "513") {
                this.naEditorialGuardaEdit = true;
            } else {
                this.naEditorialGuardaEdit = false;
            }
        },
        'comercial.tinta_frente_guardas'(newValue) {
            if (newValue == "276") {
                this.naEditorialGuardaEdit = true;
            } else {
                this.naEditorialGuardaEdit = false;
            }
        },
        'comercial.tinta_vuelta_guardas'(newValue) {
            if (newValue == "276") {
                this.naEditorialGuardaEdit = true;
            } else {
                this.naEditorialGuardaEdit = false;
            }
        },
        'comercial.medida_final_guardas'(newValue) {
            if (newValue == "") {
                this.naEditorialGuardaEdit = true;
            } else {
                this.naEditorialGuardaEdit = false;
            }
        },
        'comercial.medida_extendida_guardas'(newValue) {
            if (newValue == "") {
                this.naEditorialGuardaEdit = true;
            } else {
                this.naEditorialGuardaEdit = false;
            }
        },
        'comercial.barniz_frente_guardas'(newValue) {
            if (newValue == "93") {
                this.naEditorialGuardaEdit = true;
            } else {
                this.naEditorialGuardaEdit = false;
            }
        },
        'comercial.barniz_vuelta_guardas'(newValue) {
            if (newValue == "93") {
                this.naEditorialGuardaEdit = true;
            } else {
                this.naEditorialGuardaEdit = false;
            }
        },

        'comercial.metalizado_frente_forro'(newValue) {
            if (newValue == "81") {
                this.naAcabadoForroEdit = true;
            } else {
                this.naAcabadoForroEdit = false;
            }
        },
        'comercial.metalizado_vuelta_forro'(newValue) {
            if (newValue == "81") {
                this.naAcabadoForroEdit = true;
            } else {
                this.naAcabadoForroEdit = false;
            }
        },
        'comercial.laminado_frente_forro'(newValue) {
            if (newValue == "86") {
                this.naAcabadoForroEdit = true;
            } else {
                this.naAcabadoForroEdit = false;
            }
        },
        'comercial.laminado_vuelta_forro'(newValue) {
            if (newValue == "86") {
                this.naAcabadoForroEdit = true;
            } else {
                this.naAcabadoForroEdit = false;
            }
        },
        'comercial.barniz_sakurai_frente_forro'(newValue) {
            if (newValue == "93") {
                this.naAcabadoForroEdit = true;
            } else {
                this.naAcabadoForroEdit = false;
            }
        },
        'comercial.barniz_sakurai_vuelta_forro'(newValue) {
            if (newValue == "93") {
                this.naAcabadoForroEdit = true;
            } else {
                this.naAcabadoForroEdit = false;
            }
        },
        'comercial.realce_forro'(newValue) {
            if (newValue == "288") {
                this.naAcabadoForroEdit = true;
            } else {
                this.naAcabadoForroEdit = false;
            }
        },
        'comercial.placa_realce_forro'(newValue) {
            if (newValue == "") {
                this.naAcabadoForroEdit = true;
            } else {
                this.naAcabadoForroEdit = false;
            }
        },
        'comercial.hot_stamping_forro'(newValue) {
            if (newValue == "288") {
                this.naAcabadoForroEdit = true;
            } else {
                this.naAcabadoForroEdit = false;
            }
        },
        'comercial.placa_hot_stamping_forro'(newValue) {
            if (newValue == "") {
                this.naAcabadoForroEdit = true;
            } else {
                this.naAcabadoForroEdit = false;
            }
        },
        'comercial.color_hot_stamping_forro'(newValue) {
            if (newValue == "290") {
                this.naAcabadoForroEdit = true;
            } else {
                this.naAcabadoForroEdit = false;
            }
        },
        'comercial.suaje_forro'(newValue) {
            if (newValue == "428") {
                this.naAcabadoForroEdit = true;
            } else {
                this.naAcabadoForroEdit = false;
            }
        },
        'comercial.suajado_forro'(newValue) {
            if (newValue == "521") {
                this.naAcabadoForroEdit = true;
            } else {
                this.naAcabadoForroEdit = false;
            }
        },
    },
    camposConTipo: [
        [
            'num_paginas_interiores',
            'especificaciones',
            'input',
            'comercial',
            'Número de páginas interiores'
        ],
        [
            'sustrato_interiores',
            'minimos',
            'select',
            'comercial',
            'Sustrato interiores'
        ],
        [
            'tinta_frente_interiores',
            'minimos',
            'select',
            'comercial',
            'Tinta frente interiores'
        ],
        [
            'tinta_vuelta_interiores',
            'minimos',
            'select',
            'comercial',
            'Tinta vuelta interiores'
        ],
        [
            'medida_final_interiores',
            'especificaciones',
            'input',
            'comercial',
            'Medida final interiores'
        ],
        [
            'medida_extendida_interiores',
            'especificaciones',
            'input',
            'comercial',
            'Medida extendida interiores'
        ],
        [
            'barniz_frente_interiores',
            'minimos',
            'select',
            'comercial',
            'Barniz frente interiores'
        ],
        [
            'barniz_vuelta_interiores',
            'minimos',
            'select',
            'comercial',
            'Barniz vuelta interiores'
        ],
        [
            'metalizado_frente_interiores',
            'especificaciones',
            'select',
            'comercial',
            'Metalizado frente interiores'
        ],
        [
            'metalizado_vuelta_interiores',
            'especificaciones',
            'select',
            'comercial',
            'Metalizado vuelta interiores'
        ],
        [
            'laminado_frente_interiores',
            'minimos',
            'select',
            'comercial',
            'Laminado frente interiores'
        ],
        [
            'laminado_vuelta_interiores',
            'minimos',
            'select',
            'comercial',
            'Laminado vuelta interiores'
        ],
        [
            'barniz_sakurai_frente_interiores',
            'minimos',
            'select',
            'comercial',
            'Barniz sakurai frente interiores'
        ],
        [
            'barniz_sakurai_vuelta_interiores',
            'minimos',
            'select',
            'comercial',
            'Barniz sakurai vuelta interiores'
        ],
        [
            'doblez',
            'especificaciones',
            'select',
            'comercial',
            'Doblez interiores'
        ],
        [
            'embolsado_retractilado',
            'especificaciones',
            'select',
            'comercial',
            'Embolsado/retractilado interiores'
        ],
        [
            'perforado',
            'especificaciones',
            'select',
            'comercial',
            'Perforado interiores'
        ],
        [
            'suaje_interiores',
            'especificaciones',
            'select',
            'comercial',
            'Suaje interiores'
        ],
        [
            'otros_interiores',
            'especificaciones',
            'select',
            'comercial',
            'Otros interiores'
        ],
        [
            'num_paginas_forro',
            'especificaciones',
            'input',
            'comercial',
            'Número de páginas forro'
        ],
        [
            'sustrato_forro',
            'minimos',
            'select',
            'comercial',
            'Sustrato forro'
        ],
        [
            'tinta_frente_forro',
            'minimos',
            'select',
            'comercial',
            'Tinta frente forro'
        ],
        [
            'tinta_vuelta_forro',
            'minimos',
            'select',
            'comercial',
            'Tinta vuelta forro'
        ],
        [
            'medida_final_forro',
            'especificaciones',
            'input',
            'comercial',
            'Medida final forro'
        ],
        [
            'medida_extendida_forro',
            'especificaciones',
            'input',
            'comercial',
            'Medida extendida forro'
        ],
        [
            'barniz_frente_forro',
            'minimos',
            'select',
            'comercial',
            'Barniz frente forro'
        ],
        [
            'barniz_vuelta_forro',
            'minimos',
            'select',
            'comercial',
            'Barniz vuelta forro'
        ],
        [
            'metalizado_frente_forro',
            'especificaciones',
            'select',
            'comercial',
            'Metalizado frente forro'
        ],
        [
            'metalizado_vuelta_forro',
            'especificaciones',
            'select',
            'comercial',
            'Metalizado vuelta forro'
        ],
        [
            'laminado_frente_forro',
            'minimos',
            'select',
            'comercial',
            'Laminado frente forro'
        ],
        [
            'laminado_vuelta_forro',
            'minimos',
            'select',
            'comercial',
            'Laminado vuelta forro'
        ],
        [
            'barniz_sakurai_frente_forro',
            'minimos',
            'select',
            'comercial',
            'Barniz sakurai frente forro'
        ],
        [
            'barniz_sakurai_vuelta_forro',
            'minimos',
            'select',
            'comercial',
            'Barniz sakurai vuelta forro'
        ],
        [
            'realce_forro',
            'especificaciones',
            'select',
            'comercial',
            'Realce forro'
        ],
        [
            'placa_realce_forro',
            'especificaciones',
            'input',
            'comercial',
            'Placa realce forro'
        ],
        [
            'hot_stamping_forro',
            'especificaciones',
            'select',
            'comercial',
            'Hot stamping forro'
        ],
        [
            'placa_hot_stamping_forro',
            'especificaciones',
            'input',
            'comercial',
            'Placa hot stamping forro'
        ],
        [
            'color_hot_stamping_forro',
            'especificaciones',
            'select',
            'comercial',
            'Color hot stamping forro'
        ],
        [
            'suaje_forro',
            'especificaciones',
            'select',
            'comercial',
            'Suaje forro'
        ],
        [
            'num_paginas_guardas',
            'especificaciones',
            'input',
            'comercial',
            'Número de páginas guardas'
        ],
        [
            'sustrato_guardas',
            'minimos',
            'select',
            'comercial',
            'Sustrato guardas'
        ],
        [
            'tinta_frente_guardas',
            'minimos',
            'select',
            'comercial',
            'Tinta frente guardas'
        ],
        [
            'tinta_vuelta_guardas',
            'minimos',
            'select',
            'comercial',
            'Tinta vuelta guardas'
        ],
        [
            'medida_final_guardas',
            'especificaciones',
            'input',
            'comercial',
            'Medida final guardas'
        ],
        [
            'medida_extendida_guardas',
            'especificaciones',
            'input',
            'comercial',
            'Medida extendida guardas'
        ],
        [
            'barniz_frente_guardas',
            'minimos',
            'select',
            'comercial',
            'Barniz frente guardas'
        ],
        [
            'barniz_vuelta_guardas',
            'minimos',
            'select',
            'comercial',
            'Barniz vuelta guardas',
        ],
        [
            'encuadernado',
            'especificaciones',
            'select',
            'comercial',
            'Encuadernado'
        ],
        [
            'empaque',
            'especificaciones',
            'select',
            'comercial',
            'Empaque'
        ],
        [
            'entrega',
            'especificaciones',
            'select',
            'comercial',
            'Entrega',
        ],
        [
            'lugar_entrega',
            'especificaciones',
            'select',
            'comercial',
            'Lugar de entrega'
        ],
        [
            'suajado',
            'especificaciones',
            'select',
            'comercial',
            'Suajado'
        ],
        [
            'suajado_forro',
            'especificaciones',
            'select',
            'comercial',
            'Suajado forro'
        ],
    ]
}