<div class="modal fade" id="modal-apps-lito" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" class="form-horizontal" data-toggle="validator">
                <div class="modal-body">
                    <input type="hidden" name="IdUsuario" id="idUsuario">
                    <div class="card-body">
                        <div class="rounded bg-gray">
                            <div class="row mb-2 mt-1">
                                <div class="col-sm-12">
                                    <h6 class="titulo text-bold pt-2 pl-1 text-center">Apps Asignadas</h6>
                                </div>
                            </div>
                        </div>
                        <h6 class="nombre text-bold pt-1 pl-1 text-uppercase text-center"></h6>

                        <div class="row m-1" style="border: solid 1px black">

                            <div class="col-2 center" id="panel-cotizador">
                                <div class="text-center pt-1">
                                    <img src="" width="60" />
                                    <span style="color: black">
                                        <h5>Rep. Producción</h5>
                                    </span>
                                </div>
                            </div>
                            <div class="col-2 pt-1" style="border-right: solid 1px black">
                                <div class="custom-switch pb-1"><input type="checkbox" class="custom-control-input"
                                        id="EstatusCotizador"><label class="custom-control-label"
                                        style="cursor: pointer" onclick="activarApp('Cotizador')">Acceso</label></div>

                                <div class="form-group">
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio" id="idCotizadorCrear"
                                            name="TipoUsuarioCotizador" value="1">
                                        <label for="idStatusPre" class="custom-control-label">Crear</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio" id="idCotizadorEditar"
                                            name="TipoUsuarioCotizador" value="2">
                                        <label for="idStatusSolCot" class="custom-control-label">Editar</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio" id="idCotizadorConsultar"
                                            name="TipoUsuarioCotizador" value="3">
                                        <label for="idStatusSolCot" class="custom-control-label">Consultar</label>
                                    </div>
                                </div>

                            </div>

                            <div class="col-2 center" id="panel-accountability">
                                <div class="text-center pt-1">
                                    <img src="" width="60" />
                                    <span style="color: black">
                                        <h5>Accountability</h5>
                                    </span>
                                </div>
                            </div>
                            <div class="col-2 pt-1" style="border-right: solid 1px black">
                                <div class="custom-switch pb-1"><input type="checkbox" class="custom-control-input"
                                        id="EstatusAccountability"><label class="custom-control-label"
                                        style="cursor: pointer" onclick="activarApp('Cotizador')">Acceso</label></div>

                                <div class="form-group">
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio" id="idAccountabilityCrear"
                                            name="TipoUsuarioAccountability" value="1">
                                        <label for="idStatusPre" class="custom-control-label">Crear</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio" id="idAccountabilityEditar"
                                            name="TipoUsuarioAccountability" value="2">
                                        <label for="idStatusSolCot" class="custom-control-label">Editar</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio" id="idAccountabilityConsultar"
                                            name="TipoUsuarioAccountability" value="3">
                                        <label for="idStatusSolCot" class="custom-control-label">Consultar</label>
                                    </div>
                                </div>

                            </div>

                            <div class="col-2 center" id="panel-almacenPT">
                                <div class="text-center pt-1">
                                    <img src="" width="60" />
                                    <span style="color: black">
                                        <h5>Almacén PT</h5>
                                    </span>
                                </div>
                            </div>
                            <div class="col-2 pt-1">
                                <div class="custom-switch pb-1"><input type="checkbox" class="custom-control-input"
                                        id="EstatusAlmacenPT"><label class="custom-control-label"
                                        style="cursor: pointer" onclick="activarApp('Cotizador')">Acceso</label></div>

                                <div class="form-group">
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio" id="idAlmacenPTCrear"
                                            name="TipoUsuarioAlmacenPT" value="1">
                                        <label for="idStatusPre" class="custom-control-label">Crear</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio" id="idAlmacenPTEditar"
                                            name="TipoUsuarioAlmacenPT" value="2">
                                        <label for="idStatusSolCot" class="custom-control-label">Editar</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio" id="idAlmacenPTConsultar"
                                            name="TipoUsuarioAlmacenPT" value="3">
                                        <label for="idStatusSolCot" class="custom-control-label">Consultar</label>
                                    </div>
                                </div>

                            </div>
                            {{-- <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-cotizador"
                                category="panel-informeop">
                                <a href="informeOP/index.php">
                                    <div style="padding: 20px;">
                                        <img src="icons/ordenproduccion.png" width="60" />
                                        <span style="color:#c8752f;">
                                            <h5>Informe OP</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-iDashboards"
                                category="panel-idashboards">
                                <a href="http://192.168.2.217:8080/idashboards/" target="_blank">
                                    <div style="padding: 20px;">
                                        <img src="icons/idashboard.png" width="70" />
                                        <span style="color:#fdb721;">
                                            <h5>iDashboards</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-dbxtra"
                                category="panel-dbxtra">
                                <a href="http://192.168.2.211:8083/DBxtra.NET/LogIn.aspx" target="_blank">
                                    <div style="padding: 20px;">
                                        <img src="icons/dbxtra.png" width="70" />
                                        <span style="color:#ff8204;">
                                            <h5>DBXtra</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-starbucks"
                                category="panel-starbucks">
                                <a href="http://192.168.2.222/starbucks/login?usuario=" target="_blank">
                                    <div style="padding: 20px;">
                                        <img src="icons/starbucks_sinfondo.png" width="70" />
                                        <span style="color:#067655;">
                                            <h5>Starbucks</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-starbucks2"
                                category="panel-starbucks2">
                                <a href="http://192.168.2.222/starbucks2/login?usuario=" target="_blank">
                                    <div style="padding: 20px;">
                                        <img src="icons/starbucks_sinfondo.png" width="70" />
                                        <span style="color:#067655;">
                                            <h5>Starbucks2</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-starbucks3"
                                category="panel-starbucks3">
                                <a href="http://192.168.2.222/starbucks3/login?usuario=" target="_blank">
                                    <div style="padding: 20px;">
                                        <img src="icons/starbucks_sinfondo.png" width="70" />
                                        <span style="color:#067655;">
                                            <h5>Starbucks3</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-kryspykreme"
                                category="panel-kryspykreme">
                                <a href="http://192.168.2.222/kryspykreme/login?usuario=" target="_blank">
                                    <div class="col s12" style="padding: 20px;">
                                        <img src="icons/Krispy_Kreme_logo.png" width="180" />
                                        <span style="color:#c52033;">
                                            <h5>Kryspykreme</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-libera"
                                category="panel-libera">
                                <a href="liberacionPT/index.php">
                                    <div style="padding: 20px;">
                                        <img src="icons/liberacion.png" width="70" />
                                        <span style="color:#006df1;">
                                            <h5>Liberación PT</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-controlm"
                                category="panel-muestras">
                                <a href="muestras/index.php">
                                    <div style="padding: 20px;">
                                        <img src="icons/control_muestras.png" width="70" />
                                        <span style="color:#17eab8;">
                                            <h5>Muestras</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-muestreo"
                                category="panel-revisionbd">
                                <a href="revisionBD/index.php">
                                    <div style="padding: 20px;">
                                        <img src="icons/muestreo_bd.png" width="70" />
                                        <span class="green-text text-darken-4">
                                            <h5>Revision BD</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-acabadom"
                                category="panel-acabadom">
                                <a href="acabadomanual/index.php">
                                    <div style="padding: 20px;">
                                        <img src="icons/acabado_manual.png" width="70" />
                                        <span class="grey-text text-darken-4">
                                            <h5>A.Manual</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-reprocesos"
                                category="panel-reprocesos">
                                <a href="reprocesos/index.php">
                                    <div style="padding: 20px;">
                                        <img src="icons/reprocesos.png" width="70" />
                                        <span style="color:#e75000;">
                                            <h5>Reprocesos</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-capacitacion"
                                category="panel-capacitacion">
                                <a href="capacitacion/index.php">
                                    <div style="padding: 20px;">
                                        <img src="icons/capacitacion.png" width="70" />
                                        <span style="color:#480c8d;">
                                            <h5>Capacitación</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-inventario"
                                category="panel-inventario">
                                <a href="inventarioSistemas/index.php">
                                    <div style="padding: 20px;">
                                        <img src="icons/inventario_sistemas.png" width="70" />
                                        <span class="blue-text text-accent-4">
                                            <h5>Inv.Sistemas</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-inventario"
                                category="panel-inventario2">
                                <a href="http://192.168.2.222/inventariosistemas?usuario=/" target="_blank">
                                    <div style="padding: 20px;">
                                        <img src="icons/informatica.png" width="70" />
                                        <span class="blue-text text-accent-4">
                                            <h5>Inv.Sistemas 2</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-nissan"
                                category="panel-nissan">
                                <a href="nissan/index.php">
                                    <div style="padding: 20px;">
                                        <img src="icons/nissan.png" width="70" />
                                        <span class="black-text">
                                            <h5>Nissan Scan</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-cotizador"
                                category="panel-cotizador">
                                <a href="cotizador/index.php">
                                    <div style="padding: 20px;">
                                        <img src="icons/presupuesto.png" width="70" />
                                        <span class="indigo-text darken-3-text">
                                            <h5>Cotizador</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-mantenimiento"
                                category="panel-mantenimiento">
                                <a href="mantenimientos/index.php">
                                    <div style="padding: 20px;">
                                        <img src="icons/mantenimiento.png" width="70" />
                                        <span style="color: #a49f3c;">
                                            <h5>Mantenimiento</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-requisiciondepersonal"
                                category="panel-requisiciondepersonal">
                                <a href="requisiciondepersonal/index.php">
                                    <div style="padding: 20px;">
                                        <img src="icons/candidatos.png" width="70" />
                                        <span style="color: #40A3C3;">
                                            <h5>Req.Personal</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-mesacontrol"
                                category="panel-mesacontrol">
                                <a href="http://192.168.2.222/mesacontrol/" target="_blank">
                                    <div style="padding: 20px;">
                                        <img src="icons/solucion.png" width="70" />
                                        <span style="color: #1D39AA;">
                                            <h5>Mesa de Control</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-propuestasclientes"
                                category="panel-propuestasclientes">
                                <a href="propuestasclientes/index.php">
                                    <div style="padding: 20px;">
                                        <img src="icons/propuestas (1).png" width="70" />
                                        <span style="color: #F3C21B;">
                                            <h5>Propuestas Clientes</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-quejasclientes"
                                category="panel-propuestasclientes">
                                <a href="quejasclientes/index.php">
                                    <div style="padding: 20px;">
                                        <img src="icons/quejas.png" width="70" />
                                        <span style="color: #AB31E1;">
                                            <h5>Quejas Clientes</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-encuesta"
                                category="panel-climalaboral">
                                <a href="encuesta/index.php">
                                    <div style="padding: 20px;">
                                        <img src="icons/encu2.png" width="70" />
                                        <span style="color: #FF33A2;">
                                            <h5>Clima Laboral</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-accountability"
                                category="panel-accountability">
                                <a href="accountability/index.php">
                                    <div style="padding: 20px;">
                                        <img src="icons/rompecabezas.png" width="70" />
                                        <span style="color: #e25748;">
                                            <h5>Accountability</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-mapas"
                                category="panel-mapas">
                                <a href="mapasdeprocesos/index.php">
                                    <div style="padding: 20px;">
                                        <img src="icons/planificacion.png" width="70" />
                                        <span class="grey-text text-darken-3">
                                            <h5>Mapas de procesos</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-proyectos"
                                category="panel-avanceproyectos">
                                <a href="avancesproyectos/index.php">
                                    <div style="padding: 20px;">
                                        <img src="icons/avance.png" width="70" />
                                        <span class="grey-text text-darken-3">
                                            <h5>Avance Proyectos</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-vw"
                                category="panel-confeccionvw">
                                <a href="vw/index.php">
                                    <div style="padding: 20px;">
                                        <img src="icons/volkswagen.png" width="70" />
                                        <span class="grey-text text-darken-3">
                                            <h5>Confección VW</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-contratos"
                                category="panel-contratos">
                                <a href="contratos/index.php">
                                    <div style="padding: 20px;">
                                        <img src="icons/contrato.png" width="70" />
                                        <span class="grey-text text-darken-3">
                                            <h5>Contratos Lito</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-calidad"
                                category="panel-calidad">
                                <a href="calidad/index.php">
                                    <div style="padding: 20px;">
                                        <img src="icons/calidad.png" width="70" />
                                        <span class="grey-text text-darken-3">
                                            <h5>Calidad</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-calidad"
                                category="panel-crece">
                                <a href="../crece">
                                    <div style="padding: 20px;">
                                        <img src="icons/crece.png" width="70" />
                                        <span class="grey-text text-darken-3">
                                            <h5>CRECE +</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-fichas"
                                category="panel-fichas">
                                <a href="fichastecnicas/index.php">
                                    <div style="padding: 20px;">
                                        <img src="icons/fichatecnica.png" width="70" />
                                        <span class="grey-text text-darken-3">
                                            <h5>Fichas Técnicas</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-calidad"
                                category="panel-juntas">
                                <a href="Juntas/index.php">
                                    <div style="padding: 20px;">
                                        <img src="icons/juntas.png" width="70" />
                                        <span style="color: #293d92;">
                                            <h5>Juntas</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-fichas"
                                category="panel-layoutsbancomer">
                                <a href="pagosSIT/index.php">
                                    <div style="padding: 20px;">
                                        <img src="icons/bancomer.png" width="70" />
                                        <span class="grey-text text-darken-3">
                                            <h5>Layouts Bancomer</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-Fiesta"
                                category="panel-fechasentrega">
                                <a href="FechasEntrega/index.php">
                                    <div style="padding: 20px;">
                                        <img src="icons/TiempoEntrega.png" width="70" />
                                        <span style="color: #f72b2b;">
                                            <h5>Fechas de Entrega</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-Fiesta"
                                category="panel-acabadoproductividad">
                                <a href="ProductividadAM/index.php">
                                    <div style="padding: 20px;">
                                        <img src="icons/productividad.png" width="70" />
                                        <span style="font-size: 12px; color: #325F68;">
                                            <h5>A. Manual: Productividad</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-altaclientes"
                                category="panel-altaclientes">
                                <a href="altaclientes/index.php">
                                    <div style="padding: 20px;">
                                        <img src="icons/nuevocliente.png" width="70" />
                                        <span style="color: #1D39AA;">
                                            <h5>Alta Clientes</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-formatosSGC"
                                category="panel-formatossgc">
                                <a href="formatosSGC/index.php">
                                    <div style="padding: 20px;">
                                        <img src="icons/carpeta.png" width="70" />
                                        <span class="grey-text text-darken-3">
                                            <h5>Formatos SGC</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-Etiquetas"
                                category="panel-etiquetas">
                                <a href="Etiquetas/index.php">
                                    <div style="padding: 20px;">
                                        <img src="icons/label.png" width="70" />
                                        <span style="font-size: 12px; color: #325F68;">
                                            <h5>Etiquetas</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-Fiesta"
                                category="panel-auditorias">
                                <a href="Auditorias/index.php">
                                    <div style="padding: 20px;">
                                        <img src="icons/auditorias.png" width="70" />
                                        <span style="font-size: 12px; color: #325F68;">
                                            <h5>Auditorias</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-dosiers"
                                category="panel-dosiers">
                                <a href="dosiers/index.php">
                                    <div style="padding: 20px;">
                                        <img src="icons/microstrategy.png" width="70" />
                                        <span style="font-size: 12px; color: #325F68;">
                                            <h5>Dosier Ventas</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-almacenPT"
                                category="panel-almacenpt">
                                <a href="almacenPT/index.php">
                                    <div style="padding: 20px;">
                                        <img src="icons/almacen.png" width="70" />
                                        <span class="grey-text text-darken-3">
                                            <h5>AlmacenPT</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-Solunion"
                                category="panel-solunion">
                                <a href="Solunion/index.php">
                                    <div style="padding: 20px;">
                                        <img src="icons/Solunion.png" width="70" />
                                        <span class="grey-text text-darken-3">
                                            <h5>Solunion</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-Solunion"
                                category="panel-portalempleados">
                                <a href="https://servicios.litoprocess.com/portal/empleados/login.php">
                                    <div style="padding: 20px;">
                                        <img src="icons/portal.png" width="70" />
                                        <span class="grey-text text-darken-3">
                                            <h5>Portal Empleados</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-Articulos"
                                category="panel-altaarticulos">
                                <a href="altaintelisisarticulos/index.php">
                                    <div style="padding: 20px;">
                                        <img src="icons/addproduct.png" width="70" />
                                        <span class="grey-text text-darken-3">
                                            <h5>Alta Articulos</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-Articulos"
                                category="panel-estatusentrega">
                                <a href="http://servicios.litoprocess.com/crece/vendedor/">
                                    <div style="padding: 10px;">
                                        <img src="icons/fast_delivery.png" width="80" />
                                        <span class="grey-text text-darken-3">
                                            <h5>Estatus Entrega</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-kfc"
                                category="panel-kfc">
                                <a href="http://192.168.2.222/kfc/login?usuario=" target="_blank">
                                    <div style="padding: 20px;">
                                        <img src="icons/KFC-Logo.png" width="140" />
                                        <span style="text-align:center;" class="grey-text text-darken-3">
                                            <h5>KFC</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-Articulos"
                                category="panel-incidencias">
                                <a href="https://servicios.litoprocess.com/incidencias/index.html">
                                    <div style="padding: 20px;">
                                        <img src="icons/incidencias.png" width="70" />
                                        <span class="grey-text text-darken-3">
                                            <h5>Incidencias</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-Articulos"
                                category="panel-asistencias">
                                <a href="Asistencias/index.php">
                                    <div style="padding: 20px;">
                                        <img src="icons/asistencia.png" width="70" />
                                        <span class="grey-text text-darken-3">
                                            <h5>Asistencias</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-Vales"
                                category="panel-vales">
                                <a href="Vales/index.php">
                                    <div style="padding: 20px;">
                                        <img src="icons/vales.png" width="70" />
                                        <span class="grey-text text-darken-3">
                                            <h5>Vales</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-Buro"
                                category="panel-buro">
                                <a href="../buro">
                                    <div style="padding: 20px;">
                                        <img src="icons/burocredito.png" width="70" />
                                        <span class="grey-text text-darken-3">
                                            <h5>Buro de Credito</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-documentos"
                                category="panel-documentos">
                                <a href="documentoslicitaciones/index.php">
                                    <div style="padding: 20px;">
                                        <img src="icons/licitaciones.png" width="70" />
                                        <span class="grey-text text-darken-3">
                                            <h5>Documentos Licitaciones</h5>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="col s12 m4 l2 xl2  waves-effect center panel" id="panel-crmlito"
                                category="panel-crmlito">
                                <a href="/crmlito?usuario=" target="_blank">
                                    <div style="padding: 20px;">
                                        <img src="icons/CRMLito.png" width="70" />
                                        <span class="grey-text text-darken-3">
                                            <h5>CRM Lito</h5>
                                        </span>
                                    </div>
                                </a>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
