@extends('layout.master')
@section('content')
    @include('partials.loading')

    <script src="https://kit.fontawesome.com/624de654bd.js" crossorigin="anonymous"></script>

    {{-- <input type="hidden" id="rol_permiso" value="{{ $rol }}"> --}}
    {{-- <div class="sidebar-dark-primary text-center text-white">
        <h1>DISEÑO ESTRUCTURAL</h1>
    </div> --}}

    <div class="container-fluid">

        <div class="content-header">
            <div class="row mb-n2">
                <div class="col-sm-2">
                    <h1>Diseño Estructural</h1>
                    <input type="hidden" id="role" value="{{ $idrol }}">
                    <input type="hidden" id="idUsuario" value="{{ $id_usuario }}">
                </div>
                <div class="col-sm-8 justify-content-md-center">
                    <input type="hidden" id="idRolEstr" value="{{ $idrol }}">
                    <div class="col-sm-12 input-group input-group-md ">
                        {{--  <input type="text" id="searchEstr" class="form-control text-uppercase" placeholder="Buscar ...">  --}}

                        {{--  Buscador por liberados  --}}
                        <input type="text" id="searchFolio" class="form-control text-uppercase ml-1"
                            {{--  accionamos la busqueda cuando se le de enter  --}}
                            onkeyup="if(event.keyCode == 13) buscarEstrNuevo()"
                            placeholder="Folio">

                            {{--  Buscador por liberado  --}}
                        <select id="searchLiberado" class="form-control text-uppercase ml-1" onchange="buscarEstrNuevo()">
                            <option value="">Situación</option>
                            <option value="0">No liberado</option>
                            <option value="1">Liberado</option>
                        </select>

                        {{--  Buscador por cliente  --}}
                        <input type="text" id="searchCliente" class="form-control text-uppercase ml-1"
                        onkeyup="if(event.keyCode == 13) buscarEstrNuevo()"
                        placeholder="Cliente">

                        {{--  Buscador por titulo  --}}
                        <input type="text" id="searchTitulo" class="form-control text-uppercase ml-1"
                        onkeyup="if(event.keyCode == 13) buscarEstrNuevo()"
                        placeholder="Titulo">
                        
                        {{--  Buscador por motivo de reapertura  --}}
                        <input type="text" id="searchMotivoReapertura" class="form-control text-uppercase ml-1"
                        onkeyup="if(event.keyCode == 13) buscarEstrNuevo()"
                        placeholder="Motivo de reapertura">

                        {{--  Buscador por oportunidad  --}}
                        <input type="text" id="searchOportunidad" class="form-control text-uppercase ml-1"
                        onkeyup="if(event.keyCode == 13) buscarEstrNuevo()"
                        placeholder="Oportunidad">

                        {{--  Select por situación  --}}
                        <select id="selectSituacion" class="form-control text-uppercase ml-1" onchange="buscarEstrNuevo()">
                            <option value="">Situación</option>
                            <option value="--">--</option>
                            <option value="VENCE MAÑANA">VENCE MAÑANA</option>
                            <option value="ATRASADO">ATRASADO</option>
                            <option value="VENCE HOY">VENCE HOY</option>
                            <option value="A TIEMPO">A TIEMPO</option>
                        </select>
                        
                        {{--  Select por operador  --}}
                        <select id="selectOperador" class="form-control text-uppercase ml-1" onchange="buscarEstrNuevo()">
                            <option value="">Operador</option>
                        </select>

                        {{--  Select por estatus  --}}
                        <select id="selectEstatus" class="form-control text-uppercase ml-1" onchange="buscarEstrNuevo()">
                            <option value="">Estatus</option>
                        </select>
                        
                        <select id="selectMotivoReapertura" class="form-control text-uppercase ml-1" onchange="buscarEstrNuevo()">
                            <option value="">Motivo de reapertura</option>
                            @foreach ($catMotivosReEstr as $motivo)
                                <option value="{{ $motivo->id }}">{{ $motivo->nombre }}</option>
                            @endforeach
                        </select>
                        
                        @if ($idrol != 4)
                        {{--  Select por agentes  --}}
                        <select id="selectAgente" class="form-control text-uppercase ml-1" onchange="buscarEstrNuevo()">
                            <option value="">Agente</option>
                            @foreach ($agentesDb as $agenteItem)
                                <option value="{{ $agenteItem->Nombre }}">{{ $agenteItem->Nombre }}</option>
                            @endforeach
                        </select>
                        @endif

                        <span class="input-group-append">
                            <button type="button" onclick="buscarEstrNuevo()" id="btn-searchEstr"
                                class="btn btn-primary btn-flat ml-2 rounded-btn" title="Aplicar">
                                <i class="nav-icon fas fa-search"></i>
                            </button>
                            <button type="button" onclick="LimpiarFiltro()" id="btn-cleanEstr"
                                class="btn btn-danger btn-flat ml-2 rounded-btn" title="Limpiar">
                                <i class="nav-icon fas fa-search-minus"></i>
                            </button>
                            <a type="button" onclick="expandirTodo()" id="btn-expandirEstr"
                                class="ml-2 cursor-pointer btn p-0 mt-1" title="Colapsar todo">
                                <img src="{{ asset('img/flecha-up_w.png') }}" width="25" id="btn-expandirEstr-i">
                            </a>
                        </span>
                    </div>
                </div>
                <div class="col-sm-2 justify-content-md-center">
                    <span class="input-group-append">
                        <label for="abiertosCerrados" class="mr-1" style="cursor: pointer">Abiertos</label>
                        <div class="custom-switch" id="abiertosCerradosSwith">
                            <input type="checkbox" id="abiertosCerrados" class="custom-control-input">
                            <label for="abiertosCerrados" class="custom-control-label" style="cursor: pointer"></label>
                        </div>
                        <label for="abiertosCerrados" class="" style="cursor: pointer">Cerrados/Cancelados</label>
                    </span>
                </div>
            </div>
        </div>

        <div id="tabGeneral">
            <div class="tab-content mt-1">
                <div class="tab-pane fade show active" id="asignados" role="tabpanel" aria-labelledby="asignados-tab">
                    {{--  @if ($crearTickets == 'Crear')
                        <button onclick="agregarTicketDE(1, 1)" type="button" class="btn-flotante button btn btn-primary"
                            title="Agregar Proyecto">
                            <i class="fas fas fa-plus-circle"></i>
                        </button>
                    @endif  --}}
                    <table id="tblDetalleAsignados" class="table table-hover table-striped nowrap compact tblnombre"
                        onclick="ajustarTablas()">
                        <thead>
                            <tr class="bg-gray">
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th style="width: 12%"></th>
                                <th style="width: 10%"></th>
                                <th></th>
                                <th style="width: 20%"></th>
                                <th style="width: 20%"></th>
                                <th style="width: 15%"></th>
                                <th style="width: 22%"></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray">
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                
                                
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <script src=" {{ asset('js/estructural.js?n=1') }} " defer></script>
    <script src=" {{ asset('js/cambiar_estatus.js?n=1') }} " defer></script>
    @include('estructural.create')
    @include('estructural.mod_bitacoraEstr')

    <style>
        .no-styles {
            padding: 0 !important;
            margin: 0;
            border: 1px solid #4142438c;
            background: rgba(255, 255, 255, 1);
            color: inherit;
            border-radius: 5px !important;
            text-align: center;
            box-sizing: border-box;
            height: 25px !important;
            width: 50px;
        }

        .no-styles::-webkit-inner-spin-button,
        .no-styles::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .no-styles:focus {
            outline: none;
        }

        select {
            border-radius: 5px !important;
        }

        .text-yellow_ {
            color: #f39c12 !important;
        }

        .select_st,
        .select_op {
            width: 170px;
        }
    </style>
@endsection
