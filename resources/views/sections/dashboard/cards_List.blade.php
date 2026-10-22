<div class="row col-sm-12">
    <div class="card" style="width: 100%;">
        <div class="card-header">
            <h3 class="card-title">
                <i class="ion ion-clipboard mr-1"></i>
                Oportunidades por Atender
                <strong style="font-weight: bold">
                    &nbsp;&nbsp;&nbsp; ( {{ count($my_array) }} )
                </strong>
            </h3>

            <div class="card-tools">
                <ul class="pagination pagination-sm">
                    <li class="page-item"><a href="#" class="page-link">&laquo;</a></li>
                    <li class="page-item"><a href="#" class="page-link">1</a></li>
                    <li class="page-item"><a href="#" class="page-link">2</a></li>
                    <li class="page-item"><a href="#" class="page-link">3</a></li>
                    <li class="page-item"><a href="#" class="page-link">&raquo;</a></li>
                </ul>
            </div>
        </div>
        <div class="card-body mt-1" style="padding: 0 10px !important; height: 34.7vh;">
            <table class="table compact nowrap tblnombre" id="tblOportXAtender">
                <thead>
                    <tr class="bg-gray">
                        <th width="4%">IND.</th>
                        <th width="10%">FOLIO</th>
                        <th width="60%">OPORTUNIDAD</th>
                        <th width="10%">FECHA TOPE</th>
                        {{-- <th width="10%">AGENTE</th> --}}
                        <th width="10%">ESTATUS</th>
                        {{-- <th hidden width="10%">DÍAS</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($my_array as $List)
                        <tr
                            class="{{ (($List->flagCambioEspecificaciones == 1) || ($List->flagCambioPrecios == 1) || ($List->flagCambioTexto == 1) || ($List->cambiosMinimos > 0)) ? 'bg-cambios-min' : '' }}"
                        >
                            <td>
                                <span class="handle">
                                    @if ($List->flagCambioEspecificaciones == 1)
                                    <i style="border-radius: 50px; background-color: #009d24; color: #fff; padding: 3px; font-size: 8px;">CE</i>
                                    @endif
                                    
                                    @if ($List->flagCambioPrecios == 1)
                                    <i style="border-radius: 50px; background-color: #000d9d; color: #fff; padding: 3px; font-size: 8px;">CP</i>
                                    @endif

                                    @if ($List->flagCambioTexto == 1)
                                    <i style="border-radius: 50px; background-color: #a34800; color: #fff; padding: 3px; font-size: 8px;">CT</i>
                                    @endif

                                    @if ($List->cambiosMinimos > 0)
                                    <i style="border-radius: 50px; background-color: #990b96; color: #fff; padding: 3px; font-size: 8px;">CM</i>
                                    @endif

                                    {{-- Si no hay cambios --}}
                                    @if (
                                        ($List->flagCambioEspecificaciones == null || $List->flagCambioEspecificaciones == 0) &&
                                        ($List->flagCambioPrecios == null || $List->flagCambioPrecios == 0) &&
                                        ($List->flagCambioTexto == null || $List->flagCambioTexto == 0) &&
                                        ($List->cambiosMinimos == null || $List->cambiosMinimos == 0)
                                    )
                                    <i class="fas fa-ellipsis-v"></i>
                                    @endif
                                </span>
                            </td>
                            <td>
                                <a href="oportunidades/{{ $List->idOportunidad }}/edit" title="Editar Oportunidad"
                                    onclick="loaderON()" class="text-primary text-uppercase">
                                    {{ $List->Folio }}
                                </a>
                            </td>
                            <td>
                                <span class="text text-uppercase">
                                    <a href="oportunidades/{{ $List->idOportunidad }}/edit" title="Editar Oportunidad"
                                        onclick="loaderON()" class="text-primary text-uppercase">
                                        {{ $List->nomOportunidad }}
                                    </a>
                                </span>
                            </td>
                            <td>
                                <span class="text">
                                    {{ $List->fecTope }}
                                    {{-- {{ \Carbon\Carbon::parse($List->fecTope)->format('d/m/Y H:i') }} --}}
                                </span>
                            </td>
                            {{-- <td>
                                <span class="text">
                                    {{ $List->Atendido }}
                                </span>
                            </td> --}}
                            <td>
                                <span class="text">
                                    <strong class="text-uppercase">{{ $List->descEstatus }}</strong>
                                    {{-- @if ($List->ST_Oport == 1)
                                        <strong>PRE REGISTRO</strong>
                                    @else
                                        <strong>SOLICITUD COTIZACION</strong>
                                    @endif --}}
                                </span>
                            </td>
                            {{-- <td hidden>
                                <span class="text badge badge-warning" style="font-size: 12px">
                                    <i class="far fa-clock"></i>
                                    &nbsp;&nbsp;&nbsp; {{ $List->Time }}
                                </span>
                            </td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
