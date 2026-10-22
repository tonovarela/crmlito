<div class="row col-sm-12">
    <div class="card" style="width: 100%;">
        <div class="card-header">
            <h3 class="card-title">
                <i class="ion ion-clipboard mr-1"></i>
                Oportunidades Sin Archivo Autorizado
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
        <div class="card-body mt-1" style="padding: 0 10px !important; height: 26.5vh;">
            <table class="table compact nowrap tblnombre" id="tblOportVentasXAtender">
                <thead>
                    <tr class="bg-gray">
                        <th width="10%">FOLIO</th>
                        <th width="60%">OPORTUNIDAD</th>
                        <th width="10%">FECHA ENTREGA</th>
                        <th width="10%">ESTATUS</th>
                        <th width="10%">AGENTE</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($my_array as $List_ventas)
                        <tr>
                            <td>
                                <span class="handle">
                                    <i class="fas fa-ellipsis-v"></i>
                                    <i class="fas fa-ellipsis-v"></i>
                                </span>
                                <span class="text">
                                    <a href="oportunidades/ {{ $List_ventas->idOportunidad }}/edit" title="Editar Oportunidad"
                                        onclick="loaderON()" class="text-primary text-uppercase">
                                        &nbsp;&nbsp;&nbsp; {{ $List_ventas->folio }}
                                    </a>
                                </span>
                            </td>
                            <td>
                                <span class="text text-uppercase">
                                    <a href="oportunidades/ {{ $List_ventas->idOportunidad }}/edit" title="Editar Oportunidad"
                                        onclick="loaderON()" class="text-primary text-uppercase">
                                        {{ $List_ventas->nomOportunidad }}
                                    </a>
                                </span>
                            </td>
                            <td>
                                <span class="text">
                                    {{ \Carbon\Carbon::parse($List_ventas->FechaRegistro)->format('d/m/Y') }}
                                </span>
                            </td>
                            <td>
                                <span class="text-uppercase">
                                        <strong class="text-uppercase">{{ $List_ventas->descEstatus }}</strong>
                                </span>
                            </td>
                            <td>
                                <span class="text">
                                        <strong class="text-uppercase">{{ $List_ventas->Nombre }}</strong>
                                </span>
                            </td>
                            {{-- <td>
                                <span class="text badge badge-warning" style="font-size: 12px">
                                    <i class="far fa-clock"></i>
                                    &nbsp;&nbsp;&nbsp; {{ $List_ventas->Time }}
                                </span>
                            </td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
