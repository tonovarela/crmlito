<div class="text-lg font-bold text-gray-700 uppercase py-2 px-1">
    <h3 class="text-sky-600">empaque</h3>
</div>

<div class="grid sm:grid-cols-4 lg:grid-cols-6 gap-4 px-1 border border-gray-300 rounded-lg p-2">
    {{--  Sustratos  --}}
    <div>
        <form-select
            label="Sustrato gr/ptos"
            name="sustratos_t2"
            v-model="empaque.sustrato_t2"
            :options="empaqueCatalogos.sustratos"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :required="naTacon2 ? false : true"
            :disabled="(naTacon2 || validacionDisabled) ? true : false"
            :error="errors.empaque?.sustrato_t2"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'sustrato_t2') || camposQueCambiaron.includes('sustrato_t2')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            >
        </form-select>
    </div>

    {{--  Gramajes  --}}
    {{--  <div>
        <form-select
            label="Gramaje/puntos"
            name="gramaje_t2"
            v-model="empaque.gramaje_t2"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.gramajes"
            :required="naTacon2 ? false : true"
            :disabled="(naTacon2 || validacionDisabled) ? true : false"
            :error="errors.empaque?.gramaje_t2"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'gramaje_t2') || camposQueCambiaron.includes('gramaje_t2')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            >
        </form-select>
    </div>  --}}

    {{--  Tintas frente  --}}
    <div>
        <form-select
            label="Tintas frente"
            name="tinta_frente_t2"
            v-model="empaque.tinta_frente_t2"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.tintasFrente"
            :required="naTacon2 ? false : true"
            :disabled="(naTacon2 || validacionDisabled) ? true : false"
            :error="errors.empaque?.tinta_frente_t2"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'tinta_frente_t2') || camposQueCambiaron.includes('tinta_frente_t2')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            >
        </form-select>
    </div>

    {{--  Tintas vuelta  --}}
    <div>
        <form-select
            label="Tintas vuelta"
            name="tinta_vuelta_t2"
            v-model="empaque.tinta_vuelta_t2"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.tintasVuelta"
            :required="naTacon2 ? false : true"
            :disabled="(naTacon2 || validacionDisabled) ? true : false"
            :error="errors.empaque?.tinta_vuelta_t2"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'tinta_vuelta_t2') || camposQueCambiaron.includes('tinta_vuelta_t2')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            >
        </form-select>
    </div>

    {{--  Medida Final (cm)  --}}
    <div>
        <form-select
            label="Medida Final (cm)"
            name="medida_final_t2"
            v-model="empaque.medida_final_t2"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.medidasFinales"
            :required="naTacon2 ? false : true"
            :disabled="(naTacon2 || validacionDisabled) ? true : false"
            :error="errors.empaque?.medida_final_t2"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'medida_final_t2') || camposQueCambiaron.includes('medida_final_t2')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            >
        </form-select>

        <div class="flex gap-2 mt-2">
            <form-input
                label="Largo (mm)"
                name="medida_final_largo_t2"
                v-model="empaque.medida_final_largo_t2"
                type="number"
                placeholder="Ingrese largo en mm"
                min="0"
                max="999999999"
                :required="false"
                :disabled="(naTacon2 || validacionDisabled) ? true : false"
                :error="errors.empaque?.medida_final_largo_t2"
                :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'medida_final_largo_t2') || camposQueCambiaron.includes('medida_final_largo_t2')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-input>

            <form-input
                label="Ancho (mm)"
                name="medida_final_ancho_t2"
                v-model="empaque.medida_final_ancho_t2"
                type="number"
                placeholder="Ingrese ancho en mm"
                min="0"
                max="999999999"
                :required="false"
                :disabled="(naTacon2 || validacionDisabled) ? true : false"
                :error="errors.empaque?.medida_final_ancho_t2"
                :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'medida_final_ancho_t2') || camposQueCambiaron.includes('medida_final_ancho_t2')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-input>

            <form-input
                label="Alto (mm)"
                name="medida_final_alto_t2"
                v-model="empaque.medida_final_alto_t2"
                type="number"
                placeholder="Ingrese alto en mm"
                min="0"
                max="999999999"
                :required="false"
                :disabled="(naTacon2 || validacionDisabled) ? true : false"
                :error="errors.empaque?.medida_final_alto_t2"
                :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'medida_final_alto_t2') || camposQueCambiaron.includes('medida_final_alto_t2')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-input>
        </div>
    </div>

    {{--  Medida Extendida (cm)  --}}
    <div>
        <form-select
            label="Medida Extendida (cm)"
            name="medida_extendida_t2"
            v-model="empaque.medida_extendida_t2"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.medidasExtendidas"
            :required="naTacon2 ? false : true"
            :disabled="(naTacon2 || validacionDisabled) ? true : false"
            :error="errors.empaque?.medida_extendida_t2"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'medida_extendida_t2') || camposQueCambiaron.includes('medida_extendida_t2')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            >
        </form-select>
        
        <div class="flex gap-2 mt-2">
            <form-input
                label="Largo (mm)"
                name="medida_extendida_largo_t2"
                v-model="empaque.medida_extendida_largo_t2"
                type="number"
                placeholder="Ingrese largo en mm"
                min="0"
                max="999999999"
                :required="false"
                :disabled="(naTacon2 || validacionDisabled) ? true : false"
                :error="errors.empaque?.medida_extendida_largo_t2"
                :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'medida_extendida_largo_t2') || camposQueCambiaron.includes('medida_extendida_largo_t2')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-input>

            <form-input
                label="Ancho (mm)"
                name="medida_extendida_ancho_t2"
                v-model="empaque.medida_extendida_ancho_t2"
                type="number"
                placeholder="Ingrese ancho en mm"
                min="0"
                max="999999999"
                :required="false"
                :disabled="(naTacon2 || validacionDisabled) ? true : false"
                :error="errors.empaque?.medida_extendida_ancho_t2"
                :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'medida_extendida_ancho_t2') || camposQueCambiaron.includes('medida_extendida_ancho_t2')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-input>

            {{--  <form-input
                label="Alto (mm)"
                name="medida_extendida_alto_t2"
                v-model="empaque.medida_extendida_alto_t2"
                type="number"
                placeholder="Ingrese alto en mm"
                min="0"
                max="999999999"
                :required="false"
                :disabled="(naTacon2 || validacionDisabled) ? true : false"
                :error="errors.empaque?.medida_extendida_alto_t2"
                :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'medida_extendida_alto_t2') || camposQueCambiaron.includes('medida_extendida_alto_t2')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-input>  --}}
        </div>
    </div>

    {{--  Barniz Frente  --}}
    <div>
        <form-select
            label="Barniz Frente"
            name="barniz_frente_t2"
            v-model="empaque.barniz_frente_t2"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.barnicesFrente"
            :required="naTacon2 ? false : true"
            :disabled="(naTacon2 || validacionDisabled) ? true : false"
            :error="errors.empaque?.barniz_frente_t2"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'barniz_frente_t2') || camposQueCambiaron.includes('barniz_frente_t2')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            >
        </form-select>
    </div>

    {{--  Barniz Vuelta  --}}
    <div>
        <form-select
            label="Barniz Vuelta"
            name="barniz_vuelta_t2"
            v-model="empaque.barniz_vuelta_t2"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.barnicesVuelta"
            :required="naTacon2 ? false : true"
            :disabled="(naTacon2 || validacionDisabled) ? true : false"
            :error="errors.empaque?.barniz_vuelta_t2"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'barniz_vuelta_t2') || camposQueCambiaron.includes('barniz_vuelta_t2')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            >
        </form-select>
    </div>

    {{--  Sustratos para Empalme  --}}
    <div>
        <form-select
            label="Sustratos para Empalme"
            name="sustrato_empalme_t2"
            v-model="empaque.sustrato_empalme_t2"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.sustratosParaEmpalme"
            :required="naTacon2 ? false : true"
            :disabled="(naTacon2 || validacionDisabled) ? true : false"
            :error="errors.empaque?.sustrato_empalme_t2"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'sustrato_empalme_t2') || camposQueCambiaron.includes('sustrato_empalme_t2')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            >
        </form-select>
    </div>

    {{--  Requiere ventana  --}}
    <div>
        <form-select
            label="Requiere ventana"
            name="requiere_ventana_t2"
            v-model="empaque.requiere_ventana_t2"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.requieresVentana"
            :required="naTacon2 ? false : true"
            :disabled="(naTacon2 || validacionDisabled) ? true : false"
            :error="errors.empaque?.requiere_ventana_t2"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'requiere_ventana_t2') || camposQueCambiaron.includes('requiere_ventana_t2')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            >
        </form-select>
    </div>

    {{--  Puntaje ventana  --}}
    <div>
        <form-select
            label="Puntaje ventana"
            name="puntaje_ventana_t2"
            v-model="empaque.puntaje_ventana_t2"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.puntajesVentana"
            :required="naTacon2 ? false : true"
            :disabled="(naTacon2 || validacionDisabled) ? true : false"
            :error="errors.empaque?.puntaje_ventana_t2"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'puntaje_ventana_t2') || camposQueCambiaron.includes('puntaje_ventana_t2')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            >
        </form-select>
    </div>

    {{--  Otros  --}}
    <div>
        <form-select
            label="Otros"
            name="otros_empaque_t2"
            v-model="empaque.otros_empaque_t2"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.otrosEmpaque"
            :required="naTacon2 ? false : true"
            :disabled="(naTacon2 || validacionDisabled) ? true : false"
            :error="errors.empaque?.otros_empaque_t2"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'otros_empaque_t2') || camposQueCambiaron.includes('otros_empaque_t2')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            >
        </form-select>
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
            name="metalizado_frente_t2"
            v-model="empaque.metalizado_frente_t2"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.metalizadosFrente"
            :required="naTacon2 ? false : true"
            :disabled="(naTacon2 || validacionDisabled) ? true : false"
            :error="errors.empaque?.metalizado_frente_t2"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'metalizado_frente_t2') || camposQueCambiaron.includes('metalizado_frente_t2')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            >
        </form-select>
    </div>

    {{--  Metalizado Vuelta  --}}
    <div>
        <form-select
            label="Metalizado Vuelta"
            name="metalizado_vuelta_t2"
            v-model="empaque.metalizado_vuelta_t2"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.metalizadosVuelta"
            :required="naTacon2 ? false : true"
            :disabled="(naTacon2 || validacionDisabled) ? true : false"
            :error="errors.empaque?.metalizado_vuelta_t2"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'metalizado_vuelta_t2') || camposQueCambiaron.includes('metalizado_vuelta_t2')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            >
        </form-select>
    </div>

    {{--  Laminado Frente  --}}
    <div>
        <form-select
            label="Laminado Frente"
            name="laminado_frente_t2"
            v-model="empaque.laminado_frente_t2"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.laminadosFrente"
            :required="naTacon2 ? false : true"
            :disabled="(naTacon2 || validacionDisabled) ? true : false"
            :error="errors.empaque?.laminado_frente_t2"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'laminado_frente_t2') || camposQueCambiaron.includes('laminado_frente_t2')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            >
        </form-select>
    </div>

    {{--  Laminado Vuelta  --}}
    <div>
        <form-select
            label="Laminado Vuelta"
            name="laminado_vuelta_t2"
            v-model="empaque.laminado_vuelta_t2"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.laminadosVuelta"
            :required="naTacon2 ? false : true"
            :disabled="(naTacon2 || validacionDisabled) ? true : false"
            :error="errors.empaque?.laminado_vuelta_t2"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'laminado_vuelta_t2') || camposQueCambiaron.includes('laminado_vuelta_t2')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            >
        </form-select>
    </div>
    
    {{--  Barniz Sakurai Frente  --}}
    <div>
        <form-select
            label="Barniz Sakurai Frente"
            name="barniz_sakurai_frente_t2"
            v-model="empaque.barniz_sakurai_frente_t2"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.barnicesSakuraiFrente"
            :required="naTacon2 ? false : true"
            :disabled="(naTacon2 || validacionDisabled) ? true : false"
            :error="errors.empaque?.barniz_sakurai_frente_t2"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'barniz_sakurai_frente_t2') || camposQueCambiaron.includes('barniz_sakurai_frente_t2')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            >
        </form-select>
    </div>

    {{--  Barniz Sakurai Vuelta  --}}
    <div>
        <form-select
            label="Barniz Sakurai Vuelta"
            name="barniz_sakurai_vuelta_t2"
            v-model="empaque.barniz_sakurai_vuelta_t2"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.barnicesSakuraiVuelta"
            :required="naTacon2 ? false : true"
            :disabled="(naTacon2 || validacionDisabled) ? true : false"
            :error="errors.empaque?.barniz_sakurai_vuelta_t2"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'barniz_sakurai_vuelta_t2') || camposQueCambiaron.includes('barniz_sakurai_vuelta_t2')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            >
        </form-select>
    </div>

    {{--  realces  --}}
    <div>
        <form-select
            label="Realces"
            name="realce_t2"
            v-model="empaque.realce_t2"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.realces"
            :required="naTacon2 ? false : true"
            :disabled="(naTacon2 || validacionDisabled) ? true : false"
            :error="errors.empaque?.realce_t2"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'realce_t2') || camposQueCambiaron.includes('realce_t2')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            >
        </form-select>
    </div>

    {{--  Placa de Realce(Medida cm LxA)  --}}
    <div>
        <form-input
            label="Placa de Realce (Medida cm LxA)"
            name="placa_realce_t2"
            v-model="empaque.placa_realce_t2"
            placeholder="---"
            type="text"
            :required="empaque.realce_t2 != 288 && empaque.realce_t2 != '' ? true : false"
            :disabled="empaque.realce_t2 != 288 && empaque.realce_t2 != '' && !validacionDisabled ? false : true"
            :error="errors.empaque?.placa_realce_t2"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'placa_realce_t2') || camposQueCambiaron.includes('placa_realce_t2')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
        >
        </form-input>
    </div>

    {{--  Hot Stamping  --}}
    <div>
        <form-select
            label="Hot Stamping"
            name="hot_stamping_t2"
            v-model="empaque.hot_stamping_t2"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.hotStampings"
            :required="naTacon2 ? false : true"
            :disabled="(naTacon2 || validacionDisabled) ? true : false"
            :error="errors.empaque?.hot_stamping_t2"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'hot_stamping_t2') || camposQueCambiaron.includes('hot_stamping_t2')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
        >
        </form-select>
    </div>

    {{--  Placa Hot Stamping (Medida cm LxA)  --}}
    <div>
        <form-input
            label="Placa Hot Stamping (Medida cm LxA)"
            name="placa_hot_stamping_t2"
            v-model="empaque.placa_hot_stamping_t2"
            placeholder="---"
            type="text"
            :required="empaque.hot_stamping_t2 != 193 && empaque.hot_stamping_t2 != '' ? true : false"
            :disabled="empaque.hot_stamping_t2 != 193 && empaque.hot_stamping_t2 != '' && !validacionDisabled ? false : true"
            :error="errors.empaque?.placa_hot_stamping_t2"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'placa_hot_stamping_t2') || camposQueCambiaron.includes('placa_hot_stamping_t2')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
        >
        </form-input>
    </div>

    {{--  Color Hot Stamping  --}}
    <div>
        <form-select
            label="Color Hot Stamping"
            name="color_hot_stamping_t2"
            v-model="empaque.color_hot_stamping_t2"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.coloresHotStamping"
            :required="naTacon2 ? false : true"
            :disabled="(naTacon2 || validacionDisabled) ? true : false"
            :error="errors.empaque?.color_hot_stamping_t2"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'color_hot_stamping_t2') || camposQueCambiaron.includes('color_hot_stamping_t2')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
        >
        </form-select>
    </div>

    {{--  Suaje  --}}
    <div>
        <form-select
            label="Suaje"
            name="suaje_t2"
            v-model="empaque.suaje_t2"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.suajes"
            :required="naTacon2 ? false : true"
            :disabled="(naTacon2 || validacionDisabled) ? true : false"
            :error="errors.empaque?.suaje_t2"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'suaje_t2') || camposQueCambiaron.includes('suaje_t2')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
        >
        </form-select>
    </div>

    {{--  Suajado  --}}
    <div>
        <form-select
            label="Suajado"
            name="suajado_t2"
            v-model="empaque.suajado_t2"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.suajadosList"
            :required="naTacon2 ? false : true"
            :disabled="(naTacon2 || validacionDisabled) ? true : false"
            :error="errors.empaque?.suajado_t2"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'suajado_t2') || camposQueCambiaron.includes('suajado_t2')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
        ></form-select>
    </div>

    {{--  Otros acabados  --}}
    <div>
        <form-select
            label="Otros acabados"
            name="otros_acabados_t2"
            v-model="empaque.otros_acabados_t2"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.otrosAcabados"
            :required="naTacon2 ? false : true"
            :disabled="(naTacon2 || validacionDisabled) ? true : false"
            :error="errors.empaque?.otros_acabados_t2"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'otros_acabados_t2') || camposQueCambiaron.includes('otros_acabados_t2')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
        >
        </form-select>
    </div>
</div>