<div class="text-lg font-bold text-gray-700 uppercase py-2 px-1">
    <h3 class="text-sky-600">empaque</h3>
</div>

<div class="grid sm:grid-cols-4 lg:grid-cols-6 gap-4 px-1 border border-gray-300 rounded-lg p-2">
    {{--  Sustratos  --}}
    <div>
        <form-select
            label="Sustrato gr/ptos"
            name="sustratos"
            v-model="empaque.sustrato"
            :options="empaqueCatalogos.sustratos"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :required="true"
            :disabled="validacionDisabled"
            :error="errors.empaque?.sustrato"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'sustrato') || camposQueCambiaron.includes('sustrato')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            >
        </form-select>
    </div>

    {{--  Gramajes  --}}
    {{--  <div>
        <form-select
            label="Gramaje/puntos"
            name="gramaje"
            v-model="empaque.gramaje"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.gramajes"
            :required="true"
            :disabled="validacionDisabled"
            :error="errors.empaque?.gramaje"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'gramaje') || camposQueCambiaron.includes('gramaje')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            >
        </form-select>
    </div>  --}}

    {{--  Tintas frente  --}}
    <div>
        <form-select
            label="Tintas frente"
            name="tinta_frente"
            v-model="empaque.tinta_frente"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.tintasFrente"
            :required="true"
            :disabled="validacionDisabled"
            :error="errors.empaque?.tinta_frente"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'tinta_frente') || camposQueCambiaron.includes('tinta_frente')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            >
        </form-select>
    </div>

    {{--  Tintas vuelta  --}}
    <div>
        <form-select
            label="Tintas vuelta"
            name="tinta_vuelta"
            v-model="empaque.tinta_vuelta"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.tintasVuelta"
            :required="true"
            :disabled="validacionDisabled"
            :error="errors.empaque?.tinta_vuelta"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'tinta_vuelta') || camposQueCambiaron.includes('tinta_vuelta')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            >
        </form-select>
    </div>

    {{--  Medida Final (cm)  --}}
    <div>
        <form-select
            label="Medida Final (cm)"
            name="medida_final"
            v-model="empaque.medida_final"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.medidasFinales"
            :required="true"
            :disabled="validacionDisabled"
            :error="errors.empaque?.medida_final"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'medida_final') || camposQueCambiaron.includes('medida_final')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            >
        </form-select>

        <div class="flex gap-2 mt-2">
            <form-input
                label="Largo (mm)"
                name="medida_final_largo_general"
                v-model="empaque.medida_final_largo_general"
                type="number"
                placeholder="Ingrese largo en mm"
                min="0"
                max="999999999"
                :required="false"
                :disabled="validacionDisabled"
                :error="errors.empaque?.medida_final_largo_general"
                :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'medida_final_largo_general') || camposQueCambiaron.includes('medida_final_largo_general')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-input>

            <form-input
                label="Ancho (mm)"
                name="medida_final_ancho_general"
                v-model="empaque.medida_final_ancho_general"
                type="number"
                placeholder="Ingrese ancho en mm"
                min="0"
                max="999999999"
                :required="false"
                :disabled="validacionDisabled"
                :error="errors.empaque?.medida_final_ancho_general"
                :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'medida_final_ancho_general') || camposQueCambiaron.includes('medida_final_ancho_general')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-input>

            <form-input
                label="Alto (mm)"
                name="medida_final_alto_general"
                v-model="empaque.medida_final_alto_general"
                type="number"
                placeholder="Ingrese alto en mm"
                min="0"
                max="999999999"
                :required="false"
                :disabled="validacionDisabled"
                :error="errors.empaque?.medida_final_alto_general"
                :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'medida_final_alto_general') || camposQueCambiaron.includes('medida_final_alto_general')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-input>
        </div>
    </div>

    {{--  Medida Extendida (cm)  --}}
    <div>
        <form-select
            label="Medida Extendida (cm)"
            name="medida_extendida"
            v-model="empaque.medida_extendida"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.medidasExtendidas"
            :required="true"
            :disabled="validacionDisabled"
            :error="errors.empaque?.medida_extendida"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'medida_extendida') || camposQueCambiaron.includes('medida_extendida')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            >
        </form-select>

        <div class="flex gap-2 mt-2">
            <form-input
                label="Largo (mm)"
                name="medida_extendida_largo_general"
                v-model="empaque.medida_extendida_largo_general"
                type="number"
                placeholder="Ingrese largo en mm"
                min="0"
                max="999999999"
                :required="false"
                :disabled="validacionDisabled"
                :error="errors.empaque?.medida_extendida_largo_general"
                :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'medida_extendida_largo_general') || camposQueCambiaron.includes('medida_extendida_largo_general')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-input>

            <form-input
                label="Ancho (mm)"
                name="medida_extendida_ancho_general"
                v-model="empaque.medida_extendida_ancho_general"
                type="number"
                placeholder="Ingrese ancho en mm"
                min="0"
                max="999999999"
                :required="false"
                :disabled="validacionDisabled"
                :error="errors.empaque?.medida_extendida_ancho_general"
                :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'medida_extendida_ancho_general') || camposQueCambiaron.includes('medida_extendida_ancho_general')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-input>

            {{--  <form-input
                label="Alto (mm)"
                name="medida_extendida_alto_general"
                v-model="empaque.medida_extendida_alto_general"
                type="number"
                placeholder="Ingrese alto en mm"
                min="0"
                max="999999999"
                :required="false"
                :disabled="validacionDisabled"
                :error="errors.empaque?.medida_extendida_alto_general"
                :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'medida_extendida_alto_general') || camposQueCambiaron.includes('medida_extendida_alto_general')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-input>  --}}
        </div>
    </div>

    {{--  Barniz Frente  --}}
    <div>
        <form-select
            label="Barniz Frente"
            name="barniz_frente"
            v-model="empaque.barniz_frente"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.barnicesFrente"
            :required="true"
            :disabled="validacionDisabled"
            :error="errors.empaque?.barniz_frente"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'barniz_frente') || camposQueCambiaron.includes('barniz_frente')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            >
        </form-select>
    </div>

    {{--  Barniz Vuelta  --}}
    <div>
        <form-select
            label="Barniz Vuelta"
            name="barniz_vuelta"
            v-model="empaque.barniz_vuelta"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.barnicesVuelta"
            :required="true"
            :disabled="validacionDisabled"
            :error="errors.empaque?.barniz_vuelta"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'barniz_vuelta') || camposQueCambiaron.includes('barniz_vuelta')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            >
        </form-select>
    </div>

    {{--  Sustratos para Empalme  --}}
    <div>
        <form-select
            label="Sustratos para Empalme"
            name="sustrato_empalme"
            v-model="empaque.sustrato_empalme"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.sustratosParaEmpalme"
            :required="true"
            :disabled="validacionDisabled"
            :error="errors.empaque?.sustrato_empalme"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'sustrato_empalme') || camposQueCambiaron.includes('sustrato_empalme')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            >
        </form-select>
    </div>

    {{--  Requiere ventana  --}}
    <div>
        <form-select
            label="Requiere ventana"
            name="requiere_ventana"
            v-model="empaque.requiere_ventana"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.requieresVentana"
            :required="true"
            :disabled="validacionDisabled"
            :error="errors.empaque?.requiere_ventana"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'requiere_ventana') || camposQueCambiaron.includes('requiere_ventana')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            >
        </form-select>
    </div>

    {{--  Puntaje ventana  --}}
    <div>
        <form-select
            label="Puntaje ventana"
            name="puntaje_ventana"
            v-model="empaque.puntaje_ventana"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.puntajesVentana"
            :required="true"
            :disabled="validacionDisabled"
            :error="errors.empaque?.puntaje_ventana"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'puntaje_ventana') || camposQueCambiaron.includes('puntaje_ventana')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            >
        </form-select>
    </div>

    {{--  Otros  --}}
    <div>
        <form-select
            label="Otros"
            name="otros_empaque"
            v-model="empaque.otros_empaque"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.otrosEmpaque"
            :required="false"
            :disabled="validacionDisabled"
            :error="errors.empaque?.otros_empaque"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'otros_empaque') || camposQueCambiaron.includes('otros_empaque')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
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
            name="metalizado_frente"
            v-model="empaque.metalizado_frente"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.metalizadosFrente"
            :required="true"
            :disabled="validacionDisabled"
            :error="errors.empaque?.metalizado_frente"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'metalizado_frente') || camposQueCambiaron.includes('metalizado_frente')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            >
        </form-select>
    </div>

    {{--  Metalizado Vuelta  --}}
    <div>
        <form-select
            label="Metalizado Vuelta"
            name="metalizado_vuelta"
            v-model="empaque.metalizado_vuelta"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.metalizadosVuelta"
            :required="true"
            :disabled="validacionDisabled"
            :error="errors.empaque?.metalizado_vuelta"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'metalizado_vuelta') || camposQueCambiaron.includes('metalizado_vuelta')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            >
        </form-select>
    </div>

    {{--  Laminado Frente  --}}
    <div>
        <form-select
            label="Laminado Frente"
            name="laminado_frente"
            v-model="empaque.laminado_frente"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.laminadosFrente"
            :required="true"
            :disabled="validacionDisabled"
            :error="errors.empaque?.laminado_frente"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'laminado_frente') || camposQueCambiaron.includes('laminado_frente')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            >
        </form-select>
    </div>

    {{--  Laminado Vuelta  --}}
    <div>
        <form-select
            label="Laminado Vuelta"
            name="laminado_vuelta"
            v-model="empaque.laminado_vuelta"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.laminadosVuelta"
            :required="true"
            :disabled="validacionDisabled"
            :error="errors.empaque?.laminado_vuelta"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'laminado_vuelta') || camposQueCambiaron.includes('laminado_vuelta')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            >
        </form-select>
    </div>
    
    {{--  Barniz Sakurai Frente  --}}
    <div>
        <form-select
            label="Barniz Sakurai Frente"
            name="barniz_sakurai_frente"
            v-model="empaque.barniz_sakurai_frente"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.barnicesSakuraiFrente"
            :required="true"
            :disabled="validacionDisabled"
            :error="errors.empaque?.barniz_sakurai_frente"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'barniz_sakurai_frente') || camposQueCambiaron.includes('barniz_sakurai_frente')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            >
        </form-select>
    </div>

    {{--  Barniz Sakurai Vuelta  --}}
    <div>
        <form-select
            label="Barniz Sakurai Vuelta"
            name="barniz_sakurai_vuelta"
            v-model="empaque.barniz_sakurai_vuelta"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.barnicesSakuraiVuelta"
            :required="true"
            :disabled="validacionDisabled"
            :error="errors.empaque?.barniz_sakurai_vuelta"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'barniz_sakurai_vuelta') || camposQueCambiaron.includes('barniz_sakurai_vuelta')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            >
        </form-select>
    </div>

    {{--  realces  --}}
    <div>
        <form-select
            label="Realces"
            name="realce"
            v-model="empaque.realce"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.realces"
            :required="true"
            :disabled="validacionDisabled"
            :error="errors.empaque?.realce"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'realce') || camposQueCambiaron.includes('realce')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            >
        </form-select>
    </div>

    {{--  Placa de Realce(Medida cm LxA)  --}}
    <div>
        <form-input
            label="Placa de Realce (Medida cm LxA)"
            name="placa_realce"
            v-model="empaque.placa_realce"
            placeholder="---"
            type="text"
            :required="empaque.realce != 288 && empaque.realce != '' ? true : false"
            :disabled="empaque.realce != 288 && empaque.realce != '' && !validacionDisabled ? false : true"
            :error="errors.empaque?.placa_realce"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'placa_realce') || camposQueCambiaron.includes('placa_realce')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
        >
        </form-input>
    </div>

    {{--  Hot Stamping  --}}
    <div>
        <form-select
            label="Hot Stamping"
            name="hot_stamping"
            v-model="empaque.hot_stamping"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.hotStampings"
            :required="true"
            :disabled="validacionDisabled"
            :error="errors.empaque?.hot_stamping"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'hot_stamping') || camposQueCambiaron.includes('hot_stamping')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
        >
        </form-select>
    </div>

    {{--  Placa Hot Stamping (Medida cm LxA)  --}}
    <div>
        <form-input
            label="Placa Hot Stamping (Medida cm LxA)"
            name="placa_hot_stamping"
            v-model="empaque.placa_hot_stamping"
            placeholder="---"
            type="text"
            :required="empaque.hot_stamping != 193 && empaque.hot_stamping != '' ? true : false"
            :disabled="empaque.hot_stamping != 193 && empaque.hot_stamping != '' && !validacionDisabled ? false : true"
            :error="errors.empaque?.placa_hot_stamping"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'placa_hot_stamping') || camposQueCambiaron.includes('placa_hot_stamping')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
        >
        </form-input>
    </div>

    {{--  Color Hot Stamping  --}}
    <div>
        <form-select
            label="Color Hot Stamping"
            name="color_hot_stamping"
            v-model="empaque.color_hot_stamping"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.coloresHotStamping"
            :required="true"
            :disabled="validacionDisabled"
            :error="errors.empaque?.color_hot_stamping"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'color_hot_stamping') || camposQueCambiaron.includes('color_hot_stamping')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
        >
        </form-select>
    </div>

    {{--  Suaje  --}}
    <div>
        <form-select
            label="Suaje"
            name="suaje"
            v-model="empaque.suaje"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.suajes"
            :required="true"
            :disabled="validacionDisabled"
            :error="errors.empaque?.suaje"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'suaje') || camposQueCambiaron.includes('suaje')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
        >
        </form-select>
    </div>

    {{--  Suajado  --}}
    <div>
        <form-select
            label="Suajado"
            name="suajado"
            v-model="empaque.suajado"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.suajadosList"
            :required="true"
            :disabled="validacionDisabled"
            :error="errors.empaque?.suajado"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'suajado') || camposQueCambiaron.includes('suajado')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
        ></form-select>
    </div>

    {{--  Otros acabados  --}}
    <div>
        <form-select
            label="Otros acabados"
            name="otros_acabados"
            v-model="empaque.otros_acabados"
            id-field="id"
            value-field="descripcion"
            placeholder="---"
            :options="empaqueCatalogos.otrosAcabados"
            :required="false"
            :disabled="validacionDisabled"
            :error="errors.empaque?.otros_acabados"
            :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'otros_acabados') || camposQueCambiaron.includes('otros_acabados')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
        >
        </form-select>
    </div>
</div>