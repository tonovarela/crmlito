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
            <tr>
                <td style="border: solid 1px #000; width: 20%; text-align: right">Nombre del Proyecto : </td>
                <td style="border: solid 1px #000; width: 80%; text-transform: uppercase;">
                    {{ $nombre_proyecto }}&nbsp;&nbsp;</td>
            </tr>
            <tr style="width: 80%">
                <td style="border: solid 1px #000; text-align: right">Folio : </td>
                <td style="border: solid 1px #000">{{ $folio }}&nbsp;&nbsp;</td>
            </tr>
            <tr>
                <td style="border: solid 1px #000; text-align: right">Agente : </td>
                <td style="border: solid 1px #000">{{ $nombre_agente }} &nbsp;&nbsp;</td>
            </tr>
            @if ($alertas->Campo !== 'CREADO')
            <tr>
                <td style="border: solid 1px #000; text-align: right">Asignado A : </td>
                <td style="border: solid 1px #000">{{ $asignadoA }} &nbsp;&nbsp;</td>
            </tr>
            @endif
            @if ($alertas->Campo == 'CANCELADO' || $alertas->Campo == 'RECHAZADO')
                <tr>
                    <td style="border: solid 1px #000; text-align: right">Motivo de {{ $alertas->Campo == 'RECHAZADO' ? 'Rechazo' : 'Cancelación' }} : </td>
                    <td style="border: solid 1px #000">{{ $motivo_cancelacion }} &nbsp;&nbsp;</td>
                </tr>
            @endif
            @if ($alertas->Campo == 'CERRADO')
                <tr>
                    <td style="border: solid 1px #000; text-align: right">Motivo de Cierre: </td>
                    <td style="border: solid 1px #000">{{ $motivo_cancelacion }} &nbsp;&nbsp;</td>
                </tr>
            @endif
            @if ($alertas->Campo == 'NOTIFICAR A VENTAS')
                <tr>
                    <td style="border: solid 1px #000; text-align: right">Motivo de Notificación a Ventas: </td>
                    <td style="border: solid 1px #000">{{ $motivo_cancelacion }} &nbsp;&nbsp;</td>
                </tr>
            @endif
            @if ($alertas->Campo == 'REABRIR TICKET')
                <tr>
                    <td style="border: solid 1px #000; text-align: right">Motivo de Reapertura: </td>
                    <td style="border: solid 1px #000">{{ $motivo_cancelacion }} &nbsp;&nbsp;</td>
                </tr>
            @endif
            @if ($alertas->Campo == 'FECHA')
                <tr>
                    <td style="border: solid 1px #000; text-align: right">Motivo del Cambio de Fecha : </td>
                    <td style="border: solid 1px #000">{{ $motivo_cambio_fecha }} &nbsp;&nbsp;</td>
                </tr>
            @endif
        </tbody>
    </table>

    <p>Este e-mail se ha generado por un sistema automático. Por favor, no respondas a este e-mail directamente.</p>
</body>
