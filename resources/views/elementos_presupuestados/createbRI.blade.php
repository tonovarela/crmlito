@extends('layout.master') @section('content')

<div id="formOporEdit" class="col-12 mt-2">
    <form method="post" role="form">
        <!-- <div class="card-body"> -->
        <div class="form-group">
            <div class="input-group input-group-sm">
                <label class="mt-2" for="nomElementoPres">Elemento Presupuestado &nbsp;</label>
                <input type="text" class="form-control col-md-8 col-lg-9 col-xl-10" id="nomElementoPres" name="nomElementoPres" autocomplete="off" placeholder="Requerido..">
                <div class="input-group-append col-md-4 col-lg-3 col-xl-2">
                    <span class="col-md-6"><button id="btnSave" type="submit"
                                        class="btn btn-primary btn-sm ">Guardar</button></span>
                    <span class="col-md-6 center"><a href="{{ route('ElementosPresupuestados') }}"
                                        class="btn btn-secondary btn-sm ">Regresar</a></span>
                </div>
            </div>
        </div>
        <div>
            <span class="col-4">
                <div class="rounded-pill bg-blue" style="float: right">
                    <strong class="p-2 text-lg">
                        EP 001
                    </strong>
                </div>
            </span>
        </div>
        <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
            <div class="row">
                <div id="infGeneral" class="col-lg-7 col-sm-12 mt-2">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="idCliente">Cliente</label>
                                <select id="idCliente" name="idCliente" class="form-control select2" style="width: 100%">
                                    <option value='0' selected> Seleccionar...</option>
                                    @foreach ($combo_clientes as $Cliente)
                                    <option value="{{ $Cliente->ClienteINT}}">{{ $Cliente->LITONombreComercial }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="idOportunidad">Oportunidad</label>
                                <select id="idOportunidad" name="idOportunidad" class="form-control select2" style="width: 100%;">
                                    <option value='0' selected> Seleccionar...</option> 
                                    @foreach ($combo_oportunidades as $Oportunidad)
                                    <option value="{{ $Oportunidad->idOportunidad}}">{{ $Oportunidad->nomOportunidad }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Equipos</label>
                                <select id="idEquipo" name="idEquipo" class="form-control select2" style="width: 100%;">
                                    <option value='0' selected> Seleccionar...</option>
                                    @foreach ($equipos as $equipo)
                                    <option value="{{ $equipo->idEquipo }}">{{ $equipo->descEquipo }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="idAgente">Asignado a</label>
                                <select id="idAgente" name="idAgente" class="form-control select2" style="width: 100%;">
                                    <option value='0' selected> Seleccionar...</option>
                                    @foreach ($combo_vendedores as $vendedor)
                                    <option value="{{ $vendedor->Agente }}">{{ $vendedor->Nombre }}</option>
                                    @endforeach                
                                    </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tipo_item">Tipo Item</label>
                                <select class="input-group select2" style="width: 100%;">
                                    <option selected="selected" disabled>Seleccionar...</option>
                                    <option>FOLLETOS ENC. A GRAPA</option>
                                    <option>FOLLETOS ENC. A HOT MELT</option>
                                    <option>CALENDARIOS</option>
                                    <option>DISEÑO ORIGINALES</option>
                                    <option>IMPRESIÓN DIGITAL</option>
                                    <option>DISPLAY, GRAN FORMATO, PLOTTER</option>
                                    <option>EMBOLSADO</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="numPresupuestoMTC">Num Presupuesto</label>
                                <div class="input-group">
                                    <input id="numPresupuestoMTC" name="numPresupuestoMTC" type="text" class="form-control" placeholder="Num Presupuesto Metrics" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="from-group">
                                <div class="custom-control custom-switch ustom-switch-on-success mt-4">
                                    <input type="checkbox" class="custom-control-input" id="tbjoNvo" name="tbjoNvo">
                                    <label class="custom-control-label" for="customSwitch3">Trabajo Nuevo</label>


                                </div>
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
                                <label for="Estado">Precio Objetivo</label>
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


                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- AQUI ESPECIFICACIONES -->
        <!-- <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
            <div class="row">
                <div id="infGeneral" class="col-lg-7 col-sm-12 mt-2">
                </div>
            </div>
        </div> -->
    </form>
</div>
@endsection