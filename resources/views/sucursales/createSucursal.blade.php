<div class="modal fade" id="modal-create-sucursalCte" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form method="post" class="form-horizontal" data-toggle="validator">
                <div class="modal-body">
                    <div class="col-12">
                        <input type="hidden" name="idSucursal" id="idSucursal">
                        <input type="hidden" name="idSucursalINT" id="idSucursalINT">
                        <input type="hidden" value="{{ $id_usuario }}" name="idUsuario" id="idUsuarioSuc">
                        <input type="hidden" name="idClienteINTSuc" id="idClienteINTSuc">
                        <div class="card-body">
                            <div class="rounded bg-gray">
                                <div class="row mb-2 mt-1">
                                    <div class="col-sm-12">
                                        <h6 class="titulo text-bold pt-2 pl-1" style="cursor: move">Nueva Sucursal</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-1" id="SelectCliente">
                                <div class="col-12">
                                    <label for="idClienteINT">Cliente</label>

                                    {{-- <select id="idCliente" name="idCliente"
                                        class="form-control col-md-8 col-lg-9 col-xl-10 select2 text-uppercase">
                                        <option selected disabled>Seleccionar Cliente...</option>
                                        @foreach ($combo_clientes as $cliente)
                                        <option value="{{ $cliente->ClienteINT }}">
                                            {{ $cliente->razonsocial }}
                                        </option>
                                        @endforeach
                                    </select>
                                    --}}




                                    <select id="idClienteINT" name="idClienteINT"
                                        class="form-control select2  select2-height text-uppercase" style="width: 100%" required>
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
                                <div class="col-md-12">
                                    <label for="Nombre">Nombre</label>
                                    <div class="input-group">
                                        <input id="Nombre" name="Nombre" type="text" class="form-control"
                                            placeholder="Nombre" autocomplete="off" value="{{ old('Nombre') }}"
                                            autofocus required>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-12">
                                    <label for="Encargado">Encargado</label>
                                    <div class="input-group">
                                        <input id="Encargado" name="Encargado" type="text" class="form-control"
                                            placeholder="Encargado" autocomplete="off" value="{{ old('Encargado') }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label for="eMail1">Correo Encargado</label>
                                    <div class="input-group">
                                        <input id="eMail1" name="eMail1" type="text" class="form-control"
                                            placeholder="Correo Encargado" autocomplete="off"
                                            value="{{ old('eMail1') }}">
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="row mb-1">
                                <div class="col-md-12 mt-1">
                                    <input id="mismaDireccion" name="mismaDireccion" type="hidden">
                                    <div class="custom-control custom-switch">
                                        <input id="mismaDireccion_check" type="checkbox" class="custom-control-input"
                                            disabled>
                                        <label class="custom-control-label" for="mismaDireccion_check">Misma dirección
                                            Matriz</label>
                                    </div>
                                    <input type="text" class="col-12 border-0 text-red text-bold text-center" disabled
                                        id="mismaDireccion_check_validate"
                                        value="Ya existe una sucursal con dirección fiscal">
                                </div>
                            </div> --}}
                            <div class="row mb-1">
                                <div class="col-md-6">
                                    <label for="Direccion">Calle</label>
                                    <div class="input-group">
                                        <input id="Direccion" name="Direccion" type="text" class="form-control"
                                            placeholder="Calle" autocomplete="off" value="{{ old('Direccion') }}"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="DireccionNumero">Número Ext.</label>
                                    <div class="input-group">
                                        <input id="DireccionNumero" name="DireccionNumero" type="text"
                                            class="form-control" placeholder="Número Ext." autocomplete="off"
                                            value="{{ old('DireccionNumero') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="DireccionNumeroInt">Número Int.</label>
                                    <div class="input-group">
                                        <input id="DireccionNumeroInt" name="DireccionNumeroInt" type="text"
                                            class="form-control" placeholder="Número Int." autocomplete="off"
                                            value="{{ old('DireccionNumeroInt') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-8">
                                    <label for="CodigoPostalListaSuc">Códigos Postales</label>
                                    <div class="input-group">
                                        <input id="CodigoPostalListaSuc" type="text" class="form-control"
                                            placeholder="Código Postal" autocomplete="off" autofill="off">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="CodigoPostal">Código Postal</label>
                                    <div class="input-group">
                                        <input id="CodigoPostal" name="CodigoPostal" type="text" class="form-control"
                                            placeholder="Código Postal" autocomplete="off"
                                            value="{{ old('CodigoPostal') }}" required readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-6">
                                    <label for="Colonia">Colonia</label>
                                    <div class="input-group">
                                        <input id="Colonia" name="Colonia" type="text" class="form-control"
                                            placeholder="Colonia" autocomplete="off" value="{{ old('Colonia') }}"
                                            required readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="Delegacion">Alcadia / Municipio</label>
                                    <div class="input-group">
                                        <input id="Delegacion" name="Delegacion" type="text" class="form-control"
                                            placeholder="Alcadia / Municipio" autocomplete="off"
                                            value="{{ old('Delegacion') }}" required readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-4">
                                    <label for="Poblacion">Localidad</label>
                                    <div class="input-group">
                                        <input id="Poblacion" name="Poblacion" type="text" class="form-control"
                                            placeholder="Localidad" autocomplete="off" value="{{ old('Poblacion') }}"
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="Estado">Estado</label>
                                    <div class="input-group">
                                        <input id="Estado" name="Estado" type="text" class="form-control"
                                            placeholder="Estado" autocomplete="off" value="{{ old('Estado') }}" required
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="Pais">País</label>
                                    <div class="input-group">
                                        <input id="Pais" name="Pais" type="text" class="form-control" placeholder="País"
                                            autocomplete="off" value="{{ old('Pais') }}" required readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-12">
                                    <label for="Telefonos">Teléfonos</label>
                                    <div class="input-group">
                                        <input id="TelefonosSuc" name="Telefonos" type="text" class="form-control"
                                            placeholder="Teléfonos" autocomplete="off" value="{{ old('Telefonos') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-sucursales">
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