<div>
    <h2><strong> Hola {{ ucfirst(strtolower(trans(strtok($nombre, " ")))) }}, hoy {{ now()->format('d-m-Y') }} tienes
            @if (count($reuniones) == 1)
                {{ count($reuniones) }} reunión.
            @else
                {{ count($reuniones) }} reuniones.
            @endif
        </strong>
    </h2>

    @if (count($reuniones) > 0)
        <table>
            <tr style="background-color: #343A40; color: #fff; font-stretch: bold; border: #000 solid 1px">
                <th class="text-left">Hora</th>
                <th class="text-left">Descripción</th>
                <th class="text-left">Cliente</th>
                <th class="text-left">Lugar</th>
            </tr>
            <tbody>
                @forelse ($reuniones as $reunion)
                    <tr>
                        <td style="border: solid 1px #000">
                            &nbsp;&nbsp;{{ \Carbon\Carbon::parse($reunion->fecInicio)->format('H:i') }} HRS.
                            &nbsp;&nbsp;</td>
                        <td style="border: solid 1px #000"> {{ $reunion->descripcion }} &nbsp;&nbsp;</td>
                        <td style="border: solid 1px #000"> {{ $reunion->razonsocial }} &nbsp;&nbsp;</td>
                        <td style="border: solid 1px #000"> {{ $reunion->asuntoLugar }} &nbsp;&nbsp;</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</div>
<p>Este e-mail se ha generado por un sistema automático. Por favor, no respondas a este e-mail directamente.</p>
