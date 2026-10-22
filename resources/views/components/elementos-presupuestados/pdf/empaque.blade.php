<div class="mt-2">
    @component('components.elementos-presupuestados.pdf.titulo-general', ['titulo' => 'General'])
    @endcomponent

    <br>

    @component('components.elementos-presupuestados.pdf.titulo', ['titulo' => 'Empaque'])
    @endcomponent

    <br>

    <div>
        <table style="border-collapse: collapse; width: 100%" border="1" class="tblDetails">
            <tbody>
                <tr>
                    <td style="width: 100px; font-weight: bold;">Sustrato gr/ptos</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->sustrato }}</td>
                    <td style="width: 100px; font-weight: bold;">Sustrato para Empalme</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->sustrato_empalme }}</td>
                </tr>

                <tr>
                    <td style="width: 100px; font-weight: bold;">Tintas Frente</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->tinta_frente }}</td>
                    <td style="width: 100px; font-weight: bold;">Requiere Ventana</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->requiere_ventana }}</td>
                </tr>

                <tr>
                    <td style="width: 100px; font-weight: bold;">Tintas Vuelta</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->tinta_vuelta }}</td>
                    <td style="width: 100px; font-weight: bold;">Puntaje Ventana</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->puntaje_ventana }}</td>
                </tr>

                <tr>
                    <td style="width: 100px; font-weight: bold;">Medida Final (mm)</td>
                    <td style="width: 120px;">
                        {{ $dto['solicitud']['solicitudEmpaque']->medida_final }}
                        <br>
                        @php
                            $ancho = $dto['solicitud']['solicitudEmpaque']->medida_final_ancho_general;
                            $largo = $dto['solicitud']['solicitudEmpaque']->medida_final_largo_general;
                            $alto  = $dto['solicitud']['solicitudEmpaque']->medida_final_alto_general;
                            $partes = [];
                            if (!empty($ancho) && is_numeric($ancho) && $ancho > 0) $partes[] = 'Anc: ' . $ancho;
                            if (!empty($largo) && is_numeric($largo) && $largo > 0) $partes[] = 'Lar: ' . $largo;
                            if (!empty($alto)  && is_numeric($alto)  && $alto  > 0) $partes[] = 'Alt: ' . $alto;
                        @endphp
                        @if(count($partes) > 0)
                            <small>({{ implode(' x ', $partes) }})</small>
                        @endif
                    </td>
                    <td style="width: 100px; font-weight: bold;">Otros</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->otros_empaque }}</td>
                </tr>

                <tr>
                    <td style="width: 100px; font-weight: bold;">Medida Extendida (mm)</td>
                    <td style="width: 120px;">
                        {{ $dto['solicitud']['solicitudEmpaque']->medida_extendida }}
                        <br>
                        @php
                            $ancho = $dto['solicitud']['solicitudEmpaque']->medida_extendida_ancho_general;
                            $largo = $dto['solicitud']['solicitudEmpaque']->medida_extendida_largo_general;
                            $alto  = $dto['solicitud']['solicitudEmpaque']->medida_extendida_alto_general;
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
                    <td style="width: 100px; font-weight: bold;">Barniz frente</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->barniz_frente }}</td>
                    <td style="width: 100px; font-weight: bold;"></td>
                    <td style="width: 120px;"></td>
                </tr>

                <tr>
                    <td style="width: 100px; font-weight: bold;">Barniz Vuelta</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->barniz_vuelta }}</td>
                    <td style="width: 100px; font-weight: bold;"></td>
                    <td style="width: 120px;"></td>
                </tr>
            </tbody>
        </table>
    </div>

    <br>

    @component('components.elementos-presupuestados.pdf.titulo', ['titulo' => 'Acabados'])
    @endcomponent

    <br>

    <div>
        <table style="border-collapse: collapse; width: 100%" border="1" class="tblDetails">
            <tbody>
                <tr>
                    <td style="width: 100px; font-weight: bold;">Metalizado Frente</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->metalizado_frente }}</td>
                    <td style="width: 100px; font-weight: bold;">Realce</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->realce }}</td>
                </tr>
                <tr>
                    <td style="width: 100px; font-weight: bold;">Metalizado Vuelta</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->metalizado_vuelta }}</td>
                    <td style="width: 100px; font-weight: bold;">Placa de Realce(Medida mm LxA)</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->placa_realce }}</td>
                </tr>
                <tr>
                    <td style="width: 100px; font-weight: bold;">Laminado Frente</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->laminado_frente }}</td>
                    <td style="width: 100px; font-weight: bold;">Hot Stamping</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->hot_stamping }}</td>
                </tr>
                <tr>
                    <td style="width: 100px; font-weight: bold;">Laminado Vuelta</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->laminado_vuelta }}</td>
                    <td style="width: 100px; font-weight: bold;">Placa Hot Stamping (Medida mm LxA)</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->placa_hot_stamping }}</td>
                </tr>
                <tr>
                    <td style="width: 100px; font-weight: bold;">Barniz Sakurai Frente</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->barniz_sakurai_frente }}</td>
                    <td style="width: 100px; font-weight: bold;">Color Hot Stamping</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->color_hot_stamping }}</td>
                </tr>
                <tr>
                    <td style="width: 100px; font-weight: bold;">Barniz Sakurai Vuelta</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->barniz_sakurai_vuelta }}</td>
                    <td style="width: 100px; font-weight: bold;">Suaje</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->suaje }}</td>
                </tr>
                <tr>
                    <td style="width: 100px; font-weight: bold;">Otros</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->otros_acabados }}</td>
                    <td style="width: 100px; font-weight: bold;">Suajado</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->suajado }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    @if ($dto['solicitud']['solicitudEmpaque']->na_tacon_1 == 0)
        <br>

        @component('components.elementos-presupuestados.pdf.titulo-general', ['titulo' => 'Tacon 1'])
        @endcomponent

        <br>
        
        @component('components.elementos-presupuestados.pdf.titulo', ['titulo' => 'Empaque (Tacon 1)'])
        @endcomponent

        <br>

        <div>
            <table style="border-collapse: collapse; width: 100%" border="1" class="tblDetails">
                <tbody>
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Sustrato gr/ptos</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->sustrato_t1 }}</td>
                        <td style="width: 100px; font-weight: bold;">Sustrato para Empalme</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->sustrato_empalme_t1 }}</td>
                    </tr>

                    <tr>
                        <td style="width: 100px; font-weight: bold;">Tintas Frente</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->tinta_frente_t1 }}</td>
                        <td style="width: 100px; font-weight: bold;">Puntaje Ventana</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->puntaje_ventana_t1 }}</td>
                    </tr>

                    <tr>
                        <td style="width: 100px; font-weight: bold;">Tintas Vuelta</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->tinta_vuelta_t1 }}</td>
                        <td style="width: 100px; font-weight: bold;">Requiere Ventana</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->requiere_ventana_t1 }}</td>
                    </tr>

                    <tr><td style="width: 100px; font-weight: bold;">Medida Final (mm)</td>
                        <td style="width: 120px;">
                            {{ $dto['solicitud']['solicitudEmpaque']->medida_final_t1 }}
                            <br>
                            @php
                                $ancho = $dto['solicitud']['solicitudEmpaque']->medida_final_ancho_t1;
                                $largo = $dto['solicitud']['solicitudEmpaque']->medida_final_largo_t1;
                                $alto  = $dto['solicitud']['solicitudEmpaque']->medida_final_alto_t1;
                                $partes = [];
                                if (!empty($ancho) && is_numeric($ancho) && $ancho > 0) $partes[] = 'Anc: ' . $ancho;
                                if (!empty($largo) && is_numeric($largo) && $largo > 0) $partes[] = 'Lar: ' . $largo;
                                if (!empty($alto)  && is_numeric($alto)  && $alto  > 0) $partes[] = 'Alt: ' . $alto;
                            @endphp
                            @if(count($partes) > 0)
                                <small>({{ implode(' x ', $partes) }})</small>
                            @endif
                        </td>
                        <td style="width: 100px; font-weight: bold;">Otros</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->otros_empaque_t1 }}</td>
                    </tr>

                    <tr>
                        <td style="width: 100px; font-weight: bold;">Medida Extendida (mm)</td>
                        <td style="width: 120px;">
                            {{ $dto['solicitud']['solicitudEmpaque']->medida_extendida_t1 }}
                            <br>
                            @php
                                $ancho = $dto['solicitud']['solicitudEmpaque']->medida_extendida_ancho_t1;
                                $largo = $dto['solicitud']['solicitudEmpaque']->medida_extendida_largo_t1;
                                $alto  = $dto['solicitud']['solicitudEmpaque']->medida_extendida_alto_t1;
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
                        <td style="width: 100px; font-weight: bold;">Barniz frente</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->barniz_frente_t1 }}</td>
                        <td style="width: 100px; font-weight: bold;"></td>
                        <td style="width: 120px;"></td>
                    </tr>

                    <tr>
                        <td style="width: 100px; font-weight: bold;">Barniz Vuelta</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->barniz_vuelta_t1 }}</td>
                        <td style="width: 100px; font-weight: bold;"></td>
                        <td style="width: 120px;"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <br>

        @component('components.elementos-presupuestados.pdf.titulo', ['titulo' => 'Acabados (Tacon 1)'])
        @endcomponent

        <div>
            <table style="border-collapse: collapse; width: 100%" border="1" class="tblDetails">
                <tbody>
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Metalizado Frente</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->metalizado_frente_t1 }}</td>
                        <td style="width: 100px; font-weight: bold;">Realce</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->realce_t1 }}</td>
                    </tr>
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Metalizado Vuelta</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->metalizado_vuelta_t1 }}</td>
                        <td style="width: 100px; font-weight: bold;">Placa de Realce(Medida mm LxA)</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->placa_realce_t1 }}</td>
                    </tr>
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Laminado Frente</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->laminado_frente_t1 }}</td>
                        <td style="width: 100px; font-weight: bold;">Hot Stamping</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->hot_stamping_t1 }}</td>
                    </tr>
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Laminado Vuelta</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->laminado_vuelta_t1 }}</td>
                        <td style="width: 100px; font-weight: bold;">Placa Hot Stamping (Medida mm LxA)</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->placa_hot_stamping_t1 }}</td>
                    </tr>
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Barniz Sakurai Frente</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->barniz_sakurai_frente_t1 }}</td>
                        <td style="width: 100px; font-weight: bold;">Color Hot Stamping</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->color_hot_stamping_t1 }}</td>
                    </tr>
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Barniz Sakurai Vuelta</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->barniz_sakurai_vuelta_t1 }}</td>
                        <td style="width: 100px; font-weight: bold;">Suaje</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->suaje_t1 }}</td>
                    </tr>
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Otros</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->otros_acabados_t1 }}</td>
                        <td style="width: 100px; font-weight: bold;">Suajado</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->suajado_t1 }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

    @endif

    @if ($dto['solicitud']['solicitudEmpaque']->na_tacon_2 == 0)
        <br>

        @component('components.elementos-presupuestados.pdf.titulo-general', ['titulo' => 'Tacon 2'])
        @endcomponent

        <br>
        
        @component('components.elementos-presupuestados.pdf.titulo', ['titulo' => 'Empaque (Tacon 2)'])
        @endcomponent

        <br>

        <div>
            <table style="border-collapse: collapse; width: 100%" border="1" class="tblDetails">
                <tbody>
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Sustrato gr/ptos</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->sustrato_t2 }}</td>
                        <td style="width: 100px; font-weight: bold;">Sustrato para Empalme</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->sustrato_empalme_t2 }}</td>
                    </tr>

                    <tr>
                        <td style="width: 100px; font-weight: bold;">Tintas Frente</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->tinta_frente_t2 }}</td>
                        <td style="width: 100px; font-weight: bold;">Puntaje Ventana</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->puntaje_ventana_t2 }}</td>
                    </tr>

                    <tr>
                        <td style="width: 100px; font-weight: bold;">Tintas Vuelta</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->tinta_vuelta_t2 }}</td>
                        <td style="width: 100px; font-weight: bold;">Requiere Ventana</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->requiere_ventana_t2 }}</td>
                    </tr>

                    <tr><td style="width: 100px; font-weight: bold;">Medida Final (mm)</td>
                        <td style="width: 120px;">
                            {{ $dto['solicitud']['solicitudEmpaque']->medida_final_t2 }}
                            <br>
                            @php
                                $ancho = $dto['solicitud']['solicitudEmpaque']->medida_final_ancho_t2;
                                $largo = $dto['solicitud']['solicitudEmpaque']->medida_final_largo_t2;
                                $alto  = $dto['solicitud']['solicitudEmpaque']->medida_final_alto_t2;
                                $partes = [];
                                if (!empty($ancho) && is_numeric($ancho) && $ancho > 0) $partes[] = 'Anc: ' . $ancho;
                                if (!empty($largo) && is_numeric($largo) && $largo > 0) $partes[] = 'Lar: ' . $largo;
                                if (!empty($alto)  && is_numeric($alto)  && $alto  > 0) $partes[] = 'Alt: ' . $alto;
                            @endphp
                            @if(count($partes) > 0)
                                <small>({{ implode(' x ', $partes) }})</small>
                            @endif
                        </td>
                        <td style="width: 100px; font-weight: bold;">Otros</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->otros_empaque_t2 }}</td>
                    </tr>

                    <tr>
                        <td style="width: 100px; font-weight: bold;">Medida Extendida (mm)</td>
                        <td style="width: 120px;">
                            {{ $dto['solicitud']['solicitudEmpaque']->medida_extendida_t2 }}
                            <br>
                            @php
                                $ancho = $dto['solicitud']['solicitudEmpaque']->medida_extendida_ancho_t2;
                                $largo = $dto['solicitud']['solicitudEmpaque']->medida_extendida_largo_t2;
                                $alto  = $dto['solicitud']['solicitudEmpaque']->medida_extendida_alto_t2;
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
                        <td style="width: 100px; font-weight: bold;">Barniz frente</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->barniz_frente_t2 }}</td>
                        <td style="width: 100px; font-weight: bold;"></td>
                        <td style="width: 120px;"></td>
                    </tr>

                    <tr>
                        <td style="width: 100px; font-weight: bold;">Barniz Vuelta</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->barniz_vuelta_t2 }}</td>
                        <td style="width: 100px; font-weight: bold;"></td>
                        <td style="width: 120px;"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <br>

        @component('components.elementos-presupuestados.pdf.titulo', ['titulo' => 'Acabados (Tacon 2)'])
        @endcomponent

        <div>
            <table style="border-collapse: collapse; width: 100%" border="1" class="tblDetails">
                <tbody>
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Metalizado Frente</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->metalizado_frente_t2 }}</td>
                        <td style="width: 100px; font-weight: bold;">Realce</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->realce_t2 }}</td>
                    </tr>
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Metalizado Vuelta</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->metalizado_vuelta_t2 }}</td>
                        <td style="width: 100px; font-weight: bold;">Placa de Realce(Medida mm LxA)</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->placa_realce_t2 }}</td>
                    </tr>
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Laminado Frente</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->laminado_frente_t2 }}</td>
                        <td style="width: 100px; font-weight: bold;">Hot Stamping</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->hot_stamping_t2 }}</td>
                    </tr>
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Laminado Vuelta</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->laminado_vuelta_t2 }}</td>
                        <td style="width: 100px; font-weight: bold;">Placa Hot Stamping (Medida mm LxA)</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->placa_hot_stamping_t2 }}</td>
                    </tr>
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Barniz Sakurai Frente</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->barniz_sakurai_frente_t2 }}</td>
                        <td style="width: 100px; font-weight: bold;">Color Hot Stamping</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->color_hot_stamping_t2 }}</td>
                    </tr>
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Barniz Sakurai Vuelta</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->barniz_sakurai_vuelta_t2 }}</td>
                        <td style="width: 100px; font-weight: bold;">Suaje</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->suaje_t2 }}</td>
                    </tr>
                    <tr>
                        <td style="width: 100px; font-weight: bold;">Otros</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->otros_acabados_t2 }}</td>
                        <td style="width: 100px; font-weight: bold;">Suajado</td>
                        <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->suajado_t2 }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

    @endif

    <br>

    @component('components.elementos-presupuestados.pdf.titulo-general', ['titulo' => 'adicionales'])
    @endcomponent

    <br>

    <div>
        <table style="border-collapse: collapse; width: 100%" border="1" class="tblDetails">
            <tbody>
                <tr>
                    <td style="width: 100px; font-weight: bold;">Tipo de Pegue/Armado</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->tipo_pegue_armado }}</td>
                    <td style="width: 100px; font-weight: bold;">Empaque</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->empaque }}</td>
                </tr>

                <tr>
                    <td style="width: 100px; font-weight: bold;">Entrega</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->entrega }}</td>
                    <td style="width: 100px; font-weight: bold;">Lugar de entrega</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudEmpaque']->lugar_entrega }}</td>
                </tr>

                <tr>
                    <td style="width: 100px; font-weight: bold;">Observaciones</td>
                    <td style="width: 120px;" colspan="3">{{ $dto['solicitud']['solicitudEmpaque']->observaciones }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
