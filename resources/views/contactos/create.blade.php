<div class="modal fade" id="modal-create-contacto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form method="post" class="form-horizontal" data-toggle="validator">
                {{ csrf_field() }} {{ method_field('POST') }}
                <div class="modal-body">
                    <div class="col-12">
                        <input type="hidden" name="idContacto" id="idContacto">
                        <input type="hidden" name="idContactoINT" id="idContactoINT">
                        <input type="hidden" value="{{ $id_usuario }}" name="idUsuario" id="idUsuarioCto">
                        <input type="hidden" value="{{ $cliente[0]->ClienteINT }}" name="idClienteINT"
                            id="idClienteINTCto">
                        <div class="card-body">
                            <div class="rounded bg-gray">
                                <div class="row mb-2 mt-1">
                                    <div class="col-sm-12">
                                        <h6 class="titulo text-bold pt-2 pl-1">Nuevo Contacto</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-3">
                                    <label for="Tratamiento">Tratamiento</label>
                                    <div class="input-group">
                                        <input id="Tratamiento" name="Tratamiento" type="text" class="form-control"
                                            placeholder="Tratamiento" autocomplete="off"
                                            value="{{ old('Tratamiento') }}" autofocus>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <label for="Nombre">Nombre</label>
                                    <div class="input-group">
                                        <input id="Nombre" name="Nombre" type="text" class="form-control"
                                            placeholder="Nombre" autocomplete="off" value="{{ old('Nombre') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-6">
                                    <label for="Cargo">Cargo</label>
                                    <div class="input-group">
                                        <input id="Cargo" name="Cargo" type="text" class="form-control"
                                            placeholder="Cargo" autocomplete="off" value="{{ old('Cargo') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="Departamento">Departamento</label>
                                    <div class="input-group">
                                        <input id="Departamento" name="Departamento" type="text" class="form-control"
                                            placeholder="Departamento" autocomplete="off"
                                            value="{{ old('Departamento') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-12">
                                    <label for="Telefonos">Teléfonos</label>
                                    <div class="input-group">
                                        <input id="TelefonosCto" name="Telefonos" type="text" class="form-control"
                                            placeholder="Teléfonos" autocomplete="off" value="{{ old('Telefonos') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-12">
                                    <label for="email">Correo</label>
                                    <div class="input-group">
                                        <input id="email" name="email" type="text" class="form-control"
                                            placeholder="email" autocomplete="off" value="{{ old('email') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-1 text-center">
                                <div class="col-md-4">
                                    <div class="custom-control custom-switch">
                                        <input id="CFD_Enviar" name="CFD_Enviar" type="checkbox"
                                            class="custom-control-input">
                                        <label class="custom-control-label" for="CFD_Enviar">Enviar CFD</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="custom-control custom-switch">
                                        <input id="contactoCXP" name="contactoCXP" type="checkbox"
                                            class="custom-control-input">
                                        <label class="custom-control-label" for="contactoCXP">Contacto CXP</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="custom-control custom-switch">
                                        <input id="principal" name="principal" type="checkbox"
                                            class="custom-control-input">
                                        <label class="custom-control-label" for="principal">Principal</label>
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
                        {{-- <span class="col-md-6"><button type="submit"
                            class="rounded-lg btn btn-primary btn-sm btn-block">Guardar</button></span> --}}
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
