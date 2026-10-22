<div id="empaqueForm">
    <style>
        .empaque-tab--active {
            box-shadow: 0 2px 8px #38bdf8;
            font-weight: bold;
        }
    </style>
    <div class="grid md:grid-cols-3 grid-cols-1 mt-4" v-show="general.cat_EP_tipoProducto_id != '10-2'">
        <div
            class="empaque-tab cursor-pointer font-bold uppercase p-2 rounded-t-md mb-2 border text-sky-600"
            :class="{
                'empaque-tab--active bg-sky-600 text-white border-sky-700': empaqueFormSection === 'general',
                'bg-white border-gray-300': empaqueFormSection !== 'general'
            }"
            v-on:click="toggleEmpaqueForm('general')"
        >
            <h3 class="text-center">General</h3>
        </div>
        <div
            class="empaque-tab cursor-pointer font-bold uppercase p-2 rounded-t-md mb-2 border text-sky-600"
            :class="{
                'empaque-tab--active bg-sky-600 text-white border-sky-700': empaqueFormSection === 'tacon1',
                'bg-white border-gray-300': empaqueFormSection !== 'tacon1'
            }"
            v-on:click="toggleEmpaqueForm('tacon1')"
        >
            <h3 class="text-center">Tacon 1</h3>
        </div>
        <div
            class="empaque-tab cursor-pointer font-bold uppercase p-2 rounded-t-md mb-2 border text-sky-600"
            :class="{
                'empaque-tab--active bg-sky-600 text-white border-sky-700': empaqueFormSection === 'tacon2',
                'bg-white border-gray-300': empaqueFormSection !== 'tacon2'
            }"
            v-on:click="toggleEmpaqueForm('tacon2')"
        >
            <h3 class="text-center">Tacon 2</h3>
        </div>
    </div>

    <div v-show="empaqueFormSection == 'general'">
        @include('elementos_presupuestados_v2.formularios-dinamicos.empaque-general')
    </div>
    <div v-show="general.cat_EP_tipoProducto_id != '10-2' && empaqueFormSection == 'tacon1'">
        <div class="flex justify-end w-full px-4">
            <div class="flex space-x-2 items-center">
                <label for="naTacon1" class="block text-gray-700 text-sm font-bold mb-0!">
                    N/A
                </label>
                <input
                    type="checkbox"
                    v-model="naTacon1"
                    :class="[
                        'border border-gray-300 rounded h-auto text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 focus-next'
                    ]"
                    id="naTacon1"
                    name="naTacon1"
                    :disabled="validacionDisabled"
                >
            </div>
        </div>
        @include('elementos_presupuestados_v2.formularios-dinamicos.empaque-tacon-1')
    </div>
    <div v-show="general.cat_EP_tipoProducto_id != '10-2' && empaqueFormSection == 'tacon2'">
        <div class="flex justify-end w-full px-4">
            <div class="flex space-x-2 items-center">
                <label for="naTacon2" class="block text-gray-700 text-sm font-bold mb-0!">
                    N/A
                </label>
                <input
                    type="checkbox"
                    v-model="naTacon2"
                    :class="[
                        'border border-gray-300 rounded h-auto text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 focus-next'
                    ]"
                    id="naTacon2"
                    name="naTacon2"
                    :disabled="validacionDisabled"
                >
            </div>
        </div>
        @include('elementos_presupuestados_v2.formularios-dinamicos.empaque-tacon-2')
    </div>

    <div class="text-lg font-bold text-gray-700 uppercase py-2 px-1">
        <h3 class="text-sky-600">adicionales</h3>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 px-1 border border-gray-300 rounded-lg p-2">
        {{--  TIPO DE PEGUE/ARMADO  --}}
        <div class="md:col-span-3">
            <form-select
                label="Tipo de Pegue/Armado"
                name="tipo_pegue_armado"
                v-model="empaque.tipo_pegue_armado"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="empaqueCatalogos.tiposPeguesArmado"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.empaque?.tipo_pegue_armado"
                :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'tipo_pegue_armado') || camposQueCambiaron.includes('tipo_pegue_armado')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            >
            </form-select>
        </div>

        {{--  Empaque  --}}
        <div class="md:col-span-3">
            <form-select
                label="Empaque"
                name="empaque"
                v-model="empaque.empaque"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="empaqueCatalogos.empaques"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.empaque?.empaque"
                :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'empaque') || camposQueCambiaron.includes('empaque')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            >
            </form-select>
        </div>

        {{--  Entregas  --}}
        <div class="md:col-span-3">
            <form-select
                label="Entregas"
                name="entrega"
                v-model="empaque.entrega"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="empaqueCatalogos.entregas"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.empaque?.entrega"
                :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'entrega') || camposQueCambiaron.includes('entrega')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            >
            </form-select>
        </div>

        {{--  Lugar de Entrega  --}}
        <div class="md:col-span-3">
            <form-select
                label="Lugar de Entrega"
                name="lugar_entrega"
                v-model="empaque.lugar_entrega"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="empaqueCatalogos.lugarEntregas"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.empaque?.lugar_entrega"
                :is-modified="(bitacoraCambiosEmpaque.some(c => c.campo === 'lugar_entrega') || camposQueCambiaron.includes('lugar_entrega')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            >
            </form-select>
        </div>

        {{--  Observaciones  --}}
        <div class="md:col-span-12" title="Estas observaciones serán visibles únicamente para el cotizador y sirven como complemento para aquellos campos en los que se haya seleccionado la opción 'Otro (En observaciones)'">
            <form-textarea
                label="Observaciones (Para cotizador)"
                name="observaciones"
                v-model="empaque.observaciones"
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

        <div class="md:col-span-12 text-sm" v-if="(estatusEnQuePuedeActualizarCS.includes(parseInt(estatusOportunidad)) || empaque.observacionesExtras.length > 0) && tipoForm == 2" title="Estos comentarios son visibles para el equipo de Customers y se utilizan para comunicar información adicional relevante sobre el elemento que no se haya cubierto en los campos anteriores, ademas de que se considera como cambio minimo al agregar al menos 1.">
            <label class="block text-gray-700 font-bold mb-1">Comentarios adicionales</label>
            <div v-for="(comentario, idx) in empaque.observacionesExtras" :key="idx" class="flex items-center mb-2">
                <textarea
                    v-model="empaque.observacionesExtras[idx]"
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
