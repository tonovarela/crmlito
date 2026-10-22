<div class="modal fade" id="modal-tiempo-entrega" tabindex="-1" role="dialog" data-backdrop="static"
    data-keyboard="false" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" class="form-horizontal" data-toggle="validator">
                <div class="modal-body">
                    <div class="col-12">
                        <div class="card-body">
                            <input type="hidden" name="idOportunidadTEntrega" id="idOportunidadTEntrega">
                            <input type="hidden" name="idUsuarioTEntrega" id="idUsuarioTEntrega">
                            <input type="hidden" name="idTiempoEntregaOprotunidad" id="idTiempoEntregaOprotunidad">
                            <div class="rounded bg-gray">
                                <div class="row mb-2 mt-1">
                                    <div class="col-sm-12">
                                        <h6 class="text-center text-bold pt-2 titulo" style="cursor: move">Tiempo de
                                            Entrega
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-12">
                                    <label for="Autorizo">Autoriza</label>
                                    <div class="input-group">
                                        <input id="Autorizo" name="Autorizo" type="text" class="form-control"
                                            placeholder="Autorizo" autocomplete="off" value="{{ session('nombre') }}"
                                            required readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-1" style="display: none">
                                <div class="form-group col-md-4">
                                    <div class="custom-control custom-radio mt-4">
                                        <input class="custom-control-input" type="radio" id="dias" name="diasOFecha"
                                            value="dias">
                                        <label for="dias" class="custom-control-label">Días de Entrega</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="fecArchivoAutorizado">Días</label>
                                    <input id="DiasEntrega" name="DiasEntrega" type="number" class="form-control"
                                        placeholder="Días de Entrega" autocomplete="off" required disabled>
                                </div>
                                <div class="col-md-4">
                                    <label for="fecArchivoAutorizado">Fecha Archivo Autorizado</label>
                                    <div class="input-group">
                                        <input id="fecArchivoAutorizado" name="fecArchivoAutorizado" type="date"
                                            class="form-control" autocomplete="off" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-1" style="display: none">
                                <div class="form-group col-md-4">
                                    <div class="custom-control custom-radio mt-4">
                                        <input class="custom-control-input" type="radio" id="fecha" name="diasOFecha"
                                            value="fecha">
                                        <label for="fecha" class="custom-control-label">Fecha de
                                            Entrega</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="FechaAutorizaEnt">Si el archivo se autoriza el </label>
                                    <div class="input-group">
                                        <input id="FechaAutorizaEnt" name="FechaAutorizaEnt" type="date" class="form-control"
                                            autocomplete="off" required readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="FechaEntregaEnt">La fecha de entrega es </label>
                                    <div class="input-group">
                                        <input id="FechaEntregaEnt" name="FechaEntregaEnt" type="date" class="form-control"
                                            autocomplete="off" required disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Fecha Primera Entrega</label>
                                        <div class="input-group date" id="FechaPrimeraEntrega">
                                                <input id="FechaPrimeraEntregaInput" name="FechaPrimeraEntrega" type="text"
                                                    class="form-control datetimepicker-input"
                                                    data-target="#FechaPrimeraEntrega" placeholder="Requerido" required />
                                            <div class="input-group-append" data-target="#FechaPrimeraEntrega"
                                                data-toggle="datetimepicker">
                                                <label class="labelCalendar" for="FechaPrimeraEntregaInput">
                                                    <div class="divCalendar input-group-text"><i
                                                            class="fa fa-calendar"></i>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Fecha Última Entrega</label>
                                        <div class="input-group date" id="FechaUltimaEntrega">
                                                <input id="FechaUltimaEntregaInput" name="FechaUltimaEntrega" type="text"
                                                    class="form-control datetimepicker-input"
                                                    data-target="#FechaUltimaEntrega" placeholder="Requerido" required />
                                            <div class="input-group-append" data-target="#FechaUltimaEntrega"
                                                data-toggle="datetimepicker">
                                                <label class="labelCalendar" for="FechaUltimaEntregaInput">
                                                    <div class="divCalendar input-group-text"><i
                                                            class="fa fa-calendar"></i>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-12">
                                    <label for="Comentarios">Comentarios</label>
                                    <div class="input-group">
                                        <textarea name="Comentarios" id="Comentarios" cols="100" rows="2"
                                            class="form-control" placeholder="Comentarios" autocomplete="off"
                                            required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer modal-footer-contactos">
                        <div class="text-center input-group-append col-md-12">
                            <button type="button" class="rounded-lg btn btn-primary btn-sm btn-block col-md-6"
                                id="saveBtnTEntrega" value="create">
                                Guardar
                            </button>
                            <span class="col-md-6">
                                <button type="button" class="rounded-lg btn btn-secondary btn-sm btn-block"
                                    data-dismiss="modal">Regresar</button>
                            </span>
                        </div>
                    </div>
            </form>
        </div>
    </div>
</div>