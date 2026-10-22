<div class="modal fade" id="modal-create-elemento">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="margin-left: 40%">Nuevo Elemento Presupuestado</h4>
                {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> --}}
            </div>
            <div class="modal-body">
                <div class="col-12">
                    <!-- general form elements -->
                    <form>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="nombre_oportunidad">Elemento Presupuestado</label>
                                <div class="input-group">
                                    <input type="text" class="form-control col-md-9 col-lg-10" id="nombre_oportunidad_ele"
                                        placeholder="Requerido" required autocomplete="off">
                                    <div class="input-group-append col-md-3 col-lg-2">
                                        <span class="col-md-8 col-lg-7 pt-2"><a href="#" class="btn-sm"
                                                data-dismiss="modal">Cancelar</a></span>
                                        <span class="col-md-4 col-lg-5"><button type="button"
                                                class="btn btn-primary btn-sm">Guardar</button></span>
                                    </div>
                                </div>
                            </div>
                            <div id="scrollbarTabsModalRound">
                                <div class="mt-2">
                                    <div class="col-md-12">
                                        <div id="accordion2">
                                            <div class="card card-secondary">
                                                <div class="card-header">
                                                    <h4 class="card-title w-100">
                                                        <a class="d-block w-100" data-toggle="collapse"
                                                            href="#collapse1">
                                                            - ITEM
                                                        </a>
                                                    </h4>
                                                </div>
                                                <div id="collapse1" class="collapse show" data-parent="#accordion2">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label for="cantidad1">Cantidad 1</label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control"
                                                                        id="cantidad1" required autocomplete="off">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="folio">Folio</label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" id="folio"
                                                                        required autocomplete="off">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label for="cantidad2">Cantidad 2</label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control"
                                                                        id="cantidad2" required autocomplete="off">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="num_presupuesto">Numero Presupuesto</label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control"
                                                                        id="num_presupuesto" required
                                                                        autocomplete="off">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label for="cantidad3">Cantidad 3</label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control"
                                                                        id="cantidad3" required autocomplete="off">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="tipo_item">Tipo Item</label>
                                                                <select class="input-group select2"
                                                                    style="width: 100%;">
                                                                    <option selected="selected" disabled>
                                                                        Seleccionar...</option>
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
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label>Fecha y Hora Limite Cotizacion</label>
                                                                <div class="input-group date" id="reservationdatetime5"
                                                                    data-target-input="nearest">
                                                                    <input type="text"
                                                                        class="form-control datetimepicker-input"
                                                                        data-target="#reservationdatetime5" />
                                                                    <div class="input-group-append"
                                                                        data-target="#reservationdatetime5"
                                                                        data-toggle="datetimepicker">
                                                                        <div class="input-group-text"><i
                                                                                class="fa fa-calendar"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>Fecha Req. de Entrega Producto</label>
                                                                <div class="input-group date" id="reservationdate4"
                                                                    data-target-input="nearest">
                                                                    <input type="text"
                                                                        class="form-control datetimepicker-input"
                                                                        data-target="#reservationdate4"
                                                                        placeholder="(Requerido) dd-mm-aaaa" />
                                                                    <div class="input-group-append"
                                                                        data-target="#reservationdate4"
                                                                        data-toggle="datetimepicker">
                                                                        <div class="input-group-text"><i
                                                                                class="fa fa-calendar"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card card-secondary">
                                                <div class="card-header">
                                                    <h4 class="card-title w-100">
                                                        <a class="d-block w-100" data-toggle="collapse"
                                                            href="#collapse2">
                                                            - CONDICIONES DE VENTA
                                                        </a>
                                                    </h4>
                                                </div>
                                                <div id="collapse2" class="collapse" data-parent="#accordion2">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-6">
                                                            </div>
                                                            <div class="col-md-6">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card card-secondary">
                                                <div class="card-header">
                                                    <h4 class="card-title w-100">
                                                        <a class="d-block w-100" data-toggle="collapse"
                                                            href="#collapse3">
                                                            - ESPECIFICACIONES TÉCNICAS
                                                        </a>
                                                    </h4>
                                                </div>
                                                <div id="collapse3" class="collapse" data-parent="#accordion2">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-6">
                                                            </div>
                                                            <div class="col-md-6">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card card-secondary">
                                                <div class="card-header">
                                                    <h4 class="card-title w-100">
                                                        <a class="d-block w-100" data-toggle="collapse"
                                                            href="#collapse4">
                                                            - INTERIORES 1
                                                        </a>
                                                    </h4>
                                                </div>
                                                <div id="collapse4" class="collapse" data-parent="#accordion2">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-6">
                                                            </div>
                                                            <div class="col-md-6">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card card-secondary">
                                                <div class="card-header">
                                                    <h4 class="card-title w-100">
                                                        <a class="d-block w-100" data-toggle="collapse"
                                                            href="#collapse5">
                                                            - ACABADOS INTERIOR 1
                                                        </a>
                                                    </h4>
                                                </div>
                                                <div id="collapse5" class="collapse" data-parent="#accordion2">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-6">
                                                            </div>
                                                            <div class="col-md-6">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card card-secondary">
                                                <div class="card-header">
                                                    <h4 class="card-title w-100">
                                                        <a class="d-block w-100" data-toggle="collapse"
                                                            href="#collapse6">
                                                            - INTERIORES 2
                                                        </a>
                                                    </h4>
                                                </div>
                                                <div id="collapse6" class="collapse" data-parent="#accordion2">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-6">
                                                            </div>
                                                            <div class="col-md-6">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card card-secondary">
                                                <div class="card-header">
                                                    <h4 class="card-title w-100">
                                                        <a class="d-block w-100" data-toggle="collapse"
                                                            href="#collapse7">
                                                            - ACABADOS INTERIOR 2
                                                        </a>
                                                    </h4>
                                                </div>
                                                <div id="collapse7" class="collapse" data-parent="#accordion2">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-6">
                                                            </div>
                                                            <div class="col-md-6">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card card-secondary">
                                                <div class="card-header">
                                                    <h4 class="card-title w-100">
                                                        <a class="d-block w-100" data-toggle="collapse"
                                                            href="#collapse8">
                                                            - INTERIORES 3
                                                        </a>
                                                    </h4>
                                                </div>
                                                <div id="collapse8" class="collapse" data-parent="#accordion2">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-6">
                                                            </div>
                                                            <div class="col-md-6">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card card-secondary">
                                                <div class="card-header">
                                                    <h4 class="card-title w-100">
                                                        <a class="d-block w-100" data-toggle="collapse"
                                                            href="#collapse9">
                                                            - ACABADOS INTERIOR 3
                                                        </a>
                                                    </h4>
                                                </div>
                                                <div id="collapse9" class="collapse" data-parent="#accordion2">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-6">
                                                            </div>
                                                            <div class="col-md-6">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card card-secondary">
                                                <div class="card-header">
                                                    <h4 class="card-title w-100">
                                                        <a class="d-block w-100" data-toggle="collapse"
                                                            href="#collapse10">
                                                            - PORTADAS
                                                        </a>
                                                    </h4>
                                                </div>
                                                <div id="collapse10" class="collapse" data-parent="#accordion2">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-6">
                                                            </div>
                                                            <div class="col-md-6">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card card-secondary">
                                                <div class="card-header">
                                                    <h4 class="card-title w-100">
                                                        <a class="d-block w-100" data-toggle="collapse"
                                                            href="#collapse11">
                                                            - ACABADOS PORTADAS
                                                        </a>
                                                    </h4>
                                                </div>
                                                <div id="collapse11" class="collapse" data-parent="#accordion2">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-6">
                                                            </div>
                                                            <div class="col-md-6">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card card-secondary">
                                                <div class="card-header">
                                                    <h4 class="card-title w-100">
                                                        <a class="d-block w-100" data-toggle="collapse"
                                                            href="#collapse12">
                                                            - ENCUADERNADO
                                                        </a>
                                                    </h4>
                                                </div>
                                                <div id="collapse12" class="collapse" data-parent="#accordion2">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-6">
                                                            </div>
                                                            <div class="col-md-6">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card card-secondary">
                                                <div class="card-header">
                                                    <h4 class="card-title w-100">
                                                        <a class="d-block w-100" data-toggle="collapse"
                                                            href="#collapse13">
                                                            - EMPAQUE ESPECIAL
                                                        </a>
                                                    </h4>
                                                </div>
                                                <div id="collapse13" class="collapse" data-parent="#accordion2">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-6">
                                                            </div>
                                                            <div class="col-md-6">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card card-secondary">
                                                <div class="card-header">
                                                    <h4 class="card-title w-100">
                                                        <a class="d-block w-100" data-toggle="collapse"
                                                            href="#collapse14">
                                                            - ACABADOS GENERALES
                                                        </a>
                                                    </h4>
                                                </div>
                                                <div id="collapse14" class="collapse" data-parent="#accordion2">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-6">
                                                            </div>
                                                            <div class="col-md-6">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card card-secondary">
                                                <div class="card-header">
                                                    <h4 class="card-title w-100">
                                                        <a class="d-block w-100" data-toggle="collapse"
                                                            href="#collapse15">
                                                            - DISTRIBUCIÓN
                                                        </a>
                                                    </h4>
                                                </div>
                                                <div id="collapse15" class="collapse" data-parent="#accordion2">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-6">
                                                            </div>
                                                            <div class="col-md-6">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card card-secondary">
                                                <div class="card-header">
                                                    <h4 class="card-title w-100">
                                                        <a class="d-block w-100" data-toggle="collapse"
                                                            href="#collapse16">
                                                            - MOSTRAR MÁS
                                                        </a>
                                                    </h4>
                                                </div>
                                                <div id="collapse16" class="collapse" data-parent="#accordion2">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-6">
                                                            </div>
                                                            <div class="col-md-6">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                </form>
            </div>
        </div>
        {{-- <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div> --}}
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>