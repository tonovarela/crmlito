<div class="modal fade" id="modal-create-contactoCte" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form method="post" class="form-horizontal" data-toggle="validator">
                {{-- {{ csrf_field() }} {{ method_field('POST') }} --}}
                <div class="modal-body">
                    <div class="col-12">
                        <input type="hidden" name="idContacto" id="idContacto">
                        <input type="hidden" name="idContactoINT" id="idContactoINT">
                        <input type="hidden" name="idClienteINTCto" id="idClienteINTCto">
                        <div class="card-body">
                            <div class="rounded bg-gray">
                                <div class="row mb-2 mt-1">
                                    <div class="col-sm-12">
                                        <h6 class="titulo text-bold pt-2 pl-1">Nuevo Contacto</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-1" id="SelectCliente">
                                <div class="col-12">
                                    <label for="idClienteINT">Cliente</label>
                                    <select id="idClienteINT" name="idClienteINT" class="form-control"
                                        style="width: 100%" required>
                                        <option selected disabled>Requerido</option>
                                        @foreach ($combo_clientes as $cliente)
                                            <option value="{{ $cliente->ClienteINT }}">
                                                {{ $cliente->razonsocial }}
                                            </option>
                                        @endforeach
                                    </select>
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
                                            placeholder="Nombre" autocomplete="off" value="{{ old('Nombre') }}" required>
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
                                        <input type="hidden" name="CFD_Enviar" id="CFD_Enviar_input">
                                        <input id="CFD_EnviarCto" type="checkbox"
                                            class="custom-control-input">
                                        <label class="custom-control-label" for="CFD_EnviarCto">Enviar CFD</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="custom-control custom-switch">
                                        <input type="hidden" name="contactoCXP" id="contactoCXP_input">
                                        <input id="contactoCXPCto" type="checkbox"
                                            class="custom-control-input">
                                        <label class="custom-control-label" for="contactoCXPCto">Contacto CXP</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="custom-control custom-switch">
                                        <input type="hidden" name="principal" id="principal_input">
                                        <input id="principalCto" type="checkbox"
                                            class="custom-control-input">
                                        <label class="custom-control-label" for="principalCto">Principal</label>
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
