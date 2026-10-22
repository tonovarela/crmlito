<!DOCTYPE html>
<html>

<head>
    <title>Brief de Diseño & Cotizaciones</title>
    <style>
        /** Define the margins of your page **/

        @font-face {
            font-family: 'Hero';
            src: url('../../../public/fonts/Hero.otf') format('opentype');
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
        }

        /* .page_break {
            page-break-before: always;
        } */

        #page-wrap {
            width: 700px;
            margin: 0 auto;
        }

        .center-justified {
            text-align: justify;
            margin: 0 auto;
            width: 30em;
        }

        table.border {
            border-collapse: collapse;
        }

        table.center {
            text-align: center;
        }

        table.border,
        table.border td,
        table.border th {
            border: 2px solid black;
        }

        td.border,
        th.border {
            border: 1px solid;
        }

        td.bottom,
        th.bottom {
            border-bottom: 1px solid;
        }

        tr.center td,
        td.center {
            text-align: center;
            vertical-align: text-top;
        }

        tr.right td,
        td.right {
            text-align: right;
        }

        .grey {
            background: grey;
        }

        li {
            text-align: justify;
        }

        .cantidades {
            text-align: center;
            border-bottom: solid 1px #000;
        }

        .titulo {
            font-weight: bold;
            font-size: 13px;
            color: #686868;
        }

        .border {
            border-radius: 10px;
        }

        @page {
            margin: 30px 30px;
        }

        #header {
            position: fixed;
            top: -40px;
            text-align: right;
            font-family: 'Times';
            font-size: 8pt;
            font-style: italic;
        }

        /*background-color: orange; height: 150px;  left: 0px; top: -130px; right: 0px;*/

        footer {
            position: fixed;
            bottom: 0px;
            left: 0px;
            right: 0px;
            height: 50px;

            /** Extra personal styles **/
            /* background-color: #03a9f4; */
            color: black;
            text-align: center;
            line-height: 35px;
        }

        #footer {
            position: fixed;
            bottom: -40px;
            text-align: right;
            font-family: 'Times';
            font-size: 10pt;
            font-weight: bold;
            font-style: italic;
        }

        /*left: 0px; right: 0px; height: 150px; background-color: lightblue;*/
        /*#footer .page:after { content: "[Pagina " counter(page, upper-roman)"]"; }*/
        #footer .page:after {
            content: counter(page, upper-roman);
        }

        .pagenum:after {
            content: counter(page);
        }
    </style>
</head>

<body>
    <header>
        <table id="table-head" width="100%">
            <tbody>
                <tr>
                    <td width="50%" colspan="2" rowspan="3" style="text-align: left;">
                        {{-- <img src="{{ asset('img/Logo Lito impresos+soluciones 2.svg') }}" style="width: 250px;"> --}}
                        @component('sections/logoLito')
                        @endcomponent
                    </td>
                    <td width="50%" colspan="2" style="text-align: right; font-size: 20px !important">
                        <b>Brief
                            de
                            Diseño & Cotizaciones</b>
                    </td>
                </tr>
                <tr>
                    <td width="50%" colspan="2" style="text-align: right; font-size: 11pt;"><b>#Folio: </b>
                        <strong>{{ $dto->master }}</strong>
                </tr>
                <tr>
                    <td width="50%" colspan="2" style="text-align: right; font-size: 11pt;"><b>Fecha
                            Entrega:
                        </b>{{ $dto->FechaCompromiso == null ? 'SIN ASIGNAR' : \Carbon\Carbon::parse($dto->FechaCompromiso)->format('d/m/Y') }}</td>
                </tr>
            </tbody>
        </table>
        <table id="table-tipo" width="100%">
            <tbody>
                <tr>
                    <td width="100%" style="vertical-align: bottom"><b>(
                            {{ $dto->dum == 1 ? 'X' : '&nbsp;' }} )</b> Dummy</td>
                    <td width="100%" style="vertical-align: bottom"><b>(
                            {{ $dto->render == 1 ? 'X' : '&nbsp;' }} )</b> Render</td>
                    <td width="100%" style="vertical-align: bottom"><b>(
                            {{ $dto->trazo == 1 ? 'X' : '&nbsp;' }} )</b> Trazo Mecánico</td>
                </tr>
            </tbody>
        </table>
        <table id="table1-proyecto" width="100%">
            <tbody>
                <tr>
                    <td width="10%" class="titulo"><b>PROYECTO:</b></td>
                    <td width="45%"></td>
                    <td width="8%"></td>
                    <td width="37%"></td>
                </tr>
                <tr>
                    <td width="10%"><b>Nombre:</b></td>
                    <td width="45%" class="bottom" style="text-transform: uppercase">{{ $dto->nombre }}</td>
                    <td width="8%"><b>Agente:</b></td>
                    <td width="37%" class="bottom" style="text-transform: uppercase">{{ $dto->nombre_agente }}</td>
                </tr>
            </tbody>
        </table>
        <table id="table2-proyecto" width="100%">
            <tbody>
                <tr>
                    <td width="10%"><b>Cliente:</b></td>
                    <td width="45%" class="bottom" style="text-transform: uppercase">{{ $dto->nombreCliente }}</td>
                    <td width="10%"><b>Cantidades:</b></td>
                    <td width="7%" class="cantidades">{{ $dto->cant1 == '' ? 0 : $dto->cant1 }}</td>
                    <td width="7%" class="cantidades">{{ $dto->cant2 == '' ? 0 : $dto->cant2 }}</td>
                    <td width="7%" class="cantidades">{{ $dto->cant3 == '' ? 0 : $dto->cant3 }}</td>
                </tr>
            </tbody>
        </table>
        <table id="table3-observaciones" width="100%">
            <tbody>
                <tr>
                    <td width="100%%"><b>Observaciones:</b></td>
                </tr>
                <tr>
                    <td width="100%%" class="bottom" style="text-transform: uppercase">{{ $dto->descripcion }}</td>
                </tr>
            </tbody>
        </table>
        <br>
    </header>
    <main>
        <table id="table4" width="100%" style="margin-top: -10px">
            <tbody>
                <tr>
                    <td width="100%" class="border" valign="top"> {{-- TIPO PRODUCTO --}}
                        <table id="table4-oportunidad" width="100%">
                            <tbody>
                                <tr>
                                    <td width="100%" colspan="6" class="titulo"><b>TIPO DE PRODUCTO:</b></td>
                                </tr>
                                <tr>
                                    <td width="100%" colspan="6" class="titulo">{{ $dto->categoria }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="vertical-align: bottom"><b>{{ $dto->producto }} </td>
                                </tr>


                                <tr>
                                    <td colspan="6"></td>
                                </tr>
                                <tr style="font-size:9.5pt;">

                                    <td colspan="3">
                                        <table id="table5-medidas" width="100%">
                                            <tbody>
                                                <tr>
                                                    <td colspan="3" class="titulo"><b>VENTANA:</b></td>
                                                </tr>
                                                <tr>
                                                    <td width="100%" style="vertical-align: bottom"><b>(
                                                            {{ $dto->conAcetato == 1 ? 'X' : '&nbsp;' }} )</b> Con
                                                        acetato</td>
                                                    <td width="100%" style="vertical-align: bottom"><b>(
                                                            {{ $dto->suajado == 1 ? 'X' : '&nbsp;' }} )</b> Solo
                                                        suajado</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td colspan="3">
                                        <table id="table5-medidas" width="100%">
                                            <tbody>
                                                <tr>
                                                    <td colspan="3" class="titulo"><b>MEDIDAS GENERALES:</b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="10%">Frente</td>
                                                    <td width="23%" class="cantidades">
                                                        {{ $dto->mgFrente !== '' ? $dto->mgFrente : 0 }}</td>
                                                    <td width="10%">Fondo</td>
                                                    <td width="23%" class="cantidades">
                                                        {{ $dto->mgFondo !== '' ? $dto->mgFondo : 0 }}</td>
                                                    <td width="10%">Alto</td>
                                                    <td width="23%" class="cantidades">
                                                        {{ $dto->mgAlto !== '' ? $dto->mgAlto : 0 }}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6">
                                                        A consideración del diseñador
                                                        {{ $dto->consideracionDisign == 1 ? 'Sí ( X ), No ( )' : 'Sí ( ), No ( X )' }}</td>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                {{-- <tr>
                                    <td colspan="6"></td>
                                </tr> --}}
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td width="100%" class="border" valign="top">
                        <table id="table4-materiales" width="100%">
                            <tbody>
                                <tr>
                                    <td class="titulo"><b>MATERIALES:</b></td>
                                </tr>
                                <tr>
                                    <td colspan="2" width="20%" style="color:white;">ssssssssssssss</td>
                                    <td width="7%" class="cantidades"><b>Puntos</b></td>
                                    <td width="7%" class="cantidades"><b>Flauta</b></td>
                                </tr>
                                <tr>
                                    <td colspan="2" width="20%" style="border-bottom: #000 1px solid; text-transform: uppercase">
                                        {{ $dto->materiales1 !== '' ? $dto->materiales1 : '&nbsp;' }}</td>
                                    <td width="7%" class=" cantidades">
                                        {{ $dto->puntos1 !== '' ? $dto->puntos1 : 0 }}</td>
                                    <td width="7%" class=" cantidades">
                                        {{ $dto->flauta1 !== '' ? $dto->flauta1 : 0 }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" width="20%" style="border-bottom: #000 1px solid; text-transform: uppercase">
                                        {{ $dto->materiales2 !== '' ? $dto->materiales2 : '&nbsp;' }}</td>
                                    <td width="7%" class=" cantidades">
                                        {{ $dto->puntos2 !== '' ? $dto->puntos2 : 0 }}</td>
                                    <td width="7%" class=" cantidades">
                                        {{ $dto->flauta2 !== '' ? $dto->flauta2 : 0 }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" width="20%" style="border-bottom: #000 1px solid; text-transform: uppercase">
                                        {{ $dto->materiales3 !== '' ? $dto->materiales3 : '&nbsp;' }}</td>
                                    <td width="7%" class=" cantidades">
                                        {{ $dto->puntos3 !== '' ? $dto->puntos3 : 0 }}</td>
                                    <td width="7%" class=" cantidades">
                                        {{ $dto->flauta3 !== '' ? $dto->flauta3 : 0 }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" width="20%" style="border-bottom: #000 1px solid; text-transform: uppercase">
                                        {{ $dto->materiales4 !== '' ? $dto->materiales4 : '&nbsp;' }}</td>
                                    <td width="7%" class=" cantidades">
                                        {{ $dto->puntos4 !== '' ? $dto->puntos4 : 0 }}</td>
                                    <td width="7%" class=" cantidades">
                                        {{ $dto->flauta4 !== '' ? $dto->flauta4 : 0 }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>

                </tr>
            </tbody>
        </table>
        <table width="100%">
            <tbody>
                <tr>
                    <td width="50%" class="border">
                        <table width="100%">
                            <tbody>
                                <tr>
                                    <td width="100%" class="titulo" colspan="5"><b>Material entregado por el cliente:</b></td>
                                </tr>
                                <tr>
                                    <td colspan="3" width="100%" style="vertical-align: bottom"><b>Plano</b> ( {{ $dto->entPlano == 1 ? 'X' : '&nbsp;' }} )</td>
                                    <td colspan="3" width="100%" style="vertical-align: bottom"><b>Render</b> ( {{ $dto->entRender == 1 ? 'X' : '&nbsp;' }} )</td>
                                    <td colspan="3" width="100%" style="vertical-align: bottom"><b>Foto</b> ( {{ $dto->entFoto == 1 ? 'X' : '&nbsp;' }} )</td>
                                    <td colspan="3" width="100%" style="vertical-align: bottom"><b>Muestra Física</b> ( {{ $dto->entMuestraFisica == 1 ? 'X' : '&nbsp;' }} )</td>
                                    <td colspan="3" width="100%" style="vertical-align: bottom"><b>Producto</b> ( {{ $dto->entProducto == 1 ? 'X' : '&nbsp;' }} )</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <table width="100%">
            <tbody>
                <tr>
                    <td width="50%" class="border">
                        <table width="100%">
                            <tbody>
                                <tr>
                                    <td width="100%" class="titulo"><b>EMPAQUE:</b></td>
                                </tr>
                                <tr>
                                    <td colspan="3" width="100%" style="vertical-align: bottom"><b>(
                                        {{ $dto->cajamaster == 1 ? 'X' : '&nbsp;' }} )</b> Caja master (pza c/u)
                                    </td>
                                    <td colspan="3" width="100%" style="vertical-align: bottom"><b>({{ $dto->bolsa == 1 ? 'X' : '&nbsp;' }} )</b> Bolsa</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td width="50%" class="border">
                        <table width="100%">
                            <tbody>
                                <tr>
                                    <td width="100%" class="titulo"><b>ENTREGA:</b></td>
                                </tr>
                                <tr>
                                    <td colspan="2" width="100%" style="vertical-align: bottom"><b>(
                                            {{ $dto->armado == 1 ? 'X' : '&nbsp;' }} )</b> Armado</td>
                                    <td colspan="2" width="100%" style="vertical-align: bottom"><b>(
                                            {{ $dto->desplegado == 1 ? 'X' : '&nbsp;' }} )</b> Desplegado</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>

    </main>
</body>

</html>
