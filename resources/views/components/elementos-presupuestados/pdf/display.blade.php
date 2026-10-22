<div class="mt-2">
    <div class="keep-together">
        @component('components.elementos-presupuestados.pdf.titulo-general', ['titulo' => 'Display'])
        @endcomponent
    
        <br>
    
        <div>
            <table style="border-collapse: collapse; width: 100%" border="1" class="tblDetails">
                <tbody>
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Sustrato gr/ptos</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudDisplay']->sustrato }}</td>
                        <td style="width: 100px; font-weight: bold;">Sustrato para Empalme</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudDisplay']->sustrato_empalme }}</td>
                    </tr>
    
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Tintas Frente</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudDisplay']->tinta_frente }}</td>
                        <td style="width: 100px; font-weight: bold;">Otros</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudDisplay']->otros_display }}</td>
                    </tr>
    
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Medida Final (mm)</td>
                        <td style="width: 120px;">
                            {{ $dto['solicitud']['solicitudDisplay']->medida_final }}
                            <br>
                            @php
                                $ancho = $dto['solicitud']['solicitudDisplay']->medida_final_ancho;
                                $largo = $dto['solicitud']['solicitudDisplay']->medida_final_largo;
                                $alto  = $dto['solicitud']['solicitudDisplay']->medida_final_alto;
                                $partes = [];
                                if (!empty($ancho) && is_numeric($ancho) && $ancho > 0) $partes[] = 'Anc: ' . $ancho;
                                if (!empty($largo) && is_numeric($largo) && $largo > 0) $partes[] = 'Lar: ' . $largo;
                                if (!empty($alto)  && is_numeric($alto)  && $alto  > 0) $partes[] = 'Alt: ' . $alto;
                            @endphp
                            @if(count($partes) > 0)
                                <small>({{ implode(' x ', $partes) }})</small>
                            @endif
                        </td>
                        <td style="width: 100px; font-weight: bold;"></td>
                        <td style="width: 120px;"></td>
                    </tr>
    
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Medida Extendida (mm)</td>
                        <td style="width: 120px;">
                            {{ $dto['solicitud']['solicitudDisplay']->medida_extendida }}
                            <br>
                            @php
                                $ancho = $dto['solicitud']['solicitudDisplay']->medida_extendida_ancho;
                                $largo = $dto['solicitud']['solicitudDisplay']->medida_extendida_largo;
                                $alto  = $dto['solicitud']['solicitudDisplay']->medida_extendida_alto;
                                $partes = [];
                                if (!empty($ancho) && is_numeric($ancho) && $ancho > 0) $partes[] = 'Anc: ' . $ancho;
                                if (!empty($largo) && is_numeric($largo) && $largo > 0) $partes[] = 'Lar: ' . $largo;
                                if (!empty($alto)  && is_numeric($alto)  && $alto  > 0) $partes[] = 'Alt: ' . $alto;
                            @endphp
                            @if(count($partes) > 0)
                                <small>({{ implode(' x ', $partes) }})</small>
                            @endif
                        </td>
                        <td style="width: 100px; font-weight: bold;"></td>
                        <td style="width: 120px;"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    
        <br>
    </div>

    <div class="keep-together">
        @component('components.elementos-presupuestados.pdf.titulo-general', ['titulo' => 'Acabados'])
        @endcomponent
    
        <br>
    
        <div>
            <table style="border-collapse: collapse; width: 100%" border="1" class="tblDetails">
                <tbody>
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Metalizado Frente</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudDisplay']->metalizado_frente }}</td>
                        <td style="width: 100px; font-weight: bold;">Otros</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudDisplay']->otros_metalizado_frente }}</td>
                    </tr>
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Metalizado Vuelta</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudDisplay']->metalizado_vuelta }}</td>
                        <td style="width: 100px; font-weight: bold;"></td>
                        <td style="width: 120px;"></td>
                    </tr>
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Laminado Frente</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudDisplay']->laminado_frente }}</td>
                        <td style="width: 100px; font-weight: bold;"></td>
                        <td style="width: 120px;"></td>
                    </tr>
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Laminado Vuelta</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudDisplay']->laminado_vuelta }}</td>
                        <td style="width: 100px; font-weight: bold;"></td>
                        <td style="width: 120px;"></td>
                    </tr>
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Barniz Sakurai Frente</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudDisplay']->barniz_sakurai_frente }}</td>
                        <td style="width: 100px; font-weight: bold;"></td>
                        <td style="width: 120px;"></td>
                    </tr>
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Barniz Sakurai Vuelta</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudDisplay']->barniz_sakurai_vuelta }}</td>
                        <td style="width: 100px; font-weight: bold;"></td>
                        <td style="width: 120px;"></td>
                    </tr>
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Suajado</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudDisplay']->suajado }}</td>
                        <td style="width: 100px; font-weight: bold;"></td>
                        <td style="width: 120px;"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    
        <br>
    </div>

    <div class="keep-together">
        @component('components.elementos-presupuestados.pdf.titulo-general', ['titulo' => 'adicionales'])
        @endcomponent
    
        <br>
    
        <div>
            <table style="border-collapse: collapse; width: 100%" border="1" class="tblDetails">
                <tbody>
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Tipo de Pegue/Armado</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudDisplay']->tipo_pegue_armado }}</td>
                        <td style="width: 100px; font-weight: bold;">Empaque Primario</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudDisplay']->empaque_primario }}</td>
                    </tr>
    
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Empaque Secundario</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudDisplay']->empaque_secundario }}</td>
                        <td style="width: 100px; font-weight: bold;">Lugar de Entrega</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudDisplay']->lugar_entrega }}</td>
                    </tr>
    
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Observaciones</td>
                        <td style="width: 120px;" colspan="3">{{ $dto['solicitud']['solicitudDisplay']->observaciones }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>