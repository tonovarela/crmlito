@extends('layout.master') @section('content')
    @include('partials.loading')
    <div id="formEditElement" class="col-12 mt-2">
        <form method="post" role="form" action="{{ route('elementos.update', $dto->Elementos['idElementoP']) }}"
            class="form-horizontal" id="form-EPEdit" lang="en-150">

            {{ csrf_field() }} {{ method_field('POST') }}
            <div class="rounded bg-gray">
                <div class="row mb-2 mt-1">
                    <div class="col-sm-6">
                        <h6 class="text-bold pt-1 pl-1">Editar Elemento Presupuestado</h6>
                    </div>

                </div>
            </div>

            <div>

                <div class="row">

                    <div id="infGeneral1" class="col-lg-12 mt-3 pb-2 " style="margin-top: 0% !important;">
                        <div class="col-lg-12 col-sm-12 mb-1">
                            <div class="input-group input-group-sm"><input type="text"
                                    value="{{ $dto->Elementos['idElementoP'] }}" id="idElementoP1" hidden>
                                <h6 class="text-right text-bold pr-3 pt-1" id="idElementoP">&nbsp;EP-
                                    @isset($dto->Elementos) {{ $dto->Elementos['idElementoP'] }}
                                    @else
                                        {{ $folio }} @endif

                                    </h6>
                                    <label class="mt-2" for="nomElementoP">Elemento Presupuestado &nbsp;</label>

                                    <input type="text" class="form-control col-md-8 col-lg-8 col-xl-8" id="nomElementoP"
                                        name="nomElementoP" autocomplete="off" placeholder="Requerido.."
                                        value="@isset($dto->Elementos){{ $dto->Elementos['nomElementoP'] }} @endif" required>
                            <div class="input-group-append col-md-5 col-lg-3 col-xl-2">
                                <span class="col-lg-4 center">
                                    <a class="btn btn-secondary btn-edit" onclick="regresar()">Regresar</a></span>
                                <span class="col-lg-4 ml-1"><button id="btnSave" type="submit"class="btn btn-primary btn-edit">Guardar</button></span>
                                <span class="col-lg-2"><a id="print" class="btn btn-success btn-edit" href="../../api/pdf/generar/{{ $dto->Elementos['idElementoP'] }}" target="_blank"><i class="fas fa-print"></i></a></span>
                            </div>

                            <a id="clonarE" onclick="clonarElemento({{ $dto->Elementos['idElementoP'] }})" type="button"
                            class="btn-flotante button btn btn-primary-clone" title="Clonar Elemento">
                            <i><img src="{{ asset('img/clon.png') }}" width="28"></i>
                            </a>
    
                        </div>
                    </div>

                    <div class="input-group input-group-sm">

                        <div class="col-1 mt-2">
                            <div class="form-group ">
                                <label for="tipo_item ">Tipo Item</label>
                            </div>
                        </div>
                        <div class="col-5 ">
                            <div class="form-group ">
                                <select class="card-primary form-control  select2 sltTipoItem select2-height" style="width: 100%; " id="idTipoItem" name="idTipoItem">
                                        <option value="" selected disabled>Seleccionar...</option>
                                        @foreach ($dto->combo_tipoitem as $TipoItem)
                                        @if ($TipoItem->idTipoItem == $dto->Elementos['idTipoItem'])
                                        <option value="{{ $TipoItem->idTipoItem }}" selected>{{ $TipoItem->descTipoItem }}</option>
                                        @else
                                        <option value="{{ $TipoItem->idTipoItem }}">{{ $TipoItem->descTipoItem }}</option>
                                        @endif
                                        
                                        @endforeach 
                                  
                                    </select>
                            </div>
                        </div>

                        <div class="col-2 mt-2 text-center">
                            <div class="form-group">
                                <label for="numPresupuestoMTC">Num Presupuesto</label>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <!-- <label for="numPresupuestoMTC">Num Presupuesto</label> -->
                                <div class="input-group">
                                    <input id="numPresupuestoMTC" name="numPresupuestoMTC" type="text" class="form-control" placeholder="Num Presupuesto Metrics" autocomplete="off" value="@isset($dto->Elementos){{ $dto->Elementos['numPresupuestoMTC'] }}@endif">

                                </div>
                            </div>
                        </div>
                        <div class="col-2 mt-2">
                            <div class="from-group text-center">
                                <div class="custom-control custom-switch ustom-switch-on-success mt-2 tbjoNvo">
                                    <!-- <input type="checkbox" class="custom-control-input checkForm" id="esNuevo" name="esNuevo" checked="true"> -->
                                    @isset($dto->Elementos)@if ($dto->Elementos['esNuevo'] = 1)
                                    <input type="checkbox" class="custom-control-input checkForm" id="esNuevo" name="esNuevo" checked="true"> @else <input type="checkbox" class="custom-control-input checkForm" id="esNuevo" name="esNuevo"> @endif @endif
                                    <label class="custom-control-label" for="esNuevo">Trabajo Nuevo</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- AQUI ESPECIFICACIONES -->
                <div id="tabGeneralEP" class="col-lg-12 mt-3 pb-2 " style="margin-top: 0% !important;">
                    <ul class="nav nav-tabs col-12" id="custom-content-below-tab" role="tablist">
                        <li class="nav-item col-4">
                            <a class="nav-link active" id="tab_1" data-toggle="pill" href="#tab_infGeneral" role="tab" aria-controls="custom-content-below-home" aria-selected="true">General</a>
                        </li>
                        <li class="nav-item col-4">
                            <a class="nav-link" id="tab_2" data-toggle="pill" href="#tab_Especifiacaciones" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Especificaciones</a>
                        </li>
                        <li class="nav-item col-4">
                            <a class="nav-link " id="tab_3" data-toggle="pill" href="#tab_Notas" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Notas</a>
                        </li>
                    </ul>
                </div>
                <div class="col-12 text-center tab-content" role="tabpanel" aria-labelledby="custom-content-below-home-tab" id="tbsElementosPre">
                    <div id="tab_infGeneral" class="col-lg-12 mt-3 pb-2 tab-pane fade show active " style="margin-top: 0% !important;">
                        <div class="rounded bg-gray">
                            <div class="row mb-2 mt-1">
                                <div class="col-sm-12">
                                    <h6 class="text-bold pt-1 pl-1"> Información General</h6>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="cant1">Cantidad 1</label>
                                    <input type="number" class="form-control" id="cant1" name='cant1' step="1" value="@isset($dto->Elementos){{ round($dto->Elementos['cant1']) }}@endif" required hidden>
                                    <input type="text" class="form-control" id="cant1_com" name='cant1_com' step="1" value="@isset($dto->Elementos){{ number_format($dto->Elementos['cant1']) }}@endif" required>

                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="cant2">Cantidad 2</label>
                                    <input type="number" class="form-control" id="cant2" name='cant2' step="1" value="@isset($dto->Elementos){{ round($dto->Elementos['cant2']) }}@endif" hidden>
                                    <input type="text" class="form-control" id="cant2_com" name='cant2_com' step="1" value="@isset($dto->Elementos){{ number_format($dto->Elementos['cant2']) }}@endif">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="cant3">Cantidad 3</label>
                                    <input type="number" class="form-control" id="cant3" name='cant3' step="1" value="@isset($dto->Elementos){{ round($dto->Elementos['cant3']) }}@endif" hidden>
                                    <input type="text" class="form-control" id="cant3_com" name='cant3_com' step="1" value="@isset($dto->Elementos){{ number_format($dto->Elementos['cant3']) }}@endif">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="cant4">Cantidad 4</label>
                                    <input type="number" class="form-control" id="cant4" name='cant4' step="1" value="@isset($dto->Elementos){{ round($dto->Elementos['cant4']) }}@endif" hidden>
                                    <input type="text" class="form-control" id="cant4_com" name='cant4_com' step="1" value="@isset($dto->Elementos){{ number_format($dto->Elementos['cant4']) }}@endif">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha y Hora Limite Cotizacion</label>
                                    <div class="input-group">
                                        @if ($dto->Elementos['fecTopeCot'] == null)
                                        <input type="text" class="form-control datetimepicker-input" id="fecTopeCot" name="fecTopeCot" />
                                        @else
                                        <input type="text" class="form-control datetimepicker-input" id="fecTopeCot" name="fecTopeCot" value="@isset($dto->Elementos){{ \Carbon\Carbon::parse($dto->Elementos['fecTopeCot'])->format('d/m/Y H:i') }}  @endif" />
                                        @endif
                                        <div class="input-group-append">
                                            <label class="labelCalendar" for="fecTopeCot">
                                                <div class="divCalendar input-group-text"><i class="fa fa-calendar"></i>
                                                </div>
                                            </label>
                                </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha Req. de Entrega Producto</label>
                                    <div class="input-group date" id="reservationdate4">
                                        @if ($dto->Elementos['fecTopeCot'] == null)
                                        <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate4" placeholder="(Requerido) dd-mm-aaaa" id="fecReqEntProd" name="fecReqEntProd" />
                                        @else
                                        <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate4" placeholder="(Requerido) dd-mm-aaaa" id="fecReqEntProd" name="fecReqEntProd" value=" @isset($dto->Elementos){{ \Carbon\Carbon::parse($dto->Elementos['fecReqEntProd'])->format('d/m/Y') }} @endif" />
                                        @endif
                                        <div class="input-group-append" data-target="#reservationdate4" data-toggle="datetimepicker">
                                            <label class="labelCalendar" for="fecReqEntProd">
                                                <div class="divCalendar input-group-text"><i class="fa fa-calendar"></i>
                                                </div>
                                            </label>
                                </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="PrecioObjvo">Precio Objetivo</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <select class="form-control" id='MonedaPrecioObjvo' name='MonedaPrecioObjvo' style="width: 100%;padding-right: 0px;padding-left: 0px;">
                                                <option selected="selected" value='MXN' >$(MXN)</option>
                                                <option value='USD' >$(USD)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="money" class="form-control" id="preObjetivo" name="preObjetivo" autocomplete="off" value="@isset($dto->Elementos){{ number_format($dto->Elementos['preObjetivo']) }}@endif">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="monpreCompetencia">Precio Competencia</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <select class="form-control" id='monpreCompetencia' name='monpreCompetencia' style="width: 100%;padding-right: 0px;padding-left: 0px;">
                                                <option selected="selected" value='MXN' >$(MXN)</option>
                                                <option value='USD' >$(USD)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="money" class="form-control" id="preCompetencia" name="preCompetencia" autocomplete="off" value="@isset($dto->Elementos){{ number_format($dto->Elementos['preCompetencia']) }}@endif">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nomCompetencia">Nombre Competencia</label>
                                    <input type="text" class="form-control" id="nomCompetencia" name="nomCompetencia" autocomplete="off" value=" @isset($dto->Elementos){{ $dto->Elementos['nomCompetencia'] }} @endif">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fecRegistro">Fecha Creación por:</label>
                                    <input type="text" class="form-control text-center" id="fecRegistro" name='fecRegistro' value=" @isset($dto->Elementos){{ \Carbon\Carbon::parse($dto->Elementos['fecRegistro'])->format('d/m/Y H:i') }} @endif" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ModifPor">Úlitma modificación por:</label>
                                    <input type="text" class="form-control text-center" id="ModifPor" name='ModifPor' value=" @if ($dto->Elementos['idUsuarioEdit'] != ''){{ $dto->Elementos['idUsuarioEdit'] }}@else {{ $dto->Elementos['NombreUsuario'] }} @endif" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="idEstadoItem">Estado</label>
                                    <select class="form-control" id='idEstadoItem' name='idEstadoItem' style="width: 100%;" di>
                                        <option value='0' >Selecionar...</option>
                                        @foreach ($dto->combo_estadoitem as $estadoitem)
                                        @if ($estadoitem->idEstadoItem == $dto->Elementos['idEstadoItem'])
                                        <option value="{{ $estadoitem->idEstadoItem }}" selected>{{ $estadoitem->descEstadoItem }}</option>
                                        @else
                                        <option value="{{ $estadoitem->idEstadoItem }}">{{ $estadoitem->descEstadoItem }}</option>
                                        @endif
                                        
                                        @endforeach 
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="rounded bg-gray">
                            <div class="row mb-2 mt-1">
                                <div class="col-sm-12">
                                    <h6 class="text-bold pt-1 pl-1">Condiciones de Venta</h6>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="porcMargen">Margen Sugerido (%)</label>
                                    <input type="number" class="form-control text-center" id="porcMargen" name='porcMargen' value="{{ number_format($dto->Elementos['porcMargen']) }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="porcFinanciamiento">Financiamiento (%)</label>
                                    <input type="number" class="form-control text-center" id="porcFinanciamiento" name='porcFinanciamiento' value="{{ number_format($dto->Elementos['porcFinanciamiento']) }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="porcComisionInt">Comisión Ext/SAAM(%)</label>
                                    <input type="number" class="form-control text-center" id="porcComisionInt" name='porcComisionInt' value="{{ number_format($dto->Elementos['porcComIsionInt']) }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="porcComisionExt">Comisión Consultor(%)</label>
                                    <input type="number" class="form-control text-center" id="porcComisionExt" name='porcComisionExt' value="{{ number_format($dto->Elementos['porcComisionExt']) }}">
                                </div>
                            </div>
                        </div>
                        <div class="rounded bg-gray">
                            <div class="row mb-2 mt-1">
                                <div class="col-sm-12">
                                    <h6 class="text-bold pt-1 pl-1">Especificaciones Técnicas</h6>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="preprensa">Preprensa</label>
                                </div>
                            </div>

                            <div class="col-md-7">
                                <div class="form-group">
                                    <select class="input-group select2 sltTipoPreprensa" style="width: 100%;" id="idTipoPreprensa" name="idTipoPreprensa">
                                        <option value='0' selected="selected" >Seleccionar...</option>
                                        @foreach ($dto->combo_pp as $pp)
                                        @if ($pp->idTipoPP == $dto->Elementos['idTipoPreprensa'])
                                        <option value="{{ $pp->idTipoPP }}" selected>{{ $pp->descTipoPP }}</option>
                                        @else
                                        <option value="{{ $pp->idTipoPP }}">{{ $pp->descTipoPP }}</option>
                                        @endif
                                        @endforeach 
                                    </select>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="from-group">
                                    <div class="custom-control custom-switch ustom-switch-on-success mt-4 tbjoNvo">
                                        @if (number_format($dto->Elementos['conRetoque']) > 0)
                                        <input type="checkbox" class="custom-control-input checkForm" id="conRetoque" name="conRetoque" checked="true"> @else
                                        <input type="checkbox" class="custom-control-input checkForm" id="conRetoque" name="conRetoque"> @endif
                                        <label class="custom-control-label" for="conRetoque">Retoque de Fotos</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="rounded bg-gray">
                            <div class="row mb-2 mt-1">
                                <div class="col-sm-12">
                                    <h6 class="text-bold pt-1 pl-1">Distribución</h6>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="distribucion">Distribución</label>
                                </div>
                            </div>

                            <div class="col-md-7">
                                <div class="form-group">
                                    <select class="input-group select2 sltidTipoDistribucion" style="width: 100%;" id="idTipoDistribucion" name="idTipoDistribucion">
                                        <option value='0' selected="selected" disabled>Seleccionar...</option>
                                        @foreach ($dto->combo_tipodistribucion as $td)
                                        @if ($td->idTipoDistribucion == $dto->Elementos['idTipoDistribucion'])
                                        <option value="{{ $td->idTipoDistribucion }}" selected>{{ $td->descTipoDistribucion }}</option>
                                        @else
                                        <option value="{{ $td->idTipoDistribucion }}">{{ $td->descTipoDistribucion }}</option>
                                        @endif
                                        @endforeach 

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="from-group">
                                    <div class="custom-control custom-switch ustom-switch-on-success mt-4 ">
                                        @if (number_format($dto->Elementos['conFullfilment']) > 0)
                                        <input type="checkbox" class="custom-control-input checkForm" id="conFullfilment" name="conFullfilment" checked="true"> @else
                                        <input type="checkbox" class="custom-control-input checkForm" id="conFullfilment" name="conFullfilment"> @endif
                                        <label class="custom-control-label" for="conFullfilment">Fullfilment</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row rounded bg-gray" style="margin-bottom: 0px;margin-top: 5px;">
                            <div class="col-sm-12">
                                <h6 class="text-bold pt-1 pl-1">Encuadernado</h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="idTipoEncuadernado">Encuadernado</label>
                                    <select class="input-group select2 sltidTipoEncuadernado" style="width: 100%;" id="idTipoEncuadernado" name="idTipoEncuadernado">
                                        <option value='0' selected="selected" disabled>Seleccionar...</option>
                                        @foreach ($dto->combo_tipoencuadernado as $te)
                                        @if ($te->idTipoEncuadernado == $dto->Elementos['idTipoEncuadernado'])
                                        <option value="{{ $te->idTipoEncuadernado }}" selected>{{ $te->descTipoEncuadernado }}</option>
                                        @else
                                        <option value="{{ $te->idTipoEncuadernado }}">{{ $te->descTipoEncuadernado }}</option>
                                        @endif
                                        @endforeach 
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="obsEncuadernado">Observaciones</label>
                                    <input type="text" class="form-control" id="obsEncuadernado" name="obsEncuadernado" value=" @isset($dto->Elementos){{ $dto->Elementos['obsEncuadernado'] }}@endif">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="otroEncuadernado">Otro</label>
                                    <input type="text" class="form-control" id="otroEncuadernado" name="otroEncuadernado" value=" @isset($dto->Elementos){{ $dto->Elementos['otroEncuadernado'] }}@endif">
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div id="infGeneral" class="col-lg-12 col-sm-12 mt-2">


                                <div class="row col-12 rounded bg-gray" style="margin-bottom: 0px;margin-top: 5px;">
                                    <div class="col-sm-12">
                                        <h6 class="text-bold pt-1 pl-1">Empaque Especial</h6>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-4 text-left">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input check-empaque" type="checkbox"
                                                    id="idTipoEmpaque_1" name="idTipoEmpaque_1" value="1">
                                                <!-- aqui falta ciclo for -->
                                                <label for="idTipoEmpaque_1" class="custom-control-label">Retractilado</label>
                                            </div>
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input check-empaque" type="checkbox"
                                                    id="idTipoEmpaque_2" name="idTipoEmpaque_2" value="2">
                                                <label for="idTipoEmpaque_2" class="custom-control-label">Embolsado</label>
                                            </div>
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input check-empaque" type="checkbox"
                                                    id="idTipoEmpaque_6" name="idTipoEmpaque_6" value="6">
                                                <label for="idTipoEmpaque_6" class="custom-control-label">Caja de Cartón</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 text-left">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input check-empaque" type="checkbox"
                                                    id="idTipoEmpaque_7" name="idTipoEmpaque_7" value="7">
                                                <label for="idTipoEmpaque_7" class="custom-control-label">Tubo de cartón
                                                </label>
                                            </div>
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input check-empaque" type="checkbox"
                                                    id="idTipoEmpaque_3" name="idTipoEmpaque_3" value="3">
                                                <label for="idTipoEmpaque_3" class="custom-control-label">Emplayado</label>
                                            </div>
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input check-empaque" type="checkbox"
                                                    id="idTipoEmpaque_4" name="idTipoEmpaque_4" value="4">
                                                <label for="idTipoEmpaque_4" class="custom-control-label">Entarimado</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 text-left">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input check-empaque" type="checkbox"
                                                    id="idTipoEmpaque_5" name="idTipoEmpaque_5" value="5">
                                                <label for="idTipoEmpaque_5" class="custom-control-label">Termosellado</label>
                                            </div>
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input check-empaque" type="checkbox"
                                                    id="idTipoEmpaque_8" name="idTipoEmpaque_8" value="8">
                                                <label for="idTipoEmpaque_8" class="custom-control-label">otro</label>
                                            </div>
                                            <div class="form-group ">

                                                <input class="custom-controlt " type="text" id="obsEmpaque"
                                                    name="obsEmpaque">
                                                <label for="obsEmpaque">Observaciones</label>

                                            </div>

                                        </div>
                                    </div>

                                </div>
                                <div class="row col-12 rounded bg-gray" style="margin-bottom: 0px;margin-top: 5px;">
                                    <div class="col-sm-12">
                                        <h6 class="text-bold pt-1 pl-1">Acabados Generales</h6>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4 text-left">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input check-AcabadosGral" type="checkbox"
                                                    id="idTipoAcabadoGral_1" name="idTipoAcabadoGral_1" value="1">
                                                <label for="idTipoAcabadoGral_1" class="custom-control-label">Cinta doble
                                                    cara</label>
                                            </div>
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input check-AcabadosGral" type="checkbox"
                                                    id="idTipoAcabadoGral_3" name="idTipoAcabadoGral_3" value="3">
                                                <label for="idTipoAcabadoGral_3"
                                                    class="custom-control-label">Encapasulado</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 text-left">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input check-AcabadosGral" type="checkbox"
                                                    id="idTipoAcabadoGral_2" name="idTipoAcabadoGral_2" value="2">
                                                <label for="idTipoAcabadoGral_2" class="custom-control-label">Ruteado</label>
                                            </div>
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input check-AcabadosGral" type="checkbox"
                                                    id="idTipoAcabadoGral_4" name="idTipoAcabadoGral_4" value="4">
                                                <label for="idTipoAcabadoGral_4" class="custom-control-label">Ojillos</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 text-left">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input check-AcabadosGral" type="checkbox"
                                                    id="idTipoAcabadoGral_5" name="idTipoAcabadoGral_5" value="5">
                                                <label for="idTipoAcabadoGral_5" class="custom-control-label">otro</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>




                            </div>

                        </div>
                    </div>




                    <div id="tab_Especifiacaciones" class="col-lg-12 mt-3 pb-2 tab-pane fad " role="tabpanel"
                        aria-labelledby="custom-content-below-home-tab">
                        @include('sections.EP_tblMedidas')
                    </div>
                    <!--  SECCION DE NOTAS TICKET NO. 8529-->

                    <div id="tab_Notas" class="col-lg-12 mt-3 pb-2 tab-pane fad " role="tabpanel"
                        aria-labelledby="custom-content-below-home-tab">
                        @include('sections.EP_Notas')
                    </div>

                </div>

        </div>
        </div>


        </form>
        </div>
        <script src=" {{ asset('js/elementos_presupuestados.js?n=1') }} " defer></script>
        <script src=" {{ asset('js/elementos_presupuestados_edit.js?n=1') }} " defer></script>
        @include('modals.visorPDF')
        @include('elementos_presupuestados.mod_Paper')
    @endsection
