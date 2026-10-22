<div class="modal fullscreen-modal fade" id="modal-create-envio" tabindex="-1" role="dialog" data-backdrop="static"
    data-keyboard="false" aria-labelledby="myModalLabel" aria-hidden="true">
    @include('partials.loadingModal')
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="col-12 mt-2">
                    <form method="post" class="form-horizontal" data-toggle="validator">
                        {{ csrf_field() }} {{ method_field('POST') }}
                        <input type="hidden" value="{{ $id_usuario }}" name="idUsuario" id="idUsuario">
                        <input type="hidden" value="{{ $id_usuario }}" name="idUsuarioUltimoCambio"
                            id="idUsuarioUltimoCambio">
                        <input type="hidden" name="idOportunidad" id="idOportunidad">
                        <input type="hidden" id="estatus">
                        <input type="hidden" id="idRol" value="{{ $rol }}">
                        <input type="hidden" id="aprobada" name="aprobada">
                        <input type="hidden" id="agente_id" value="{{ $agente_id }}">
                        <div id="tabGeneralEnv" class="card-body fullscreen-card">
                            <div class="rounded bg-gray">
                                <div class="row mb-2">
                                    <div class="col-12 text-center">
                                        <h6 id="tituloEnvío" class="text-bold pt-1 pl-1">Agregar Envío</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-1">
                                <div class="input-group input-group-sm">
                                    <h6 id="folio" class="text-right text-bold text-red pr-1 pt-2">
                                        &nbsp;No. - &nbsp;
                                    </h6>
                                    <label class="mt-2" for="nomOportunidad">Envío &nbsp;</label>
                                    <input type="text" class="form-control col-md-7 col-lg-8 col-xl-9 text-uppercase"
                                        id="nomOportunidad" name="nomOportunidad" placeholder="Requerido"
                                        autocomplete="off" autofocus>
                                    <div class="input-group-append col-md-5 col-lg-4 col-xl-3">
                                        <span id="sbtnCancelarkEnv" class="col-md-4 center">
                                            <a id="btnCancelar" href="#" onclick="CancelarEnvio(), loaderON()"
                                                class="btn btn-danger btn-sm btn-block">Cancelar</a>
                                        </span>
                                        <span id="sbtnSaveEnv" class="col-md-4"><button id="btnSaveEnv" type="submit"
                                                class="btn btn-primary btn-sm btn-block" onclick="closeMEnvio()"
                                                disabled style="pointer-events: none">Guardar</button></span>
                                        <span id="sbtnBackEnv" class="col-md-4 center">
                                            <button id="btnBackEnv" type="button"
                                                class="btn btn-secondary btn-sm btn-block" data-dismiss="modal"
                                                onclick="closeMEnvio()"></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <ul class="nav nav-tabs col-12" id="custom-content-below-tab" role="tablist">
                                    <li class="nav-item col-4">
                                        <a class="nav-link active" id="general-envio-tab" data-toggle="pill"
                                            style="cursor: pointer" onclick="mostrarGeneralEnvio()" role="tab"
                                            aria-controls="general-envio-tab" aria-selected="true">General</a>
                                    </li>
                                    @if ($rol != 1014)
                                        <li class="nav-item col-4">
                                            <a class="nav-link" id="documentos-envio-tab"
                                                onclick="mostrarDocumentosEnvio()" data-toggle="pill" role="tab"
                                                aria-controls="documentos-envio-tab" aria-selected="false"
                                                style="cursor: pointer">Documentos</a>
                                        </li>
                                    @endif
                                </ul>
                                <div id="scrollbarTabsModalRoundEnv" class="tab-content">
                                    <div class="tab-pane fade show active" id="general-envio" role="tabpanel"
                                        aria-labelledby="custom-content-below-home-tab">

                                        <div id="isCancelled" class="canceling-cont" style="display: none">
                                            <div class="canceling">CANCELADA</div>
                                        </div>

                                        <div class="row">
                                            <div id="info_general" class="col-lg-8 col-sm-12 mt-2">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="idCliente">Cliente</label>
                                                            <select id="idCliente" name="idCliente"
                                                                class="form-control select2-height"
                                                                style="width: 100%; text-transform: uppercase">
                                                                <option selected disabled>Requerido</option>

                                                                {{-- @foreach ($combo_clientes as $cliente)
                                                                        <option value="{{ $cliente->idCliente }}">
                                                                            {{ $cliente->razonsocial }}
                                                                        </option>
                                                                    @endforeach --}}

                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="idOportunidadRelacion">Oportunidad</label>
                                                            <select id="idOportunidadRelacion"
                                                                name="idOportunidadRelacion" class="form-control"
                                                                style="width: 100%;">
                                                                <option selected disabled>Oportunidad</option>
                                                                {{-- @foreach ($oportunidades as $oportunidad)
                                                                <option value="{{ $oportunidad->idOportunidad }}">
                                                                    {{ $oportunidad->nomOportunidad }}
                                                                </option>
                                                                @endforeach --}}
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="idtipo">Tipo de Envío</label>
                                                            <select id="idtipo" name="idtipo"
                                                                class="form-control" style="width: 100%;">
                                                                <option selected disabled>Requerido</option>
                                                                @foreach ($tipos as $tipo)
                                                                    <option value="{{ $tipo->idTipoOportunidad }}">
                                                                        {{ $tipo->desTipoOportundidad }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <label for="idEstatus">Estatus</label>

                                                <div class="row" id="listEstatus">
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <div class="custom-control custom-radio">
                                                                <input class="custom-control-input" type="radio"
                                                                    id="idStatusPre" name="idStatus" value="1">
                                                                <label for="idStatusPre"
                                                                    class="custom-control-label">Pre
                                                                    registro</label>
                                                            </div>
                                                            <div class="custom-control custom-radio">
                                                                <input class="custom-control-input" type="radio"
                                                                    id="idStatusSolCot" name="idStatus"
                                                                    value="2">
                                                                <label for="idStatusSolCot"
                                                                    class="custom-control-label">Solicitud de
                                                                    cotización</label>
                                                            </div>
                                                            {{-- <div class="custom-control custom-radio">
                                                                <input class="custom-control-input" type="radio"
                                                                    id="idStatusListoCot" name="idStatus" value="3">
                                                                <label for="idStatusListoCot"
                                                                    class="custom-control-label">Listo
                                                                    p/cotizar</label>
                                                            </div> --}}
                                                            {{-- <div class="custom-control custom-radio">
                                                                <input class="custom-control-input" type="radio"
                                                                    id="idStatusCot" name="idStatus" value="4">
                                                                <label for="idStatusCot"
                                                                    class="custom-control-label">Cotizando</label>
                                                            </div> --}}
                                                        </div>
                                                    </div>

                                                    {{-- <div class="col-3">
                                                        <div class="form-group">
                                                            <div class="custom-control custom-radio">
                                                                <input class="custom-control-input" type="radio"
                                                                    id="idStatusVoBo" name="idStatus" value="5">
                                                                <label for="idStatusVoBo"
                                                                    class="custom-control-label">Espera Vo.
                                                                    Bo.</label>
                                                            </div>
                                                        </div>
                                                    </div> --}}

                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <div class="custom-control custom-radio">
                                                                <input class="custom-control-input" type="radio"
                                                                    id="idStatusCotTerm" name="idStatus"
                                                                    value="6">
                                                                <label for="idStatusCotTerm"
                                                                    class="custom-control-label">Cotización
                                                                    Terminada</label>
                                                            </div>
                                                        </div>
                                                        <div class="mt-1 text-center">
                                                            <a id="btnDescargarCot" href="#"
                                                                onclick="descargarCot()"
                                                                class="btn btn-info btn-sm disabled rounded"><i
                                                                    class="fas fa-download"></i>
                                                                Descargar Cotización
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <div class="custom-control custom-radio">
                                                                <input class="custom-control-input" type="radio"
                                                                    id="idStatusCotAprob" name="idStatus"
                                                                    value="7">
                                                                <label for="idStatusCotAprob"
                                                                    class="custom-control-label">Cotización
                                                                    Aprobada</label>
                                                            </div>
                                                            <div class="custom-control custom-radio">
                                                                <input class="custom-control-input" type="radio"
                                                                    id="idStatusCotCanc" name="idStatus"
                                                                    value="8">
                                                                <label for="idStatusCotCanc"
                                                                    class="custom-control-label">Cotización
                                                                    Cancelada</label>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="idCotizacionAutorizada">No. Cotizacion
                                                                    Autorizada</label>
                                                                <select id="idCotizacionAutorizada"
                                                                    name="idCotizacionAutorizada" class="form-control"
                                                                    style="width: 100%;">
                                                                    <option selected disabled>No. Cotizacion Autorizada
                                                                    </option>
                                                                    {{-- @foreach ($folios_envios as $folio)
                                                                    <option value="{{ $folio->folioCotizacion }}">
                                                                        {{ $folio->folioCotizacion }}
                                                                    </option>
                                                                    @endforeach --}}
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="numOrdenMTC">No. Orden Metrics</label>
                                                            <input type="text" class="form-control text-uppercase"
                                                                id="numOrdenMTC" name="numOrdenMTC"
                                                                placeholder="No. Orden Metrics" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="totDestinos">Total Destinos</label>
                                                            <input type="text" class="form-control"
                                                                id="totDestinos" name="totDestinos"
                                                                placeholder="Total Destinos" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="tamPieza">Tamaño Pieza</label>
                                                            <input type="text" class="form-control" id="tamPieza"
                                                                name="tamPieza" placeholder="Tamaño Pieza"
                                                                autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="pesoPieza">Peso Pieza</label>
                                                            <input type="text" class="form-control" id="pesoPieza"
                                                                name="pesoPieza" placeholder="Peso Pieza"
                                                                autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Unidad de Peso</label>
                                                            <select id="idUnidadPeso" name="idUnidadPeso"
                                                                class="form-control" style="width: 100%;">
                                                                <option selected disabled>Requerido</option>
                                                                @foreach ($unidad_medida as $unidad)
                                                                    <option value="{{ $unidad->idUnidadEnv }}">
                                                                        {{ $unidad->descUnidadEnv }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Equipos</label>
                                                            <select id="idEquipo" name="idEquipo"
                                                                class="form-control" style="width: 100%;">
                                                                <option selected disabled>Requerido</option>
                                                                @foreach ($equipos as $equipo)
                                                                    <option value="{{ $equipo->idEquipo }}">
                                                                        {{ $equipo->descEquipo }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="idAgente">Asignado a</label>
                                                            <select id="idAgente" name="idAgente"
                                                                class="form-control" style="width: 100%;">
                                                                <option selected disabled>Requerido</option>
                                                                @foreach ($combo_vendedores as $vendedor)
                                                                    <option value="{{ $vendedor->Id_Usuario }}">
                                                                        {{ $vendedor->Nombre }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="seccion_retencion" class="row">
                                                    <div class="col-md-4 text-center">
                                                        <div class="form-group">
                                                            <div class="custom-control custom-switch mt-4">
                                                                <input id="Retencion" name="Retencion"
                                                                    type="checkbox" class="custom-control-input">
                                                                <label class="custom-control-label"
                                                                    for="Retencion">Retenida</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <div class="form-group">
                                                                <label for="notaRetencion">Nota Retención</label>
                                                                <div class="input-group">
                                                                    <input id="notaRetencion" name="notaRetencion"
                                                                        type="text" class="form-control"
                                                                        placeholder="Nota Retención"
                                                                        autocomplete="off">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-1 mt-1 pl-2 pr-2">
                                                    <div class="rounded col-sm-12 bg-gray">
                                                        <h6 class="text-bold text-center pt-1">Relevancia del Servicio
                                                        </h6>
                                                    </div>
                                                    <div class="col-sm-3 text-center">
                                                        <div class="form-group">
                                                            <div class="custom-control custom-switch">
                                                                <input id="opcMasEconomico" name="opcMasEconomico"
                                                                    type="checkbox" class="custom-control-input">
                                                                <label class="custom-control-label"
                                                                    for="opcMasEconomico">Mas
                                                                    Económico</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 text-center">
                                                        <div class="form-group">
                                                            <div class="custom-control custom-switch">
                                                                <input id="opcMasRapido" name="opcMasRapido"
                                                                    type="checkbox" class="custom-control-input">
                                                                <label class="custom-control-label"
                                                                    for="opcMasRapido">Mas
                                                                    Rápido</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 text-center">
                                                        <div class="form-group">
                                                            <div class="custom-control custom-switch">
                                                                <input id="opcConSeguro" name="opcConSeguro"
                                                                    type="checkbox" class="custom-control-input">
                                                                <label class="custom-control-label"
                                                                    for="opcConSeguro">Con
                                                                    Seguro</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 text-center">
                                                        <div class="form-group">
                                                            <div class="custom-control custom-switch">
                                                                <input id="muestra" name="muestra" type="checkbox"
                                                                    class="custom-control-input">
                                                                <label class="custom-control-label"
                                                                    for="muestra">Muestra</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="seccion_estadisticos" class="row mb-1 pl-2 pr-2">
                                                    <div class="rounded col-sm-12 bg-gray mb-1">
                                                        <h6 class="text-bold text-center pt-1">Estadísticos Envíos</h6>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="fecCreacion">Fecha Alta</label>
                                                        <div class="input-group">
                                                            <input id="fecCreacion" name="fecCreacion" type="text"
                                                                class="form-control" autocomplete="off"
                                                                placeholder="dd/mm/aaaa" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="nombreUsuarioCreacion">Usuario Alta</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control"
                                                                id="nombreUsuarioCreacion"
                                                                name="nombreUsuarioCreacion"
                                                                placeholder="Usuario Alta" autocomplete="off"
                                                                readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="fecUltCambio">Fecha Ult. Cambio</label>
                                                        <div class="input-group">
                                                            <input id="fecUltCambio" type="text"
                                                                class="form-control" name="fecUltCambio"
                                                                autocomplete="off" placeholder="dd/mm/aaaa" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="idUsuarioUltCambio">Usuario Ult. Cambio</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control"
                                                                id="idUsuarioUltCambio" name="idUsuarioUltCambio"
                                                                placeholder="Usuario Ult. Cambio" autocomplete="off"
                                                                disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="tabla_retenciones" class="row mb-1">
                                                    <div class="col-md-12">
                                                        <label>Retenciones</label>
                                                        <table id="tblOporEnvRetenidas"
                                                            class="table table-hover table-striped nowrap compact cell-border row-border">
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="time_line" class="col-lg-4 col-sm-12 mt-2"
                                                style="border: 1px solid #ddd; border-radius: 10px">

                                                <div id="scrollbarTimeLine" class="row mt-2 mb-1">
                                                    <div class="col-12">
                                                        <div class="timeline mt-1">
                                                            <div>
                                                                <i id="prereg_i" class="fas fa-times bg-red"></i>
                                                                <div class="timeline-item">
                                                                    <h5 id="prereg_h5" class="timeline-header">
                                                                        <span id="prereg_span"><i id="prereg_span_i"
                                                                                class="fas fa-clock"></i>&nbsp;</span>
                                                                        <span style="float: right">Pre Registro</span>
                                                                    </h5>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <i id="solcot_i" class="fas fa-times bg-red"></i>
                                                                <div class="timeline-item">
                                                                    <h5 id="solcot_h5" class="timeline-header">
                                                                        <span id="solcot_span"><i id="solcot_span_i"
                                                                                class="fas fa-clock"></i>&nbsp;</span>
                                                                        <span style="float: right">Solicitud de
                                                                            Cotización</span>
                                                                    </h5>
                                                                </div>
                                                            </div>
                                                            {{-- <div>
                                                                <i id="listocot_i" class="fas fa-times bg-red"></i>
                                                                <div class="timeline-item">
                                                                    <h5 id="listocot_h5" class="timeline-header">
                                                                        <span id="listocot_span"><i
                                                                                class="fas fa-clock"></i>&nbsp;</span>
                                                                        <span style="float: right">Listo
                                                                            p/cotizar</span>
                                                                    </h5>
                                                                </div>
                                                            </div> --}}
                                                            {{-- <div>
                                                                <i id="cotizando_i" class="fas fa-times bg-red"></i>
                                                                <div class="timeline-item">
                                                                    <h5 id="cotizando_h5" class="timeline-header">
                                                                        <span id="cotizando_span"><i
                                                                                class="fas fa-clock"></i>&nbsp;</span>
                                                                        <span style="float: right">Cotizando</span>
                                                                    </h5>
                                                                </div>
                                                            </div> --}}
                                                            {{-- <div>
                                                                <i id="esperavobo_i" class="fas fa-times bg-red"></i>
                                                                <div class="timeline-item">
                                                                    <h5 id="esperavobo_h5" class="timeline-header">
                                                                        <span id="esperavobo_span"><i
                                                                                class="fas fa-clock"></i>&nbsp;</span>
                                                                        <span style="float: right">Espera VoBo</span>
                                                                    </h5>
                                                                </div>
                                                            </div> --}}
                                                            <div>
                                                                <i id="cotterm_i" class="fas fa-times bg-red"></i>
                                                                <div class="timeline-item">
                                                                    <h5 id="cotterm_h5" class="timeline-header">
                                                                        <span id="cotterm_span"><i
                                                                                class="fas fa-clock"></i>&nbsp;</span>
                                                                        <span style="float: right">Cotización
                                                                            Terminada</span>
                                                                    </h5>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <i id="resulfin_i" class="fas fa-times bg-red"></i>
                                                                <div class="timeline-item">
                                                                    <h5 id="resulfin_h5" class="timeline-header">
                                                                        <span id="resulfin_span"><i
                                                                                class="fas fa-clock"></i>&nbsp;</span>
                                                                        <span style="float: right">Resultado
                                                                            Final</span>
                                                                    </h5>
                                                                </div>
                                                            </div>
                                                            <div class="mb-5">
                                                                <i id="final_i" class="fas fa-clock bg-red"></i>
                                                            </div>
                                                            <div id="nota_cancelacion" style="display: none">
                                                                <label class="ml-5">Nota Cancelación :
                                                                </label>
                                                                <h6 class="text-red text-bold ml-5 text-small"
                                                                    id="nota_cancelacion_value"></h6>
                                                            </div>

                                                        </div>
                                                        <div id="infoEnvio" class="ml-3">
                                                            <h6 class="ml-2 text-bold">El departamento de envíos te
                                                                brindó el servicio adecuado para tu proyecto?</h6>
                                                            <div>
                                                                <div class="form-group">
                                                                    <div class="custom-control custom-radio">
                                                                        <input class="custom-control-input"
                                                                            type="radio" id="servAdecuadoSi"
                                                                            name="servAdecuado" value="SI">
                                                                        <label for="servAdecuadoSi"
                                                                            class="custom-control-label">Sí</label>
                                                                    </div>
                                                                    <div class="custom-control custom-radio">
                                                                        <input class="custom-control-input"
                                                                            type="radio" id="servAdecuadoNo"
                                                                            name="servAdecuado" value="NO">
                                                                        <label for="servAdecuadoNo"
                                                                            class="custom-control-label">No</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div id="opcionesNo" style="display: none">
                                                                <label class="ml-2">Elija una de las
                                                                    siguientes opciones:</label>
                                                                <div class="form-group">
                                                                    <div class="custom-control custom-radio">
                                                                        <input class="custom-control-input"
                                                                            type="radio" id="servAdecuadoNoFS"
                                                                            name="servAdecuadoNoOpciones"
                                                                            value="FALTA DE SEGUIMIENTO">
                                                                        <label for="servAdecuadoNoFS"
                                                                            class="custom-control-label">Falta de
                                                                            seguimiento</label>
                                                                    </div>
                                                                    <div class="custom-control custom-radio">
                                                                        <input class="custom-control-input"
                                                                            type="radio" id="servAdecuadoNoAtencion"
                                                                            name="servAdecuadoNoOpciones"
                                                                            value="EL TRATO/ATENCIÓN POR PARTE DEL PERSONAL DEL ÁREA NO FUE EL REQUERIDO">
                                                                        <label for="servAdecuadoNoAtencion"
                                                                            class="custom-control-label">El
                                                                            trato/atención por parte del personal del
                                                                            área no fue el requerido</label>
                                                                    </div>
                                                                    <div class="custom-control custom-radio">
                                                                        <input class="custom-control-input"
                                                                            type="radio" id="servAdecuadoNoTiempo"
                                                                            name="servAdecuadoNoOpciones"
                                                                            value="NO SE RECIBIÓ LA INFORMACIÓN EN TIEMPO Y FORMA">
                                                                        <label for="servAdecuadoNoTiempo"
                                                                            class="custom-control-label">No se recibió
                                                                            la información en tiempo y forma</label>
                                                                    </div>
                                                                    <div class="custom-control custom-radio">
                                                                        <input class="custom-control-input"
                                                                            type="radio" id="servAdecuadoNoOtro"
                                                                            name="servAdecuadoNoOpciones"
                                                                            value="OTRO">
                                                                        <label for="servAdecuadoNoOtro"
                                                                            class="custom-control-label">Otro</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div id="infoFactura">
                                                                <h6 class="ml-2 text-bold">Capture la información de la
                                                                    factura del cliente</h6>
                                                                <div class="row ml-1">
                                                                    <div class="col-6">
                                                                        <div class="input-group">
                                                                            <input type="text" class="form-control"
                                                                                id="numFactura" name="numFactura"
                                                                                placeholder="# Factura"
                                                                                autocomplete="off">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <div class="input-group">
                                                                            <input type="text" class="form-control"
                                                                                id="importeFactura"
                                                                                name="importeFactura"
                                                                                placeholder="Importe Factura"
                                                                                autocomplete="off">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- /.col -->
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="documentos-envio" role="tabpanel"
                                        aria-labelledby="documentos-envio-tab">
                                        @component('sections.DocumentosEnvios')
                                        @endcomponent
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('modals.visorPDF')
@include('clientes.mod_UpDocs')
