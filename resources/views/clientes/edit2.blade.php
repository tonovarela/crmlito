<div class="modal fullscreen-modal zoom" id="modal-edit-cliente" tabindex="-1" role="dialog"
    aria-labelledby="myModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            {{-- <div class="modal-header">
            </div> --}}
            <div class="modal-body">
                <div id="formClienteEdit" class="col-12">
                    <!-- general form elements -->
                    <form method="post" class="form-horizontal" data-toggle="validator">
                        {{ csrf_field() }} {{ method_field('PUT') }}
                        <input type="text" name="idUsuario" id="idUsuarioEdit">
                        <input type="hidden" name="idClienteINT" id="idClienteINT">
                        <div>
                            <div class="rounded bg-gray">
                                <div class="row mb-2">
                                    <div class="col-sm-6">
                                        <h6 class="text-bold pt-1 pl-1">Editar Cliente</h6>
                                    </div>
                                    <div class=" col-sm-6">
                                        <h6 id="numCliente" class="text-right text-bold pr-3 pt-1">
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-1">
                                <div class="input-group input-group-sm">
                                    <label class="mt-2" for="razonsocial">Razon Social &nbsp;</label>
                                    <input type="text" class="form-control col-md-8 col-lg-9 col-xl-10" id="razonsocialEdit"
                                        name="razonsocial" placeholder="Requerido" required autocomplete="off"
                                        autofocus>
                                    <div class="input-group-append col-md-4 col-lg-3 col-xl-2 mt-1">
                                        <span class="col-md-6"><button type="submit"
                                                class="btn btn-primary btn-sm btn-block">Guardar</button></span>
                                        <span class="col-md-6 center">
                                            <button type="button" class="btn btn-secondary btn-sm btn-block"
                                                data-dismiss="modal" onclick="closeMEdit()">Cancelar</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <ul class="nav nav-tabs col-sm-12" id="custom-content-below-tab" role="tablist">
                                    <li class="nav-item col-sm-4">
                                        <a class="nav-link active" id="general-tab" onclick="mostrarGeneral()"
                                            data-toggle="pill" role="tab" aria-controls="general-tab"
                                            aria-selected="true" style="cursor: pointer">General</a>
                                    </li>
                                    <li class="nav-item col-sm-4">
                                        <a class="nav-link" id="contactos-tab" onclick="mostrarContactos()"
                                            data-toggle="pill" role="tab" aria-controls="contactos-tab"
                                            aria-selected="false" style="cursor: pointer">Contactos</a>
                                    </li>
                                    <li class="nav-item col-sm-4">
                                        <a class="nav-link" id="oportunidades-tab" onclick="mostrarOportunidades()"
                                            data-toggle="pill" role="tab" aria-controls="oportunidades-tab"
                                            aria-selected="false" style="cursor: pointer">Oportunidades</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="custom-content-below-tabContent">
                                    {{-- <div class="tab-pane fade show active" id="general" role="tabpanel"
                                        aria-labelledby="general-tab">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="row mb-1">
                                                    <div class="col-md-4">
                                                        <label for="LITONombreComercial">Nombre Comercial</label>
                                                        <div class="input-group">
                                                            <input id="LITONombreComercial" name="LITONombreComercial"
                                                                type="text" class="form-control"
                                                                placeholder="Nombre Comercial" autocomplete="off"
                                                                value="{{ $cliente[0]->LITONombreComercial }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="DirInternet">Pagina WEB</label>
                                                        <div class="input-group">
                                                            <input type="text" id="DirInternet" name="DirInternet"
                                                                type="text" class="form-control"
                                                                placeholder="Página WEB" autocomplete="off"
                                                                value="{{ $cliente[0]->DirInternet }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="Agente">Agente</label>
                                                        <select id="Agente" name="Agente" class="form-control"
                                                            style="width: 100%">
                                                            <option selected disabled>Agente</option>
                                                            @foreach ($agentes as $agente)
                                                                @if ($agente->agente == $cliente[0]->Agente)
                                                                    <option value="{{ $agente->agente }}" selected>
                                                                        {{ $cliente[0]->Agente }}
                                                                    </option>
                                                                @else
                                                                    <option value="{{ $agente->agente }}">
                                                                        {{ $agente->nombre }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label for="idSituacionA">Situación</label>
                                                        <select id="idSituacionA" name="idSituacionA"
                                                            class="form-control">
                                                            <option selected disabled>Situación</option>
                                                            @foreach ($cat_situacion as $situacion)
                                                                @if ($situacion->idSituacionA == $cliente[0]->idSituacionA)
                                                                    <option value="{{ $situacion->idSituacionA }}"
                                                                        selected>
                                                                        {{ $cliente[0]->descSituacionA }}
                                                                    </option>
                                                                @else
                                                                    <option value="{{ $situacion->idSituacionA }}">
                                                                        {{ $situacion->descSituacionA }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-1 mt-4">
                                                        <div class="custom-control custom-switch">
                                                            @if ($cliente[0]->conBuroC == 1)
                                                                <input id="conBuroC" name="conBuroC" type="checkbox"
                                                                    class="custom-control-input" checked>
                                                            @else
                                                                <input id="conBuroC" name="conBuroC" type="checkbox"
                                                                    class="custom-control-input">
                                                            @endif
                                                            <label class="custom-control-label"
                                                                for="conBuroC">Buró</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1 mt-4">
                                                        <div class="custom-control custom-switch">
                                                            @if ($cliente[0]->esTop == 1)
                                                                <input id="esTop" name="esTop" type="checkbox"
                                                                    class="custom-control-input" checked>
                                                            @else
                                                                <input id="esTop" name="esTop" type="checkbox"
                                                                    class="custom-control-input">
                                                            @endif
                                                            <label class="custom-control-label" for="esTop">Top</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="div_contactos_cliente_editar" class="col-lg-12 mt-3 pb-2">
                                                    <div class="mt-1 mb-1 row">
                                                        <div class="rounded col-sm-12 bg-gray">
                                                            <h6 class="text-bold text-center">Dirección</h6>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="row mb-1">
                                                                <div class="col-md-5">
                                                                    <label for="Direccion">Calle</label>
                                                                    <div class="input-group">
                                                                        <input id="Direccion" name="Direccion"
                                                                            type="text" class="form-control"
                                                                            placeholder="Calle" autocomplete="off"
                                                                            value="{{ $cliente[0]->Direccion }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label for="DireccionNumero">Número Exterior</label>
                                                                    <div class="input-group">
                                                                        <input id="DireccionNumero"
                                                                            name="DireccionNumero" type="text"
                                                                            class="form-control"
                                                                            placeholder="Número Exterior"
                                                                            autocomplete="off"
                                                                            value="{{ $cliente[0]->DireccionNumero }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label for="DireccionNumeroInt">Número
                                                                        Interior</label>
                                                                    <div class="input-group">
                                                                        <input id="DireccionNumeroInt"
                                                                            name="DireccionNumeroInt" type="text"
                                                                            class="form-control"
                                                                            placeholder="Número Interior"
                                                                            autocomplete="off"
                                                                            value="{{ $cliente[0]->DireccionNumeroInt }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label for="CodigoPostalLista">Códigos
                                                                        Postales</label>
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
                                                                    <label for="CodigoPostal">Código Postal</label>
                                                                    <div class="input-group">
                                                                        <input id="CodigoPostal" name="CodigoPostal"
                                                                            type="text" class="form-control"
                                                                            placeholder="Código Postal"
                                                                            autocomplete="off"
                                                                            value="{{ $cliente[0]->CodigoPostal }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label for="Alcaldia">Alcaldía</label>
                                                                    <div class="input-group">
                                                                        <input id="Alcaldia" name="Alcaldia" type="text"
                                                                            class="form-control" placeholder="Alcaldía"
                                                                            autocomplete="off"
                                                                            value="{{ $cliente[0]->Alcaldia }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label for="Colonia">Colonia</label>
                                                                    <div class="input-group">
                                                                        <input id="Colonia" name="Colonia" type="text"
                                                                            class="form-control" placeholder="Colonia"
                                                                            autocomplete="off"
                                                                            value="{{ $cliente[0]->Colonia }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label for="Poblacion">Población</label>
                                                                    <div class="input-group">
                                                                        <input id="Poblacion" name="Poblacion"
                                                                            type="text" class="form-control"
                                                                            placeholder="Poblacion" autocomplete="off"
                                                                            value="{{ $cliente[0]->Poblacion }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label for="Estado">Estado</label>
                                                                    <div class="input-group">
                                                                        <input id="Estado" name="Estado" type="text"
                                                                            class="form-control" placeholder="Estado"
                                                                            autocomplete="off"
                                                                            value="{{ $cliente[0]->Estado }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label for="Pais">País</label>
                                                                    <div class="input-group">
                                                                        <input id="Pais" name="Pais" type="text"
                                                                            class="form-control" placeholder="Pais"
                                                                            autocomplete="off"
                                                                            value="{{ $cliente[0]->Pais }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="rounded col-sm-12 bg-gray">
                                                            <h6 class="text-bold text-center">Dirección Fiscal</h6>
                                                            <span>
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
                                                        <div class="col-sm-12">
                                                            <div class="row mb-1">
                                                                <div class="col-md-5">
                                                                    <label for="DireccionFiscal">Calle</label>
                                                                    <div class="input-group">
                                                                        <input id="DireccionFiscal"
                                                                            name="DireccionFiscal" type="text"
                                                                            class="form-control" placeholder="Calle"
                                                                            autocomplete="off"
                                                                            value="{{ $cliente[0]->DirecccionFiscal }}">
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
                                                                            autocomplete="off"
                                                                            value="{{ $cliente[0]->DireccionFiscalNumero }}">
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
                                                                            autocomplete="off"
                                                                            value="{{ $cliente[0]->DireccionFiscalNumeroInt }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label for="CPFiscalLista">Códigos Postales</label>
                                                                    <div class="input-group">
                                                                        <input id="CPFiscalLista" type="text"
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
                                                                    <label for="CPFiscal">Código Postal</label>
                                                                    <div class="input-group">
                                                                        <input id="CPFiscal" name="CPFiscal" type="text"
                                                                            class="form-control"
                                                                            placeholder="Código Postal"
                                                                            autocomplete="off"
                                                                            value="{{ $cliente[0]->CPFiscal }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label for="AlcaldiaFiscal">Alcaldía</label>
                                                                    <div class="input-group">
                                                                        <input id="AlcaldiaFiscal" name="AlcaldiaFiscal"
                                                                            type="text" class="form-control"
                                                                            placeholder="Alcaldía" autocomplete="off"
                                                                            value="{{ $cliente[0]->AlcaldiaFiscal }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label for="ColoniaFiscal">Colonia</label>
                                                                    <div class="input-group">
                                                                        <input id="ColoniaFiscal" name="ColoniaFiscal"
                                                                            type="text" class="form-control"
                                                                            placeholder="Colonia" autocomplete="off"
                                                                            value="{{ $cliente[0]->ColoniaFiscal }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label for="PoblacionFiscal">Población</label>
                                                                    <div class="input-group">
                                                                        <input id="PoblacionFiscal"
                                                                            name="PoblacionFiscal" type="text"
                                                                            class="form-control" placeholder="Poblacion"
                                                                            autocomplete="off"
                                                                            value="{{ $cliente[0]->PoblacionFiscal }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label for="EstadoFiscal">Estado</label>
                                                                    <div class="input-group">
                                                                        <input id="EstadoFiscal" name="EstadoFiscal"
                                                                            type="text" class="form-control"
                                                                            placeholder="Estado" autocomplete="off"
                                                                            value="{{ $cliente[0]->EstadoFiscal }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label for="PaisFiscal">País</label>
                                                                    <div class="input-group">
                                                                        <input id="PaisFiscal" name="PaisFiscal"
                                                                            type="text" class="form-control"
                                                                            placeholder="Pais" autocomplete="off"
                                                                            value="{{ $cliente[0]->PaisFiscal }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row mb-1">
                                                        <div class="col-md-4">
                                                            <label for="RFC">R.F.C.</label>
                                                            <div class="input-group">
                                                                <input id="RFC" name="RFC" type="text"
                                                                    class="form-control" placeholder="R.F.C."
                                                                    autocomplete="off"
                                                                    value="{{ $cliente[0]->RFC }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="Lada1">Lada 1</label>
                                                            <div class="input-group">
                                                                <input id="Lada1" name="Lada1" type="text"
                                                                    class="form-control" placeholder="Lada 1"
                                                                    autocomplete="off"
                                                                    value="{{ $cliente[0]->Lada1 }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="Telefonos">Teléfonos</label>
                                                            <div class="input-group">
                                                                <input id="Telefonos" name="Telefonos" type="text"
                                                                    class="form-control" placeholder="Teléfonos"
                                                                    autocomplete="off"
                                                                    value="{{ $cliente[0]->Telefonos }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="Extencion1">Extencion 1</label>
                                                            <div class="input-group">
                                                                <input id="Extencion1" name="Extencion1" type="text"
                                                                    class="form-control" placeholder="Extencion1"
                                                                    autocomplete="off"
                                                                    value="{{ $cliente[0]->Extencion1 }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-1">
                                                        <div class="col-md-4">
                                                            <label for="eMail1">Email 1</label>
                                                            <div class="input-group">
                                                                <input id="eMail1" name="eMail1" type="text"
                                                                    class="form-control" placeholder="Email 1"
                                                                    autocomplete="off"
                                                                    value="{{ $cliente[0]->eMail1 }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="Lada2">Lada 2</label>
                                                            <div class="input-group">
                                                                <input id="Lada2" name="Lada2" type="text"
                                                                    class="form-control" placeholder="Lada 2"
                                                                    autocomplete="off"
                                                                    value="{{ $cliente[0]->Lada2 }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="Telefonos2">Teléfonos 2</label>
                                                            <div class="input-group">
                                                                <input id="Telefonos2" name="Telefonos2" type="text"
                                                                    class="form-control" placeholder="Teléfonos2"
                                                                    autocomplete="off"
                                                                    value="{{ $cliente[0]->Telefonos2 }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="Extencion2">Extencion 2</label>
                                                            <div class="input-group">
                                                                <input id="Extencion2" name="Extencion2" type="text"
                                                                    class="form-control" placeholder="Extencion2"
                                                                    autocomplete="off"
                                                                    value="{{ $cliente[0]->Extencion2 }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-1">
                                                        <div class="col-md-4">
                                                            <label for="CreditoCondiciones">Condiciones</label>
                                                            <div class="input-group">
                                                                <input id="CreditoCondiciones" name="CreditoCondiciones"
                                                                    type="text" class="form-control"
                                                                    placeholder="Condiciones" autocomplete="off"
                                                                    value="{{ $cliente[0]->CreditoCondiciones }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="FiscalRegimen">Régimen</label>
                                                            <div class="input-group">
                                                                <input id="FiscalRegimen" name="FiscalRegimen"
                                                                    type="text" class="form-control"
                                                                    placeholder="Régimen" autocomplete="off"
                                                                    value="{{ $cliente[0]->FiscalRegimen }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="Giro">Giro</label>
                                                            <select id="Giro" name="Giro" class="form-control"
                                                                style="width: 100%">
                                                                <option selected disabled>Giro</option>
                                                                @foreach ($cat_giros as $giro)
                                                                    @if ($giro->Giro == $cliente[0]->Giro)
                                                                        <option value="{{ $giro->Giro }}" selected>
                                                                            {{ $cliente[0]->Giro }}
                                                                        </option>
                                                                    @else
                                                                        <option value="{{ $giro->Giro }}">
                                                                            {{ $giro->Giro }}
                                                                        </option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <label for="SubGiro">Sub Giro</label>
                                                            <select id="SubGiro" name="SubGiro" class="form-control"
                                                                style="width: 100%">
                                                                <option selected disabled>Sub Giro</option>
                                                                @foreach ($cat_subgiros as $subgiro)
                                                                    @if ($subgiro->subgiro == $cliente[0]->SubGiro)
                                                                        <option value="{{ $subgiro->subgiro }}"
                                                                            selected>
                                                                            {{ $cliente[0]->SubGiro }}
                                                                        </option>
                                                                    @else
                                                                        <option value="{{ $subgiro->subgiro }}">
                                                                            {{ $subgiro->subgiro }}
                                                                        </option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="fecAniversario">Aniversario</label>
                                                            <div class="input-group">
                                                                <input id="fecAniversario" name="fecAniversario"
                                                                    type="date" class="form-control"
                                                                    placeholder="Aniversario" autocomplete="off"
                                                                    value="{{ $cliente[0]->fecAniversario }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card collapsed-card bg-transparent">
                                                        <div class="card-header bg-gray border-0 rounded"
                                                            style="height: 28px !important">
                                                            <h6 class="card-title" style="font-size: 13px">
                                                                <i class="fas fa-chevron-right mr-1"></i>
                                                                MAS INFORMACION
                                                            </h6>
                                                            <!-- card tools -->
                                                            <div class="card-tools" style="margin-top: -12px">
                                                                <button type="button" class="btn btn-secondary btn-sm"
                                                                    data-card-widget="collapse" title="Collapse"
                                                                    style="height: 28px !important">
                                                                    <i class="fas fa-plus"></i>
                                                                </button>
                                                            </div>
                                                            <!-- /.card-tools -->
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row mb-1">
                                                                <div class="col-md-4">
                                                                    <label for="fecTop">Fecha Top</label>
                                                                    <div class="input-group">
                                                                        <input id="fecTop" name="fecTop" type="date"
                                                                            class="form-control" autocomplete="off"
                                                                            value="{{ $cliente[0]->fecTop }}"
                                                                            readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label for="fecAlta">Fecha Alta</label>
                                                                    <div class="input-group">
                                                                        <input id="fecAlta" type="text"
                                                                            class="form-control" autocomplete="off"
                                                                            value="{{ \Carbon\Carbon::parse($cliente[0]->fecAlta)->format('d/m/Y H:i:s') }}"
                                                                            disabled>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label for="fecUltCambio">Fecha Ult. Cambio</label>
                                                                    <div class="input-group">
                                                                        <input id="fecUltCambio" type="text"
                                                                            class="form-control" autocomplete="off"
                                                                            value="{{ \Carbon\Carbon::parse($cliente[0]->fecUltCambio)->format('d/m/Y H:i:s') }}"
                                                                            disabled>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-1">
                                                                <div class="col-md-3">
                                                                    <label for="folio">Usuario Alta</label>
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control"
                                                                            id="idUsuarioAlta" autocomplete="off"
                                                                            value="{{ $cliente[0]->idUsuarioAlta }}"
                                                                            disabled>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label for="idUsuarioUltCambio">Usuario Ult.
                                                                        Cambio</label>
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control"
                                                                            id="idUsuarioUltCambio"
                                                                            name="idUsuarioUltCambio" autocomplete="off"
                                                                            value="{{ $cliente[0]->idUsuarioUltCambio }}"
                                                                            disabled>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- /.card-body-->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="contactos" role="tabpanel"
                                        aria-labelledby="contactos-tab">
                                        <div>
                                            <table id="tableContactosCliente"
                                                class="table table-striped table-bordered compact nowrap">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Tratamiento</th>
                                                        <th>Nombre</th>
                                                        <th>Cargo</th>
                                                        <th>Departamento</th>
                                                        <th>Teléfonos</th>
                                                        <th>Email</th>
                                                        <th>Principal</th>
                                                        <th>Editar</th>
                                                        <th>Eliminar</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($contactos as $contacto)
                                                        <tr>
                                                            <td>{{ $contacto->idContactoINT }}</td>
                                                            <td>{{ $contacto->Tratamiento }}</td>
                                                            <td>{{ $contacto->Nombre }}</td>
                                                            <td>{{ $contacto->Cargo }}</td>
                                                            <td>{{ $contacto->Departamento }}</td>
                                                            <td>{{ $contacto->Telefonos }}</td>
                                                            <td>{{ $contacto->email }}</td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="oportunidades" role="tabpanel"
                                        aria-labelledby="oportunidades-tab">
                                        <div>
                                            <table id="tableOportunidadesCliente"
                                                class="table table-striped table-bordered compact nowrap">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Oportunidad</th>
                                                        <th>Cliente</th>
                                                        <th>Estatus</th>
                                                        <th>Editar</th>
                                                        <th>Eliminar</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($oportunidades as $oportunidad)
                                                        <tr>
                                                            <td>{{ $oportunidad->idOportunidad }}</td>
                                                            <td>{{ $oportunidad->nomOportunidad }}</td>
                                                            <td>{{ $oportunidad->razonsocial }}</td>
                                                            <td>{{ $oportunidad->descEstatus }}</td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- <div class="modal-footer">
        </div> --}}
    </div>
</div>
