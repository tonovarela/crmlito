@extends('layout.master')@extends('partials.loading') @section('content')
    <div id="formCreateElement" class="col-12 mt-2">
        <form method="post" role="form" action="{{ route('elementos.store') }}" class="form-horizontal" id="frm-EPcreate"
            data-toggle="validator">
            {{ csrf_field() }} {{ method_field('POST') }}
            <div class="rounded bg-gray">
                <div class="row mb-2 mt-1">
                    <div class="col-sm-6">
                        <h6 class="text-bold pt-1 pl-1">Nuevo Elemento Presupuestado</h6>
                    </div>

                </div>
            </div>

            <div>

                <div class="row">

                    <div id="infGeneral1" class="col-lg-12 mt-3 pb-2 " style="margin-top: 0% !important;">
                        <div class="col-lg-12 col-sm-12 mb-1">
                            <input type="hidden" name="idOportunidad" value="{{ $dto->idOportunidad }}">
                            <div class="input-group input-group-sm">
                                <h6 class="text-right text-bold pr-3 pt-1" id="idElementoP">&nbsp;EP-
                                    @isset($dto->Elementos) {{ $dto->ElementoP['idElementoP'] }}-
                                    @else
                                        {{ $dto->folio }} -
                                        @endif

                                    </h6>
                                    <label class="mt-2" for="nomElementoP">Elemento Presupuestado &nbsp;</label>

                                    <input type="text" class="form-control col-md-8 col-lg-9 col-xl-10" id="nomElementoP"
                                        name="nomElementoP" autocomplete="off" placeholder="Requerido.." value="">
                                    <div class="input-group-append col-md-4 col-lg-3 col-xl-2">
                                        <span class="col-lg-6 center">
                                            <a onclick="regresar()" class="btn btn-secondary btn-">Regresar</a></span>
                                        <span class="col-lg-6"><button id="btnSave" type="submit"
                                                class="btn btn-primary btn-">Guardar</button></span>

                                    </div>
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
                                        <select class="card-primary form-control  select2 select2-height" style="width: 100%; "
                                            id="idTipoItem" name="idTipoItem">
                                            <option value="" selected disabled>Seleccionar...</option>
                                            @foreach ($dto->combo_tipoitem as $TipoItem)
                                                <option value="{{ $TipoItem->idTipoItem }}">{{ $TipoItem->descTipoItem }}
                                                </option>
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
                                            <input id="numPresupuestoMTC" name="numPresupuestoMTC" type="text"
                                                class="form-control" placeholder="Num Presupuesto Metrics" autocomplete="off"
                                                value="">

                                        </div>
                                    </div>
                                </div>
                                <div class="col-2 mt-2">
                                    <div class="from-group text-center">
                                        <div class="custom-control custom-switch ustom-switch-on-success mt-2 tbjoNvo">
                                            <input type="checkbox" class="custom-control-input checkForm" id="esNuevo"
                                                name="esNuevo" checked="true">

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
                                    <a class="nav-link active" id="tab_1" data-toggle="pill" href="#tab_infGeneral" role="tab"
                                        aria-controls="custom-content-below-home" aria-selected="true">General</a>
                                </li>
                                <li class="nav-item col-4">
                                    <a class="nav-link" id="tab_2" data-toggle="pill" href="#tab_Especifiacaciones"
                                        role="tab" aria-controls="custom-content-below-profile"
                                        aria-selected="false">Especificaciones</a>
                                </li>
                                <!-- <li class="nav-item col-4">
                                    <a class="nav-link " id="tab_3" data-toggle="pill" href="#tab_Notas" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Notas</a>
                                </li> -->
                            </ul>
                        </div>
                        <div class="col-12 text-center tab-content" role="tabpanel"
                            aria-labelledby="custom-content-below-home-tab" id="tbsElementosPre">
                            <div id="tab_infGeneral" class="col-lg-12 mt-3 pb-2 tab-pane fade show active "
                                style="margin-top: 0% !important;">
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
                                            <input type="number" class="form-control" id="cant1" name='cant1' value=""
                                                step=".01" oninput="limitDecimalPlaces(event, 2)">

                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="cant2">Cantidad 2</label>
                                            <input type="number" class="form-control" id="cant2" name='cant2' value=""
                                                step=".01" oninput="limitDecimalPlaces(event, 2)">
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="cant3">Cantidad 3</label>
                                            <input type="number" class="form-control" id="cant3" name='cant3' value=""
                                                step=".01" oninput="limitDecimalPlaces(event, 2)">
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="cant4">Cantidad 4</label>
                                            <input type="number" class="form-control" id="cant4" name='cant4' value=""
                                                step=".01" oninput="limitDecimalPlaces(event, 2)">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Fecha y Hora Limite Cotizacion</label>
                                            <div class="input-group" id="reservationdatetime5">
                                                <input type="text" class="form-control datetimepicker-input" id="fecTopeCot"
                                                    name="fecTopeCot" />
                                                <div class="input-group-append" data-target="#reservationdatetime5"
                                                    data-toggle="datetimepicker">
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
                                            <div class="input-group" id="reservationdate4">
                                                <input type="text" class="form-control datetimepicker-input"
                                                    data-target="#reservationdate4" placeholder="(Requerido) dd-mm-aaaa"
                                                    id="fecReqEntProd" name="fecReqEntProd" />
                                                <div class="input-group-append" data-target="#reservationdate4"
                                                    data-toggle="datetimepicker">
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
                                                    <select class="form-control" id='MonedaPrecioObjvo'
                                                        name='MonedaPrecioObjvo'
                                                        style="width: 100%;padding-right: 0px;padding-left: 0px;">
                                                        <option selected="selected" value='MXN'>$(MXN)</option>
                                                        <option value='USD'>$(USD)</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="number" class="form-control" id="preObjetivo"
                                                        name="preObjetivo" autocomplete="off" value="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="monpreCompetencia">Precio Competencia</label>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <select class="form-control" id='monpreCompetencia'
                                                        name='monpreCompetencia'
                                                        style="width: 100%;padding-right: 0px;padding-left: 0px;">
                                                        <option selected="selected" value='MXN'>$(MXN)</option>
                                                        <option value='USD'>$(USD)</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="number" class="form-control" id="preCompetencia"
                                                        name="preCompetencia" autocomplete="off" value="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="nomCompetencia">Nombre Competencia</label>
                                            <input type="text" class="form-control" id="nomCompetencia" name="nomCompetencia"
                                                autocomplete="off" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="idEstadoItem">Estado</label>
                                            <select class="form-control" id='idEstadoItem' name='idEstadoItem'
                                                style="width: 100%;" di>
                                                <option value='0' disabled>Selecionar...</option>
                                                <option value=1 selected="selected">Base</option>
                                                <option value=2>Opción Adicional</option>
                                                <option value='0'>Cancelado</option>
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
                                            <input type="number" class="form-control" id="porcMargen" name='porcMargen'
                                                value="">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="porcFinanciamiento">Financiamiento (%)</label>
                                            <input type="number" class="form-control" id="porcFinanciamiento"
                                                name='porcFinanciamiento' value="">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="porcComsionInt">Comisión Ext/SAAM(%)</label>
                                            <input type="number" class="form-control" id="porcComsionInt"
                                                name='porcComisionInt' value="">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="porcComisionExt">Comisión Consultor(%)</label>
                                            <input type="number" class="form-control" id="porcComisionExt"
                                                name='porcComisionExt' value="">
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
                                            <select class="input-group select2" style="width: 100%;" id="idTipoPreprensa"
                                                name="idTipoPreprensa">
                                                <option selected="selected" disabled>Seleccionar...</option>
                                                @isset($dto->combo_pp) @foreach ($dto->combo_pp as $pp)
                                                    <option value="{{ $pp->idTipoPP }}">{{ $pp->descTipoPP }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="from-group">
                                            <div class="custom-control custom-switch ustom-switch-on-success mt-4 tbjoNvo">

                                                <input type="checkbox" class="custom-control-input checkForm" id="conRetoque"
                                                    name="conRetoque">

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
                                            <select class="input-group select2" style="width: 100%;" id="idTipoDistribucion"
                                                name="idTipoDistribucion">
                                                <option selected="selected" disabled>Seleccionar...</option>
                                                @foreach ($dto->combo_tipodistribucion as $td)
                                                    <option value="{{ $td->idTipoDistribucion }}">
                                                        {{ $td->descTipoDistribucion }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="from-group">
                                            <div class="custom-control custom-switch ustom-switch-on-success mt-4 tbjoNvo">

                                                <input type="checkbox" class="custom-control-input checkForm"
                                                    id="conFullfilment" name="conFullfilment"> <label
                                                    class="custom-control-label" for="conFullfilment">Fullfilment</label>
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
                                            <select class="input-group select2" style="width: 100%;" id="idTipoEncuadernado"
                                                name="idTipoEncuadernado">
                                                <option selected="selected" disabled>Seleccionar...</option>
                                                @foreach ($dto->combo_tipoencuadernado as $td)
                                                    <option value="{{ $td->idTipoEncuadernado }}">
                                                        {{ $td->descTipoEncuadernado }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="obsEncuadernado">Observaciones</label>
                                            <input type="text" class="form-control" id="obsEncuadernado"
                                                name="obsEncuadernado" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="otroEncuadernado">Otro</label>
                                            <input type="text" class="form-control" id="otroEncuadernado"
                                                name="otroEncuadernado" value="">
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
                                                        <label for="idTipoEmpaque_1"
                                                            class="custom-control-label">Retractilado</label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input check-empaque" type="checkbox"
                                                            id="idTipoEmpaque_2" name="idTipoEmpaque_2" value="2">
                                                        <label for="idTipoEmpaque_2"
                                                            class="custom-control-label">Embolsado</label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input check-empaque" type="checkbox"
                                                            id="idTipoEmpaque_6" name="idTipoEmpaque_6" value="6">
                                                        <label for="idTipoEmpaque_6" class="custom-control-label">Caja de
                                                            Cartón</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 text-left">
                                                <div class="form-group">
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input check-empaque" type="checkbox"
                                                            id="idTipoEmpaque_7" name="idTipoEmpaque_7" value="7">
                                                        <label for="idTipoEmpaque_7" class="custom-control-label">Tubo de
                                                            cartón
                                                        </label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input check-empaque" type="checkbox"
                                                            id="idTipoEmpaque_3" name="idTipoEmpaque_3" value="3">
                                                        <label for="idTipoEmpaque_3"
                                                            class="custom-control-label">Emplayado</label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input check-empaque" type="checkbox"
                                                            id="idTipoEmpaque_4" name="idTipoEmpaque_4" value="4">
                                                        <label for="idTipoEmpaque_4"
                                                            class="custom-control-label">Entarimado</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 text-left">
                                                <div class="form-group">
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input check-empaque" type="checkbox"
                                                            id="idTipoEmpaque_5" name="idTipoEmpaque_5" value="5">
                                                        <label for="idTipoEmpaque_5"
                                                            class="custom-control-label">Termosellado</label>
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
                                                        <label for="idTipoAcabadoGral_1" class="custom-control-label">Cinta
                                                            doble cara</label>
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
                                                        <label for="idTipoAcabadoGral_2"
                                                            class="custom-control-label">Ruteado</label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input check-AcabadosGral" type="checkbox"
                                                            id="idTipoAcabadoGral_4" name="idTipoAcabadoGral_4" value="4">
                                                        <label for="idTipoAcabadoGral_4"
                                                            class="custom-control-label">Ojillos</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 text-left">
                                                <div class="form-group">
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input check-AcabadosGral" type="checkbox"
                                                            id="idTipoAcabadoGral_5" name="idTipoAcabadoGral_5" value="5">
                                                        <label for="idTipoAcabadoGral_5"
                                                            class="custom-control-label">otro</label>
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
        <script src=" {{ asset('js/elementos_presupuestados_create.js?n=1') }} " defer></script>
        @include('elementos_presupuestados.mod_Paper')
    @endsection
