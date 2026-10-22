<div class="mt-2">
    @component('components.elementos-presupuestados.pdf.titulo-general', ['titulo' => 'POP'])
    @endcomponent

    <br>

    <div>
        <table style="border-collapse: collapse; width: 100%" border="1" class="tblDetails">
            <tbody>
                <tr>
                    <td style="width: 100px; font-weight: bold;">Sustrato gr/ptos</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudPop']->sustrato }}</td>
                    <td style="width: 100px; font-weight: bold;">No. De Cambios</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudPop']->num_cambios }}</td>
                </tr>

                <tr>
                    <td style="width: 100px; font-weight: bold;">Tintas Frente</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudPop']->tinta_frente }}</td>
                    <td style="width: 100px; font-weight: bold;">Trazo Cliente</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudPop']->trazo_cliente }}</td>
                </tr>

                <tr>
                    <td style="width: 100px; font-weight: bold;">Tintas Vuelta</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudPop']->tinta_vuelta }}</td>
                    <td style="width: 100px; font-weight: bold;">Trazo Estructural</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudPop']->trazo_estructural }}</td>
                </tr>

                <tr>
                    <td style="width: 100px; font-weight: bold;">Medida Final (cm)</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudPop']->medida_final }}</td>
                    <td style="width: 100px; font-weight: bold;">Sustrato para Empalme</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudPop']->sustrato_empalme }}</td>
                </tr>

                <tr>
                    <td style="width: 100px; font-weight: bold;">Medida Extendida (cm)</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudPop']->medida_extendida }}</td>
                    <td style="width: 100px; font-weight: bold;">Otros</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudPop']->otros_pop }}</td>
                </tr>

                <tr>
                    <td style="width: 100px; font-weight: bold;">Barniz frente</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudPop']->barniz_frente }}</td>
                    <td style="width: 100px; font-weight: bold;"></td>
                    <td style="width: 120px;"></td>
                </tr>

                <tr>
                    <td style="width: 100px; font-weight: bold;">Barniz vuelta</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudPop']->barniz_vuelta }}</td>
                    <td style="width: 100px; font-weight: bold;"></td>
                    <td style="width: 120px;"></td>
                </tr>
            </tbody>
        </table>
    </div>

    <br>

    @component('components.elementos-presupuestados.pdf.titulo-general', ['titulo' => 'Acabados'])
    @endcomponent

    <br>

    <div>
        <table style="border-collapse: collapse; width: 100%" border="1" class="tblDetails">
            <tbody>
                <tr>
                    <td style="width: 100px; font-weight: bold;">Metalizado Frente</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudPop']->metalizado_frente }}</td>
                    <td style="width: 100px; font-weight: bold;">Suaje/Corte</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudPop']->suaje }}</td>
                </tr>
                <tr>
                    <td style="width: 100px; font-weight: bold;">Metalizado Vuelta</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudPop']->metalizado_vuelta }}</td>
                    <td style="width: 100px; font-weight: bold;">Suajado</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudPop']->suajado }}</td>
                </tr>
                <tr>
                    <td style="width: 100px; font-weight: bold;">Laminado Frente</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudPop']->laminado_frente }}</td>
                    <td style="width: 100px; font-weight: bold;">Termodoblez</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudPop']->termodoblez }}</td>
                </tr>
                <tr>
                    <td style="width: 100px; font-weight: bold;">Laminado Vuelta</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudPop']->laminado_vuelta }}</td>
                    <td style="width: 100px; font-weight: bold;">Hilo Monofilamento</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudPop']->hilo_monofilamento }}</td>
                </tr>
                <tr>
                    <td style="width: 100px; font-weight: bold;">Barniz Sakurai Frente</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudPop']->barniz_sakurai_frente }}</td>
                    <td style="width: 100px; font-weight: bold;">Cm de Hilo x pieza</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudPop']->cm_hilo_x_pieza }}</td>
                </tr>
                <tr>
                    <td style="width: 100px; font-weight: bold;">Barniz Sakurai Vuelta</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudPop']->barniz_sakurai_vuelta }}</td>
                    <td style="width: 100px; font-weight: bold;">Cinta Doble Cara</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudPop']->cinta_doble_cara }}</td>
                </tr>
                <tr>
                    <td style="width: 100px; font-weight: bold;">Realce</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudPop']->realce }}</td>
                    <td style="width: 100px; font-weight: bold;">Medida de cada cinta (cm)</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudPop']->medida_cada_cinta }}</td>
                </tr>
                <tr>
                    <td style="width: 100px; font-weight: bold;">Placa de Realce(Medida cm LxA)</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudPop']->placa_realce }}</td>
                    <td style="width: 100px; font-weight: bold;">Cantidad de cintas x pieza</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudPop']->cantidad_cintas_x_pieza }}</td>
                </tr>
                <tr>
                    <td style="width: 100px; font-weight: bold;">Hot Stamping</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudPop']->hot_stamping }}</td>
                    <td style="width: 100px; font-weight: bold;">Otros</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudPop']->otros_acabados }}</td>
                </tr>
                <tr>
                    <td style="width: 100px; font-weight: bold;">Placa Hot Stamping (Medida cm LxA)</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudPop']->placa_hot_stamping }}</td>
                    <td style="width: 100px; font-weight: bold;"></td>
                    <td style="width: 120px;"></td>
                </tr>
                <tr>
                    <td style="width: 100px; font-weight: bold;">Color Hot Stamping</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudPop']->color_hot_stamping }}</td>
                    <td style="width: 100px; font-weight: bold;"></td>
                    <td style="width: 120px;"></td>
            </tbody>
        </table>
    </div>

    <br>

    @component('components.elementos-presupuestados.pdf.titulo-general', ['titulo' => 'adicionales'])
    @endcomponent

    <br>

    <div>
        <table style="border-collapse: collapse; width: 100%" border="1" class="tblDetails">
            <tbody>
                <tr>
                    <td style="width: 100px; font-weight: bold;">Adicionales</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudPop']->adicional }}</td>
                    <td style="width: 100px; font-weight: bold;">Empaque</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudPop']->empaque }}</td>
                </tr>

                <tr>
                    <td style="width: 100px; font-weight: bold;">Entrega</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudPop']->entrega }}</td>
                    <td style="width: 100px; font-weight: bold;">Lugar de entrega</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudPop']->lugar_entrega }}</td>
                </tr>

                <tr>
                    <td style="width: 100px; font-weight: bold;">Observaciones</td>
                    <td style="width: 120px;" colspan="3">{{ $dto['solicitud']['solicitudPop']->observaciones }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>