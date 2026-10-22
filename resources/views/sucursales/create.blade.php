<div class="modal fade" id="modal-create-sucursal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form method="post" class="form-horizontal" data-toggle="validator">
                {{-- {{ csrf_field() }} {{ method_field('POST') }} --}}
                <div class="modal-body">
                    <div class="col-12">
                        <input type="hidden" value="{{ $id_usuario }}" name="idUsuario" id="idUsuarioSuc">
                        <input type="hidden" name="idSucursal" id="idSucursal">
                        <input type="hidden" name="idSucursalINT" id="idSucursalINT">
                        <input type="hidden" name="idClienteINTSuc" id="idClienteINTSuc">
                        <input type="hidden" value="{{ $cliente[0]->ClienteINT }}" name="idClienteINT" id="idClienteINT">
                        <div class="card-body">
                            <div class="rounded bg-gray">
                                <div class="row mb-2 mt-1">
                                    <div class="col-sm-12">
                                        <h6 class="tituloSuc text-bold pt-2 pl-1" style="cursor: move">Nueva Sucursal</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-12">
                                    <label for="NombreSuc">Nombre</label>
                                    <div class="input-group">
                                        <input id="NombreSuc" name="Nombre" type="text" class="form-control"
                                            placeholder="Nombre" autocomplete="off" value="{{ old('Nombre') }}"
                                            autofocus required>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-12">
                                    <label for="EncargadoSuc">Encargado</label>
                                    <div class="input-group">
                                        <input id="EncargadoSuc" name="Encargado" type="text" class="form-control"
                                            placeholder="Encargado" autocomplete="off"
                                            value="{{ old('Encargado') }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label for="eMail1Suc">Correo Encargado</label>
                                    <div class="input-group">
                                        <input id="eMail1Suc" name="eMail1" type="text" class="form-control"
                                            placeholder="Correo Encargado" autocomplete="off" value="{{ old('eMail1') }}">
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="row mb-1">
                                <div class="col-md-12 mt-1">
                                    <input id="mismaDireccionSuc" name="mismaDireccion" type="hidden">
                                    <div class="custom-control custom-switch">
                                            <input id="mismaDireccion_checkSuc" type="checkbox"
                                                class="custom-control-input" disabled>
                                        <label class="custom-control-label" for="mismaDireccion_checkSuc">Misma dirección Matriz</label>
                                    </div>
                                    <input type="text" class="col-12 border-0 text-red text-bold text-center" disabled id="mismaDireccion_check_validateSuc" value="Ya existe una sucursal con dirección fiscal">
                                </div>
                            </div> --}}
                            <div class="row mb-1">
                                <div class="col-md-6">
                                    <label for="DireccionSuc">Calle</label>
                                    <div class="input-group">
                                        <input id="DireccionSuc" name="Direccion" type="text" class="form-control"
                                            placeholder="Calle" autocomplete="off" value="{{ old('Direccion') }}"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="DireccionNumeroSuc">Número Ext.</label>
                                    <div class="input-group">
                                        <input id="DireccionNumeroSuc" name="DireccionNumero" type="text"
                                            class="form-control" placeholder="Número Ext." autocomplete="off"
                                            value="{{ old('DireccionNumero') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="DireccionNumeroIntSuc">Número Int.</label>
                                    <div class="input-group">
                                        <input id="DireccionNumeroIntSuc" name="DireccionNumeroInt" type="text"
                                            class="form-control" placeholder="Número Int." autocomplete="off"
                                            value="{{ old('DireccionNumeroInt') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-8">
                                    <label for="CodigoPostalListaSucCte">Códigos Postales</label>
                                    <div class="input-group">
                                        <input id="CodigoPostalListaSucCte" type="text" class="form-control"
                                            placeholder="Código Postal" autocomplete="off" autofill="off">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="CodigoPostalSuc">Código Postal</label>
                                    <div class="input-group">
                                        <input id="CodigoPostalSuc" name="CodigoPostal" type="text" class="form-control"
                                            placeholder="Código Postal" autocomplete="off"
                                            value="{{ old('CodigoPostal') }}" required readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-6">
                                    <label for="ColoniaSuc">Colonia</label>
                                    <div class="input-group">
                                        <input id="ColoniaSuc" name="Colonia" type="text" class="form-control"
                                            placeholder="Colonia" autocomplete="off" value="{{ old('Colonia') }}"
                                            required readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="DelegacionSuc">Alcadia / Municipio</label>
                                    <div class="input-group">
                                        <input id="DelegacionSuc" name="Delegacion" type="text" class="form-control"
                                            placeholder="Alcadia / Municipio" autocomplete="off"
                                            value="{{ old('Delegacion') }}" required readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-4">
                                    <label for="PoblacionSuc">Localidad</label>
                                    <div class="input-group">
                                        <input id="PoblacionSuc" name="Poblacion" type="text" class="form-control"
                                            placeholder="Localidad" autocomplete="off" value="{{ old('Poblacion') }}"
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="EstadoSuc">Estado</label>
                                    <div class="input-group">
                                        <input id="EstadoSuc" name="Estado" type="text" class="form-control"
                                            placeholder="Estado" autocomplete="off" value="{{ old('Estado') }}"
                                            required readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="PaisSuc">País</label>
                                    <div class="input-group">
                                        <input id="PaisSuc" name="Pais" type="text" class="form-control"
                                            placeholder="País" autocomplete="off" value="{{ old('Pais') }}" required readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-12">
                                    <label for="TelefonosSuc">Teléfonos</label>
                                    <div class="input-group">
                                        <input id="TelefonosSuc" name="Telefonos" type="text" class="form-control"
                                            placeholder="Teléfonos" autocomplete="off"
                                            value="{{ old('Telefonos') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-contactos">
                    <div class="text-center input-group-append col-md-12">
                        <button type="submit" class="rounded-lg btn btn-primary btn-sm btn-block" id="saveBtnSuc"
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
