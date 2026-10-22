<!doctype html>
<!-- <link rel="stylesheet" type="text/css" href="{{asset('css/pdf.css')}}"> -->
<html lang="es">

<head>
    <title>{{$dto->title}}</title>
    <style type="text/css">
        body {
            font-size: 9pt;
            font-family: "Source Sans Pro", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
        }
        
        .page-break {
            page-break-after: always;
        }
        
        .tblmedidas {
            font-size: 7.5pt;
        }
        
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
        }
        
        .titulo {
            font-weight: bold;
            font-size: 13px;
            color: #000;
        }
        
        .titulo-secundario {
            font-weight: bold;
            font-size: 12px;
            text-align: center;
            letter-spacing: 8px;
            color: #000;
        }
        
        @page {
            margin: 60px 50px;
        }
        
        #header {
            position: fixed;
            top: -40px;
            text-align: right;
            font-family: 'Times';
            font-size: 8pt;
            font-style: italic;
        }
        
        .centerth {
            margin-left: auto;
            margin-right: auto;
        }
        /*background-color: orange; height: 150px;  left: 0px; top: -130px; right: 0px;*/
        
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
        
        .table-primary>th {
            background-color: #b8daff;
            text-align: center;
        }

        .table-secondary {
            font-weight: 600;
        }
        
        td.tdtblCant {
            /* width: 90%; */
            font-size: 8px;
            height: 20px;
            text-align: center;
            border: 1px solid gray;
            border-radius: 3 px !important;
            border-top-left-radius: 5 px !important;
            border-top-right-radius: 5 px !important;
            border-bottom-right-radius: 5 px !important;
            border-bottom-left-radius: 5 px !important;
        }

        .truncate {
            width: 10px;
            overflow-wrap: break-word;
            word-break: break-all;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>

<body>
    <div id="page-wrap">
        <table id="table-head" width="100%">
            <tbody>
                <tr>
                    <td width="50%" colspan="2" rowspan="5" style="text-align: left;">
                        @component('sections/logoLito') @endcomponent
                    </td>
                    <td width="50%" colspan="2" style="text-align: right; font-size: 18px !important"><b>Solicitud de Cotización</b></td>
                </tr>
                <tr>
                    <td width="50%" colspan="2" style="text-align: right; font-size: 10pt;"><b>Fecha Limite Cotización:</b> {{\Carbon\Carbon::parse($dto->Elementos['fecTopeCot'])->format('d/m/Y H:i')}}</td>
                </tr>
                <tr>
                    <td width="50%" colspan="2" style="text-align: right; font-size: 10pt;"><b>Fecha Entrega:</b> {{\Carbon\Carbon::parse($dto->Elementos['fecReqEntProd'])->format('d/m/Y')}}</td>
                </tr>
                <tr>
                    <td width="50%" colspan="2" style="text-align: right; font-size: 10pt;"><b>No:</b><strong> {{$dto->Elementos['idElementoP']}} </strong></td>
                </tr>
                <tr>
                    <td width="50%" colspan="2" style="text-align: right; font-size: 10pt;"><b>No Presupuesto:</b><strong> @if ( $dto->Elementos['numPresupuestoMTC']  == null) S/N @else {{ $dto->Elementos['numPresupuestoMTC'] }} @endif </strong></td>
                </tr>
            </tbody>
        </table>
        <table id="table1-proyecto" width="100%">
            <tbody>
                <tr>
                    <td width="10%" class="titulo"></td>
                    <td width="45%">&nbsp;</td>
                    <td width="8%">&nbsp;</td>
                    <td width="37%">&nbsp;</td>
                </tr>
                <tr>
                    <td width="10%"><b>Nombre:</b></td>
                    <td width="45%" class="bottom">{{$dto->Elementos['nomElementoP']}}</td>
                    <td width="8%"><b>Agente:</b></td>
                    <td width="37%" class="bottom">{{$dto->Elementos['NombreUsuario']}}</td>
                </tr>
            </tbody>
        </table>
        <table id="table2-proyecto" width="100%">
            <tbody>
                <tr>
                    <td width="10%"><b>Cliente:</b></td>
                    <td width="45%" class="bottom">{{$dto->Elementos['NombreCliente']}}</td>
                    <td width="10%"><b>Cantidades:</b></td>
                    <td width="7%" class="border cantidades">@isset($dto->Elementos){{$dto->Elementos['cant1']}}@endif</td>
                    <td width="7%" class="border cantidades">@isset($dto->Elementos){{$dto->Elementos['cant2']}}@endif</td>
                    <td width="7%" class="border cantidades">@isset($dto->Elementos){{$dto->Elementos['cant3']}}@endif</td>
                    <td width="7%" class="border cantidades">@isset($dto->Elementos){{$dto->Elementos['cant4']}}@endif</td>
                </tr>
            </tbody>
        </table>
        <table id="table3-Item" width="100%">
            <tbody>
                <tr>
                    <td width="10%"><b>Tipo Item:</b></td>
                    <td width="70%" class="bottom">{{$dto->Elementos['descTipoItem']}}</td>
                    <td width="10%"><b>Nuevo:</b></td>
                    <td width="10%" class="bottom">@if ($dto->Elementos['esNuevo']=1) SI @else NO @endif </td>


                </tr>
            </tbody>
        </table>
        <table id="table4-Precio" width="100%">
            <tbody>
                <tr>
                    <td width="10%"><b>Precio Objetivo:</b></td>
                    <td width="10%" class="bottom">{{$dto->Elementos['preObjetivo']}}</td>
                    <td width="10%"><b>Competencia:</b></td>
                    <td width="20%" class="bottom">{{$dto->Elementos['nomCompetencia']}}</td>
                    <td width="10%"><b>Precio Comp.:</b></td>
                    <td width="10%" class="bottom">{{$dto->Elementos['preCompetencia']}}</td>
                </tr>
            </tbody>
        </table>
        <table id="table5-Comisiones" width="100%">
            <tbody>
                <tr>
                    <td colspan="8" class="titulo" style="text-align: center;"><b>CONDICIONES DE VENTA:</b></td>


                </tr>
                <tr>
                    <td width="10%"><b>Margen:</b></td>
                    <td width="10%" class="bottom">{{$dto->Elementos['porcMargen']}} % </td>
                    <td width="10%"><b>Financiamiento:</b></td>
                    <td width="10%" class="bottom">{{$dto->Elementos['porcFinanciamiento']}} %</td>
                    <td width="10%"><b>SAAM:</b></td>
                    <td width="20%" class="bottom">{{ $dto->Elementos['porcComIsionInt'] != 0 ? $dto->Elementos['porcComIsionInt'] : '0.0' }} %</td>
                    <td width="10%"><b>Consultor:</b></td>
                    <td width="10%" class="bottom">{{$dto->Elementos['porcComisionExt']}} %</td>
                </tr>
            </tbody>
        </table>
        <br>
        <table id="table7-Especificaciones" width="100%">
            <tbody>
                <tr>
                    <td class="border">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="titulo" style="text-align: left;" colspan="3"><b>ESPECIFICACIONES TECNICAS:</b></td>
                                </tr>
                                <tr>

                                    <td>Preprensa: </td>
                                    <td class="tdtblCant text-left">{{$dto->Elementos['descTipoPP']}}</td>
                                    <td>Retoque Fotos: </td>
                                    <td class="tdtblCant text-left">@if ($dto->Elementos['conRetoque'] !=1) NO @else SI @endif</td>
                                </tr>
                            </tbody>

                        </table>
                    </td>
                    <td class="border">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="titulo" style="text-align: left;"><b>DISTRIBUCION:</b></td>
                                </tr>
                                <tr>

                                    <td>Tipo: </td>
                                    <td class="tdtblCant text-left">{{$dto->Elementos['descTipoDistribucion']}}</td>
                                    <td>Fullfilment: </td>
                                    <td class="tdtblCant text-left">@if ($dto->Elementos['conFullfilment'] !=1) NO @else SI @endif</td>
                                </tr>
                            </tbody>


                        </table>
                    </td>


                </tr>
            </tbody>

        </table>
        <br>
        <table id="table9-Encuadernado" width="100%">
            <tbody>
                <tr>
                    <td class="border">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="titulo" style="text-align: left;" colspan="6"><b>ENCUADERNADO:</b></td>
                                </tr>
                                <tr style="text-align: center;">
                                    <td><b>Encuadernado:</b> </td>
                                    <td class="tdtblCant text-left">{{$dto->Elementos['descTipoEncuadernado']}}</td>
                                    <td><b>Observaciones:</b> </td>
                                    <td class="tdtblCant text-left">{{$dto->Elementos['obsEncuadernado']}}</td>
                                    <td><b>Otros:</b> </td>
                                    <td class="tdtblCant text-left">{{$dto->Elementos['otroEncuadernado']}}</td>
                                </tr>
                            </tbody>

                        </table>
                    </td>
                </tr>
            </tbody>

        </table>
        <br>
        <table id="table10-EmpaceEspecial" width="100%">
            <tbody>
                <tr>
                    <td class="border">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="titulo" style="text-align: left;" colspan="3"><b>EMPAQUE ESPECIAL:</b></td>
                                </tr>

                                <tr style="text-align: center;">

                                    <td><b>Retractilado:</b> </td>
                                    <td class="tdtblCant text-left"> @if ($dto->tblEmpaque->where('idTipoEmpaque', 1)->unique()->first() != null) SI @else NO @endif</td>
                                    <td><b>Embolsado:</b> </td>
                                    <td class="tdtblCant text-left"> @if ($dto->tblEmpaque->where('idTipoEmpaque', 2)->unique()->first() != null) SI @else NO @endif</td>
                                    <td><b>Caja de cartón:</b> </td>
                                    <td class="tdtblCant text-left"> @if ($dto->tblEmpaque->where('idTipoEmpaque', 6)->unique()->first() != null) SI @else NO @endif</td>
                                    <td><b>Tubo de cartón:</b> </td>
                                    <td class="tdtblCant text-left"> @if ($dto->tblEmpaque->where('idTipoEmpaque', 7)->unique()->first() != null) SI @else NO @endif</td>
                                </tr>
                                <tr style="text-align: center;">

                                    <td><b>Emplayado:</b> </td>
                                    <td class="tdtblCant text-left"> @if ($dto->tblEmpaque->where('idTipoEmpaque', 3)->unique()->first() != null) SI @else NO @endif</td>
                                    <td><b>Entarimado:</b> </td>
                                    <td class="tdtblCant text-left"> @if ($dto->tblEmpaque->where('idTipoEmpaque', 4)->unique()->first() != null) SI @else NO @endif</td>
                                    <td><b>Termosellado:</b> </td>
                                    <td class="tdtblCant text-left"> @if ($dto->tblEmpaque->where('idTipoEmpaque', 5)->unique()->first() != null) SI @else NO @endif</td>
                                    <td><b>Otro:</b> </td>
                                    <td class="tdtblCant text-left"> @if ($dto->tblEmpaque->where('idTipoEmpaque', 8)->unique()->first() != null) SI @else NO @endif</td>
                                </tr>
                                <tr style="text-align: center;">
                                    <td><b>Observaciones:</b> </td>
                                    @php $tblEmpaque = $dto->tblEmpaque->where('obsEmpaque', '!=', null)->unique()->first(); @endphp
                                    <td class="tdtblCant text-left">@if ($tblEmpaque != null) {{$tblEmpaque->obsEmpaque}} @endif</td>

                                </tr>
                            </tbody>

                        </table>
                    </td>
                </tr>
            </tbody>

        </table>
        <br>
        <table id="table6-materiales" width="100%">
            <tbody>
                <tr class="text-center centerth">
                    <td valign=" top" width="100%" class="border">
                        <table id="table6-materiales" class="centerth">
                            <tbody class="text-center">
                                <tr class="table-primary ">
                                    <th width='135px'>DESCRIPCION</th>
                                    @if (!empty($dto->combo_tblmedidas1)) 
                                        @foreach ($dto->combo_tblmedidas1 as $combo_tblmedidas)
                                            <th width='135px'>
                                                
                                                @switch($combo_tblmedidas->numInterior)
                                                    @case("1")
                                                        {{ $combo_tblmedidas->nombreInterior != null ? $combo_tblmedidas->nombreInterior : 'INTERIOR 1' }}
                                                        @break
                                                    @case("2")
                                                        {{ $combo_tblmedidas->nombreInterior != null ? $combo_tblmedidas->nombreInterior : 'INTERIOR 2' }}
                                                        @break
                                                    @case("3")
                                                        {{ $combo_tblmedidas->nombreInterior != null ? $combo_tblmedidas->nombreInterior : 'INTERIOR 3' }}
                                                        @break
                                                    @case("P")
                                                        {{ $combo_tblmedidas->nombreInterior != null ? $combo_tblmedidas->nombreInterior : 'PORTADA' }}
                                                        @break

                                                    @default
                                                        Sin interior
                                                        
                                                @endswitch

                                            </th> 
                                        @endforeach 
                                    @else
                                        <th width='135px'>INTERIOR 1</th>
                                        <th width='135px'>INTERIOR 2</th>
                                        <th width='135px'>INTERIOR 3</th>
                                        <th width='135px'>PORTADA</th>
                                    @endif
                                </tr>
                                <tr class="text-left ">
                                    <th>Largo Extendido (cm)</th>@if (!empty($dto->combo_tblmedidas1)) @foreach ($dto->combo_tblmedidas1 as $combo_tblmedidas)
                                    <td class="tdtblCant text-center">{{ $combo_tblmedidas->largoExtendido }}</td>
                                        @endforeach @else
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        @endif

                                </tr>
                                <tr class="text-left ">

                                    <th>Ancho Extendido (cm)</th>
                                    @if (!empty($dto->combo_tblmedidas1)) @foreach ($dto->combo_tblmedidas1 as $combo_tblmedidas)
                                    <td class="tdtblCant text-center ">{{ $combo_tblmedidas->anchoExtendido }}</td>
                                        @endforeach @else
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        @endif

                                </tr>
                                <tr class="text-left ">

                                    <th>Largo Final (cm)</th>
                                    @if (!empty($dto->combo_tblmedidas1)) @foreach ($dto->combo_tblmedidas1 as $combo_tblmedidas) 
                                    <td class="tdtblCant text-center ">{{ $combo_tblmedidas->largoFinal }}</td>
                                        @endforeach @else
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        @endif

                                </tr>
                                <tr class="text-left ">

                                    <th>Ancho Final (cm)</th>
                                    @if (!empty($dto->combo_tblmedidas1)) @foreach ($dto->combo_tblmedidas1 as $combo_tblmedidas)
                                    <td class="tdtblCant text-center ">{{ $combo_tblmedidas->anchoFinal }}</td>
                                        @endforeach @else
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        @endif

                                </tr>

                                <tr class="text-left ">

                                    <th>Tintas Frente</th>
                                    @if (!empty($dto->combo_tblmedidas1)) @foreach ($dto->combo_tblmedidas1 as $combo_tblmedidas)
                                    <td class="tdtblCant text-center ">{{ $combo_tblmedidas->numTintasF }}</td>
                                        @endforeach @else
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        @endif
                                </tr>

                                <tr class="text-left ">
                                    <th>Tintas Vuelta</th>
                                    @if (!empty($dto->combo_tblmedidas1)) @foreach ($dto->combo_tblmedidas1 as $combo_tblmedidas)
                                    <td class="tdtblCant text-center ">{{ $combo_tblmedidas->numTintasV }}</td>
                                        @endforeach @else
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        @endif

                                </tr>

                                <tr class="text-left ">
                                    <th>Tipo Papel (Linea)</th>
                                    @if (!empty($dto->combo_tblmedidas1)) @foreach ($dto->combo_tblmedidas1 as $combo_tblmedidas)
                                    <td class="tdtblCant text-center ">{{ $combo_tblmedidas->NombrePapel }}</td>
                                        @endforeach @else
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        @endif
                                </tr>

                                {{-- <tr class="text-left ">
                                    <th>Id Papel (Linea)</th>
                                    @if (!empty($dto->combo_tblmedidas1)) @foreach ($dto->combo_tblmedidas1 as $combo_tblmedidas) @if ($combo_tblmedidas->idTipoPapel != null)
                                    <td class="tdtblCant text-center ">{{ $combo_tblmedidas->idTipoPapel }}</td>
                                        @endif @endforeach @else
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        @endif
                                </tr> --}}

                                <tr class="text-left ">
                                    <th>Papel (Especial)</th>
                                    @if (!empty($dto->combo_tblmedidas1)) @foreach ($dto->combo_tblmedidas1 as $combo_tblmedidas) 
                                    <td class="tdtblCant text-center ">{{ $combo_tblmedidas->PapelEspecial }}</td>
                                        @endforeach @else
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        @endif
                                </tr>

                                <tr class="text-left ">
                                    <th>NoPag.</th>
                                    @if (!empty($dto->combo_tblmedidas1)) 
                                        @foreach ($dto->combo_tblmedidas1 as $combo_tblmedidas)
                                            <td class="tdtblCant text-center ">{{ $combo_tblmedidas->numPaginas }}</td>
                                        @endforeach 
                                    @else
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                    @endif
                                </tr>

                                <tr class="text-left ">
                                    <th>Barniz</th>
                                    @if (!empty($dto->combo_tblmedidas1)) @foreach ($dto->combo_tblmedidas1 as $combo_tblmedidas)
                                            <td class="tdtblCant text-center ">{{ $combo_tblmedidas->Barniz }}</td>
                                        @endforeach @else
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        @endif
                                </tr>

                                <tr class="text-left ">
                                    <th>Tipo Barniz</th>
                                    @if (!empty($dto->combo_tblmedidas1)) @foreach ($dto->combo_tblmedidas1 as $combo_tblmedidas)

                                    <td class="tdtblCant text-center ">{{ $combo_tblmedidas->TipoBarniz }}</td>
                                        @endforeach @else
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        @endif
                                </tr>

                                <tr class="text-left ">
                                    <th>Laminado</th>
                                    @if (!empty($dto->combo_tblmedidas1)) @foreach ($dto->combo_tblmedidas1 as $combo_tblmedidas)

                                    <td class="tdtblCant text-center ">{{ $combo_tblmedidas->numCarasLaminado }}</td>
                                        @endforeach @else
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        @endif
                                </tr>

                                <tr class="text-left ">

                                    <th>Tipo Laminado</th>
                                    @if (!empty($dto->combo_tblmedidas1)) @foreach ($dto->combo_tblmedidas1 as $combo_tblmedidas)
                                    <td class="tdtblCant text-center ">{{ $combo_tblmedidas->descTipoLaminado }}</td>
                                        @endforeach @else
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        @endif
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <table id="table6-5-acabados" width="100%">
            <tbody>
                <tr class="text-center centerth">
                    <td valign=" top" width="100%" class="border">
                        <table id="table6-5-acabados" class="centerth">
                            <tbody class="text-center">
                                <tr>
                                    <td colspan="5 " class="titulo-secundario " width="100"><b>ACABADOS</b></td>
                                </tr>
                                <tr class="table-primary ">
                                    <th width='135px'>DESCRIPCION</th>
                                    @if (!empty($dto->combo_tblmedidas1)) 
                                        @foreach ($dto->combo_tblmedidas1 as $combo_tblmedidas)
                                            <th width='135px'>
                                                
                                                @switch($combo_tblmedidas->numInterior)
                                                    @case("1")
                                                        {{ $combo_tblmedidas->nombreInterior != null ? $combo_tblmedidas->nombreInterior : 'INTERIOR 1' }}
                                                        @break
                                                    @case("2")
                                                        {{ $combo_tblmedidas->nombreInterior != null ? $combo_tblmedidas->nombreInterior : 'INTERIOR 2' }}
                                                        @break
                                                    @case("3")
                                                        {{ $combo_tblmedidas->nombreInterior != null ? $combo_tblmedidas->nombreInterior : 'INTERIOR 3' }}
                                                        @break
                                                    @case("P")
                                                        {{ $combo_tblmedidas->nombreInterior != null ? $combo_tblmedidas->nombreInterior : 'PORTADA' }}
                                                        @break

                                                    @default
                                                        Sin interior
                                                        
                                                @endswitch

                                            </th> 
                                        @endforeach 
                                    @else
                                        <th width='135px'>INTERIOR 1</th>
                                        <th width='135px'>INTERIOR 2</th>
                                        <th width='135px'>INTERIOR 3</th>
                                        <th width='135px'>PORTADA</th>
                                    @endif
                                </tr>
                                <tr class="text-left ">
                                    <th>Doblado</th>
                                    @if (!empty($dto->combo_tblmedidas1)) @foreach ($dto->combo_tblmedidas2 as $combo_tblmedidas)

                                    <td class="tdtblCant text-center ">@if ($combo_tblmedidas->conDoblado !=1) NO @else SI @endif</td>
                                        @endforeach @else
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        @endif
                                </tr>
                                <tr class="text-left ">
                                    <th>Perforado</th>
                                    @if (!empty($dto->combo_tblmedidas1)) @foreach ($dto->combo_tblmedidas2 as $combo_tblmedidas)

                                    <td class="tdtblCant text-center ">@if ($combo_tblmedidas->conPerforado !=1) NO @else SI @endif</td>
                                        @endforeach @else
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        @endif
                                </tr>
                                <tr class="text-left ">

                                    <th>Personalizado</th>
                                    @if (!empty($dto->combo_tblmedidas1)) @foreach ($dto->combo_tblmedidas2 as $combo_tblmedidas)
                                    <td class="tdtblCant text-center ">@if ($combo_tblmedidas->conPersonalizado !=1) NO @else SI @endif</td>
                                        @endforeach @else
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        @endif
                                </tr>
                                <tr class="text-left ">
                                    <th>Suajado</th>
                                    @if (!empty($dto->combo_tblmedidas1)) @foreach ($dto->combo_tblmedidas2 as $combo_tblmedidas)

                                    <td class="tdtblCant text-center ">@if ($combo_tblmedidas->conSuajado !=1) NO @else SI @endif</td>
                                        @endforeach @else
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        @endif
                                </tr>
                                <tr class="text-left ">
                                    <th>Pegado</th>
                                    @if (!empty($dto->combo_tblmedidas1)) @foreach ($dto->combo_tblmedidas2 as $combo_tblmedidas)

                                    <td class="tdtblCant text-center ">@if ($combo_tblmedidas->conPegado !=1) NO @else SI @endif</td>
                                        @endforeach @else
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        @endif
                                </tr>

                                <tr class="text-left ">
                                    <th>Plecado</th>
                                    @if (!empty($dto->combo_tblmedidas1)) @foreach ($dto->combo_tblmedidas2 as $combo_tblmedidas)

                                    <td class="tdtblCant text-center ">@if ($combo_tblmedidas->conPlecado !=1) NO @else SI @endif</td>
                                        @endforeach @else
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        @endif
                                </tr>
                                <tr class="text-left ">
                                    <th>Otro</th>
                                    @if (!empty($dto->combo_tblmedidas1)) @foreach ($dto->combo_tblmedidas2 as $combo_tblmedidas)

                                    <td class="tdtblCant text-center ">{{ $combo_tblmedidas->descOtro }}</td>
                                        @endforeach @else
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        @endif
                                </tr>
                                <tr class="text-left ">
                                    <th>Grabado</th>
                                    @if (!empty($dto->combo_tblmedidas1)) @foreach ($dto->combo_tblmedidas2 as $combo_tblmedidas)

                                    <td class="tdtblCant text-center ">@if ($combo_tblmedidas->conGrabado !=1) NO @else SI @endif</td>
                                        @endforeach @else
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        @endif
                                </tr>

                                <tr class="text-left ">
                                    <th>HotStamping</th>
                                    @if (!empty($dto->combo_tblmedidas1)) @foreach ($dto->combo_tblmedidas2 as $combo_tblmedidas)

                                    <td class="tdtblCant text-center ">@if ($combo_tblmedidas->conHotStamping !=1) NO @else SI @endif</td>
                                        @endforeach @else
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        @endif
                                </tr>
                                <tr class="text-left ">
                                    <th>Gatefold</th>
                                    @if (!empty($dto->combo_tblmedidas1)) @foreach ($dto->combo_tblmedidas2 as $combo_tblmedidas)

                                    <td class="tdtblCant text-center ">{{ $combo_tblmedidas->descTipoGf }}</td>
                                        @endforeach @else
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        <td class="tdtblCant text-center ">0</td>
                                        @endif
                                </tr>

                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>

        <br>
        <table id="table11-AcabadosGenerales" width="100%">
            <tbody>
                <tr>
                    <td class="border">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="titulo" style="text-align: left;"><b>ACABADOS GENERALES:</b></td>
                                </tr>
                                <tr style="text-align: center;">
                                    <td><b>Cinta doble cara:</b> </td>
                                    <td class="tdtblCant text-left"> @if ($dto->tblAcabadosGrles->where('idAcabado', 1)->unique()->first() != null) SI @else NO @endif</td>
                                    <td><b>Ruteado:</b> </td>
                                    <td class="tdtblCant text-left"> @if ($dto->tblAcabadosGrles->where('idAcabado', 2)->unique()->first() != null) SI @else NO @endif</td>
                                    <td><b>Encapsulado:</b> </td>
                                    <td class="tdtblCant text-left"> @if ($dto->tblAcabadosGrles->where('idAcabado', 3)->unique()->first() != null) SI @else NO @endif</td>
                                    <td><b>Ojillos:</b> </td>
                                    <td class="tdtblCant text-left"> @if ($dto->tblAcabadosGrles->where('idAcabado', 4)->unique()->first() != null) SI @else NO @endif</td>
                                    <td><b>Otro:</b> </td>
                                    <td class="tdtblCant text-left"> @if ($dto->tblAcabadosGrles->where('idAcabado', 5)->unique()->first() != null) SI @else NO @endif</td>
                                </tr>
                            </tbody>

                        </table>
                    </td>
                </tr>
            </tbody>

        </table>

        @if (count($dto->notas) > 0)
        <br>
        <div class="border">
            <table id="table9-Notas" width="100%">
                <tbody>
                    <tr>
                        <td class="border" >
                            <table style="width: 100%;">
                                <tbody>
                                    <tr>
                                        <td class="titulo" style="text-align: left;"><b>NOTAS:</b></td>
                                    </tr>
                                    @foreach ($dto->notas as $nota)
                                    <tr style="text-align: left !important; width: 100%;">
                                        <td class="tdtblCant text-left" style="width: 100%; text-align: left !important;">{{ $nota->Nota }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endif

    </div>






</body>

</html>