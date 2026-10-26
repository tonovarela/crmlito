@extends('layout.master') 

@section('content')
    @include('partials.loading')
    
    <div class="container-fluid">
        <div class="content-header">
            <div class="row mb-n2">
                <div class="col-6">
                    <h1>Administración de comisiones</h1>
                </div>
                <div class="col-sm-9 justify-content-md-center">
                    <input type="hidden" id="tblActual">
                    <input type="hidden" id="roleUser" value="{{ $tipoUsarioAccede }}">
                    <input type="hidden" id="activarSeleccion" value="0">
                </div>
            </div>
        </div>
    </div>

    <div class="mw-100">
        <div class="w-full row pt-4 pl-4">
            <div class="{{ $tipoUsarioAccede == 5 || $tipoUsarioAccede == 4  ? 'col-3' : 'col-4' }}">
                <button onclick="changeTab('tab-1')" id="tab1" class="tb-item-btn tb-item-active">Listado de comisiones</button>
            </div>
            <div class="{{ $tipoUsarioAccede == 5 || $tipoUsarioAccede == 4  ? 'col-3' : 'col-4' }}">
                <button onclick="changeTab('tab-2')" id="tab2" class="tb-item-btn">Comisiones listos P/Liberar</button>
            </div>
            <div class="{{ $tipoUsarioAccede == 5 || $tipoUsarioAccede == 4  ? 'col-3' : 'col-4' }}">
                <button onclick="changeTab('tab-3')" id="tab3" class="tb-item-btn">Comisiones liberadas</button>
            </div>
            <div class="{{ $tipoUsarioAccede == 5 || $tipoUsarioAccede == 4 ? 'col-3' : 'd-none' }}">
                <button onclick="changeTab('tab-4')" id="tab4" class="tb-item-btn">Incidencias</button>
            </div>
        </div>
    
        <div class="bg-white mt-4 py-4 ml-4 container-tbls relative">
            <div class="d-flex container-filters justify-content-between align-items-center" id="filtersMain">
                <div class="row" style="width: 100%">
                    <div class="col-lg">
                        <label for="filterPedido">N° pedido</label>
                        <input type="text" class="form-control searcherFilters" id="filterPedido" placeholder="Ingresa un valor para filtrar">
                    </div>
                    <div class="col-lg">
                        <label for="filterOP">N° OP</label>
                        <input type="text" class="form-control searcherFilters" id="filterOP" placeholder="Ingresa un valor para filtrar">
                    </div>
                    <div class="col-lg">
                        <label for="filterFactura">Factura</label>
                        <input type="text" class="form-control searcherFilters" id="filterFactura" placeholder="Ingresa un valor para filtrar">
                    </div>
                    <div class="col-lg">
                        <label for="filterCliente">Cliente</label>
                        <input type="text" class="form-control searcherFilters" id="filterCliente" placeholder="Ingresa un valor para filtrar">
                    </div>
                    <div class="col-lg">
                        <label for="filterTrabajo">Trabajo</label>
                        <input type="text" class="form-control searcherFilters" id="filterTrabajo" placeholder="Ingresa un valor para filtrar">
                    </div>
                    <div class="col-lg" id="ContFilterVendedor">
                        <label for="filterVendedor" id="lblVendedor">Vendedor</label>
                        <select name="filterVendedor" id="filterVendedor" class="form-control">
                            <option value="">Selecciona una opción</option>
                            @foreach ($listVendedoresIncidencias as $vendedor)
                                <option value="{{ $vendedor->NombreAgente }}">{{ $vendedor->NombreAgente }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg" id="compFilterEstatus">
                    </div>
                    <div class="col-lg" id="compFilterComPagadas">
                        <div>
                            <label for="filterComPagadas">Tipo de comisiones</label>
                            <select name="filterComPagadas" id="filterComPagadas" class="form-control">
                                <option value="5" selected>Liberadas</option>
                                <option value="6">Pagadas</option>
                                <option value="3">A cuenta de (liberado)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg" id="compFilterEstatusCom">
                        <div>
                            <label for="filterEstatusCom">Estatus Com.</label>
                            <select name="filterEstatusCom" id="filterEstatusCom" class="form-control">
                                <option value="">Seleccióna una opción</option>
                                @foreach ($estatusComisionesCandy as $estatusCom)
                                    <option value="{{ $estatusCom->IdEstatusCom }}">{{ $estatusCom->nombreEstatus }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg" id="comFilterFechaPago">
                        <label for="filterFechaPago">Fecha de pago</label>
                        <select class="form-control" id="filterFechaPago" name="filterFechaPago" required>
                            <option value="">Selecciona la quincena</option>
                            @foreach ($quincenasMajor2025 as $quincena)
                                <option value="{{ $quincena->QUINCENA }}">{{ $quincena->QUINCENA }}</option>
                            @endforeach
                        </select>
                        {{--  <input type="date" class="form-control searcherFilters" id="filterFechaPago" placeholder="Ingresa un valor para filtrar">  --}}
                    </div>
                </div>

                <div class="d-flex align-items-start justify-content-end col-lg-2">
                    <div class="input-group-append">
                        <div id="contentAddFilters">
                            <div class="d-flex">
                                <button type="button" onclick="showMoreFilters()" id="btn-generateReport"
                                    class="btn btn-secondary btn-flat ml-2 rounded-btn content-notes" title="Filtros adicionales">
                                    <span class="stash--filter-solid"></span>
                                </button>

                                <div class="bg-white absolute" style="width: 25rem; position: absolute;padding: 1.5rem;z-index: 10;right: 160px;margin-top: 40px;border-radius: 10px;box-shadow: 4px 4px 20px rgba(0,0,0,0.4);" id="moreFilters">
                                    <div class="row">
                                        <label><small>Firmas</small></label>
                                    </div>
                                    
                                    <div class="row mt-2">
                                        <div class="col-lg">
                                            <div class="">
                                                <label>Pedidos firmados (vendedores)</label>
                                                <select name="filterFirmasVendedor" id="filterFirmasVendedor" class="form-control" onchange="applyFiltersStatus('filterFirmasVendedor')">
                                                    <option value="">Todos</option>
                                                    <option value="1">Firmados</option>
                                                    <option value="2">No firmados</option>
                                                </select>
                                            </div>
                                            <div class=" mt-2">
                                                <label>Pedidos firmados (gerentes)</label>
                                                <select name="filterFirmasGerentes" id="filterFirmasGerentes" class="form-control" onchange="applyFiltersStatus('filterFirmasGerentes')">
                                                    <option value="">Todos</option>
                                                    <option value="1">Firmados</option>
                                                    <option value="2">No firmados</option>
                                                </select>
                                            </div>
                                            <div class=" mt-2 mb-2">
                                                <label>Pedidos firmados (dirección)</label>
                                                <select name="filterFirmasDireccion" id="filterFirmasDireccion" class="form-control" onchange="applyFiltersStatus('filterFirmasDireccion')">
                                                    <option value="">Todos</option>
                                                    <option value="1">Firmados</option>
                                                    <option value="2">No firmados</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" onclick="generateReportPlV2()" id="btn-generateReport"
                            class="btn btn-success btn-flat ml-2 rounded-btn content-notes" title="Generar">
                            <i class="icon-park-outline--excel"></i>
                        </button>
                        <button type="button" onclick="aplicarFiltroAdmPed()" id="btn-searchEstr"
                            class="btn btn-primary btn-flat ml-2 rounded-btn" title="Aplicar">
                            <i class="nav-icon fas fa-search"></i>
                        </button>
                        <button type="button" onclick="limpiarFiltroAdmPed()" id="btn-cleanEstr"
                            class="btn btn-danger btn-flat ml-2 rounded-btn" title="Limpiar">
                            <i class="nav-icon fas fa-search-minus"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="d-flex container-filters justify-content-between align-items-center" id="filtersIncidencias">
                <div class="row" style="width: 100%">
                    <div class="col-lg" id="ContFilterVendedorIncidencia">
                        <label for="filterVendedorIncidencia" id="lblVendedorIncidencia">Vendedor</label>
                        <select name="filterVendedorIncidencia" id="filterVendedorIncidencia" class="form-control">
                            <option value="">Selecciona una opción</option>
                            @foreach ($listVendedoresIncidencias as $vendedorI)
                                <option value="{{ $vendedorI->Id_Usuario }}">{{ $vendedorI->NombreAgente }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg" id="ContFilterMotivoIncidencia">
                        <label for="filterMotivoIncidencia" id="lblMotivoIncidencia">Motivo</label>
                        <select name="filterMotivoIncidencia" id="filterMotivoIncidencia" class="form-control">
                            <option value="">Selecciona una opción</option>
                            @foreach ($motivosIncidencias as $motivoI)
                                <option value="{{ $motivoI->incidencia }}">{{ $motivoI->incidencia }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg" id="ContFilterEstatusIncidencia">
                        <label for="filterEstatusIncidencia" id="lblEstatusIncidencia">Estatus</label>
                        <select name="filterEstatusIncidencia" id="filterEstatusIncidencia" class="form-control">
                            <option value="">Selecciona una opción</option>
                            <option value="0">Pendiente</option>
                            <option value="1">Pagado</option>
                        </select>
                    </div>
                    <div class="col-lg">
                        <label for="filterQuincena">Quincena</label>
                        <select class="form-control" id="filterQuincena" name="filterQuincena" required>
                            <option value="">Selecciona la quincena</option>
                            @foreach ($quincenasMajor2025 as $quincena)
                                <option value="{{ $quincena->QUINCENA }}">{{ $quincena->QUINCENA }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="d-flex align-items-start justify-content-end col-lg-4">
                    <span class="input-group-append">
                        <button type="button" onclick="configIncidencias()" id="btn-confInc"
                            class="btn btn-secondary btn-flat ml-2 rounded-btn" title="Configurar Incidencias">
                            <span class="heroicons--cog-8-tooth-16-solid"></span>
                        </button>

                        <button type="button" onclick="aplicarFiltroIncidencias()" id="btn-searchEstr"
                            class="btn btn-primary btn-flat ml-2 rounded-btn" title="Aplicar">
                            <i class="nav-icon fas fa-search"></i>
                        </button>
                        <button type="button" onclick="limpiarFiltroIncidencias()" id="btn-cleanEstr"
                            class="btn btn-danger btn-flat ml-2 rounded-btn" title="Limpiar">
                            <i class="nav-icon fas fa-search-minus"></i>
                        </button>
                    </span>
                </div>
            </div>
    
            <div id="listMain">
                @include('pedidos.index')
            </div>
    
            <div id="listPedidosParaLiberar">
                @include('pedidos.pedidos-listos-liberar')
            </div>
    
            <div id="pedidosLiberados">
                @include('pedidos.pedidos-liberados')
            </div>
            
            <div id="incidenciasPanel" @if ($tipoUsarioAccede == 5) style="display: none;" @endif>
                @include('pedidos.incidencias')
            </div>

            <div class="d-flex__content__actions">
                <div class="py-1">
                    <h6>Guía de acciones</h6>

                    <div class="d-flex justify-content-start align-items-center py-1">
                        <p class="d-flex align-items-center mr-2"><span class='heroicons--arrow-top-right-on-square-20-solid'></span> <span>&nbsp; Ir a oportunidad</span> </p>
                        <p class="d-flex align-items-center mr-2"><span class='charm--notes'></span> <span>&nbsp; Mostrar última tripa autorizada</span></p>
                        <p class="d-flex align-items-center mr-2"><img draggable="false" src="{{ asset('img/ojo.png') }}" width='20px' /> <span>&nbsp; Ver detalles</span></p>
                        @if ($tipoUsarioAccede == 5 || $tipoUsarioAccede == 1 || $tipoUsarioAccede == 2)
                            <p class="d-flex align-items-center"><img draggable="false" src="{{ asset('img/lapiz.png') }}" width='20px'> <span>&nbsp; Editar comision </span></p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('pedidos.modal-form')
    @include('pedidos.modal-details-pedido')
    @include('pedidos.modal-anticipos')
    @include('pedidos.nueva-incidencia')
    @include('pedidos.modal-reporte-tripa')
    @include('pedidos.modal-list-vendedores')
    @include('pedidos.modal-conf-incidencias')

    <script src=" {{ asset('js/oportunidades.js?n=1') }} " defer></script>
    <script src=" {{ asset('js/oportunidades_Docs.js?n=1') }} " defer></script>
    <script src=" {{ asset('js/cambiar_agente.js?n=1') }} " defer></script>
    <script>
        // admin_pedidos.js no puede usar route(): la URL se arma aquí para respetar el prefijo del proxy
        const urlGetVendedoresR = @json(route('getVendedoresR'));
    </script>
    <script src=" {{ asset('js/admin_pedidos.js?n=2') }} " defer></script>
    <script src=" {{ asset('js/xlsx.full.min.js?n=1') }} " defer></script>
    <script src=" {{ asset('js/xlsx.bundle.js?n=1') }} " defer></script>

    <style>
        .active-row {
            background-color: #C8D9EA !important;
            color: #2F2E2E;
        }

        .active-third-level-row {
            background-color: #c1e5e3 !important;
            color: #2F2E2E;
        }

        .active-fourth-level-row {
            background-color: #e2e5c1  !important;
            color: #2F2E2E;
        }

        .tblStr tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .custom-control-label {
            position: relative;
            margin-bottom: 0;
            vertical-align: top;
        }

        .custom-switch {
            padding: 0;
            margin-top: 1rem;
        }

        .custom-control-input {
            position: relative !important;
            left: -6px !important;
            z-index: 1 !important;
            width: 2rem !important;
            cursor: pointer;
        }

        .tb-item-btn {
            width: 100%;
            border: none;
            padding: 14px;
            font-weight: 600;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .tb-item-active {
            background-color: #007bff;
            color: #fff;
        }

        .container-tbls {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.2);
        }

        .container-filters {
            border-radius: 10px;
            box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.2);
            padding: 1.5rem;
            margin: auto 1.5rem 1.5rem;
            background-color: #E9E9E9;
        }

        .ctrl-input:checked~.ctrl-label::before {
            border-color: #c42b2b !important;
            background-color: #c42b2b !important;
        }

        .heroicons--clipboard-document-solid {
            display: inline-block;
            width: 20px;
            height: 20px;
            background-repeat: no-repeat;
            background-size: 100% 100%;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cg fill='%23007bff'%3E%3Cpath fill-rule='evenodd' d='M17.663 3.118q.338.023.673.05C19.876 3.298 21 4.604 21 6.109v9.642a3 3 0 0 1-3 3V16.5c0-5.922-4.576-10.775-10.384-11.217c.324-1.132 1.3-2.01 2.548-2.114q.336-.029.673-.051A3 3 0 0 1 13.5 1.5H15a3 3 0 0 1 2.663 1.618M12 4.5A1.5 1.5 0 0 1 13.5 3H15a1.5 1.5 0 0 1 1.5 1.5z' clip-rule='evenodd'/%3E%3Cpath d='M3 8.625c0-1.036.84-1.875 1.875-1.875h.375A3.75 3.75 0 0 1 9 10.5v1.875c0 1.036.84 1.875 1.875 1.875h1.875A3.75 3.75 0 0 1 16.5 18v2.625c0 1.035-.84 1.875-1.875 1.875h-9.75A1.875 1.875 0 0 1 3 20.625z'/%3E%3Cpath d='M10.5 10.5a5.23 5.23 0 0 0-1.279-3.434a9.77 9.77 0 0 1 6.963 6.963a5.23 5.23 0 0 0-3.434-1.279h-1.875a.375.375 0 0 1-.375-.375z'/%3E%3C/g%3E%3C/svg%3E");
        }

        .icon-park-outline--excel {
            display: inline-block;
            width: 20px;
            height: 20px;
            background-repeat: no-repeat;
            background-size: 100% 100%;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 48 48'%3E%3Cg fill='none' stroke='%23fff' stroke-linecap='round' stroke-width='4'%3E%3Cpath stroke-linejoin='round' d='M8 15V6a2 2 0 0 1 2-2h28a2 2 0 0 1 2 2v36a2 2 0 0 1-2 2H10a2 2 0 0 1-2-2v-9'/%3E%3Cpath d='M31 15h3m-6 8h6m-6 8h6'/%3E%3Cpath stroke-linejoin='round' d='M4 15h18v18H4zm6 6l6 6m0-6l-6 6'/%3E%3C/g%3E%3C/svg%3E");
        }

        .charm--notes {
            display: inline-block;
            width: 20px;
            height: 20px;
            background-repeat: no-repeat;
            background-size: 100% 100%;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='none' stroke='%2368d8ec' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M2.75 1.75h10.5v12.5H2.75zm3 6h4.5m-4.5 3h2.5m-2.5-6h4.5'/%3E%3C/svg%3E");
        }
        
        .heroicons--arrow-top-right-on-square-20-solid {
            display: inline-block;
            width: 20px;
            height: 20px;
            background-repeat: no-repeat;
            background-size: 100% 100%;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cg fill='%23007bff' fill-rule='evenodd' clip-rule='evenodd'%3E%3Cpath d='M4.25 5.5a.75.75 0 0 0-.75.75v8.5c0 .414.336.75.75.75h8.5a.75.75 0 0 0 .75-.75v-4a.75.75 0 0 1 1.5 0v4A2.25 2.25 0 0 1 12.75 17h-8.5A2.25 2.25 0 0 1 2 14.75v-8.5A2.25 2.25 0 0 1 4.25 4h5a.75.75 0 0 1 0 1.5z'/%3E%3Cpath d='M6.194 12.753a.75.75 0 0 0 1.06.053L16.5 4.44v2.81a.75.75 0 0 0 1.5 0v-4.5a.75.75 0 0 0-.75-.75h-4.5a.75.75 0 0 0 0 1.5h2.553l-9.056 8.194a.75.75 0 0 0-.053 1.06'/%3E%3C/g%3E%3C/svg%3E");
        }

        .content-notes {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .solar--notes-bold {
            display: inline-block;
            width: 25px;
            height: 25px;
            background-repeat: no-repeat;
            background-size: 100% 100%;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%23f5f500' d='m2.755 14.716l.517 1.932c.604 2.255.907 3.383 1.592 4.114a4 4 0 0 0 2.01 1.16c.976.228 2.104-.074 4.36-.678c2.254-.604 3.382-.906 4.113-1.591q.091-.086.176-.176a9 9 0 0 1-1.014-.15c-.696-.138-1.523-.36-2.501-.622l-.107-.029l-.025-.006c-1.064-.286-1.953-.524-2.663-.78c-.747-.27-1.425-.603-2.002-1.143a5.5 5.5 0 0 1-1.596-2.765c-.18-.769-.128-1.523.012-2.304c.134-.749.374-1.647.662-2.722l.535-1.994l.018-.07c-1.92.517-2.931.823-3.605 1.454a4 4 0 0 0-1.161 2.012c-.228.975.074 2.103.679 4.358'/%3E%3Cpath fill='%23cf2a2a' fill-rule='evenodd' d='m20.83 10.715l-.518 1.932c-.605 2.255-.907 3.383-1.592 4.114a4 4 0 0 1-2.01 1.161q-.145.034-.295.052c-.915.113-2.032-.186-4.064-.73c-2.255-.605-3.383-.907-4.114-1.592a4 4 0 0 1-1.161-2.011c-.228-.976.074-2.103.679-4.358l.517-1.932l.244-.905c.455-1.666.761-2.583 1.348-3.21a4 4 0 0 1 2.01-1.16c.976-.228 2.104.074 4.36.679c2.254.604 3.382.906 4.113 1.59a4 4 0 0 1 1.161 2.012c.228.976-.075 2.103-.679 4.358m-9.778-.91a.75.75 0 0 1 .919-.53l4.83 1.295a.75.75 0 1 1-.389 1.448l-4.83-1.294a.75.75 0 0 1-.53-.918m-.776 2.898a.75.75 0 0 1 .918-.53l2.898.777a.75.75 0 1 1-.388 1.448l-2.898-.776a.75.75 0 0 1-.53-.919' clip-rule='evenodd'/%3E%3C/svg%3E");
        }

        .swal2-select {
            background-color: #fff !important;
        }

        .heroicons--list-bullet-16-solid {
            display: inline-block;
            width: 25px;
            height: 25px;
            background-repeat: no-repeat;
            background-size: 100% 100%;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='%232163a3' d='M3 4.75a1 1 0 1 0 0-2a1 1 0 0 0 0 2M6.25 3a.75.75 0 0 0 0 1.5h7a.75.75 0 0 0 0-1.5zm0 4.25a.75.75 0 0 0 0 1.5h7a.75.75 0 0 0 0-1.5zm0 4.25a.75.75 0 0 0 0 1.5h7a.75.75 0 0 0 0-1.5zM4 12.25a1 1 0 1 1-2 0a1 1 0 0 1 2 0M3 9a1 1 0 1 0 0-2a1 1 0 0 0 0 2'/%3E%3C/svg%3E");
        }

        .heroicons--cog-8-tooth-16-solid {
            display: inline-block;
            width: 18px;
            height: 18px;
            margin-top: 5px;
            background-repeat: no-repeat;
            background-size: 100% 100%;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='%23fefefe' fill-rule='evenodd' d='M6.955 1.45A.5.5 0 0 1 7.452 1h1.096a.5.5 0 0 1 .497.45l.17 1.699c.484.12.94.312 1.356.562l1.321-1.081a.5.5 0 0 1 .67.033l.774.775a.5.5 0 0 1 .034.67l-1.08 1.32c.25.417.44.873.561 1.357l1.699.17a.5.5 0 0 1 .45.497v1.096a.5.5 0 0 1-.45.497l-1.699.17c-.12.484-.312.94-.562 1.356l1.082 1.322a.5.5 0 0 1-.034.67l-.774.774a.5.5 0 0 1-.67.033l-1.322-1.08a5 5 0 0 1-1.356.561l-.17 1.699a.5.5 0 0 1-.497.45H7.452a.5.5 0 0 1-.497-.45l-.17-1.699a5 5 0 0 1-1.356-.562L4.108 13.37a.5.5 0 0 1-.67-.033l-.774-.775a.5.5 0 0 1-.034-.67l1.08-1.32a5 5 0 0 1-.561-1.357l-1.699-.17A.5.5 0 0 1 1 8.548V7.452a.5.5 0 0 1 .45-.497l1.699-.17c.12-.484.312-.94.562-1.356L2.629 4.107a.5.5 0 0 1 .034-.67l.774-.774a.5.5 0 0 1 .67-.033L5.43 3.71a5 5 0 0 1 1.356-.561zM6 8c0 .538.212 1.026.558 1.385l.057.057a2 2 0 0 0 2.828-2.828l-.058-.056A2 2 0 0 0 6 8' clip-rule='evenodd'/%3E%3C/svg%3E");
        }

        .swal2-select-custom {
            background-color: #fff !important;
            color: #000 !important;
        }

        .stash--filter-solid {
            display: inline-block;
            width: 24px;
            height: 24px;
            background-repeat: no-repeat;
            background-size: 100% 100%;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%23fff' d='M6.532 4.75h6.936c.457 0 .854 0 1.165.03c.307.028.685.095.993.348c.397.326.621.814.624 1.322c.002.39-.172.726-.34.992c-.168.27-.411.59-.695.964l-2.596 3.422c-.252.332-.315.42-.359.51a1.2 1.2 0 0 0-.099.297c-.02.1-.023.212-.023.634v4.243c0 .208 0 .412-.014.578c-.015.164-.052.427-.224.663c-.21.287-.537.473-.9.495c-.302.019-.547-.103-.69-.183a7 7 0 0 1-.476-.31l-.989-.683l-.048-.033c-.191-.131-.403-.276-.562-.477a1.7 1.7 0 0 1-.303-.585c-.071-.244-.07-.5-.07-.738v-2.97c0-.422-.004-.534-.023-.634a1.2 1.2 0 0 0-.1-.297c-.043-.09-.106-.178-.358-.51L4.785 8.406c-.284-.374-.527-.694-.696-.964c-.167-.266-.34-.602-.339-.992a1.72 1.72 0 0 1 .624-1.322c.308-.253.686-.32.993-.349c.311-.029.707-.029 1.165-.029M5.308 6.305a.22.22 0 0 0-.057.134c.006.019.03.081.11.207c.128.205.33.472.64.881l2.575 3.394l.035.046c.201.264.361.475.478.715q.154.317.222.665c.051.261.05.527.05.864v2.968c0 .158.001.247.005.314l.006.062a.2.2 0 0 0 .036.073l.041.034c.05.04.12.088.248.176l.941.65V13.21c0-.337 0-.603.051-.864q.068-.347.222-.665c.117-.24.277-.45.478-.715l.035-.046l2.575-3.394c.31-.41.512-.676.64-.881c.08-.126.104-.188.11-.207a.22.22 0 0 0-.057-.134a1 1 0 0 0-.2-.032c-.232-.022-.556-.023-1.06-.023H6.568c-.504 0-.828 0-1.06.023a1 1 0 0 0-.2.032M15.75 10.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 0 1.5h-3a.75.75 0 0 1-.75-.75m-1.5 2.5a.75.75 0 0 1 .75-.75h4.5a.75.75 0 0 1 0 1.5H15a.75.75 0 0 1-.75-.75m-.5 2.5a.75.75 0 0 1 .75-.75h5a.75.75 0 0 1 0 1.5h-5a.75.75 0 0 1-.75-.75m0 2.5a.75.75 0 0 1 .75-.75H17a.75.75 0 0 1 0 1.5h-2.5a.75.75 0 0 1-.75-.75'/%3E%3Cpath fill='%23fff' d='M13.64 8H6.36l2.251 2.967c.201.264.361.475.478.715q.154.317.222.665c.051.261.05.527.05.864v2.968c0 .158.001.247.005.314l.006.062a.2.2 0 0 0 .036.073l.041.034c.05.04.12.088.248.176l.941.65V13.21c0-.337 0-.603.051-.864q.068-.347.222-.665c.117-.24.277-.45.478-.715z' opacity='0.5'/%3E%3C/svg%3E");
        }

        .clarity--users-line {
            display: inline-block;
            width: 28px;
            height: 28px;
            background-repeat: no-repeat;
            background-size: 100% 100%;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 36 36'%3E%3Cpath fill='%23000' d='M17.9 17.3c2.7 0 4.8-2.2 4.8-4.9s-2.2-4.8-4.9-4.8S13 9.8 13 12.4c0 2.7 2.2 4.9 4.9 4.9m-.1-7.7q.15 0 0 0c1.6 0 2.9 1.3 2.9 2.9s-1.3 2.8-2.9 2.8S15 14 15 12.5c0-1.6 1.3-2.9 2.8-2.9' class='clr-i-outline clr-i-outline-path-1'/%3E%3Cpath fill='%23000' d='M32.7 16.7c-1.9-1.7-4.4-2.6-7-2.5h-.8q-.3 1.2-.9 2.1c.6-.1 1.1-.1 1.7-.1c1.9-.1 3.8.5 5.3 1.6V25h2v-8z' class='clr-i-outline clr-i-outline-path-2'/%3E%3Cpath fill='%23000' d='M23.4 7.8c.5-1.2 1.9-1.8 3.2-1.3c1.2.5 1.8 1.9 1.3 3.2c-.4.9-1.3 1.5-2.2 1.5c-.2 0-.5 0-.7-.1c.1.5.1 1 .1 1.4v.6c.2 0 .4.1.6.1c2.5 0 4.5-2 4.5-4.4c0-2.5-2-4.5-4.4-4.5c-1.6 0-3 .8-3.8 2.2c.5.3 1 .7 1.4 1.3' class='clr-i-outline clr-i-outline-path-3'/%3E%3Cpath fill='%23000' d='M12 16.4q-.6-.9-.9-2.1h-.8c-2.6-.1-5.1.8-7 2.4L3 17v8h2v-7.2c1.6-1.1 3.4-1.7 5.3-1.6c.6 0 1.2.1 1.7.2' class='clr-i-outline clr-i-outline-path-4'/%3E%3Cpath fill='%23000' d='M10.3 13.1c.2 0 .4 0 .6-.1v-.6c0-.5 0-1 .1-1.4c-.2.1-.5.1-.7.1c-1.3 0-2.4-1.1-2.4-2.4S9 6.3 10.3 6.3c1 0 1.9.6 2.3 1.5c.4-.5 1-1 1.5-1.4c-1.3-2.1-4-2.8-6.1-1.5s-2.8 4-1.5 6.1c.8 1.3 2.2 2.1 3.8 2.1' class='clr-i-outline clr-i-outline-path-5'/%3E%3Cpath fill='%23000' d='m26.1 22.7l-.2-.3c-2-2.2-4.8-3.5-7.8-3.4c-3-.1-5.9 1.2-7.9 3.4l-.2.3v7.6c0 .9.7 1.7 1.7 1.7h12.8c.9 0 1.7-.8 1.7-1.7v-7.6zm-2 7.3H12v-6.6c1.6-1.6 3.8-2.4 6.1-2.4c2.2-.1 4.4.8 6 2.4z' class='clr-i-outline clr-i-outline-path-6'/%3E%3Cpath fill='none' d='M0 0h36v36H0z'/%3E%3C/svg%3E");
        }

        .d-flex__custom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .d-flex__content__actions {
            display: flex;
            justify-content: end;
            margin-right: 40px;
        }
    </style>
@endsection
