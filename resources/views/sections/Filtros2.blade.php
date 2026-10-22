<div class="filter-hide" id="card-filtros2">
    <div class="card card-primary">
        <input type="hidden" id='nametable2' value="{{ $tabla }}">
        <input type="hidden" id='namedatatable2' value="{{ $datatable }}">
        <div class="card-header">
            <h3 class="card-title">Filtros</h3>
        </div>
        <div class="card-body">
            <div class="form-group ">
                <div class="row justify-content-md-center slt" id="slt-filtro-20">
                    <div class="col-sm-9 input-group input-group-sm justify-content-md-left">
                        <div class="input-group-prepend">
                            <select class="card-primary form-control select2 slt_filtro_2" data-placeholder="Filtro"
                                style="width: 100%;" id="slt-filtro-2" data-idslt-2='0'>
                                <option selected="selected">Elige un Filtro</option>
                            </select>
                        </div>
                        <input type="text" class="column_filter_2 form-control searchCliente2_0" placeholder="Buscar ...">
                        <span class="input-group-append"><button type="button"
                                class="btn btn-primary btn-flat btn-saveF-2 Sslt2_0 btn_SearchColumn_2" title="Buscar"
                                data-idslt-2='0'>
                                <i class="nav-icon fas fa-search"></i></button>
                        </span>
                        <span class="input-group-append"><button type="button"
                                class="btn btn-danger btn-flat btn-deleteF-2 Dslt2_0" title="Eliminar Filtro"
                                data-idslt-2='0'> <i class="nav-icon fas fa-minus"></i></button></span>
                        <span class="input-group-append"><button type="button"
                                class="btn btn-primary btn-flat btn-saveF-2 Sslt2_0" title="Guardar Filtro" data-idslt-2='0'
                                hidden> <i class="nav-icon fas fa-save"></i></button></span>
                    </div>
                </div>
            </div>
            <div class="form-group " id="box-filtros-2"></div>
        </div>
        <div class="card-footer">
            <button id="btn-refresh" class="btn btn-primary btn-refresh-2 btn-flat float-right"><i
                    class="nav-icon fas fa-undo"></i> Limpiar </button>
            <button class="btn btn-success btn-flat btn-addF-2 float-right"><i class="nav-icon fas fa-plus"> </i>
                Agregar filtro</button>

        </div>
    </div>
</div>
<script src=" {{ asset('js/filtros2.js?n=1') }} " defer></script>
