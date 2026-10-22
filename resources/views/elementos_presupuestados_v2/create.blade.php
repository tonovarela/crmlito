@extends('layout.master-tailwind')
@section('title', 'Elementos presupuestados')
@section('titleSection', 'Solicitud de Cotización')

@section('content')
    <div>
        <script>
            window.oportunidad = @json($oportunidad);
            window.regresaOport = @json($regresaOport);
            window.role = @json($idRol);
        </script>
        <div id="formElementos" class="bg-gray-100 overflow-y-auto lg:overflow-y-hidden">
            <div class="w-[98%] mx-auto shadow-md rounded-lg p-1 text-base m-2 h-full bg-white relative">
                <div
                    class="w-full rounded-md py-2 px-4 text-white bg-gray uppercase font-semibold md:flex md:justify-between md:items-center space-y-2 md:space-y-0">
                    <p class="text-sm">Folio: <span class="font-bold">{{ $folio }}</span></p>
                    <h3>Solicitud de cotización</h3>
                    <p>Fecha de creación: <span class="font-bold">{{ now()->format('d/m/Y H:i:s') }}</span></p>
                </div>

                {{--  Formulario de solicitud (General)  --}}
                <form @submit.prevent="guardar" class="p-2">
                    <div class="grid grid-cols-12 gap-4 p-2 border border-gray-300 rounded-lg mb-4 max-h-full md:max-h-full overflow-y-auto lg:overflow-y-hidden bg-gray-100">
                        {{--  Acciones  --}}
                        <div class="col-span-12 md:col-span-2 w-full block md:row-end-1 md:col-start-11 md:col-end-13">
                            <div class="w-full flex flex-col md:flex-row gap-2 justify-center items-center h-full">
                                <button type="submit" class="w-full md:w-auto block btn btn-primary">
                                    Guardar
                                </button>

                                <button class="w-full md:w-auto block btn btn-secondary" type="button" @click="regresar" v-if="!regresaOport">
                                    Regresar
                                </button>

                                <button class="w-full md:w-auto block btn btn-secondary" type="button" @click="regresarOportunidad" v-else="regresaOport == 1 || regresaOport == 2">
                                    Regresar
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
                                :is-concat-field="true"
                                :error="errors.general?.oportunidad_id"
                                :default-value="defaultOportunidad"
                                :disabled="regresaOport == 3 ? true : (isDisabled ?? false)"
                            ></select-search>
                        </div>

                        {{--  Vendedor  --}}
                        <div class="col-span-12 md:col-span-2">
                            <form-input
                                label="Vendedor"
                                name="vendedor"
                                type="text"
                                required
                                v-model="generalDetail.vendedor"
                                :disabled="true"
                                :error="errors.general?.vendedor_id"
                            ></form-input>
                        </div>

                        {{--  Cliente  --}}
                        <div class="col-span-12 md:col-span-3">
                            <form-input
                                label="Cliente"
                                name="cliente"
                                type="text"
                                required
                                v-model="generalDetail.cliente"
                                :disabled="true"
                                :error="errors.general?.cliente_id"
                            ></form-input>
                        </div>

                        {{--  Contacto  --}}
                        <div class="col-span-12 md:col-span-2">
                            <form-input
                                label="Contacto"
                                name="contacto"
                                type="text"
                                required
                                v-model="generalDetail.contacto"
                                :disabled="true"
                                :error="errors.general?.contacto_id"
                            ></form-input>
                        </div>

                        {{--  Nombre del proyecto  --}}
                        <div class="col-span-12">
                            <form-input
                                label="Nombre del proyecto"
                                name="nombre_proyecto"
                                type="text"
                                required
                                v-model="general.nombre_proyecto"
                                :disabled="false"
                                :error="errors.general?.nombre_proyecto"
                            ></form-input>
                        </div>

                        {{-- Cantidad a cotizar 1 --}}
                        <div class="col-span-12 md:col-span-2">
                            <form-input
                                label="Cantidad a cotizar 1"
                                name="cantidad_cotizar_1"
                                type="number"
                                step="1"
                                required
                                v-model="general.cantidad_cotizar_1"
                                :disabled="false"
                                :error="errors.general?.cantidad_cotizar_1"
                            ></form-input>
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
                                :disabled="false"
                                :error="errors.general?.cantidad_cotizar_2"
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
                                :disabled="false"
                                :error="errors.general?.cantidad_cotizar_3"
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
                                :disabled="false"
                                :error="errors.general?.comision_externa"
                            ></form-input>
                        </div>

                        {{-- Plantilla --}}
                        <div class="col-span-12 md:col-span-2 hidden">
                            <form-select-api
                                v-model="general.plantilla_id"
                                :endpoint="`${baseUrl}/get-plantillas`"
                                label="Plantilla"
                                id-field="id"
                                value-field="Descripcion"
                                placeholder="Selecciona una plantilla..."
                                :required="true"
                                :disabled="false"
                                :error="errors.general?.plantilla_id"
                                value-concat-field=""
                            ></form-select-api>
                        </div>

                        {{--  Tipo de producto  --}}
                        <div class="col-span-12 md:col-span-4">
                            <form-select-api
                                v-model="general.cat_EP_tipoProducto_id"
                                :endpoint="`${baseUrl}/get-tipos-producto`"
                                label="Tipo de producto"
                                id-field="id"
                                value-field="descripcion"
                                placeholder="Selecciona un tipo de producto..."
                                :required="true"
                                :disabled="false"
                                :error="errors.general?.cat_EP_tipoProducto_id"
                                value-concat-field="plantilla_id"
                            ></form-select-api>
                        </div>
                    </div>

                    <div class="grid grid-cols-12 gap-4 px-2 border border-gray-300 rounded-lg mb-4 max-h-[55vh] overflow-y-auto bg-gray-100">
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
                </form>
            </div>

            @component('components.home.loader', [
                'show' => 'loading',
                'message' => 'Buscando datos...',
            ])
            @endcomponent
        </div>
    </div>

    <script src="{{ asset('vue/elementos-presupuestados/CreateElementos.js') }}" type="module"></script>
@endsection
