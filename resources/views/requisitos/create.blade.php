<div class="modal fade" id="modal-create-requisito" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form method="post" class="form-horizontal" data-toggle="validator">
                {{ csrf_field() }} {{ method_field('POST') }}
                <div class="modal-body">
                    <div class="col-12">
                        <input type="hidden" name="idRequisito" id="idRequisito">
                        <div class="card-body">
                            <div class="rounded bg-gray">
                                <div class="row mb-2 mt-1">
                                    <div class="col-sm-12">
                                        <h6 class="titulo text-bold pt-2 pl-1">Nuevo Requisito</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-12">
                                    <label for="descRequisito">Descripción</label>
                                    <div class="input-group">
                                        <input id="descRequisito" name="descRequisito" type="text" class="form-control"
                                            placeholder="Descripción" autocomplete="off"
                                            value="{{ old('descRequisito') }}" autofocus>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-contactos">
                    <div class="text-center input-group-append col-md-12">
                        <button type="submit" class="rounded-lg btn btn-primary btn-sm btn-block" id="saveBtn"
                            value="create">
                            Guardar
                        </button>
                        <span class="col-md-6">
                            <button type="button" class="rounded-lg btn btn-secondary btn-sm btn-block"
                                data-dismiss="modal">Cancelar</button>
                        </span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
