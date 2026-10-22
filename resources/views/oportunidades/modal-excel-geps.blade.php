<div class="modal fade" id="mod_eps" tabindex="-1" role="dialog" aria-labelledby="mod_epsLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Cargar Excel de Elementos Presupuestados</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {{--  <div class="modal-body centered-cells">
                <div id="excelSheet"></div>
            </div>  --}}

            <div class="modal-body">
                <div class="form-group">
                    <div class="custom-file" id="inputFileDocAddEPS">
                        <input type="file" class="custom-file-input" id="FileDocAddEPS" multiple accept="image/jpeg,image/png">
                        <label class="custom-file-label" for="FileDocAddEPS" id="labelFileDocAddEPS">Elegir archivo</label>
                    </div>
                    <small id="helpFileDocAddEPS" class="form-text text-muted">
                        Las imagenes se acomodarán de acuerdo al orden en que se seleccionen.
                    </small>
                    {{--  <div class="custom-control custom-checkbox mt-2" id="seccion-sol-prepedido">
                        <input type="checkbox" class="custom-control-input" id="solicitarPrepedidoEPS">
                        <label class="custom-control-label mt-2 pt-1" for="solicitarPrepedidoEPS">Solicitar Pre-pedido</label>
                    </div>  --}}
                    <div id="previewArchivoEPS" class="preview-container"></div>
                </div>
            </div>

            <div class="modal-footer ">
                <button type="button" class="btn btn-success" id="guardarEPExcelBtn" onclick="guardarDocumentosEPS(1)">
                    Guardar EP Excel
                </button>
                <button type="button" class="btn btn-success" id="guardarEPExcelBtn2" onclick="guardarDocumentosEPSConCambios(2)">
                    Guardar EP Excel Con cambios
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="accionCerrarModalCargEP()">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mod_view_eps_files" tabindex="-1" role="dialog" aria-labelledby="mod_view_eps_filesLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="mod_view_eps_filesLabel">Archivos relacionados del grupo</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="d-flex justify-content-between align-items-center mb-2 eps-toolbar" style="gap: 8px; flex-wrap: wrap;">
                    <div class="btn-group" role="group" aria-label="Modo de vista">
                        <button type="button" class="btn btn-sm eps-toolbar-btn eps-toolbar-btn-primary" id="btnModoCarruselEPS">Carrusel</button>
                        <button type="button" class="btn btn-sm eps-toolbar-btn eps-toolbar-btn-neutral" id="btnModoVerticalEPS">Vertical</button>
                        <button type="button" class="btn btn-sm eps-toolbar-btn eps-toolbar-btn-neutral" id="btnModoHorizontalEPS">Horizontal</button>
                    </div>

                    <div class="btn-group" role="group" aria-label="Acciones de descarga">
                        <button type="button" class="btn btn-sm eps-toolbar-btn eps-toolbar-btn-neutral" id="btnDescargarActualEPS">Descargar actual</button>
                        <button type="button" class="btn btn-sm eps-toolbar-btn eps-toolbar-btn-secondary" id="btnDescargarTodosEPS">Descargar todos</button>
                        <button type="button" class="btn btn-sm eps-toolbar-btn eps-toolbar-btn-primary" id="btnDescargarZipEPS">Descargar ZIP</button>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2" style="gap: 10px;">
                    <button type="button" class="btn eps-nav-btn eps-nav-btn-prev" id="btnPrevArchivoEPS" title="Anterior">
                        <i class="fas fa-chevron-left" aria-hidden="true"></i>
                        <span>Anterior</span>
                    </button>
                    <div class="text-center" style="flex: 1;">
                        <h5 id="viewerArchivoEPSNombre" class="m-0">Archivo</h5>
                        <small id="viewerArchivoEPSMeta" class="text-muted"></small>
                    </div>
                    <button type="button" class="btn eps-nav-btn eps-nav-btn-next" id="btnNextArchivoEPS" title="Siguiente">
                        <span>Siguiente</span>
                        <i class="fas fa-chevron-right" aria-hidden="true"></i>
                    </button>
                </div>

                <div id="viewerArchivoEPS" class="d-flex align-items-center justify-content-center" style="min-height: 420px; background: #f7f7f7; border-radius: 8px; padding: 10px;"></div>

                <div class="text-center mt-2">
                    <span id="viewerArchivoEPSCounter" class="badge badge-info">0 / 0</span>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mod_eps_excel_vista" tabindex="-1" role="dialog" aria-labelledby="mod_eps_excel_vistaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="mod_eps_excel_vistaLabel">Cargar Excel EP (Vista)</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <div class="custom-file" id="inputFileDocAddEPSVista">
                        <input type="file" class="custom-file-input" id="FileDocAddEPSVista" accept=".xls,.xlsx,.xlsm,.xlsb,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel.sheet.macroEnabled.12,application/vnd.ms-excel.sheet.binary.macroEnabled.12">
                        <label class="custom-file-label" for="FileDocAddEPSVista" id="labelFileDocAddEPSVista">Elegir archivo</label>
                    </div>
                    <small id="helpFileDocAddEPSVista" class="form-text text-muted">
                        Solo se permite un archivo Excel por carga (.xls, .xlsx, .xlsm, .xlsb).
                    </small>

                    <div id="previewArchivoEPSVista" class="preview-container mt-2"></div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="guardarEPExcelVistaBtn" onclick="guardarDocumentosEPSVista()">
                    Guardar Excel EP
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="cerrarModalExcelEPVista()">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<style>
    #mod_view_eps_files .eps-toolbar {
        padding: 8px;
        border: 1px solid #e4e7eb;
        border-radius: 10px;
        background: #f7f9fc;
    }

    #mod_view_eps_files .eps-toolbar .btn-group {
        gap: 6px;
    }

    #mod_view_eps_files .eps-toolbar-btn {
        border-radius: 999px;
        font-weight: 600;
        letter-spacing: 0.1px;
        border: 1px solid transparent;
        transition: all 0.2s ease;
        padding: 6px 12px;
        box-shadow: 0 1px 2px rgba(16, 24, 40, 0.08);
    }

    #mod_view_eps_files .eps-toolbar-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(16, 24, 40, 0.14);
    }

    #mod_view_eps_files .eps-toolbar-btn-primary {
        background: #1877f2;
        color: #fff;
        border-color: #166fe5;
    }

    #mod_view_eps_files .eps-toolbar-btn-primary:hover,
    #mod_view_eps_files .eps-toolbar-btn-primary:focus {
        background: #166fe5;
        color: #fff;
    }

    #mod_view_eps_files .eps-toolbar-btn-secondary {
        background: #ff9900;
        color: #111827;
        border-color: #f08c00;
    }

    #mod_view_eps_files .eps-toolbar-btn-secondary:hover,
    #mod_view_eps_files .eps-toolbar-btn-secondary:focus {
        background: #f08c00;
        color: #111827;
    }

    #mod_view_eps_files .eps-toolbar-btn-neutral {
        background: #ffffff;
        color: #1f2937;
        border-color: #d0d5dd;
    }

    #mod_view_eps_files .eps-toolbar-btn-neutral:hover,
    #mod_view_eps_files .eps-toolbar-btn-neutral:focus {
        background: #f2f4f7;
        color: #111827;
    }

    #mod_view_eps_files .eps-nav-btn {
        min-width: 116px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border-radius: 999px;
        border: 1px solid #d0d5dd;
        background: #ffffff;
        color: #1f2937;
        font-weight: 600;
        padding: 8px 14px;
        box-shadow: 0 1px 2px rgba(16, 24, 40, 0.08);
        transition: all 0.2s ease;
    }

    #mod_view_eps_files .eps-nav-btn:hover,
    #mod_view_eps_files .eps-nav-btn:focus {
        background: #f8fafc;
        color: #111827;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 24, 40, 0.14);
    }

    #mod_view_eps_files .eps-nav-btn-prev i,
    #mod_view_eps_files .eps-nav-btn-next i {
        color: #ff9900;
        font-size: 12px;
    }

    #mod_view_eps_files .eps-carousel-frame {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        will-change: transform, opacity;
    }

    #mod_view_eps_files .eps-carousel-enter-next {
        animation: epsSlideFromRight 0.24s ease-out;
    }

    #mod_view_eps_files .eps-carousel-enter-prev {
        animation: epsSlideFromLeft 0.24s ease-out;
    }

    #mod_view_eps_files .eps-carousel-enter-fade {
        animation: epsFadeIn 0.2s ease-out;
    }

    @keyframes epsSlideFromRight {
        from {
            opacity: 0;
            transform: translateX(26px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes epsSlideFromLeft {
        from {
            opacity: 0;
            transform: translateX(-26px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes epsFadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    #mod_view_eps_files #viewerArchivoEPS.viewer-carousel-dragging {
        cursor: grabbing;
    }
</style>