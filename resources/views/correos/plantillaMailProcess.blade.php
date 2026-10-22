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
                <h2>Se ha solicitado la ficha técnica sobre el producto "<strong>{{ $nomProductoFicha }}</strong>", del cliente "<strong>{{ $nomCliente }}</strong>". Favor de completar la información en la sección que te corresponde.</h2>
            </th>
        </tr>
        <tbody>
            <tr>
                <td style="border: solid 1px #000; width: 20%; text-align: right">Solicitado por : </td>
                <td style="border: solid 1px #000; width: 80%">{{ $usuarioNombre }} &nbsp;&nbsp;</td>
            </tr>

            <tr>
                <td style="border: solid 1px #000; width: 20%; text-align: right">Fecha de solicitud : </td>
                <td style="border: solid 1px #000; width: 80%">{{ now()->format('d/m/Y') }} &nbsp;&nbsp;</td>
            </tr>
        </tbody>
    </table>

    <p>Este e-mail se ha generado por un sistema automático. Por favor, no respondas a este e-mail directamente.</p>
</body>
