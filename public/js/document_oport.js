$('#documentos-oport-tab').on('click', function() {
    generarListaDocumentos();
});

/**
 * Funcion para generar la lista de documentos de oportunidad
 * 
 * @return void
 */
function generarListaDocumentos()
{
    let formDocumentosOport = new FormData();
    formDocumentosOport.append('_token', CSRF_TOKEN);
    formDocumentosOport.append('_method', 'GET');
    formDocumentosOport.append('idOportunidad', $('#idOportunidad').val());

    $.ajax({
        url: serve + "documentos-oportunidades",
        async: true,
        type: "POST",
        data: formDocumentosOport,
        processData: false,
        contentType: false,
        success: function(data) {
            if (data.response) {
                // Construimos la tabla de documentos
                let tabla = `
                    <div class="tblDocsOpt-responsive">
                        <div class="bg-gray text-bold text-xs tblDocsOpt" style="border-top:2px solid #d1d1d1; padding: 4px 6px;">
                            <div class="text-left">Documento</div>
                            <div class="text-center">Fecha registro</div>
                            <div class="text-center">Usuario registro</div>
                            <div class="text-center">Acciones</div>
                        </div>
                `;

                // Datos obtenidos para las acciones
                let acciones_tripas = data.acciones_tripas;
                let acciones_documentos_por_rol = data.acciones_documentos_por_rol;
                let rol = data.rol;
                let documentos_con_notas = data.documentos_con_notas;
                let oportunidad_estatus = $('#Estatus').val();
                let tiene_ordenes_compra = data.tiene_ordenes_compra;
                let tiene_orden_metrics = data.tiene_orden_metrics;
                let tiene_prepedido = data.tiene_prepedido;
                let es_oportunidad_cancelada = data.es_oportunidad_cancelada;
                let idUsuario = data.id_usuario;

                // Cuando la oportunidad este concluida solo se permiten acciones de visualizacion
                if (oportunidad_estatus == 12 || oportunidad_estatus == 10 || es_oportunidad_cancelada) {
                    if (rol != 1 && rol != 8) {
                        // Solo mantenemos las acciones para visualizar
                        acciones_documentos_por_rol = acciones_documentos_por_rol.filter(function(accion) {
                            return accion.accion_id == "2" || accion.accion_id == "1";
                        });
                    }
                    
                    if (oportunidad_estatus == 10) {
                        acciones_documentos_por_rol = acciones_documentos_por_rol.filter(function(accion) {
                            return accion.accion_id != "1" && accion.accion_id != "3";
                        });

                        acciones_tripas = acciones_tripas.filter(function(accion) {
                            return accion.accion_id != "1" && accion.accion_id != "2" && accion.accion_id != "3" && accion.accion_id != "5" && accion.accion_id != "6" && accion.accion_id != "10" && accion.accion_id != "11" && accion.accion_id != "12" && accion.accion_id != "13" && accion.accion_id != "14" && accion.accion_id != "15" && accion.accion_id != "16" && accion.accion_id != "17" && accion.accion_id != "18" && accion.accion_id != "19";
                        });
                    }

                    // En las tripas mantenemos las acciones para visualizar
                    // acciones_tripas = acciones_tripas.filter(function(accion) {
                    //     return accion.accion_id != 11;
                    // });
                } else {
                    acciones_documentos_por_rol = acciones_documentos_por_rol;
                    acciones_tripas = acciones_tripas;
                }

                data.documentos_final.forEach(function(tipo_doc) {
                    tabla += `
                        <div class="align-items-center tblDocsOpt text-xs docSectionItem" title="${tipo_doc.tooltip}" id="docSectionItem_${tipo_doc.id_tipo_documento}" onclick="mostrarSublistadoDocumentos(${tipo_doc.id_tipo_documento})" style="border-bottom:1px solid #d1d1d1; cursor: pointer;">
                            <div class="px-2 py-small text-left font-semibold">
                                <i id="chevronIconMain_${tipo_doc.id_tipo_documento}" class="fas fa-chevron-down mr-2"></i>
                                ${tipo_doc.tipo_documento} (${tipo_doc.total_documentos})
                            </div>
                            <div class="px-2 py-small text-center"></div>
                            <div class="px-2 py-small text-center"></div>
                            <div class="px-2 py-small text-center w-full">
                        `;

                    const tieneAccionCargarArchivos = acciones_documentos_por_rol.some(function(accion) {
                        return accion.documento_id == tipo_doc.id_tipo_documento && accion.accion_id == "1";
                    });

                    // Saber si algunos de los documentos cargados del tipo 14 tiene prepedido realizado
                    let prepedido_realizado = tipo_doc.documentos.some(function(doc) {
                        return doc.solicito_prepedido == "1";
                    });

                    // Cargar archivos
                    if (tieneAccionCargarArchivos) {
                        if (tipo_doc.id_tipo_documento == 14) {
                            tabla += `
                                <a title="Subir documento" onclick="abrirModalCargaArchivos(${tipo_doc.id_tipo_documento}, ${prepedido_realizado})" style="cursor:pointer; margin-right: 8px;">
                                    <i class="fas fa-upload icondocsup text-blue-500"></i>
                                </a>
                            `;
                        } else {
                            tabla += `
                                <a title="Subir documento" onclick="modal_upDocs(${tipo_doc.id_tipo_documento})" style="cursor:pointer; margin-right: 8px;">
                                    <i class="fas fa-upload icondocsup text-blue-500"></i>
                                </a>
                            `;
                        }
                    }

                    tabla += `
                            </div>
                        </div>
                        <div class="sublistadoDocsOpt" id="sublistadoDocsOpt_${tipo_doc.id_tipo_documento}" style="display: none;">
                    `;

                    tipo_doc.documentos.forEach(function(doc) {
                        tabla += `
                            <div class="align-items-center tblDocsOpt text-xss docItem pl-4" style="border-bottom:1px solid #e1e1e1;">
                                <div class="px-2 py-small text-left">
                                    ${doc.descDocumento}`;

                        // Validacion para prepedido y mostrar iconos con tooltips
                        if (doc.solicito_prepedido == "1" && tipo_doc.id_tipo_documento == 14) {
                            tabla += ` <i class="fas fa-check-circle text-green-500 text-xs ml-2" title="Se realizó prepedido sobre este documento"></i>`;
                        }

                        tabla += `
                                </div>
                                <div class="px-2 py-small text-center">${formatoFecha(doc.fecRegistro)}</div>
                                <div class="px-2 py-small text-center">${doc.usuario_registro_doc}</div>
                                <div class="px-2 py-small text-center w-full">`;

                        const tieneAccionVisualizar = acciones_documentos_por_rol.some(function(accion) {
                            return accion.documento_id == tipo_doc.id_tipo_documento && accion.accion_id == "2";
                        });
                        
                        // Visualizar
                        if (tieneAccionVisualizar) {
                            tabla += `
                                <a title="Visualizar documento" onclick="visorDocumentos(${doc.idDocumento}, ${rol})" style="cursor:pointer; margin-right: 8px;">
                                    <i class="fas fa-eye icondocseye text-green-500 text-base"></i>
                                </a>
                            `;
                        }

                        const tieneAccionEliminar = acciones_documentos_por_rol.some(function(accion) {
                            return accion.documento_id == tipo_doc.id_tipo_documento && accion.accion_id == "3";
                        });

                        // Eliminar
                        if (tieneAccionEliminar) {
                            tabla += `
                                <a title="Eliminar documento" onclick="modal_deleteDocs(${doc.idDocumento})" style="cursor:pointer; margin-right: 8px;">
                                    <i class="fas fa-trash icondocstrash text-red-500 text-base"></i>
                                </a>
                            `;
                        }

                        tabla += `
                                </div>
                            </div>
                        `;
                    });

                    tabla += `
                        </div>
                    `;
                });

                let tipo_documento_tripa = data.tipo_documento_tripa;

                // Seccion de tripas si aplica
                tabla += `
                    <div class="align-items-center tblDocsOpt text-xs docSectionItem " title="${tipo_documento_tripa.descripcionTooltip}" id="docSectionItem_tripas" style="border-bottom:1px solid #d1d1d1; padding: 4px 6px; cursor: pointer; ${rol == 1020 ? 'display:none;' : ''}" onclick="mostrarSublistadoDocumentos('tripas')">
                        <div class="px-2 py-small text-left font-semibold">Tripas Cargadas (${data.total_tripas_cargadas})</div>
                        <div class="px-2 py-small text-center"></div>
                        <div class="px-2 py-small text-center"></div>
                `;

                // if ((rol == 8 || rol == 1) && tipo_documento_tripa.idTipoDocumento == 13) {
                //     tabla += `
                //         <div class="px-2 py-small text-center w-full">
                //             <a title="Subir documento" onclick="modal_upDocs(${tipo_documento_tripa.idTipoDocumento})" style="cursor:pointer; margin-right: 8px;">
                //                 <i class="fas fa-upload icondocsup text-blue-500"></i>
                //             </a>
                //         </div>
                //     `;
                // }
                
                tabla += `
                    </div>
                `;

                if (data.total_tripas_cargadas > 0) {
                    // Sublistado de tripas
                    tabla += `
                        <div class="sublistadoDocsOpt ${rol == 1020 ? 'sr-only' : ''}" id="sublistadoDocsOpt_tripas">
                    `;

                    // Orden personalizado
                    const orden_tripac = [1, 6, 2, 5, 3, 4];
                    orden_tripac.forEach(function(tipo_tripa) {
                        let tripa_tipo = data.tripas_agrupadas[tipo_tripa];
                        if (!tripa_tipo) return;

                        tabla += `
                            <div class="align-items-center tblDocsOpt text-xss docItem pl-4 align-items-center tblDocsOpt text-xs docSectionSubItem" style="border-bottom:1px solid #e1e1e1;" onclick="mostrarSubSublistadoDocsOpt('${tipo_tripa}'); savePositionOpenedSublist('subsublistadoDocsOpt_${tipo_tripa}', ${tipo_tripa});">
                                <div class="px-2 py-small text-left font-semibold" style="cursor:pointer;">
                                    <i id="chevronIcon_${tipo_tripa}" class="fas fa-chevron-down mr-2"></i>
                                    ${tituloSublistaTripas(tipo_tripa)} (${tripa_tipo.length}) 
                                </div>
                            </div>
                            <div class="subsublistadoDocsOpt" id="subsublistadoDocsOpt_${tipo_tripa}" style="display:none;">
                        `;

                        tripa_tipo.forEach(function(tripa) {
                            tabla += `
                                <div class="align-items-center tblDocsOpt text-xss docItem pl-4" style="border-bottom:1px solid #e1e1e1; padding: 4px 20px;">
                                    <div class="px-2 py-small text-left">`;

                            // Acción para cambiar el nombre de la tripa
                            const tieneAccionCambiarNombreTripa = acciones_tripas.some(function(accion) {
                                return accion.accion_id == "12";
                            });

                            if (tieneAccionCambiarNombreTripa && (tipo_tripa === 3 || tipo_tripa === 2)) {
                                tabla += `
                                    <div class="d-flex">
                                        <div style="width: 80%">
                                            <input id="descDocOld_${tripa.idDocumento}" type="hidden" value="${tripa.descDocumento}">
                                            <input id="descDoc_${tripa.idDocumento}" type="text" class="form-control" onkeyup="mostrarBtnSaveA(${tripa.idDocumento})" style="border: none; height: 25px !important" value="${tripa.descDocumento}">
                                        </div>
                                        <div style="width: 20%; text-align: center">
                                            <i id="saveDoc_${tripa.idDocumento}" class="fas fa-save cursor-pointer mt-1" style="font-size: 20px; display: none;" onclick="actualizarNombreDocumento(${tripa.idDocumento})"></i>
                                        </div>
                                    </div>
                                `;
                            } else {
                                tabla += `
                                    ${tripa.descDocumento}
                                `;
                            }

                            tabla += `
                                    </div>
                                    <div class="px-2 py-small text-center">${formatoFecha(tripa.fecRegistro)}</div>
                                    <div class="px-2 py-small text-center">${tripa.usuario_registro_doc}</div>
                                    <div class="px-2 py-small text-center w-full d-flex align-items-start justify-content-center">`;

                            // Acción para descargar tripa
                            const tieneAccionVerMotivoRechazo = acciones_tripas.some(function(accion) {
                                return accion.accion_id == "9";
                            });

                            const tieneNotaRechazo = documentos_con_notas.includes(tripa.idDocumento);

                            if (tieneAccionVerMotivoRechazo && tieneNotaRechazo) {
                                tabla += `
                                    <a title="Ver motivo de rechazo" onclick="showMotiveRejected(${tripa.idDocumento})" style="cursor:pointer; margin-right: 8px;">
                                        <i class="fas fa-sticky-note icondocsnote text-red-600 text-base"></i>
                                    </a>
                                `;
                            }
                            
                            // Acción para ver la nota de produccion de la tripa
                            const tieneAccionVerNotaProduccion = acciones_tripas.some(function(accion) {
                                return accion.accion_id == "18";
                            });

                            if (tieneAccionVerNotaProduccion && tripa.nota_produccion && tripa.nota_produccion !== '' && tipo_tripa === 1) {
                                tabla += `
                                    <a title="Ver notas de produccion" onclick="showNotaProduccion(${tripa.idDocumento})" style="cursor:pointer; margin-right: 8px;">
                                        <i class="fas fa-sticky-note icondocsnote text-sky-600 text-base"></i>
                                    </a>
                                `;
                            }

                            // Acción para rechazar tripa
                            const tieneAccionRechazarTripa = acciones_tripas.some(function(accion) {
                                return accion.accion_id == "10";
                            });

                            if (tieneAccionRechazarTripa && tipo_tripa === 3) {
                                tabla += `
                                    <a title="Rechazar tripa" onclick="rechazarDocumento(${tripa.idDocumento})" style="cursor:pointer; margin-right: 8px;">
                                        <i class="fas fa-times-circle icondocstreject text-red-500 text-base"></i>
                                    </a>
                                `;
                            }

                            // Acción para editar tripa
                            const tieneAccionEditarTripa = acciones_tripas.some(function(accion) {
                                return accion.accion_id == "2";
                            });

                            if (tieneAccionEditarTripa && tipo_tripa === 3 && idUsuario != 13582) {
                                tabla += `
                                    <a title="Editar tripa" onclick="editDocument(${tripa.idDocumento})" style="cursor:pointer; margin-right: 8px;">
                                        <i class="fas fa-pen icondocsedit text-black text-base"></i>
                                    </a>
                                `;
                            }

                            // Acción para copiar link de tripa
                            const tieneAccionCopiarLinkTripa = acciones_tripas.some(function(accion) {
                                return accion.accion_id == "3";
                            });

                            if (tieneAccionCopiarLinkTripa && tipo_tripa === 3 && idUsuario != 13582) {
                                tabla += `
                                    <a title="Copiar link de documento" onclick="copiarLigaTripa(${tripa.idDocumento})" style="cursor:pointer; margin-right: 8px;">
                                        <i class="fas fa-link icondocslink text-black text-base"></i>
                                    </a>
                                `;
                            }

                            // Acción para visualizar tripa
                            const tieneAccionVisualizarTripa = acciones_tripas.some(function(accion) {
                                return accion.accion_id == "4";
                            });

                            if (tieneAccionVisualizarTripa) {
                                tabla += `
                                    <a title="Visualizar documento" onclick="visorDocumentos(${tripa.idDocumento}, ${rol})" style="cursor:pointer; margin-right: 8px;">
                                        <i class="fas fa-eye icondocseye text-green-500 text-base"></i>
                                    </a>
                                `;
                            }

                            // Acción para eliminar tripa
                            const tieneAccionEliminarTripa = acciones_tripas.some(function(accion) {
                                return accion.accion_id == "11";
                            });

                            if (tieneAccionEliminarTripa && tipo_tripa !== 1 && tipo_tripa !== 6 && tipo_tripa !== 5 && tipo_tripa !== 2) {
                                tabla += `
                                    <a title="Eliminar documento" onclick="modal_deleteDocs(${tripa.idDocumento})" style="cursor:pointer; margin-right: 8px;">
                                        <i class="fas fa-trash icondocstrash text-red-500 text-base"></i>
                                    </a>
                                `;
                            }

                            // Acción para des-autorizar tripa s/cambios
                            const tieneAccionDesAutorizarTripa = acciones_tripas.some(function(accion) {
                                return accion.accion_id == "6";
                            });

                            if (tieneAccionDesAutorizarTripa && tipo_tripa === 2) {
                                tabla += `
                                    <a title="Remover autorización S/Cambios" onclick="validarTripaC(${tripa.idDocumento}, 5)" style="cursor:pointer; margin-right: 8px;">
                                        <i class="fas fa-times-circle icondocstreject text-red-500 text-base"></i>
                                    </a>
                                `;
                            }

                            // Acción para des-autorizar tripa c/cambios
                            const tieneAccionDesAutorizarTripaConCambios = acciones_tripas.some(function(accion) {
                                return accion.accion_id == "5";
                            });

                            if (tieneAccionDesAutorizarTripaConCambios && tipo_tripa === 5) {
                                tabla += `
                                    <a title="Remover autorización C/Cambios" onclick="validarTripaC(${tripa.idDocumento}, 5)" style="cursor:pointer; margin-right: 8px;">
                                        <i class="fas fa-times-circle icondocstreject text-red-500 text-base"></i>
                                    </a>
                                `;
                            }

                            // Acción para enviar a tripas de preprensa
                            const tieneAccionEnviarPreprensa = acciones_tripas.some(function(accion) {
                                return accion.accion_id == "13";
                            });

                            if (tieneAccionEnviarPreprensa && tipo_tripa === 2) {
                                // Validamos si tiene ordenes de compra para poder enviar a tripas de preprensa
                                // Colocamos un icono de engranaje para indicar que se puede enviar a tripas de preprensa
                                tabla += `
                                    <a title="Enviar a tripas de preprensa" onclick="enviarTripaPreprensa(${tripa.idDocumento})" style="cursor:pointer; margin-right: 8px;">
                                        <i class="fas fa-cogs icondocslink text-sky-500 text-base"></i>
                                    </a>
                                `;
                            }
                            
                            // Acción para mover a tripas de producción
                            const tieneAccionEnviarProduccion = acciones_tripas.some(function(accion) {
                                return accion.accion_id == "14";
                            });

                            if (tieneAccionEnviarProduccion && tipo_tripa === 6 ) {
                                // Validamos si tiene orden de metrics para poder enviar a producción
                                if (tiene_prepedido && tripa.cantidad_preprensa && tripa.cantidad_preprensa > 0 && tripa.folio_preprensa && tripa.folio_preprensa !== '') {
                                    // Colocamos un icono de engranaje para indicar que se puede enviar a producción
                                    tabla += `
                                        <a title="Enviar a producción" onclick="visorDocumentos(${tripa.idDocumento}, 2, 2)" style="cursor:pointer; margin-right: 8px;">
                                            <i class="fas fa-cogs icondocslink text-green-500 text-base"></i>
                                        </a>
                                    `;
                                }
                            }

                            // Acción para rechazar tripa de producción
                            const tieneAccionRechazarTripaProd = acciones_tripas.some(function(accion) {
                                return accion.accion_id == "16";
                            });

                            if (tieneAccionRechazarTripaProd && tipo_tripa === 1) {
                                tabla += `
                                    <a title="Rechazar tripa (PROD)" onclick="rechazarTripaProd(${tripa.idDocumento})" style="cursor:pointer; margin-right: 8px;">
                                        <i class="fas fa-times-circle icondocstreject text-red-500 text-base"></i>
                                    </a>
                                `;
                            }
                            
                            // Acción para rechazar tripa de tripas de preprensa
                            const tieneAccionRechazarTripaPreprensa = acciones_tripas.some(function(accion) {
                                return accion.accion_id == "19";
                            });

                            if (tieneAccionRechazarTripaPreprensa && tipo_tripa === 6) {
                                tabla += `
                                    <a title="Rechazar tripa (PREPRENSA)" onclick="rechazarTripaPreprensa(${tripa.idDocumento})" style="cursor:pointer; margin-right: 8px;">
                                        <i class="fas fa-times-circle icondocstreject text-red-500 text-base"></i>
                                    </a>
                                `;
                            }

                            // Acción para asiganar folio de preprensa
                            const tieneAccionAsignarFolioPreprensa = acciones_tripas.some(function(accion) {
                                return accion.accion_id == "17";
                            });

                            if (tieneAccionAsignarFolioPreprensa && tipo_tripa === 6) {
                                // Si tiene folio de preprensa asignado ya no se muestra el switch
                                if ((!tripa.folio_preprensa || tripa.folio_preprensa == '')) {
                                    tabla += `
                                        <a title="Asignar Folio Preprensa y Liberar" onclick="asignarFolioPP(${tripa.idDocumento})" style="cursor:pointer; margin-right: 8px;">
                                            <i class="fas fa-flag-checkered icondocslink text-sky-500 text-base"></i>
                                        </a>
                                    `;
                                } else {
                                    if (tripa.folio_preprensa && tripa.folio_preprensa != '') {
                                        // Iconos para ver el folio de preprensa ya asignado
                                        if (tripa.folio_preprensa != null) {
                                            tabla += `
                                                <div style=" font-weight: bold; margin-right: 7px;">
                                                    <span># Orden: ${tripa.folio_preprensa}; &nbsp;</span>
                                                </div>
                                            `;
                                        } else {
                                            tabla += ``;
                                        }
                                    }
                                }
                            } else {
                                // Solo para producción mostrar el folio de preprensa asignado
                                if (tipo_tripa === 1) {
                                    // Iconos para ver el folio de preprensa ya asignado
                                    if (tripa.folio_preprensa != null) {
                                        tabla += `
                                            <div style=" font-weight: bold; ">
                                                <span># Orden: ${tripa.folio_preprensa}; &nbsp;</span>
                                            </div>
                                        `;
                                    } else {
                                        tabla += ``;
                                    }
                                }
                            }

                            if (tripa.checklist_finalizado == 1) {
                                tabla += `
                                    <a title="Mostrar checklist" onclick="mostrarChecklist(${tripa.idDocumento})" style="cursor:pointer; margin-right: 8px;">
                                        <i class="fas fa-clipboard-list icondocschecklist text-sky-500 text-base"></i>
                                    </a>
                                `;
                            }

                            // Acción para actualizar estatus a solicitud de prepedido
                            const tieneAccionActualizarEstatusPrepedido = acciones_tripas.some(function(accion) {
                                return accion.accion_id == "15";
                            });

                            if (tieneAccionActualizarEstatusPrepedido && (tipo_tripa === 2 || tipo_tripa === 6 || tipo_tripa === 1)) {
                                if (tiene_ordenes_compra) {
                                    // Validamos si la cantidad esta vacia o en cero se muestra el icono
                                    if (!tripa.cantidad_preprensa || tripa.cantidad_preprensa === 0) {
                                        // Colocamos un icono de engranaje para indicar que se puede enviar a tripas de prepedido
                                        tabla += `
                                            <a title="Solicitar Prepedido" onclick="abrirModalSeleccionarCantidadTripa(${tripa.idDocumento})" style="cursor:pointer; margin-right: 8px;">
                                                <i class="fas fa-dollar-sign text-amber-500 text-base"></i>
                                            </a>
                                        `;
                                    } else {
                                        // Iconos para ver la cantidad ya seleccionada
                                        if (tripa.cantidad_preprensa != null) {
                                            tabla += `
                                                <div style=" font-weight: bold;">
                                                    <span>Cant.: ${tripa.cantidad_preprensa.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")};</span>
                                                </div>
                                            `;
                                        } else {
                                            tabla += ``;
                                        }
                                    }
                                }
                            } else {
                                if (tripa.cantidad_preprensa != null) {
                                    tabla += `
                                        <div style=" font-weight: bold;">
                                            <span>Cant.: ${tripa.cantidad_preprensa.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")};</span>
                                        </div>
                                    `;
                                } else {
                                    tabla += ``;
                                }
                            }

                            tabla += `
                                    </div>
                                </div>
                            `;
                        });

                        tabla += `
                            </div>
                        `;
                    });

                    tabla += `
                        </div>
                    `;
                }

                tabla += `
                    </div>
                `;

                $('#tblDocumentosOport').html(tabla);

                // Una vez que se pinte la tabla, restauramos las sublistas abiertas
                setTimeout(function() {
                    restorePositionOpenedSublists();
                }, 500);
            }
        }
    });
}

/**
 * Función para guardar las categorías de las tripas que han sido desplegadas en localStorage para que cuando se de click en recargar no se pierdan
 * 
 * @param {string} id_tab
 * @param {int} tipo_tripa
 * @return void
 */
function savePositionOpenedSublist(id_tab, tipo_tripa)
{
    let openedSublist = JSON.parse(localStorage.getItem('openedSublistDocsOport')) || {};
    openedSublist[tipo_tripa] = (document.getElementById(id_tab).style.display === 'block');
    localStorage.setItem('openedSublistDocsOport', JSON.stringify(openedSublist));
}

/**
 * Funcion para restaurar la posicion de las sublistas abiertas
 * 
 */
function restorePositionOpenedSublists()
{
    let openedSublist = JSON.parse(localStorage.getItem('openedSublistDocsOport')) || {};

    for (const id_tab in openedSublist) {
        if (openedSublist[id_tab]) {
            mostrarSubSublistadoDocsOpt(id_tab);
        }
    }
}

/**
 * Funcion para mostrar el boton de guardar al cambiar el nombre del documento
 * 
 * @param {number} id_documento
 */
function mostrarBtnSaveA(id_documento)
{
    $('#saveDoc_' + id_documento).show();
}

/**
 * Funcion para dar formato a la fecha
 * 
 * @param {*} fechaStr 
 * @returns 
 */
function formatoFecha(fechaStr) {
    if (!fechaStr) return '';
    const fecha = new Date(fechaStr);
    if (isNaN(fecha)) return fechaStr;
    const dia = String(fecha.getDate()).padStart(2, '0');
    const mes = String(fecha.getMonth() + 1).padStart(2, '0');
    const anio = fecha.getFullYear();
    const horas = String(fecha.getHours()).padStart(2, '0');
    const minutos = String(fecha.getMinutes()).padStart(2, '0');
    const segundos = String(fecha.getSeconds()).padStart(2, '0');
    return `${dia}/${mes}/${anio} ${horas}:${minutos}:${segundos}`;
}

/**
 * Funcion para mostrar el sublistado de documentos por tipo
 * 
 * @param {number} id_tipo_documento
 */
function mostrarSublistadoDocumentos(id_tipo_documento)
{
    const el = document.getElementById('sublistadoDocsOpt_' + id_tipo_documento);
    const icon = document.getElementById('chevronIconMain_' + id_tipo_documento);
    if (el) {
        if (el.style.display === 'none') {
            el.style.display = 'block';
            if (icon) {
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            }
        } else {
            el.style.display = 'none';
            if (icon) {
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            }
        }
    }
}

/**
 * Funcion para mostrar el sub-sublistado de tripas por tipo de tripa
 * 
 * @param {*} tipo_tripa
 */
function mostrarSubSublistadoDocsOpt(tipo_tripa) {
    const el = document.getElementById('subsublistadoDocsOpt_' + tipo_tripa);
    const icon = document.getElementById('chevronIcon_' + tipo_tripa);
    if (el) {
        if (el.style.display === 'none') {
            el.style.display = 'block';
            if (icon) {
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            }
        } else {
            el.style.display = 'none';
            if (icon) {
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            }
        }
    }
}
/**
 * Funcion para asignar los titulos de las sublistas de tripas
 * 
 * @param {string} tipo_tripa
 * @return {string}
 */
function tituloSublistaTripas(tipo_tripa)
{
    let titulo = '';

    // 1 = Producción
    // 2 = Autorizado S/Cambios
    // 3 = Por Autorizar
    // 4 = Rechazado
    // 5 = Autorizado C/Cambios
    // 6 = Tripas p/Preprensa

    switch (tipo_tripa) {
        case 1:
            titulo = 'Producción';
            break;
        case 2:
            titulo = 'Autorizado S/Cambios';
            break;
        case 3:
            titulo = 'Por Autorizar';
            break;
        case 4:
            titulo = 'Rechazado';
            break;
        case 5:
            titulo = 'Autorizado C/Cambios';
            break;
        case 6:
            titulo = 'Tripas p/Preprensa';
            break;
    }

    return titulo;
}

/**
 * Funcion para abrir el modal de carga de documentos
 * 
 * @param {number} id_tipo_documento
 * @param {boolean} prepedido_realizado
 * @return void
 */
function abrirModalCargaArchivos(id_tipo_documento, prepedido_realizado)
{
    $('#mod_carga_archivos').modal('show');
    $('#idTipoDocumentoCandy').val(id_tipo_documento);

    // if (id_tipo_documento == 14 && prepedido_realizado) {
    //     $('#seccion-sol-prepedido').addClass('sr-only');
    // } else {
    //     $('#seccion-sol-prepedido').removeClass('sr-only');
    // }
}

/**
 * Funcion para previsualizar el archivo seleccionado
 * 
 * @return void
 */
$(document).off('change', '#FileDocAdd').on('change', '#FileDocAdd', function(e) {
    const file = e.target.files[0];
    const preview = $('#previewArchivo');
    preview.html(`
        <div class="progress" style="height: 18px;">
            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%; background: #28a745;">
                Cargando archivo...
            </div>
        </div>
    `);
    if (!file) return;
    if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(ev) {
            preview.html(`<img src="${ev.target.result}" class="preview-img" alt="Previsualización">`);
        };
        setTimeout(() => reader.readAsDataURL(file), 500); // Simula carga
    } else if (file.type === 'application/pdf') {
        setTimeout(() => {
            const url = URL.createObjectURL(file);
            preview.html(`<iframe src="${url}" class="preview-pdf"></iframe>`);
        }, 500); // Simula carga
    } else {
        setTimeout(() => {
            preview.html('<span class="text-danger">Formato no soportado para previsualización.</span>');
        }, 500);
    }
});

/**
 * Funcion para previsualizar el archivo seleccionado
 * 
 * @return void
 */
let selectedFilesP = [];

function obtenerMimeArchivoMultipresupuesto(extension) {
    const extensionNormalizada = (extension || '').toString().trim().toLowerCase();

    switch (extensionNormalizada) {
        case 'jpg':
        case 'jpeg':
            return 'image/jpeg';
        case 'png':
            return 'image/png';
        default:
            return 'application/octet-stream';
    }
}

function construirDataUriMultipresupuesto(archivoBase64, extension) {
    if (!archivoBase64) return '';

    if (archivoBase64.startsWith('data:')) {
        return archivoBase64;
    }

    return `data:${obtenerMimeArchivoMultipresupuesto(extension)};base64,${archivoBase64}`;
}

async function convertirArchivoGrupoEPParaMultipresupuesto(archivo, indice) {
    const extension = (archivo.extension || 'jpg').toString().trim().toLowerCase();
    const mimeType = obtenerMimeArchivoMultipresupuesto(extension);
    const dataUri = construirDataUriMultipresupuesto(archivo.archivo, extension);
    const response = await fetch(dataUri);
    const blob = await response.blob();
    const nombreArchivo = archivo.nombreArchivo || `grupo_ep_${archivo.idGrupoEEP || 'archivo'}_${indice + 1}.${extension}`;
    const file = new File([blob], nombreArchivo, { type: blob.type || mimeType });

    file.previewUrl = URL.createObjectURL(blob);

    return file;
}

function limpiarArchivosMultipresupuesto() {
    $('#FileDocAddP').val('');
    $('#previewArchivoP').html('');
    selectedFilesP = [];
}

async function precargarArchivosGrupoEPParaMultipresupuesto(idGrupoEP) {
    if (!idGrupoEP) return;

    $('#previewArchivoP').html('<p class="text-center text-muted m-0">Cargando archivos del grupo...</p>');

    try {
        const resp = await $.ajax({
            url: serve + 'obtenerExcelesEPsPorGrupo/' + idGrupoEP,
            type: 'POST',
            dataType: 'json',
            data: {
                _token: CSRF_TOKEN
            }
        });

        if (!(resp && resp.response && Array.isArray(resp.data) && resp.data.length > 0)) {
            $('#previewArchivoP').html('<p class="text-center text-muted m-0">No se encontraron imágenes relacionadas al grupo.</p>');
            return;
        }

        const archivosConvertidos = [];

        for (let indice = 0; indice < resp.data.length; indice++) {
            const archivo = resp.data[indice];

            if (!archivo || !archivo.archivo) continue;

            archivosConvertidos.push(await convertirArchivoGrupoEPParaMultipresupuesto(archivo, indice));
        }

        selectedFilesP = archivosConvertidos;
        renderPreviewP();
    } catch (error) {
        selectedFilesP = [];
        $('#previewArchivoP').html('<p class="text-center text-danger m-0">Ocurrió un error al cargar los archivos del grupo.</p>');
    }
}

// Optimiza imágenes antes de agregarlas (reescalado y compresión)
async function processImageFile(file, maxWidth = 4000, quality = 0.9) {
    return new Promise((resolve) => {
        const img = new Image();
        const url = URL.createObjectURL(file);
        img.onload = function () {
            let width = img.width;
            let height = img.height;
            if (width > maxWidth) {
                height = Math.round((maxWidth / width) * height);
                width = maxWidth;
            }
            const canvas = document.createElement('canvas');
            canvas.width = width;
            canvas.height = height;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0, width, height);
            canvas.toBlob(
                (blob) => {
                    const newFile = new File([blob], file.name.replace(/\.[^/.]+$/, ".jpg"), { type: "image/jpeg" });
                    newFile.previewUrl = URL.createObjectURL(blob);
                    resolve(newFile);
                },
                'image/jpeg',
                quality
            );
            URL.revokeObjectURL(url);
        };
        img.src = url;
    });
}

function renderPreviewP() {
    const preview = $('#previewArchivoP');
    let html = '<ul id="sortablePreviewP" style="list-style:none;padding:0;display:flex;flex-wrap:wrap;">';
    selectedFilesP.forEach((file, idx) => {
        let item = `<li class="draggable-preview" draggable="true" data-idx="${idx}" style="margin:4px;cursor:move;position:relative;">`;
        // Botón de eliminar
        item += `<button type="button" class="remove-file-btn" data-remove-idx="${idx}" style="position:absolute;top:2px;right:2px;z-index:2;background:#fff;border:1px solid #ccc;border-radius:50%;width:22px;height:22px;cursor:pointer;">&times;</button>`;
        if (file.type.startsWith('image/')) {
            const url = file.previewUrl || URL.createObjectURL(file);
            item += `<img src="${url}" class="preview-img-p" alt="Previsualización" style="max-width:120px;max-height:120px;">`;
        } else {
            item += '<span class="text-danger d-block">Formato no soportado</span>';
        }
        item += '</li>';
        html += item;
    });
    html += '</ul>';
    preview.html(html);

    // Drag & drop nativo
    let dragSrcEl = null;
    $('#sortablePreviewP').on('dragstart', 'li', function(e) {
        dragSrcEl = this;
        e.originalEvent.dataTransfer.effectAllowed = 'move';
        e.originalEvent.dataTransfer.setData('text/html', this.outerHTML);
        $(this).addClass('dragElem');
    });
    $('#sortablePreviewP').on('dragover', 'li', function(e) {
        e.preventDefault();
        e.originalEvent.dataTransfer.dropEffect = 'move';
        return false;
    });
    $('#sortablePreviewP').on('drop', 'li', function(e) {
        e.stopPropagation();
        if (dragSrcEl !== this) {
            if ($(dragSrcEl).index() < $(this).index()) {
                $(this).after(dragSrcEl);
            } else {
                $(this).before(dragSrcEl);
            }
            updateSelectedFilesOrder();
        }
        $('.dragElem').removeClass('dragElem');
        return false;
    });
    $('#sortablePreviewP').on('dragend', 'li', function() {
        $('.dragElem').removeClass('dragElem');
    });

    // Eliminar archivo
    $('.remove-file-btn').off('click').on('click', function(e) {
        e.stopPropagation();
        const idx = $(this).data('remove-idx');
        selectedFilesP.splice(idx, 1);
        renderPreviewP();
    });

    function updateSelectedFilesOrder() {
        let newOrder = [];
        $('#sortablePreviewP li').each(function() {
            let idx = $(this).attr('data-idx');
            newOrder.push(selectedFilesP[idx]);
        });
        selectedFilesP = newOrder;
        renderPreviewP(); // Re-render para actualizar los índices y botones
    }
}

// Al seleccionar archivos, los agrega a la lista sin reiniciar y optimiza imágenes
$(document).off('change', '#FileDocAddP').on('change', '#FileDocAddP', async function(e) {
    const files = Array.from(e.target.files);
    for (const file of files) {
        if (!selectedFilesP.some(f => f.name === file.name && f.size === file.size)) {
            if (file.type.startsWith('image/')) {
                const processed = await processImageFile(file, 2000, 0.9);
                selectedFilesP.push(processed);
            } else {
                file.previewUrl = file.type === 'application/pdf' ? URL.createObjectURL(file) : null;
                selectedFilesP.push(file);
            }
        }
    }
    renderPreviewP();
    // Limpia el input para permitir volver a seleccionar el mismo archivo si se elimina
    $(this).val('');
});

/**
 * Funcion para guardar el documento cargado de oportunidad
 * 
 * @return void
 */
function subirArchivoOport() {
    // let solicitarPrepedido = $('#solicitarPrepedido').is(':checked') ? 1 : 0;
    let solicitarPrepedido = 1;
    let formDataUpload = null;
    const fileInput = document.getElementById('FileDocAdd');
    const file = fileInput.files[0];
    
    if (!file) {
        $('#labelFileDocAdd').addClass('is-invalid');
        return;
    } else {
        $('#labelFileDocAdd').removeClass('is-invalid');
    }

    formDataUpload = new FormData();
    formDataUpload.append('_token', CSRF_TOKEN);
    formDataUpload.append('_method', 'POST');
    formDataUpload.append('idTipoDocumento', $('#idTipoDocumentoCandy').val());
    formDataUpload.append('solicitarPrepedido', solicitarPrepedido);
    formDataUpload.append('idOportunidad', $('#idOportunidad').val());
    formDataUpload.append('documento', file);

    $.ajax({
        url: serve + "/subir-archivo-oportunidad",
        type: "POST",
        data: formDataUpload,
        processData: false,
        contentType: false,
        success: function(resp) {
            if (resp.response) {
                const toastSuccess = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });

                toastSuccess.fire({
                    icon: 'success',
                    title: resp.message || 'Documento subido exitosamente.'
                });

                $('#mod_carga_archivos').modal('hide');
                generarListaDocumentos();

                // Limpiar el formulario
                $('#FileDocAdd').val('');
                $('#previewArchivo').html('');
                // $('#solicitarPrepedido').prop('checked', false);

                if (resp.refresh) {
                    // Actualizamos la pagina
                    location.reload();
                }
            } else {
                Swal.fire('Error', resp.message || 'No se pudo subir el documento.', 'error');
            }
        },
        error: function() {
            Swal.fire('Error', 'Ocurrió un error al subir el documento.', 'error');
        }
    });
}

/**
 * Funcion para enviar a tripas de preprensa
 * 
 * @param {number} id_documento
 * @return void
 */
function enviarTripaPreprensa(id_documento)
{
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción moverá la tripa a la sección de tripas de Preprensa.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, mover',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            let formData = new FormData();
            formData.append('_token', CSRF_TOKEN);
            formData.append('_method', 'POST');
            formData.append('idDocumento', id_documento);
            formData.append('idOportunidad', $('#idOportunidad').val());

            $.ajax({
                url: serve + "/enviar-tripa-preprensa",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(resp) {
                    if (resp.response) {
                        const toastSuccess = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                        });

                        toastSuccess.fire({
                            icon: 'success',
                            title: resp.message || 'Documento movido exitosamente.'
                        });

                        generarListaDocumentos();

                        if (resp.refresh) {
                            location.reload();
                        }
                    } else {
                        Swal.fire('Error', resp.message || 'No se pudo mover el documento.', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Ocurrió un error al mover el documento.', 'error');
                }
            });
        }
    });
}

let excelInstance = null;

/**
 * Funcion para abrir el modal de multipresupuesto desde Excel
 * 
 * @return void
 */
function abrirModalMultipresupuestoExcel(idGrupoEP = null)
{
    limpiarArchivosMultipresupuesto();
    $('#mod_multipresupuesto_excel').modal('show');

    if (idGrupoEP) {
        precargarArchivosGrupoEPParaMultipresupuesto(idGrupoEP);
    }

    // excelInstance = null;
    // document.getElementById('excelSheet').innerHTML = '';

    // // excelInstance = jspreadsheet(document.getElementById('excelSheet'), {
    // //     worksheets: [{
    // //         columns: [
    // //             { width:'200px' },
    // //             { width:'200px' },
    // //             { width:'200px' },
    // //             { width:'200px' },
    // //             { width:'200px' },
    // //             { width:'200px' },
    // //             { width:'200px' },
    // //             { width:'200px' },
    // //             { width:'200px' },
    // //             { width:'200px' }
    // //         ],
    // //         rows: Array.from({length: 20}, () => ({height: 100})),
    // //         minDimensions: [10,20],
    // //         allowInsertColumn: false,
    // //         allowDeleteColumn: false,
    // //         allowRenameColumn: false,
    // //         allowManualInsertColumn: false,
    // //         columnDrag: false,
    // //         rowDrag: false,
    // //         tableOverflow: true,
    // //         tableWidth: "95%",
    // //         // tableHeight: "400px",
    // //         allowComments: false,
    // //     }]
    // // });

    // const sheet = jspreadsheet(document.getElementById('excelSheet'), {
    //     worksheets: [{
    //         columns: [
    //             { width: 200 },
    //             { width: 200 },
    //             { width: 200 },
    //             { width: 200 },
    //             { width: 200 },
    //         ],

    //         rows: Array.from({ length: 20 }, () => ({ height: 100 })),
    //         minDimensions: [10, 20],
    //         allowInsertColumn: false,
    //         allowDeleteColumn: false,
    //         allowRenameColumn: false,
    //         allowManualInsertColumn: false,
    //         columnDrag: false,
    //         rowDrag: false,

    //         tableOverflow: true,
    //         tableWidth: "95%",
    //         allowComments: false,

    //         // Permitir merges
    //         allowMerge: true,
    //     }]
    // });

    // document.getElementById('excelSheet').addEventListener('paste', function (e) {
    //     const html = e.clipboardData.getData('text/html');
    //     if (!html) return;

    //     e.preventDefault();

    //     const parser = new DOMParser();
    //     const doc = parser.parseFromString(html, 'text/html');
    //     const table = doc.querySelector('table');
    //     if (!table) return;

    //     const rows = [];
    //     const merges = [];
    //     const cellStyles = {};

    //     [...table.rows].forEach((tr, r) => {
    //         rows[r] = [];
    //         let cIndex = 0;

    //         [...tr.cells].forEach(td => {
    //             while (rows[r][cIndex] !== undefined) cIndex++;

    //             rows[r][cIndex] = td.innerText;

    //             // Leer estilos CSS de Excel
    //             const style = td.getAttribute("style");
    //             if (style) {
    //                 if (!cellStyles[r]) cellStyles[r] = {};
    //                 cellStyles[r][cIndex] = style;
    //             }

    //             const cs = td.colSpan;
    //             const rs = td.rowSpan;

    //             if (cs > 1 || rs > 1) {
    //                 merges.push({
    //                     row: r,
    //                     col: cIndex,
    //                     colspan: cs,
    //                     rowspan: rs
    //                 });

    //                 for (let rr = r; rr < r + rs; rr++) {
    //                     for (let cc = cIndex; cc < cIndex + cs; cc++) {
    //                         if (!rows[rr]) rows[rr] = [];
    //                         rows[rr][cc] = rows[rr][cc] || "";
    //                     }
    //                 }
    //             }

    //             cIndex++;
    //         });
    //     });

    //     // Insertar contenido
    //     sheet[0].setData(rows);

    //     // Aplicar merges
    //     merges.forEach(m => {
    //         sheet[0].setMerge(m.row, m.col, m.rowspan, m.colspan);
    //     });

    //     // Aplicar estilos
    //     Object.entries(cellStyles).forEach(([r, cols]) => {
    //         Object.entries(cols).forEach(([c, style]) => {
    //             sheet[0].setStyle(+r, +c, style);
    //         });
    //     });
    // });


    // excelInstance = sheet;

}

/**
 * Función para guardar el multipresupuesto desde Excel
 * 
 * @return void
 */
function guardarMultipresupuestoExcel()
{
    // let dataExcel = excelInstance[0].getData();

    // // Validamos que haya datos
    // let datosValidos = dataExcel.some(row => row.some(cell => cell !== null && cell !== ''));

    // if (!datosValidos) {
    //     const toastError = Swal.mixin({
    //         toast: true,
    //         position: 'top-end',
    //         showConfirmButton: false,
    //         timer: 3000,
    //         timerProgressBar: true,
    //     });

    //     toastError.fire({
    //         icon: 'error',
    //         title: 'No hay datos para guardar en el multipresupuesto.'
    //     });

    //     return;
    // }

    const fileInput = document.getElementById('FileDocAddP');
    const file = fileInput.files[0];
    const files = fileInput.files;
    
    if (!selectedFilesP || selectedFilesP.length === 0) {
        $('#labelFileDocAddP').addClass('is-invalid');
        return;
    } else {
        $('#labelFileDocAddP').removeClass('is-invalid');
    }

    let formData = new FormData();
    formData.append('_token', CSRF_TOKEN);
    formData.append('_method', 'POST');
    formData.append('idOportunidad', $('#idOportunidad').val());
    for (let i = 0; i < selectedFilesP.length; i++) {
        formData.append('files[]', selectedFilesP[i]);
    }

    formData.append('valorOportunidad', obtenerValorOportunidadNumerico());

    // formData.append('dataExcel', JSON.stringify(dataExcel));

    $.ajax({
        url: serve + "/guardar-multipresupuesto-excel",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(resp) {
            if (resp.response) {
                const toastSuccess = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });

                toastSuccess.fire({
                    icon: 'success',
                    title: resp.message || 'Multipresupuesto guardado exitosamente.'
                });

                $('#mod_multipresupuesto_excel').modal('hide');

                $('#valorOportunidad').val(obtenerValorOportunidadNumerico());
                $('#Valor').val(obtenerValorOportunidadNumerico());
                $('#valorOport').val('');

                // Destruir la instancia de Excel
                // if (excelInstance) {
                //     excelInstance = null;
                // }

                // Limpiar el formulario
                $('#FileDocAddP').val('');
                $('#previewArchivoP').html('');
                selectedFilesP = [];

                // Simula el click en la pestaña de documentos
                $('#documentos-oport-tab').trigger('click');
            } else {
                Swal.fire('Error', resp.message || 'No se pudo guardar el multipresupuesto.', 'error');
            }
        },
        error: function() {
            Swal.fire('Error', 'Ocurrió un error al guardar el multipresupuesto.', 'error');
        }
    });
}

/**
 * Función para cerrar el modal de multipresupuesto desde Excel
 * 
 * @return void
 */
function accionCerrarModalCargaMultiPres()
{
    $('#mod_multipresupuesto_excel').modal('hide');
    limpiarArchivosMultipresupuesto();
}

/**
 * Función para abrir el modal y seleccionar una cantidad de la tripa
 * 
 * @param {number} id_documento
 * @return void
 */
function abrirModalSeleccionarCantidadTripa(id_documento)
{
    let formData = new FormData();
    formData.append('_token', CSRF_TOKEN);
    formData.append('_method', 'POST');
    formData.append('idOportunidad', $('#idOportunidad').val());
    formData.append('idDocumento', id_documento);

    $.ajax({
        url: serve + "obtener-cantidades-tripa",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(resp) {
            if (resp.response) {
                const formatNum = n => n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                const checkboxesHtml = resp.cantidades.map((cantidad, i) => `
                    <label class="swal-qty-item" style="display:flex;align-items:center;gap:10px;padding:8px 12px;margin:4px 0;border-radius:8px;cursor:pointer;border:1.5px solid #e0e0e0;transition:background .15s,border-color .15s;">
                        <input type="checkbox" class="swal-qty-check" value="${cantidad}" id="qty_${i}"
                            style="width:18px;height:18px;accent-color:#3085d6;cursor:pointer;flex-shrink:0;">
                        <span style="font-size:15px;font-weight:500;color:#333;">${formatNum(cantidad)}</span>
                    </label>`).join('');

                Swal.fire({
                    title: 'Seleccionar cantidades para Prepedido',
                    html: `
                        <div style="text-align:left;max-height:280px;overflow-y:auto;padding:4px 2px;">
                            ${checkboxesHtml}
                        </div>
                        <div style="margin-top:14px;padding:10px 14px;background:#f0f7ff;border-radius:8px;border:1.5px solid #3085d6;display:flex;justify-content:space-between;align-items:center;">
                            <span style="font-size:13px;color:#555;font-weight:500;">Total seleccionado:</span>
                            <span id="swal-qty-total" style="font-size:18px;font-weight:700;color:#3085d6;">0</span>
                        </div>`,
                    showCancelButton: true,
                    confirmButtonText: 'Actualizar Estatus',
                    cancelButtonText: 'Cancelar',
                    didOpen: () => {
                        const updateTotal = () => {
                            const total = [...document.querySelectorAll('.swal-qty-check:checked')]
                                .reduce((sum, cb) => sum + Number(cb.value), 0);
                            document.getElementById('swal-qty-total').textContent = formatNum(total);
                        };

                        document.querySelectorAll('.swal-qty-item').forEach(label => {
                            const cb = label.querySelector('input');
                            cb.addEventListener('change', () => {
                                label.style.background = cb.checked ? '#e8f4fd' : '';
                                label.style.borderColor = cb.checked ? '#3085d6' : '#e0e0e0';
                                updateTotal();
                            });
                        });
                    },
                    preConfirm: () => {
                        const checked = [...document.querySelectorAll('.swal-qty-check:checked')];
                        if (checked.length === 0) {
                            Swal.showValidationMessage('Por favor selecciona al menos una cantidad.');
                            return false;
                        }
                        return checked.reduce((sum, cb) => sum + Number(cb.value), 0);
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        actualizarEstatusOportPrepedido(id_documento, result.value);
                    }
                });
            } else {
                Swal.fire('Error', resp.message || 'No se pudo obtener las cantidades.', 'error');
            }
        }
    });

}

/**
 * Funcion para actualizar el estatus de la oportunidad a prepedido
 * 
 * @param {number} id_documento
 * @param {number} cantidad
 * @return void
 */
function actualizarEstatusOportPrepedido(id_documento, cantidad)
{
    let formData = new FormData();
    formData.append('_token', CSRF_TOKEN);
    formData.append('_method', 'POST');
    formData.append('idOportunidad', $('#idOportunidad').val());
    formData.append('idDocumento', id_documento);
    formData.append('cantidad_preprensa', cantidad);

    $.ajax({
        url: serve + "actualizar-estatus-oport-prepedido",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(resp) {
            if (resp.response) {
                const toastSuccess = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });

                toastSuccess.fire({
                    icon: 'success',
                    title: resp.message || 'Estatus actualizado a Prepedido exitosamente.'
                });

                if (resp.refresh) {
                    location.reload();
                } else {
                    generarListaDocumentos();
                }
            }
        },
        error: function() {
            Swal.fire('Error', 'Ocurrió un error al actualizar el estatus.', 'error');
        }
    });
}

/**
 * Funcion para mostrar la cantidad seleccionada de prepedido
 * 
 * @param {number} cantidad
 * @return void
 */
function abrirSwalCantidadPrepedido(cantidad)
{
    Swal.fire({
        title: 'Cantidad Prepedido',
        text: `La cantidad seleccionada para Prepedido es: ${cantidad}`,
        icon: 'info',
    });
}

/**
 * Función para mostrar el modal del checklist de la oportunidad. Se usa como
 * requisito previo antes de continuar con otras acciones (p. ej. enviar a
 * producción), invocando onFinalizado una vez que el checklist se completa.
 *
 * @param {number} id_documento
 * @param {number} idOportunidad
 * @param {Object} data
 * @param {Function} onFinalizado Función a ejecutar una vez finalizado el checklist
 * @return void
 */
function abrirModalChecklistOP(id_documento, idOportunidad, data, onFinalizado)
{
    let filasHtml = data.preguntas.map(function(pregunta, index) {
        let colorFila = (index % 2 === 0) ? '#F4F6F9' : '#FFFFFF';

        return `
            <tr style="background-color: ${colorFila};">
                <td style="padding: 8px; text-align: left; font-weight: 600; color: #212529; border: 1px solid #dee2e6;">${index + 1}. ${pregunta.pregunta}</td>
                <td style="width: 70px; padding: 0; height: 46px; border: 1px solid #dee2e6;">
                    <input type="checkbox" class="checklist-op-checkbox" data-id-pregunta="${pregunta.id}" ${pregunta.marcado ? 'checked' : ''} style="display: block; width: 100%; height: 100%; margin: 0; padding: 0; accent-color: #3085d6; cursor: pointer;">
                </td>
            </tr>
        `;
    }).join('');

    let tablaHtml = `
        <div style="max-height: 400px; overflow-y: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr class="bg-gray">
                        <th style="padding: 8px; text-align: left; color: #fff; border: 1px solid #6C757D;">Pregunta</th>
                        <th style="width: 70px; padding: 8px; text-align: center; color: #fff; border: 1px solid #6C757D;">Confirmado</th>
                    </tr>
                </thead>
                <tbody>
                    ${filasHtml}
                </tbody>
            </table>
        </div>
    `;

    let actualizarBotonContinuar = function() {
        let totalPreguntas = data.preguntas.length;
        let totalMarcadas = $('.checklist-op-checkbox:checked').length;

        // Se oculta/muestra el botón directamente (sin Swal.update) para no
        // re-renderizar el html y así no perder los checkboxes ya marcados
        // ni sus eventos.
        $(Swal.getConfirmButton()).toggle(totalMarcadas === totalPreguntas);
    };

    Swal.fire({
        title: 'Checklist de confirmación',
        html: `<div id="checklist-op-contenedor">${tablaHtml}</div>`,
        width: 700,
        showCancelButton: true,
        showConfirmButton: false,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Continuar',
        cancelButtonText: 'Cancelar',
        didOpen: () => {
            $('.checklist-op-checkbox').on('change', actualizarBotonContinuar);

            actualizarBotonContinuar();
        },
        preConfirm: () => {
            let respuestas = data.preguntas.map(function(pregunta) {
                return {
                    idPregunta: pregunta.id,
                    marcado: $(`.checklist-op-checkbox[data-id-pregunta="${pregunta.id}"]`).is(':checked') ? 1 : 0
                };
            });

            let formData = new FormData();
            formData.append('_token', CSRF_TOKEN);
            formData.append('_method', 'POST');
            formData.append('idChecklistOP', data.idChecklistOP);
            formData.append('respuestas', JSON.stringify(respuestas));

            return fetch(serve + "guardar-checklist-op", {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(resp => {
                if (!resp.response) {
                    Swal.showValidationMessage(resp.message || 'Ocurrió un error al guardar el checklist.');
                }
                return resp;
            })
            .catch(() => {
                Swal.showValidationMessage('Error en la validación del servidor.');
            });
        }
    }).then((result) => {
        if (result.isConfirmed && result.value && result.value.response && typeof onFinalizado === 'function') {
            onFinalizado(id_documento);
        }
    });
}

/**
 * Función para mostrar las respuestas ya registradas del checklist de un documento
 *
 * @param {number} id_documento
 * @return void
 */
function mostrarChecklist(id_documento)
{
    let idOportunidad = $('#idOportunidad').val();

    $.ajax({
        url: serve + "ver-checklist-op",
        type: "GET",
        data: {
            idDocumento: id_documento,
            idOportunidad: idOportunidad
        },
        success: function(resp) {
            if (resp.response) {
                let filasHtml = resp.respuestas.map(function(item, index) {
                    let colorFila = (index % 2 === 0) ? '#F4F6F9' : '#FFFFFF';

                    return `
                        <tr style="background-color: ${colorFila};">
                            <td style="padding: 8px; text-align: left; font-weight: 600; color: #212529; border: 1px solid #dee2e6;">${index + 1}. ${item.pregunta}</td>
                            <td style="width: 70px; padding: 8px; text-align: center; color: #212529; border: 1px solid #dee2e6;">${item.respuesta ? 'Sí' : 'No'}</td>
                        </tr>
                    `;
                }).join('');

                let usuarioFinalizo = resp.usuarioFinalizo || 'N/D';
                let fechaFinalizo = resp.fechaFinalizo || 'N/D';

                if (fechaFinalizo !== 'N/D') {
                    // Formateamos la fecha a formato legible
                    let fecha = new Date(fechaFinalizo);
                    fechaFinalizo = fecha.toLocaleString('es-MX', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit'
                    });
                }

                Swal.fire({
                    title: 'Checklist de la oportunidad',
                    html: `
                        <div style="text-align: left; color: #fff; font-weight: 600; margin-bottom: 10px;">
                            Realizado por: ${usuarioFinalizo} &nbsp;|&nbsp; Fecha: ${fechaFinalizo}
                        </div>
                        <div style="max-height: 400px; overflow-y: auto;">
                            <table style="width: 100%; border-collapse: collapse;">
                                <thead>
                                    <tr class="bg-gray">
                                        <th style="padding: 8px; text-align: left; color: #fff; border: 1px solid #6C757D;">Pregunta</th>
                                        <th style="width: 70px; padding: 8px; text-align: center; color: #fff; border: 1px solid #6C757D;">Confirmado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${filasHtml}
                                </tbody>
                            </table>
                        </div>
                    `,
                    width: 700,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Cerrar'
                });
            } else {
                Swal.fire('Aviso', resp.message || 'No se encontró un checklist registrado para este documento.', 'info');
            }
        },
        error: function() {
            Swal.fire('Error', 'Ocurrió un error al consultar el checklist.', 'error');
        }
    });
}

/**
 * Función para asignar folio de preprensa y liberar
 *
 * @param {number} id_documento
 * @return void
 */
function asignarFolioPP(id_documento)
{
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Asígnar el folio de preprensa y liberar la tripa.",
        input: 'text',
        inputLabel: 'Ingresa el folio de preprensa',
        inputPlaceholder: 'Folio de preprensa',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, asignar folio',
        cancelButtonText: 'Cancelar',
        preConfirm: (value) => {
            if (!value) {
                Swal.showValidationMessage('Por favor ingresa el folio de preprensa.');
            }

            return fetch(serve + `/validar-folio-metrics/${value}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
            })
            .then(response => response.json())
            .then(data => {
                if (!data.existe) {
                    Swal.showValidationMessage('No es un folio de preprensa válido.');
                }
                return data.folio;
            })
            .catch(() => {
                Swal.showValidationMessage('Error en la validación del servidor.');
            });
        }
    }).then((result) => {
        if (result.isConfirmed) {
            let folio_preprensa = result.value;

            let formData = new FormData();
            formData.append('_token', CSRF_TOKEN);
            formData.append('_method', 'POST');
            formData.append('idDocumento', id_documento);
            formData.append('folio_preprensa', folio_preprensa);

            $.ajax({
                url: serve + "asignar-folio-preprensa-liberar",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(resp) {
                    if (resp.response) {
                        const toastSuccess = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                        });

                        toastSuccess.fire({
                            icon: 'success',
                            title: resp.message || 'Folio de preprensa asignado y tripa liberada exitosamente.'
                        });

                        generarListaDocumentos();

                        // verificamos si el radio aun no esta chequeado para habilitarlo
                        // if (!$('#idStatusPPConcluida').is(':checked')) {
                        //     // Habilitamos el radio para poder finalizar la preprensa
                        //     $('#idStatusPPConcluida').prop('disabled', false);

                        //     // Damos click en el tab de edición de oportunidad
                        //     $('#general-oport-tab').trigger('click');
                        // }
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Ocurrió un error al asignar el folio de preprensa y liberar la tripa.', 'error');
                }
            });
        }
    });
}

/**
 * Funcion para mostrar el folio de preprensa asignado
 * 
 * @param {string} folio_preprensa
 * @return void
 */
function abrirSwalFolioPP(folio_preprensa)
{
    Swal.fire({
        title: 'Folio de Preprensa',
        text: `El folio de preprensa asignado es: ${folio_preprensa}`,
        icon: 'info',
    });
}

/**
 * Funcion para ver la nota de producción
 * 
 * @param {string} id_documento
 * @return void
 */
function showNotaProduccion(id_documento)
{
    let formData = new FormData();
    formData.append('_token', CSRF_TOKEN);
    formData.append('_method', 'POST');
    formData.append('idDocumento', id_documento);

    $.ajax({
        url: serve + "obtener-nota-produccion",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(resp) {
            if (resp.response) {
                Swal.fire({
                    title: 'Nota de Producción',
                    text: resp.nota_produccion,
                    icon: 'info',
                    customClass: {
                        popup: 'swal2-modal-small'
                    }
                });
            } else {
                Swal.fire('Error', 'No se pudo obtener la nota de producción.', 'error');
            }
        },
        error: function() {
            Swal.fire('Error', 'Ocurrió un error al obtener la nota de producción.', 'error');
        }
    });
}

/**
 * Funcion para rechazar tripa (PREPRENSA)
 * 
 * @param {number} id_documento
 * @return void
 */
function rechazarTripaPreprensa(id_documento)
{
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción rechazará la tripa en preprensa.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, rechazar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            let formData = new FormData();
            formData.append('_token', CSRF_TOKEN);
            formData.append('_method', 'POST');
            formData.append('idDocumento', id_documento);
            formData.append('idOportunidad', $('#idOportunidad').val());

            $.ajax({
                url: serve + "rechazar-tripa-preprensa",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(resp) {
                    if (resp.response) {
                        const toastSuccess = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                        });

                        toastSuccess.fire({
                            icon: 'success',
                            title: 'Tripa de preprensa rechazada y regresada a "Autorizadas S/Cambios" exitosamente.'
                        });

                        generarListaDocumentos();

                        if (resp.refresh) {
                            location.reload();
                        }
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Ocurrió un error al rechazar la tripa de preprensa.', 'error');
                }
            })
        }
    });
}

/**
 * Funcion para rechazar tripa (PROD)
 * 
 * @param {number} id_documento
 * @return void
 */
function rechazarTripaProd(id_documento)
{
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción rechazará la tripa en producción.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, rechazar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            let formData = new FormData();
            formData.append('_token', CSRF_TOKEN);
            formData.append('_method', 'POST');
            formData.append('idDocumento', id_documento);
            formData.append('idOportunidad', $('#idOportunidad').val());

            $.ajax({
                url: serve + "rechazar-tripa-produccion",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(resp) {
                    if (resp.response) {
                        const toastSuccess = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                        });

                        toastSuccess.fire({
                            icon: 'success',
                            title: 'Tripa de producción rechazada y regresada a "Tripas p/Preprensa" exitosamente.'
                        });

                        generarListaDocumentos();

                        if (resp.refresh) {
                            location.reload();
                        }
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Ocurrió un error al rechazar la tripa en producción.', 'error');
                }
            })
        }
    });
}

function formatearValorOportunidadInput(valor) {
    if (valor === null || typeof valor === 'undefined') {
        return '';
    }

    let limpio = String(valor).replace(/[^\d.,]/g, '');

    if (limpio === '') {
        return '';
    }

    // Las comas se usan como separadores de miles para mantener formato tipo 1,111,111.11
    limpio = limpio.replace(/,/g, '');

    const terminaEnPunto = limpio.endsWith('.');

    const partesCrudas = limpio.split('.');
    let enteroCrudo = partesCrudas.shift() || '';
    let decimalesCrudos = partesCrudas.join('');

    let entero = enteroCrudo.replace(/^0+(?=\d)/, '');
    const decimales = decimalesCrudos.substring(0, 2);

    if (entero === '') {
        entero = '0';
    }

    entero = entero.replace(/\B(?=(\d{3})+(?!\d))/g, ',');

    if (terminaEnPunto) {
        return `${entero}.`;
    }

    return decimales.length > 0 ? `${entero}.${decimales}` : entero;
}

function obtenerValorOportunidadNumerico() {
    const valorCapturado = $('#valorOport').val() || '';
    const limpio = valorCapturado.replace(/[^\d.]/g, '');
    return limpio;
}

$(document).off('input', '#valorOport').on('input', '#valorOport', function() {
    $(this).val(formatearValorOportunidadInput($(this).val()));
});