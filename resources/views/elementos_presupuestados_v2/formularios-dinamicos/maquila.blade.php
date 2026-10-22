<div id="maquilaForm">
    <div class="text-lg font-bold text-gray-700 uppercase py-2 px-1">
        <h3 class="text-sky-600">maquila</h3>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 px-1 border border-gray-300 rounded-lg p-2">
        {{--  Empaque  --}}
        <div class="md:col-span-3">
            <form-select
                label="Empaque"
                name="empaque"
                v-model="maquila.empaque"
                :options="maquilaCatalogos.empaques"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.maquila?.empaque"
                :is-modified="(bitacoraCambiosMaquila.some(c => c.campo === 'empaque') || camposQueCambiaron.includes('empaque')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Entregas  --}}
        <div class="md:col-span-3">
            <form-select
                label="Entregas"
                name="entrega"
                v-model="maquila.entrega"
                :options="maquilaCatalogos.entregas"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.maquila?.entrega"
                :is-modified="(bitacoraCambiosMaquila.some(c => c.campo === 'entrega') || camposQueCambiaron.includes('entrega')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Lugar de Entrega  --}}
        <div class="md:col-span-3">
            <form-select
                label="Lugar de Entrega"
                name="lugar_entrega"
                v-model="maquila.lugar_entrega"
                :options="maquilaCatalogos.lugarEntregas"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.maquila?.lugar_entrega"
                :is-modified="(bitacoraCambiosMaquila.some(c => c.campo === 'lugar_entrega') || camposQueCambiaron.includes('lugar_entrega')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Suajado  --}}
        {{--  <div class="md:col-span-3">
            <form-select
                label="Suajado"
                name="suajado"
                v-model="maquila.suajado"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="maquilaCatalogos.suajadosList"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.maquila?.suajado"
                :is-modified="(bitacoraCambiosMaquila.some(c => c.campo === 'suajado') || camposQueCambiaron.includes('suajado')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>  --}}

        {{--  Descripcion  --}}
        <div class="md:col-span-6">
            <form-textarea
                label="Descripción"
                name="descripcion"
                v-model="maquila.descripcion"
                placeholder="Escribe tu descripción aquí..."
                :required="false"
                :disabled="validacionDisabled"
                :error="errors.maquila?.descripcion"
                :is-modified="(bitacoraCambiosMaquila.some(c => c.campo === 'descripcion') || camposQueCambiaron.includes('descripcion')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-textarea>
        </div>

        {{--  Observaciones  --}}
        {{--  <div class="md:col-span-6">
            <form-textarea
                label="Observaciones"
                name="observaciones"
                v-model="maquila.observaciones"
                placeholder="Escribe tus observaciones aquí..."
                :required="false"
                :disabled="validacionDisabled"
                :error="errors.maquila?.observaciones"
            ></form-textarea>
        </div>  --}}

        {{--  Instalación  --}}
        <div class="md:col-span-6">
            <form-textarea
                label="Instalación"
                name="instalacion"
                v-model="maquila.instalacion"
                placeholder="Escribe la instalación aquí..."
                :required="false"
                :disabled="validacionDisabled"
                :error="errors.maquila?.instalacion"
                :is-modified="(bitacoraCambiosMaquila.some(c => c.campo === 'instalacion') || camposQueCambiaron.includes('instalacion')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-textarea>
        </div>

        {{--  Observaciones  --}}
        <div class="md:col-span-12" title="Estas observaciones serán visibles únicamente para el cotizador y sirven como complemento para aquellos campos en los que se haya seleccionado la opción 'Otro (En observaciones)'">
            <form-textarea
                label="Observaciones (Para cotizador)"
                name="observaciones"
                v-model="maquila.observaciones"
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

        <div class="md:col-span-12 text-sm" v-if="(estatusEnQuePuedeActualizarCS.includes(parseInt(estatusOportunidad)) || maquila.observacionesExtras.length > 0) && tipoForm == 2" title="Estos comentarios son visibles para el equipo de Customers y se utilizan para comunicar información adicional relevante sobre el elemento que no se haya cubierto en los campos anteriores, ademas de que se considera como cambio minimo al agregar al menos 1.">
            <label class="block text-gray-700 font-bold mb-1">Comentarios adicionales</label>
            <div v-for="(comentario, idx) in maquila.observacionesExtras" :key="idx" class="flex items-center mb-2">
                <textarea
                    v-model="maquila.observacionesExtras[idx]"
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
