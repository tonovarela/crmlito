<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <title>Alertas CANDY</title>
</head>

<body>

    <table style="width: 100%">
        <tr style="background-color: #343A40; color: #fff; font-stretch: bold; border: #000 solid 1px">
            <th colspan="2">
                <h2>{{ $mensaje }}</h2>
            </th>
        </tr>
        <tbody>
            @if ($route == 'oportunidades')
                <tr>
                    <td style="border: solid 1px #000; width: 20%; text-align: right">Nombre de la Oportunidad : </td>
                    <td style="border: solid 1px #000; width: 80%"><a
                            href="{{ url($route) }}/{{ $folio }}/edit"
                            style="text-transform: uppercase;">{{ $nombre_oportunidad }}</a>&nbsp;&nbsp;</td>
                </tr>
                <tr style="width: 80%">
                    <td style="border: solid 1px #000; text-align: right">Folio : </td>
                    <td style="border: solid 1px #000"><a
                            href="{{ url($route) }}/{{ $folio }}/edit">{{ $folio }}</a>&nbsp;&nbsp;
                    </td>
                </tr>
            @elseif ($route == 'envios')
                <tr>
                    <td style="border: solid 1px #000; width: 20%; text-align: right">Nombre de la Oportunidad : </td>
                    <td style="border: solid 1px #000; width: 80%"><a
                            href="{{ url($route) }}"
                            style="text-transform: uppercase;">{{ $nombre_oportunidad }}</a>&nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: solid 1px #000; text-align: right">Folio : </td>
                    <td style="border: solid 1px #000"><a
                            href="{{ url($route) }}">{{ $folio }}</a>&nbsp;&nbsp;
                    </td>
                </tr>
            @else
                <tr>
                    <td style="border: solid 1px #000; text-align: right">Nombre de la Oportunidad : </td>
                    <td style="border: solid 1px #000; text-transform: uppercase;">{{ $nombre_oportunidad }}
                        &nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: solid 1px #000; text-align: right">Folio : </td>
                    <td style="border: solid 1px #000">{{ $folio }} &nbsp;&nbsp;</td>
                </tr>
            @endif
            <tr>
                <td style="border: solid 1px #000; text-align: right">Estatus Actual : </td>
                <td style="border: solid 1px #000">{{ $estatus_oportunidad }} &nbsp;&nbsp;</td>
            </tr>
            <tr>
                <td style="border: solid 1px #000; text-align: right">Agente : </td>
                <td style="border: solid 1px #000">{{ $nombre_agente }} &nbsp;&nbsp;</td>
            </tr>
            {{-- @if ($diasEntrega != null && $fecha_aut_archivo == null && $fecha_entrega == null)
                <tr>
                    <td style="border: solid 1px #000; text-align: right">Fecha Entrega : </td>
                    <td style="border: solid 1px #000; text-transform: uppercase;">{{ $diasEntrega }} DÍAS A PARTIR
                        DE LA FECHA DEL ARCHIVO AUTORIZADO &nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: solid 1px #000; text-align: right">Nota : </td>
                    <td style="border: solid 1px #000; text-transform: uppercase;">{{ $nota }} &nbsp;&nbsp;
                    </td>
                </tr>
            @elseif ($diasEntrega != null && $fecha_aut_archivo != null)
                <tr>
                    <td style="border: solid 1px #000; text-align: right">Fecha Entrega : </td>
                    <td style="border: solid 1px #000; text-transform: uppercase;"><strong>{{ $fecha_entrega }} /
                            {{ $diasEntrega }} DÍAS SI EL ARCHIVO SE RECIBE EN LA FECHA
                            {{ \Carbon\Carbon::parse($fecha_aut_archivo)->format('d/m/Y') }}</strong>&nbsp;&nbsp;
                    </td>
                </tr>
                <tr>
                    <td style="border: solid 1px #000; text-align: right">Fecha Autorización : </td>
                    <td style="border: solid 1px #000; text-transform: uppercase;">
                        {{ \Carbon\Carbon::parse($fecha_aut_archivo)->format('d/m/Y H:i') }} &nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: solid 1px #000; text-align: right">Orden Metrics : </td>
                    <td style="border: solid 1px #000; text-transform: uppercase;">{{ $numOrdenMTC }} &nbsp;&nbsp;
                    </td>
                </tr>
                <tr>
                    <td style="border: solid 1px #000; text-align: right">Nota : </td>
                    <td style="border: solid 1px #000; text-transform: uppercase;">{{ $nota }} &nbsp;&nbsp;
                    </td>
                </tr> --}}
            {{-- @elseif ($diasEntrega == null && $fecha_entrega != null)
                <tr>
                    <td style="border: solid 1px #000; text-align: right">Fecha Archivo Autorizado : </td>
                    <td style="border: solid 1px #000; text-transform: uppercase;">
                        {{ \Carbon\Carbon::parse($fecha_aut_archivo)->format('d/m/Y') }} &nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: solid 1px #000; text-align: right">Fecha Entrega : </td>
                    <td style="border: solid 1px #000; text-transform: uppercase;">{{ $fecha_entrega }} &nbsp;&nbsp;
                    </td>
                </tr>
                <tr>
                    <td style="border: solid 1px #000; text-align: right">Orden Metrics : </td>
                    <td style="border: solid 1px #000; text-transform: uppercase;">{{ $numOrdenMTC }} &nbsp;&nbsp;
                    </td>
                </tr>
                <tr>
                    <td style="border: solid 1px #000; text-align: right">Nota : </td>
                    <td style="border: solid 1px #000; text-transform: uppercase;">{{ $nota }} &nbsp;&nbsp;
                    </td>
                </tr> --}}
            {{-- @elseif ($fecha_entrega != null) --}}
            @if ($fecha_entrega != null && $fecha_aut_archivo == null)
                {{-- <tr>
                    <td style="border: solid 1px #000; text-align: right">Fecha Archivo Autorizado : </td>
                    <td style="border: solid 1px #000; text-transform: uppercase;">
                        {{ \Carbon\Carbon::parse($fecha_aut_archivo)->format('d/m/Y') }} &nbsp;&nbsp;</td>
                </tr> --}}
                @if ($rejectDocument == 0)
                <tr>
                    <td style="border: solid 1px #000; text-align: right">Fecha Entrega : </td>
                    <td style="border: solid 1px #000; text-transform: uppercase;">{{ $fecha_entrega }} &nbsp;&nbsp;
                    </td>
                </tr>
                <tr>
                    <td style="border: solid 1px #000; text-align: right">Orden Metrics : </td>
                    <td style="border: solid 1px #000; text-transform: uppercase;">{{ $numOrdenMTC }} &nbsp;&nbsp;
                    </td>
                </tr>
                @endif
                <tr>
                    <td style="border: solid 1px #000; text-align: right">Nota : </td>
                    <td style="border: solid 1px #000; text-transform: uppercase;">{{ $nota }} &nbsp;&nbsp;
                    </td>
                </tr>
            @elseif ($fecha_entrega != null && $fecha_aut_archivo != null)
                <tr>
                    <td style="border: solid 1px #000; text-align: right">Fecha Archivo Autorizado : </td>
                    <td style="border: solid 1px #000; text-transform: uppercase;">
                        {{ \Carbon\Carbon::parse($fecha_aut_archivo)->format('d/m/Y H:i') }} &nbsp;&nbsp;</td>
                </tr>
                @if ($rejectDocument == 0)
                <tr>
                    <td style="border: solid 1px #000; text-align: right">Fecha Entrega : </td>
                    <td style="border: solid 1px #000; text-transform: uppercase;">{{ $fecha_entrega }} &nbsp;&nbsp;
                    </td>
                </tr>
                <tr>
                    <td style="border: solid 1px #000; text-align: right">Orden Metrics : </td>
                    <td style="border: solid 1px #000; text-transform: uppercase;">{{ $numOrdenMTC }} &nbsp;&nbsp;
                    </td>
                </tr>
                @endif
                <tr>
                    <td style="border: solid 1px #000; text-align: right">Nota : </td>
                    <td style="border: solid 1px #000; text-transform: uppercase;">{{ $nota }} &nbsp;&nbsp;
                    </td>
                </tr>
            @elseif ($fecha_entrega == null && $fecha_aut_archivo != null)
                <tr>
                    <td style="border: solid 1px #000; text-align: right">Fecha Archivo Autorizado : </td>
                    <td style="border: solid 1px #000; text-transform: uppercase;">
                        {{ \Carbon\Carbon::parse($fecha_aut_archivo)->format('d/m/Y H:i') }} &nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: solid 1px #000; text-align: right">Orden Metrics : </td>
                    <td style="border: solid 1px #000; text-transform: uppercase;">{{ $numOrdenMTC }} &nbsp;&nbsp;
                    </td>
                </tr>
            @elseif ($fecha_solicitud != null)
                <tr>
                    <td style="border: solid 1px #000; text-align: right">Fecha Solicitud : </td>
                    <td style="border: solid 1px #000; text-transform: uppercase;">
                        {{ \Carbon\Carbon::parse($fecha_solicitud)->format('d/m/Y H:i') }} &nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: solid 1px #000; text-align: right">Nota : </td>
                    <td style="border: solid 1px #000; text-transform: uppercase;">{{ $nota }} &nbsp;&nbsp;
                    </td>
                </tr>
            @else
                <tr>
                    <td style="border: solid 1px #000; text-align: right">Fecha Creación : </td>
                    <td style="border: solid 1px #000; text-transform: uppercase;">{{ $fecha }} &nbsp;&nbsp;
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    {{-- <ul>
        @if ($route == 'oportunidades')
            <li>Nombre de la Oportunidad : <a href="{{ url($route) }}/{{ $folio }}/edit" style="text-transform: uppercase;">{{ $nombre_oportunidad }}</a></li>
            <li>Folio : <a href="{{ url($route) }}/{{ $folio }}/edit">{{ $folio }}</a></li>
        @elseif ($route == "envios")
            <li>Nombre de la Oportunidad : <a href="{{ url($route) }}" style="text-transform: uppercase;">{{ $nombre_oportunidad }}</a></li>
            <li>Folio : <a href="{{ url($route) }}">{{ $folio }}</a></li>
        @else
            <li style="text-transform: uppercase;">Nombre de la Oportunidad : {{ $nombre_oportunidad }}</li>
            <li>Folio : {{ $folio }}</li>
        @endif

        <li>Estatus Actual : {{ $estatus_oportunidad }}</li>
        <li>Agente : {{ $nombre_agente }}</li>

        @if ($diasEntrega != null && $fecha_aut_archivo == null && $fecha_entrega == null)
            <li>Fecha Entrega : {{ $diasEntrega }} DÍAS A PARTIR DE LA FECHA DEL ARCHIVO AUTORIZADO</li>
            <li style="text-transform: uppercase;">Nota : {{ $nota }}</li>
        @elseif ($diasEntrega != null && $fecha_aut_archivo != null)
            <li>Fecha Entrega : <strong>{{ $fecha_entrega }} / {{ $diasEntrega }} DÍAS SI EL ARCHIVO SE RECIBE EN LA FECHA {{ \Carbon\Carbon::parse($fecha_aut_archivo)->format('d/m/Y') }}</strong></li>
            <li>Fecha Autorización : {{ \Carbon\Carbon::parse($fecha_aut_archivo)->format('d/m/Y H:i') }}</li>
            <li style="text-transform: uppercase;">Nota : {{ $nota }}</li>
        @elseif ($diasEntrega == null && $fecha_entrega != null)
            <li>Fecha Archivo Autorizado : {{ \Carbon\Carbon::parse($fecha_aut_archivo)->format('d/m/Y') }}</li>
            <li>Fecha Entrega : {{ $fecha_entrega }}</li>
            <li style="text-transform: uppercase;">Nota : {{ $nota }}</li>
        @elseif ($diasEntrega != null && $fecha_entrega != null)
            <li>Fecha Archivo Autorizado : {{ \Carbon\Carbon::parse($fecha_aut_archivo)->format('d/m/Y') }}</li>
            <li>Fecha Entrega : {{ $fecha_entrega }}</li>
            <li style="text-transform: uppercase;">Nota : {{ $nota }}</li>
        @elseif ($fecha_aut_archivo != null)
            <li>Fecha Archivo Autorizado : {{ \Carbon\Carbon::parse($fecha_aut_archivo)->format('d/m/Y H:i') }}</li>
            <li>Fecha Entrega : {{ $fecha_entrega }}</li>
        @elseif ($fecha_solicitud != null)
            <li>Fecha Solicitud : {{ \Carbon\Carbon::parse($fecha_solicitud)->format('d/m/Y H:i') }}</li>
            <li style="text-transform: uppercase;">Nota : {{ $nota }}</li>
        @else
            <li>Fecha Creación : {{ $fecha }}</li>
        @endif

    </ul> --}}

    <p>Este e-mail se ha generado por un sistema automático. Por favor, no respondas a este e-mail directamente.</p>
</body>
