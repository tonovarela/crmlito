@extends('layout.master-tailwind')
@section('title', 'Elementos Presupuestados')
@section('titleSection', 'Elementos Presupuestados')
@section('classBody', 'overflow-hidden')

@section('header-title')
    <div class="container-fluid">
        <div class="content-header">
            <div class="row mb-n2">
                <div class="col-12">
                    <h2 class="m-0 text-white text-3xl">
                        Elementos Presupuestados
                    </h2>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="w-[98%] mx-auto p-1 text-base m-2 h-full relative">
        <div id="elementosList" class="overflow-hidden w-full text-base px-2">
            <script>
                window.role = @json($role);
            </script>
            <div class="mb-4 flex items-center justify-between w-full">
                <div class="flex items-center space-x-4 w-[90%]">
                    <div class="w-full">
                        <filter-input
                            label="Buscar"
                            placeholder="Buscar..."
                            v-model="searchQuery"
                            @keyup.enter="search"
                        />
                    </div>
                </div>

                <div class="flex items-center space-x-2">
                    <button @click="search"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm transition duration-300">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>

                    <div v-if="searchQuery">
                        <button @click="clearFilters" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded text-sm transition duration-300">
                            <i class="fa-solid fa-filter-circle-xmark"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                @component('components.home.table.table')
                    @slot('thead')
                        <tr>
                            @component('components.home.table.th', ['title' => 'Folio', 'value' => 'folio', 'adicionalClasses' => 'text-center'])
                            @endcomponent

                            @component('components.home.table.th', ['title' => 'Proyecto', 'value' => 'nombre_proyecto'])
                            @endcomponent

                            @component('components.home.table.th', ['title' => 'Vendedor', 'value' => 'vendedor'])
                            @endcomponent

                            @component('components.home.table.th', ['title' => 'Categoria', 'value' => 'plantilla'])
                            @endcomponent

                            @component('components.home.table.th', ['title' => 'Tipo de producto', 'value' => 'tipo_producto', 'mainAdicionalClasses' => 'w-[200px]'])
                            @endcomponent

                            @component('components.home.table.th', ['title' => 'Folio Opt.', 'value' => 'folio_oportunidad', 'adicionalClasses' => 'text-center', 'mainAdicionalClasses' => 'w-[150px]'])
                            @endcomponent

                            @component('components.home.table.th', ['title' => 'Oportunidad', 'value' => 'oportunidad', 'adicionalClasses' => 'text-center'])
                            @endcomponent

                            @component('components.home.table.th', ['title' => 'Cliente', 'value' => 'cliente'])
                            @endcomponent

                            @component('components.home.table.th', ['title' => 'Pdf', 'value' => ''])
                            @endcomponent

                            @component('components.home.table.th', ['title' => 'Clonar', 'value' => ''])
                            @endcomponent

                            @component('components.home.table.th', ['title' => 'Creado', 'value' => 'fecha_registro'])
                            @endcomponent
                        </tr>
                    @endslot
                    @slot('tbody')
                        <tr v-for="elemento in elementos" :key="elemento.id" class="hover:bg-gray-50">
                            @component('components.home.table.td', ['value' => '@{{ elemento.folio }}', 'adicionalClasses' => 'text-center'])
                            @endcomponent
                            @component('components.home.table.td')
                                @slot('value')
                                    <a :href="`${baseUrl}/elementosp/${elemento.id}/edit`" class="text-blue-500 hover:underline" target="_blank">
                                        @{{ elemento.nombre_proyecto }}
                                    </a>
                                @endslot
                            @endcomponent
                            @component('components.home.table.td', ['value' => '@{{ elemento.vendedor }}'])
                            @endcomponent
                            @component('components.home.table.td', ['value' => '@{{ elemento.plantilla }}'])
                            @endcomponent
                            @component('components.home.table.td', ['value' => '@{{ elemento.tipo_producto }}'])
                            @endcomponent
                            @component('components.home.table.td', ['value' => '@{{ elemento.folio_oportunidad }}', 'adicionalClasses' => 'text-center'])
                            @endcomponent
                            @component('components.home.table.td', ['adicionalClasses' => 'text-center'])
                                @slot('value')
                                    <a :href="`${baseUrl}/oportunidades/${elemento.oportunidad_id}/edit`" class="text-blue-500 hover:underline text-center" target="_blank">
                                        @{{ elemento.oportunidad }}
                                    </a>
                                @endslot
                            @endcomponent
                            @component('components.home.table.td', ['value' => '@{{ elemento.cliente }}'])
                            @endcomponent

                            @component('components.home.table.td')
                                @slot('value')
                                    <a :href="`${baseUrl}/elementosp/generarPdfElemento/${elemento.id}/pdf`" class="text-blue-500 hover:underline" target="_blank">
                                        <i class="fa-solid fa-file-pdf"></i>
                                    </a>
                                @endslot
                            @endcomponent

                            @component('components.home.table.td', ['adicionalClasses' => 'text-center'])
                                @slot('value')
                                    <div class="w-full mx-auto" v-if="role != 1013">
                                        <button @click="clonarElemento(elemento.id, elemento.nombre_proyecto, elemento.folio)" class="text-blue-500 hover:underline text-center">
                                            <img :src="`${baseUrl}/img/clon.png`" width="20">
                                        </button>
                                    </div>
                                @endslot
                            @endcomponent

                            @component('components.home.table.td', ['value' => '@{{ new Date(elemento.fecha_registro).toLocaleDateString("es-MX", { year: "numeric", month: "2-digit", day: "2-digit", hour: "2-digit", minute: "2-digit", second: "2-digit" }) }}'])
                            @endcomponent
                        </tr>
                        <tr v-if="elementos.length === 0">
                            <td colspan="8" class="text-center text-base py-2">No hay elementos para mostrar</td>
                        </tr>
                    @endslot
                @endcomponent
            </div>

            <div>
                @component('components.home.table.pagination', [
                    'links' => 'paginationLinks',
                    'total' => 'totalElementos',
                ])
                @endcomponent
            </div>

            @component('components.home.loader', [
                'show' => 'loading',
                'message' => 'Buscando datos...',
            ])
            @endcomponent
        </div>

        {{--  @if (auth()->user()->canDo('Crear', 'Elementos Presupuestados'))
            @component('components.elementos-presupuestados.btn-create')
            @endcomponent
        @endif  --}}

        <script src="{{ asset('vue/elementos-presupuestados/Index.js') }}" type="module"></script>
    </div>
@endsection
