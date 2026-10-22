<div class="modal fullscreen-modal" id="modal-create-ticket" tabindex="-1" role="dialog" data-backdrop="static"
    data-keyboard="false" aria-labelledby="myModalLabel" aria-hidden="true">
    @include('partials.loading')
    <div class="modal-dialog">
        <div class="modal-content">
            <div id="isCancelledESTR" class="canceling-cont" style="display: block">
                <div class="canceling">CANCELADO</div>
            </div>

            
            <button id="clonarTicket" @if ($idrol != 1024) onclick="clonarTicketDE()" @endif type="button"
                class="btn-flotante button btn btn-primary-clone {{ ($idrol != 1024) ? '' : 'sr-only' }}" title="Clonar Proyecto">
                <i><img src="{{ asset('img/clon.png') }}" width="28"></i>
            </button>

            <form id="formCreateEstr">
                {{ csrf_field() }} {{ method_field('POST') }}
                <input type="hidden" id="usuarioPerteneceTicket" name="usuarioPerteneceTicket">

                {{-- <form method="post" class="form-horizontal" data-toggle="validator"> --}}
                <div class="modal-head">
                    <div class="col-12 mt-2">
                        <div class="rounded-pill bg-gray">
                            <div class="row mb-2 d-flex">
                                <div class="col-10 text-center float-left">
                                    <h6 id="tituloTicket" class="text-bold pt-1 pl-1" style="margin-left: 16.666667%">
                                        Alta de Proyecto</h6>
                                </div>
                                <div class="col-2 text-center float-right">
                                    <h6 id="fechaEstr" class="text-bold pt-1 pl-1"></h6>
                                </div>
                            </div>
                        </div>
                        <div class="mb-1 seccion_proyecto">
                            <div class="d-flex">
                                <h5 id="folio" class="text-center text-bold ml-2 text-red" style="margin-top: -5px">
                                    Folio No.
                                </h5>

                                <div class="col-11 text-right" style="margin-top: -5px">
                                    {{--  <div class="custom-control custom-switch">
                                        <input id="cotizacion" name="cotizacion" type="checkbox"
                                            class="custom-control-input">
                                        <label id="cotizacionLabel" class="custom-control-label"
                                            for="cotizacion">Requiere Cotización </label>
                                    </div>  --}}
                                </div>

                            </div>
                            <div class="input-group input-group-sm">
                                <div class="col-1">
                                    <label class="mt-2" for="folioOportunidad">Folio oportunidad &nbsp; <span
                                            style="color:red;">*</span></label>
                                    </div>
                                <div class="col-2 mr-2">
                                    <input type="hidden" name="idOportunidadEstrNew"
                                    id="idOportunidadEstrNew">
                                    <div id="selectFolio">
                                        <select id="folioOportunidad" name="folioOportunidad"
                                        class="form-control select2 select2-height"
                                            style="text-transform: uppercase; margin: 0 5px;">
                                            <option value="" selected disabled>Folio Oportunidad</option>
                                            @foreach ($oportunidadesCache as $oportunidad)
                                                <option value="{{ $oportunidad->idOportunidad }} - {{ $oportunidad->idClienteINT }} - {{ $oportunidad->nomCliente }}">
                                                    {{ $oportunidad->folio }} - {{ $oportunidad->nomOportunidad }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="text" class="form-control hidden" id="folioOportunidadText"
                                        placeholder="Folio Oportunidad" readonly autocomplete="off" autofocus>
                                </div>
                                <label class="mt-2" for="nombOportunidad">Oportunidad &nbsp; <span
                                        style="color:red;">*</span></label>
                                <input type="text" class="form-control col-lg-4 text-uppercase mr-2" id="nombOportunidad"
                                    placeholder="Nombre Oportunidad" readonly autocomplete="off"
                                    autofocus>
                                <label class="mt-2" for="nomOportunidadEstr">Nombre <span
                                        style="color:red;">*</span></label>
                                <input type="text" class="form-control col-lg-4 text-uppercase"
                                    id="nomOportunidadEstr" name="nomOportunidadEstr" placeholder="Nombre Proyecto"
                                    readonly autocomplete="off" autofocus>
                                <div class="input-group-append col-lg-4">
                                    <span id="sbtnCancelarkTicket" class="col-md-4 center">
                                        <a id="btnCancelarTicket"
                                            class="btn btn-danger btn-sm btn-block cursor-pointer">Cancelar</a>
                                    </span>
                                    <span id="sbtnSaveTicket" class="col-lg-4">
                                        @if ($idrol != 1024)
                                        <button id="btnSaveTicket" type="button"
                                            class="btn btn-primary btn-sm btn-block"
                                            onclick="guardarTicket()">Guardar</button>
                                        @endif
                                    </span>
                                    <span id="sbtnBackTicket" class="col-lg-4 center">
                                        <button id="btnBackTicket" type="button"
                                            class="btn btn-secondary btn-sm btn-block" data-dismiss="modal"
                                            onclick="closeMEstr(), loaderOut()"></button>
                                    </span>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-6">
                                    <div class="row mt-1 mb-1">
                                        <div class="col-2">
                                            <label for="idClienteEstr">Cliente <span style="color:red;">*</span></label>
                                        </div>
                                        <div class="col-10">
                                            {{--  <select id="idClienteEstr" name="idClienteEstr"
                                                class="form-control select2 select2-height"
                                                disabled
                                                style="text-transform: uppercase">
                                            </select>  --}}

                                            <input type="hidden" name="idClienteEstr" id="idClienteEstr">
                                            <input type="text" class="form-control text-uppercase" id="nomClienteEstr"
                                                name="nomClienteEstr" placeholder="Nombre Cliente" readonly
                                                autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-3 text-center">
                                            <div class="form-group">
                                                <div class="custom-control custom-switch">
                                                    <input id="dummy" name="dummy" type="checkbox"
                                                        class="custom-control-input">
                                                    <label id="dummyLabel" class="custom-control-label"
                                                        for="dummy">Dummy <span style="color:red;">*</span></label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-3 text-center">
                                            <div class="form-group">
                                                <div class="custom-control custom-switch">
                                                    <input id="render" name="render" type="checkbox"
                                                        class="custom-control-input">
                                                    <label id="renderLabel" class="custom-control-label"
                                                        for="render">Render</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-3 text-center">
                                            <div class="form-group">
                                                <div class="custom-control custom-switch">
                                                    <input id="trazo" name="trazo" type="checkbox"
                                                        class="custom-control-input">
                                                    <label id="trazoLabel" class="custom-control-label"
                                                        for="trazo">Trazo
                                                        Mecánico</label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-3 text-center">
                                            <div class="form-group">
                                                <div class="custom-control custom-switch">
                                                    <input id="render_dinamico" name="render_dinamico" type="checkbox"
                                                        class="custom-control-input">
                                                    <label id="render_dinamicoLabel" class="custom-control-label"
                                                        for="render_dinamico">Render Dinámico</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-3 text-center">
                                            {{--  <div class="form-group">
                                                <div class="custom-control custom-switch">
                                                    <input id="instructivo" name="instructivo" type="checkbox"
                                                        class="custom-control-input">
                                                    <label id="instructivoLabel" class="custom-control-label"
                                                        for="instructivo">Instructivo</label>
                                                </div>
                                            </div>  --}}
                                        </div>
                                    </div>
                                </div>

                            </div>
                            {{-- <hr> --}}
                            <div>
                                <div class="row">
                                    <div class="col-6 mb-2">
                                        <div class="row mb-2">
                                            <div class="col-2">
                                                <label>Cantidades <span style="color:red;">*</span></label>
                                            </div>
                                            <div class="col-2">
                                                <input id="cant1" name="cant1" required type="number"
                                                    class="form-control input-sm">
                                            </div>
                                            <div class="col-2">
                                                <input id="cant2" name="cant2" type="number"
                                                    class="form-control input-sm" disabled>
                                            </div>
                                            <div class="col-2">
                                                <input id="cant3" name="cant3" type="number"
                                                    class="form-control input-sm" disabled>
                                            </div>
                                            {{--  <div class="col-2">
                                                <input id="cant4" name="cant4" type="number"
                                                    class="form-control input-sm" disabled>
                                            </div>
                                            <div class="col-2">
                                                <input id="cant5" name="cant5" type="number"
                                                    class="form-control input-sm" disabled>
                                            </div>  --}}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="col-12" style="margin-top: -2px">
                        <div>
                            <div>
                                <ul class="nav nav-tabs col-12" id="custom-content-below-tab" role="tablist">
                                    <li class="nav-item col-4">
                                        <a class="nav-link active" id="general-ticket-tab" data-toggle="pill"
                                            style="cursor: pointer" onclick="mostrarGeneralTicket()" role="tab"
                                            aria-controls="general-ticket-tab" aria-selected="true">General</a>
                                    </li>
                                    {{-- @if ($rol != 1014) --}}
                                    {{-- <li class="nav-item col-4">
                                        <a class="nav-link" id="especificaciones-ticket-tab"
                                            onclick="mostrarEspecificacionesTicket()" data-toggle="pill"
                                            role="tab" aria-controls="especificaciones-ticket-tab"
                                            aria-selected="false" style="cursor: pointer">Especificaciones</a>
                                    </li> --}}
                                    {{-- @endif --}}
                                    <li class="nav-item col-4">
                                        <a class="nav-link" id="documentos-ticket-tab" onclick="mostrarDocumentosTicket()" data-toggle="pill" role="tab"
                                            aria-controls="documentos-ticket-tab" aria-selected="false"
                                            style="cursor: pointer">Documentos</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="scrollbarTabsModalRoundEstr">
                                    <div class="tab-pane fade show active" id="general-ticket" role="tabpanel"
                                        aria-labelledby="custom-content-below-home-tab">
                                        <input type="hidden" name="idUsuarioEstr" id="idUsuarioEstr" />
                                        <input type="hidden" name="idClienteINTEstr" id="idClienteINTEstr" />
                                        <input type="hidden" name="agenteEstr" id="agenteEstr" />
                                        <input type="hidden" name="idAgenteEstr" id="idAgenteEstr" />
                                        <input type="hidden" name="idOportunidadEstr" id="idOportunidadEstr" />
                                        <input type="hidden" name="idTicketEstr" id="idTicketEstr" />
                                        <input type="hidden" name="fechaRegistroEstr" id="fechaRegistroEstr" />
                                        <input type="hidden" name="fechaCambioEstr" id="fechaCambioEstr" />
                                        <input type="hidden" name="Categoria" id="Categoria" />

                                        <div class="row pl-2 pr-2">
                                            <div class="col-6-pr seccion_proyecto">
                                                <div style="margin-top: -10px; margin-bottom: -10px; padding-top: 10px;">
                                                    <h6 id="productoLabel" class="mb-0 text-bold">Tipo de producto
                                                        <label style="color:red;">*</label>
                                                    </h6>

                                                    <div>
                                                        <select name="tipo_producto" id="tipo_producto" class="form-control">
                                                            <option value="" selected disabled>Seleccione un tipo de producto</option>
                                                            @foreach ($catTiposProductosAgrupados as $catTipoProducto)
                                                                <optgroup label="{{ $catTipoProducto['label'] }}">
                                                                    @foreach ($catTipoProducto['options'] as $option)
                                                                        <option value="{{ $catTipoProducto['label'] }}-{{ $option['descripcion'] }}">
                                                                            {{ $option['descripcion'] }}
                                                                        </option>
                                                                    @endforeach
                                                                </optgroup>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <h6 class="text-bold" style="margin-top: -10px;">Ventana</h6>
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="form-group">
                                                                        <div class="custom-control custom-switch">
                                                                            <input id="conAcetato" name="conAcetato"
                                                                                type="checkbox" class="custom-control-input">
                                                                            <label class="custom-control-label"
                                                                                for="conAcetato">Con
                                                                                acetato</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="form-group">
                                                                        <div class="custom-control custom-switch">
                                                                            <input id="suajado" name="suajado"
                                                                                type="checkbox" class="custom-control-input">
                                                                            <label class="custom-control-label"
                                                                                for="suajado">Solo suajado</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-6">
                                                            <h6 class="text-bold" style="margin-top: -10px;">Material entregado por el cliente</h6>
                                                            <div class="row" style="padding-bottom: 10px;">
                                                                <div class="col-4">
                                                                    <div class="form-group">
                                                                        <div class="custom-control custom-switch">
                                                                            <input id="entPlano" name="entPlano"
                                                                                type="checkbox" class="custom-control-input">
                                                                            <label class="custom-control-label"
                                                                                for="entPlano">Plano</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-4">
                                                                    <div class="form-group">
                                                                        <div class="custom-control custom-switch">
                                                                            <input id="entRender" name="entRender"
                                                                                type="checkbox" class="custom-control-input">
                                                                            <label class="custom-control-label"
                                                                                for="entRender">Render</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-4">
                                                                    <div class="form-group">
                                                                        <div class="custom-control custom-switch">
                                                                            <input id="entFoto" name="entFoto"
                                                                                type="checkbox" class="custom-control-input">
                                                                            <label class="custom-control-label"
                                                                                for="entFoto">Foto</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-4">
                                                                    <div class="form-group">
                                                                        <div class="custom-control custom-switch">
                                                                            <input id="entMuestraFisica" name="entMuestraFisica"
                                                                                type="checkbox" class="custom-control-input">
                                                                            <label class="custom-control-label"
                                                                                for="entMuestraFisica">Muestra Física</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-4">
                                                                    <div class="form-group">
                                                                        <div class="custom-control custom-switch">
                                                                            <input id="entProducto" name="entProducto"
                                                                                type="checkbox" class="custom-control-input">
                                                                            <label class="custom-control-label"
                                                                                for="entProducto">Producto</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="mb-2">
                                                    <h6 class="text-bold"
                                                        style="margin-top: -10px; margin-bottom: -10px">Materiales</h6>
                                                    <div class="row mb-1">
                                                        <div class="col-6">

                                                        </div>
                                                        <div class="col-3">
                                                            Puntos
                                                        </div>
                                                        <div class="col-3">
                                                            Flauta
                                                        </div>
                                                    </div>

                                                    <div class="row mb-1">
                                                        <div class="col-6">
                                                            <input type="text"
                                                                class="form-control  form-control-sm text-uppercase"
                                                                name="materiales1" id="materiales1">
                                                        </div>
                                                        <div class="col-3">
                                                            <input type="text"
                                                                class="form-control  form-control-sm text-uppercase"
                                                                name="puntos1" id="puntos1">
                                                        </div>
                                                        <div class="col-3">
                                                            <input type="text"
                                                                class="form-control  form-control-sm text-uppercase"
                                                                name="flauta1" id="flauta1">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-1">
                                                        <div class="col-6">
                                                            <input type="text"
                                                                class="form-control  form-control-sm text-uppercase"
                                                                name="materiales2" id="materiales2">
                                                        </div>
                                                        <div class="col-3">
                                                            <input type="text"
                                                                class="form-control  form-control-sm text-uppercase"
                                                                name="puntos2" id="puntos2">
                                                        </div>
                                                        <div class="col-3">
                                                            <input type="text"
                                                                class="form-control  form-control-sm text-uppercase"
                                                                name="flauta2" id="flauta2">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-1">
                                                        <div class="col-6">
                                                            <input type="text"
                                                                class="form-control  form-control-sm text-uppercase"
                                                                name="materiales3" id="materiales3">
                                                        </div>
                                                        <div class="col-3">
                                                            <input type="text"
                                                                class="form-control  form-control-sm text-uppercase"
                                                                name="puntos3" id="puntos3">
                                                        </div>
                                                        <div class="col-3">
                                                            <input type="text"
                                                                class="form-control  form-control-sm text-uppercase"
                                                                name="flauta3" id="flauta3">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-1">
                                                        <div class="col-6">
                                                            <input type="text"
                                                                class="form-control  form-control-sm text-uppercase"
                                                                name="materiales4" id="materiales4">
                                                        </div>
                                                        <div class="col-3">
                                                            <input type="text"
                                                                class="form-control  form-control-sm text-uppercase"
                                                                name="puntos4" id="puntos4">
                                                        </div>
                                                        <div class="col-3">
                                                            <input type="text"
                                                                class="form-control  form-control-sm text-uppercase"
                                                                name="flauta4" id="flauta4">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6 seccion_proyecto">
                                                {{--  <div class="pb-1" style="margin-top: -10px;">
                                                    <h6 class="text-bold">Impresión</h6>
                                                    <div class="row form-group" style="margin-bottom: -8px">

                                                        <div class="custom-control custom-radio col-6">
                                                            <input class="custom-control-input" type="radio"
                                                                id="tipo_impresionOffset" name="tipo_impresion"
                                                                value="OFFSET">
                                                            <label for="tipo_impresionOffset"
                                                                class="custom-control-label">Offset</label>
                                                        </div>

                                                        <div class="custom-control custom-radio col-6">
                                                            <input class="custom-control-input" type="radio"
                                                                id="tipo_impresionCama_plana" name="tipo_impresion"
                                                                value="CAMA PLANA">
                                                            <label for="tipo_impresionCama_plana"
                                                                class="custom-control-label">Cama plana</label>
                                                        </div>

                                                    </div>
                                                    <hr>
                                                    <div class="row form-group mb-1">

                                                        <div class="custom-control custom-radio col-6">
                                                            <input class="custom-control-input" type="radio"
                                                                id="tipo_impresion4x0" name="medida" value="4x0">
                                                            <label for="tipo_impresion4x0"
                                                                class="custom-control-label">4x0</label>
                                                        </div>


                                                        <div class="custom-control custom-radio col-6">
                                                            <input class="custom-control-input" type="radio"
                                                                id="tipo_impresion4x4" name="medida" value="4x4">
                                                            <label for="tipo_impresion4x4"
                                                                class="custom-control-label">4x4</label>
                                                        </div>

                                                    </div>
                                                    <div class="row mb-1">
                                                        <div class="col-12">
                                                            <select class="select-multiple" name="barnices[]"
                                                                id="barnices" multiple="multiple">
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12 mb-1">
                                                            <input type="text" class="form-control text-uppercase"
                                                                placeholder="Otros acabados" name="otrosAcabados"
                                                                id="otrosAcabados" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>  --}}
                                                <div style="margin-top: -10px; margin-bottom: -10px; padding-top: 10px;">
                                                    <h6 class="text-bold">Medidas Generales </h6>

                                                    <div class="d-flex w-full justify-content-between">
                                                        <div class="mb-1">
                                                            <h6>Tipo de medida</h6>
                                                            <div class="d-flex">
                                                                <div class="d-flex justify-content-start align-items-center">
                                                                    <input type="radio" class="ml-2"
                                                                    name="tipoMedidaGeneral" id="tipoMedidaGeneral1"
                                                                    value="2">
                                                                    <label for="tipoMedidaGeneral1" class="ml-1 mb-0">mm</label>
                                                                </div>
                                                                <div class="d-flex justify-content-start align-items-center">
                                                                    <input type="radio" class="ml-2"
                                                                        name="tipoMedidaGeneral" id="tipoMedidaGeneral2"
                                                                        value="1">
                                                                    <label for="tipoMedidaGeneral2" class="ml-1 mb-0">cm</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="mb-1">
                                                            <div class="d-flex">
                                                                <div class="form-group">
                                                                    <div class="custom-control custom-switch">
                                                                        <input id="aConsideracion" name="aConsideracion"
                                                                            type="checkbox" class="custom-control-input">
                                                                        <label class="custom-control-label"
                                                                            for="aConsideracion">A consideración del diseñador</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-1">
                                                        <div class="col-4"><input type="text"
                                                                class="form-control form-control-sm text-uppercase"
                                                                name="mgFrente" id="mgFrente" />
                                                        </div>
                                                        <div class="col-4"><input type="text"
                                                                class="form-control form-control-sm text-uppercase"
                                                                name="mgFondo" id="mgFondo" />
                                                        </div>
                                                        <div class="col-4"><input type="text"
                                                                class="form-control form-control-sm text-uppercase"
                                                                name="mgAlto" id="mgAlto" />
                                                        </div>
                                                    </div>
                                                    <div class="row mb-1">
                                                        <div class="col-4">Frente</div>
                                                        <div class="col-4">Fondo</div>
                                                        <div class="col-4">Alto</div>
                                                    </div>
                                                </div>
                                                <hr>
                                                    {{--  <div style="margin-top: -10px; margin-bottom: -10px">
                                                        <h6 class="text-bold">Especificaciones de carga/producto</h6>
                                                        
                                                        <div class="mb-1">
                                                            <h6>Tipo de medida</h6>
                                                            <div class="d-flex">
                                                                <div class="d-flex justify-content-start align-items-center">
                                                                    <input type="radio" class="ml-2"
                                                                        name="tipoMedidaProducto" id="tipoMedidaProducto1"
                                                                        value="2">
                                                                    <label for="tipoMedidaProducto1" class="ml-1 mb-0">mm</label>
                                                                </div>
                                                                <div class="d-flex justify-content-start align-items-center">
                                                                    <input type="radio" class="ml-2"
                                                                        name="tipoMedidaProducto" id="tipoMedidaProducto2"
                                                                        value="1">
                                                                    <label for="tipoMedidaProducto2" class="ml-1 mb-0">cm</label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-1">
                                                            <div class="col-4">Descripción</div>
                                                            <div class="col-1">Cant.</div>
                                                            <div class="col-2">Frente</div>
                                                            <div class="col-2">Fondo</div>
                                                            <div class="col-2">Alto </div>
                                                            <div class="col-1">Muestra</div>
                                                        </div>
                                                        <div class="row mb-1">
                                                            <div class="col-4"><input type="text"
                                                                    class="form-control form-control-sm text-uppercase"
                                                                    name="ecpDescr1" id="ecpDescr1" />
                                                            </div>
                                                            <div class="col-1"><input type="text"
                                                                    class="form-control form-control-sm text-uppercase"
                                                                    name="ecpCant1" id="ecpCant1" />
                                                            </div>
                                                            <div class="col-2"><input type="text"
                                                                    class="form-control form-control-sm text-uppercase"
                                                                    name="ecpFrente1" id="ecpFrente1" />
                                                            </div>
                                                            <div class="col-2"><input type="text"
                                                                    class="form-control form-control-sm text-uppercase"
                                                                    name="ecpFondo1" id="ecpFondo1" />
                                                            </div>
                                                            <div class="col-2"><input type="text"
                                                                    class="form-control form-control-sm text-uppercase"
                                                                    name="ecpAlto1" id="ecpAlto1" />
                                                            </div>
                                                            <div class="col-1">
                                                                <div class="custom-control custom-switch">
                                                                    <input id="esp1" name="esp1" type="checkbox"
                                                                        class="custom-control-input">
                                                                    <label class="custom-control-label"
                                                                        for="esp1"></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-1">
                                                            <div class="col-4"><input type="text"
                                                                    class="form-control form-control-sm text-uppercase"
                                                                    name="ecpDescr2" id="ecpDescr2" />
                                                            </div>
                                                            <div class="col-1"><input type="text"
                                                                    class="form-control form-control-sm text-uppercase"
                                                                    name="ecpCant2" id="ecpCant2" />
                                                            </div>
                                                            <div class="col-2"><input type="text"
                                                                    class="form-control form-control-sm text-uppercase"
                                                                    name="ecpFrente2" id="ecpFrente2" />
                                                            </div>
                                                            <div class="col-2"><input type="text"
                                                                    class="form-control form-control-sm text-uppercase"
                                                                    name="ecpFondo2" id="ecpFondo2" />
                                                            </div>
                                                            <div class="col-2"><input type="text"
                                                                    class="form-control form-control-sm text-uppercase"
                                                                    name="ecpAlto2" id="ecpAlto2" />
                                                            </div>
                                                            <div class="col-1">
                                                                <div class="custom-control custom-switch">
                                                                    <input id="esp2" name="esp2" type="checkbox"
                                                                        class="custom-control-input">
                                                                    <label class="custom-control-label"
                                                                        for="esp2"></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-1">
                                                            <div class="col-4"><input type="text"
                                                                    class="form-control form-control-sm text-uppercase"
                                                                    name="ecpDescr3" id="ecpDescr3" />
                                                            </div>
                                                            <div class="col-1"><input type="text"
                                                                    class="form-control form-control-sm text-uppercase"
                                                                    name="ecpCant3" id="ecpCant3" />
                                                            </div>
                                                            <div class="col-2"><input type="text"
                                                                    class="form-control form-control-sm text-uppercase"
                                                                    name="ecpFrente3" id="ecpFrente3" />
                                                            </div>
                                                            <div class="col-2"><input type="text"
                                                                    class="form-control form-control-sm text-uppercase"
                                                                    name="ecpFondo3" id="ecpFondo3" />
                                                            </div>
                                                            <div class="col-2"><input type="text"
                                                                    class="form-control form-control-sm text-uppercase"
                                                                    name="ecpAlto3" id="ecpAlto3" />
                                                            </div>
                                                            <div class="col-1">
                                                                <div class="custom-control custom-switch">
                                                                    <input id="esp3" name="esp3" type="checkbox"
                                                                        class="custom-control-input">
                                                                    <label class="custom-control-label"
                                                                        for="esp3"></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-1">
                                                            <div class="col-4"><input type="text"
                                                                    class="form-control form-control-sm text-uppercase"
                                                                    name="ecpDescr4" id="ecpDescr4" />
                                                            </div>
                                                            <div class="col-1"><input type="text"
                                                                    class="form-control form-control-sm text-uppercase"
                                                                    name="ecpCant4" id="ecpCant4" />
                                                            </div>
                                                            <div class="col-2"><input type="text"
                                                                    class="form-control form-control-sm text-uppercase"
                                                                    name="ecpFrente4" id="ecpFrente4" />
                                                            </div>
                                                            <div class="col-2"><input type="text"
                                                                    class="form-control form-control-sm text-uppercase"
                                                                    name="ecpFondo4" id="ecpFondo4" />
                                                            </div>
                                                            <div class="col-2"><input type="text"
                                                                    class="form-control form-control-sm text-uppercase"
                                                                    name="ecpAlto4" id="ecpAlto4" />
                                                            </div>
                                                            <div class="col-1">
                                                                <div class="custom-control custom-switch">
                                                                    <input id="esp4" name="esp4" type="checkbox"
                                                                        class="custom-control-input">
                                                                    <label class="custom-control-label"
                                                                        for="esp4"></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>  --}}
                                                <div class="row">
                                                    <div class="col-6 border-right mb-2">
                                                        <div style="margin-top: -10px; margin-bottom: -10px">
                                                            <h6 class="text-bold">Empaque</h6>
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <div class="form-group">
                                                                        <div class="custom-control custom-switch">
                                                                            <input id="cajamaster" name="cajamaster"
                                                                                type="checkbox"
                                                                                class="custom-control-input">
                                                                            <label class="custom-control-label"
                                                                                for="cajamaster">Caja master
                                                                                (pza c/u)</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6">
                                                                    <div class="form-group">
                                                                        <div class="custom-control custom-switch">
                                                                            <input id="bolsa" name="bolsa"
                                                                                type="checkbox"
                                                                                class="custom-control-input">
                                                                            <label class="custom-control-label"
                                                                                for="bolsa">Bolsa</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                {{--  <div class="col-6">
                                                                    <div class="form-group">
                                                                        <div class="custom-control custom-switch">
                                                                            <input id="cajaindividual"
                                                                                name="cajaindividual" type="checkbox"
                                                                                class="custom-control-input">
                                                                            <label class="custom-control-label"
                                                                                for="cajaindividual">Caja
                                                                                individual </label>
                                                                        </div>
                                                                    </div>
                                                                </div>  --}}
                                                            </div>
                                                            {{--  <div class="row">
                                                                
                                                                <div class="col-6">
                                                                    <div class="form-group">
                                                                        <div class="custom-control custom-switch">
                                                                            <input id="emplayado" name="emplayado"
                                                                                type="checkbox"
                                                                                class="custom-control-input">
                                                                            <label class="custom-control-label"
                                                                                for="emplayado">Emplayado</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row" style="margin-left: 10px">
                                                                <div class="col-6"></div>
                                                                <div class="col-6">
                                                                    <div class="form-group">
                                                                        <div class="custom-control custom-switch">
                                                                            <input id="enpaquetes" name="enpaquetes"
                                                                                type="checkbox"
                                                                                class="custom-control-input">
                                                                            <label class="custom-control-label"
                                                                                for="enpaquetes">En
                                                                                paquetes</label>
                                                                        </div>
                                                                        <div class="custom-control custom-switch">
                                                                            <input id="individual" name="individual"
                                                                                type="checkbox"
                                                                                class="custom-control-input">
                                                                            <label class="custom-control-label"
                                                                                for="individual">Individual</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>  --}}
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div style="margin-top: -10px; margin-bottom: -10px">
                                                            <h6 class="text-bold">Entrega</h6>
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <div class="form-group">
                                                                        <div class="custom-control custom-switch">
                                                                            <input id="armado" name="armado"
                                                                                type="checkbox"
                                                                                class="custom-control-input">
                                                                            <label class="custom-control-label"
                                                                                for="armado">Armado</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                {{--  <div class="col-6">
                                                                    <div class="form-group">
                                                                        <div class="custom-control custom-switch">
                                                                            <input id="transporte" name="transporte"
                                                                                type="checkbox"
                                                                                class="custom-control-input">
                                                                            <label class="custom-control-label"
                                                                                for="transporte">Transporte</label>
                                                                        </div>
                                                                    </div>
                                                                </div>  --}}
                                                                <div class="col-6">
                                                                    <div class="form-group">
                                                                        <div class="custom-control custom-switch">
                                                                            <input id="desplegado" name="desplegado"
                                                                                type="checkbox"
                                                                                class="custom-control-input">
                                                                            <label class="custom-control-label"
                                                                                for="desplegado">Desplegado</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{--  <div class="row">
                                                                <div class="col-6">
                                                                    <div class="form-group">
                                                                        <div class="custom-control custom-switch">
                                                                            <input id="desplegado" name="desplegado"
                                                                                type="checkbox"
                                                                                class="custom-control-input">
                                                                            <label class="custom-control-label"
                                                                                for="desplegado">Desplegado</label>
                                                                        </div>
                                                                        <div style="padding-left:10px">
                                                                            <div class="custom-control custom-switch">
                                                                                <input id="instructivos"
                                                                                    name="instructivos"
                                                                                    type="checkbox"
                                                                                    class="custom-control-input">
                                                                                <label class="custom-control-label"
                                                                                    for="instructivos">Instructivo</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6">
                                                                    <div style="padding-left:10px">
                                                                        <div class="form-group">
                                                                            <div class="custom-control custom-switch">
                                                                                <input id="ametropolitana"
                                                                                    name="ametropolitana"
                                                                                    type="checkbox"
                                                                                    class="custom-control-input">
                                                                                <label class="custom-control-label"
                                                                                    for="ametropolitana">Area
                                                                                    metro.</label>
                                                                            </div>
                                                                            <div class="custom-control custom-switch">
                                                                                <input id="foraneo" name="foraneo"
                                                                                    type="checkbox"
                                                                                    class="custom-control-input">
                                                                                <label class="custom-control-label"
                                                                                    for="foraneo">Foráneo</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="mb-2">
                                                                <input type="text" placeholder="Especifique"
                                                                    class="form-control form-control-sm text-uppercase"
                                                                    id="especifique" name="especifique">
                                                            </div>  --}}
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <br>
                                                <br>

                                                <div class="row">
                                                    <div class="col-12 mb-2">
                                                        <div style="margin-top: -10px; margin-bottom: -10px">
                                                            <h6 class="text-bold">Descripción <span style="color:red;">*</span></h6>
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="form-group">
                                                                        <div>
                                                                            <textarea id="descripcion" required name="descripcion" class="form-control text-uppercase"
                                                                            rows="10" style="height: auto !important;"
                                                                            placeholder="Descripción del Proyecto"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row" style="margin: 3rem 10px; text-align: center;">
                                            <p class="text-sm font-semibold col">“ Los archivos de Renders, Planos y Fotos” proporcionados por el cliente deben subirse en la pestaña de Documentos dentro del ticket correspondiente.</p>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="documentos-ticket" role="tabpanel"
                                        aria-labelledby="documentos-ticket-tab">

                                        {{-- <button onclick="modal_upDocsEstr()" type="button" id="agregarDocumento"
                                            data-target="modal" class="btn-flotante button btn btn-primary"
                                            title="Agregar Documentos">
                                            <i class="fas fas fa-plus-circle"></i>
                                        </button> --}}

                                        @component('sections.DocumentosEstr')
                                        @endcomponent
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <style>
        .swal2-select-custom {
            min-width: 50%;
            max-width: 100%;
            padding: .375em .625em;
            font-size: 1.125em;
            min-width: 100% !important;
            background-color: #fff !important;
            border: 1px solid #2d2d2d !important;
        }
    </style>
</div>
@include('estructural.mod_UpDocsEstr')
@include('modals.mod_mailDocs')
@include('modals.visorPDF')
