<div id="popForm">
    <div class="text-lg font-bold text-gray-700 uppercase py-2 px-1">
        <h3 class="text-sky-600">pop</h3>
    </div>

    <div class="grid sm:grid-cols-4 lg:grid-cols-6 gap-4 px-1 border border-gray-300 rounded-lg p-2">
        {{--  Sustratos  --}}
        <div>
            <form-select
                label="Sustrato"
                name="sustrato"
                v-model="pop.sustrato"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="popCatalogos.sustratos"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.pop?.sustrato"
                :is-modified="(bitacoraCambiosPop.some(c => c.campo === 'sustrato') || camposQueCambiaron.includes('sustrato')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Gramajes  --}}
        {{--  <div>
            <form-select
                label="Gramaje/puntos"
                name="gramaje"
                v-model="pop.gramaje"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="popCatalogos.gramajes"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.pop?.gramaje"
                :is-modified="(bitacoraCambiosPop.some(c => c.campo === 'gramaje') || camposQueCambiaron.includes('gramaje')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>  --}}

        {{--  Tintas frente  --}}
        <div>
            <form-select
                label="Tintas frente"
                name="tinta_frente"
                v-model="pop.tinta_frente"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="popCatalogos.tintasFrentes"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.pop?.tinta_frente"
                :is-modified="(bitacoraCambiosPop.some(c => c.campo === 'tinta_frente') || camposQueCambiaron.includes('tinta_frente')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Tintas vuelta  --}}
        <div>
            <form-select
                label="Tintas vuelta"
                name="tinta_vuelta"
                v-model="pop.tinta_vuelta"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="popCatalogos.tintasVuelta"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.pop?.tinta_vuelta"
                :is-modified="(bitacoraCambiosPop.some(c => c.campo === 'tinta_vuelta') || camposQueCambiaron.includes('tinta_vuelta')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Medida Final (cm)  --}}
        <div>
            <form-input
                label="Medida Final (cm)"
                name="medida_final"
                v-model="pop.medida_final"
                placeholder="---"
                type="text"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.pop?.medida_final"
                :is-modified="(bitacoraCambiosPop.some(c => c.campo === 'medida_final') || camposQueCambiaron.includes('medida_final')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-input>
        </div>

        {{--  Medida Extendida (cm)  --}}
        <div>
            <form-input
                label="Medida Extendida (cm)"
                name="medida_extendida"
                v-model="pop.medida_extendida"
                placeholder="---"
                type="text"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.pop?.medida_extendida"
                :is-modified="(bitacoraCambiosPop.some(c => c.campo === 'medida_extendida') || camposQueCambiaron.includes('medida_extendida')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-input>
        </div>

        {{--  Barniz Frente  --}}
        <div>
            <form-select
                label="Barniz Frente"
                name="barniz_frente"
                v-model="pop.barniz_frente"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="popCatalogos.barnicesFrente"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.pop?.barniz_frente"
                :is-modified="(bitacoraCambiosPop.some(c => c.campo === 'barniz_frente') || camposQueCambiaron.includes('barniz_frente')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Barniz Vuelta  --}}
        <div>
            <form-select
                label="Barniz Vuelta"
                name="barniz_vuelta"
                v-model="pop.barniz_vuelta"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="popCatalogos.barnicesVuelta"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.pop?.barniz_vuelta"
                :is-modified="(bitacoraCambiosPop.some(c => c.campo === 'barniz_vuelta') || camposQueCambiaron.includes('barniz_vuelta')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  No. De Cambios  --}}
        <div>
            <form-select
                label="No. de Cambios"
                name="num_cambios"
                v-model="pop.num_cambios"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="popCatalogos.numCambios"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.pop?.num_cambios"
                :is-modified="(bitacoraCambiosPop.some(c => c.campo === 'num_cambios') || camposQueCambiaron.includes('num_cambios')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Trazo Cliente  --}}
        <div>
            <form-select
                label="Trazo Cliente"
                name="trazo_cliente"
                v-model="pop.trazo_cliente"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="popCatalogos.trazosCliente"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.pop?.trazo_cliente"
                :is-modified="(bitacoraCambiosPop.some(c => c.campo === 'trazo_cliente') || camposQueCambiaron.includes('trazo_cliente')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Trazo Estructural  --}}
        <div>
            <form-select
                label="Trazo Estructural"
                name="trazo_estructural"
                v-model="pop.trazo_estructural"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="popCatalogos.trazosEstructural"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.pop?.trazo_estructural"
                :is-modified="(bitacoraCambiosPop.some(c => c.campo === 'trazo_estructural') || camposQueCambiaron.includes('trazo_estructural')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Sustratos para Empalme  --}}
        <div>
            <form-select
                label="Sustratos para Empalme"
                name="sustrato_empalme"
                v-model="pop.sustrato_empalme"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="popCatalogos.sustratosParaEmpalme"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.pop?.sustrato_empalme"
                :is-modified="(bitacoraCambiosPop.some(c => c.campo === 'sustrato_empalme') || camposQueCambiaron.includes('sustrato_empalme')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Otros  --}}
        <div>
            <form-select
                label="Otros"
                name="otros_pop"
                v-model="pop.otros_pop"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="popCatalogos.otrosPop"
                :required="false"
                :disabled="validacionDisabled"
                :error="errors.pop?.otros_pop"
                :is-modified="(bitacoraCambiosPop.some(c => c.campo === 'otros_pop') || camposQueCambiaron.includes('otros_pop')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>
    </div>

    <div class="text-lg font-bold text-gray-700 uppercase py-2 px-1">
        <h3 class="text-sky-600">acabados</h3>
    </div>

    <div class="grid sm:grid-cols-4 lg:grid-cols-6 gap-4 px-1 border border-gray-300 rounded-lg p-2">
        {{--  Metalizado Frente  --}}
        <div>
            <form-select
                label="Metalizado Frente"
                name="metalizado_frente"
                v-model="pop.metalizado_frente"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="popCatalogos.metalizadosFrentes"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.pop?.metalizado_frente"
                :is-modified="(bitacoraCambiosPop.some(c => c.campo === 'metalizado_frente') || camposQueCambiaron.includes('metalizado_frente')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Metalizado Vuelta  --}}
        <div>
            <form-select
                label="Metalizado Vuelta"
                name="metalizado_vuelta"
                v-model="pop.metalizado_vuelta"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="popCatalogos.metalizadosVuelta"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.pop?.metalizado_vuelta"
                :is-modified="(bitacoraCambiosPop.some(c => c.campo === 'metalizado_vuelta') || camposQueCambiaron.includes('metalizado_vuelta')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>
        
        {{--  Laminado Frente  --}}
        <div>
            <form-select
                label="Laminado Frente"
                name="laminado_frente"
                v-model="pop.laminado_frente"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="popCatalogos.laminadosFrente"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.pop?.laminado_frente"
                :is-modified="(bitacoraCambiosPop.some(c => c.campo === 'laminado_frente') || camposQueCambiaron.includes('laminado_frente')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Laminado Vuelta  --}}
        <div>
            <form-select
                label="Laminado Vuelta"
                name="laminado_vuelta"
                v-model="pop.laminado_vuelta"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="popCatalogos.laminadosVuelta"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.pop?.laminado_vuelta"
                :is-modified="(bitacoraCambiosPop.some(c => c.campo === 'laminado_vuelta') || camposQueCambiaron.includes('laminado_vuelta')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Barniz Sakurai Frente  --}}
        <div>
            <form-select
                label="Barniz Sakurai Frente"
                name="barniz_sakurai_frente"
                v-model="pop.barniz_sakurai_frente"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="popCatalogos.barnicesSakuraiFrente"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.pop?.barniz_sakurai_frente"
                :is-modified="(bitacoraCambiosPop.some(c => c.campo === 'barniz_sakurai_frente') || camposQueCambiaron.includes('barniz_sakurai_frente')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Barniz Sakurai Vuelta  --}}
        <div>
            <form-select
                label="Barniz Sakurai Vuelta"
                name="barniz_sakurai_vuelta"
                v-model="pop.barniz_sakurai_vuelta"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="popCatalogos.barnicesSakuraiVuelta"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.pop?.barniz_sakurai_vuelta"
                :is-modified="(bitacoraCambiosPop.some(c => c.campo === 'barniz_sakurai_vuelta') || camposQueCambiaron.includes('barniz_sakurai_vuelta')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  realces  --}}
        <div>
            <form-select
                label="Realces"
                name="realce"
                v-model="pop.realce"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="popCatalogos.realces"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.pop?.realce"
                :is-modified="(bitacoraCambiosPop.some(c => c.campo === 'realce') || camposQueCambiaron.includes('realce')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Placa de Realce(Medida cm LxA)  --}}
        <div>
            <form-input
                label="Placa de Realce (Medida cm LxA)"
                name="placa_realce"
                v-model="pop.placa_realce"
                placeholder="---"
                type="text"
                :required="pop.realce != 288 && pop.realce != '' ? true : false"
                :disabled="pop.realce != 288 && pop.realce != '' && !validacionDisabled ? false : true"
                :error="errors.pop?.placa_realce"
                :is-modified="(bitacoraCambiosPop.some(c => c.campo === 'placa_realce') || camposQueCambiaron.includes('placa_realce')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-input>
        </div>

        {{--  Hot Stamping  --}}
        <div>
            <form-select
                label="Hot Stamping"
                name="hot_stamping"
                v-model="pop.hot_stamping"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="popCatalogos.hotStampings"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.pop?.hot_stamping"
                :is-modified="(bitacoraCambiosPop.some(c => c.campo === 'hot_stamping') || camposQueCambiaron.includes('hot_stamping')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Placa Hot Stamping (Medida cm LxA)  --}}
        <div>
            <form-input
                label="Placa Hot Stamping (Medida cm LxA)"
                name="placa_hot_stamping"
                v-model="pop.placa_hot_stamping"
                placeholder="---"
                type="text"
                :required="pop.hot_stamping != 288 && pop.hot_stamping != '' ? true : false"
                :disabled="pop.hot_stamping != 288 && pop.hot_stamping != '' && !validacionDisabled ? false : true"
                :error="errors.pop?.placa_hot_stamping"
                :is-modified="(bitacoraCambiosPop.some(c => c.campo === 'placa_hot_stamping') || camposQueCambiaron.includes('placa_hot_stamping')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-input>
        </div>

        {{--  Color Hot Stamping  --}}
        <div>
            <form-select
                label="Color Hot Stamping"
                name="color_hot_stamping"
                v-model="pop.color_hot_stamping"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="popCatalogos.coloresHotStamping"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.pop?.color_hot_stamping"
                :is-modified="(bitacoraCambiosPop.some(c => c.campo === 'color_hot_stamping') || camposQueCambiaron.includes('color_hot_stamping')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Suaje/Corte  --}}
        <div>
            <form-select
                label="Suaje/Corte"
                name="suaje"
                v-model="pop.suaje"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="popCatalogos.suajes"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.pop?.suaje"
                :is-modified="(bitacoraCambiosPop.some(c => c.campo === 'suaje') || camposQueCambiaron.includes('suaje')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Suajado  --}}
        <div>
            <form-select
                label="Suajado"
                name="suajado"
                v-model="pop.suajado"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="popCatalogos.suajadosList"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.pop?.suajado"
                :is-modified="(bitacoraCambiosPop.some(c => c.campo === 'suajado') || camposQueCambiaron.includes('suajado')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Termodoblez  --}}
        <div>
            <form-select
                label="Termodoblez"
                name="termodoblez"
                v-model="pop.termodoblez"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="popCatalogos.termodobleces"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.pop?.termodoblez"
                :is-modified="(bitacoraCambiosPop.some(c => c.campo === 'termodoblez') || camposQueCambiaron.includes('termodoblez')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Hilo Monofilamento  --}}
        <div>
            <form-select
                label="Hilo Monofilamento"
                name="hilo_monofilamento"
                v-model="pop.hilo_monofilamento"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="popCatalogos.hilosMonofilamentos"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.pop?.hilo_monofilamento"
                :is-modified="(bitacoraCambiosPop.some(c => c.campo === 'hilo_monofilamento') || camposQueCambiaron.includes('hilo_monofilamento')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Cm de Hilo x pieza  --}}
        <div>
            <form-input
                label="Cm de Hilo x pieza"
                name="cm_hilo_x_pieza"
                v-model="pop.cm_hilo_x_pieza"
                placeholder="---"
                type="number"
                :required="pop.hilo_monofilamento != 432 && pop.hilo_monofilamento != '' ? true : false"
                :disabled="pop.hilo_monofilamento != 432 && pop.hilo_monofilamento != '' && !validacionDisabled ? false : true"
                :error="errors.pop?.cm_hilo_x_pieza"
                :is-modified="(bitacoraCambiosPop.some(c => c.campo === 'cm_hilo_x_pieza') || camposQueCambiaron.includes('cm_hilo_x_pieza')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-input>
        </div>

        {{--  Cinta Doble Cara  --}}
        <div>
            <form-select
                label="Cinta Doble Cara"
                name="cinta_doble_cara"
                v-model="pop.cinta_doble_cara"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="popCatalogos.cintasDobleCaras"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.pop?.cinta_doble_cara"
                :is-modified="(bitacoraCambiosPop.some(c => c.campo === 'cinta_doble_cara') || camposQueCambiaron.includes('cinta_doble_cara')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Cantidad de cintas x pieza  --}}
        <div>
            <form-select
                label="Cantidad de cintas x pieza"
                name="cantidad_cintas_x_pieza"
                v-model="pop.cantidad_cintas_x_pieza"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="popCatalogos.cantidadCintasXPiezas"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.pop?.cantidad_cintas_x_pieza"
                :is-modified="(bitacoraCambiosPop.some(c => c.campo === 'cantidad_cintas_x_pieza') || camposQueCambiaron.includes('cantidad_cintas_x_pieza')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Medida de cada cinta (cm)  --}}
        <div>
            <form-input
                label="Medida de cada cinta (cm)"
                name="medida_cada_cinta"
                v-model="pop.medida_cada_cinta"
                placeholder="---"
                type="number"
                :required="pop.cantidad_cintas_x_pieza != 446 && pop.cantidad_cintas_x_pieza != '' ? true : false"
                :disabled="pop.cantidad_cintas_x_pieza != 446 && pop.cantidad_cintas_x_pieza != '' && !validacionDisabled ? false : true"
                :error="errors.pop?.medida_cada_cinta"
                :is-modified="(bitacoraCambiosPop.some(c => c.campo === 'medida_cada_cinta') || camposQueCambiaron.includes('medida_cada_cinta')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-input>
        </div>

        {{--  Otros acabados  --}}
        <div>
            <form-select
                label="Otros acabados"
                name="otros_acabados"
                v-model="pop.otros_acabados"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="popCatalogos.otrosAcabados"
                :required="false"
                :disabled="validacionDisabled"
                :error="errors.pop?.otros_acabados"
                :is-modified="(bitacoraCambiosPop.some(c => c.campo === 'otros_acabados') || camposQueCambiaron.includes('otros_acabados')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>
    </div>

    <div class="text-lg font-bold text-gray-700 uppercase py-2 px-1">
        <h3 class="text-sky-600">adicionales</h3>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 px-1 border border-gray-300 rounded-lg p-2">
        {{--  Adicionales  --}}
        <div class="md:col-span-3">
            <form-select
                label="Adicionales"
                name="adicional"
                v-model="pop.adicional"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="popCatalogos.adicionales"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.pop?.adicional"
                :is-modified="(bitacoraCambiosPop.some(c => c.campo === 'adicional') || camposQueCambiaron.includes('adicional')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Empaque  --}}
        <div class="md:col-span-3">
            <form-select
                label="Empaque"
                name="empaque"
                v-model="pop.empaque"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="popCatalogos.empaques"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.pop?.empaque"
                :is-modified="(bitacoraCambiosPop.some(c => c.campo === 'empaque') || camposQueCambiaron.includes('empaque')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Entregas  --}}
        <div class="md:col-span-3">
            <form-select
                label="Entregas"
                name="entrega"
                v-model="pop.entrega"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="popCatalogos.entregas"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.pop?.entrega"
                :is-modified="(bitacoraCambiosPop.some(c => c.campo === 'entrega') || camposQueCambiaron.includes('entrega')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Lugar de Entrega  --}}
        <div class="md:col-span-3">
            <form-select
                label="Lugar de Entrega"
                name="lugar_entrega"
                v-model="pop.lugar_entrega"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="popCatalogos.lugarEntregas"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.pop?.lugar_entrega"
                :is-modified="(bitacoraCambiosPop.some(c => c.campo === 'lugar_entrega') || camposQueCambiaron.includes('lugar_entrega')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Observaciones  --}}
        <div class="md:col-span-12" title="Estas observaciones serán visibles únicamente para el cotizador y sirven como complemento para aquellos campos en los que se haya seleccionado la opción 'Otro (En observaciones)'">
            <form-textarea
                label="Observaciones (Para cotizador)"
                name="observaciones"
                v-model="pop.observaciones"
                placeholder="Ingrese observaciones para el cotizador."
                :required="false"
                :disabled="((estatusOportunidad != 1 && estatusOportunidad != 2)) && !validacionDisabled"
            ></form-textarea>
        </div>

        {{--  Observaciones  --}}
        <div class="md:col-span-12" title="Estas observaciones serán visibles en el presupuesto final que se entregue al cliente, por lo que se recomienda incluir información complementaria a los campos seleccionados en caso de haber elegido opciones como 'Otro (En observaciones)' o para detallar información relevante que no esté contemplada en los campos anteriores.">
            <form-textarea
                label="Observaciones (Para Presupuesto)"
                name="observaciones_presupuesto"
                v-model="general.observaciones_presupuesto"
                placeholder="Ingrese observaciones pertinentes que se mostrarán en el presupuesto."
                :required="false"
                :disabled="validacionDisabled"
                :error="errors.general?.observaciones_presupuesto"
                :is-modified="(((estatusOportunidad != 2 && estatusOportunidad != 1) && tipoForm == 2 ) || esParaCambioTexto)"
            ></form-textarea>
        </div>

        <div class="md:col-span-12 text-sm" v-if="(estatusEnQuePuedeActualizarCS.includes(parseInt(estatusOportunidad)) || pop.observacionesExtras.length > 0) && tipoForm == 2" title="Estos comentarios son visibles para el equipo de Customers y se utilizan para comunicar información adicional relevante sobre el elemento que no se haya cubierto en los campos anteriores, ademas de que se considera como cambio minimo al agregar al menos 1.">
            <label class="block text-gray-700 font-bold mb-1">Comentarios adicionales</label>
            <div v-for="(comentario, idx) in pop.observacionesExtras" :key="idx" class="flex items-center mb-2">
                <textarea
                    v-model="pop.observacionesExtras[idx]"
                    :id="`comentario-extra-${idx}`"
                    :class="[
                        'form-input flex-1 rounded px-2 py-1 focus:outline-none focus:ring-sky-400 focus:border-sky-400 border-2 !border-amber-400 focus:ring-2 focus:ring-amber-400 focus:border-amber-400',
                    ]"
                    placeholder="Comentario adicional..."
                    :disabled="!estatusEnQuePuedeActualizarCS.includes(parseInt(estatusOportunidad)) || !(role == 4 || role == 9 || role == 1) ? true : false"
                ></textarea>
                <button type="button" class="ml-2 text-red-500" @click="removerComentarioExtra(idx)" v-if="estatusEnQuePuedeActualizarCS.includes(parseInt(estatusOportunidad)) && (role == 4 || role == 9 || role == 1)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <button type="button" class="btn btn-secondary mt-1" @click="agregarComentarioExtra" v-if="estatusEnQuePuedeActualizarCS.includes(parseInt(estatusOportunidad)) && (role == 4 || role == 9 || role == 1)">
                <i class="fas fa-plus"></i> Agregar comentario
            </button>
        </div>
    </div>
</div>
