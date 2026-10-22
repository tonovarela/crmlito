@php
    if (!function_exists('lineaBrevePop')) {
        function lineaBrevePop($label, $value)
        {
            $valor = trim((string) $value);

            if ($label === 'Sustrato') {
                return 'En ' . $valor . '.';
            }

            if ($label === 'Gramaje') {
                return 'Gramaje ' . $valor . '.';
            }

            if ($label === 'Tintas Frente') {
                return 'Frente impreso a ' . $valor . '.';
            }

            if ($label === 'Tintas Vuelta') {
                return 'Vuelta impreso a ' . $valor . '.';
            }

            if ($label === 'Medida Final (cm)') {
                return 'Medida final ' . $valor . ' cm.';
            }

            if ($label === 'Medida Extendida (cm)') {
                return 'Medida extendida ' . $valor . ' cm.';
            }

            if ($label === 'Barniz Frente') {
                return 'Barniz frente ' . $valor . '.';
            }

            if ($label === 'Barniz Vuelta') {
                return 'Barniz vuelta ' . $valor . '.';
            }

            if ($label === 'No. de Cambios') {
                return $valor . ' cambios.';
            }

            if ($label === 'Trazo Cliente') {
                return 'Trazo cliente ' . $valor . '.';
            }

            if ($label === 'Trazo Estructural') {
                return 'Trazo estructural ' . $valor . '.';
            }

            if ($label === 'Sustrato para Empalme') {
                return 'Empalme en ' . $valor . '.';
            }

            if ($label === 'Otros') {
                return 'Otros ' . $valor . '.';
            }

            if ($label === 'Realce') {
                if ($valor === 'Si') {
                    return 'Con realce.';
                }
            }

            if ($label === 'Placa de Realce (Medida cm LxA)') {
                if ($valor != null && $valor !== '' && $valor !== 'Si') {
                    return 'Placa de realce ' . $valor . '.';
                }
            }

            if ($label === 'Hot Stamping') {
                if ($valor === 'Si') {
                    return 'Con Hot Stamping.';
                }
            }

            if ($label === 'Placa Hot Stamping (Medida cm LxA)') {
                if ($valor != null && $valor !== '' && $valor !== 'Si') {
                    return 'Placa Hot Stamping ' . $valor . '.';
                }
            }

            if ($label === 'Color Hot Stamping') {
                return 'Color Hot Stamping ' . $valor . '.';
            }

            if ($label === 'Suaje/Corte') {
                return 'Suaje/Corte ' . $valor . '.';
            }

            if ($label === 'Suajado') {
                if ($label === $valor) {
                    return 'Suajado.';
                }

                return $valor . '.';
            }

            if ($label === 'Termodoblez') {
                if ($valor === 'Si') {
                    return 'Con termodoblez.';
                }
            }

            if ($label === 'Hilo Monofilamento') {
                if ($valor === 'Si') {
                    return 'Con hilo monofilamento.';
                }
            }

            if ($label === 'CM Hilo X Pieza') {
                if ($valor != null && $valor !== '' && $valor !== '0.0' && $valor !== '0' && $valor !== 'Si' && $valor !== 'N/A' && $valor !== 'Otro (Especificar en Observaciones)' && $valor !== 'Especificar en Observaciones') {
                    $valorConCm = (stripos($valor, 'cm') === false) ? $valor . ' cm' : $valor;
                    // Si termina con .0 antes de 'cm', eliminar el .0 para que quede solo el número entero seguido de cm, por ejemplo: 5.0 cm -> 5 cm
                    $valorConCm = preg_replace('/\.0(?= cm$)/', '', $valorConCm);
                    return $valorConCm . ' de hilo por pieza';
                }
            }

            if ($label === 'Cinta doble cara') {
                if ($valor === 'Si') {
                    return 'Con cinta doble cara.';
                }
            }

            if ($label === 'Cantidad cintas x pieza') {
                if ($valor != null && $valor !== '' && $valor !== '0.0' && $valor !== '0' && $valor !== 'Si' && $valor !== 'N/A' && $valor !== 'Otro (Especificar en Observaciones)' && $valor !== 'Especificar en Observaciones') {
                    return 'Cantidad de cintas por pieza ' . $valor . '.';
                }
            }

            if ($label === 'Medida cada cinta') {
                if ($valor != null && $valor !== '' && $valor !== '0.0' && $valor !== '0' && $valor !== 'Si') {
                    $valorConCm = (stripos($valor, 'cm') === false) ? $valor . ' cm' : $valor;
                    $valorConCm = preg_replace('/\.0(?= cm$)/', '', $valorConCm);
                    return 'Medida de cada cinta ' . $valorConCm . '.';
                }
            }

            if (stripos($label, 'Laminado') !== false) {
                return 'Laminado ' . strtolower(str_replace('Laminado ', '', $label)) . ' ' . $valor . '.';
            }

            if (!($valor === 'N/A'
                || $valor === 'Otro (Especificar en Observaciones)'
                || $valor === 'Especificar en Observaciones') && $valor !== '' && $valor !== null && $valor !== '0' && $valor !== '0.0' && $valor !== 'Si' && $valor !== 'N/A' && $valor !== 'Otro (Especificar en Observaciones' && $valor !== 'Especificar en Observaciones') {
                return $label . ' ' . $valor . '.';
            } else {
                return null;
            }
        }
    }

    if (!function_exists('itemPopSimple')) {
        function itemPopSimple($label, $value)
        {
            if (!in_array($label, [
                'Termodoblez', 
                'Hilo Monofilamento',
                'CM Hilo X Pieza',
                'Cinta doble cara',
                'Cantidad cintas x pieza',
                'Medida cada cinta'
            ])) {
                if (!validoPop($value)) {
                    return null;
                }
            }

            return [
                'label' => $label,
                'value' => $value,
            ];
        }
    }

    $infoGeneral = array_values(array_filter([
        itemPopSimple('Sustrato', $pres->sustrato ?? null),
        itemPopSimple('Gramaje', $pres->gramaje ?? null),
        itemPopSimple('Tintas Frente', $pres->tinta_frente ?? null),
        itemPopSimple('Tintas Vuelta', $pres->tinta_vuelta ?? null),
        itemPopSimple('Barniz Frente', $pres->barniz_frente ?? null),
        itemPopSimple('Barniz Vuelta', $pres->barniz_vuelta ?? null),
        // itemPopSimple('No. de Cambios', $pres->num_cambios ?? null),
        itemPopSimple('Trazo Cliente', $pres->trazo_cliente ?? null),
        itemPopSimple('Trazo Estructural', $pres->trazo_estructural ?? null),
        itemPopSimple('Sustrato para Empalme', $pres->sustrato_empalme ?? null),
        itemPopSimple('Otros', $pres->otros ?? null),
    ]));

    $acabadosGenerales = array_values(array_filter([
        itemPopSimple('Metalizado Frente', $pres->metalizado_frente ?? null),
        itemPopSimple('Metalizado Vuelta', $pres->metalizado_vuelta ?? null),
        itemPopSimple('Laminado Frente', $pres->laminado_frente ?? null),
        itemPopSimple('Laminado Vuelta', $pres->laminado_vuelta ?? null),
        itemPopSimple('Barniz Sakurai Frente', $pres->barniz_sakurai_frente ?? null),
        itemPopSimple('Barniz Sakurai Vuelta', $pres->barniz_sakurai_vuelta ?? null),
        itemPopSimple('Realce', $pres->realce ?? null),
        itemPopSimple('Placa de Realce (Medida cm LxA)', $pres->placa_realce ?? null),
        itemPopSimple('Hot Stamping', $pres->hot_stamping ?? null),
        itemPopSimple('Placa Hot Stamping (Medida cm LxA)', $pres->placa_hot_stamping ?? null),
        itemPopSimple('Color Hot Stamping', $pres->color_hot_stamping ?? null),
        itemPopSimple('Termodoblez', $pres->termodoblez ?? null),
        itemPopSimple('Hilo Monofilamento', $pres->hilo_monofilamento ?? null),
        itemPopSimple('CM Hilo X Pieza', $pres->cm_hilo_x_pieza ?? null),
        itemPopSimple('Cinta doble cara', $pres->cinta_doble_cara ?? null),
        itemPopSimple('Cantidad cintas x pieza', $pres->cantidad_cintas_x_pieza ?? null),
        itemPopSimple('Medida cada cinta', $pres->medida_cada_cinta ?? null),
        itemPopSimple('Suajado', $pres->suajado ?? null),
    ]));

    if (!function_exists('filtrarItemsPop')) {
        function filtrarItemsPop(array $items)
        {
            return array_values(array_filter($items, function ($item) {
                $linea = lineaBrevePop($item['label'], $item['value']);
                return $linea !== null && $linea !== '';
            }));
        }
    }

    $infoGeneral    = filtrarItemsPop($infoGeneral);
    $acabadosGenerales = filtrarItemsPop($acabadosGenerales);

    $tableStyle = 'border-collapse: collapse; width: 100%; border: none; table-layout: fixed;';
    $subtitleStyle = 'font-weight: 700; color: #174f84; border: none; padding: 0 0 6px 0; margin: 0 0 2px 0; font-size: 10.3pt; letter-spacing: 0; text-align: left; line-height: 1.1; page-break-after: avoid;';
    $labelCellStyle = 'font-weight: 500; border: none; padding: 0 4px 1px 0; vertical-align: top; line-height: 1.14; font-size: 9.5pt; color: #1f2933;';
    $listStyle = 'margin: 0; padding-left: 11px; list-style-type: disc; list-style-position: outside;';
    $sectionTitleStyle = 'color:#174f84;font-weight:700;font-size:10.5pt;text-align:left;width: 100%; margin: 0 0 9px 0; line-height: 1.1; page-break-after: avoid;';

    $haInfo     = count($infoGeneral) > 0;
    $haAcabados = count($acabadosGenerales) > 0;
    // Caso especifico: si ambas secciones tienen items, mostrarlas en una fila lado a lado.
    $filaUnicaSecciones = $haInfo && $haAcabados;
@endphp

@if ($haInfo || $haAcabados)
<div style="color: #1f2933; padding: 0; margin-top: 10px; width: 100%; font-size: 0; page-break-inside: avoid; break-inside: avoid-page;">
    @if ($haInfo)
        <div style="display: inline-block; width: {{ $filaUnicaSecciones ? '49%' : '100%' }}; vertical-align: top; {{ $filaUnicaSecciones ? 'padding-right: 1%;' : '' }} font-size: 9.5pt;">
            <p style="{{ $sectionTitleStyle }}">Información General:</p>
            <table style="{{ $tableStyle }}" class="tblDetails">
                <colgroup>
                    <col style="width: 100%;">
                </colgroup>
                <tbody>
                    @foreach ($infoGeneral as $item)
                        <tr>
                            <td style="{{ $labelCellStyle }} color: #1f2933;">
                                <ul style="{{ $listStyle }}">
                                    <li style="margin: 0;">{{ lineaBrevePop($item['label'], $item['value']) }}</li>
                                </ul>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if ($haAcabados)
        <div style="display: inline-block; width: {{ $filaUnicaSecciones ? '49%' : '100%' }}; vertical-align: top; font-size: 9.3pt;">
            <p style="{{ $sectionTitleStyle }}">Acabados generales:</p>
            <table style="{{ $tableStyle }}" class="tblDetails">
                <colgroup>
                    <col style="width: 100%;">
                </colgroup>
                <tbody>
                    @foreach ($acabadosGenerales as $item)
                        <tr>
                            <td style="{{ $labelCellStyle }} color: #1f2933;">
                                <ul style="{{ $listStyle }}">
                                    <li style="margin: 0;">{{ lineaBrevePop($item['label'], $item['value']) }}</li>
                                </ul>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endif
