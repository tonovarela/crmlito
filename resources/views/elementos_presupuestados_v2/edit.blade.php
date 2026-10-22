@extends('layout.master-tailwind')
@section('title', 'Elementos presupuestados')
@section('titleSection', 'Solicitud de Cotización')

@section('content')
    <div>
        <script>
            window.solicitud = @json($solicitud);
            window.generalOriginal = @json($solicitud);
        </script>

        <div id="formElementosEdit" class="bg-gray-100 overflow-y-auto lg:overflow-y-hidden">
            <button class="fixed bottom-0 right-0 z-50 bg-white shadow-md items-center rounded-full w-12 h-12 flex justify-center m-2 text-blue-500 p-2" @click="clonarElemento(window.solicitud.solicitud.id, window.solicitud.solicitud.nombre_proyecto, window.solicitud.solicitud.folio)" v-if="(role == 1 || role == 8 || ((role == 4 || role == 9) && solicitud.solicitud.oportEstatus <= 2)) && fromOport != 3">
                {{--  Clonar elemento  --}}
                <img :src="`${baseUrl}/img/clon.png`" width="24">
            </button>

            <div class="w-[98%] mx-auto shadow-md rounded-lg p-1 text-base m-2 h-full bg-white relative">
                <div
                    class="w-full rounded-md py-2 px-4 text-white bg-gray uppercase font-semibold md:flex md:justify-between md:items-center space-y-2 md:space-y-0">
                    <p class="text-sm">Folio: <span class="font-bold">@{{ folioSolicitud }}</span></p>
                    <h3>Solicitud de cotización</h3>
                    <p>Fecha de creación: <span class="font-bold">@{{ fechaCreacion }}</span></p>
                </div>

                {{--  Formulario de solicitud (General)  --}}
                <form @submit.prevent="actualizar" class="p-2">
                    <div class="grid grid-cols-12 gap-4 p-2 border border-gray-300 rounded-lg mb-4 max-h-[50vh] md:max-h-full overflow-y-auto lg:overflow-y-hidden bg-gray-100" id="generalForm">
                        {{--  Acciones  --}}
                        <div class="col-span-12 md:col-span-2 w-full block md:row-end-1 md:col-start-11 md:col-end-13">
                            <div class="w-full flex flex-col md:flex-row gap-2 justify-center items-center h-full">
                                <button type="submit" class="w-full md:w-auto block btn btn-primary" v-if="estatusEnQuePuedeActualizar.includes(parseInt(estatusOportunidad)) && (role == 4 || role == 9 || role == 1) && !validacionDisabled" >
                                    Actualizar
                                </button>

                                <button class="w-full md:w-auto block btn btn-primary" type="button" @click="guardarCambios" v-else-if="role != 2 && role != 3 && role != 1013 && fromOport == 3">
                                    Guardar
                                </button>

                                <button class="w-full md:w-auto block btn btn-secondary" type="button" @click="regresarOportunidad">
                                    Regresar
                                </button>

                                <button class="w-full md:w-auto block btn btn-success" type="button" @click="generarPDF">
                                    <i class="fas fa-file-pdf"></i>
                                </button>

                                {{--  <button class="w-full md:w-auto block btn btn-secondary" type="button" @click="limpiarFormulario">
                                    Limpiar
                                </button>  --}}
                            </div>
                        </div>

                        {{--  Oportunidad folio  --}}
                        <div class="col-span-12 md:col-span-3">
                            <select-search
                                v-model="general.oportunidad_id"
                                :endpoint="`${baseUrl}/get-oportunidades-general`"
                                label="Oportunidad"
                                id-field="folio"
                                value-field="nomOportunidad"
                                id-store-field="idOportunidad"
                                placeholder="Ingresa el folio de la oportunidad..."
                                ref="filtroOportunidad"
                                is-concat-field="true"
                                :error="errors.general?.oportunidad_id"
                                :default-value="solicitud.solicitud.folio_oportunidad ? solicitud.solicitud.folio_oportunidad + ' - ' + solicitud.solicitud.oportunidad : ''"
                                :disabled="fromOport == 3 ? true : (isDisabled ?? false)"
                            ></select-search>
                        </div>

                        {{--  Vendedor  --}}
                        <div class="col-span-12 md:col-span-2">
                            <form-input
                                label="Vendedor"
                                name="vendedor"
                                type="text"
                                :required="true"
                                v-model="generalDetail.vendedor"
                                :disabled="true"
                                :error="errors.general?.vendedor_id">
                            </form-input>
                        </div>

                        {{--  Cliente  --}}
                        <div class="col-span-12 md:col-span-3">
                            <form-input
                                label="Cliente"
                                name="cliente"
                                type="text"
                                :required="true"
                                v-model="generalDetail.cliente"
                                :disabled="true"
                                :error="errors.general?.cliente_id">
                            </form-input>
                        </div>

                        {{--  Contacto  --}}
                        <div class="col-span-12 md:col-span-2">
                            <form-input
                                label="Contacto"
                                name="contacto"
                                type="text"
                                :required="true"
                                v-model="generalDetail.contacto"
                                :disabled="true"
                                :error="errors.general?.contacto_id">
                            </form-input>
                        </div>

                        {{--  Nombre del proyecto  --}}
                        <div class="col-span-12">
                            <form-input
                                label="Nombre del proyecto"
                                name="nombre_proyecto"
                                type="text"
                                :required="true"
                                v-model="general.nombre_proyecto"
                                :disabled="(fromOport == 3 || validacionDisabled) ? true : false"
                                :error="errors.general?.nombre_proyecto">
                            </form-input>
                        </div>

                        {{-- Cantidad a cotizar 1 --}}
                        <div class="col-span-12 md:col-span-2">
                            <div class="relative">
                                <form-input
                                    label="Cantidad a cotizar 1"
                                    name="cantidad_cotizar_1"
                                    type="number"
                                    step="1"
                                    :required="true"
                                    v-model="general.cantidad_cotizar_1"
                                    :disabled="(fromOport == 3 || validacionDisabled) ? true : false"
                                    :error="errors.general?.cantidad_cotizar_1"
                                    :is-modified="(bitacoraCambiosGeneral.some(c => c.campo === 'cantidad_cotizar_1') || camposQueCambiaron.includes('cantidad_cotizar_1')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                                ></form-input>
                            </div>
                        </div>

                        {{-- Cantidad a cotizar 2 --}}
                        <div class="col-span-12 md:col-span-2">
                            <form-input
                                label="Cantidad a cotizar 2"
                                name="cantidad_cotizar_2"
                                type="number"
                                step="1"
                                :required="false"
                                v-model="general.cantidad_cotizar_2"
                                :disabled="(fromOport == 3 || validacionDisabled) ? true : false"
                                :error="errors.general?.cantidad_cotizar_2"
                                :is-modified="(bitacoraCambiosGeneral.some(c => c.campo === 'cantidad_cotizar_2') || camposQueCambiaron.includes('cantidad_cotizar_2')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                            ></form-input>
                        </div>

                        {{-- Cantidad a cotizar 3 --}}
                        <div class="col-span-12 md:col-span-2">
                            <form-input
                                label="Cantidad a cotizar 3"
                                name="cantidad_cotizar_3"
                                type="number"
                                step="1"
                                :required="false"
                                v-model="general.cantidad_cotizar_3"
                                :disabled="(fromOport == 3 || validacionDisabled) ? true : false"
                                :error="errors.general?.cantidad_cotizar_3"
                                :is-modified="(bitacoraCambiosGeneral.some(c => c.campo === 'cantidad_cotizar_3') || camposQueCambiaron.includes('cantidad_cotizar_3')) && (estatusOportunidad != 2 && estatusOportunidad != 1)"
                            ></form-input>
                        </div>

                        {{--  Comisión Externa  --}}
                        <div class="col-span-12 md:col-span-2">
                            <form-input
                                label="Comisión Externa"
                                name="comision_externa"
                                type="number"
                                :required="false"
                                v-model="general.comision_externa"
                                :disabled="(fromOport == 3 || validacionDisabled) ? true : false"
                                :error="errors.general?.comision_externa">
                            </form-input>
                        </div>

                        {{-- Plantilla --}}
                        <div class="col-span-12 md:col-span-2 hidden">
                            <form-select-api
                                label="Plantilla"
                                name="plantilla"
                                v-model="general.plantilla_id"
                                id-field="id"
                                value-field="Descripcion"
                                placeholder="Selecciona una plantilla..."
                                endpoint="{{ url('/') }}/get-plantillas"
                                :required="true"
                                :disabled="false"
                                :error="errors.general?.plantilla_id"
                                value-concat-field=""
                            ></form-select-api>
                        </div>

                        {{--  Tipo de producto  --}}
                        <div class="col-span-12 md:col-span-4">
                            <form-select-api
                                label="Tipo de producto"
                                name="cat_EP_tipoProducto_id"
                                v-model="general.cat_EP_tipoProducto_id"
                                id-field="id"
                                value-field="descripcion"
                                placeholder="Selecciona un tipo de producto..."
                                endpoint="{{ url('/') }}/get-tipos-producto"
                                :required="true"
                                :disabled="true"
                                :error="errors.general?.cat_EP_tipoProducto_id"
                                value-concat-field="plantilla_id"
                            ></form-select-api>
                        </div>
                    </div>

                    <div class="grid grid-cols-12 gap-4 px-2 border border-gray-300 rounded-lg mb-4 bg-gray-100 max-h-[55vh] overflow-y-auto">
                        {{--  Formularios dinamicos  --}}
                        <div v-if="general.plantilla_id == 3" class="col-span-12">
                            @include('elementos_presupuestados_v2.formularios-dinamicos.display')
                        </div>
                        
                        <div v-if="general.plantilla_id == 4" class="col-span-12">
                            @include('elementos_presupuestados_v2.formularios-dinamicos.pop')
                        </div>

                        <div v-if="general.plantilla_id == 2" class="col-span-12">
                            @include('elementos_presupuestados_v2.formularios-dinamicos.empaque')
                        </div>
                        
                        <div v-if="general.plantilla_id == 6" class="col-span-12">
                            @include('elementos_presupuestados_v2.formularios-dinamicos.maquila')
                        </div>

                        <div v-if="general.plantilla_id == 1" class="col-span-12">
                            @include('elementos_presupuestados_v2.formularios-dinamicos.comercial')
                        </div>
                    </div>

                    <div class="px-2 border border-gray-300 rounded-lg mb-4 bg-gray-100 max-h-[55vh] overflow-y-auto" v-if="fromOport == 3">
                        <div>
                            <div class="text-lg font-bold text-gray-700 uppercase py-2 px-1">
                                <h3 class="text-sky-600">Cambio de especificaciones</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 px-1 border border-gray-300 rounded-lg p-2">
                                {{--  Cambios  --}}
                                <div class="md:col-span-12">
                                    <form-textarea
                                        label="Motivo del Cambio de Especificaciones"
                                        name="mensaje"
                                        v-model="mensaje"
                                        placeholder="Ingrese el motivo del cambio de especificaciones"
                                        :error="errors.mensaje"
                                        :required="true"
                                        :disabled="role == 2 ? true : false"
                                    ></form-textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{--  Bitacora de cambios  --}}
                    <div class="px-2 border border-gray-300 rounded-lg mb-4 bg-gray-100 max-h-[30vh] overflow-y-auto" v-if="bitacoraCambios.length > 0 && tipoForm == 2">
                        <div>
                            <div class="text-lg font-bold text-gray-700 uppercase py-2 px-1">
                                <h3 class="text-sky-600">Bitacora de cambios solicitados</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 px-1 border border-gray-300 rounded-lg p-2">
                                {{--  Cambios  --}}
                                <div class="md:col-span-12">
                                    <table class="table-auto w-full">
                                        <thead>
                                            <tr class="bg-gray-200">
                                                <th class="px-2 py-0.5 border">Campo</th>
                                                <th class="px-2 py-0.5 border">Valor Anterior</th>
                                                <th class="px-2 py-0.5 border">Valor Nuevo</th>
                                                <th class="px-2 py-0.5 border">Tipo de Cambio</th>
                                                <th class="px-2 py-0.5 border">Usuario realizó cambio</th>
                                                <th class="px-2 py-0.5 border">Fecha de Cambio</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(cambio, index) in bitacoraCambios" :key="index" class="hover:bg-gray-100">
                                                <td class="px-2 py-0.5 border">@{{ cambio.campoEtiqueta }}</td>
                                                <td class="px-2 py-0.5 border">@{{ cambio.valorAnterior }}</td>
                                                <td class="px-2 py-0.5 border">@{{ cambio.valorNuevo }}</td>
                                                <td class="px-2 py-0.5 border">@{{ cambio.tipo }}</td>
                                                <td class="px-2 py-0.5 border">@{{ cambio.usuario }}</td>
                                                <td class="px-2 py-0.5 border">@{{ formatoFecha(cambio.fechaRegistro) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            @component('components.home.loader', [
                'show' => 'loading',
                'message' => 'Buscando datos...',
            ])
            @endcomponent
        </div>
    </div>

    <script src="{{ asset('vue/elementos-presupuestados/EditElemento.js') }}" type="module"></script>
@endsection
