@php
    if (!function_exists('validoEmpaque')) {
        function validoEmpaque($valor)
        {
            return !empty($valor) &&
                $valor !== 'N/A' &&
                $valor !== 'Si' &&
                $valor !== 'Otro (Especificar en Observaciones)' &&
                $valor !== 'Especificar en Observaciones';
        }
    }

    if (!function_exists('itemEmpaque')) {
        function itemEmpaque($label, $value)
        {
            if (!validoEmpaque($value)) {
                return null;
            }

            return [
                'label' => $label,
                'value' => $value,
            ];
        }
    }

    if (!function_exists('lineaBreveEmpaque')) {
        function lineaBreveEmpaque($label, $value)
        {
            $valor = trim((string) $value);

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

            if ($label === 'Empalme') {
                return 'Empalme en ' . $valor . '.';
            }

            if ($label === 'Puntaje Ventana') {
                return 'Ventana ' . $valor . '.';
            }

            if ($label === 'Doblez') {
                return 'Doblez para un acabado limpio y profesional.';
            }

            if ($label === 'Laminado Frente') {
                return $valor . ' al frente.';
            }

            if ($label === 'Laminado Vuelta') {
                return $valor . ' a la vuelta.';
            }

            if ($label === 'Tipo de Pegue/Armado') {
                return $valor . '.';
            }

            if ($label === 'Suajado') {
                return $valor;
            }

            return $label . ' ' . $valor . '.';
        }
    }

    $bloques = [];
    $bloques[] = [
        'titulo' => 'Preprensa',
        'contenido' => '<div style="margin-top:5px;">Los archivos se procesan con tecnologia CTP.</div>',
    ];

    $armarEspecificacionesEmpaque = function ($sufijo = '') use ($pres) {
        return array_values(array_filter([
            itemEmpaque('Sustrato', $pres->{"sustrato$sufijo"} ?? null),
            itemEmpaque('Frente Impreso a', $pres->{"tinta_frente$sufijo"} ?? null),
            itemEmpaque('Vuelta Impreso a', $pres->{"tinta_vuelta$sufijo"} ?? null),
            itemEmpaque('Barniz Frente', $pres->{"barniz_frente$sufijo"} ?? null),
            itemEmpaque('Barniz Vuelta', $pres->{"barniz_vuelta$sufijo"} ?? null),
            itemEmpaque('Empalme', $pres->{"sustrato_empalme$sufijo"} ?? null),
            itemEmpaque('Ventana', $pres->{"requiere_ventana$sufijo"} ?? null),
            itemEmpaque('Puntaje Ventana', $pres->{"puntaje_ventana$sufijo"} ?? null),
            itemEmpaque('Otros', $pres->{"otros_empaque$sufijo"} ?? null),
        ]));
    };

    $armarAcabadosEmpaque = function ($sufijo = '') use ($pres) {
        return array_values(array_filter([
            itemEmpaque('Metalizado Frente', $pres->{"metalizado_frente$sufijo"} ?? null),
            itemEmpaque('Metalizado Vuelta', $pres->{"metalizado_vuelta$sufijo"} ?? null),
            itemEmpaque('Laminado Frente', $pres->{"laminado_frente$sufijo"} ?? null),
            itemEmpaque('Laminado Vuelta', $pres->{"laminado_vuelta$sufijo"} ?? null),
            itemEmpaque('Barniz Sakurai Frente', $pres->{"barniz_sakurai_frente$sufijo"} ?? null),
            itemEmpaque('Barniz Sakurai Vuelta', $pres->{"barniz_sakurai_vuelta$sufijo"} ?? null),
            itemEmpaque('Realce', $pres->{"realce$sufijo"} ?? null),
            itemEmpaque('Hot Stamping', $pres->{"hot_stamping$sufijo"} ?? null),
            itemEmpaque('Color Hot Stamping', $pres->{"color_hot_stamping$sufijo"} ?? null),
            itemEmpaque('Suajado', $pres->{"suajado$sufijo"} ?? null),
            (($sufijo === '') ? itemEmpaque('Tipo de Pegue/Armado', $pres->{"tipo_pegue_armado"} ?? null) : null),
        ]));
    };

    $especificacionesBloques = [];
    $acabadosBloques = [];

    $generalEspec = $armarEspecificacionesEmpaque('');
    if (count($generalEspec) > 0) {
        $especificacionesBloques[] = ['titulo' => 'General', 'items' => $generalEspec];
    }

    $generalAcab = $armarAcabadosEmpaque('');
    if (count($generalAcab) > 0) {
        $acabadosBloques[] = ['titulo' => 'General', 'items' => $generalAcab, 'colA' => '100%', 'colB' => '0%'];
    }

    if (($pres->na_tacon_1 ?? 1) == 0) {
        $t1Espec = $armarEspecificacionesEmpaque('_t1');
        if (count($t1Espec) > 0) {
            $especificacionesBloques[] = ['titulo' => 'Tacon 1', 'items' => $t1Espec];
        }

        $t1Acab = $armarAcabadosEmpaque('_t1');
        if (count($t1Acab) > 0) {
            $acabadosBloques[] = ['titulo' => 'Tacon 1', 'items' => $t1Acab, 'colA' => '100%', 'colB' => '0%'];
        }
    }

    if (($pres->na_tacon_2 ?? 1) == 0) {
        $t2Espec = $armarEspecificacionesEmpaque('_t2');
        if (count($t2Espec) > 0) {
            $especificacionesBloques[] = ['titulo' => 'Tacon 2', 'items' => $t2Espec];
        }

        $t2Acab = $armarAcabadosEmpaque('_t2');
        if (count($t2Acab) > 0) {
            $acabadosBloques[] = ['titulo' => 'Tacon 2', 'items' => $t2Acab, 'colA' => '100%', 'colB' => '0%'];
        }
    }

    $tableStyle = 'border-collapse: collapse; width: 100%; border: none; table-layout: fixed;';
    $subtitleStyle = 'font-weight: 700; color: #174f84; border: none; padding: 0 0 6px 0; margin: 0 0 2px 0; font-size: 10.3pt; letter-spacing: 0; text-align: left; line-height: 1.1; page-break-after: avoid;';
    $subtitleAcabadosStyle = $subtitleStyle . ' margin-left: 0; padding-left: 0; text-indent: 0;';
    $labelCellStyle = 'font-weight: 500; border: none; padding: 0 4px 1px 0; vertical-align: top; line-height: 1.14; font-size: 9.5pt; color: #1f2933;';
    $listStyle = 'margin: 0; padding-left: 11px; list-style-type: disc; list-style-position: outside;';
    $sectionTitleStyle = 'color:#174f84;font-weight:700;font-size:10.5pt;text-align:left;width: 100%; margin: 0 0 9px 0; line-height: 1.1; page-break-after: avoid;';

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
                <table style="{{ $tableStyle }}" class="tblDetails">
                    <colgroup>
                        <col style="width: 100%;">
                    </colgroup>
                    <tbody>
                        @foreach ($especificacionesBloques[0]['items'] as $item)
                            <tr>
                                <td style="{{ $labelCellStyle }} color: #1f2933;">
                                    <ul style="{{ $listStyle }}">
                                        <li style="margin: 0;">{{ lineaBreveEmpaque($item['label'], $item['value']) }}</li>
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="display: inline-block; width: 49%; vertical-align: top; font-size: 9.3pt;">
                <p style="{{ $sectionTitleStyle }}">Acabados generales:</p>
                <table style="{{ $tableStyle }}" class="tblDetails">
                    <colgroup>
                        <col style="width: 100%;">
                    </colgroup>
                    <tbody>
                        @foreach ($acabadosBloques[0]['items'] as $item)
                            <tr>
                                <td style="{{ $labelCellStyle }} color: #1f2933;">
                                    <ul style="{{ $listStyle }}">
                                        <li style="margin: 0;">{{ lineaBreveEmpaque($item['label'], $item['value']) }}</li>
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
            <p style="{{ $sectionTitleStyle }}">Información General:</p>

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
                                                    <li style="margin: 0;">{{ lineaBreveEmpaque($item['label'], $item['value']) }}</li>
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
            <p style="{{ $sectionTitleStyle }} margin-bottom: 10px;">Acabados generales:</p>

            @if (count($acabadosBloques) > 0)
                @php
                    $totalAcabados = count($acabadosBloques);
                    $anchoAcabados = '100%';

                    if ($totalAcabados === 2) {
                        $anchoAcabados = '49%';
                    } elseif ($totalAcabados >= 3) {
                        $anchoAcabados = '32.3%';
                    }
                @endphp

                <div style="width: 100%; margin-top: 10px; margin-bottom: 10px; font-size: 0; page-break-inside: avoid; break-inside: avoid-page;">
                    @foreach ($acabadosBloques as $bloque)
                        @php
                            $paddingCol = $loop->last ? '0' : '1%';
                        @endphp
                        <div style="display: inline-block; width: {{ $anchoAcabados }}; vertical-align: top; padding-right: {{ $paddingCol }}; padding-left: 0; font-size: 9.3pt;">
                            <div style="{{ $subtitleAcabadosStyle }}">{{ $bloque['titulo'] }}</div>
                            <table style="{{ $tableStyle }}" class="tblDetails">
                                <colgroup>
                                    <col style="width: 100%;">
                                </colgroup>
                                <tbody>
                                    @foreach ($bloque['items'] as $item)
                                        <tr>
                                            <td style="{{ $labelCellStyle }} color: #1f2933;">
                                                <ul style="{{ $listStyle }}">
                                                    <li style="margin: 0;">{{ lineaBreveEmpaque($item['label'], $item['value']) }}</li>
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
    @endif
</div>
