@extends('layout.master')

@section('content')
<div class="container-fluid">


<div class="content-header">
    <div class="row mb-2">
        <div class="col-sm-4">
            <h1>Elementos Presupuestados</h1>
        </div>
        <div class="col-sm-6 justify-content-md-center">
            <div class="col-sm-11 input-group input-group-sm ">
                <input type="text" id="searchxportunidad" class="form-control" placeholder="Buscar ...">
                <span class="input-group-append"><button type="button" id="btn-refresh"
                        class="btn btn-primary btn-flat"><i class="nav-icon fas fa-search"></i></button></span>
            </div>
        </div>
        <div class="col-sm-2">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item" style="margin-right: 5px;">
                    <button type="button" class="btn btn-block btn-primary btn-sm" id="btn-filters"
                        data-bs-toggle="tooltip" data-bs-placement="bottom" title="Mostrar Filtros" > 
                        <i class="nav-icon fas fa-filter"></i>
                    </button>
                </li>
                
                <li style="margin-right: 5px;">
                    <a class="button btn btn-block btn-primary btn-sm" data-bs-toggle="tooltip"
                        data-bs-placement="bottom" title="Nuevo Elemento" id="nvoElemento" href="{{ route('Nuevo Elemento') }}">
                        <i class="fas fas fa-file"></i>
                    </a>
                </li>
                
            </ol>
        </div>
    </div>
</div>
<div class="filter-hide" id="card-filtros1">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Filtros</h3>
        </div>
        <div class="card-body">
            <div class="form-group ">
                <div class="row justify-content-md-center slt" id="slt-filtro0">
                    <div class="col-sm-9 input-group input-group-sm justify-content-md-left">
                        <div class="input-group-prepend">
                            <select class="card-primary form-control  select2 slt_filtroOp" data-placeholder="Filtro"
                                style="width: 100%;" id="slt-filtro" data-idslt='0'>
                                <option selected="selected">Elige un Filtro</option>
                                <option value="ID" data-columns="0">Número de Oportunidad</option>
                                <option value="nomOportunidad" data-columns="1">Nombre de Oportunidad</option>
                                <option value="descEstatus" data-columns="2">Estatus</option>
                                <option value="fecCreacion" data-columns="3">Fecha de creación</option>
                                <option value="desTipoOportundidad" data-columns="4">Tipo de Oportunidad</option>
                            </select>
                            {{-- </div> --}}
                        </div>
                        <input type="text" class="column_filterOp form-control searchCliente_0" placeholder="Buscar ...">
                        <span class="input-group-append"><button type="button"
                                class="btn btn-danger btn-flat btn-deleteF Dslt_0" data-bs-toggle="tooltip"
                                title="Eliminar Filtro" data-idslt='0'> <i
                                    class="nav-icon fas fa-minus"></i></button></span>
                        <span class="input-group-append"><button type="button"
                                class="btn btn-primary btn-flat btn-saveF Sslt_0" data-bs-toggle="tooltip"
                                title="Guardar Filtro" data-idslt='0'> <i
                                    class="nav-icon fas fa-save"></i></button></span>
                    </div>
                </div>
            </div>
            <div class="form-group " id="box-filtros"></div>
        </div>
        <div class="card-footer">
            <button id="btn-refresh" class="btn btn-primary btn-refreshOp btn-flat float-right"><i
                    class="nav-icon fas fa-undo"></i> Limpiar </button>
            <button class="btn btn-success btn-flat btn-addFiltreOp float-right"><i class="nav-icon fas fa-plus"> </i>
                Agregar filtro</button>

        </div>
    </div>
</div>
<div class="mt-2">
    <table id="tblOportunidades" class="table table-hover table-striped nowrap compact">
        <thead>
            <tr class="bg-gray">
                <th>ID</th>
                <th>Atendido por</th>
                <th>Fecha de Creación</th>
                <th>Oportunidad</th>
                <th>Num Orden MTC</th>
                <th>Cliente</th>
                <th>Asignado a:</th>
                <th>Tipo Oportunidad</th>
                <th>Estatus</th>
                <th>Probabilidad Ganado</th>
                <th>Valor</th>
                <th>PreReg</th>
                <th>SolCot</th>
                <th>LisCot</th>
                <th>Cotizado</th>
                <th>Revisión</th>
                <th>VoBo</th>
                <th>LtoP/Pre</th>
                <th>PtoTmdo</th>
                <th>RestFnal</th>
                <th>OPConcl</th>
                <th>CierPrev</th>
                
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
</div>

@endsection
