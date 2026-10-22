<div id="displayForm">
    <div class="text-lg font-bold text-gray-700 uppercase py-2 px-1">
        <h3 class="text-sky-600">display</h3>
    </div>

    <div class="grid sm:grid-cols-4 lg:grid-cols-6 gap-4 px-1 border border-gray-300 rounded-lg p-2">
        {{--  Sustratos  --}}
        <div>
            <form-select
                label="Sustrato gr/ptos"
                name="sustrato"
                v-model="display.sustrato"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="displayCatalogos.sustratos"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.display?.sustrato"
                :is-modified="(bitacoraCambiosDisplay.some(c => c.campo === 'sustrato') || camposQueCambiaron.includes('sustrato')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Gramaje  --}}
        {{--  <div>
            <form-select
                label="Gramaje/Puntos"
                name="gramaje"
                v-model="display.gramaje"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="displayCatalogos.gramajes"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.display?.gramaje"
                :is-modified="(bitacoraCambiosDisplay.some(c => c.campo === 'gramaje') || camposQueCambiaron.includes('gramaje')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>  --}}

        {{--  Tintas Frente  --}}
        <div>
            <form-select
                label="Tintas Frente"
                name="tinta_frente"
                v-model="display.tinta_frente"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="displayCatalogos.tintasFrentes"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.display?.tinta_frente"
                :is-modified="(bitacoraCambiosDisplay.some(c => c.campo === 'tinta_frente') || camposQueCambiaron.includes('tinta_frente')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Sustrato para Empalme  --}}
        <div>
            <form-select
                label="Sustrato para Empalme"
                name="sustrato_empalme"
                v-model="display.sustrato_empalme"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="displayCatalogos.sustratosParaEmpalme"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.display?.sustrato_empalme"
                :is-modified="(bitacoraCambiosDisplay.some(c => c.campo === 'sustrato_empalme') || camposQueCambiaron.includes('sustrato_empalme')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Medida final  --}}
        <div>
            <form-select
                label="Medida Final (mm)"
                name="medida_final"
                v-model="display.medida_final"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="displayCatalogos.medidasFinales"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.display?.medida_final"
                :is-modified="(bitacoraCambiosDisplay.some(c => c.campo === 'medida_final') || camposQueCambiaron.includes('medida_final')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>

            <div class="flex gap-2 mt-2">
                <form-input
                    label="Largo (mm)"
                    name="medida_final_largo"
                    v-model="display.medida_final_largo"
                    type="number"
                    placeholder="Ingrese largo en mm"
                    min="0"
                    max="999999999"
                    :required="false"
                    :disabled="validacionDisabled"
                    :error="errors.display?.medida_final_largo"
                    :is-modified="(bitacoraCambiosDisplay.some(c => c.campo === 'medida_final_largo') || camposQueCambiaron.includes('medida_final_largo')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-input>

                <form-input
                    label="Ancho (mm)"
                    name="medida_final_ancho"
                    v-model="display.medida_final_ancho"
                    type="number"
                    placeholder="Ingrese ancho en mm"
                    min="0"
                    max="999999999"
                    :required="false"
                    :disabled="validacionDisabled"
                    :error="errors.display?.medida_final_ancho"
                    :is-modified="(bitacoraCambiosDisplay.some(c => c.campo === 'medida_final_ancho') || camposQueCambiaron.includes('medida_final_ancho')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-input>

                <form-input
                    label="Alto (mm)"
                    name="medida_final_alto"
                    v-model="display.medida_final_alto"
                    type="number"
                    placeholder="Ingrese alto en mm"
                    min="0"
                    max="999999999"
                    :required="false"
                    :disabled="validacionDisabled"
                    :error="errors.display?.medida_final_alto"
                    :is-modified="(bitacoraCambiosDisplay.some(c => c.campo === 'medida_final_alto') || camposQueCambiaron.includes('medida_final_alto')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-input>
            </div>
        </div>

        {{--  Medida Extendida (mm)  --}}
        <div>
            <form-select
                label="Medida Extendida (mm)"
                name="medida_extendida"
                v-model="display.medida_extendida"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="displayCatalogos.medidasExtendidas"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.display?.medida_extendida"
                :is-modified="(bitacoraCambiosDisplay.some(c => c.campo === 'medida_extendida') || camposQueCambiaron.includes('medida_extendida')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>

            <div class="flex gap-2 mt-2">
                <form-input
                    label="Largo (mm)"
                    name="medida_extendida_largo"
                    v-model="display.medida_extendida_largo"
                    type="number"
                    placeholder="Ingrese largo en mm"
                    min="0"
                    max="999999999"
                    :required="false"
                    :disabled="validacionDisabled"
                    :error="errors.display?.medida_extendida_largo"
                    :is-modified="(bitacoraCambiosDisplay.some(c => c.campo === 'medida_extendida_largo') || camposQueCambiaron.includes('medida_extendida_largo')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-input>

                <form-input
                    label="Ancho (mm)"
                    name="medida_extendida_ancho"
                    v-model="display.medida_extendida_ancho"
                    type="number"
                    placeholder="Ingrese ancho en mm"
                    min="0"
                    max="999999999"
                    :required="false"
                    :disabled="validacionDisabled"
                    :error="errors.display?.medida_extendida_ancho"
                    :is-modified="(bitacoraCambiosDisplay.some(c => c.campo === 'medida_extendida_ancho') || camposQueCambiaron.includes('medida_extendida_ancho')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-input>

                <form-input
                    label="Alto (mm)"
                    name="medida_extendida_alto"
                    v-model="display.medida_extendida_alto"
                    type="number"
                    placeholder="Ingrese alto en mm"
                    min="0"
                    max="999999999"
                    :required="false"
                    :disabled="validacionDisabled"
                    :error="errors.display?.medida_extendida_alto"
                    :is-modified="(bitacoraCambiosDisplay.some(c => c.campo === 'medida_extendida_alto') || camposQueCambiaron.includes('medida_extendida_alto')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-input>
            </div>
        </div>

        {{--  Otros display  --}}
        <div class="col-span-6">
            <form-select
                label="Otros"
                name="otros"
                v-model="display.otros_display"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="displayCatalogos.otrosDisplays"
                :required="false"
                :disabled="validacionDisabled"
                :error="errors.display?.otros_display"
                :is-modified="(bitacoraCambiosDisplay.some(c => c.campo === 'otros_display') || camposQueCambiaron.includes('otros_display')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
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
                v-model="display.metalizado_frente"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="displayCatalogos.metalizadosFrente"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.display?.metalizado_frente"
                :is-modified="(bitacoraCambiosDisplay.some(c => c.campo === 'metalizado_frente') || camposQueCambiaron.includes('metalizado_frente')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Metalizado Vuelta  --}}
        <div>
            <form-select
                label="Metalizado Vuelta"
                name="metalizado_vuelta"
                v-model="display.metalizado_vuelta"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="displayCatalogos.metalizadosVuelta"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.display?.metalizado_vuelta"
                :is-modified="(bitacoraCambiosDisplay.some(c => c.campo === 'metalizado_vuelta') || camposQueCambiaron.includes('metalizado_vuelta')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Laminado Frente  --}}
        <div>
            <form-select
                label="Laminado Frente"
                name="laminado_frente"
                v-model="display.laminado_frente"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="displayCatalogos.laminadosFrente"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.display?.laminado_frente"
                :is-modified="(bitacoraCambiosDisplay.some(c => c.campo === 'laminado_frente') || camposQueCambiaron.includes('laminado_frente')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Laminado Vuelta  --}}
        <div>
            <form-select
                label="Laminado Vuelta"
                name="laminado_vuelta"
                v-model="display.laminado_vuelta"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="displayCatalogos.laminadosVuelta"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.display?.laminado_vuelta"
                :is-modified="(bitacoraCambiosDisplay.some(c => c.campo === 'laminado_vuelta') || camposQueCambiaron.includes('laminado_vuelta')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Barniz Frente  --}}
        <div>
            <form-select
                label="Barniz Frente"
                name="barniz_sakurai_frente"
                v-model="display.barniz_sakurai_frente"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="displayCatalogos.barnicesSakuraiFrente"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.display?.barniz_sakurai_frente"
                :is-modified="(bitacoraCambiosDisplay.some(c => c.campo === 'barniz_sakurai_frente') || camposQueCambiaron.includes('barniz_sakurai_frente')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Barniz Vuelta  --}}
        <div>
            <form-select
                label="Barniz Vuelta"
                name="barniz_sakurai_vuelta"
                v-model="display.barniz_sakurai_vuelta"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="displayCatalogos.barnicesSakuraiVuelta"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.display?.barniz_sakurai_vuelta"
                :is-modified="(bitacoraCambiosDisplay.some(c => c.campo === 'barniz_sakurai_vuelta') || camposQueCambiaron.includes('barniz_sakurai_vuelta')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Suajado  --}}
        <div>
            <form-select
                label="Suajado"
                name="suajado"
                v-model="display.suajado"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="displayCatalogos.suajadosList"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.display?.suajado"
                :is-modified="(bitacoraCambiosDisplay.some(c => c.campo === 'suajado') || camposQueCambiaron.includes('suajado')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Otros  --}}
        <div class="col-span-5">
            <form-select
                label="Otros"
                name="otros_metalizado_frente"
                v-model="display.otros_metalizado_frente"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="displayCatalogos.otrosMetalizadosFrente"
                :required="false"
                :disabled="validacionDisabled"
                :error="errors.display?.otros_metalizado_frente"
                :is-modified="(bitacoraCambiosDisplay.some(c => c.campo === 'otros_metalizado_frente') || camposQueCambiaron.includes('otros_metalizado_frente')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>
    </div>

    <div class="text-lg font-bold text-gray-700 uppercase py-2 px-1">
        <h3 class="text-sky-600">Adicionales</h3>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 px-1 border border-gray-300 rounded-lg p-2">
        {{--  Tipo de Pegue  --}}
        <div class="md:col-span-3">
            <form-select
                label="Tipo de Pegue"
                name="tipo_pegue_armado"
                v-model="display.tipo_pegue_armado"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="displayCatalogos.tipoPegueArmado"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.display?.tipo_pegue_armado"
                :is-modified="(bitacoraCambiosDisplay.some(c => c.campo === 'tipo_pegue_armado') || camposQueCambiaron.includes('tipo_pegue_armado')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Empaque Primario  --}}
        <div class="md:col-span-3">
            <form-select
                label="Empaque Primario"
                name="empaque_primario"
                v-model="display.empaque_primario"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="displayCatalogos.empaquesPrimarios"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.display?.empaque_primario"
                :is-modified="(bitacoraCambiosDisplay.some(c => c.campo === 'empaque_primario') || camposQueCambiaron.includes('empaque_primario')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Empaque Secundario  --}}
        <div class="md:col-span-3">
            <form-select
                label="Empaque Secundario"
                name="empaque_secundario"
                v-model="display.empaque_secundario"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="displayCatalogos.empaquesSecundarios"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.display?.empaque_secundario"
                :is-modified="(bitacoraCambiosDisplay.some(c => c.campo === 'empaque_secundario') || camposQueCambiaron.includes('empaque_secundario')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Lugar de Entrega  --}}
        <div class="md:col-span-3">
            <form-select
                label="Lugar de Entrega"
                name="lugar_entrega"
                v-model="display.lugar_entrega"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="displayCatalogos.lugaresEntrega"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.display?.lugar_entrega"
                :is-modified="(bitacoraCambiosDisplay.some(c => c.campo === 'lugar_entrega') || camposQueCambiaron.includes('lugar_entrega')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Observaciones  --}}
        <div class="md:col-span-12" title="Estas observaciones serán visibles únicamente para el cotizador y sirven como complemento para aquellos campos en los que se haya seleccionado la opción 'Otro (En observaciones)'">
            <form-textarea
                label="Observaciones (Para cotizador)"
                name="observaciones"
                v-model="display.observaciones"
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

        <div class="md:col-span-12 text-sm" v-if="(estatusEnQuePuedeActualizarCS.includes(parseInt(estatusOportunidad)) || display.observacionesExtras.length > 0) && tipoForm == 2" title="Estos comentarios son visibles para el equipo de Customers y se utilizan para comunicar información adicional relevante sobre el elemento que no se haya cubierto en los campos anteriores, ademas de que se considera como cambio minimo al agregar al menos 1.">
            <label class="block text-gray-700 font-bold mb-1">Comentarios adicionales</label>
            <div v-for="(comentario, idx) in display.observacionesExtras" :key="idx" class="flex items-center mb-2">
                <textarea
                    v-model="display.observacionesExtras[idx]"
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
