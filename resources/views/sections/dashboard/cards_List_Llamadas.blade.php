<div class="card" style="width: 100%;">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-phone mr-1"></i>
            Llamadas por Atender
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
        <table class="table compact nowrap tblnombre" id="tblLlamadasXAtender">
            <thead>
                <tr class="bg-gray">
                    <th width="10%">FOLIO</th>
                    <th width="60%">LLAMADA</th>
                    <th width="20%">FECHA</th>
                    <th width="10%">DÍAS</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($my_array as $List_Llamadas)
                    <tr>
                        <td>
                            <span class="handle">
                                <i class="fas fa-ellipsis-v"></i>
                                <i class="fas fa-ellipsis-v"></i>
                            </span>
                            <span class="text">
                                <a title="Editar Llamada"
                                    onclick="editarLlamada({{ $List_Llamadas->idLlamadasReuniones }}, 1, 'Llamadas'), loaderON()"
                                    class="cursor-pointer text-primary text-uppercase">
                                    &nbsp;&nbsp;{{ $List_Llamadas->folio }}</a>
                            </span>
                        </td>
                        <td>
                            <span class="text text-uppercase">
                                <a title="Editar Llamada"
                                    onclick="editarLlamada({{ $List_Llamadas->idLlamadasReuniones }}, 1, 'Llamadas'), loaderON()"
                                    class="cursor-pointer text-primary text-uppercase">
                                    {{ $List_Llamadas->asuntoLugar }} - {{ $List_Llamadas->Situacion }}</a>
                            </span>
                        </td>
                        <td>
                            <span class="text">
                                {{ \Carbon\Carbon::parse($List_Llamadas->Fecha)->format('d/m/Y H:i') }}
                            </span>
                        </td>
                        <td>
                            <span class="text badge badge-warning" style="font-size: 12px">
                                <i class="far fa-clock"></i>
                                &nbsp;&nbsp;&nbsp; {{ $List_Llamadas->Time }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>





{{-- <div>
    <div class="card" style="width: 100%;">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-phone mr-1"></i>
                Llamadas por Atender
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
        <div class="card-body">
            <ul class="todo-list" data-widget="todo-list">
                @foreach ($my_array as $List_Llamadas)
                    <li>
                        <span class="handle">
                            <i class="fas fa-ellipsis-v"></i>
                            <i class="fas fa-ellipsis-v"></i>
                        </span>
                        <span class="text">
                            <a title="Editar Llamada"
                                onclick="editarLlamada({{ $List_Llamadas->idLlamadasReuniones }}, 1, 'Llamadas'), loaderON()" class="cursor-pointer text-red text-uppercase">
                                {{ $List_Llamadas->folio }} - {{ $List_Llamadas->asuntoLugar }} - {{ $List_Llamadas->Situacion }} - {{ \Carbon\Carbon::parse($List_Llamadas->Fecha)->format('d/m/Y H:i') }}</a></span>
                        <small class="badge badge-warning"><i class="far fa-clock"></i> {{ $List_Llamadas->Time }}</small>
                    </li>
                @endforeach

            </ul>
        </div>
    </div>
</div> --}}
