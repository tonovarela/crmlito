<tr class="text-center centerth">
                    <td valign=" top" width="100% " class="border">
                        <table id="table6-materiales" class="centerth">
                            <tbody class="text-center">
                                <tr>
                                    <td colspan="5 " class="titulo "><b>ESPECIFICACIONES TÉCNICAS:</b></td>
                                </tr>
                                <tr class="table-primary ">
                                    <th whidth='200px'>DESCRIPCION</th>
                                    @foreach ($dto->combo_tblmedidas1 as $combo_tblmedidas) @if ($combo_tblmedidas->largoExtendido >0)
                                    <th whidth='200px'>{{ $combo_tblmedidas->nombreInterior }}</th>
                                    @endif @endforeach
                                </tr>
                                <tr class="text-left ">
                                    <th>Largo Extendido (cm)</th>
                                    @foreach ($dto->combo_tblmedidas1 as $combo_tblmedidas) @if ($combo_tblmedidas->largoExtendido >0)
                                    <td class="tdtblCant text-center ">{{ $combo_tblmedidas->nombreInterior }}</th>
                                        @endif @endforeach

                                </tr>
                                <tr class="text-left ">
                                    <th>Ancho Extendido (cm)</th>
                                    @foreach ($dto->combo_tblmedidas1 as $combo_tblmedidas) @if ($combo_tblmedidas->anchoExtendido >0)
                                    <td class="tdtblCant text-center ">{{ $combo_tblmedidas->nombreInterior }}</th>
                                        @endif @endforeach

                                </tr>
                                <tr class="text-left ">
                                    <th>Largo Final (cm)</th>
                                    @foreach ($dto->combo_tblmedidas1 as $combo_tblmedidas) @if ($combo_tblmedidas->largoFinal >0)
                                    <td class="tdtblCant text-center ">{{ $combo_tblmedidas->largoFinal }}</th>
                                        @endif @endforeach

                                </tr>
                                <tr class="text-left ">
                                    <th>Ancho Final (cm)</th>
                                    @foreach ($dto->combo_tblmedidas1 as $combo_tblmedidas) @if ($combo_tblmedidas->anchoFinal >0)
                                    <td class="tdtblCant text-center ">{{ $combo_tblmedidas->anchoFinal }}</th>
                                        @endif @endforeach

                                </tr>

                                <tr class="text-left ">
                                    <th>Tintas Frente</th>
                                    @foreach ($dto->combo_tblmedidas1 as $combo_tblmedidas) @if ($combo_tblmedidas->largoExtendido >0)
                                    <td class="tdtblCant text-center ">{{ $combo_tblmedidas->numTintasF }}</th>
                                        @endif @endforeach
                                </tr>

                                <tr class="text-left ">
                                    <th>Tintas Vuelta</th>
                                    @foreach ($dto->combo_tblmedidas1 as $combo_tblmedidas) @if ($combo_tblmedidas->anchoExtendido >0)
                                    <td class="tdtblCant text-center ">{{ $combo_tblmedidas->numTintasV }}</th>
                                        @endif @endforeach

                                </tr>

                                <tr class="text-left ">
                                    <th>Tipo Papel (Linea)</th>
                                    @foreach ($dto->combo_tblmedidas1 as $combo_tblmedidas) @if ($combo_tblmedidas->largoExtendido >0)
                                    <td class="tdtblCant text-center ">{{ $combo_tblmedidas->NombrePapel }}</th>
                                        @endif @endforeach
                                </tr>

                                <tr class="text-left ">
                                    <th>Id Papel (Linea)</th>
                                    @foreach ($dto->combo_tblmedidas1 as $combo_tblmedidas) @if ($combo_tblmedidas->largoExtendido >0)
                                    <td class="tdtblCant text-center ">{{ $combo_tblmedidas->idTipoPapel }}</th>
                                        @endif @endforeach
                                </tr>

                                <tr class="text-left ">

                                    <th>Papel (Especial)</th>
                                    @foreach ($dto->combo_tblmedidas1 as $combo_tblmedidas) @if ($combo_tblmedidas->PapelEspecial !='')
                                    <td class="tdtblCant text-center ">{{ $combo_tblmedidas->PapelEspecial }}</th>
                                        @endif @endforeach
                                </tr>

                                <tr class="text-left ">
                                    <th>NoPag.</th>
                                    @foreach ($dto->combo_tblmedidas1 as $combo_tblmedidas) @if ($combo_tblmedidas->largoExtendido >0)
                                    <td class="tdtblCant text-center ">{{ $combo_tblmedidas->numPaginas }}</th>
                                        @endif @endforeach
                                </tr>

                                <tr class="text-left ">
                                    <th>Barniz</th>
                                    @foreach ($dto->combo_tblmedidas1 as $combo_tblmedidas) @if ($combo_tblmedidas->largoExtendido >0)
                                    <td class="tdtblCant text-center ">{{ $combo_tblmedidas->Barniz }}</th>
                                        @endif @endforeach
                                </tr>

                                <tr class="text-left ">
                                    <th>Tipo Barniz</th>
                                    @foreach ($dto->combo_tblmedidas1 as $combo_tblmedidas) @if ($combo_tblmedidas->largoExtendido >0)
                                    <td class="tdtblCant text-center ">{{ $combo_tblmedidas->TipoBarniz }}</th>
                                        @endif @endforeach
                                </tr>

                                <tr class="text-left ">
                                    <th>Laminado</th>
                                    @foreach ($dto->combo_tblmedidas1 as $combo_tblmedidas) @if ($combo_tblmedidas->largoExtendido >0)
                                    <td class="tdtblCant text-center ">{{ $combo_tblmedidas->numCarasLaminado }}</th>
                                        @endif @endforeach
                                </tr>

                                <tr class="text-left ">
                                    <th>Tipo Laminado</th>
                                    @foreach ($dto->combo_tblmedidas1 as $combo_tblmedidas) @if ($combo_tblmedidas->largoExtendido >0)
                                    <td class="tdtblCant text-center ">{{ $combo_tblmedidas->descTipoLaminado }}</th>
                                        @endif @endforeach
                                </tr>

                                <tr class="table-secondary ">
                                    <td colspan="3" class="text-center tdtblCant">A C A B A D O S</td>
                                </tr>
                                <tr class="text-left ">
                                    <th>Doblado</th>
                                    @foreach ($dto->combo_tblmedidas2 as $combo_tblmedidas) @if ($combo_tblmedidas->largoExtendido >0)
                                    <td class="tdtblCant text-center ">@if ($combo_tblmedidas->conDoblado !=1) NO @else SI @endif</th>
                                        @endif @endforeach
                                </tr>
                                <tr class="text-left ">
                                    <th>Perforado</th>
                                    @foreach ($dto->combo_tblmedidas2 as $combo_tblmedidas) @if ($combo_tblmedidas->largoExtendido >0)
                                    <td class="tdtblCant text-center ">@if ($combo_tblmedidas->conPerforado !=1) NO @else SI @endif</th>
                                        @endif @endforeach
                                </tr>
                                <tr class="text-left ">
                                    <th>Personalizado</th>
                                    @foreach ($dto->combo_tblmedidas2 as $combo_tblmedidas) @if ($combo_tblmedidas->largoExtendido >0)
                                    <td class="tdtblCant text-center ">@if ($combo_tblmedidas->conPersonalizado !=1) NO @else SI @endif</th>
                                        @endif @endforeach
                                </tr>
                                <tr class="text-left ">
                                    <th>Suajado</th>
                                    @foreach ($dto->combo_tblmedidas2 as $combo_tblmedidas) @if ($combo_tblmedidas->largoExtendido >0)
                                    <td class="tdtblCant text-center ">@if ($combo_tblmedidas->conSuajado !=1) NO @else SI @endif</th>
                                        @endif @endforeach
                                </tr>
                                <tr class="text-left ">
                                    <th>Pegado</th>
                                    @foreach ($dto->combo_tblmedidas2 as $combo_tblmedidas) @if ($combo_tblmedidas->largoExtendido >0)
                                    <td class="tdtblCant text-center ">@if ($combo_tblmedidas->conPegado !=1) NO @else SI @endif</th>
                                        @endif @endforeach
                                </tr>

                                <tr class="text-left ">
                                    <th>Plecado</th>
                                    @foreach ($dto->combo_tblmedidas2 as $combo_tblmedidas) @if ($combo_tblmedidas->largoExtendido >0)
                                    <td class="tdtblCant text-center ">@if ($combo_tblmedidas->conPlecado !=1) NO @else SI @endif</th>
                                        @endif @endforeach
                                </tr>
                                <tr class="text-left ">
                                    <th>Otro</th>
                                    @foreach ($dto->combo_tblmedidas2 as $combo_tblmedidas) @if ($combo_tblmedidas->largoExtendido >0)
                                    <td class="tdtblCant text-center ">{{ $combo_tblmedidas->descOtro }}</th>
                                        @endif @endforeach
                                </tr>
                                <tr class="text-left ">
                                    <th>Grabado</th>
                                    @foreach ($dto->combo_tblmedidas2 as $combo_tblmedidas) @if ($combo_tblmedidas->largoExtendido >0)
                                    <td class="tdtblCant text-center ">@if ($combo_tblmedidas->conGrabado !=1) NO @else SI @endif</th>
                                        @endif @endforeach
                                </tr>

                                <tr class="text-left ">
                                    <th>HotStamping</th>
                                    @foreach ($dto->combo_tblmedidas2 as $combo_tblmedidas) @if ($combo_tblmedidas->largoExtendido >0)
                                    <td class="tdtblCant text-center ">@if ($combo_tblmedidas->conHotStamping !=1) NO @else SI @endif</th>
                                        @endif @endforeach
                                </tr>
                                <tr class="text-left ">
                                    <th>Gatefold</th>
                                    @foreach ($dto->combo_tblmedidas2 as $combo_tblmedidas) @if ($combo_tblmedidas->largoExtendido >0)
                                    <td class="tdtblCant text-center ">{{ $combo_tblmedidas->descTipoGf }}</th>
                                        @endif @endforeach
                                </tr>
                            </tbody>
                        </table>
                        </td>
                </tr>