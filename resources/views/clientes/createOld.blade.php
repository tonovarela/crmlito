@extends('layout.master')

@section('content')
    <div id="formClienteCreate" class="col-12 mt-2">
        <form method="post" action="{{ route('clientes.store') }}" class="form-horizontal" data-toggle="validator">
            {{ csrf_field() }} {{ method_field('POST') }}
            <input type="hidden" value="4259" name="idUsuario" id="idUsuario">
            {{-- <input type="hidden" name="agente" id="agente"> --}}
            <div>
                <div class="rounded bg-gray">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h6 class="text-bold pt-1 pl-1">Nuevo Cliente</h6>
                        </div>
                        <div class=" col-sm-6">
                            <h6 class="text-right text-bold pr-3 pt-1">&nbsp;No.
                                {{ $id_cliente_int[0]->idClienteINT }}
                            </h6>
                        </div>
                    </div>
                </div>
                <div class="mb-1">
                    <div class="input-group input-group-sm">
                        <label class="mt-2" for="razonsocial">Razon Social &nbsp;</label>
                        <input type="text" class="form-control col-md-8 col-lg-9 col-xl-10" id="razonsocial"
                            name="razonsocial" placeholder="Requerido" required autocomplete="off" autofocus value="{{ old('razonsocial') }}">
                        <div class="input-group-append col-md-4 col-lg-3 col-xl-2 mt-1">
                            <span class="col-md-6"><button type="submit"
                                    class="btn btn-primary btn-sm btn-block">Guardar</button></span>
                            <span class="col-md-6 center"><a href="{{ route('Clientes') }}"
                                    class="btn btn-secondary btn-sm btn-block">Cancelar</a></span>
                        </div>
                    </div>
                </div>
                <div class="tab-content" id="custom-content-below-tabContent">
                    <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel"
                        aria-labelledby="custom-content-below-home-tab">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row mb-1">
                                    <div class="col-md-4">
                                        <label for="LITONombreComercial">Nombre Comercial</label>
                                        <div class="input-group">
                                            <input id="LITONombreComercial" name="LITONombreComercial" type="text"
                                                class="form-control" placeholder="Nombre Comercial" autocomplete="off" value="{{ old('LITONombreComercial') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="DirInternet">Pagina WEB</label>
                                        <div class="input-group">
                                            <input type="text" id="DirInternet" name="DirInternet" type="text"
                                                class="form-control" placeholder="Página WEB" autocomplete="off" value="{{ old('DirInternet') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="Agente">Agente</label>
                                        <select id="Agente" name="Agente" class="form-control" style="width: 100%">
                                            <option selected disabled>Agente</option>
                                            @foreach ($agentes as $agente)
                                                <option value="{{ $agente->agente }}">
                                                    {{ $agente->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-md-6">
                                        <label for="idSituacionA">Situación</label>
                                        <select id="idSituacionA" name="idSituacionA" class="form-control"
                                            style="width: 100%">
                                            <option selected disabled>Situación</option>
                                            @foreach ($cat_situacion as $situacion)
                                                <option value="{{ $situacion->idSituacionA }}">
                                                    {{ $situacion->descSituacionA }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 mt-4">
                                        <div class="custom-control custom-switch">
                                            <input id="conBuroC" name="conBuroC" type="checkbox"
                                                class="custom-control-input" value="{{ old('conBuroC') }}">
                                            <label class="custom-control-label" for="conBuroC">Buró</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2 mt-4">
                                        <div class="custom-control custom-switch">
                                            <input id="esTop" name="esTop" type="checkbox" class="custom-control-input" value="{{ old('esTop') }}">
                                            <label class="custom-control-label" for="esTop">Top</label>
                                        </div>
                                    </div>

                                </div>
                                <div id="div_contactos_cliente" class="col-lg-12 mt-3 pb-2">
                                    <div class="mt-1 mb-1 row">
                                        <div class="rounded col-sm-12 bg-gray">
                                            <h6 class="text-bold text-center">Dirección Comercial</h6>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="row mb-1">
                                                <div class="col-md-5">
                                                    <label for="Direccion">Calle</label>
                                                    <div class="input-group">
                                                        <input id="Direccion" name="Direccion" type="text"
                                                            class="form-control" placeholder="Calle" autocomplete="off" value="{{ old('Direccion') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="DireccionNumero">Número Exterior</label>
                                                    <div class="input-group">
                                                        <input id="DireccionNumero" name="DireccionNumero" type="text"
                                                            class="form-control" placeholder="Número Exterior"
                                                            autocomplete="off" value="{{ old('DireccionNumero') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="DireccionNumeroInt">Número Interior</label>
                                                    <div class="input-group">
                                                        <input id="DireccionNumeroInt" name="DireccionNumeroInt" type="text"
                                                            class="form-control" placeholder="Número Interior"
                                                            autocomplete="off" value="{{ old('DireccionNumeroInt') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="CodigoPostalLista">Códigos Postales</label>
                                                    <div class="input-group">
                                                        <input id="CodigoPostalLista" name="CodigoPostalLista" type="text"
                                                        class="form-control" placeholder="Código Postal"
                                                        autocomplete="off" value="{{ old('CodigoPostalLista') }}">
                                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-1">
                                                <div class="col-md-2">
                                                    <label for="CodigoPostal">Código Postal</label>
                                                    <div class="input-group">
                                                        <input id="CodigoPostal" name="CodigoPostal" type="text"
                                                            class="form-control" placeholder="Código Postal"
                                                            autocomplete="off" value="{{ old('CodigoPostal') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="Alcaldia">Alcaldía</label>
                                                    <div class="input-group">
                                                        <input id="Alcaldia" name="Alcaldia" type="text"
                                                            class="form-control" placeholder="Alcaldía" autocomplete="off" value="{{ old('Alcaldia') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="Colonia">Colonia</label>
                                                    <div class="input-group">
                                                        <input id="Colonia" name="Colonia" type="text" class="form-control"
                                                            placeholder="Colonia" autocomplete="off" value="{{ old('Colonia') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="Poblacion">Población</label>
                                                    <div class="input-group">
                                                        <input id="Poblacion" name="Poblacion" type="text"
                                                            class="form-control" placeholder="Poblacion" autocomplete="off" value="{{ old('Poblacion') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="Estado">Estado</label>
                                                    <div class="input-group">
                                                        <input id="Estado" name="Estado" type="text" class="form-control"
                                                            placeholder="Estado" autocomplete="off" value="{{ old('Estado') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="Pais">País</label>
                                                    <div class="input-group">
                                                        <input id="Pais" name="Pais" type="text" class="form-control"
                                                            placeholder="Pais" autocomplete="off" value="{{ old('Pais') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="rounded col-sm-12 bg-gray">
                                            <h6 class="text-bold text-center">Dirección Fiscal</h6>
                                            <span>
                                                <div class="custom-control custom-switch" style="margin-top: -25px">
                                                    <input id="igualDirCom" name="igualDirCom" type="checkbox"
                                                        class="custom-control-input">
                                                    <label class="custom-control-label" for="igualDirCom">Misma que
                                                        Dirección Comercial</label>
                                                </div>
                                            </span>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="row mb-1">
                                                <div class="col-md-5">
                                                    <label for="DireccionFiscal">Calle</label>
                                                    <div class="input-group">
                                                        <input id="DireccionFiscal" name="DireccionFiscal" type="text"
                                                            class="form-control" placeholder="Calle" autocomplete="off" value="{{ old('DireccionFiscal') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="DireccionFiscalNumero">Número Exterior</label>
                                                    <div class="input-group">
                                                        <input id="DireccionFiscalNumero" name="DireccionFiscalNumero"
                                                            type="text" class="form-control" placeholder="Número Exterior"
                                                            autocomplete="off" value="{{ old('DireccionFiscalNumero') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="DireccionFiscalNumeroInt">Número Interior</label>
                                                    <div class="input-group">
                                                        <input id="DireccionFiscalNumeroInt" name="DireccionFiscalNumeroInt"
                                                            type="text" class="form-control" placeholder="Número Interior"
                                                            autocomplete="off" value="{{ old('DireccionFiscalNumeroInt') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="CPFiscalLista">Códigos Postales</label>
                                                    <div class="input-group">
                                                        <input id="CPFiscalLista" name="CPFiscalLista" type="text"
                                                            class="form-control" placeholder="Código Postal"
                                                            autocomplete="off" value="{{ old('CPFiscalLista') }}">
                                                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                                                        </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <label for="CPFiscal">Código Postal</label>
                                                    <div class="input-group">
                                                        <input id="CPFiscal" name="CPFiscal" type="text"
                                                            class="form-control" placeholder="Código Postal"
                                                            autocomplete="off" value="{{ old('CPFiscal') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="AlcaldiaFiscal">Alcaldía</label>
                                                    <div class="input-group">
                                                        <input id="AlcaldiaFiscal" name="AlcaldiaFiscal" type="text"
                                                            class="form-control" placeholder="Alcaldía" autocomplete="off" value="{{ old('AlcaldiaFiscal') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="ColoniaFiscal">Colonia</label>
                                                    <div class="input-group">
                                                        <input id="ColoniaFiscal" name="ColoniaFiscal" type="text"
                                                            class="form-control" placeholder="Colonia" autocomplete="off" value="{{ old('ColoniaFiscal') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="PoblacionFiscal">Población</label>
                                                    <div class="input-group">
                                                        <input id="PoblacionFiscal" name="PoblacionFiscal" type="text"
                                                            class="form-control" placeholder="Poblacion" autocomplete="off" value="{{ old('PoblacionFiscal') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="EstadoFiscal">Estado</label>
                                                    <div class="input-group">
                                                        <input id="EstadoFiscal" name="EstadoFiscal" type="text"
                                                            class="form-control" placeholder="Estado" autocomplete="off" value="{{ old('EstadoFiscal') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="PaisFiscal">País</label>
                                                    <div class="input-group">
                                                        <input id="PaisFiscal" name="PaisFiscal" type="text"
                                                            class="form-control" placeholder="Pais" autocomplete="off" value="{{ old('PaisFiscal') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <hr>
                                    <div class="row mb-1">
                                        <div class="col-md-4">
                                            <label for="RFC">R.F.C.</label>
                                            <div class="input-group">
                                                <input id="RFC" name="RFC" type="text" class="form-control"
                                                    placeholder="R.F.C." autocomplete="off" value="{{ old('RFC') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="Lada1">Lada 1</label>
                                            <div class="input-group">
                                                <input id="Lada1" name="Lada1" type="text" class="form-control"
                                                    placeholder="Lada 1" autocomplete="off" value="{{ old('Lada1') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="Telefonos">Teléfonos</label>
                                            <div class="input-group">
                                                <input id="Telefonos" name="Telefonos" type="text" class="form-control"
                                                    placeholder="Teléfonos" autocomplete="off" value="{{ old('Telefonos') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="Extencion1">Extencion 1</label>
                                            <div class="input-group">
                                                <input id="Extencion1" name="Extencion1" type="text" class="form-control"
                                                    placeholder="Extencion1" autocomplete="off" value="{{ old('Extencion1') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-md-4">
                                            <label for="eMail1">Email 1</label>
                                            <div class="input-group">
                                                <input type="email" id="eMail1" name="eMail1" class="form-control"
                                                    placeholder="Email 1" autocomplete="off" value="{{ old('eMail1') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="Lada2">Lada 2</label>
                                            <div class="input-group">
                                                <input id="Lada2" name="Lada2" type="text" class="form-control"
                                                    placeholder="Lada 2" autocomplete="off" value="{{ old('Lada2') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="Telefonos2">Teléfonos 2</label>
                                            <div class="input-group">
                                                <input id="Telefonos2" name="Telefonos2" type="text" class="form-control"
                                                    placeholder="Teléfonos2" autocomplete="off" value="{{ old('Telefonos2') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="Extencion2">Extencion 2</label>
                                            <div class="input-group">
                                                <input id="Extencion2" name="Extencion2" type="text" class="form-control"
                                                    placeholder="Extencion2" autocomplete="off" value="{{ old('Extencion2') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-md-4">
                                            <label for="CreditoCondiciones">Condiciones</label>
                                            <div class="input-group">
                                                <input id="CreditoCondiciones" name="CreditoCondiciones" type="text"
                                                    class="form-control" placeholder="Condiciones" autocomplete="off" value="{{ old('CreditoCondiciones') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="FiscalRegimen">Régimen</label>
                                            <div class="input-group">
                                                <input id="FiscalRegimen" name="FiscalRegimen" type="text"
                                                    class="form-control" placeholder="Régimen" autocomplete="off" value="{{ old('FiscalRegimen') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="Giro">Giro</label>
                                            <select id="Giro" name="Giro" class="form-control" style="width: 100%">
                                                <option selected disabled>Giro</option>
                                                @foreach ($cat_giros as $giro)
                                                    <option value="{{ $giro->Giro }}">
                                                        {{ $giro->Giro }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <label for="SubGiro">Sub Giro</label>
                                            <select id="SubGiro" name="SubGiro" class="form-control" style="width: 100%">
                                                <option selected disabled>Sub Giro</option>
                                                @foreach ($cat_subgiros as $subgiro)
                                                    <option value="{{ $subgiro->subgiro }}">
                                                        {{ $subgiro->subgiro }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="fecAniversario">Aniversario</label>
                                            <div class="input-group">
                                                <input id="fecAniversario" name="fecAniversario" type="date"
                                                    class="form-control" placeholder="Aniversario" autocomplete="off" value="{{ old('fecAniversario') }}">
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
@endsection

@section('scripts')
    <script>
        var igualdir = document.getElementById('igualDirCom')
        var codigos = document.getElementById('CodigoPostalLista');
        var calle = document.getElementById('Direccion');
        var numero = document.getElementById('DireccionNumero');
        var numeroint = document.getElementById('DireccionNumeroInt');
        var cp = document.getElementById('CodigoPostal');
        var alcaldia = document.getElementById('Alcaldia');
        var colonia = document.getElementById('Colonia');
        var poblacion = document.getElementById('Poblacion');
        var estado = document.getElementById('Estado');
        var pais = document.getElementById('Pais');

        var codigosF = document.getElementById('CPFiscalLista');
        var calleF = document.getElementById('DireccionFiscal');
        var numeroF = document.getElementById('DireccionFiscalNumero');
        var numerointF = document.getElementById('DireccionFiscalNumeroInt');
        var cpF = document.getElementById('CPFiscal');
        var alcaldiaF = document.getElementById('AlcaldiaFiscal');
        var coloniaF = document.getElementById('ColoniaFiscal');
        var poblacionF = document.getElementById('PoblacionFiscal');
        var estadoF = document.getElementById('EstadoFiscal');
        var paisF = document.getElementById('PaisFiscal');

        $(document).ready(function() {

            igualdir.addEventListener('change', function() {
                igualDireccionComercial(igualdir.checked);
            });

            function igualDireccionComercial(check) {
                if (check == true) {
                    calleF.value = calle.value;
                    numeroF.value = numero.value;
                    numerointF.value = numeroint.value;
                    cpF.value = cp.value;
                    alcaldiaF.value = alcaldia.value;
                    coloniaF.value = colonia.value;
                    poblacionF.value = poblacion.value;
                    estadoF.value = estado.value;
                    paisF.value = pais.value;
                    calleF.readOnly = true;
                    numeroF.readOnly = true;
                    numerointF.readOnly = true;
                    cpF.readOnly = true;
                    alcaldiaF.readOnly = true;
                    coloniaF.readOnly = true;
                    poblacionF.readOnly = true;
                    estadoF.readOnly = true;
                    paisF.readOnly = true;
                    codigosF.disabled = true;
                } else {
                    calleF.value = '';
                    numeroF.value = '';
                    numerointF.value = '';
                    cpF.value = '';
                    alcaldiaF.value = '';
                    coloniaF.value = '';
                    poblacionF.value = '';
                    estadoF.value = '';
                    paisF.value = '';
                    calleF.readOnly = false;
                    numeroF.readOnly = false;
                    numerointF.readOnly = false;
                    cpF.readOnly = false;
                    alcaldiaF.readOnly = false;
                    coloniaF.readOnly = false;
                    poblacionF.readOnly = false;
                    estadoF.readOnly = false;
                    paisF.readOnly = false;
                    codigosF.disabled = false;
                }
            }
        });


        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $(document).ready(function() {

            $("#CodigoPostalLista").autocomplete({
                delay: 0,
                minLength: 4,
                source: function(request, response) {
                    // Fetch data
                    $.ajax({
                        url: "{{ route('codigosP') }}",
                        type: 'GET',
                        dataType: "json",
                        data: {
                            _token: CSRF_TOKEN,
                            search: request.term
                        },
                        success: function(data) {
                            response(data);
                        }
                    });
                },
                select: function(event, ui) {
                    var codigo = JSON.stringify(ui.item.value)
                    $.ajax({
                        url: "{{ route('buscarCP') }}",
                        dataType: "json",
                        data: {
                            id_Codigo: codigo
                        },
                        success: function(data) {
                            cp.value = data[0].Codigo;
                            alcaldia.value = data[0].Municipio;
                            colonia.value = data[0].Tipo_Asenta + ' ' + data[0]
                                .Asentamiento;
                            poblacion.value = data[0].Ciudad;
                            estado.value = data[0].Estado;
                            pais.value = "MX";
                        },
                        error: function(data) {
                            cp.value = "";
                            alcaldia.value = "";
                            colonia.value = "";
                            poblacion.value = "";
                            estado.value = "";
                            pais.value = "";
                        }

                    });
                    return false;
                }
            });

            $("#CPFiscalLista").autocomplete({
                delay: 0,
                minLength: 4,
                source: function(request, response) {
                    // Fetch data
                    $.ajax({
                        url: "{{ route('codigosP') }}",
                        type: 'GET',
                        dataType: "json",
                        data: {
                            _token: CSRF_TOKEN,
                            search: request.term
                        },
                        success: function(data) {
                            response(data);
                        }
                    });
                },
                select: function(event, ui) {
                    var codigo = JSON.stringify(ui.item.value)
                    $.ajax({
                        url: "{{ route('buscarCP') }}",
                        dataType: "json",
                        data: {
                            id_Codigo: codigo
                        },
                        success: function(data) {
                            cpF.value = data[0].Codigo;
                            alcaldiaF.value = data[0].Municipio;
                            coloniaF.value = data[0].Tipo_Asenta + ' ' + data[0]
                                .Asentamiento;
                            poblacionF.value = data[0].Ciudad;
                            estadoF.value = data[0].Estado;
                            paisF.value = "MX";
                        },
                        error: function(data) {
                            cpF.value = "";
                            alcaldiaF.value = "";
                            coloniaF.value = "";
                            poblacionF.value = "";
                            estadoF.value = "";
                            paisF.value = "";
                        }
                    });
                    return false;
                }
            });
        });
    </script>
@endsection
