<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Presupuesto - Elementos</title>
    <style type="text/css">
        @page { margin: 0pt; }

        body {
            font-size: 10pt;
            background: #e4e4e4;
            color: #323232;
            font-family: 'Helvetica', sans-serif;
            padding: 0.2in 0.6in;
        }

        .page-break {
            page-break-after: always;
        }

        .page_break { page-break-before: always; }

        .text-lg {
            font-size: 18pt;
        }

        .mt-2 {
            margin-top: 9px;
        }

        .-mt-2 {
            margin-top: -9px;
        }

        p {
            text-align: justify;
            line-height: 1.2;
        }

        .keep-together {
            page-break-inside: avoid;
        }

    </style>
</head>

<body>
    <div id="page-wrap">
        <div>
            <div>
                <table width="100%">
                    <tr>
                        <td>
                            <h3 class="text-lg" style="color: #1F5078; font-weight: bold; text-align: left;">
                                @if ($presupuesto->plantilla != 'Maquila')
                                Presupuesto División {{ $presupuesto->plantilla }}
                                @else
                                División de Impresión Comercial.
                                @endif
                            </h3>
                        </td>
                        <td>
                            <img src="img/cert/lg.png" alt="logo litoprocess" style="width: 180px; height: auto; margin-left: 80pt;" />
                        </td>
                    </tr>
                </table>
            </div>

            <div>
                <p class="mt-2">
                    <strong>Estimado(a) {{ $presupuesto->contacto }},</strong>
                </p>

                <p class="mt-2">
                    Por medio de la presente, Litoprocess se complace en presentar el presupuesto solicitado para la producción de 
                    {{ (count($presupuestos) > 1) ?  'sus elementos' : 'su elemento' }}
                    <strong>{{ $elementosTitulos }}</strong>. Nuestro compromiso es ofrecer soluciones de impresión de la más alta calidad, adaptadas a las necesidades específicas de <strong>{{ $presupuesto->cliente }}</strong>
                </p>

                <p class="mt-2">
                    Este documento detalla las especificaciones técnicas, los costos y las condiciones comerciales, asegurando un producto final que refleje la excelencia de su marca.
                </p>

                <p class="mt-2">
                    Agradecemos su confianza en nuestros servicios y quedamos a su disposición para cualquier aclaración o ajuste.
                </p>
            </div>
        </div>

        <table width="100%" style="margin-top: -10px;">
            <tr>
                <td style="color: #1F5078; font-weight: bold; font-size: 11pt; padding: 10px 0;">
                    Cliente
                </td>

                <td style="color: #1F5078; font-weight: bold; font-size: 11pt; padding: 10px 0;">
                    Agente de Ventas
                </td>
            </tr>

            <tr>
                <td style="font-weight: bold; padding-bottom: 10px;">
                    {{ $presupuesto->cliente }}
                </td>

                <td style="font-weight: bold; padding-bottom: 10px;">
                    {{ $presupuesto->vendedor }}
                </td>
            </tr>

            <tr>
                <td style="font-size: 9pt; line-height: 1.2; width: 50%;">
                    <div style="width: 150pt;">
                        {{ $presupuesto->Direccion }}
                    </div>
                </td>

                <td style="font-size: 9pt; line-height: 1.2; width: 50%; text-align: left;">
                    <div style="width: 150pt;">
                        <div>
                            <img src="img/telefono.png" alt="" width="11" height="11"> Tel: 55-2122-5600 &nbsp; Ext: {{ $presupuesto->Extension }}
                        </div>

                        @php
                            function mascaraTelefono($numero) {
                                // Elimina cualquier caracter que no sea dígito
                                $numero = preg_replace('/\D/', '', $numero);

                                // Aplica la máscara si tiene al menos 10 dígitos
                                if (strlen($numero) == 10) {
                                    return substr($numero, 0, 2) . '-' . substr($numero, 2, 4) . '-' . substr($numero, 6, 4);
                                }
                                // Si no tiene 10 dígitos, regresa el número original
                                return $numero;
                            }

                            $telefono = ($presupuesto->numCelular) ? mascaraTelefono($presupuesto->numCelular) : '';
                        @endphp

                        <div>
                            <img src="img/whatsapp.png" alt="" width="11" height="11"> Cel: {{ $telefono }}
                        </div>
                        <div>
                            Correo: {{ $presupuesto->Correo }}
                        </div>
                        <div>
                            <strong>Fecha: {{ \Carbon\Carbon::parse($presupuesto->fecha_actual)->format('d-m-Y') }}</strong>
                        </div>
                        {{--  <div style="padding-top: 1px;">
                            <strong>Presupuesto # 179675</strong>
                        </div>  --}}
                    </div>
                </td>
            </tr>
        </table>

        @php
            function format_mm($valor) {
                if ($valor === null || $valor === '' || $valor === 'N/A') return null;
                if (is_numeric($valor)) {
                    return (fmod($valor, 1) == 0) ? number_format($valor, 0) : number_format($valor, 2);
                }
                $partes = preg_split('/\s*x\s*/', $valor);
                if (count($partes) > 1) {
                    $partes = array_map(function($v) {
                        return (is_numeric($v) && fmod($v, 1) == 0) ? number_format($v, 0) : number_format((float)$v, 2);
                    }, $partes);
                    return implode(' x ', $partes);
                }
                return $valor;
            }

            function medidas_con_texto($label, $ancho, $largo = null, $alto = null) {
                $partes = [];
                if (!empty($ancho) && $ancho !== 'N/A' && is_numeric($ancho) && $ancho > 0) $partes[] = '' . format_mm($ancho);
                if (!empty($largo) && $largo !== 'N/A' && is_numeric($largo) && $largo > 0) $partes[] = '' . format_mm($largo);
                if (!empty($alto) && $alto !== 'N/A' && is_numeric($alto) && $alto > 0) $partes[] = '' . format_mm($alto);
                if (count($partes) > 0) {
                    return '<strong>' . $label . ' (mm):</strong> ' . implode(' x ', $partes);
                }
                return null;
            }
        @endphp

        {{--  Funciones Comerciales  --}}
        @include('components.elementos-presupuestados.pdf.funciones-comercial')
        @include('components.elementos-presupuestados.pdf.funciones-empaque')
        @include('components.elementos-presupuestados.pdf.funciones-display')
        @include('components.elementos-presupuestados.pdf.funciones-pop')

        {{--  Funciones Comerciales  --}}

        @foreach ($presupuestos as $pres)
        <div class="keep-together">
            <table width="100%" style="color: #1F5078; font-weight: bold; margin-top: 20px;" class="text-lg">
                <tr>
                    <td style="color: #1F5078; font-weight: bold;">
                        Especificaciones de {{ $pres->nombre_proyecto }}
                    </td>

                    <td style="font-weight: bold; text-align: right; font-size: 12pt;">
                        Folio: {{ $pres->folio_metrics }}
                    </td>
                </tr>
            </table>

            <p class="mt-2" style="color: #1F5078;">
                @php
                    $medidas = [];

                    $agregarParRaw = function ($labelFinal, $labelExtendida, $final, $extendida) use (&$medidas) {
                        $partes = [];

                        if (!empty($final) && $final !== 'N/A') {
                            $partes[] = '<strong>' . $labelFinal . ' (mm):</strong> ' . format_mm($final);
                        }

                        if (!empty($extendida) && $extendida !== 'N/A') {
                            $partes[] = '<strong>' . $labelExtendida . ' (mm):</strong> ' . format_mm($extendida);
                        }

                        if (!empty($partes)) {
                            $medidas[] = implode('; ', $partes);
                        }
                    };

                    $agregarParRaw('Medida Final', 'Medida Extendida', $pres->medida_final_interiores ?? null, $pres->medida_extendida_interiores ?? null);

                    if ($plantilla == 4) {
                        $agregarParRaw('Medida Final', 'Medida Extendida', $pres->medida_final ?? null, $pres->medida_extendida ?? null);
                    }

                    $agregarParTexto = function ($textoFinal, $textoExtendida) use (&$medidas) {
                        $partes = [];

                        if (!empty($textoFinal)) {
                            $partes[] = $textoFinal;
                        }

                        if (!empty($textoExtendida)) {
                            $partes[] = $textoExtendida;
                        }

                        if (!empty($partes)) {
                            $medidas[] = implode('; ', $partes);
                        }
                    };

                    if ($plantilla == 3) {
                        $agregarParTexto(
                            medidas_con_texto('Medida Final', $pres->medida_final_ancho ?? null, $pres->medida_final_largo ?? null, $pres->medida_final_alto ?? null),
                            medidas_con_texto('Medida Extendida', $pres->medida_extendida_ancho ?? null, $pres->medida_extendida_largo ?? null, $pres->medida_extendida_alto ?? null)
                        );
                    }

                    $agregarParTexto(
                        medidas_con_texto('Medida Final', $pres->medida_final_ancho_general ?? null, $pres->medida_final_largo_general ?? null, $pres->medida_final_alto_general ?? null),
                        medidas_con_texto('Medida Extendida', $pres->medida_extendida_ancho_general ?? null, $pres->medida_extendida_largo_general ?? null, $pres->medida_extendida_alto_general ?? null)
                    );

                    $agregarParTexto(
                        medidas_con_texto('Medida Final de Tacon 1', $pres->medida_final_ancho_t1 ?? null, $pres->medida_final_largo_t1 ?? null, $pres->medida_final_alto_t1 ?? null),
                        medidas_con_texto('Medida Extendida de Tacon 1', $pres->medida_extendida_ancho_t1 ?? null, $pres->medida_extendida_largo_t1 ?? null, $pres->medida_extendida_alto_t1 ?? null)
                    );

                    $agregarParTexto(
                        medidas_con_texto('Medida Final de Tacon 2', $pres->medida_final_ancho_t2 ?? null, $pres->medida_final_largo_t2 ?? null, $pres->medida_final_alto_t2 ?? null),
                        medidas_con_texto('Medida Extendida de Tacon 2', $pres->medida_extendida_ancho_t2 ?? null, $pres->medida_extendida_largo_t2 ?? null, $pres->medida_extendida_alto_t2 ?? null)
                    );

                    $med = implode('<br>', array_filter($medidas));
                @endphp
                {!! $med !!}
            </p>

            <div style="page-break-inside: auto;">
                @switch($plantilla)
                    @case(1)
                        @include('components.elementos-presupuestados.pdf.especificaciones_comercial')
                        @break
                    @case(2)
                        @include('components.elementos-presupuestados.pdf.especificaciones_empaque')
                        @break
                    @case(3)
                        @include('components.elementos-presupuestados.pdf.especificaciones_display')
                        @break
                    @case(4)
                        @include('components.elementos-presupuestados.pdf.especificaciones_pop')
                        @break
                    @case(6)
                        @include('components.elementos-presupuestados.pdf.especificaciones_maquila')
                        @break
                @endswitch
            </div>
        </div>

        <div class="keep-together">
            <h3 class="text-lg" style="color: #1F5078; font-weight: bold; margin-top: 16px; margin-bottom: -5px;">
                Detalles de Precios
            </h3>

            <p style="font-size: 12px; margin-bottom: -5px;">EP: {{ $pres->folio_ep }}</p>

            <table width="100%" style="border-collapse: collapse; margin-top: 4px;" border="1">
                <tr>
                    <td style="text-align: center;"><strong>Cantidad</strong></td>
                    <td style="text-align: center;"><strong>Precio Unitario</strong></td>
                    <td style="text-align: center;"><strong>Precio Total</strong></td>
                </tr>
                
                <tr>
                    <td style="text-align: center">{{ number_format($pres->cantidad_1, 0) }}</td>
                    <td style="text-align: center;">$ {{ number_format($pres->precio_1, 3) }}</td>
                    <td style="text-align: center;">$ {{ number_format($pres->cantidad_1 * $pres->precio_1, 2) }}</td>
                </tr>

                @if ($pres->cantidad_2 != 0)
                <tr>
                    <td style="text-align: center">{{ number_format($pres->cantidad_2, 0) }}</td>
                    <td style="text-align: center;">$ {{ number_format($pres->precio_2, 3) }}</td>
                    <td style="text-align: center;">$ {{ number_format($pres->cantidad_2 * $pres->precio_2, 2) }}</td>
                </tr>
                @endif

                @if ($pres->cantidad_3 != 0)
                <tr>
                    <td style="text-align: center">{{ number_format($pres->cantidad_3, 0) }}</td>
                    <td style="text-align: center;">$ {{ number_format($pres->precio_3, 3) }}</td>
                    <td style="text-align: center;">$ {{ number_format($pres->cantidad_3 * $pres->precio_3, 2) }}</td>
                </tr>
                @endif

                @php
                    $cantidadesAdicionalesFiltrado = $cantidadesAdicionales->where('solicitud_cotizacion_id', $pres->solicitud_cotizacion_id);
                    foreach ($cantidadesAdicionalesFiltrado as $adicional) {
                @endphp
                <tr>
                    <td style="text-align: center">{{ number_format($adicional->cantidad, 0) }}</td>
                    <td style="text-align: center;">$ {{ number_format($adicional->precio, 2) }}</td>
                    <td style="text-align: center;">$ {{ number_format($adicional->cantidad * $adicional->precio, 2) }}</td>
                </tr>
                @php
                    }
                @endphp
                
                {{--  @if ($pres->cantidad_2 != 0 || $pres->cantidad_3 != 0)
                <tr>
                    <td style="text-align: center"><strong>Total</strong></td>
                    <td style="text-align: center;">$ {{ number_format($pres->precio_1 + $pres->precio_2 + $pres->precio_3, 2) }}</td>
                    <td style="text-align: center;">$ {{ number_format(($pres->cantidad_1 * $pres->precio_1) + ($pres->cantidad_2 * $pres->precio_2) + ($pres->cantidad_3 * $pres->precio_3), 2) }}</td>
                </tr>
                @endif  --}}
            </table>
        </div>

        @if ($pres->categoria != null && $pres->subcategoria != null)
        <small class="text-center" style="display: block; margin-top: 4px; font-size: 8pt; color: #555; text-align: center; font-weight: bold;">
            <br>
            {{ 'Certificado FSC: PBN-COC-005509 Tipo de producto FSC ' . $pres->categoria . ', ' . $pres->subcategoria . '' }}
        </small>
        @endif

        @if ($pres->observaciones_presupuesto != null && $pres->observaciones_presupuesto != '')
        <p style="border-left: 4px solid #1F5078; padding: 3px 6px; border-radius: 2px; background: #fff; font-size: 9pt;">
            <strong>Observaciones:</strong> {{ $pres->observaciones_presupuesto }}
        </p>
        @endif
        @endforeach

    </div>

    <div class="page-break"></div>

    <div id="page-wrap">
        <h3 class="text-lg" style="color: #1F5078; font-weight: bold; margin-top: 20px;">
            Condiciones Comerciales
        </h3>

        <table width="100%" style="border-collapse: separate; border-spacing: 5px 12px; margin-top: -10px;">
            <tr>
                @php
                    $empaque = isset($presupuestos[0]->empaque) ? trim($presupuestos[0]->empaque) : trim($presupuestos[0]->empaque_primario);
                    $entrega = isset($presupuestos[0]->entrega) ? trim($presupuestos[0]->entrega) : '';
                    $lugarEntrega = trim($presupuestos[0]->lugar_entrega) ?? '';

                    // Opciones de entrega reconocidas
                    $entregasOpciones = [
                        'Local Entrega en 1punto' => 'La entrega se realizará localmente en un 1 punto.',
                        'Local Entrega en varios puntos (Especificar)' => 'La entrega se realizará localmente en varios puntos.',
                        'Foraneo Entrega en 1 punto' => 'La entrega se realizará foráneamente en un 1 punto.',
                        'Foraneo Entrega en varios puntos (Especificar)' => 'La entrega se realizará foráneamente en varios puntos.',
                        'Cliente Recolecta' => 'El cliente realizará la recolección del producto.',
                        '' => 'Ninguna opción de entrega especificada.',
                    ];

                    $mostrarEmpaque = !empty($empaque) && $empaque !== 'Otro (Especificar en Observaciones)';
                    $mostrarEntrega = (!empty($entrega) && $entrega !== 'Otro (Especificar en Observaciones)' && $entrega !== 'N/A' && $entrega !== 'Varios Destinos (Especificar en Observaciones)');

                    $textoEmpaqueEntrega = '';

                    if ($mostrarEmpaque) {
                        $textoEmpaqueEntrega .= "El empaque se realizará en: <strong>{$empaque}</strong>.";
                    }

                    if ($mostrarEntrega) {
                        // Si la opción de entrega está en el arreglo, usa el texto formal
                        if (array_key_exists($entrega, $entregasOpciones)) {
                            $textoEmpaqueEntrega .= ($textoEmpaqueEntrega ? ' ' : '') . $entregasOpciones[$entrega];
                        } else {
                            $textoEmpaqueEntrega .= ($textoEmpaqueEntrega ? ' ' : '') . "La entrega se efectuará en: <strong>{$entrega}</strong>.";
                        }
                        // Si hay lugar de entrega, agrégalo
                        if (!empty($lugarEntrega) && $lugarEntrega !== 'Otro (Especificar en Observaciones)' && $lugarEntrega !== 'N/A' && $lugarEntrega !== 'Varios Destinos (Especificar en Observaciones)') {
                            $textoEmpaqueEntrega .= " <br><strong>Lugar de entrega:</strong> {$lugarEntrega}.";
                        }
                    }

                    // Para display
                    if ($presupuestos[0]->plantilla_id == 3) {
                        if (!empty($lugarEntrega) && $lugarEntrega !== 'Otro (Especificar en Observaciones)' && $lugarEntrega !== 'N/A' && $lugarEntrega !== 'Varios Destinos (Especificar en Observaciones)') {
                            $textoEmpaqueEntrega .= " <br><strong>Lugar de entrega:</strong> {$lugarEntrega}.";
                        }
                    }

                    if (!$mostrarEmpaque && !$mostrarEntrega) {
                        $textoEmpaqueEntrega = "El empaque y la entrega serán definidos conforme a las observaciones del proyecto.";
                    }
                @endphp

                @if ($textoEmpaqueEntrega != '')
                <td style="border-left: 4px solid #1F5078; padding: 12px 12px 12px 10px; border-radius: 4px; background: #fff; font-size: 9pt; vertical-align: top; width: 48%;">
                    <h3 style="margin-top: 0; margin-bottom: 6px;">Empaque y entrega:</h3>
                    <p style="margin: 0;">
                        {!! $textoEmpaqueEntrega !!}
                    </p>
                </td>
                @endif

                <td style="border-left: 4px solid #1F5078; padding: 12px 12px 12px 10px; border-radius: 4px; background: #fff; font-size: 9pt; vertical-align: top; width: 48%;">
                    <h3 style="margin-top: 0; margin-bottom: 6px;">Precios y Moneda:</h3>
                    <p style="margin: 0;">
                        Los precios son expresados en pesos mexicanos (MXN) y no incluyen IVA. Son Precios F.O.B. (Free On Board) en México.
                    </p>
                </td>
            </tr>
            <tr>
                <td style="border-left: 4px solid #1F5078; padding: 12px 12px 12px 10px; border-radius: 4px; background: #fff; font-size: 9pt; vertical-align: top;">
                    <h3 style="margin-top: 0; margin-bottom: 6px;">Tiempo de Entrega:</h3>
                    <p style="margin: 0;">
                        El tiempo aproximado de entrega será convenido al momento de la confirmación del pedido, para alinearnos a sus plazos.
                    </p>
                </td>
                <td style="border-left: 4px solid #1F5078; padding: 12px 12px 12px 10px; border-radius: 4px; background: #fff; font-size: 9pt; vertical-align: top;">
                    <h3 style="margin-top: 0; margin-bottom: 6px;">Condiciones de Pago:</h3>
                    <p style="margin: 0;">
                        Se solicita {{ $presupuesto->Condicion }}. Los precios están sujetos a cambio sin previo aviso.
                    </p>
                </td>
            </tr>
            <tr>
                <td style="border-left: 4px solid #1F5078; padding: 12px 12px 12px 10px; border-radius: 4px; background: #fff; font-size: 9pt; vertical-align: top;">
                    <h3 style="margin-top: 0; margin-bottom: 6px;">Variación en Cantidad:</h3>
                    <p style="margin: 0;">
                        En algunos casos, la entrega final podría presentar una variación del 10% más o menos sobre la cantidad solicitada.
                    </p>
                </td>
                <td style="border-left: 4px solid #1F5078; padding: 12px 12px 12px 10px; border-radius: 4px; background: #fff; font-size: 9pt; vertical-align: top;">
                    <h3 style="margin-top: 0; margin-bottom: 6px;">Cargos Adicionales:</h3>
                    <p style="margin: 0;">
                        En caso de incurrir en costos de almacenaje de material, se cobrarán $300 pesos por tarima al mes. Se considera una entrega en un único destino.
                    </p>
                </td>
            </tr>
        </table>

        <p class="mt-2">
            La firma del presente documento representa la aceptación de los precios, materiales y especificaciones técnicas del producto aquí detallados.
        </p>


        <table width="100%" style="margin-top: 30px;">
            <tr>
                <td style="text-align: center; font-size: 9pt;">
                    Atentamente,
                </td>
                <td style="text-align: center; font-size: 9pt;">
                    Firma de aceptación
                </td>
            </tr>
            <tr>
                <td style="text-align: center; font-size: 9pt; padding-top: 50px;">
                    ________________________________
                </td>
                <td style="text-align: center; font-size: 9pt; padding-top: 50px;">
                    ________________________________
                </td>
            </tr>
            <tr>
                <td style="text-align: center; font-size: 9pt; padding-top: 5px;">
                    {{ $presupuesto->vendedor }}
                </td>
                <td style="text-align: center; font-size: 9pt; padding-top: 5px;">
                    {{ $presupuesto->contacto }}
                </td>
            </tr>
            <tr>
                <td style="text-align: center; font-size: 9pt;">
                    Agente de Ventas
                </td>
                <td style="text-align: center; font-size: 9pt;">
                    Cliente
                </td>
            </tr>
        </table>

        <table width="100%" style="margin-top: 40px; border-collapse: collapse;">
            <tr>
                <td style="padding: 5px; text-align: center;">
                    @component('sections/logoLito2') @endcomponent
                </td>
            </tr>
            <tr>
                <td style="padding: 5px; text-align: center; font-size: 9pt; margin-top: 10px;">
                    San Fco. Cuautlalpan 102-A San Francisco, Naucalpan C.P.53569, Estado de México
                </td>
            </tr>
            <tr>
                <td style="text-align: center; font-size: 9pt;">
                    Tel. 55-2122-5600
                </td>
            </tr>
            <tr>
                <td style="text-align: center; font-size: 9pt; padding-top: 5px;">
                    <a href="https://www.litoprocess.com/" target="_blank" style="color: #1F5078; text-decoration: none;">www.litoprocess.com</a>
                </td>
            </tr>
            @php
                $mostrarFSC = false;
                foreach ($presupuestos as $pres) {
                    if ($pres->categoria != null && $pres->subcategoria != null) {
                        $mostrarFSC = true;
                        break;
                    }
                }
            @endphp
            @if ($mostrarFSC)
            <tr>
                <td style="text-align: center; font-size: 9pt; padding-top: 5px;">
                    <span>L-FO-VE-005</span> <br>
                    <span>"Únicamente los productos identificados como tal en este documento están certificados por el FSC®" Rev:01</span>
                </td>
            </tr>
            @endif
        </table>

        {{--  pie de pagina  --}}
        <div style="margin-top: 20px; margin-left: -15px;">
            <div>
                <table width="100%" style="border-collapse: separate; border-spacing: 0px 40px;">
                    <tr>
                        <td style="vertical-align: center; text-align: center;">
                            <img src="img/cert/LogoESR.png" alt="logo litoprocess" style="width: 90px; height: auto;" />
                        </td>
                        {{--  @if($mostrarFSC)  --}}
                        <td style="vertical-align: center; text-align: center;">
                            <img src="img/cert/logoFSCNoBgIcon.png" alt="logo litoprocess" style="width: 50px; height: auto;" />
                        </td>
                        {{--  @endif  --}}
                        <td style="vertical-align: center; text-align: center;">
                            <img src="img/cert/9001_2015.png" alt="logo litoprocess" style="width: 50px; height: auto; margin-top: 2px;" />
                        </td>
                        <td style="vertical-align: center; text-align: center;">
                            <img src="img/cert/14001.png" alt="logo litoprocess" style="width: 50px; height: auto; margin-top: 5px;" />
                        </td>
                        <td style="vertical-align: center; text-align: center;">
                            <img src="img/cert/disney.png" alt="logo litoprocess" style="width: 60px; height: auto; margin-top: 5px;" />
                        </td>
                        <td style="vertical-align: center; text-align: center;">
                            <img src="img/cert/fssc.png" alt="logo litoprocess" style="width: 50px; height: auto;" />
                        </td>
                        <td style="vertical-align: center; text-align: center;">
                            <img src="img/cert/hechoenmex.png" alt="logo litoprocess" style="width: 45px; height: auto; margin-top: 5px;" />
                        </td>
                        <td style="vertical-align: center; text-align: center;">
                            <img src="img/cert/smeta.png" alt="logo litoprocess" style="width: 90px; height: auto;" />
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <footer style="width: 100%; text-align: right; font-size: 5pt; color: #666; margin-top: 20px;">
            {{ $titulo }}
        </footer>
    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $texto = "Pagina {PAGE_NUM} de {PAGE_COUNT}";
            $fuente = $fontMetrics->get_font("Helvetica", "bold");
            $tamano = 9;
            // color negro suave
            $color = [0.5, 0.5, 0.5];

            $anchoTexto = $fontMetrics->get_text_width($texto, $fuente, $tamano);
            $x = $pdf->get_width() - $anchoTexto + 96;
            $y = $pdf->get_height() - 34;

            $pdf->page_text($x, $y, $texto, $fuente, $tamano, $color);
        }
    </script>

</body>
</html>
