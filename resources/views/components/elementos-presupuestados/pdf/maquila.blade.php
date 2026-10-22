<div class="mt-2">
    @component('components.elementos-presupuestados.pdf.titulo-general', ['titulo' => 'Maquila'])
    @endcomponent

    <br>

    <div>
        <table style="border-collapse: collapse; width: 100%" border="1" class="tblDetails">
            <tbody>
                <tr>
                    <td style="width: 100px; font-weight: bold;">Empaque</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudMaquila']->empaque }}</td>
                    <td style="width: 100px; font-weight: bold;">Entrega</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudMaquila']->entrega }}</td>
                </tr>

                <tr>
                    <td style="width: 100px; font-weight: bold;">Lugar de entrega</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudMaquila']->lugar_entrega }}</td>
                    <td style="width: 100px; font-weight: bold;">Suajado</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudMaquila']->suajado }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <br>

    @component('components.elementos-presupuestados.pdf.titulo-general', ['titulo' => 'Adicionales'])
    @endcomponent

    <br>

    <div>
        <table style="border-collapse: collapse; width: 100%" border="1" class="tblDetails">
            <tbody>
                <tr>
                    <td style="width: 100px; font-weight: bold;">Descripción</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudMaquila']->descripcion }}</td>
                    <td style="width: 100px; font-weight: bold;">Observaciones</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudMaquila']->observaciones }}</td>
                </tr>
                <tr>
                    <td style="width: 100px; font-weight: bold;">Instalación</td>
                    <td style="width: 120px;">{{ $dto['solicitud']['solicitudMaquila']->instalacion }}</td>
                    <td></td>
                    <td></td>
                    {{--  <td style="width: 100px; font-weight: bold;">Proveedor sugerido</td>  --}}
                    {{--  <td style="width: 120px;">{{ $dto['solicitud']['solicitudMaquila']->proveedor_sugerido }}</td>  --}}
                </tr>
            </tbody>
        </table>
    </div>
</div>
