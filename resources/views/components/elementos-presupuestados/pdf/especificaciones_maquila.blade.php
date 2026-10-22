@php
    if (!function_exists('validoMaquila')) {
        function validoMaquila($valor)
        {
            return !empty($valor) &&
                $valor !== 'N/A' &&
                $valor !== 'Si' &&
                $valor !== 'Otro (Especificar en Observaciones)' &&
                $valor !== 'Especificar en Observaciones';
        }
    }

    if (!function_exists('itemMaquila')) {
        function itemMaquila($label, $value)
        {
            if (!validoMaquila($value)) {
                return null;
            }

            return [
                'label' => $label,
                'value' => $value,
            ];
        }
    }

    if (!function_exists('lineaBreveMaquila')) {
        function lineaBreveMaquila($label, $value)
        {
            $valor = trim((string) $value);

            if ($label === 'Descripcion') {
                return 'Descripcion ' . $valor . '.';
            }

            if ($label === 'Instalacion') {
                return 'Instalacion ' . $valor . '.';
            }

            if ($label === 'Proveedor sugerido') {
                return 'Proveedor sugerido ' . $valor . '.';
            }

            if ($label === 'Observaciones') {
                return 'Observaciones ' . $valor . '.';
            }

            if ($label === 'Suajado') {
                if (strcasecmp($valor, 'Suajado') === 0) {
                    return 'Suajado.';
                }

                return 'Suajado ' . $valor . '.';
            }

            return $label . ' ' . $valor . '.';
        }
    }

    $infoGeneral = array_values(array_filter([
        itemMaquila('Descripcion', $pres->descripcion ?? null),
        itemMaquila('Instalacion', $pres->instalacion ?? null),
        itemMaquila('Proveedor sugerido', $pres->proveedor_sugerido ?? null),
        itemMaquila('Observaciones', $pres->observaciones ?? null),
    ]));

    $infoGeneralColumnSize = max(1, (int) ceil(count($infoGeneral) / 2));
    $infoGeneralColumns = array_chunk($infoGeneral, $infoGeneralColumnSize);

    $acabadosGenerales = array_values(array_filter([
        itemMaquila('Suajado', $pres->suajado ?? null),
    ]));

    $tableStyle = 'border-collapse: collapse; width: 100%; border: none; table-layout: fixed;';
    $subtitleStyle = 'font-weight: 700; color: #174f84; border: none; padding: 0 0 6px 0; margin: 0 0 2px 0; font-size: 10.3pt; letter-spacing: 0; text-align: left; line-height: 1.1; page-break-after: avoid;';
    $labelCellStyle = 'font-weight: 500; border: none; padding: 0 4px 1px 0; vertical-align: top; line-height: 1.14; font-size: 9.5pt; color: #1f2933;';
    $listStyle = 'margin: 0; padding-left: 11px; list-style-type: disc; list-style-position: outside;';
    $sectionTitleStyle = 'color:#174f84;font-weight:700;font-size:10.5pt;text-align:left;width: 100%; margin: 0 0 9px 0; line-height: 1.1; page-break-after: avoid;';

    $acabadosColumnSize = max(1, (int) ceil(count($acabadosGenerales) / 2));
    $acabadosColumns = array_chunk($acabadosGenerales, $acabadosColumnSize);
@endphp

<div style="color: #1f2933; padding: 0; margin-top: 10px; width: 100%; font-size: 0; page-break-inside: avoid; break-inside: avoid-page;">
    @if (count($infoGeneral) > 0 && count($acabadosGenerales) > 0)
        <div style="display: inline-block; width: 50%; vertical-align: top; padding-right: 0.5%; font-size: 9.5pt;">
            <p style="{{ $sectionTitleStyle }}">Información General:</p>
            <div style="width: 100%; font-size: 0;">
                @foreach ($infoGeneralColumns as $col)
                    <div style="display: inline-block; width: 50%; vertical-align: top; padding-right: {{ $loop->last ? '0' : '0.5%' }}; font-size: 9.5pt;">
                        <table style="{{ $tableStyle }}" class="tblDetails">
                            <colgroup>
                                <col style="width: 100%;">
                            </colgroup>
                            <tbody>
                                @foreach ($col as $item)
                                    <tr>
                                        <td style="{{ $labelCellStyle }} color: #1f2933;">
                                            <ul style="{{ $listStyle }}">
                                                <li style="margin: 0;">{{ lineaBreveMaquila($item['label'], $item['value']) }}</li>
                                            </ul>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
            </div>
        </div>

        <div style="display: inline-block; width: 50%; vertical-align: top; font-size: 9.5pt;">
            <p style="{{ $sectionTitleStyle }}">Acabados generales:</p>
            <div style="width: 100%; font-size: 0;">
                @foreach ($acabadosColumns as $col)
                    <div style="display: inline-block; width: 50%; vertical-align: top; padding-right: {{ $loop->last ? '0' : '0.5%' }}; font-size: 9.5pt;">
                        <table style="{{ $tableStyle }}" class="tblDetails">
                            <colgroup>
                                <col style="width: 100%;">
                            </colgroup>
                            <tbody>
                                @foreach ($col as $item)
                                    <tr>
                                        <td style="{{ $labelCellStyle }} color: #1f2933;">
                                            <ul style="{{ $listStyle }}">
                                                <li style="margin: 0;">{{ lineaBreveMaquila($item['label'], $item['value']) }}</li>
                                            </ul>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
            </div>
        </div>
    @elseif (count($infoGeneral) > 0)
        <div style="width: 100%; font-size: 9.5pt;">
            <p style="{{ $sectionTitleStyle }}">Información General:</p>
            <div style="width: 100%; font-size: 0;">
                @foreach ($infoGeneralColumns as $col)
                    <div style="display: inline-block; width: 50%; vertical-align: top; padding-right: {{ $loop->last ? '0' : '0.5%' }}; font-size: 9.5pt;">
                        <table style="{{ $tableStyle }}" class="tblDetails">
                            <colgroup>
                                <col style="width: 100%;">
                            </colgroup>
                            <tbody>
                                @foreach ($col as $item)
                                    <tr>
                                        <td style="{{ $labelCellStyle }} color: #1f2933;">
                                            <ul style="{{ $listStyle }}">
                                                <li style="margin: 0;">{{ lineaBreveMaquila($item['label'], $item['value']) }}</li>
                                            </ul>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
            </div>
        </div>
    @elseif (count($acabadosGenerales) > 0)
        <div style="width: 100%; font-size: 9.5pt;">
            <p style="{{ $sectionTitleStyle }}">Acabados generales:</p>
            <div style="width: 100%; font-size: 0;">
                @foreach ($acabadosColumns as $col)
                    <div style="display: inline-block; width: 50%; vertical-align: top; padding-right: {{ $loop->last ? '0' : '0.5%' }}; font-size: 9.5pt;">
                        <table style="{{ $tableStyle }}" class="tblDetails">
                            <colgroup>
                                <col style="width: 100%;">
                            </colgroup>
                            <tbody>
                                @foreach ($col as $item)
                                    <tr>
                                        <td style="{{ $labelCellStyle }} color: #1f2933;">
                                            <ul style="{{ $listStyle }}">
                                                <li style="margin: 0;">{{ lineaBreveMaquila($item['label'], $item['value']) }}</li>
                                            </ul>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>