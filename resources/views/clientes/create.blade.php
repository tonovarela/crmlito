<div class="modal fullscreen-modal fade" id="modal-create-cliente" tabindex="-1" role="dialog" data-backdrop="static"
    data-keyboard="false" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="col-12">
                    <!-- general form elements -->
                    <form method="post" action="{{ route('clientes.store') }}" class="form-horizontal"
                        data-toggle="validator">
                        {{ csrf_field() }} {{ method_field('POST') }}
                        <input type="hidden" value="{{ $id_usuario }}" name="idUsuario" id="idUsuario">
                        <input type="hidden" value="{{ $idrol }}" name="idRol" id="idRol">
                        <div class="card-body fullscreen-card">
                            <div class="rounded bg-gray">
                                <div class="row mb-2 mt-1">
                                    <div class="col-12 text-center">
                                        <h6 class="text-bold pt-1 pl-1">Nuevo Cliente</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-1">
                                <div class="input-group input-group-sm">
                                    <h6 class="col-md-1 text-right text-bold text-red pr-1 pt-2">&nbsp;No.
                                        {{ $id_cliente_int[0]->idClienteINT }} -
                                    </h6>
                                    <label class="mt-2" for="razonsocial">Razon Social <b
                                            class="asterisco">*</b>&nbsp;</label>
                                    <input type="text" class="form-control col-lg-9 col-xl-10"
                                        id="razonsocial" name="razonsocial" placeholder="Requerido" autocomplete="off"
                                        autofocus>
                                    <div class="input-group-append col-md-4 col-lg-3 col-xl-2 mt-1">
                                        <span class="col-md-6"><button type="submit" id="btnSave"
                                                class="btn btn-primary btn-sm btn-block"
                                                disabled>Guardar</button></span>
                                        <span class="col-md-6 center">
                                            <button type="button" class="btn btn-secondary btn-sm btn-block"
                                                data-dismiss="modal" onclick="closeM()">Cancelar</button>
                                            {{-- <a href="#" data-dismiss="modal"
                                                class="btn btn-secondary btn-sm btn-block">Cancelar</a> --}}

                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-content" id="custom-content-below-tabContent">
                                <div class="tab-pane fade show active" id="div_contactos_cliente" role="tabpanel"
                                    aria-labelledby="custom-content-below-home-tab">
                                    <div class="row">
                                        <div class="col-lg-12">
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






                                                <div class="col-lg-2">
                                                    <label for="empresaWeb">Empresa WEB</label>
                                                    <select id="empresaWeb" name="empresaWeb" class="form-control">
                                                        <option selected disabled>Empresa WEB</option>
                                                        @foreach ($empresa_web_combo as $empresa_web)
                                                                <option value="{{ $empresa_web->Opcion }}">
                                                                    {{ $empresa_web->Opcion }}
                                                                </option>
                                                        @endforeach
                                                    </select>
                                                </div>
    
                                                <div class="col-lg-2">
                                                    <label for="WebUserID">WEB User ID</label>
                                                    <div class="input-group">
                                                        <input type="text" id="WebUserID" name="WebUserID"
                                                            type="text" class="form-control" placeholder="WEB User ID"
                                                            autocomplete="off">
                                                    </div>
                                                </div>




                                                <div class="col-lg-2">
                                                    <label for="DirInternet">Pagina WEB</label>
                                                    <div class="input-group">
                                                        <input type="text" id="DirInternet" name="DirInternet"
                                                            type="text" class="form-control"
                                                            placeholder="Pí¡gina WEB" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <label for="fecAniversario">Aniversario</label>
                                                    <div class="input-group">
                                                        <input id="fecAniversario" name="fecAniversario" type="date"
                                                            class="form-control" placeholder="Aniversario"
                                                            autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-1">
                                                <div class="col-lg-2">
                                                    <label for="idSituacionA">Situación</label>
                                                    <select id="idSituacionA" name="idSituacionA" class="form-control">
                                                        <option selected disabled>Situación</option>
                                                        @foreach ($cat_situacion as $situacion)
                                                            <option value="{{ $situacion->idSituacionA }}">
                                                                {{ $situacion->descSituacionA }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-2">
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
                                                <div class="col-lg-2">
                                                    <label for="AgenteSecundario">Agente Secundario</label>
                                                    <select id="AgenteSecundario" name="AgenteSecundario" class="form-control"
                                                        style="width: 100%">
                                                        <option selected disabled>Agente</option>
                                                        @foreach ($agentes as $agente)
                                                            <option value="{{ $agente->agente }}">
                                                                {{ $agente->agente }} - {{ $agente->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-6 col-lg-2 mt-4">
                                                    <div class="custom-control custom-switch">
                                                        <input id="conBuroC" name="conBuroC" type="checkbox"
                                                            class="custom-control-input">
                                                        <label class="custom-control-label"
                                                            for="conBuroC">Buró</label>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-lg-2 mt-4">
                                                    <div class="custom-control custom-switch">
                                                        <input id="esTop" name="esTop" type="checkbox"
                                                            class="custom-control-input">
                                                        <label class="custom-control-label" for="esTop">Top</label>
                                                    </div>
                                                </div>

                                                {{--  <div class="col-2 col-lg-2 mt-4">
                                                    <div class="custom-control custom-switch">
                                                        <input id="puedeDescargarPresupuesto" name="puedeDescargarPresupuesto" type="checkbox"
                                                            class="custom-control-input">
                                                        <label class="custom-control-label" for="puedeDescargarPresupuesto">¿Se puede descargar presupuesto?</label>
                                                    </div>
                                                </div>  --}}
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
                                                        <label for="eMail1">Email 1 <b
                                                                class="asterisco">*</b></label>
                                                        <div class="input-group">
                                                            <input id="eMail1" name="eMail1" type="text"
                                                                class="form-control" placeholder="Email 1"
                                                                autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <label for="Giro">Giro <b class="asterisco">*</b></label>
                                                        <select id="Giro" name="Giro" class="form-control"
                                                            style="width: 100%">
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
                                                        <select id="SubGiro" name="SubGiro" class="form-control"
                                                            style="width: 100%">
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
                                                        <label for="eMail1">Email 1 <b
                                                                class="asterisco">*</b></label>
                                                        <div class="input-group">
                                                            <input id="eMail1" name="eMail1" type="text"
                                                                class="form-control" placeholder="Email 1"
                                                                autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <label for="Giro">Giro <b class="asterisco">*</b></label>
                                                        <select id="Giro" name="Giro" class="form-control"
                                                            style="width: 100%">
                                                            <option selected disabled>Giro</option>
                                                            @foreach ($cat_giros as $giro)
                                                                <option value="{{ $giro->Giro }}">
                                                                    {{ $giro->Giro }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <label for="SubGiro">Sub Giro <b
                                                            class="asterisco">*</b></label>
                                                        <select id="SubGiro" name="SubGiro" class="form-control"
                                                            style="width: 100%">
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
                                            <div class="col-lg-12 mt-1 pb-2">
                                                <div class="mt-1 mb-1 row">
                                                    <div class="rounded col-sm-12 bg-gray">
                                                        <h6 class="text-bold text-center">Dirección</h6>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="row mb-1">
                                                            <div class="col-md-5">
                                                                <label for="Direccion">Calle <b
                                                                        class="asterisco">*</b></label>
                                                                <div class="input-group">
                                                                    <input id="Direccion" name="Direccion"
                                                                        type="text" class="form-control"
                                                                        placeholder="Calle" autocomplete="off">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="DireccionNumero">Número Exterior <b
                                                                        class="asterisco">*</b></label>
                                                                <div class="input-group">
                                                                    <input id="DireccionNumero" name="DireccionNumero"
                                                                        type="text" class="form-control"
                                                                        placeholder="Número Exterior"
                                                                        autocomplete="off">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="DireccionNumeroInt">Número Interior</label>
                                                                <div class="input-group">
                                                                    <input id="DireccionNumeroInt"
                                                                        name="DireccionNumeroInt" type="text"
                                                                        class="form-control"
                                                                        placeholder="Número Interior"
                                                                        autocomplete="off">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="CodigoPostalLista">Códigos Postales</label>
                                                                <div class="input-group">
                                                                    <input id="CodigoPostalLista" type="text"
                                                                        class="form-control"
                                                                        placeholder="Código Postal"
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
                                                                        placeholder="Código Postal"
                                                                        autocomplete="off">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="Alcaldia">Alcaldí­a <b
                                                                        class="asterisco">*</b></label>
                                                                <div class="input-group">
                                                                    <input id="Alcaldia" name="Alcaldia"
                                                                        type="text" class="form-control"
                                                                        placeholder="Alcaldí­a" autocomplete="off">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="Colonia">Colonia <b
                                                                        class="asterisco">*</b></label>
                                                                <div class="input-group">
                                                                    <input id="Colonia" name="Colonia"
                                                                        type="text" class="form-control"
                                                                        placeholder="Colonia" autocomplete="off">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="Poblacion">Localidad <b
                                                                        class="asterisco">*</b></label>
                                                                <div class="input-group">
                                                                    <input id="Poblacion" name="Poblacion"
                                                                        type="text" class="form-control"
                                                                        placeholder="Localidad" autocomplete="off">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="Estado">Estado <b
                                                                        class="asterisco">*</b></label>
                                                                <div class="input-group">
                                                                    <input id="Estado" name="Estado"
                                                                        type="text" class="form-control"
                                                                        placeholder="Estado" autocomplete="off">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">






                                                                <label for="Pais">País</label>
                                                                <select id="Pais" name="Pais"
                                                                    class="form-control" style="width: 100%">
                                                                    <option selected disabled>Pais</option>
                                                                    @foreach ($paises as $pais)
                                                                        <option value="{{ $pais->Descripcion }}">
                                                                            {{ $pais->Descripcion }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>





                                                                <!-- <label for="Pais">Paí­s <b class="asterisco">*</b></label>
                                                                <div class="input-group">
                                                                    <input id="Pais" name="Pais" type="text" class="form-control" placeholder="Pais" autocomplete="off">
                                                                </div> -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- <div class="row mb-1">
                                                    <div class="rounded col-sm-12 bg-gray">
                                                        <h6 class="text-bold text-center">Dirección Fiscal</h6>
                                                        <span id="spanDirFiscal">
                                                            <div class="custom-control custom-switch"
                                                                style="margin-top: -25px">
                                                                <input id="igualDirCom" type="checkbox"
                                                                    class="custom-control-input">
                                                                <label class="custom-control-label"
                                                                    for="igualDirCom">Misma que
                                                                    Dirección Comercial</label>
                                                            </div>
                                                        </span>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="row mb-1">
                                                            <div class="col-md-5">
                                                                <label for="DireccionFiscal">Calle</label>
                                                                <div class="input-group">
                                                                    <input id="DireccionFiscal" name="DireccionFiscal"
                                                                        type="text" class="form-control"
                                                                        placeholder="Calle" autocomplete="off">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="DireccionFiscalNumero">Número
                                                                    Exterior</label>
                                                                <div class="input-group">
                                                                    <input id="DireccionFiscalNumero"
                                                                        name="DireccionFiscalNumero" type="text"
                                                                        class="form-control"
                                                                        placeholder="Número Exterior"
                                                                        autocomplete="off">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="DireccionFiscalNumeroInt">Número
                                                                    Interior</label>
                                                                <div class="input-group">
                                                                    <input id="DireccionFiscalNumeroInt"
                                                                        name="DireccionFiscalNumeroInt" type="text"
                                                                        class="form-control"
                                                                        placeholder="Número Interior"
                                                                        autocomplete="off">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="CPFiscalLista">Códigos Postales</label>
                                                                <div class="input-group">
                                                                    <input id="CPFiscalLista" type="text"
                                                                        class="form-control"
                                                                        placeholder="Código Postal" autocomplete="off">
                                                                    <span class="input-group-text"><i
                                                                            class="fas fa-search"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-1">
                                                            <div class="col-md-2">
                                                                <label for="CPFiscal">Código Postal</label>
                                                                <div class="input-group">
                                                                    <input id="CPFiscal" name="CPFiscal" type="text"
                                                                        class="form-control"
                                                                        placeholder="Código Postal" autocomplete="off">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="AlcaldiaFiscal">Alcaldí­a</label>
                                                                <div class="input-group">
                                                                    <input id="AlcaldiaFiscal" name="AlcaldiaFiscal"
                                                                        type="text" class="form-control"
                                                                        placeholder="Alcaldí­a" autocomplete="off">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="ColoniaFiscal">Colonia</label>
                                                                <div class="input-group">
                                                                    <input id="ColoniaFiscal" name="ColoniaFiscal"
                                                                        type="text" class="form-control"
                                                                        placeholder="Colonia" autocomplete="off">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="PoblacionFiscal">Población</label>
                                                                <div class="input-group">
                                                                    <input id="PoblacionFiscal" name="PoblacionFiscal"
                                                                        type="text" class="form-control"
                                                                        placeholder="Poblacion" autocomplete="off">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="EstadoFiscal">Estado</label>
                                                                <div class="input-group">
                                                                    <input id="EstadoFiscal" name="EstadoFiscal"
                                                                        type="text" class="form-control"
                                                                        placeholder="Estado" autocomplete="off">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="PaisFiscal">Paí­s</label>
                                                                <div class="input-group">
                                                                    <input id="PaisFiscal" name="PaisFiscal" type="text"
                                                                        class="form-control" placeholder="Pais"
                                                                        autocomplete="off">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> --}}
                                                {{-- <div class="row mb-1">
                                                    <div class="col-4">
                                                        <label for="RFC">R.F.C.</label>
                                                        <div class="input-group">
                                                            <input id="RFC" name="RFC" type="text"
                                                                class="form-control" placeholder="R.F.C."
                                                                autocomplete="off" onchange="validarInput(this)">
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <label for="CreditoCondiciones">Condiciones</label>
                                                        <select id="CreditoCondiciones" name="CreditoCondiciones"
                                                            class="form-control" style="width: 100%">
                                                            <option selected disabled>Condiciones</option>
                                                            @foreach ($cat_condiciones as $condiciones)
                                                                <option value="{{ $condiciones->Condicion }}">
                                                {{ $condiciones->Condicion }}
                                                </option>
                                                @endforeach
                                                </select>
                                            </div>
                                            <div class="col-4">
                                                <label for="FiscalRegimen">Régimen</label>
                                                <select id="FiscalRegimen" name="FiscalRegimen" class="form-control" style="width: 100%">
                                                    <option selected disabled>Régimen</option>
                                                    @foreach ($cat_regimen as $regimen)
                                                    <option value="{{ $regimen->idRegimen }}">
                                                        {{ $regimen->idRegimen }} -
                                                        {{ $regimen->Descripcion }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div> --}}
                                                {{-- <div class="row mb-1">
                                                    <div class="rounded col-sm-12 bg-gray">
                                                        <h6 class="text-bold text-center">Datos Administrativos</h6>
                                                    </div>
                                                    <div class="col-4">
                                                        <label for="urlPortal">URL Portal</label>
                                                        <div class="input-group">
                                                            <input id="urlPortal" name="urlPortal" type="text"
                                                                class="form-control" placeholder="URL Portal"
                                                                autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-2">
                                                        <label for="usuarioPortal">Usuario Portal</label>
                                                        <div class="input-group">
                                                            <input id="usuarioPortal" name="usuarioPortal" type="text"
                                                                class="form-control" placeholder="Usuario Portal"
                                                                autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-2">
                                                        <label for="cvePortal">Clave Portal</label>
                                                        <div class="input-group">
                                                            <input id="cvePortal" name="cvePortal" type="text"
                                                                class="form-control" placeholder="Clave Portal"
                                                                autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <label for="manualPortal">Manual Portal</label>
                                                        <div class="input-group">
                                                            <input id="manualPortal" name="manualPortal" type="text"
                                                                class="form-control" placeholder="Manual Portal"
                                                                autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div> --}}
                                                <div class="row">
                                                    <div class="card bg-transparent col-12 pb-3">
                                                        <div class="bg-gray border-0 rounded text center"
                                                            style="height: 28px !important">
                                                            <h6 class="text-center text-bold">
                                                                Requisitos Especiales
                                                            </h6>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-4">
                                                                    <div class="form-group">
                                                                        <label for="selectReqEspeciales"
                                                                            class="bg-gray col-12 ronded-lg p-1">Para
                                                                            Entrega :</label>
                                                                        <select id="selectReqEspeciales"
                                                                            name="selectReqEspeciales_Entrega[]"
                                                                            class="duallistbox" multiple="multiple">
                                                                            @foreach ($cat_requisitos as $requisito)
                                                                                <option
                                                                                    value="{{ $requisito->idRequisito }}">
                                                                                    {{ $requisito->descRequisito }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-4">
                                                                    <div class="form-group">
                                                                        <label for="selectReqEspecialesP"
                                                                            class="bg-gray col-12 ronded-lg p-1">Para
                                                                            Factura :</label>
                                                                        <select id="selectReqEspecialesP"
                                                                            name="selectReqEspeciales_Pedido[]"
                                                                            class="duallistbox" multiple="multiple">
                                                                            @foreach ($cat_requisitos as $requisito)
                                                                                <option
                                                                                    value="{{ $requisito->idRequisito }}">
                                                                                    {{ $requisito->descRequisito }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-4">
                                                                    <div class="form-group">
                                                                        <label for="selectReqEspecialesF"
                                                                            class="bg-gray col-12 ronded-lg p-1">Para
                                                                            CXC :</label>
                                                                        <select id="selectReqEspecialesF"
                                                                            name="selectReqEspecialesF_Cobro[]"
                                                                            class="duallistbox" multiple="multiple">
                                                                            @foreach ($cat_requisitos as $requisito)
                                                                                <option
                                                                                    value="{{ $requisito->idRequisito }}">
                                                                                    {{ $requisito->descRequisito }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
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
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
