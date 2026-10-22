<div class="modal fullscreen-modal fade" id="modal_create_prospecto" tabindex="-1" role="dialog" data-backdrop="static"
    data-keyboard="false" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="col-12">
                    <form method="post" action="{{ route('candidatos.store') }}" class="form-horizontal"
                        data-toggle="validator">
                        {{ csrf_field() }} {{ method_field('POST') }}
                        <input type="hidden" value="{{ $id_usuario }}" name="idUsuario" id="idUsuario">
                        <input type="hidden" value="{{ $idrol }}" name="idRol" id="idRol">
                        <div class="card-body fullscreen-card">
                            <div class="rounded-pill bg-gray">
                                <div class="row mb-2 mt-1">
                                    <div class="col-12 text-center">
                                        <h6 class="text-bold pt-1 pl-1">Nuevo Candidato</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-1">
                                <div class="input-group input-group-sm">
                                    <h6 class="col-md-1 text-right text-bold text-red pr-1 pt-2">&nbsp;No.
                                        {{ $id_prospecto }} -
                                    </h6>
                                    <label class="mt-2" for="razonsocial">Razon Social <b
                                            class="asterisco">*</b>&nbsp;</label>
                                    <input type="text" class="form-control col-lg-9 col-xl-10" id="razonsocial"
                                        name="razonsocial" placeholder="Requerido" autocomplete="off" autofocus>
                                    <div class="input-group-append col-md-4 col-lg-3 col-xl-2 mt-1">
                                        <span class="col-md-6"><button type="submit" id="btnSave"
                                                class="btn btn-primary btn-sm btn-block"
                                                disabled>Guardar</button></span>
                                        <span class="col-md-6 center">
                                            <button type="button" class="btn btn-secondary btn-sm btn-block"
                                                data-dismiss="modal" onclick="closeM()">Cancelar</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-content" id="custom-content-below-tabContent">
                                <div class="tab-pane fade show active" id="div_contactos_prospecto" role="tabpanel"
                                    aria-labelledby="custom-content-below-home-tab">
                                    <div class="col-lg-12 mt-1 pb-2">
                                        <div class="mt-1 mb-1 row">
                                            <div class="rounded-pill text-center col-sm-12 bg-gray">
                                                <h6 class="text-bold pt-1 pl-1">General</h6>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="row mb-1">
                                                    <div class="col-lg-4">
                                                        <label for="LITONombreComercial">Nombre Comercial <b
                                                                class="asterisco">*</b></label>
                                                        <div class="input-group">
                                                            <input id="LITONombreComercial" name="LITONombreComercial"
                                                                type="text" class="form-control"
                                                                placeholder="Nombre Comercial" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <label for="DirInternet">Pagina WEB</label>
                                                        <div class="input-group">
                                                            <input type="text" id="DirInternet" name="DirInternet"
                                                                type="text" class="form-control"
                                                                placeholder="Pí¡gina WEB" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <label for="Agente">Agente <b class="asterisco">*</b></label>
                                                        <select id="Agente" name="Agente" class="form-control"
                                                            style="width: 100%">
                                                            <option selected disabled>Agente</option>
                                                            @foreach ($agentes as $agente)
                                                                <option value="{{ $agente->agente }}">
                                                                    {{ $agente->agente }} - {{ $agente->nombre }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <div class="col-lg-1">
                                                        <label for="Lada1">Lada 1</label>
                                                        <div class="input-group">
                                                            <input id="Lada1" name="Lada1" type="text"
                                                                class="form-control" placeholder="Lada 1"
                                                                autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <label for="Telefonos">Teléfonos <b
                                                                class="asterisco">*</b></label>
                                                        <div class="input-group">
                                                            <input id="Telefonos" name="Telefonos" type="text"
                                                                class="form-control" placeholder="Teléfonos"
                                                                autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <label for="Extencion1">Ext 1</label>
                                                        <div class="input-group">
                                                            <input id="Extencion1" name="Extencion1" type="text"
                                                                class="form-control" placeholder="Ext 1"
                                                                autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <label for="Lada2">Lada 2</label>
                                                        <div class="input-group">
                                                            <input id="Lada2" name="Lada2" type="text"
                                                                class="form-control" placeholder="Lada 2"
                                                                autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <label for="Telefonos2">Teléfonos 2</label>
                                                        <div class="input-group">
                                                            <input id="Telefonos2" name="Telefonos2" type="text"
                                                                class="form-control" placeholder="Teléfonos2"
                                                                autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <label for="Extencion2">Ext 2</label>
                                                        <div class="input-group">
                                                            <input id="Extencion2" name="Extencion2" type="text"
                                                                class="form-control" placeholder="Ext 2"
                                                                autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    @if ($idrol == 1017 || $idrol == 5 || $idrol == 1 || $idrol == 8 || $idrol == 1010 || $id_usuario == 63)
                                                        <div class="col-lg-3">
                                                            <label for="eMail1">Email <b
                                                                    class="asterisco">*</b></label>
                                                            <div class="input-group">
                                                                <input id="eMail1" name="eMail1" type="text"
                                                                    class="form-control" placeholder="Email"
                                                                    autocomplete="off">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3">
                                                            <label for="Giro">Giro <b
                                                                    class="asterisco">*</b></label>
                                                            <select id="Giro" name="Giro"
                                                                class="form-control" style="width: 100%">
                                                                <option selected disabled>Giro</option>
                                                                @foreach ($cat_giros as $giro)
                                                                    <option value="{{ $giro->Giro }}">
                                                                        {{ $giro->Giro }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-3">
                                                            <label for="SubGiro">Sub Giro <b
                                                                    class="asterisco">*</b></label>
                                                            <select id="SubGiro" name="SubGiro"
                                                                class="form-control" style="width: 100%">
                                                                <option selected disabled>Sub Giro</option>
                                                                @foreach ($cat_subgiros as $subgiro)
                                                                    <option value="{{ $subgiro->subgiro }}">
                                                                        {{ $subgiro->subgiro }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-3">
                                                            <label for="ClaveUsoCFDI">Clave Uso CFDI</label>
                                                            <select id="ClaveUsoCFDI" name="ClaveUsoCFDI" class="form-control"
                                                                style="width: 100%">
                                                                <option selected disabled>Clave Uso CFDI</option>
                                                                @foreach ($uso_cfdi as $cfdi)
                                                                    <option value="{{ $cfdi->ClaveUsoCFDI }}">
                                                                        {{ $cfdi->ClaveUsoCFDI }} -
                                                                        {{ $cfdi->Descripcion }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        @else
                                                        <div class="col-lg-4">
                                                            <label for="eMail1">Email <b
                                                                    class="asterisco">*</b></label>
                                                            <div class="input-group">
                                                                <input id="eMail1" name="eMail1" type="text"
                                                                    class="form-control" placeholder="Email"
                                                                    autocomplete="off">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <label for="Giro">Giro <b
                                                                    class="asterisco">*</b></label>
                                                            <select id="Giro" name="Giro"
                                                                class="form-control" style="width: 100%">
                                                                <option selected disabled>Giro</option>
                                                                @foreach ($cat_giros as $giro)
                                                                    <option value="{{ $giro->Giro }}">
                                                                        {{ $giro->Giro }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <label for="SubGiro">Sub Giro</label>
                                                            <select id="SubGiro" name="SubGiro"
                                                                class="form-control" style="width: 100%">
                                                                <option selected disabled>Sub Giro</option>
                                                                @foreach ($cat_subgiros as $subgiro)
                                                                    <option value="{{ $subgiro->subgiro }}">
                                                                        {{ $subgiro->subgiro }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mt-1 pb-2">
                                        <div class="mt-1 mb-1 row">
                                            <div class="rounded-pill text-center col-sm-12 bg-gray">
                                                <h6 class="text-bold pt-1 pl-1">Dirección</h6>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="row mb-1">
                                                    <div class="col-md-5">
                                                        <label for="Direccion">Calle <b
                                                                class="asterisco">*</b></label>
                                                        <div class="input-group">
                                                            <input id="Direccion" name="Direccion" type="text"
                                                                class="form-control" placeholder="Calle"
                                                                autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="DireccionNumero">Número Exterior <b
                                                                class="asterisco">*</b></label>
                                                        <div class="input-group">
                                                            <input id="DireccionNumero" name="DireccionNumero"
                                                                type="text" class="form-control"
                                                                placeholder="Número Exterior" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="DireccionNumeroInt">Número Interior</label>
                                                        <div class="input-group">
                                                            <input id="DireccionNumeroInt" name="DireccionNumeroInt"
                                                                type="text" class="form-control"
                                                                placeholder="Número Interior" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="CodigoPostalLista">Códigos Postales</label>
                                                        <div class="input-group">
                                                            <input id="CodigoPostalLista" type="text"
                                                                class="form-control" placeholder="Código Postal"
                                                                autocomplete="off">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-search"></i></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mb-1">
                                                    <div class="col-md-2">
                                                        <label for="CodigoPostal">Código Postal <b
                                                                class="asterisco">*</b></label>
                                                        <div class="input-group">
                                                            <input id="CodigoPostal" name="CodigoPostal"
                                                                type="text" class="form-control"
                                                                placeholder="Código Postal" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="Alcaldia">Alcaldí­a <b
                                                                class="asterisco">*</b></label>
                                                        <div class="input-group">
                                                            <input id="Alcaldia" name="Alcaldia" type="text"
                                                                class="form-control" placeholder="Alcaldí­a"
                                                                autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="Colonia">Colonia <b
                                                                class="asterisco">*</b></label>
                                                        <div class="input-group">
                                                            <input id="Colonia" name="Colonia" type="text"
                                                                class="form-control" placeholder="Colonia"
                                                                autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="Poblacion">Localidad <b
                                                                class="asterisco">*</b></label>
                                                        <div class="input-group">
                                                            <input id="Poblacion" name="Poblacion" type="text"
                                                                class="form-control" placeholder="Localidad"
                                                                autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="Estado">Estado <b
                                                                class="asterisco">*</b></label>
                                                        <div class="input-group">
                                                            <input id="Estado" name="Estado" type="text"
                                                                class="form-control" placeholder="Estado"
                                                                autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="Pais">País</label>
                                                        <select id="Pais" name="Pais" class="form-control"
                                                            style="width: 100%">
                                                            <option selected disabled>Pais</option>
                                                            @foreach ($paises as $pais)
                                                                <option value="{{ $pais->Descripcion }}">
                                                                    {{ $pais->Descripcion }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-1 col-12">
                                            <div class="col-lg-12">
                                                <div class="mt-1 mb-1 row">
                                                    <div class="rounded-pill text-center col-sm-12 bg-gray">
                                                        <h6 class="text-bold pt-1 pl-1">Contacto Principal</h6>
                                                    </div>
                                                    <div class="row mb-1 col-lg-12">
                                                        <div class="col-lg">
                                                            <label for="TratamientoContacto">Tratamiento</label>
                                                            <div class="input-group">
                                                                <input id="TratamientoContacto" name="TratamientoContacto"
                                                                    type="text" class="form-control"
                                                                    placeholder="Tratamiento" autocomplete="off"
                                                                    value="{{ old('TratamientoContacto') }}" autofocus>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg">
                                                            <label for="NombreContacto">Nombre <b
                                                                    class="asterisco">*</b></label>
                                                            <div class="input-group">
                                                                <input id="NombreContacto" name="NombreContacto"
                                                                    type="text" class="form-control" placeholder="Nombre"
                                                                    autocomplete="off" value="{{ old('NombreContacto') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg">
                                                            <label for="CargoContacto">Cargo</label>
                                                            <div class="input-group">
                                                                <input id="CargoContacto" name="CargoContacto" type="text"
                                                                    class="form-control" placeholder="Cargo"
                                                                    autocomplete="off" value="{{ old('CargoContacto') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg">
                                                            <label for="DepartamentoContacto">Departamento</label>
                                                            <div class="input-group">
                                                                <input id="DepartamentoContacto" name="DepartamentoContacto"
                                                                    type="text" class="form-control"
                                                                    placeholder="Departamento" autocomplete="off"
                                                                    value="{{ old('DepartamentoContacto') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg">
                                                            <label for="Telefonos1Contacto">Teléfonos</label>
                                                            <div class="input-group">
                                                                <input id="Telefonos1Contacto" name="Telefonos1Contacto"
                                                                    type="text" class="form-control"
                                                                    placeholder="Teléfonos" autocomplete="off"
                                                                    value="{{ old('Telefonos1Contacto') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg">
                                                            <label for="email">Correo</label>
                                                            <div class="input-group">
                                                                <input id="eMailContacto" name="eMailContacto" type="text"
                                                                    class="form-control" placeholder="Correo"
                                                                    autocomplete="off" value="{{ old('eMailContacto') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{--  <div class="col-lg-4">
                                                <div class="mt-1 mb-1 row">
                                                    <div class="rounded-pill text-center col-sm-12 bg-gray">
                                                        <h6 class="text-bold pt-1 pl-1">Contacto CXP</h6>
                                                    </div>
                                                    <div class="row mb-1 col-lg-12">
                                                        <div class="col-lg-4">
                                                            <label for="TratamientoContactoCXP">Tratamiento</label>
                                                            <div class="input-group">
                                                                <input id="TratamientoContactoCXP" name="TratamientoContactoCXP"
                                                                    type="text" class="form-control"
                                                                    placeholder="Tratamiento" autocomplete="off"
                                                                    value="{{ old('TratamientoContactoCXP') }}" autofocus>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <label for="NombreContactoCXP">Nombre</label>
                                                            <div class="input-group">
                                                                <input id="NombreContactoCXP" name="NombreContactoCXP"
                                                                    type="text" class="form-control" placeholder="Nombre"
                                                                    autocomplete="off" value="{{ old('NombreContactoCXP') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <label for="CargoContactoCXP">Cargo</label>
                                                            <div class="input-group">
                                                                <input id="CargoContactoCXP" name="CargoContactoCXP" type="text"
                                                                    class="form-control" placeholder="Cargo"
                                                                    autocomplete="off" value="{{ old('CargoContactoCXP') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-1 col-lg-12">
                                                        <div class="col-lg-4">
                                                            <label for="DepartamentoContactoCXP">Departamento</label>
                                                            <div class="input-group">
                                                                <input id="DepartamentoContactoCXP" name="DepartamentoContactoCXP"
                                                                    type="text" class="form-control"
                                                                    placeholder="Departamento" autocomplete="off"
                                                                    value="{{ old('DepartamentoContactoCXP') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <label for="Telefonos1ContactoCXP">Teléfonos</label>
                                                            <div class="input-group">
                                                                <input id="Telefonos1ContactoCXP" name="Telefonos1ContactoCXP"
                                                                    type="text" class="form-control"
                                                                    placeholder="Teléfonos" autocomplete="off"
                                                                    value="{{ old('Telefonos1ContactoCXP') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <label for="eMailContactoCXP">Correo</label>
                                                            <div class="input-group">
                                                                <input id="eMailContactoCXP" name="eMailContactoCXP" type="text"
                                                                    class="form-control" placeholder="Correo"
                                                                    autocomplete="off" value="{{ old('eMailContactoCXP') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-4">
                                                <div class="mt-1 mb-1 row">
                                                    <div class="rounded-pill text-center col-sm-12 bg-gray">
                                                        <h6 class="text-bold pt-1 pl-1">Contacto CFDI</h6>
                                                    </div>
                                                    <div class="row mb-1 col-lg-12">
                                                        <div class="col-lg-4">
                                                            <label for="TratamientoContactoCFDI">Tratamiento</label>
                                                            <div class="input-group">
                                                                <input id="TratamientoContactoCFDI" name="TratamientoContactoCFDI"
                                                                    type="text" class="form-control"
                                                                    placeholder="Tratamiento" autocomplete="off"
                                                                    value="{{ old('TratamientoContactoCFDI') }}" autofocus>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <label for="NombreContactoCFDI">Nombre</label>
                                                            <div class="input-group">
                                                                <input id="NombreContactoCFDI" name="NombreContactoCFDI"
                                                                    type="text" class="form-control" placeholder="Nombre"
                                                                    autocomplete="off" value="{{ old('NombreContactoCFDI') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <label for="CargoContactoCFFDI">Cargo</label>
                                                            <div class="input-group">
                                                                <input id="CargoContactoCFFDI" name="CargoContactoCFFDI" type="text"
                                                                    class="form-control" placeholder="Cargo"
                                                                    autocomplete="off" value="{{ old('CargoContactoCFFDI') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-1 col-lg-12">
                                                        <div class="col-lg-4">
                                                            <label for="DepartamentoContactoCFDI">Departamento</label>
                                                            <div class="input-group">
                                                                <input id="DepartamentoContactoCFDI" name="DepartamentoContactoCFDI"
                                                                    type="text" class="form-control"
                                                                    placeholder="Departamento" autocomplete="off"
                                                                    value="{{ old('DepartamentoContactoCFDI') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <label for="Telefonos1ContactoCFDI">Teléfonos</label>
                                                            <div class="input-group">
                                                                <input id="Telefonos1ContactoCFDI" name="Telefonos1ContactoCFDI"
                                                                    type="text" class="form-control"
                                                                    placeholder="Teléfonos" autocomplete="off"
                                                                    value="{{ old('Telefonos1ContactoCFDI') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <label for="eMailContactoCFDI">Correo</label>
                                                            <div class="input-group">
                                                                <input id="eMailContactoCFDI" name="eMailContactoCFDI" type="text"
                                                                    class="form-control" placeholder="Correo"
                                                                    autocomplete="off" value="{{ old('eMailContactoCFDI') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>  --}}
                                        </div>
                                        <div class="mt-1 mb-1 row">
                                            <div class="rounded-pill text-center col-sm-12 bg-gray">
                                                <h6 class="text-bold pt-1 pl-1">Validación</h6>
                                            </div>
                                            <div class="row mb-1 ml-1 col-lg-12">
                                                @foreach ($v_Validaciones as $v_Validacion)
                                                    <div class="border rounded mt-1 pl-1 col-lg-4"
                                                        {{-- style="width: 33%; margin-right: 0.33%" --}}
                                                        >
                                                        <h6 class="ml-2 mt-2 text-bold d-flex">
                                                            <i class="fas fa-info-circle text-warning mr-2"
                                                                style="margin-top: 2px;"
                                                                title='{{ $v_Validacion->informacion }}'>
                                                            </i>
                                                            {{ $v_Validacion->descripcion }}
                                                        </h6>
                                                        <div class="ml-3" style="margin-top: -10px">
                                                            <div class="form-group">
                                                                @foreach ($validaciones as $validacion)
                                                                    @if ($validacion->numPregunta == $v_Validacion->numPregunta)
                                                                        @if ($validacion->tipoObjeto == 'radio')
                                                                            <div class="custom-control custom-radio"
                                                                                style="display: {{ $validacion->display }}">
                                                                                <input class="custom-control-input"
                                                                                    type="radio"
                                                                                    id="{{ $validacion->idCatValidacion }}-{{ $validacion->numPregunta }}"
                                                                                    name="{{ $validacion->nombre }}"
                                                                                    value="{{ $validacion->idCatValidacion }}">
                                                                                <label
                                                                                    for="{{ $validacion->idCatValidacion }}-{{ $validacion->numPregunta }}"
                                                                                    class="custom-control-label">{{ $validacion->valor }}</label>
                                                                            </div>
                                                                        @elseif ($validacion->tipoObjeto == 'radioResp')
                                                                            <div class="float-right col-8 custom-control custom-radio" id="respuestas{{ $validacion->idCatValidacion }}-{{ $validacion->numPregunta }}"
                                                                                style="display: none; margin-left: 65px; z-index: 99999;">
                                                                                <input class="custom-control-input"
                                                                                    type="radio"
                                                                                    id="{{ $validacion->idCatValidacion }}-{{ $validacion->numPregunta }}"
                                                                                    name="{{ $validacion->nombre }}"
                                                                                    value="{{ $validacion->idCatValidacion }}">
                                                                                <label
                                                                                    for="{{ $validacion->idCatValidacion }}-{{ $validacion->numPregunta }}"
                                                                                    class="custom-control-label">{{ $validacion->valor }}</label>
                                                                            </div>
                                                                        @elseif ($validacion->tipoObjeto == 'checkbox')
                                                                            <div class="custom-switch">
                                                                                <input type="checkbox"
                                                                                    id="{{ $validacion->idCatValidacion }}-{{ $validacion->numPregunta }}"
                                                                                    name="{{ $validacion->nombre }}"
                                                                                    value="{{ $validacion->idCatValidacion }}"
                                                                                    class="custom-control-input"
                                                                                    style="z-index: 99999;">
                                                                                <label onclick=""
                                                                                    for="{{ $validacion->idCatValidacion }}-{{ $validacion->numPregunta }}"
                                                                                    class="custom-control-label"
                                                                                    style="cursor: pointer">{{ $validacion->valor }}
                                                                                </label>
                                                                            </div>
                                                                        @elseif ($validacion->tipoObjeto == 'text')
                                                                            <div id="respuesta29"
                                                                                style="display: none; position: absolute;margin-left: 60px;">
                                                                                <div class="d-flex col-lg-12">
                                                                                    <label
                                                                                        for="{{ $validacion->idCatValidacion }}-{{ $validacion->numPregunta }}"
                                                                                        class="col-lg-4">{{ $validacion->valor }}</label>
                                                                                    <textarea name="{{ $validacion->nombre }}" id="{{ $validacion->idCatValidacion }}-{{ $validacion->numPregunta }}"
                                                                                        cols="30" class="form-control col-lg-12" style="text-transform: uppercase; z-index: 99999;"></textarea>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function enablearGuardar() {
            document.getElementById('btnSave').classList.remove('disabled');
            document.getElementById('btnSave').disabled = false;
        }

        // Escuchar en los labels (área visible en custom-control de Bootstrap)
        document.querySelector('label[for="49-8"]').addEventListener('click', enablearGuardar);
        document.querySelector('label[for="48-9"]').addEventListener('click', enablearGuardar);

        // Escuchar change en los inputs como respaldo (cambios programáticos)
        document.getElementById('49-8').addEventListener('change', enablearGuardar);
        document.getElementById('48-9').addEventListener('change', enablearGuardar);
    </script>
</div>
