<div class="mt-2">
    <div class="keep-together">
        @component('components.elementos-presupuestados.pdf.titulo-general', ['titulo' => 'editorial'])
        @endcomponent
    
        <br>
    
        <div>
            <table style="border-collapse: collapse; width: 100%" border="1" class="tblDetails">
                <tbody>
                    <tr>
                        <td style="font-weight: bold; background-color: #6c6c6c; color: #fff; border-color: #000;" colspan="2">INTERIORES</td>
                        <td style="font-weight: bold; background-color: #6c6c6c; color: #fff; border-color: #000;" colspan="2">FORRO</td>
                        <td style="font-weight: bold; background-color: #6c6c6c; color: #fff; border-color: #000;" colspan="2">GUARDA</td>
                    </tr>
                    
                    <tr>
                        <td style="width: 100px; font-weight: bold;">No. Páginas</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->num_paginas_interiores }}</td>
                        <td style="width: 100px; font-weight: bold;">No. Páginas</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->num_paginas_forro }}</td>
                        <td style="width: 100px; font-weight: bold;">No. Páginas</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->num_paginas_guardas }}</td>
                    </tr>
    
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Sustrato</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->sustrato_interiores }}</td>
                        <td style="width: 100px; font-weight: bold;">Sustrato</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->sustrato_forro }}</td>
                        <td style="width: 100px; font-weight: bold;">Sustrato</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->sustrato_guardas }}</td>
                    </tr>
    
                    {{--  <tr>
                        <td style="width: 100px; font-weight: bold;">Gramaje/Puntos</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->gramaje_interiores }}</td>
                        <td style="width: 100px; font-weight: bold;">Gramaje/Puntos</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->gramaje_forro }}</td>
                        <td style="width: 100px; font-weight: bold;">Gramaje/Puntos</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->gramaje_guardas }}</td>
                    </tr>  --}}
    
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Tintas Frente</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->tinta_frente_interiores }}</td>
                        <td style="width: 100px; font-weight: bold;">Tintas Frente</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->tinta_frente_forro }}</td>
                        <td style="width: 100px; font-weight: bold;">Tintas Frente</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->tinta_frente_guardas }}</td>
                    </tr>
    
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Tintas Vuelta</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->tinta_vuelta_interiores }}</td>
                        <td style="width: 100px; font-weight: bold;">Tintas Vuelta</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->tinta_vuelta_forro }}</td>
                        <td style="width: 100px; font-weight: bold;">Tintas Vuelta</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->tinta_vuelta_guardas }}</td>
                    </tr>
    
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Medida Final (cm)</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->medida_final_interiores }}</td>
                        <td style="width: 100px; font-weight: bold;">Medida Final (cm)</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->medida_final_forro }}</td>
                        <td style="width: 100px; font-weight: bold;">Medida Final (cm)</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->medida_final_guardas }}</td>
                    </tr>
    
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Medida Extendida (cm)</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->medida_extendida_interiores }}</td>
                        <td style="width: 100px; font-weight: bold;">Medida Extendida (cm)</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->medida_extendida_forro }}</td>
                        <td style="width: 100px; font-weight: bold;">Medida Extendida (cm)</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->medida_extendida_guardas }}</td>
                    </tr>
    
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Barniz Frente</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->barniz_frente_interiores }}</td>
                        <td style="width: 100px; font-weight: bold;">Barniz Frente</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->barniz_frente_forro }}</td>
                        <td style="width: 100px; font-weight: bold;">Barniz Frente</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->barniz_frente_guardas }}</td>
                    </tr>
    
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Barniz Vuelta</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->barniz_vuelta_interiores }}</td>
                        <td style="width: 100px; font-weight: bold;">Barniz Vuelta</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->barniz_vuelta_forro }}</td>
                        <td style="width: 100px; font-weight: bold;">Barniz Vuelta</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->barniz_vuelta_guardas }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="keep-together">
        <br>

        @component('components.elementos-presupuestados.pdf.titulo-general', ['titulo' => 'acabados'])
        @endcomponent
    
        <br>
    
        <div>
            <table style="border-collapse: collapse; width: 100%" border="1" class="tblDetails">
                <tbody>
                    <tr>
                        <td style="font-weight: bold; background-color: #6c6c6c; color: #fff; border-color: #000;" colspan="2">INTERIORES</td>
                        <td style="font-weight: bold; background-color: #6c6c6c; color: #fff; border-color: #000;" colspan="2">FORRO</td>
                    </tr>
                    
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Metalizado Frente</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->metalizado_frente_interiores }}</td>
                        <td style="width: 100px; font-weight: bold;">Metalizado Frente</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->metalizado_frente_forro }}</td>
                    </tr>
    
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Metalizado Vuelta</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->metalizado_vuelta_interiores }}</td>
                        <td style="width: 100px; font-weight: bold;">Metalizado Vuelta</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->metalizado_vuelta_forro }}</td>
                    </tr>
    
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Laminado Frente</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->laminado_frente_interiores }}</td>
                        <td style="width: 100px; font-weight: bold;">Laminado Frente</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->laminado_frente_forro }}</td>
                    </tr>
    
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Laminado Vuelta</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->laminado_vuelta_interiores }}</td>
                        <td style="width: 100px; font-weight: bold;">Laminado Vuelta</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->laminado_vuelta_forro }}</td>
                    </tr>
    
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Barniz Sakurai Frente</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->barniz_sakurai_frente_interiores }}</td>
                        <td style="width: 100px; font-weight: bold;">Barniz Sakurai Frente</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->barniz_sakurai_frente_forro }}</td>
                    </tr>
    
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Barniz Sakurai Vuelta</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->barniz_sakurai_vuelta_interiores }}</td>
                        <td style="width: 100px; font-weight: bold;">Barniz Sakurai Vuelta</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->barniz_sakurai_vuelta_forro }}</td>
                    </tr>
    
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Doblez</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->doblez }}</td>
                        <td style="width: 100px; font-weight: bold;">Realce</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->realce_forro }}</td>
                    </tr>
    
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Embolsado/Retractilado</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->embolsado_retractilado }}</td>
                        <td style="width: 100px; font-weight: bold;">Placa de Realce (Medida cm LxA)</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->placa_realce_forro }}</td>
                    </tr>
    
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Perforado</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->perforado }}</td>
                        <td style="width: 100px; font-weight: bold;">Hot Stamping</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->hot_stamping_forro }}</td>
                    </tr>
    
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Suaje</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->suaje_interiores }}</td>
                        <td style="width: 100px; font-weight: bold;">Placa Hot Stamping (Medida cm LxA)</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->placa_hot_stamping_forro }}</td>
                    </tr>
    
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Suajado</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->suajado }}</td>
                        <td style="width: 100px; font-weight: bold;">Color Hot Stamping</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->color_hot_stamping_forro }}</td>
                    </tr>
    
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Otros</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->otros_interiores }}</td>
                        <td style="width: 100px; font-weight: bold;">Suaje</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->suaje_forro }}</td>
                    </tr>
                    
                    
    
                    <tr>
                        <td style="width: 100px; font-weight: bold;"></td>
                        <td style="width: 100px;"></td>
                        <td style="width: 100px; font-weight: bold;">Suajado</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->suajado_forro }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="keep-together">
        <br>

        @component('components.elementos-presupuestados.pdf.titulo-general', ['titulo' => 'adicionales'])
        @endcomponent
    
        <br>
    
        <div>
            <table style="border-collapse: collapse; width: 100%" border="1" class="tblDetails">
                <tbody>
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Encuadernado</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->encuadernado }}</td>
                        <td style="width: 100px; font-weight: bold;">Empaque</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->empaque }}</td>
                    </tr>
    
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Entrega</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->entrega }}</td>
                        <td style="width: 100px; font-weight: bold;">Lugar de entrega</td>
                        <td style="width: 100px;">{{ $dto['solicitud']['solicitudComercial']->lugar_entrega }}</td>
                    </tr>
    
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Observaciones</td>
                        <td style="width: 100px;" colspan="3">{{ $dto['solicitud']['solicitudComercial']->observaciones }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>