@php
    if (!function_exists('validoDisplay')) {
        function validoDisplay($valor)
        {
            return !empty($valor) &&
                $valor !== 'N/A' &&
                $valor !== 'Si' &&
                $valor !== 'Otro (Especificar en Observaciones)' &&
                $valor !== 'Especificar en Observaciones';
        }
    }

    if (!function_exists('itemDisplay')) {
        function itemDisplay($label, $value)
        {
            if (!validoDisplay($value)) {
                return null;
            }

            return [
                'label' => $label,
                'value' => $value,
            ];
        }
    }

    if (!function_exists('lineaBreveDisplay')) {
        function lineaBreveDisplay($label, $value)
        {
            $valor = trim((string) $value);

            if ($label === 'Sustrato') {
                return 'En ' . $valor . '.';
            }

            if ($label === 'Frente Impreso a') {
                return 'Frente impreso a ' . $valor . '.';
            }

            if ($label === 'Empalme') {
                return 'Empalme en ' . $valor . '.';
            }

            if ($label === 'Metalizado frente') {
                return 'Metalizado frente ' . $valor . '.';
            }

            if ($label === 'Metalizado vuelta') {
                return 'Metalizado vuelta ' . $valor . '.';
            }

            if ($label === 'Laminado frente') {
                return '' . $valor . ' al frente.';
            }

            if ($label === 'Laminado vuelta') {
                return '' . $valor . ' a la vuelta.';
            }

            if ($label === 'Barniz Sakurai frente') {
                return 'Barniz frente ' . $valor . '.';
            }

            if ($label === 'Barniz Sakurai vuelta') {
                return 'Barniz vuelta ' . $valor . '.';
            }

            if ($label === 'Suajado') {
                return $valor;
            }

            if ($label === 'Tipo de pegado') {
                return $valor . '.';
            }

            return $label . ' ' . $valor . '.';
        }
    }

    $displayTecnicas = array_values(array_filter([
        itemDisplay('Sustrato', $pres->sustrato ?? null),
        itemDisplay('Frente Impreso a', $pres->tinta_frente ?? null),
        itemDisplay('Empalme', $pres->sustrato_empalme ?? null),
    ]));

    $displayAcabados = array_values(array_filter([
        itemDisplay('Metalizado frente', $pres->metalizado_frente ?? null),
        itemDisplay('Metalizado vuelta', $pres->metalizado_vuelta ?? null),
        itemDisplay('Laminado frente', $pres->laminado_frente ?? null),
        itemDisplay('Laminado vuelta', $pres->laminado_vuelta ?? null),
        itemDisplay('Barniz Sakurai frente', $pres->barniz_sakurai_frente ?? null),
        itemDisplay('Barniz Sakurai vuelta', $pres->barniz_sakurai_vuelta ?? null),
        itemDisplay('Suajado', $pres->suajado ?? null),
        itemDisplay('Tipo de pegado', $pres->tipo_pegue_armado ?? null),
    ]));

    $tableStyle = 'border-collapse: collapse; width: 100%; border: none; table-layout: fixed;';
    $subtitleStyle = 'font-weight: 700; color: #174f84; border: none; padding: 0 0 6px 0; margin: 0 0 2px 0; font-size: 10.3pt; letter-spacing: 0; text-align: left; line-height: 1.1; page-break-after: avoid;';
    $labelCellStyle = 'font-weight: 500; border: none; padding: 0 4px 1px 0; vertical-align: top; line-height: 1.14; font-size: 9.5pt; color: #1f2933;';
    $listStyle = 'margin: 0; padding-left: 11px; list-style-type: disc; list-style-position: outside;';
    $sectionTitleStyle = 'color:#174f84;font-weight:700;font-size:10.5pt;text-align:left;width: 100%; margin: 0 0 9px 0; line-height: 1.1; page-break-after: avoid;';
@endphp

<div style="color: #1f2933; padding: 0; margin-top: 10px;">
    @if (count($displayTecnicas) > 0 && count($displayAcabados) > 0)
        <div style="margin-top: 12px; width: 100%; font-size: 0; page-break-inside: avoid; break-inside: avoid-page;">
            <div style="display: inline-block; width: 49%; vertical-align: top; padding-right: 1%; font-size: 9.5pt;">
                <p style="{{ $sectionTitleStyle }}">Información General:</p>
                <table style="{{ $tableStyle }}" class="tblDetails">
                    <colgroup>
                        <col style="width: 100%;">
                    </colgroup>
                    <tbody>
                        @foreach ($displayTecnicas as $item)
                            <tr>
                                <td style="{{ $labelCellStyle }} color: #1f2933;">
                                    <ul style="{{ $listStyle }}">
                                        <li style="margin: 0;">{{ lineaBreveDisplay($item['label'], $item['value']) }}</li>
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="display: inline-block; width: 49%; vertical-align: top; font-size: 9.5pt;">
                <p style="{{ $sectionTitleStyle }}">Acabados generales:</p>
                <table style="{{ $tableStyle }}" class="tblDetails">
                    <colgroup>
                        <col style="width: 100%;">
                    </colgroup>
                    <tbody>
                        @foreach ($displayAcabados as $item)
                            <tr>
                                <td style="{{ $labelCellStyle }} color: #1f2933;">
                                    <ul style="{{ $listStyle }}">
                                        <li style="margin: 0;">{{ lineaBreveDisplay($item['label'], $item['value']) }}</li>
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        @if (count($displayTecnicas) > 0)
            <div style="margin-top: 12px; margin-bottom: 12px;">
                <p style="{{ $sectionTitleStyle }}">Información General:</p>
                <table style="{{ $tableStyle }}" class="tblDetails">
                    <colgroup>
                        <col style="width: 100%;">
                    </colgroup>
                    <tbody>
                        @foreach ($displayTecnicas as $item)
                            <tr>
                                <td style="{{ $labelCellStyle }} color: #1f2933;">
                                    <ul style="{{ $listStyle }}">
                                        <li style="margin: 0;">{{ lineaBreveDisplay($item['label'], $item['value']) }}</li>
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @if (count($displayAcabados) > 0)
            <div style="margin-top: 12px;">
                <p style="{{ $sectionTitleStyle }}">Acabados generales:</p>
                <table style="{{ $tableStyle }}" class="tblDetails">
                    <colgroup>
                        <col style="width: 100%;">
                    </colgroup>
                    <tbody>
                        @foreach ($displayAcabados as $item)
                            <tr>
                                <td style="{{ $labelCellStyle }} color: #1f2933;">
                                    <ul style="{{ $listStyle }}">
                                        <li style="margin: 0;">{{ lineaBreveDisplay($item['label'], $item['value']) }}</li>
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @endif
</div>
