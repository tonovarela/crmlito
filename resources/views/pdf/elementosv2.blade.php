<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Elementos | Solicitud cotización</title>
    <style type="text/css">
        body {
            font-size: 9pt;
            font-family: "Source Sans Pro", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
        }
        
        .page-break {
            page-break-after: always;
        }

        .page_break { page-break-before: always; }
        
        @page {
            margin: 30px 50px;
        }

        td {
            padding: 0 8px;
            line-height: 1.1;
        }

        .w-auto {
            width: auto;
        }

        .w-full {
            width: 100%;
        }

        .text-left {
            text-align: left;
        }

        .border {
            border: 1px solid #000;
            padding: 2px;
            border-radius: 10px;
        }

        .mt-1 {
            margin-top: 0.25rem;
        }

        .mt-2 {
            margin-top: 0.5rem;
        }

        .mt-4 {
            margin-top: 1rem;
        }

        .border-b {
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
        }

        .tblDetails td {
            padding: 4px;
            font-size: 8pt;
        }

        .keep-together {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>
    <div id="page-wrap">
        <table id="table-head" width="100%">
            <tbody>
                <tr>
                    <td width="50%" colspan="2" rowspan="2" style="text-align: left;">
                        @component('sections/logoLito') @endcomponent
                    </td>
                    <td width="50%" colspan="2" rowspan="2" style="text-align: right; font-size: 18px !important; margin-top: 1rem;">
                        <small style="font-size: 16px !important;">
                            <b>Folio:</b> {{ $dto['solicitud']['solicitud']->folio }}
                        </small>
                        <br>
                        <p class="mt-4">
                            <b>Solicitud de Cotización</b>
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="mt-2 keep-together">
            <table class="w-full">
                <tbody>
                    <tr>
                        <td style="float: left;">
                            <b>Oportunidad:</b>
                        </td>
                        <td style="float: right; width: 240px;">
                            <p class="border-b">{{ $dto['solicitud']['solicitud']->folio_oportunidad }} - {{ $dto['solicitud']['solicitud']->oportunidad }}</p>
                        </td>

                        <td style="float: left;">
                            <b>Vendedor:</b>
                        </td>
                        <td style="float: right; width: 240px;">
                            <p class="border-b">{{ $dto['solicitud']['solicitud']->vendedor }}</p>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table class="w-full" style="border-collapse: collapse;">
                <tbody>
                    <tr>
                        <td style="float: left;">
                            <b>Cliente:</b>
                        </td>
                        <td style="float: right; width: 140px;">
                            <p class="border-b">{{ $dto['solicitud']['solicitud']->cliente }}</p>
                        </td>
                        
                        <td style="float: left;">
                            <b>Contacto:</b>
                        </td>
                        <td style="float: right; width: 170px;">
                            <p class="border-b">{{ $dto['solicitud']['solicitud']->contacto }}</p>
                        </td>
                        
                        <td style="float: left; width: 110px;">
                            <b>Comisión Externa:</b>
                        </td>
                        <td style="float: right; width: 70px;">
                            <p class="border-b">{{ number_format($dto['solicitud']['solicitud']->comision_externa, 2) }}</p>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table class="w-full" style="border-collapse: collapse;">
                <tbody>
                    <tr class="w-f">
                        <td style="float: left; width: 30px;">
                            <b>Nombre del proyecto:</b>
                        </td>
                        <td style="float: right; width: 240px;">
                            <p class="border-b">{{ $dto['solicitud']['solicitud']->nombre_proyecto }}</p>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table class="w-full" style="border-collapse: collapse;">
                <tbody>
                    <tr>
                        <td style="float: left;">
                            <b>Cant. a cotizar 1:</b>
                        </td>
                        <td style="float: right; width: 76px;">
                            <p class="border-b">{{ number_format($dto['solicitud']['solicitud']->cantidad_cotizar_1, 0) }}</p>
                        </td>

                        <td style="float: left;">
                            <b>Cant. a cotizar 2:</b>
                        </td>
                        <td style="float: right; width: 76px;">
                            <p class="border-b">{{ number_format($dto['solicitud']['solicitud']->cantidad_cotizar_2, 0) }}</p>
                        </td>
                        
                        <td style="float: left;">
                            <b>Cant. a cotizar 3:</b>
                        </td>
                        <td style="float: right; width: 76px;">
                            <p class="border-b">{{ number_format($dto['solicitud']['solicitud']->cantidad_cotizar_3, 0) }}</p>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table style="border-collapse: collapse; width: 100%;">
                <tbody>
                    <tr>
                        <td style="float: left;">
                            <b>Unidad de negocio:</b>
                        </td>
                        <td style="float: right; width: 100px;">
                            <p class="border-b">{{ $dto['solicitud']['solicitud']->plantilla }}</p>
                        </td>

                        <td style="float: left;">
                            <b>Tipo de producto:</b>
                        </td>
                        <td style="float: right; width: 300px;">
                            <p class="border-b">{{ $dto['solicitud']['solicitud']->tipo_producto }}</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <br>

        @switch($dto['solicitud']['solicitud']->plantilla_id)
            @case(1)
                @isset($dto['solicitud']['solicitudComercial'])
                    @component('components.elementos-presupuestados.pdf.comercial', ['dto' => $dto ])
                    @endcomponent
                @endisset
                @break
            @case(2)
                @isset($dto['solicitud']['solicitudEmpaque'])
                    @component('components.elementos-presupuestados.pdf.empaque', ['dto' => $dto ])
                    @endcomponent
                @endisset
                @break
            @case(3)
                @isset($dto['solicitud']['solicitudDisplay'])
                    @component('components.elementos-presupuestados.pdf.display', ['dto' => $dto])
                    @endcomponent
                @endisset
                @break
            @case(4)
                @isset($dto['solicitud']['solicitudPop'])
                    @component('components.elementos-presupuestados.pdf.pop', ['dto' => $dto ])
                    @endcomponent
                @endisset
                @break
            @case(6)
                @isset($dto['solicitud']['solicitudMaquila'])
                    @component('components.elementos-presupuestados.pdf.maquila', ['dto' => $dto ])
                    @endcomponent
                @endisset
                @break
        @endswitch

        {{--  fin del documento  --}}
    </div>
</body>

</html>