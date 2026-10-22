@extends('layout.master') @section('content')

<div id="formCreateElement" class="col-12 mt-2">
    <form method="post" role="form">
        <div class="rounded bg-gray">
            <div class="row mb-2 mt-1">
                <div class="col-sm-6">
                    <h6 class="text-bold pt-1 pl-1">Nuevo Elemento Presupuestado</h6>
                </div>
                <div class=" col-sm-6">
                    <h6 class="text-right text-bold pr-3 pt-1">&nbsp;EP 001
                    </h6>
                </div>
            </div>
        </div>

        <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">

            <div class="row">

                <div id="infGeneral1" class="col-lg-12 mt-3 pb-2 " style="margin-top: 0% !important;">
                    <div class="col-lg-12 col-sm-12 mb-1">
                        <div class="input-group input-group-sm">
                            <label class="mt-2" for="nomElementoPres">Elemento Presupuestado &nbsp;</label>
                            <input type="text" class="form-control col-md-8 col-lg-9 col-xl-10" id="nomElementoPres" name="nomElementoPres" autocomplete="off" placeholder="Requerido..">
                            <div class="input-group-append col-md-4 col-lg-3 col-xl-2">
                                <span class="col-lg-6 center"><a href="{{ route('ElementosPresupuestados') }}"
                                    class="btn btn-secondary btn-">Regresar</a></span>
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
                                <!-- <label for="tipo_item ">Tipo Item</label> -->
                                <select class="input-group select2 " style="width: 100%; ">
                                        <option selected="selected " disabled>Seleccionar...</option>
                                        @foreach ($combo_tipoitem as $TipoItem)
                                        <option value="{{ $TipoItem->idTipoItem}}">{{ $TipoItem->descTipoItem }}</option>
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
                                    <input id="numPresupuestoMTC" name="numPresupuestoMTC" type="text" class="form-control" placeholder="Num Presupuesto Metrics" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="col-2 mt-2">
                            <div class="from-group text-center">
                                <div class="custom-control custom-switch ustom-switch-on-success mt-2 tbjoNvo">
                                    <input type="checkbox" class="custom-control-input" id="tbjoNvo" name="tbjoNvo">
                                    <label class="custom-control-label" for="tbjoNvo">Trabajo Nuevo</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- AQUI ESPECIFICACIONES -->
                <div id="tabGeneralEP" class="col-lg-12 mt-3 pb-2 ">
                    <ul class="nav nav-tabs col-12" id="custom-content-below-tab" role="tablist">
                        <li class="nav-item col-6">
                            <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#tab_infGeneral" role="tab" aria-controls="custom-content-below-home" aria-selected="true">General</a>
                        </li>
                        <li class="nav-item col-6">
                            <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#tab_Especifiacaciones" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Especificaciones</a>
                        </li>
                    </ul>
                </div>
                <!-- <div class="row col-lg-12 col-sm-12 text-center" id="tab_infGeneral"> -->
                <div id="tab_infGeneral" class="col-lg-12 mt-3 pb-2 tab-content" style="margin-top: 0% !important;">
                    <div class="rounded bg-gray">
                        <div class="row mb-2 mt-1">
                            <div class="col-sm-12">
                                <h6 class="text-bold pt-1 pl-1"> Información General</h6>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fecha y Hora Limite Cotizacion</label>
                                <div class="input-group date" id="reservationdatetime5" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#reservationdatetime5" />
                                    <div class="input-group-append" data-target="#reservationdatetime5" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fecha Req. de Entrega Producto</label>
                                <div class="input-group date" id="reservationdate4" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate4" placeholder="(Requerido) dd-mm-aaaa" />
                                    <div class="input-group-append" data-target="#reservationdate4" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i>
                                        </div>
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
                                        <input type="text" class="form-control" id="PrecioObjtvo" required autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="PrecioComp">Precio Competencia</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <select class="form-control" id='PrecioComp' name='PrecioComp' style="width: 100%;padding-right: 0px;padding-left: 0px;">
                                                <option selected="selected" value='MXN' >$(MXN)</option>
                                                <option value='USD' >$(USD)</option>
                                            </select>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="PrecioComp" required autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="NomComp">Nombre Competencia</label>
                                <input type="text" class="form-control" id="NomComp" name="NomComp" required autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="FechCreaPor">Fecha Creación por:</label>
                                <input type="text" class="form-control" id="FechCreaPor" name='id="FechCreaPor"'>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ModifPor">Úlitma modificación por:</label>
                                <input type="text" class="form-control" id="ModifPor" name='id="ModifPor"'>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="Estado">Estado</label>
                                <select class="form-control" id='Estado' name='Estado' style="width: 100%;" di>
                                        <option selected="selected" value='0' disabled>Selecionar...</option>
                                        <option value='Base' >Base</option>
                                        <option value='Opción Adicional' >Opción Adicional</option>
                                        <option value='Cancelado' >Cancelado</option>
                                    </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Descripción</label>
                                <textarea id='Descripcion' name='Descripcion' class="form-control" rows="3" placeholder="...">
                                    </textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Observaciones Generales</label>
                                <textarea id='obs' name='obs' class="form-control" rows="3" placeholder="...">
                                    </textarea>
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
                                <label for="Margen">Margen Sugerido (%)</label>
                                <input type="text" class="form-control" id="Margen" name='id="Margen"'>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="Financiamiento">Financiamiento (%)</label>
                                <input type="text" class="form-control" id="Financiamiento" name='id="Financiamiento"'>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="comisionSAAM">Comisión Ext/SAAM(%)</label>
                                <input type="text" class="form-control" id="comisionSAAM" name='id="comisionSAAM"'>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="comisionConsultor">Comisión Consultor(%)</label>
                                <input type="text" class="form-control" id="comisionConsultor" name='id="comisionConsultor"'>
                            </div>
                        </div>
                    </div>
                    <div class="rounded bg-gray">
                        <div class="row mb-2 mt-1">
                            <div class="col-sm-12">
                                <h6 class="text-bold pt-1 pl-1">Especificaiones Técnicas</h6>
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
                                <select class="input-group select2" style="width: 100%;" id="preprensa">
                                        <option selected="selected" disabled>Seleccionar...</option>
                                        @foreach ($combo_pp as $pp)
                                        <option value="{{ $pp->idTipoPP }}">{{ $pp->descTipoPP }}</option>
                                        @endforeach  
                                    </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="from-group">
                                <div class="custom-control custom-switch ustom-switch-on-success mt-4 tbjoNvo">
                                    <input type="checkbox" class="custom-control-input" id="retoque" name="retoque">
                                    <label class="custom-control-label" for="retoque">Retoque de Fotos</label>
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
                                <select class="input-group select2" style="width: 100%;" id="distribucion">
                                        <option selected="selected" disabled>Seleccionar...</option>
                                        @foreach ($combo_tipodistribucion as $td)
                                        <option value="{{ $td->idTipoDistribucion }}">{{ $td->descTipoDistribucion }}</option>
                                        @endforeach  
                                    </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="from-group">
                                <div class="custom-control custom-switch ustom-switch-on-success mt-4 tbjoNvo">
                                    <input type="checkbox" class="custom-control-input" id="Fullfilment" name="Fullfilment">
                                    <label class="custom-control-label" for="Fullfilment">Fullfilment</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div id="infGeneral" class="col-lg-12 col-sm-12 mt-2">

                            <!-- <div class="row rounded bg-gray" style="margin-bottom: 0px;margin-top: 5px;">
                                    <div>
                                        <h6 class="text-bold pt-1 pl-1">Acabados Portadas</h6>
                                    </div>
                                </div>  -->
                            <!-- <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="customCheckbox1" value="Doblado">
                                                <label for="customCheckbox1" class="custom-control-label">Dobaldo</label>
                                            </div>
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="customCheckbox1" value="Personalizado">
                                                <label for="customCheckbox1" class="custom-control-label">Personalizado</label>
                                            </div>
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="customCheckbox1" value="Pegado">
                                                <label for="customCheckbox1" class="custom-control-label">Pegado</label>
                                            </div>
    
    
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="customCheckbox1" value="Gatefold ">
                                                <label for="customCheckbox1" class="custom-control-label">Gatefold </label>
                                            </div>
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="customCheckbox1" value="Perforado">
                                                <label for="customCheckbox1" class="custom-control-label">Perforado</label>
                                            </div>
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="customCheckbox1" value="Suajado">
                                                <label for="customCheckbox1" class="custom-control-label">Suajado</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="customCheckbox1" value="Plecado">
                                                <label for="customCheckbox1" class="custom-control-label">Plecado</label>
                                            </div>
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="customCheckbox1" value="Hot stamping ">
                                                <label for="customCheckbox1" class="custom-control-label">Hot stamping </label>
                                            </div>
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="Grabado" value="Grabado">
                                                <label for="customCheckbox1" class="custom-control-label">Grabado</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
    
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="customCheckbox1" value="Otro ">
                                                <label for="customCheckbox1" class="custom-control-label">Otro </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row rounded bg-gray" style="margin-bottom: 0px;margin-top: 5px;">
                                    <div>
                                        <h6 class="text-bold pt-1 pl-1">Encuadernado</h6>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="Encuadernado">Encuadernado</label>
                                            <input type="text" class="form-control" id="Encuadernado" name="Encuadernado">
    
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="EncuadernadoObs">Observaciones</label>
                                            <input type="text" class="form-control" id="EncuadernadoObs" name="EncuadernadoObs">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="EncuadernadoOtro">Otro</label>
                                            <input type="text" class="form-control" id="EncuadernadoOtro" name="EncuadernadoOtro">
                                        </div>
                                    </div>
    
                                </div> -->
                            <div class="row col-12 rounded bg-gray" style="margin-bottom: 0px;margin-top: 5px;">
                                <div>
                                    <h6 class="text-bold pt-1 pl-1">Empaque Especial</h6>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-4 text-left">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="customCheckbox1" value="Retractilado">
                                            <label for="customCheckbox1" class="custom-control-label">Retractilado</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="customCheckbox1" value="Embolsado">
                                            <label for="customCheckbox1" class="custom-control-label">Embolsado</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="customCheckbox1" value="Caja de Cartón">
                                            <label for="customCheckbox1" class="custom-control-label">Caja de Cartón</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 text-left">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="customCheckbox1" value="Tubo de cartón ">
                                            <label for="customCheckbox1" class="custom-control-label">Tubo de cartón </label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="customCheckbox1" value="Emplayado">
                                            <label for="customCheckbox1" class="custom-control-label">Emplayado</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="customCheckbox1" value="Entarimado">
                                            <label for="customCheckbox1" class="custom-control-label">Entarimado</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 text-left">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="customCheckbox1" value="Termosellado">
                                            <label for="customCheckbox1" class="custom-control-label">Termosellado</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="text" id="customCheckbox1" value="Observaciones de Empaque">
                                            <label for="customCheckbox1" class="custom-control-label">Observaciones de Empaque</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="customCheckbox1" value="otro">
                                            <label for="customCheckbox1" class="custom-control-label">otro</label>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row col-12 rounded bg-gray" style="margin-bottom: 0px;margin-top: 5px;">
                                <div>
                                    <h6 class="text-bold pt-1 pl-1">Acabados Generales</h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4 text-left">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="customCheckbox1" value="cinta">
                                            <label for="customCheckbox1" class="custom-control-label">Cinta doble cara</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="customCheckbox1" value="Encapasulado">
                                            <label for="customCheckbox1" class="custom-control-label">Encapasulado</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 text-left">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="customCheckbox1" value="Ruteado">
                                            <label for="customCheckbox1" class="custom-control-label">Ruteado</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="customCheckbox1" value="Ojillos">
                                            <label for="customCheckbox1" class="custom-control-label">Ojillos</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 text-left">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="customCheckbox1" value="otro">
                                            <label for="customCheckbox1" class="custom-control-label">otro</label>
                                        </div>
                                    </div>
                                </div>
                            </div>




                        </div>
                        <!-- <div id="infGeneral" class="col-lg-6 col-sm-12 mt-2">
                                <div class="row rounded bg-gray" style="margin-bottom: 10px;margin-top: 5px;">
                                    <div>
                                        <h6 class="text-bold pt-1 pl-1">Especificaciones</h6>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-hover" id="tblElmenPresupestados">
                                        <thead>
                                            <tr class="table-primary text-center text-capitalize">
                                                <th>DESCRIPCIÓN</th>
                                                <th>INERIOR 1</th>
                                                <th>INERIOR 2</th>
                                                <th>INERIOR 3</th>
                                                <th>PORTADAS</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Largo Extendido (cm)</td>
                                                <td><input type="text" class="form-control tblLE" id="LEint1" name="LEint1" required autocomplete="off"></td>
                                                <td><input type="text" class="form-control tblLE" id="LEint2" name="LEint2" required autocomplete="off"></td>
                                                <td><input type="text" class="form-control tblLE" id="LEint3" name="LEint3" required autocomplete="off"></td>
                                                <td><input type="text" class="form-control tblLE" id="LEPort" name="LEPort" required autocomplete="off"></td>
                                            </tr>
                                            <tr>
                                                <td>Ancho Extendido (cm)</td>
                                                <td><input type="text" class="form-control tblAE" id="AEint1" name="AEint1" required autocomplete="off"></td>
                                                <td><input type="text" class="form-control tblAE" id="AEint2" name="AEint2" required autocomplete="off"></td>
                                                <td><input type="text" class="form-control tblAE" id="AEint3" name="AEint3" required autocomplete="off"></td>
                                                <td><input type="text" class="form-control tblAE" id="AEPort" name="AEPort" required autocomplete="off"></td>
                                            </tr>
                                            <tr>
                                                <td>Largo Final (cm)</td>
                                                <td><input type="text" class="form-control tblLF" id="LFint1" name="LFint1" required autocomplete="off"></td>
                                                <td><input type="text" class="form-control tblLF" id="LFint2" name="LFint2" required autocomplete="off"></td>
                                                <td><input type="text" class="form-control tblLF" id="LFint3" name="LFint3" required autocomplete="off"></td>
                                                <td><input type="text" class="form-control tblLF" id="LFPort" name="LFPort" required autocomplete="off"></td>
                                            </tr>
                                            <tr>
                                                <td>Ancho Final (cm)</td>
                                                <td><input type="text" class="form-control tblAF" id="AFint1" name="AFint1" required autocomplete="off"></td>
                                                <td><input type="text" class="form-control tblAF" id="AFint2" name="AFint2" required autocomplete="off"></td>
                                                <td><input type="text" class="form-control tblAF" id="AFint3" name="AFint3" required autocomplete="off"></td>
                                                <td><input type="text" class="form-control tblAF" id="AFPort" name="AFPort" required autocomplete="off"></td>
                                            </tr>
                                            <tr>
                                                <td>Tintas Frente</td>
                                                <td><input type="text" class="form-control tblTF" id="TFint1" name="TFint1" required autocomplete="off"></td>
                                                <td><input type="text" class="form-control tblTF" id="TFint2" name="TFint2" required autocomplete="off"></td>
                                                <td><input type="text" class="form-control tblTF" id="TFint3" name="TFint3" required autocomplete="off"></td>
                                                <td><input type="text" class="form-control tblTF" id="TFPort" name="TFPort" required autocomplete="off"></td>
                                            </tr>
                                            <tr>
                                                <td>Tintas Vuelta</td>
                                                <td><input type="text" class="form-control tblTV" id="TVint1" name="TVint1" required autocomplete="off"></td>
                                                <td><input type="text" class="form-control tblTV" id="TVint2" name="TVint2" required autocomplete="off"></td>
                                                <td><input type="text" class="form-control tblTV" id="TVint3" name="TVint3" required autocomplete="off"></td>
                                                <td><input type="text" class="form-control tblTV" id="TVPort" name="TVPort" required autocomplete="off"></td>
                                            </tr>
                                            <tr>
                                                <td>Papel (línea)</td>
                                                <td>
                                                    <select class="form-control tblPL" style="width: 100%;" id="PLint1" name="PLint1">
                                                    <option selected="selected" disabled>Seleccionar...</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control tblPL" style="width: 100%;" id="PLint2" name="PLint2">
                                                    <option selected="selected" disabled>Seleccionar...</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control tblPL" style="width: 100%;" id="PLint3" name="PLint3">
                                                    <option selected="selected" disabled>Seleccionar...</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control tblPL" style="width: 100%;" id="PLPort" name="PLPort">
                                                    <option selected="selected" disabled>Seleccionar...</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Papel (Especial)</td>
                                                <td><input type="text" class="form-control tblPE" id="PEint1" name="PEint1" required autocomplete="off"></td>
                                                <td><input type="text" class="form-control tblPE" id="PEint2" name="PEint2" required autocomplete="off"></td>
                                                <td><input type="text" class="form-control tblPE" id="PEint3" name="PEint3" required autocomplete="off"></td>
                                                <td><input type="text" class="form-control tblPE" id="PEPort" name="PEPort" required autocomplete="off"></td>
                                            </tr>
                                            <tr>
                                                <td>No Paginas</td>
                                                <td><input type="text" class="form-control tblNP" id="NPint1" name="NPint1" required autocomplete="off"></td>
                                                <td><input type="text" class="form-control tblNP" id="NPint2" name="NPint2" required autocomplete="off"></td>
                                                <td><input type="text" class="form-control tblNP" id="NPint3" name="NPint3" required autocomplete="off"></td>
                                                <td><input type="text" class="form-control tblNP" id="NPPort" name="NPPort" required autocomplete="off"></td>
                                            </tr>
                                            <tr>
                                                <td>Barniz</td>
                                                <td>
                                                    <select class="form-control tblBa" style="width: 100%;" id="Baint1" name="Baint1">
                                                    <option selected="selected" disabled>Seleccionar...</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control tblBa" style="width: 100%;" id="Baint2" name="Baint2">
                                                    <option selected="selected" disabled>Seleccionar...</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control tblBa" style="width: 100%;" id="Baint3" name="Baint3">
                                                    <option selected="selected" disabled>Seleccionar...</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control tblBa" style="width: 100%;" id="BaPort" name="BaPort">
                                                    <option selected="selected" disabled>Seleccionar...</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Tipp de Barniz</td>
                                                <td>
                                                    <select class="form-control tblTB" style="width: 100%;" id="TBint1" name="TBint1">
                                                    <option selected="selected" disabled>Seleccionar...</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control tblTB" style="width: 100%;" id="TBint2" name="TBint2">
                                                    <option selected="selected" disabled>Seleccionar...</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control tblTB" style="width: 100%;" id="TBint3" name="TBint3">
                                                    <option selected="selected" disabled>Seleccionar...</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control tblTB" style="width: 100%;" id="TBPort" name="TBPort">
                                                    <option selected="selected" disabled>Seleccionar...</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Laminado</td>
                                                <td>
                                                    <select class="form-control tblLa" style="width: 100%;" id="Laint1" name="Laint1">
                                                    <option selected="selected" disabled>Seleccionar...</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control tblLa" style="width: 100%;" id="Laint2" name="Laint2">
                                                    <option selected="selected" disabled>Seleccionar...</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control tblLa" style="width: 100%;" id="Laint3" name="Laint3">
                                                    <option selected="selected" disabled>Seleccionar...</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control tblLa" style="width: 100%;" id="LaPort" name="LaPort">
                                                    <option selected="selected" disabled>Seleccionar...</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Tipo de Laminado</td>
                                                <td>
                                                    <select class="form-control tblTL" style="width: 100%;" id="TLint1" name="TLint1">
                                                    <option selected="selected" disabled>Seleccionar...</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control tblTL" style="width: 100%;" id="TLint2" name="TLint2">
                                                    <option selected="selected" disabled>Seleccionar...</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control tblTL" style="width: 100%;" id="TLint3" name="TLint3">
                                                    <option selected="selected" disabled>Seleccionar...</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control tblTL" style="width: 100%;" id="TLPort" name="TLPort">
                                                    <option selected="selected" disabled>Seleccionar...</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr class="table-secondary">
                                                <td colspan="5" class="text-center text-bold ">A C A B A D O S</td>
                                            </tr>
                                            <tr>
                                                <td>Doblado</td>
                                                <td class="text-center">
                                                    <div class="custom-control custom-switch ustom-switch-on-success tblDOB">
                                                        <input type="checkbox" class="custom-control-input" id="DOBint1" name="DOBint1">
                                                        <label class="custom-control-label" for="DOBint1"></label>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="custom-control custom-switch ustom-switch-on-success tblDOB">
                                                        <input type="checkbox" class="custom-control-input" id="DOBint2" name="DOBint2">
                                                        <label class="custom-control-label" for="DOBint2"></label>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="custom-control custom-switch ustom-switch-on-success tblDOB">
                                                        <input type="checkbox" class="custom-control-input" id="DOBint3" name="DOBint3">
                                                        <label class="custom-control-label" for="DOBint3"></label>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="custom-control custom-switch ustom-switch-on-success tblDOB">
                                                        <input type="checkbox" class="custom-control-input" id="DOBPort" name="DOBPort">
                                                        <label class="custom-control-label" for="DOBPort"></label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Perforado</td>
                                                <td class="text-center">
                                                    <div class="custom-control custom-switch ustom-switch-on-success tblPER">
                                                        <input type="checkbox" class="custom-control-input" id="PERint1" name="PERint1">
                                                        <label class="custom-control-label" for="PERint1"></label>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="custom-control custom-switch ustom-switch-on-success tblPER">
                                                        <input type="checkbox" class="custom-control-input" id="PERint2" name="PERint2">
                                                        <label class="custom-control-label" for="PERint2"></label>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="custom-control custom-switch ustom-switch-on-success tblPER">
                                                        <input type="checkbox" class="custom-control-input" id="PERint3" name="PERint3">
                                                        <label class="custom-control-label" for="PERint3"></label>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="custom-control custom-switch ustom-switch-on-success tblPER">
                                                        <input type="checkbox" class="custom-control-input" id="PERPort" name="PERPort">
                                                        <label class="custom-control-label" for="PERPort"></label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Personalizdo</td>
                                                <td class="text-center">
                                                    <div class="custom-control custom-switch ustom-switch-on-success tblPDO">
                                                        <input type="checkbox" class="custom-control-input" id="PDOint1" name="PDOint1">
                                                        <label class="custom-control-label" for="PDOint1"></label>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="custom-control custom-switch ustom-switch-on-success tblPDO">
                                                        <input type="checkbox" class="custom-control-input" id="PDOint2" name="PDOint2">
                                                        <label class="custom-control-label" for="PDOint2"></label>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="custom-control custom-switch ustom-switch-on-success tblPDO">
                                                        <input type="checkbox" class="custom-control-input" id="PDOint3" name="PDOint3">
                                                        <label class="custom-control-label" for="PDOint3"></label>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="custom-control custom-switch ustom-switch-on-success tblPDO">
                                                        <input type="checkbox" class="custom-control-input" id="PDOPort" name="PDOPort">
                                                        <label class="custom-control-label" for="PDOPort"></label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Suajado</td>
                                                <td class="text-center">
                                                    <div class="custom-control custom-switch ustom-switch-on-success tblSUA">
                                                        <input type="checkbox" class="custom-control-input" id="SUAint1" name="SUAint1">
                                                        <label class="custom-control-label" for="SUAint1"></label>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="custom-control custom-switch ustom-switch-on-success tblSUA">
                                                        <input type="checkbox" class="custom-control-input" id="SUAint2" name="SUAint2">
                                                        <label class="custom-control-label" for="SUAint2"></label>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="custom-control custom-switch ustom-switch-on-success tblSUA">
                                                        <input type="checkbox" class="custom-control-input" id="SUAint3" name="SUAint3">
                                                        <label class="custom-control-label" for="SUAint3"></label>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="custom-control custom-switch ustom-switch-on-success tblSUA">
                                                        <input type="checkbox" class="custom-control-input" id="SUAPort" name="SUAPort">
                                                        <label class="custom-control-label" for="SUAPort"></label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Pegado</td>
                                                <td class="text-center">
                                                    <div class="custom-control custom-switch ustom-switch-on-success tblPEG">
                                                        <input type="checkbox" class="custom-control-input" id="PEGint1" name="PEGint1">
                                                        <label class="custom-control-label" for="PEGint1"></label>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="custom-control custom-switch ustom-switch-on-success tblPEG">
                                                        <input type="checkbox" class="custom-control-input" id="PEGint2" name="PEGint2">
                                                        <label class="custom-control-label" for="PEGint2"></label>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="custom-control custom-switch ustom-switch-on-success tblPEG">
                                                        <input type="checkbox" class="custom-control-input" id="PEGint3" name="PEGint3">
                                                        <label class="custom-control-label" for="PEGint3"></label>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="custom-control custom-switch ustom-switch-on-success tblPEG">
                                                        <input type="checkbox" class="custom-control-input" id="PEGPort" name="PEGPort">
                                                        <label class="custom-control-label" for="PEGPort"></label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Plecado</td>
                                                <td class="text-center">
                                                    <div class="custom-control custom-switch ustom-switch-on-success tblPLE">
                                                        <input type="checkbox" class="custom-control-input" id="PLEint1" name="PLEint1">
                                                        <label class="custom-control-label" for="PLEint1"></label>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="custom-control custom-switch ustom-switch-on-success tblPLE">
                                                        <input type="checkbox" class="custom-control-input" id="PLEint2" name="PLEint2">
                                                        <label class="custom-control-label" for="PLEint2"></label>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="custom-control custom-switch ustom-switch-on-success tblPLE">
                                                        <input type="checkbox" class="custom-control-input" id="PLEint3" name="PLEint3">
                                                        <label class="custom-control-label" for="PLEint3"></label>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="custom-control custom-switch ustom-switch-on-success tblPLE">
                                                        <input type="checkbox" class="custom-control-input" id="PLEPort" name="PLEPort">
                                                        <label class="custom-control-label" for="PLEPort"></label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Otro </td>
                                                <td><input type="text" class="form-control tblAO" id="AOint1" name="AOint1" required autocomplete="off"></td>
                                                <td><input type="text" class="form-control tblAO" id="AOint2" name="AOint2" required autocomplete="off"></td>
                                                <td><input type="text" class="form-control tblAO" id="AOint3" name="AOint3" required autocomplete="off"></td>
                                                <td><input type="text" class="form-control tblAO" id="AOPort" name="AOPort" required autocomplete="off"></td>
                                            </tr>
    
                                        </tbody>
                                    </table>
                                </div>
                            </div> -->

                    </div>
                </div>




                <div id="tab_Especifiacaciones" class="col-lg-12 mt-3 pb-2 tab-content">
                    <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                        <div class="rounded bg-gray">
                            <div class="row mb-2 mt-1">
                                <div class="col-sm-12">
                                    <h6 class="text-bold pt-1 pl-1">Especificaciones</h6>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <table class="table table-bordered table-hover" id="tblElmenPresupestados">
                                <thead style="margin-right: 5.3vh !important;">
                                    <tr class="table-primary text-center text-capitalize" width='100%'>
                                        <th class="text-center thtitle">DESCRIPCIÓN</th>
                                        <th class="text-center thtitle">
                                            <input type="text" class="form-control text-center titletbl" id="TitleInterior1" name="TitleInterior1" placeholder="INTERIOR 1">
                                        </th>
                                        <th class="text-center thtitle">
                                            <input type="text" class="form-control text-center titletbl" id="TitleInterior2" name="TitleInterior2" placeholder="INTERIOR 2">
                                        </th>
                                        <th class="text-center thtitle">
                                            <input type="text" class="form-control text-center titletbl" id="TitleInterior3" name="TitleInterior3" placeholder="INTERIOR 3">
                                        </th>
                                        <th class="text-center thtitle">
                                            <input type="text" class="form-control text-center titletbl" id="TitlePortada" name="TitlePortada" placeholder="PORTADA">
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="thtitle1">Largo Extendido (cm)</td>
                                        <td class="thtitle2"><input type="text" class="form-control tblLE" id="LEint1" name="LEint1" required autocomplete="off"></td>
                                        <td class="thtitle2"><input type="text" class="form-control tblLE" id="LEint2" name="LEint2" required autocomplete="off"></td>
                                        <td class="thtitle2"><input type="text" class="form-control tblLE" id="LEint3" name="LEint3" required autocomplete="off"></td>
                                        <td class="thtitle2"><input type="text" class="form-control tblLE" id="LEPort" name="LEPort" required autocomplete="off"></td>
                                    </tr>
                                    <tr>
                                        <td>Ancho Extendido (cm)</td>
                                        <td><input type="text" class="form-control tblAE" id="AEint1" name="AEint1" required autocomplete="off"></td>
                                        <td><input type="text" class="form-control tblAE" id="AEint2" name="AEint2" required autocomplete="off"></td>
                                        <td><input type="text" class="form-control tblAE" id="AEint3" name="AEint3" required autocomplete="off"></td>
                                        <td><input type="text" class="form-control tblAE" id="AEPort" name="AEPort" required autocomplete="off"></td>
                                    </tr>
                                    <tr>
                                        <td>Largo Final (cm)</td>
                                        <td><input type="text" class="form-control tblLF" id="LFint1" name="LFint1" required autocomplete="off"></td>
                                        <td><input type="text" class="form-control tblLF" id="LFint2" name="LFint2" required autocomplete="off"></td>
                                        <td><input type="text" class="form-control tblLF" id="LFint3" name="LFint3" required autocomplete="off"></td>
                                        <td><input type="text" class="form-control tblLF" id="LFPort" name="LFPort" required autocomplete="off"></td>
                                    </tr>
                                    <tr>
                                        <td>Ancho Final (cm)</td>
                                        <td><input type="text" class="form-control tblAF" id="AFint1" name="AFint1" required autocomplete="off"></td>
                                        <td><input type="text" class="form-control tblAF" id="AFint2" name="AFint2" required autocomplete="off"></td>
                                        <td><input type="text" class="form-control tblAF" id="AFint3" name="AFint3" required autocomplete="off"></td>
                                        <td><input type="text" class="form-control tblAF" id="AFPort" name="AFPort" required autocomplete="off"></td>
                                    </tr>
                                    <tr>
                                        <td>Tintas Frente</td>
                                        <td><input type="text" class="form-control tblTF" id="TFint1" name="TFint1" required autocomplete="off"></td>
                                        <td><input type="text" class="form-control tblTF" id="TFint2" name="TFint2" required autocomplete="off"></td>
                                        <td><input type="text" class="form-control tblTF" id="TFint3" name="TFint3" required autocomplete="off"></td>
                                        <td><input type="text" class="form-control tblTF" id="TFPort" name="TFPort" required autocomplete="off"></td>
                                    </tr>
                                    <tr>
                                        <td>Tintas Vuelta</td>
                                        <td><input type="text" class="form-control tblTV" id="TVint1" name="TVint1" required autocomplete="off"></td>
                                        <td><input type="text" class="form-control tblTV" id="TVint2" name="TVint2" required autocomplete="off"></td>
                                        <td><input type="text" class="form-control tblTV" id="TVint3" name="TVint3" required autocomplete="off"></td>
                                        <td><input type="text" class="form-control tblTV" id="TVPort" name="TVPort" required autocomplete="off"></td>
                                    </tr>
                                    <tr>
                                        <td>Papel (línea)</td>
                                        <td class="btnTP">
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control inputTP" placeholder="Elige el Papel" id="PLint1" name="PLint1" readonly>
                                                <span class="input-group-append">
                                                        <button type="button" class="btn btn-secondary btnPaper"  data-column="Ineterior1" onclick="OpenModal('PLint1')">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-medical" viewBox="0 0 16 16">
                                                                <path d="M7.5 5.5a.5.5 0 0 0-1 0v.634l-.549-.317a.5.5 0 1 0-.5.866L6 7l-.549.317a.5.5 0 1 0 .5.866l.549-.317V8.5a.5.5 0 1 0 1 0v-.634l.549.317a.5.5 0 1 0 .5-.866L8 7l.549-.317a.5.5 0 1 0-.5-.866l-.549.317V5.5zm-2 4.5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 2a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5z"></path>
                                                                <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"></path>
                                                            </svg>
                                                        </button>
                                                    </span>
                                            </div>
                                        </td>
                                        <td class="btnTP">
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control inputTP" placeholder="Elige el Papel" id="PLint2" name="PLint2" readonly>
                                                <span class="input-group-append">
                                                        <button type="button" class="btn btn-secondary btnPaper" onclick="OpenModal('PLint2')">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-medical" viewBox="0 0 16 16">
                                                                <path d="M7.5 5.5a.5.5 0 0 0-1 0v.634l-.549-.317a.5.5 0 1 0-.5.866L6 7l-.549.317a.5.5 0 1 0 .5.866l.549-.317V8.5a.5.5 0 1 0 1 0v-.634l.549.317a.5.5 0 1 0 .5-.866L8 7l.549-.317a.5.5 0 1 0-.5-.866l-.549.317V5.5zm-2 4.5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 2a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5z"></path>
                                                                <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"></path>
                                                            </svg>
                                                        </button>
                                                    </span>
                                            </div>
                                        </td>
                                        <td class="btnTP">
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control inputTP" placeholder="Elige el Papel" id="PLint3" name="PLint3" readonly>
                                                <span class="input-group-append">
                                                        <button type="button" class="btn btn-secondary btnPaper" onclick="OpenModal('PLint3')">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-medical" viewBox="0 0 16 16">
                                                                <path d="M7.5 5.5a.5.5 0 0 0-1 0v.634l-.549-.317a.5.5 0 1 0-.5.866L6 7l-.549.317a.5.5 0 1 0 .5.866l.549-.317V8.5a.5.5 0 1 0 1 0v-.634l.549.317a.5.5 0 1 0 .5-.866L8 7l.549-.317a.5.5 0 1 0-.5-.866l-.549.317V5.5zm-2 4.5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 2a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5z"></path>
                                                                <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"></path>
                                                            </svg>
                                                        </button>
                                                    </span>
                                            </div>
                                        </td>
                                        <td class="btnTP">
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control inputTP" placeholder="Elige el Papel" id="PLPort" name="PLPort" readonly>
                                                <span class="input-group-append">
                                                        <button type="button" class="btn btn-secondary btnPaper" onclick="OpenModal('PLPort')">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-medical" viewBox="0 0 16 16">
                                                                <path d="M7.5 5.5a.5.5 0 0 0-1 0v.634l-.549-.317a.5.5 0 1 0-.5.866L6 7l-.549.317a.5.5 0 1 0 .5.866l.549-.317V8.5a.5.5 0 1 0 1 0v-.634l.549.317a.5.5 0 1 0 .5-.866L8 7l.549-.317a.5.5 0 1 0-.5-.866l-.549.317V5.5zm-2 4.5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 2a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5z"></path>
                                                                <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"></path>
                                                            </svg>
                                                        </button>
                                                    </span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Papel (Especial)</td>
                                        <td><input type="text" class="form-control tblPE" id="PEint1" name="PEint1" required autocomplete="off"></td>
                                        <td><input type="text" class="form-control tblPE" id="PEint2" name="PEint2" required autocomplete="off"></td>
                                        <td><input type="text" class="form-control tblPE" id="PEint3" name="PEint3" required autocomplete="off"></td>
                                        <td><input type="text" class="form-control tblPE" id="PEPort" name="PEPort" required autocomplete="off"></td>
                                    </tr>
                                    <tr>
                                        <td>No Paginas</td>
                                        <td><input type="text" class="form-control tblNP" id="NPint1" name="NPint1" required autocomplete="off"></td>
                                        <td><input type="text" class="form-control tblNP" id="NPint2" name="NPint2" required autocomplete="off"></td>
                                        <td><input type="text" class="form-control tblNP" id="NPint3" name="NPint3" required autocomplete="off"></td>
                                        <td><input type="text" class="form-control tblNP" id="NPPort" name="NPPort" required autocomplete="off"></td>
                                    </tr>
                                    <tr>
                                        <td>Barniz</td>
                                        <td>
                                            <select class="form-control tblBa" style="width: 100%;" id="Baint1" name="Baint1">
                                                <option selected="selected" disabled>Seleccionar...</option>
                                                </select>
                                        </td>
                                        <td>
                                            <select class="form-control tblBa" style="width: 100%;" id="Baint2" name="Baint2">
                                                <option selected="selected" disabled>Seleccionar...</option>
                                                </select>
                                        </td>
                                        <td>
                                            <select class="form-control tblBa" style="width: 100%;" id="Baint3" name="Baint3">
                                                <option selected="selected" disabled>Seleccionar...</option>
                                                </select>
                                        </td>
                                        <td>
                                            <select class="form-control tblBa" style="width: 100%;" id="BaPort" name="BaPort">
                                                <option selected="selected" disabled>Seleccionar...</option>
                                                </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Tipp de Barniz</td>
                                        <td>
                                            <select class="form-control tblTB" style="width: 100%;" id="TBint1" name="TBint1">
                                                <option selected="selected" disabled>Seleccionar...</option>
                                                <option selected="selected" disabled>Seleccionar...</option>
                                                @foreach ($combo_tipobarniz as $tb)
                                                <option value="{{ $tb->idTipoBarniz }}">{{ $tb->descBarniz }}</option>
                                                @endforeach  
                                                </select>
                                        </td>
                                        <td>
                                            <select class="form-control tblTB" style="width: 100%;" id="TBint2" name="TBint2">
                                                <option selected="selected" disabled>Seleccionar...</option>
                                                </select>
                                        </td>
                                        <td>
                                            <select class="form-control tblTB" style="width: 100%;" id="TBint3" name="TBint3">
                                                <option selected="selected" disabled>Seleccionar...</option>
                                                </select>
                                        </td>
                                        <td>
                                            <select class="form-control tblTB" style="width: 100%;" id="TBPort" name="TBPort">
                                                <option selected="selected" disabled>Seleccionar...</option>
                                                </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Laminado</td>
                                        <td>
                                            <select class="form-control tblLa" style="width: 100%;" id="Laint1" name="Laint1">
                                                <option selected="selected" disabled>Seleccionar...</option>
                                                </select>
                                        </td>
                                        <td>
                                            <select class="form-control tblLa" style="width: 100%;" id="Laint2" name="Laint2">
                                                <option selected="selected" disabled>Seleccionar...</option>
                                                </select>
                                        </td>
                                        <td>
                                            <select class="form-control tblLa" style="width: 100%;" id="Laint3" name="Laint3">
                                                <option selected="selected" disabled>Seleccionar...</option>
                                                </select>
                                        </td>
                                        <td>
                                            <select class="form-control tblLa" style="width: 100%;" id="LaPort" name="LaPort">
                                                <option selected="selected" disabled>Seleccionar...</option>
                                                </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Tipo de Laminado</td>
                                        <td>
                                            <select class="form-control tblTL" style="width: 100%;" id="TLint1" name="TLint1">
                                                <option selected="selected" disabled>Seleccionar...</option>
                                                </select>
                                        </td>
                                        <td>
                                            <select class="form-control tblTL" style="width: 100%;" id="TLint2" name="TLint2">
                                                <option selected="selected" disabled>Seleccionar...</option>
                                                </select>
                                        </td>
                                        <td>
                                            <select class="form-control tblTL" style="width: 100%;" id="TLint3" name="TLint3">
                                                <option selected="selected" disabled>Seleccionar...</option>
                                                </select>
                                        </td>
                                        <td>
                                            <select class="form-control tblTL" style="width: 100%;" id="TLPort" name="TLPort">
                                                <option selected="selected" disabled>Seleccionar...</option>
                                                </select>
                                        </td>
                                    </tr>
                                    <tr class="table-secondary">
                                        <td colspan="5" class="text-center text-bold ">A C A B A D O S</td>
                                    </tr>
                                    <tr>
                                        <td>Doblado</td>
                                        <td class="text-center">
                                            <div class="custom-control custom-switch ustom-switch-on-success tblDOB">
                                                <input type="checkbox" class="custom-control-input" id="DOBint1" name="DOBint1">
                                                <label class="custom-control-label" for="DOBint1"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="custom-control custom-switch ustom-switch-on-success tblDOB">
                                                <input type="checkbox" class="custom-control-input" id="DOBint2" name="DOBint2">
                                                <label class="custom-control-label" for="DOBint2"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="custom-control custom-switch ustom-switch-on-success tblDOB">
                                                <input type="checkbox" class="custom-control-input" id="DOBint3" name="DOBint3">
                                                <label class="custom-control-label" for="DOBint3"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="custom-control custom-switch ustom-switch-on-success tblDOB">
                                                <input type="checkbox" class="custom-control-input" id="DOBPort" name="DOBPort">
                                                <label class="custom-control-label" for="DOBPort"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Perforado</td>
                                        <td class="text-center">
                                            <div class="custom-control custom-switch ustom-switch-on-success tblPER">
                                                <input type="checkbox" class="custom-control-input" id="PERint1" name="PERint1">
                                                <label class="custom-control-label" for="PERint1"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="custom-control custom-switch ustom-switch-on-success tblPER">
                                                <input type="checkbox" class="custom-control-input" id="PERint2" name="PERint2">
                                                <label class="custom-control-label" for="PERint2"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="custom-control custom-switch ustom-switch-on-success tblPER">
                                                <input type="checkbox" class="custom-control-input" id="PERint3" name="PERint3">
                                                <label class="custom-control-label" for="PERint3"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="custom-control custom-switch ustom-switch-on-success tblPER">
                                                <input type="checkbox" class="custom-control-input" id="PERPort" name="PERPort">
                                                <label class="custom-control-label" for="PERPort"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Personalizdo</td>
                                        <td class="text-center">
                                            <div class="custom-control custom-switch ustom-switch-on-success tblPDO">
                                                <input type="checkbox" class="custom-control-input" id="PDOint1" name="PDOint1">
                                                <label class="custom-control-label" for="PDOint1"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="custom-control custom-switch ustom-switch-on-success tblPDO">
                                                <input type="checkbox" class="custom-control-input" id="PDOint2" name="PDOint2">
                                                <label class="custom-control-label" for="PDOint2"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="custom-control custom-switch ustom-switch-on-success tblPDO">
                                                <input type="checkbox" class="custom-control-input" id="PDOint3" name="PDOint3">
                                                <label class="custom-control-label" for="PDOint3"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="custom-control custom-switch ustom-switch-on-success tblPDO">
                                                <input type="checkbox" class="custom-control-input" id="PDOPort" name="PDOPort">
                                                <label class="custom-control-label" for="PDOPort"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Suajado</td>
                                        <td class="text-center">
                                            <div class="custom-control custom-switch ustom-switch-on-success tblSUA">
                                                <input type="checkbox" class="custom-control-input" id="SUAint1" name="SUAint1">
                                                <label class="custom-control-label" for="SUAint1"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="custom-control custom-switch ustom-switch-on-success tblSUA">
                                                <input type="checkbox" class="custom-control-input" id="SUAint2" name="SUAint2">
                                                <label class="custom-control-label" for="SUAint2"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="custom-control custom-switch ustom-switch-on-success tblSUA">
                                                <input type="checkbox" class="custom-control-input" id="SUAint3" name="SUAint3">
                                                <label class="custom-control-label" for="SUAint3"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="custom-control custom-switch ustom-switch-on-success tblSUA">
                                                <input type="checkbox" class="custom-control-input" id="SUAPort" name="SUAPort">
                                                <label class="custom-control-label" for="SUAPort"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Pegado</td>
                                        <td class="text-center">
                                            <div class="custom-control custom-switch ustom-switch-on-success tblPEG">
                                                <input type="checkbox" class="custom-control-input" id="PEGint1" name="PEGint1">
                                                <label class="custom-control-label" for="PEGint1"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="custom-control custom-switch ustom-switch-on-success tblPEG">
                                                <input type="checkbox" class="custom-control-input" id="PEGint2" name="PEGint2">
                                                <label class="custom-control-label" for="PEGint2"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="custom-control custom-switch ustom-switch-on-success tblPEG">
                                                <input type="checkbox" class="custom-control-input" id="PEGint3" name="PEGint3">
                                                <label class="custom-control-label" for="PEGint3"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="custom-control custom-switch ustom-switch-on-success tblPEG">
                                                <input type="checkbox" class="custom-control-input" id="PEGPort" name="PEGPort">
                                                <label class="custom-control-label" for="PEGPort"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Plecado</td>
                                        <td class="text-center">
                                            <div class="custom-control custom-switch ustom-switch-on-success tblPLE">
                                                <input type="checkbox" class="custom-control-input" id="PLEint1" name="PLEint1">
                                                <label class="custom-control-label" for="PLEint1"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="custom-control custom-switch ustom-switch-on-success tblPLE">
                                                <input type="checkbox" class="custom-control-input" id="PLEint2" name="PLEint2">
                                                <label class="custom-control-label" for="PLEint2"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="custom-control custom-switch ustom-switch-on-success tblPLE">
                                                <input type="checkbox" class="custom-control-input" id="PLEint3" name="PLEint3">
                                                <label class="custom-control-label" for="PLEint3"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="custom-control custom-switch ustom-switch-on-success tblPLE">
                                                <input type="checkbox" class="custom-control-input" id="PLEPort" name="PLEPort">
                                                <label class="custom-control-label" for="PLEPort"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Otro </td>
                                        <td><input type="text" class="form-control tblAO" id="AOint1" name="AOint1" required autocomplete="off"></td>
                                        <td><input type="text" class="form-control tblAO" id="AOint2" name="AOint2" required autocomplete="off"></td>
                                        <td><input type="text" class="form-control tblAO" id="AOint3" name="AOint3" required autocomplete="off"></td>
                                        <td><input type="text" class="form-control tblAO" id="AOPort" name="AOPort" required autocomplete="off"></td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                <!-- </div> -->

            </div>
        </div>


    </form>
</div>
<script src=" {{ asset('js/elementos_presupuestados.js') }} " defer></script>
@include('elementos_presupuestados.mod_Paper') @endsection