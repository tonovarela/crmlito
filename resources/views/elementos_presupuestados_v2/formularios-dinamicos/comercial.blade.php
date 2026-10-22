<div id="comercialForm">
    <div class="text-lg font-bold text-gray-700 uppercase py-2 px-1">
        <h3 class="text-sky-600">comercial (editorial)</h3>
    </div>

    <div class="grid sm:grid-cols-4 lg:grid-cols-6 gap-4 px-1 border border-gray-300 rounded-lg p-2">
        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4 border-r border-gray-300 pr-3">
            <div class="text-base font-bold text-gray-700 uppercase p-1 md:col-span-2">
                <h3 class="text-cyan-600">interiores</h3>
            </div>
            {{--  No páginas  --}}
            <div>
                <form-input
                    label="No. Páginas"
                    name="num_paginas_interiores"
                    v-model="comercial.num_paginas_interiores"
                    placeholder="---"
                    type="text"
                    :required="true"
                    :disabled="validacionDisabled"
                    :error="errors.comercial?.num_paginas_interiores"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'num_paginas_interiores') || camposQueCambiaron.includes('num_paginas_interiores')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-input>
            </div>

            {{--  sustrato interiores  --}}
            <div>
                <form-select
                    label="Sustrato"
                    name="sustrato_interiores"
                    v-model="comercial.sustrato_interiores"
                    :options="comercialCatalogos.sustratosInteriores"
                    id-field="id"
                    value-field="descripcion"
                    placeholder="---"
                    :required="true"
                    :disabled="validacionDisabled"
                    :error="errors.comercial?.sustrato_interiores"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'sustrato_interiores') || camposQueCambiaron.includes('sustrato_interiores')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-select>
            </div>

            {{--  gramaje interiores  --}}
            {{--  <div>
                <form-select
                    label="Gramaje"
                    name="gramaje_interiores"
                    v-model="comercial.gramaje_interiores"
                    :options="comercialCatalogos.gramajesInteriores"
                    id-field="id"
                    value-field="descripcion"
                    placeholder="---"
                    :required="true"
                    :disabled="validacionDisabled"
                    :error="errors.comercial?.gramaje_interiores"
                ></form-select>
            </div>  --}}

            {{--  tinta frente interiores  --}}
            <div>
                <form-select
                    label="Tinta Frente"
                    name="tinta_frente_interiores"
                    v-model="comercial.tinta_frente_interiores"
                    :options="comercialCatalogos.tintasFrentesInteriores"
                    id-field="id"
                    value-field="descripcion"
                    placeholder="---"
                    :required="true"
                    :disabled="validacionDisabled"
                    :error="errors.comercial?.tinta_frente_interiores"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'tinta_frente_interiores') || camposQueCambiaron.includes('tinta_frente_interiores')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-select>
            </div>

            {{--  tinta vuelta interiores  --}}
            <div>
                <form-select
                    label="Tinta Vuelta"
                    name="tinta_vuelta_interiores"
                    v-model="comercial.tinta_vuelta_interiores"
                    :options="comercialCatalogos.tintasVueltaInteriores"
                    id-field="id"
                    value-field="descripcion"
                    placeholder="---"
                    :required="true"
                    :disabled="validacionDisabled"
                    :error="errors.comercial?.tinta_vuelta_interiores"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'tinta_vuelta_interiores') || camposQueCambiaron.includes('tinta_vuelta_interiores')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-select>
            </div>

            {{--  medida final interiores  --}}
            <div>
                <form-input
                    label="Medida Final (cm)"
                    name="medida_final_interiores"
                    v-model="comercial.medida_final_interiores"
                    placeholder="---"
                    :required="true"
                    :disabled="validacionDisabled"
                    :error="errors.comercial?.medida_final_interiores"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'medida_final_interiores') || camposQueCambiaron.includes('medida_final_interiores')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-input>
            </div>

            {{--  medida extendida interiores  --}}
            <div>
                <form-input
                    label="Medida Extendida (cm)"
                    name="medida_extendida_interiores"
                    v-model="comercial.medida_extendida_interiores"
                    placeholder="---"
                    :required="true"
                    :disabled="validacionDisabled"
                    :error="errors.comercial?.medida_extendida_interiores"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'medida_extendida_interiores') || camposQueCambiaron.includes('medida_extendida_interiores')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-input>
            </div>

            {{--  barniz frente interiores  --}}
            <div>
                <form-select
                    label="Barniz Frente"
                    name="barniz_frente_interiores"
                    v-model="comercial.barniz_frente_interiores"
                    :options="comercialCatalogos.barnicesFrenteInteriores"
                    id-field="id"
                    value-field="descripcion"
                    placeholder="---"
                    :required="true"
                    :disabled="validacionDisabled"
                    :error="errors.comercial?.barniz_frente_interiores"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'barniz_frente_interiores') || camposQueCambiaron.includes('barniz_frente_interiores')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-select>
            </div>

            {{--  barniz vuelta interiores  --}}
            <div>
                <form-select
                    label="Barniz Vuelta"
                    name="barniz_vuelta_interiores"
                    v-model="comercial.barniz_vuelta_interiores"
                    :options="comercialCatalogos.barnicesVueltaInteriores"
                    id-field="id"
                    value-field="descripcion"
                    placeholder="---"
                    :required="true"
                    :disabled="validacionDisabled"
                    :error="errors.comercial?.barniz_vuelta_interiores"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'barniz_vuelta_interiores') || camposQueCambiaron.includes('barniz_vuelta_interiores')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-select>
            </div>
        </div>
        
        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4 border-r border-gray-300 pr-3">
            <div class="text-base font-bold text-gray-700 uppercase p-1 md:col-span-2 flex items-center justify-between">
                <h3 class="text-cyan-600">forro</h3>

                <div class="flex space-x-2 items-center">
                    <label for="naEditorialForro" class="block text-gray-700 text-sm font-bold !mb-0">
                        N/A
                    </label>
                    <input
                        type="checkbox"
                        v-model="naEditorialForro"
                        :class="[
                            'border border-gray-300 rounded h-auto text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 focus-next'
                        ]"
                        id="naEditorialForro"
                        name="naEditorialForro"
                        :disabled="validacionDisabled"
                    >
                </div>
            </div>
            {{--  No páginas  --}}
            <div>
                <form-input
                    label="No. Páginas"
                    name="num_paginas_forro"
                    v-model="comercial.num_paginas_forro"
                    placeholder="---"
                    type="text"
                    :required="true"
                    :disabled="(naEditorialForro || validacionDisabled) ? true : false"
                    :error="errors.comercial?.num_paginas_forro"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'num_paginas_forro') || camposQueCambiaron.includes('num_paginas_forro')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-input>
            </div>

            {{--  sustrato forro  --}}
            <div>
                <form-select
                    label="Sustrato"
                    name="sustrato_forro"
                    v-model="comercial.sustrato_forro"
                    :options="comercialCatalogos.sustratosForro"
                    id-field="id"
                    value-field="descripcion"
                    placeholder="---"
                    :required="true"
                    :disabled="(naEditorialForro || validacionDisabled) ? true : false"
                    :error="errors.comercial?.sustrato_forro"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'sustrato_forro') || camposQueCambiaron.includes('sustrato_forro')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-select>
            </div>

            {{--  gramaje forro  --}}
            {{--  <div>
                <form-select
                    label="Gramaje"
                    name="gramaje_forro"
                    v-model="comercial.gramaje_forro"
                    :options="comercialCatalogos.gramajesForro"
                    id-field="id"
                    value-field="descripcion"
                    placeholder="---"
                    :required="true"
                    :disabled="(naEditorialForro || validacionDisabled) ? true : false"
                    :error="errors.comercial?.gramaje_forro"
                ></form-select>
            </div>  --}}

            {{--  tinta frente forro  --}}
            <div>
                <form-select
                    label="Tinta Frente"
                    name="tinta_frente_forro"
                    v-model="comercial.tinta_frente_forro"
                    :options="comercialCatalogos.tintasFrentesForro"
                    id-field="id"
                    value-field="descripcion"
                    placeholder="---"
                    :required="true"
                    :disabled="(naEditorialForro || validacionDisabled) ? true : false"
                    :error="errors.comercial?.tinta_frente_forro"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'tinta_frente_forro') || camposQueCambiaron.includes('tinta_frente_forro')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-select>
            </div>

            {{--  tinta vuelta forro  --}}
            <div>
                <form-select
                    label="Tinta Vuelta"
                    name="tinta_vuelta_forro"
                    v-model="comercial.tinta_vuelta_forro"
                    :options="comercialCatalogos.tintasVueltaForro"
                    id-field="id"
                    value-field="descripcion"
                    placeholder="---"
                    :required="true"
                    :disabled="(naEditorialForro || validacionDisabled) ? true : false"
                    :error="errors.comercial?.tinta_vuelta_forro"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'tinta_vuelta_forro') || camposQueCambiaron.includes('tinta_vuelta_forro')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-select>
            </div>

            {{--  medida final forro  --}}
            <div>
                <form-input
                    label="Medida Final (cm)"
                    name="medida_final_forro"
                    v-model="comercial.medida_final_forro"
                    placeholder="---"
                    type="text"
                    :required="true"
                    :disabled="(naEditorialForro || validacionDisabled) ? true : false"
                    :error="errors.comercial?.medida_final_forro"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'medida_final_forro') || camposQueCambiaron.includes('medida_final_forro')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-input>
            </div>

            {{--  medida extendida forro  --}}
            <div>
                <form-input
                    label="Medida Extendida (cm)"
                    name="medida_extendida_forro"
                    v-model="comercial.medida_extendida_forro"
                    placeholder="---"
                    type="text"
                    :required="true"
                    :disabled="(naEditorialForro || validacionDisabled) ? true : false"
                    :error="errors.comercial?.medida_extendida_forro"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'medida_extendida_forro') || camposQueCambiaron.includes('medida_extendida_forro')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-input>
            </div>

            {{--  barniz frente forro  --}}
            <div>
                <form-select
                    label="Barniz Frente"
                    name="barniz_frente_forro"
                    v-model="comercial.barniz_frente_forro"
                    :options="comercialCatalogos.barnicesFrenteForro"
                    id-field="id"
                    value-field="descripcion"
                    placeholder="---"
                    :required="true"
                    :disabled="(naEditorialForro || validacionDisabled) ? true : false"
                    :error="errors.comercial?.barniz_frente_forro"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'barniz_frente_forro') || camposQueCambiaron.includes('barniz_frente_forro')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-select>
            </div>

            {{--  barniz vuelta forro  --}}
            <div>
                <form-select
                    label="Barniz Vuelta"
                    name="barniz_vuelta_forro"
                    v-model="comercial.barniz_vuelta_forro"
                    :options="comercialCatalogos.barnicesVueltaForro"
                    id-field="id"
                    value-field="descripcion"
                    placeholder="---"
                    :required="true"
                    :disabled="(naEditorialForro || validacionDisabled) ? true : false"
                    :error="errors.comercial?.barniz_vuelta_forro"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'barniz_vuelta_forro') || camposQueCambiaron.includes('barniz_vuelta_forro')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-select>
            </div>
        </div>

        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="text-base font-bold text-gray-700 uppercase p-1 md:col-span-2 flex items-center justify-between">
                <h3 class="text-cyan-600">guardas</h3>

                <div class="flex space-x-2 items-center">
                    <label for="naEditorialGuarda" class="block text-gray-700 text-sm font-bold !mb-0">
                        N/A
                    </label>
                    <input
                        type="checkbox"
                        v-model="naEditorialGuarda"
                        :class="[
                            'border border-gray-300 rounded h-auto text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 focus-next'
                        ]"
                        id="naEditorialGuarda"
                        name="naEditorialGuarda"
                        :disabled="validacionDisabled"
                    >
                </div>
            </div>
            {{--  No páginas guardas  --}}
            <div>
                <form-input
                    label="No. Páginas"
                    name="num_paginas_guardas"
                    v-model="comercial.num_paginas_guardas"
                    placeholder="---"
                    type="text"
                    :required="true"
                    :disabled="(naEditorialGuarda || validacionDisabled) ? true : false"
                    :error="errors.comercial?.num_paginas_guardas"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'num_paginas_guardas') || camposQueCambiaron.includes('num_paginas_guardas')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-input>
            </div>

            {{--  sustrato guardas  --}}
            <div>
                <form-select
                    label="Sustrato"
                    name="sustrato_guardas"
                    v-model="comercial.sustrato_guardas"
                    :options="comercialCatalogos.sustratosGuardas"
                    id-field="id"
                    value-field="descripcion"
                    placeholder="---"
                    :required="true"
                    :disabled="(naEditorialGuarda || validacionDisabled) ? true : false"
                    :error="errors.comercial?.sustrato_guardas"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'sustrato_guardas') || camposQueCambiaron.includes('sustrato_guardas')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-select>
            </div>

            {{--  gramaje guardas  --}}
            {{--  <div>
                <form-select
                    label="Gramaje"
                    name="gramaje_guardas"
                    v-model="comercial.gramaje_guardas"
                    :options="comercialCatalogos.gramajesGuardas"
                    id-field="id"
                    value-field="descripcion"
                    placeholder="---"
                    :required="true"
                    :disabled="(naEditorialGuarda || validacionDisabled) ? true : false"
                    :error="errors.comercial?.gramaje_guardas"
                ></form-select>
            </div>  --}}

            {{--  tinta frente guardas  --}}
            <div>
                <form-select
                    label="Tinta Frente"
                    name="tinta_frente_guardas"
                    v-model="comercial.tinta_frente_guardas"
                    :options="comercialCatalogos.tintasFrentesGuardas"
                    id-field="id"
                    value-field="descripcion"
                    placeholder="---"
                    :required="true"
                    :disabled="(naEditorialGuarda || validacionDisabled) ? true : false"
                    :error="errors.comercial?.tinta_frente_guardas"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'tinta_frente_guardas') || camposQueCambiaron.includes('tinta_frente_guardas')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-select>
            </div>

            {{--  tinta vuelta guardas  --}}
            <div>
                <form-select
                    label="Tinta Vuelta"
                    name="tinta_vuelta_guardas"
                    v-model="comercial.tinta_vuelta_guardas"
                    :options="comercialCatalogos.tintasVueltaGuardas"
                    id-field="id"
                    value-field="descripcion"
                    placeholder="---"
                    :required="true"
                    :disabled="(naEditorialGuarda || validacionDisabled) ? true : false"
                    :error="errors.comercial?.tinta_vuelta_guardas"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'tinta_vuelta_guardas') || camposQueCambiaron.includes('tinta_vuelta_guardas')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-select>
            </div>

            {{--  medida final guardas  --}}
            <div>
                <form-input
                    label="Medida Final (cm)"
                    name="medida_final_guardas"
                    v-model="comercial.medida_final_guardas"
                    placeholder="---"
                    type="text"
                    :required="true"
                    :disabled="(naEditorialGuarda || validacionDisabled) ? true : false"
                    :error="errors.comercial?.medida_final_guardas"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'medida_final_guardas') || camposQueCambiaron.includes('medida_final_guardas')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-input>
            </div>

            {{--  medida extendida guardas  --}}
            <div>
                <form-input
                    label="Medida Extendida (cm)"
                    name="medida_extendida_guardas"
                    v-model="comercial.medida_extendida_guardas"
                    placeholder="---"
                    type="text"
                    :required="true"
                    :disabled="(naEditorialGuarda || validacionDisabled) ? true : false"
                    :error="errors.comercial?.medida_extendida_guardas"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'medida_extendida_guardas') || camposQueCambiaron.includes('medida_extendida_guardas')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-input>
            </div>

            {{--  barniz frente guardas  --}}
            <div>
                <form-select
                    label="Barniz Frente"
                    name="barniz_frente_guardas"
                    v-model="comercial.barniz_frente_guardas"
                    :options="comercialCatalogos.barnicesFrenteGuardas"
                    id-field="id"
                    value-field="descripcion"
                    placeholder="---"
                    :required="true"
                    :disabled="(naEditorialGuarda || validacionDisabled) ? true : false"
                    :error="errors.comercial?.barniz_frente_guardas"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'barniz_frente_guardas') || camposQueCambiaron.includes('barniz_frente_guardas')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-select>
            </div>

            {{--  barniz vuelta guardas  --}}
            <div>
                <form-select
                    label="Barniz Vuelta"
                    name="barniz_vuelta_guardas"
                    v-model="comercial.barniz_vuelta_guardas"
                    :options="comercialCatalogos.barnicesVueltaGuardas"
                    id-field="id"
                    value-field="descripcion"
                    placeholder="---"
                    :required="true"
                    :disabled="(naEditorialGuarda || validacionDisabled) ? true : false"
                    :error="errors.comercial?.barniz_vuelta_guardas"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'barniz_vuelta_guardas') || camposQueCambiaron.includes('barniz_vuelta_guardas')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-select>
            </div>
        </div>
    </div>

    <div class="text-lg font-bold text-gray-700 uppercase py-2 px-1">
        <h3 class="text-sky-600">acabados</h3>
    </div>

    <div class="grid sm:grid-cols-6 lg:grid-cols-6 gap-4 px-1 border border-gray-300 rounded-lg p-2">
        <div class="md:col-span-3 grid grid-cols-1 lg:grid-cols-3 gap-4 border-r border-gray-300 pr-3">
            <div class="text-base font-bold text-gray-700 uppercase p-1 lg:col-span-3 flex items-center justify-between">
                <h3 class="text-cyan-600">interiores</h3>
            </div>
            {{--  Metalizado frente interiores  --}}
            <div>
                <form-select
                    label="Metalizado Frente"
                    name="metalizado_frente_interiores"
                    v-model="comercial.metalizado_frente_interiores"
                    id-field="id"
                    value-field="descripcion"
                    placeholder="---"
                    :options="comercialCatalogos.metalizadosFrentesInteriores"
                    :required="true"
                    :disabled="validacionDisabled"
                    :error="errors.comercial?.metalizado_frente_interiores"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'metalizado_frente_interiores') || camposQueCambiaron.includes('metalizado_frente_interiores')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-select>
            </div>

            {{--  Metalizado vuelta interiores  --}}
            <div>
                <form-select
                    label="Metalizado Vuelta"
                    name="metalizado_vuelta_interiores"
                    v-model="comercial.metalizado_vuelta_interiores"
                    id-field="id"
                    value-field="descripcion"
                    placeholder="---"
                    :options="comercialCatalogos.metalizadosVueltaInteriores"
                    :required="true"
                    :disabled="validacionDisabled"
                    :error="errors.comercial?.metalizado_vuelta_interiores"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'metalizado_vuelta_interiores') || camposQueCambiaron.includes('metalizado_vuelta_interiores')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-select>
            </div>

            {{--  Laminado frente interiores  --}}
            <div>
                <form-select
                    label="Laminado Frente"
                    name="laminado_frente_interiores"
                    v-model="comercial.laminado_frente_interiores"
                    id-field="id"
                    value-field="descripcion"
                    placeholder="---"
                    :options="comercialCatalogos.laminadosFrenteInteriores"
                    :required="true"
                    :disabled="validacionDisabled"
                    :error="errors.comercial?.laminado_frente_interiores"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'laminado_frente_interiores') || camposQueCambiaron.includes('laminado_frente_interiores')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-select>
            </div>

            {{--  Laminado vuelta interiores  --}}
            <div>
                <form-select
                    label="Laminado Vuelta"
                    name="laminado_vuelta_interiores"
                    v-model="comercial.laminado_vuelta_interiores"
                    id-field="id"
                    value-field="descripcion"
                    placeholder="---"
                    :options="comercialCatalogos.laminadosVueltaInteriores"
                    :required="true"
                    :disabled="validacionDisabled"
                    :error="errors.comercial?.laminado_vuelta_interiores"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'laminado_vuelta_interiores') || camposQueCambiaron.includes('laminado_vuelta_interiores')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-select>
            </div>

            {{--  Barniz sakurai frente interiores  --}}
            <div>
                <form-select
                    label="Barniz Sakurai Frente"
                    name="barniz_sakurai_frente_interiores"
                    v-model="comercial.barniz_sakurai_frente_interiores"
                    id-field="id"
                    value-field="descripcion"
                    placeholder="---"
                    :options="comercialCatalogos.barnicesSakuraiFrenteInteriores"
                    :required="true"
                    :disabled="validacionDisabled"
                    :error="errors.comercial?.barniz_sakurai_frente_interiores"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'barniz_sakurai_frente_interiores') || camposQueCambiaron.includes('barniz_sakurai_frente_interiores')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-select>
            </div>

            {{--  Barniz sakurai vuelta interiores  --}}
            <div>
                <form-select
                    label="Barniz Sakurai Vuelta"
                    name="barniz_sakurai_vuelta_interiores"
                    v-model="comercial.barniz_sakurai_vuelta_interiores"
                    id-field="id"
                    value-field="descripcion"
                    placeholder="---"
                    :options="comercialCatalogos.barnicesSakuraiVueltaInteriores"
                    :required="true"
                    :disabled="validacionDisabled"
                    :error="errors.comercial?.barniz_sakurai_vuelta_interiores"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'barniz_sakurai_vuelta_interiores') || camposQueCambiaron.includes('barniz_sakurai_vuelta_interiores')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-select>
            </div>

            {{--  Doblez interiores  --}}
            <div>
                <form-select
                    label="Doblez"
                    name="doblez"
                    v-model="comercial.doblez"
                    id-field="id"
                    value-field="descripcion"
                    placeholder="---"
                    :options="comercialCatalogos.doblecesInteriores"
                    :required="true"
                    :disabled="validacionDisabled"
                    :error="errors.comercial?.doblez"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'doblez') || camposQueCambiaron.includes('doblez')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-select>
            </div>

            {{--  Embolsado/Retractilado  --}}
            <div>
                <form-select
                    label="Embolsado/Retractilado"
                    name="embolsado_retractilado"
                    v-model="comercial.embolsado_retractilado"
                    id-field="id"
                    value-field="descripcion"
                    placeholder="---"
                    :options="comercialCatalogos.embolsadosRetractiladosInteriores"
                    :required="true"
                    :disabled="validacionDisabled"
                    :error="errors.comercial?.embolsado_retractilado"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'embolsado_retractilado') || camposQueCambiaron.includes('embolsado_retractilado')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-select>
            </div>

            {{--  Perforado  --}}
            <div>
                <form-select
                    label="Perforado"
                    name="perforado"
                    v-model="comercial.perforado"
                    id-field="id"
                    value-field="descripcion"
                    placeholder="---"
                    :options="comercialCatalogos.perforadosInteriores"
                    :required="true"
                    :disabled="validacionDisabled"
                    :error="errors.comercial?.perforado"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'perforado') || camposQueCambiaron.includes('perforado')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-select>
            </div>

            {{--  Suaje  --}}
            <div>
                <form-select
                    label="Suaje"
                    name="suaje_interiores"
                    v-model="comercial.suaje_interiores"
                    id-field="id"
                    value-field="descripcion"
                    placeholder="---"
                    :options="comercialCatalogos.suajesInteriores"
                    :required="true"
                    :disabled="validacionDisabled"
                    :error="errors.comercial?.suaje_interiores"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'suaje_interiores') || camposQueCambiaron.includes('suaje_interiores')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-select>
            </div>

            {{--  Suajado  --}}
            <div>
                <form-select
                    label="Suajado"
                    name="suajado"
                    v-model="comercial.suajado"
                    id-field="id"
                    value-field="descripcion"
                    placeholder="---"
                    :options="comercialCatalogos.suajadosList"
                    :required="true"
                    :disabled="validacionDisabled"
                    :error="errors.comercial?.suajado"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'suajado') || camposQueCambiaron.includes('suajado')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-select>
            </div>

            {{--  Otros interiores  --}}
            <div>
                <form-select
                    label="Otros"
                    name="otros_interiores"
                    v-model="comercial.otros_interiores"
                    id-field="id"
                    value-field="descripcion"
                    placeholder="---"
                    :options="comercialCatalogos.otrosInteriores"
                    :required="true"
                    :disabled="validacionDisabled"
                    :error="errors.comercial?.otros_interiores"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'otros_interiores') || camposQueCambiaron.includes('otros_interiores')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-select>
            </div>
        </div>
        
        <div class="md:col-span-3 grid grid-cols-1 lg:grid-cols-3 gap-4 border-r border-gray-300 pr-3">
            <div class="text-base font-bold text-gray-700 uppercase p-1 lg:col-span-3 flex items-center justify-between">
                <h3 class="text-cyan-600">forro</h3>

                <div class="flex space-x-2 items-center">
                    <label for="naAcabadoForro" class="block text-gray-700 text-sm font-bold !mb-0">
                        N/A
                    </label>
                    <input
                        type="checkbox"
                        v-model="naAcabadoForro"
                        :class="[
                            'border border-gray-300 rounded h-auto text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 focus-next'
                        ]"
                        id="naAcabadoForro"
                        name="naAcabadoForro"
                        :disabled="validacionDisabled"
                    >
                </div>
            </div>
            {{--  Metalizado frente forro  --}}
            <div>
                <form-select
                    label="Metalizado Frente"
                    name="metalizado_frente_forro"
                    v-model="comercial.metalizado_frente_forro"
                    id-field="id"
                    value-field="descripcion"
                    placeholder="---"
                    :options="comercialCatalogos.metalizadosFrentesForro"
                    :required="true"
                    :disabled="(naAcabadoForro || validacionDisabled) ? true : false"
                    :error="errors.comercial?.metalizado_frente_forro"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'metalizado_frente_forro') || camposQueCambiaron.includes('metalizado_frente_forro')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-select>
            </div>

            {{--  Metalizado vuelta forro  --}}
            <div>
                <form-select
                    label="Metalizado Vuelta"
                    name="metalizado_vuelta_forro"
                    v-model="comercial.metalizado_vuelta_forro"
                    id-field="id"
                    value-field="descripcion"
                    placeholder="---"
                    :options="comercialCatalogos.metalizadosVueltaForro"
                    :required="true"
                    :disabled="(naAcabadoForro || validacionDisabled) ? true : false"
                    :error="errors.comercial?.metalizado_vuelta_forro"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'metalizado_vuelta_forro') || camposQueCambiaron.includes('metalizado_vuelta_forro')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-select>
            </div>

            {{--  Laminado frente forro  --}}
            <div>
                <form-select
                    label="Laminado Frente"
                    name="laminado_frente_forro"
                    v-model="comercial.laminado_frente_forro"
                    id-field="id"
                    value-field="descripcion"
                    placeholder="---"
                    :options="comercialCatalogos.laminadosFrenteForro"
                    :required="true"
                    :disabled="(naAcabadoForro || validacionDisabled) ? true : false"
                    :error="errors.comercial?.laminado_frente_forro"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'laminado_frente_forro') || camposQueCambiaron.includes('laminado_frente_forro')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-select>
            </div>

            {{--  Laminado vuelta forro  --}}
            <div>
                <form-select
                    label="Laminado Vuelta"
                    name="laminado_vuelta_forro"
                    v-model="comercial.laminado_vuelta_forro"
                    id-field="id"
                    value-field="descripcion"
                    placeholder="---"
                    :options="comercialCatalogos.laminadosVueltaForro"
                    :required="true"
                    :disabled="(naAcabadoForro || validacionDisabled) ? true : false"
                    :error="errors.comercial?.laminado_vuelta_forro"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'laminado_vuelta_forro') || camposQueCambiaron.includes('laminado_vuelta_forro')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-select>
            </div>

            {{--  Barniz sakurai frente forro  --}}
            <div>
                <form-select
                    label="Barniz Sakurai Frente"
                    name="barniz_sakurai_frente_forro"
                    v-model="comercial.barniz_sakurai_frente_forro"
                    id-field="id"
                    value-field="descripcion"
                    placeholder="---"
                    :options="comercialCatalogos.barnicesSakuraiFrenteForro"
                    :required="true"
                    :disabled="(naAcabadoForro || validacionDisabled) ? true : false"
                    :error="errors.comercial?.barniz_sakurai_frente_forro"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'barniz_sakurai_frente_forro') || camposQueCambiaron.includes('barniz_sakurai_frente_forro')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-select>
            </div>

            {{--  Barniz sakurai vuelta forro  --}}
            <div>
                <form-select
                    label="Barniz Sakurai Vuelta"
                    name="barniz_sakurai_vuelta_forro"
                    v-model="comercial.barniz_sakurai_vuelta_forro"
                    id-field="id"
                    value-field="descripcion"
                    placeholder="---"
                    :options="comercialCatalogos.barnicesSakuraiVueltaForro"
                    :required="true"
                    :disabled="(naAcabadoForro || validacionDisabled) ? true : false"
                    :error="errors.comercial?.barniz_sakurai_vuelta_forro"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'barniz_sakurai_vuelta_forro') || camposQueCambiaron.includes('barniz_sakurai_vuelta_forro')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-select>
            </div>

            {{--  Realce forro  --}}
            <div>
                <form-select
                    label="Realce"
                    name="realce_forro"
                    v-model="comercial.realce_forro"
                    id-field="id"
                    value-field="descripcion"
                    placeholder="---"
                    :options="comercialCatalogos.realcesForro"
                    :required="true"
                    :disabled="(naAcabadoForro || validacionDisabled) ? true : false"
                    :error="errors.comercial?.realce_forro"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'realce_forro') || camposQueCambiaron.includes('realce_forro')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-select>
            </div>

            {{--  Placa de Realce (Medida cm LxA) forro  --}}
            <div>
                <form-input
                    label="Placa de Realce (Medida cm LxA)"
                    name="placa_realce_forro"
                    v-model="comercial.placa_realce_forro"
                    placeholder="---"
                    type="text"
                    :required="comercial.realce_forro != 288 && comercial.realce_forro != '' ? true : false"
                    :disabled="comercial.realce_forro != 288 && comercial.realce_forro != '' && !(naAcabadoForro || validacionDisabled) ? false : true"
                    :error="errors.comercial?.placa_realce_forro"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'placa_realce_forro') || camposQueCambiaron.includes('placa_realce_forro')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-input>
            </div>

            {{--  Hot stamping forro  --}}
            <div>
                <form-select
                    label="Hot Stamping"
                    name="hot_stamping_forro"
                    v-model="comercial.hot_stamping_forro"
                    id-field="id"
                    value-field="descripcion"
                    placeholder="---"
                    :options="comercialCatalogos.hotStampingsForro"
                    :required="true"
                    :disabled="(naAcabadoForro || validacionDisabled) ? true : false"
                    :error="errors.comercial?.hot_stamping_forro"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'hot_stamping_forro') || camposQueCambiaron.includes('hot_stamping_forro')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-select>
            </div>

            {{--  Placa Hot Stamping (Medida cm LxA) forro  --}}
            <div>
                <form-input
                    label="Placa Hot Stamping (Medida cm LxA)"
                    name="placa_hot_stamping_forro"
                    v-model="comercial.placa_hot_stamping_forro"
                    placeholder="---"
                    type="text"
                    :required="comercial.hot_stamping_forro != 288 && comercial.hot_stamping_forro != '' ? true : false"
                    :disabled="comercial.hot_stamping_forro != 288 && comercial.hot_stamping_forro != '' && !(naAcabadoForro || validacionDisabled) ? false : true"
                    :error="errors.comercial?.placa_hot_stamping_forro"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'placa_hot_stamping_forro') || camposQueCambiaron.includes('placa_hot_stamping_forro')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-input>
            </div>

            {{--  Color hot stamping forro  --}}
            <div>
                <form-select
                    label="Color Hot Stamping"
                    name="color_hot_stamping_forro"
                    v-model="comercial.color_hot_stamping_forro"
                    id-field="id"
                    value-field="descripcion"
                    placeholder="---"
                    :options="comercialCatalogos.coloresHotStampingForro"
                    :required="true"
                    :disabled="(naAcabadoForro || validacionDisabled) ? true : false"
                    :error="errors.comercial?.color_hot_stamping_forro"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'color_hot_stamping_forro') || camposQueCambiaron.includes('color_hot_stamping_forro')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-select>
            </div>

            {{--  Suaje forro  --}}
            <div>
                <form-select
                    label="Suaje"
                    name="suaje_forro"
                    v-model="comercial.suaje_forro"
                    id-field="id"
                    value-field="descripcion"
                    placeholder="---"
                    :options="comercialCatalogos.suajesForro"
                    :required="true"
                    :disabled="(naAcabadoForro || validacionDisabled) ? true : false"
                    :error="errors.comercial?.suaje_forro"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'suaje_forro') || camposQueCambiaron.includes('suaje_forro')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-select>
            </div>

            {{--  Suajado forro --}}
            <div>
                <form-select
                    label="Suajado"
                    name="suajado_forro"
                    v-model="comercial.suajado_forro"
                    id-field="id"
                    value-field="descripcion"
                    placeholder="---"
                    :options="comercialCatalogos.suajadosList"
                    :required="true"
                    :disabled="(naAcabadoForro || validacionDisabled) ? true : false"
                    :error="errors.comercial?.suajado_forro"
                    :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'suajado_forro') || camposQueCambiaron.includes('suajado_forro')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                ></form-select>
            </div>
        </div>
    </div>

    <div class="text-lg font-bold text-gray-700 uppercase py-2 px-1">
        <h3 class="text-sky-600">adicionales</h3>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 px-1 border border-gray-300 rounded-lg p-2">
        {{--  Encuadernado  --}}
        <div class="md:col-span-3">
            <form-select
                label="Encuadernado"
                name="encuadernado"
                v-model="comercial.encuadernado"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="comercialCatalogos.encuadernados"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.comercial?.encuadernado"
                :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'encuadernado') || camposQueCambiaron.includes('encuadernado')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Empaque  --}}
        <div class="md:col-span-3">
            <form-select
                label="Empaque"
                name="empaque"
                v-model="comercial.empaque"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="comercialCatalogos.empaques"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.comercial?.empaque"
                :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'empaque') || camposQueCambiaron.includes('empaque')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Entregas  --}}
        <div class="md:col-span-3">
            <form-select
                label="Entregas"
                name="entrega"
                v-model="comercial.entrega"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="comercialCatalogos.entregas"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.comercial?.entrega"
                :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'entrega') || camposQueCambiaron.includes('entrega')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Lugar de Entrega  --}}
        <div class="md:col-span-3">
            <form-select
                label="Lugar de Entrega"
                name="lugar_entrega"
                v-model="comercial.lugar_entrega"
                id-field="id"
                value-field="descripcion"
                placeholder="---"
                :options="comercialCatalogos.lugarEntregas"
                :required="true"
                :disabled="validacionDisabled"
                :error="errors.comercial?.lugar_entrega"
                :is-modified="(bitacoraCambiosComercial.some(c => c.campo === 'lugar_entrega') || camposQueCambiaron.includes('lugar_entrega')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
            ></form-select>
        </div>

        {{--  Observaciones  --}}
        <div class="md:col-span-12" title="Estas observaciones serán visibles únicamente para el cotizador y sirven como complemento para aquellos campos en los que se haya seleccionado la opción 'Otro (En observaciones)'">
            <form-textarea
                label="Observaciones (Para cotizador)"
                name="observaciones"
                v-model="comercial.observaciones"
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
                :is-modified="(this.estatusOportunidad == 7 || this.estatusOportunidad == 8) && this.tipoForm == 2 && this.esParaCambioTexto"
            ></form-textarea>
        </div>

        <div class="md:col-span-12 text-sm" v-if="(estatusEnQuePuedeActualizarCS.includes(parseInt(estatusOportunidad)) || comercial.observacionesExtras.length > 0) && tipoForm == 2" title="Estos comentarios son visibles para el equipo de Customers y se utilizan para comunicar información adicional relevante sobre el elemento que no se haya cubierto en los campos anteriores, ademas de que se considera como cambio minimo al agregar al menos 1.">
            <label class="block text-gray-700 font-bold mb-1">Comentarios adicionales</label>
            <div v-for="(comentario, idx) in comercial.observacionesExtras" :key="idx" class="flex items-center mb-2">
                <textarea
                    v-model="comercial.observacionesExtras[idx]"
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