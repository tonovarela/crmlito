<div class="modal fade" id="modal-permisos-usuario" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form method="post" class="form-horizontal" data-toggle="validator">
                <div class="modal-body">
                    <div class="col-12">
                        <input type="hidden" name="IdUsuario" id="idUsuario">
                        <div class="card-body">
                            <div class="rounded bg-gray">
                                <div class="row mb-2 mt-1">
                                    <div class="col-sm-12">
                                        <h6 class="titulo text-bold pt-2 pl-1">Permisos Asignados</h6>
                                    </div>
                                </div>
                            </div>
                            <h6 class="nombre text-bold pt-1 pl-1 text-uppercase"></h6>
                            <div class="row m-2">
                                <span>{{ session('id_usuario_permisos') }}</span>
                                <div class="col-12">
                                    <table id="tablePermisos" class="table table-hover table-bordered table-striped nowrap compact">
                                        <thead>
                                            <tr class="bg-gray">
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <hr>
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-contactos">
                    <div class="text-center input-group-append col-md-12">
                        <span class="col-md-6 offset-md-3">
                            <button type="button" class="rounded-lg btn btn-primary btn-sm btn-block"
                                data-dismiss="modal">Cerrar</button>
                        </span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
