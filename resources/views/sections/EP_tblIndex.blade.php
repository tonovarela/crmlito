<div class="mt-2" style="height: inherit; overflow-y: auto !important; ">
    {{--  <table onclick="ajustarTablas()" id="tblElementosPresupuestados" class="table table-hover table-striped nowrap compact tblnombre">
        <thead>
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
                <th style="visibility: hidden"></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th style="visibility: hidden"></th>
                <th></th>
            </tr>
        </thead>
        <tfoot></tfoot>
    </table>  --}}
    <div class="my-2 float-right">
        <div class="float-left mr-2">
            @if ($oportunidad[0]->preSolicitoCambios == 1 && ($idrol == 4 || $idrol == 9 || $idrol == 1 || $idrol == 8))
            <button class="btn text-sm btn-solicitar-cambios" id="btnRegistroMultipresupuesto" onclick="solicitarCambios()" type="button">
                <span class="rainbow-text">Solicitar cambios</span>
            </button>
            @endif
        </div>
        
        @if ($idrol == 10 || $idrol == 1 || $idrol == 8 || $idrol == 2)
            {{--  1 multiple, 2 individual  --}}
            <button class="btn btn-info text-sm d-none" id="btnNuevoPresupuesto" onclick="abrirModalRegistroPresupuesto(1)" type="button">
                Nuevo presupuesto (Múltiple)
            </button>

            @if (($oportunidad[0]->idEstatus == 7) && ($idrol == 3 || $idrol == 2 || $idrol == 1 || $idrol == 8 || $idrol == 10))
            <button class="btn btn-info text-sm {{ $oportunidad[0]->tieneExcel == 1 ? '' : 'sr-only' }}" id="btnRegistroMultipresupuesto" onclick="abrirModalMultipresupuestoExcel()" type="button">
                Multi-elementos para presupuesto (Excel)
            </button>
            @endif
        @endif

        <input type="hidden" id="activarSeleccion" value="0">

        <select name="categoriasFsc" id="categoriasFsc" class="sr-only">
            <option value="">Seleccionar categoría</option>
            @foreach ($catCategoriasFsc as $categoria)
                <option value="{{ $categoria->id }}">{{ $categoria->categoria }}</option>
            @endforeach
        </select>
        
        <select name="subcategoriasFsc" id="subcategoriasFsc" class="sr-only">
            <option value="">Seleccionar subcategoría</option>
            @foreach ($catSubCategoriasFsc as $subcategoria)
                <option value="{{ $subcategoria->id }}">{{ $subcategoria->subcategoria }}</option>
            @endforeach
        </select>
    </div>

    <div id="seccionCambios" style="display: none;">
        <h5 style="margin-bottom: 1rem;">Elementos Presupuestados (Máximo 5)</h5>

        <table id="elementosV2" class="table table-hover table-striped nowrap compact" style="width: 100% !important;"  onclick="ajustarTablas()">
            <thead class="bg-gray" style="color: #fff !important;">
                <tr>
                    <th></th>
                    <th>Folio</th>
                    <th>Nombre proyecto</th>
                    <th>Vendedor</th>
                    <th>Categoría</th>
                    <th>Tipo de Producto</th>
                    <th>Oportunidad</th>
                    <th>Cliente</th>
                    <th>Pdf</th>
                    <th>Clonar</th>
                    <th>Eliminar</th>
                    <th>Registrado por</th>
                    <th>Creado</th>
                    <th>Finalizar Cambios</th>
                    <th>Acciones</th>
                </tr>
            </thead>
        </table>
    </div>

    <br>

    <div id="historicoCambiosContainer"></div>

    <div class="sr-only">
        <h5 style="margin-bottom: 1rem;">Elementos Presupuestados (Excel)</h5>

        <div class="d-flex justify-content-end align-items-center mb-2" id="divBtnNuevoPresupuestoExcel" >
            <button class="btn btn-info text-sm float-right mb-2" id="btnNuevoPresupuestoExcel" onclick="validarEstatusParaExcel()" type="button">
                Cargar Excel EP
            </button>
        </div>

        <table id="elementosExcel" class="table table-hover table-striped nowrap compact" style="width: 100% !important;"  onclick="ajustarTablas()">
            <thead class="bg-gray" style="color: #fff !important;">
                <tr>
                    <th>Folio</th>
                    <th>Total EPs</th>
                    <th>Usuario registro</th>
                    <th>Estatus</th>
                    <th>Fin. Cambio</th>
                    <th>Fecha Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
        </table>
    </div>

    {{--  Seccion para cambios de excel  --}}
    <div class="mt-4 {{ $oportunidad[0]->tieneExcel == 1 ? '' : 'sr-only' }}" style="display: none;" id="seccionCambiosExcel">
        <h5 style="margin-bottom: 1rem;">Detalle de Excel EP</h5>

        @if ($idrol == 4 || $idrol == 9 || $idrol == 1 || $idrol == 8)
            <div class="d-flex justify-content-end align-items-center mb-2" id="divBtnNuevoPresupuestoExcelV2">
                <button class="btn btn-info text-sm float-right mb-2" id="btnNuevoPresupuestoExcelV2" onclick="abrirModalExcelEPVista()" type="button">
                    Cargar Excel EP (Vista)
                </button>
            </div>
        @endif

        <table id="elementosExcelVista" class="table table-hover table-striped nowrap compact" style="width: 100% !important;" onclick="ajustarTablas()">
            <thead class="bg-gray" style="color: #fff !important;">
                <tr>
                    <th>Nombre Archivo</th>
                    <th>Extension</th>
                    <th>Peso</th>
                    <th>Archivo para descargar</th>
                    <th>Usuario</th>
                    <th>Estatus</th>
                    <th>Fin. Cambio</th>
                    <th>Fecha Registro</th>
                </tr>
            </thead>
        </table>
    </div>

    <style>
        .bg-yellow-100 {
            background-color: #fef3c7 !important;
        }
    </style>
</div>

<script src=" {{ asset('js/elementos_presupuestados_tblindex.js?n=1') }} " defer></script>
