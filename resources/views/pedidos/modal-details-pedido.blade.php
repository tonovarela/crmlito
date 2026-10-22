<div class="modal fade" id="mod_DetailsComision">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h4 class="modal-title" style="margin: auto 0; font-size: 1.5rem;">Historico del pedido <span class="dpedido"></span></h4>
            </div>
            <div class="modal-body">
                <div class="px-5 py-2" style="background-color: #F3F3F3; border-radius: 10px;">

                    <div class="row bg-white px-5 py-2" style="border-radius: 10px;">
                        {{--  Datos fijos  --}}
                        <div style="background-color: #17A2B8; border-radius: 10px; margin-right: 1rem; color: #fff;" class="px-3 py-2 col-md-3">
                            <div>
                                <h6 class="mb-1"><strong>Pedido:</strong> <span class="dpedido"></span></h6>
                            </div>

                            <div>
                                <h6 class="mb-1"><strong>Fecha del Pedido:</strong> <span class="dfechaPedido"></span></h6>
                            </div>

                            <div>
                                <h6 class="mb-1"><strong>Cliente:</strong> <span id="dnomCliente"></span></h6>
                            </div>

                            <div>
                                <h6 class="mb-1"><strong>Trabajo:</strong> <span id="dtrabajo"></span></h6>
                            </div>

                            <div>
                                <h6 class="mb-1"><strong>OP del Pedido:</strong> <span id="dnoOp"></span></h6>
                            </div>

                            <div>
                                <h6 class="mb-1"><strong>Importe del Pedido:</strong> <span id="dimportePedido"></span></h6>
                            </div>

                            <div>
                                <h6 class="mb-1"><strong>Importe de la Comisión:</strong> <span id="dimporteComisionFinal"></span></h6>
                            </div>

                            <div>
                                <h6 class="mb-1"><strong>Porcentaje de la Comisión:</strong> <span id="dporcentajeComisionFinal"></span></h6>
                            </div>

                            <div>
                                <h6 class="mb-1"><strong>Porcentaje de retención:</strong> <span id="dRetencionDescripcion"></span></h6>
                            </div>

                            <div>
                                <h6 class="mb-1"><strong>Total de la retención:</strong> <span id="dRetencionFinal"></span></h6>
                            </div>

                            <div>
                                <h6 class="mb-1"><strong>Saldo final:</strong> <span id="dSaldoFinal"></span></h6>
                            </div>
                        </div>

                        {{--  Carrucel de historicos  --}}
                        <div class="flex justify-content-center col-md-8 align-items-center h-100">
                            {{--  <div id="carouselHistory" class="carousel slide" data-ride="carousel">
                                <div id="containerCarouselHistory" class="carousel-inner carousel-size" style="margin: 0 auto; padding: 10px;">
                                </div>
                                <button class="btn btn-secondary carousel-control-prev btn-controls-history" type="button" style="width: 40px; height: 40px; margin: auto;" data-target="#carouselHistory" data-slide="prev">
                                    <span class="carousel-control-prev-icon text-black" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </button>
                                <button class="btn btn-secondary carousel-control-next btn-controls-history" type="button" style="width: 40px; height: 40px; margin: auto;" data-target="#carouselHistory" data-slide="next">
                                    <span class="carousel-control-next-icon text-black" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </button>
                            </div>  --}}
                            <div style="padding: 10px;">
                                <table id="tblHistoricoDetails" class="table table-hover table-striped nowrap compact" style="width: 100% !important;">
                                    <thead>
                                        <tr class="bg-gray">
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer ">
                <div id="spaceToBtnProd"></div>
                <button type="button" class="btn btn-default " data-dismiss="modal" id="dcloseTableWs" onclick="dCloseModalPdd()">Cerrar</button>
            </div>
        </div>
    </div>

    <div class="loanding">
        <div id="circle">
            <div class="loader"></div>
        </div>
    </div>
    
    <style>
        .title-fc {
            padding: 2rem;
        }

        .px-5 {
            padding-left: 2rem !important;
            padding-right: 2rem !important;
        }

        .py-2 {
            padding-top: 2rem !important;
            padding-bottom: 2rem !important;
        }

        .mb-2 {
            margin-bottom: 2rem !important;
        }

        .table-corr th,
        .table-corr td
        {
            padding: 0.25rem;
        }

        .carousel-size {
            width: 90%;
            text-align: center;
            margin: auto;
        }

        .modal-header-custom {
            background-color: #17a2b8;
            color: #ffffff;
            height: 60px;
            display: flex !important;
            flex-direction: column;
        }

        .gap-4 {
            gap: 1rem;
        }

        .mb-1 {
            margin-bottom: 1rem !important;
        }

        .date-title {
            width: 100%;
            background-color: gainsboro;
            padding: 12px;
            border-radius: 0.25rem;
        }

        .py-1 {
            padding-top: 0.25rem !important;
            padding-bottom: 0.25rem !important;
        }

        .px-3 {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }
    </style>
</div>