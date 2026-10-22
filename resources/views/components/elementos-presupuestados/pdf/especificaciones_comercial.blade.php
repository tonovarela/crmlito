@php
    if (!function_exists('validoComercial')) {
        function validoComercial($valor)
        {
            $texto = str_replace("\xc2\xa0", ' ', (string) $valor);
            $texto = preg_replace('/\s+/u', ' ', $texto);
            $texto = trim($texto);

            return $texto !== '' &&
                $texto !== 'N/A' &&
                $texto !== 'Si' &&
                $texto !== 'Otro (Especificar en Observaciones)' &&
                $texto !== 'Especificar en Observaciones';
        }
    }

    if (!function_exists('itemComercial')) {
        function itemComercial($label, $value)
        {
            if (!in_array($label, [
                'Realce',
                'Placa de Realce (Medida cm LxA)', 
                'Hot Stamping',
                'Placa Hot Stamping (Medida cm LxA)',
            ])) {
                if (!validoComercial($value)) {
                    return null;
                }
            }

            return [
                'label' => $label,
                'value' => $value,
            ];
        }
    }

    if (!function_exists('lineaBreveComercial')) {
        function lineaBreveComercial($label, $value)
        {
            $valor = str_replace("\xc2\xa0", ' ', (string) $value);
            $valor = preg_replace('/\s+/u', ' ', $valor);
            $valor = trim($valor);

            if ($label === 'No. Paginas') {
                return $valor . ' paginas.';
            }

            if ($label === 'Sustrato') {
                return 'En ' . $valor . '.';
            }

            if ($label === 'Frente Impreso a') {
                return 'Frente impreso a ' . $valor . '.';
            }

            if ($label === 'Vuelta Impreso a') {
                return 'Vuelta impreso a ' . $valor . '.';
            }

            if ($label === 'Barniz Frente') {
                return 'Barniz frente ' . $valor . '.';
            }

            if ($label === 'Barniz Vuelta') {
                return 'Barniz vuelta ' . $valor . '.';
            }

            if ($label === 'Doblez') {
                return 'Doblez para un acabado limpio y profesional.';
            }

            if ($label === 'Realce') {
                if ($valor !== '' && $valor !== 'N/A') {
                    return 'Cuenta con realce.';
                }
            }

            if ($label === 'Placa de Realce (Medida cm LxA)') {
                if ($valor !== '' && $valor !== 'N/A') {
                    $valorConCm = (stripos($valor, 'cm') === false) ? trim($valor) . ' cm' : trim($valor);
                    return 'Placa de realce con medida ' . $valorConCm . '.';
                }
            }

            if ($label === 'Hot Stamping') {
                if ($valor !== '' && $valor !== 'N/A') {
                    return 'Cuenta con hot stamping.';
                }
            }

            if ($label === 'Placa Hot Stamping (Medida cm LxA)') {
                if ($valor !== '' && $valor !== 'N/A') {
                    $valorConCm = (stripos($valor, 'cm') === false) ? trim($valor) . ' cm' : trim($valor);
                    return 'Placa de hot stamping con medida ' . $valorConCm . '.';
                }
            }

            if ($label === 'Perforado') {
                if ($valor !== '' && $valor !== 'N/A' && $valor !== 'Si' && $valor !== '0' && $valor !== '0.0' && $valor !== 'Otro (Especificar en Observaciones)' && $valor !== 'Especificar en Observaciones') {
                    return 'Cuenta con perforado';
                }
            }

            if ($label === 'Laminado Frente') {
                return '' . $valor . ' al frente.';
            }

            if ($label === 'Laminado Vuelta') {
                return '' . $valor . ' a la vuelta.';
            }

            if ($label === 'Suajado') {
                return $valor;
            }

            if ($valor !== '' && $valor !== 'N/A' && $valor !== 'Si' && $valor !== '0' && $valor !== '0.0' && $valor !== 'Otro (Especificar en Observaciones)' && $valor !== 'Especificar en Observaciones') {
                return $label . ' ' . $valor . '.';
            } return null;
        }
    }

    if (!function_exists('filtrarItemsComerciales')) {
        function filtrarItemsComerciales(array $items)
        {
            return array_values(array_filter($items, function ($item) {
                $linea = lineaBreveComercial($item['label'], $item['value']);
                return $linea !== null && $linea !== '';
            }));
        }
    }

    /** PREPRENSA */
    $bloques = [];
    $bloques[] = [
        'titulo' => 'Preprensa',
        'contenido' => '<div style="margin-top:5px;">
        Los archivos se procesan con tecnologia CTP.
    </div>',
    ];

    $editorialInteriores = array_values(array_filter([
        itemComercial('No. Paginas', $pres->num_paginas_interiores ?? null),
        itemComercial('Sustrato', $pres->sustrato_interiores ?? null),
        itemComercial('Frente Impreso a', $pres->tinta_frente_interiores ?? null),
        itemComercial('Vuelta Impreso a', $pres->tinta_vuelta_interiores ?? null),
        itemComercial('Barniz Frente', $pres->barniz_frente_interiores ?? null),
        itemComercial('Barniz Vuelta', $pres->barniz_vuelta_interiores ?? null),
    ]));

    $editorialForro = array_values(array_filter([
        itemComercial('No. Paginas', $pres->num_paginas_forro ?? null),
        itemComercial('Sustrato', $pres->sustrato_forro ?? null),
        itemComercial('Frente Impreso a', $pres->tinta_frente_forro ?? null),
        itemComercial('Vuelta Impreso a', $pres->tinta_vuelta_forro ?? null),
        itemComercial('Barniz Frente', $pres->barniz_frente_forro ?? null),
        itemComercial('Barniz Vuelta', $pres->barniz_vuelta_forro ?? null),
    ]));

    $editorialGuardas = array_values(array_filter([
        itemComercial('No. Paginas', $pres->num_paginas_guardas ?? null),
        itemComercial('Sustrato', $pres->sustrato_guardas ?? null),
        itemComercial('Frente Impreso a', $pres->tinta_frente_guardas ?? null),
        itemComercial('Vuelta Impreso a', $pres->tinta_vuelta_guardas ?? null),
        itemComercial('Barniz Frente', $pres->barniz_frente_guardas ?? null),
        itemComercial('Barniz Vuelta', $pres->barniz_vuelta_guardas ?? null),
    ]));

    $acabadosInteriores = array_values(array_filter([
        itemComercial('Metalizado Frente', $pres->metalizado_frente_interiores ?? null),
        itemComercial('Metalizado Vuelta', $pres->metalizado_vuelta_interiores ?? null),
        itemComercial('Laminado Frente', $pres->laminado_frente_interiores ?? null),
        itemComercial('Laminado Vuelta', $pres->laminado_vuelta_interiores ?? null),
        itemComercial('Barniz Sakurai Frente', $pres->barniz_sakurai_frente_interiores ?? null),
        itemComercial('Barniz Sakurai Vuelta', $pres->barniz_sakurai_vuelta_interiores ?? null),
        itemComercial('Embolsado/Retractilado', $pres->embolsado_retractilado ?? null),
        itemComercial('Doblez', $pres->doblez ?? null),
        itemComercial('Perforado', $pres->perforado ?? null),
        itemComercial('Suajado', $pres->suajado ?? null),
        itemComercial('Encuadernado', $pres->encuadernado ?? null),
        itemComercial('Otros', $pres->otros_interiores ?? null),
    ]));

    $acabadosForro = array_values(array_filter([
        itemComercial('Metalizado Frente', $pres->metalizado_frente_forro ?? null),
        itemComercial('Metalizado Vuelta', $pres->metalizado_vuelta_forro ?? null),
        itemComercial('Laminado Frente', $pres->laminado_frente_forro ?? null),
        itemComercial('Laminado Vuelta', $pres->laminado_vuelta_forro ?? null),
        itemComercial('Barniz Sakurai Frente', $pres->barniz_sakurai_frente_forro ?? null),
        itemComercial('Barniz Sakurai Vuelta', $pres->barniz_sakurai_vuelta_forro ?? null),
        itemComercial('Realce', $pres->realce_forro ?? null),
        itemComercial('Placa de Realce (Medida cm LxA)', $pres->placa_realce_forro ?? null),
        itemComercial('Hot Stamping', $pres->hot_stamping_forro ?? null),
        itemComercial('Placa Hot Stamping (Medida cm LxA)', $pres->placa_hot_stamping_forro ?? null),
        itemComercial('Color Hot Stamping', $pres->color_hot_stamping_forro ?? null),
        itemComercial('Suajado', $pres->suajado_forro ?? null),
    ]));

    $editorialInteriores = filtrarItemsComerciales($editorialInteriores);
    $editorialForro = filtrarItemsComerciales($editorialForro);
    $editorialGuardas = filtrarItemsComerciales($editorialGuardas);
    $acabadosInteriores = filtrarItemsComerciales($acabadosInteriores);
    $acabadosForro = filtrarItemsComerciales($acabadosForro);

    $maxEditorialRows = max(count($editorialInteriores), count($editorialForro), count($editorialGuardas));
    $maxAcabadosRows = max(count($acabadosInteriores), count($acabadosForro));

    $tableStyle = 'border-collapse: collapse; width: 100%; border: none; table-layout: fixed;';
    $subtitleStyle = 'font-weight: 700; color: #174f84; border: none; padding: 0 0 6px 0; margin: 0 0 2px 0; font-size: 10.3pt; letter-spacing: 0; text-align: left; line-height: 1.1; page-break-after: avoid;';
    $labelCellStyle = 'font-weight: 500; border: none; padding: 0 4px 1px 0; vertical-align: top; line-height: 1.14; font-size: 9.5pt; color: #1f2933;';
    $valueCellStyle = 'border: none; padding: 0 1px 1px 0; vertical-align: top; line-height: 1.14; font-size: 9.5pt; color: #1f2933;';
    $listStyle = 'margin: 0; padding-left: 11px; list-style-type: disc; list-style-position: outside;';
    $groupGapLeft = 'padding-left: 10px;';
    $groupGapRight = 'padding-right: 10px;';
    $tableCardStyle = 'border: none; padding: 0 8px 2px 0; border-radius: 0; background: transparent;';
    $sectionTitleStyle = 'color:#174f84;font-weight:700;font-size:10.5pt;text-align:left;width: 100%; margin: 0 0 9px 0; line-height: 1.1; page-break-after: avoid;';

    $tituloInterioresDinamico = ($pres->tipo_producto ?? '') === 'CAG: Folletos, Catálogos, Libros y Revistas'
        ? 'Interiores'
        : 'Especificaciones tecnicas';

    $especificacionesBloques = [];
    if (count($editorialInteriores) > 0) {
        $especificacionesBloques[] = ['titulo' => $tituloInterioresDinamico, 'items' => $editorialInteriores];
    }
    if (count($editorialForro) > 0) {
        $especificacionesBloques[] = ['titulo' => 'Forro', 'items' => $editorialForro];
    }
    if (count($editorialGuardas) > 0) {
        $especificacionesBloques[] = ['titulo' => 'Guarda', 'items' => $editorialGuardas];
    }
    $especificacionesChunks = array_chunk($especificacionesBloques, 2);

    $acabadosBloques = [];
    if (count($acabadosInteriores) > 0) {
        $acabadosBloques[] = ['titulo' => $tituloInterioresDinamico, 'items' => $acabadosInteriores, 'colA' => '44%', 'colB' => '56%'];
    }
    if (count($acabadosForro) > 0) {
        $acabadosBloques[] = ['titulo' => 'Forro', 'items' => $acabadosForro, 'colA' => '44%', 'colB' => '56%'];
    }
    $acabadosChunks = array_chunk($acabadosBloques, 2);

    // Caso especifico: si ambas secciones tienen un solo bloque, mostrar ambas en una fila.
    $filaUnicaSecciones = count($especificacionesBloques) === 1 && count($acabadosBloques) === 1;
@endphp

<div style="color: #1f2933; padding: 0; margin-top: 10px;">
    <div style="margin-bottom: 6px;">
        <p style="{{ $sectionTitleStyle }}">
            {{ ucfirst(strtolower($bloques[0]['titulo'])) }}:
        </p>
        <div style="font-size: 9.5pt; line-height: 1.14; margin: 0; padding: 0;">{!! $bloques[0]['contenido'] !!}</div>
    </div>

    @if ($filaUnicaSecciones)
        <div style="margin-top: 12px; width: 100%; font-size: 0; page-break-inside: avoid; break-inside: avoid-page;">
            <div style="display: inline-block; width: 49%; vertical-align: top; padding-right: 1%; font-size: 9.5pt;">
                <p style="{{ $sectionTitleStyle }}">Información General:</p>
                <div style="{{ $subtitleStyle }}">{{ $especificacionesBloques[0]['titulo'] }}</div>
                <table style="{{ $tableStyle }}" class="tblDetails">
                    <colgroup>
                        <col style="width: 100%;">
                    </colgroup>
                    <tbody>
                        @php
                            $itemsEspecificaciones = $especificacionesBloques[0]['items'] ?? [];
                        @endphp
                        @foreach ($itemsEspecificaciones as $item)
                            <tr>
                                <td style="{{ $labelCellStyle }} color: #1f2933;">
                                    <ul style="{{ $listStyle }}">
                                        <li style="margin: 0;">{{ lineaBreveComercial($item['label'], $item['value']) }}</li>
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="display: inline-block; width: 49%; vertical-align: top; font-size: 9.3pt;">
                <p style="{{ $sectionTitleStyle }}">Acabados generales:</p>
                <div style="{{ $subtitleStyle }}">{{ $acabadosBloques[0]['titulo'] }}</div>
                <table style="{{ $tableStyle }}" class="tblDetails">
                    <colgroup>
                        <col style="width: 100%;">
                    </colgroup>
                    <tbody>
                        @php
                            $itemsAcabados = $acabadosBloques[0]['items'] ?? [];
                        @endphp
                        @foreach ($itemsAcabados as $item)
                            <tr>
                                <td style="{{ $labelCellStyle }} color: #1f2933;">
                                    <ul style="{{ $listStyle }}">
                                        <li style="margin: 0;">{{ lineaBreveComercial($item['label'], $item['value']) }}</li>
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div style="margin-top: 12px;">
            <p style="{{ $sectionTitleStyle }}">
                Información General:
            </p>

            @if (count($especificacionesBloques) > 0)
                @php
                    $totalEspecificaciones = count($especificacionesBloques);
                    $anchoEspecificacion = '100%';

                    if ($totalEspecificaciones === 2) {
                        $anchoEspecificacion = '49%';
                    } elseif ($totalEspecificaciones >= 3) {
                        $anchoEspecificacion = '32.3%';
                    }
                @endphp

                <div style="width: 100%; margin-top: 10px; margin-bottom: 10px; font-size: 0; page-break-inside: avoid; break-inside: avoid-page;">
                    @foreach ($especificacionesBloques as $bloque)
                        @php
                            $paddingCol = $loop->last ? '0' : '1%';
                        @endphp
                        <div style="display: inline-block; width: {{ $anchoEspecificacion }}; vertical-align: top; padding-right: {{ $paddingCol }}; font-size: 9.3pt;">
                            <div style="{{ $subtitleStyle }}">{{ $bloque['titulo'] }}</div>
                            <table style="{{ $tableStyle }}" class="tblDetails">
                                <colgroup>
                                    <col style="width: 100%;">
                                </colgroup>
                                <tbody>
                                    @foreach ($bloque['items'] as $item)
                                        <tr>
                                            <td style="{{ $labelCellStyle }} color: #1f2933;">
                                                <ul style="{{ $listStyle }}">
                                                    <li style="margin: 0;">{{ lineaBreveComercial($item['label'], $item['value']) }}</li>
                                                </ul>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div style="margin-top: 14px;">
            <p style="{{ $sectionTitleStyle }} margin-bottom: 10px;">
                Acabados generales:
            </p>

            @if (count($acabadosBloques) > 0)
                @foreach ($acabadosChunks as $chunk)
                    @php
                        $left = $chunk[0];
                        $right = $chunk[1] ?? null;
                    @endphp

                    <div style="width: 100%; margin-top: 10px; margin-bottom: 10px; page-break-inside: avoid; break-inside: avoid-page;">
                        @if ($right)
                            <div style="display: inline-block; width: 49%; vertical-align: top; padding-right: 1%;">
                                <div style="{{ $subtitleStyle }}">{{ $left['titulo'] }}</div>
                                <table style="{{ $tableStyle }}" class="tblDetails">
                                    <colgroup>
                                        <col style="width: {{ $left['colA'] }};">
                                        <col style="width: {{ $left['colB'] }};">
                                    </colgroup>
                                    <tbody>
                                        @foreach ($left['items'] as $item)
                                            <tr>
                                                <td colspan="2" style="{{ $labelCellStyle }} color: #1f2933;">
                                                    <ul style="{{ $listStyle }}">
                                                        <li style="margin: 0;">{{ lineaBreveComercial($item['label'], $item['value']) }}</li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div style="display: inline-block; width: 49%; vertical-align: top;">
                                <div style="{{ $subtitleStyle }}">{{ $right['titulo'] }}</div>
                                <table style="{{ $tableStyle }}" class="tblDetails">
                                    <colgroup>
                                        <col style="width: {{ $right['colA'] }};">
                                        <col style="width: {{ $right['colB'] }};">
                                    </colgroup>
                                    <tbody>
                                        @foreach ($right['items'] as $item)
                                            <tr>
                                                <td colspan="2" style="{{ $labelCellStyle }} color: #1f2933;">
                                                    <ul style="{{ $listStyle }}">
                                                        <li style="margin: 0;">{{ lineaBreveComercial($item['label'], $item['value']) }}</li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div style="width: 100%; vertical-align: top;">
                                <div style="{{ $subtitleStyle }}">{{ $left['titulo'] }}</div>
                                <table style="{{ $tableStyle }}" class="tblDetails">
                                    <colgroup>
                                        <col style="width: 22%;">
                                        <col style="width: 78%;">
                                    </colgroup>
                                    <tbody>
                                        @foreach ($left['items'] as $item)
                                            <tr>
                                                <td colspan="2" style="{{ $labelCellStyle }} color: #1f2933;">
                                                    <ul style="{{ $listStyle }}">
                                                        <li style="margin: 0;">{{ lineaBreveComercial($item['label'], $item['value']) }}</li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>
    @endif
</div>
