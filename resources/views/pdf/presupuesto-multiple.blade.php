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
            margin-top: 10px;
        }

        .-mt-2 {
            margin-top: -10px;
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
                                Presupuesto
                            </h3>
                        </td>
                        <td>
                            <img src="img/Logo_LIto_azul.png" alt="logo litoprocess" style="width: 180px; height: auto; margin-left: 120pt;" />
                        </td>
                    </tr>
                </table>
            </div>

            <div>
                <p class="mt-2">
                    <strong>Estimado(a) {{ $oportunidad->contacto }},</strong>
                </p>

                <p class="mt-2">
                    Por medio de la presente, Litoprocess se complace en presentar el presupuesto solicitado para la producción de <strong>{{ $oportunidad->oportunidad }}</strong>. Nuestro compromiso es ofrecer soluciones de impresión de la más alta calidad, adaptadas a las necesidades específicas de <strong>{{ $oportunidad->cliente }}</strong>
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
                    {{ $oportunidad->cliente }}
                </td>

                <td style="font-weight: bold; padding-bottom: 10px;">
                    {{ $oportunidad->vendedor }}
                </td>
            </tr>

            <tr>
                <td style="font-size: 9pt; line-height: 1.2; width: 50%;">
                    <div style="width: 150pt;">
                        {{ $oportunidad->cliente_direccion }}
                    </div>
                </td>

                <td style="font-size: 9pt; line-height: 1.2; width: 50%; text-align: left;">
                    <div style="width: 150pt;">
                        <div>
                            <img src="img/telefono.png" alt="" width="11" height="11"> Tel: 55-2122-5600 &nbsp; Ext: 133
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

                            $telefono = ($oportunidad->vendedor_celular) ? mascaraTelefono($oportunidad->vendedor_celular) : '';
                        @endphp

                        <div>
                            <img src="img/whatsapp.png" alt="" width="11" height="11"> Cel: {{ $telefono }}
                        </div>
                        <div>
                            Correo: {{ $oportunidad->vendedor_correo }}
                        </div>
                        <div>
                            <strong>Fecha: {{ \Carbon\Carbon::parse($oportunidad->fecha_actual)->format('d-m-Y') }}</strong>
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        <div>
            <table width="100%" style="color: #1F5078; font-weight: bold; margin-top: 20px;" class="text-lg">
                <tr>
                    <td style="color: #1F5078; font-weight: bold;">
                        Especificaciones de elementos múltiples
                    </td>
                </tr>
            </table>
{{--  
            @php
                $headers = $data_excel[0];

                // Eliminamos los headers del array de datos
                $data_excel = array_slice($data_excel, 1);
            @endphp

            <table width="100%" style="margin-top: 10px; border-collapse: collapse;" border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr style="background-color: #1F5078; color: #fff; text-align: center; font-weight: bold;">
                        <th style="width: 20%;">{{ $headers[0] }}</th>
                        <th style="width: 20%;">{{ $headers[1] }}</th>
                        <th style="width: 20%;">{{ $headers[2] }}</th>
                        <th style="width: 20%;">{{ $headers[3] }}</th>
                        <th style="width: 20%;">{{ $headers[4] }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data_excel as $item)
                        <tr style="text-align: center;">
                            <td style="width: 20%;">{{ $item[0] }}</td>
                            <td style="width: 20%;">{{ $item[1] }}</td>
                            <td style="width: 20%;">{{ $item[2] }}</td>
                            <td style="width: 20%;">{{ $item[3] }}</td>
                            <td style="width: 20%;">{{ $item[4] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
--}}

            {{--  Incrustamos la imagen que haya subido  --}}
            <table>
                @foreach ($archivos as $archivo)
                <tr>
                    <td style="text-align: center; padding-top: 10px;">
                        <img src="{{ $archivo }}" alt="Imagen de elementos múltiples" style="width: 100%;" />
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>

    <div class="page-break"></div>

    <div id="page-wrap">
        <h3 class="text-lg" style="color: #1F5078; font-weight: bold; margin-top: 20px;">
            Condiciones Comerciales
        </h3>

        <table width="100%" style="border-collapse: separate; border-spacing: 5px 12px; margin-top: -10px;">
            <tr>
                <td style="border-left: 4px solid #1F5078; padding: 12px 12px 12px 10px; border-radius: 4px; background: #fff; font-size: 9pt; vertical-align: top; width: 48%;">
                    <h3 style="margin-top: 0; margin-bottom: 6px;">Empaque y entrega:</h3>
                    <p style="margin: 0;">
                        El empaque estándar incluye cajas de cartón corrugado y protección interna adecuada para garantizar la integridad del producto durante el transporte.
                    </p>
                </td>

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
                        Solicitamos un {{ $oportunidad->Condicion }}. Los precios están sujetos a cambio sin previo aviso.
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
                    {{ $oportunidad->vendedor }}
                </td>
                <td style="text-align: center; font-size: 9pt; padding-top: 5px;">
                    {{ $oportunidad->contacto }}
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
                    Tel. 55-21-22-56-00
                </td>
            </tr>
            <tr>
                <td style="text-align: center; font-size: 9pt; padding-top: 5px;">
                    <a href="https://www.litoprocess.com/" target="_blank" style="color: #1F5078; text-decoration: none;">https://www.litoprocess.com</a>
                </td>
            </tr>
            <tr>
                <td style="text-align: center; font-size: 9pt; padding-top: 5px;">
                    <span>L-FO-VE-005</span> <br>
                    <span>"Únicamente los productos identificados como tal en este documento están certificados por el FSC®" Rev:01</span>
                </td>
            </tr>
        </table>

        {{--  pie de pagina  --}}
        <div style="margin-top: 20px; margin-left: -15px;">
            <div>
                <table width="100%" style="border-collapse: separate; border-spacing: 0px 40px;">
                    <tr>
                        <td style="vertical-align: center; text-align: center;">
                            <img src="img/cert/LogoESR.png" alt="logo litoprocess" style="width: 90px; height: auto;" />
                        </td>
                        <td style="vertical-align: center; text-align: center;">
                            <img src="img/cert/logoFSCNoBgIcon.png" alt="logo litoprocess" style="width: 40px; height: auto;" />
                        </td>
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

        <footer style="width: 100%; text-align: right; font-size: 5pt; color: #666; margin-top: 40px;">
            {{ $titulo }}
        </footer>
    </div>
</body>
</html>
