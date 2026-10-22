import Alert from "./componentes/alerts/Alert.js";
import Input from "./componentes/forms/Input.js";
import SelectSearch from "./componentes/forms/SelectSearch.js";
import SelectFormApi from "./componentes/forms/SelectApi.js";
import SelectForm from "./componentes/forms/Select.js";
import TextArea from "./componentes/forms/TextArea.js";
import Loader from './componentes/Loader.js';

import CamposGeneral from "./Js/CamposGeneral.js";

import CamposPop from "./Js/CamposPop.js";
import AsignacionPop from "./Js/AsignacionPop.js";

import CamposEmpaque from "./Js/CamposEmpaque.js";
import AsignacionEmpaque from "./Js/AsignacionEmpaque.js";

import CamposDisplay from "./Js/CamposDisplay.js";
import AsignacionDisplay from "./Js/AsignacionDisplay.js";

import CamposMaquila from "./Js/CamposMaquila.js";
import AsignacionMaquila from "./Js/AsignacionMaquila.js";

import CamposComercial from "./Js/CamposComercial.js";
import AsignacionComercial from "./Js/AsignacionComercial.js";

const camposGeneral = CamposGeneral.camposConTipo; // Lista de campos generales a observar

// Lista de campos de comercial a observar
const camposComercial = CamposComercial.camposConTipo;
const camposDisplay = CamposDisplay.camposConTipo;
const camposEmpaque = CamposEmpaque.camposConTipo;
const camposPop = CamposPop.camposConTipo;
const camposMaquila = CamposMaquila.camposConTipo;

new Vue({
    el: '#formElementosEdit',
    data: {
        errors: {
            general: {},
            pop: {},
            empaque: {},
            display: {},
            maquila: {},
            comercial: {},
        },

        baseUrl: window.Laravel.baseUrl,
        oportunidad_edit: '',

        isDisabled: false, // Estado de deshabilitado para el campo de oportunidad

        solicitud: window.solicitud || {}, // Objeto de solicitud, si existe
        folio: window.solicitud ? window.solicitud.folio : '', // Folio de la solicitud, si existe
        loading: false,

        general: CamposGeneral.general, // Importamos los campos del objeto CamposGeneral
        generalDetail: CamposGeneral.generalDetail, // Importamos los campos del objeto CamposGeneral
        tiposProductos: [],

        generalOriginal: window.generalOriginal || {},

        pop: CamposPop.pop, // Importamos los campos del objeto CamposPop
        popCatalogos: CamposPop.popCatalogos, // Importamos el catálogo de POP
        empaque: CamposEmpaque.empaque, // Importamos los campos del objeto CamposEmpaque
        empaqueCatalogos: CamposEmpaque.empaqueCatalogos, // Importamos el catálogo de empaques
        display: CamposDisplay.display, // Importamos los campos del objeto CamposDisplay
        displayCatalogos: CamposDisplay.displayCatalogos, // Importamos el catálogo de displays
        maquila: CamposMaquila.maquila, // Importamos los campos del objeto CamposMaquila
        maquilaCatalogos: CamposMaquila.maquilaCatalogos, // Importamos el catálogo de maquila
        comercial: CamposComercial.comercial, // Importamos los campos del objeto CamposComercial
        comercialCatalogos: CamposComercial.comercialCatalogos, // Importamos el catálogo de comercial

        naEditorialForro: false, // Estado para el checkbox de Editorial Forro
        naEditorialGuarda: false, // Estado para el checkbox de Editorial Guarda
        naAcabadoForro: false, // Estado para el checkbox de Acabado Forro

        naEditorialForroEdit: false, // Estado para el checkbox de Editorial Forro
        naEditorialGuardaEdit: false, // Estado para el checkbox de Editorial Guarda
        naAcabadoForroEdit: false, // Estado para el checkbox de Acabado Forro

        naTacon1: false, // Estado para el checkbox de Tacon 1
        naTacon2: false, // Estado para el checkbox de Tacon 2

        canEditPlacaRealceForro: false, // Estado para permitir editar la placa de realce forro
        fromOport: window.solicitud.fromOport || null, // 1 Crear desde Oportunidad, 2 Editar desde Oportunidad, 3 Registrar cambio de especificaciones
        dataCambios: window.solicitud.dataCambios || null, // Datos de cambios de especificaciones

        isDisabletToAll: true, // Estado para deshabilitar el campo de nombre
        ultimoFolio: '', // Variable para almacenar el último folio
        role: window.solicitud.role || 0, // Rol del usuario, si existe

        esParaCambioTexto: false, // Variable para saber si el cambio de especificaciones es solo de texto o también de campos

        mensaje: '',

        empaqueFormSection: 'general', // Sección del formulario de empaque que se muestra

        emergenciaPuedeVolverAllenar: false,

        // banderas para saber si se realizon un cambio minimo o de especificaciones
        seRealizoCambioMinimo: false,
        seRealizoCambioEspecificaciones: false,

        bitacoraCambiosGeneral: [],
        bitacoraCambiosComercial: [],
        bitacoraCambiosDisplay: [],
        bitacoraCambiosEmpaque: [],
        bitacoraCambiosPop: [],
        bitacoraCambiosMaquila: [],

        camposQueCambiaron: [],

        bitacoraCambios: [],

        observacionesAdicionalesDB: [],

        // Actualizar general
        estatusEnQuePuedeActualizar: [2, 1, 7, 8],

        // Sin solicitud de cambios
        estatusEnQuePuedeActualizarSSC: [2, 1],

        // Con solicitud de cambios
        estatusEnQuePuedeActualizarCS: [7, 8],

        // Tipo de formulario, 1 para cambio de especificaciones, 2 para edición normal
        tipoForm: 2,

        validacionDisabled: false, // Variable para controlar la validación de campos al deshabilitar el formulario
    },
    computed: {
        fechaCreacion() {
            return new Date(this.solicitud.solicitud.fecha_registro).toLocaleDateString("es-MX", { year: "numeric", month: "2-digit", day: "2-digit", hour: "2-digit", minute: "2-digit", second: "2-digit" });
        },
        folioSolicitud() {
            return this.solicitud.solicitud.folio || '';
        },
        estatusOportunidad() {
            return this.solicitud.oportunidad.idEstatus || 0;
        },
    },
    mounted() {
        this.loading = true;

        this.oportunidad_edit = this.solicitud.solicitud.folio_oportunidad + ' - ' + this.solicitud.solicitud.oportunidad || '';
        this.oportEstatus = this.solicitud.oportunidad.idEstatus

        // Colocamos toda la información de la solicitud en los campos generales
        if (this.solicitud.solicitud) {
            CamposGeneral.asignarValoresCampos(this.solicitud.solicitud);
            setTimeout(() => {
                this.esParaCambioTexto = this.solicitud.solicitud.tiene_cambio_texto == 1;
            }, 4000);
        }

        if (this.solicitud.solicitud.plantilla_id == 1) {
            if (this.solicitud.solicitudComercial == undefined) {
                this.emergenciaPuedeVolverAllenar = true;
            }
        }

        if (this.solicitud.solicitud.plantilla_id == 2) {
            if (this.solicitud.solicitudEmpaque == undefined) {
                this.emergenciaPuedeVolverAllenar = true;
            }
        }

        if (this.solicitud.solicitud.plantilla_id == 3) {
            if (this.solicitud.solicitudDisplay == undefined) {
                this.emergenciaPuedeVolverAllenar = true;
            }
        }

        if (this.solicitud.solicitud.plantilla_id == 4) {
            if (this.solicitud.solicitudPop == undefined) {
                this.emergenciaPuedeVolverAllenar = true;
            }
        }

        if (this.solicitud.solicitud.plantilla_id == 6) {
            if (this.solicitud.solicitudMaquila == undefined) {
                this.emergenciaPuedeVolverAllenar = true;
            }
        }

        setTimeout(() => {
            if (this.solicitud.solicitud.plantilla_id == 1) {
                if (this.solicitud.solicitudComercial != undefined) {
                    CamposComercial.asignarValoresCampos(this.solicitud.solicitudComercial);

                    if (this.solicitud.solicitudComercial.na_editorial_forro == 1) {
                        this.naEditorialForro = true;
                    }
                    if (this.solicitud.solicitudComercial.na_editorial_guarda == 1) {
                        this.naEditorialGuarda = true;
                    }
                    if (this.solicitud.solicitudComercial.na_acabado_forro == 1) {
                        this.naAcabadoForro = true;
                    }
                }
                
                this.comercialCatalogos = CamposComercial.comercialCatalogos;
            }

            if (this.solicitud.solicitud.plantilla_id == 2) {
                CamposEmpaque.asignarValoresCampos(this.solicitud.solicitudEmpaque);

                if (this.solicitud.solicitudEmpaque.na_tacon_1 == 1) {
                    this.naTacon1 = true;
                }
                if (this.solicitud.solicitudEmpaque.na_tacon_2 == 1) {
                    this.naTacon2 = true;
                }
            }

            if (this.solicitud.solicitud.plantilla_id == 3) {
                CamposDisplay.asignarValoresCampos(this.solicitud.solicitudDisplay);
            }

            if (this.solicitud.solicitud.plantilla_id == 4) {
                CamposPop.asignarValoresCampos(this.solicitud.solicitudPop);
            }

            if (this.solicitud.solicitud.plantilla_id == 6) {
                CamposMaquila.asignarValoresCampos(this.solicitud.solicitudMaquila);
            }

            this.loading = false;

            // Si hay datos en la bitacora la asignamos
            if (this.solicitud.camposQueCambiaron) {
                const cambios = Array.isArray(this.solicitud.camposQueCambiaron)
                    ? this.solicitud.camposQueCambiaron
                    : Object.values(this.solicitud.camposQueCambiaron || {});
                cambios.forEach(campo => {
                    this.camposQueCambiaron.push(campo);
                });
            }

            if (this.solicitud.bitacoraCambios) {
                this.bitacoraCambios = this.solicitud.bitacoraCambios;
            }

            // Asignamos las observaciones adicionales a la variable observacionesAdicionalesDB
            this.observacionesAdicionalesDB = this.solicitud.observacionesAdicionales || [];

            if (this.observacionesAdicionalesDB.length > 0) {
                // Agregamos las observaciones adicionales en el área de texto
                this.observacionesAdicionalesDB.forEach(observacion => {
                    if (this.solicitud.solicitud.plantilla_id == 1) {
                        this.comercial.observacionesExtras.push(observacion);
                    }
                    if (this.solicitud.solicitud.plantilla_id == 2) {
                        this.empaque.observacionesExtras.push(observacion);
                    }
                    if (this.solicitud.solicitud.plantilla_id == 3) {
                        this.display.observacionesExtras.push(observacion);
                    }
                    if (this.solicitud.solicitud.plantilla_id == 4) {
                        this.pop.observacionesExtras.push(observacion);
                    }
                    if (this.solicitud.solicitud.plantilla_id == 6) {
                        this.maquila.observacionesExtras.push(observacion);
                    }
                });
            }
        }, 6000);

        if (this.fromOport == 1 || this.fromOport == 2) {
            this.isDisabled = true; // Deshabilitar el campo de oportunidad si se viene de una oportunidad
        }

        this.obtenerUltimoFolioRegistrado(); // Obtener el último folio registrado
        if (this.role != 1) {
            if (!this.estatusEnQuePuedeActualizar.includes(parseInt(this.estatusOportunidad)) || (this.role != 4 && this.role != 9)) {
                setTimeout(() => {
                    // Bloqueamos todo el formulario que esta dentro de #comercialForm
                    const generalForm = document.getElementById('generalForm');
                    const comercialForm = document.getElementById('comercialForm');
                    const displayForm = document.getElementById('displayForm');
                    const empaqueForm = document.getElementById('empaqueForm');
                    const maquilaForm = document.getElementById('maquilaForm');
                    const popForm = document.getElementById('popForm');
    
                    if (generalForm) {
                        generalForm.querySelectorAll('input, select, textarea').forEach(element => {
                            element.disabled = true;
                        });
                    }
    
                    if (comercialForm) {
                        comercialForm.querySelectorAll('input, select, textarea').forEach(element => {
                            element.disabled = true;
                        });
                    }
    
                    if (displayForm) {
                        displayForm.querySelectorAll('input, select, textarea').forEach(element => {
                            element.disabled = true;
                        });
                    }
    
                    if (empaqueForm) {
                        empaqueForm.querySelectorAll('input, select, textarea').forEach(element => {
                            element.disabled = true;
                        });
                    }
    
                    if (maquilaForm) {
                        maquilaForm.querySelectorAll('input, select, textarea').forEach(element => {
                            element.disabled = true;
                        });
                    }
    
                    if (popForm) {
                        popForm.querySelectorAll('input, select, textarea').forEach(element => {
                            element.disabled = true;
                        });
                    }
    
                    this.mensaje = this.dataCambios ? this.dataCambios.mensaje : ''; // Asignar el motivo de cambio si existe
    
                }, 2000);
            }
        }

        // let tieneCambiosNoFinalizados = this.solicitud.solicitud.cambio_prioridad == 1 || this.solicitud.solicitud.cambio_prioridad == 2;
        let tieneCambiosNoFinalizados = this.solicitud.oportunidad.preSolicitoCambios == 0 && this.solicitud.oportunidad.finalizoCambios == 0;

        this.validacionDisabled = !(this.estatusEnQuePuedeActualizar.includes(parseInt(this.estatusOportunidad)) && (this.role == 4 || this.role == 9 || this.role == 1) && this.tipoForm == 2) || tieneCambiosNoFinalizados; // Deshabilitar la validación de campos si el usuario no tiene permiso para editar
    },
    watch: {
        'general.oportunidad_id'(newValue) {
            if (newValue) {
                axios.get(`${this.baseUrl}/get-oportunidad`, { params: { idOportunidad: newValue } })
                    .then(response => {
                        const data = response.data;
                        this.generalDetail.vendedor = data.NombreAgente || '';
                        this.generalDetail.contacto = data.NombreContacto || '';
                        this.generalDetail.cliente = data.nomCliente || '';

                        this.general.vendedor_id = data.idAgente || '';
                        this.general.vendedor_abr = data.AgenteAbr || '';
                        this.general.contacto_id = data.idContacto || '';
                        this.general.cliente_id = data.idCliente || '';
                    })
                    .catch(error => {
                        console.log('Error al obtener la oportunidad:', error);
                    });
            }
        },
        'general.cat_EP_tipoProducto_id'(newValue) {
            if (newValue) {
                newValue = newValue.split('-')[1]; // Extraer el ID de la plantilla del valor seleccionado

                if (newValue !== this.general.plantilla_id) {
                    switch (newValue) {
                        case '1': // COMERCIAL
                            this.limpiarCamposComercial();
                            break;
                        case '2': // EMPAQUE
                            this.limpiarCamposEmpaque();
                            break;
                        case '3': // DISPLAY
                            this.limpiarCamposDisplay();
                            break;
                        case '4': // POP
                            this.limpiarCamposPop();
                            break;
                        case '6': // MAQUILA
                            this.limpiarCamposMaquila();
                            break;
                    }
                }

                axios.get(`${this.baseUrl}/get-data-form`, { params: { idPlantilla: newValue, idSolicitud: generalOriginal.solicitud.id } })
                    .then(response => {
                        const data = response.data;

                        // Asignar los datos del formulario a las variables correspondientes
                        switch (newValue) {
                            case '1':
                                this.asignarComercialData(data);
                                this.general.plantilla_id = '1'; // Asignar el ID de la plantilla general
                                break;
                            case '2':
                                this.asignarEmpaqueData(data);
                                this.general.plantilla_id = '2'; // Asignar el ID de la plantilla de empaque
                                break;
                            case '3':
                                this.asignarDisplayData(data);
                                this.general.plantilla_id = '3'; // Asignar el ID de la plantilla de display
                                break;
                            case '4':
                                this.asignarPopData(data);
                                this.general.plantilla_id = '4'; // Asignar el ID de la plantilla de POP
                                break;
                            case '6':
                                this.asignarMaquilaData(data);
                                this.general.plantilla_id = '6';
                        }
                    })
                    .catch(error => {
                        console.log('Error al obtener los datos del formulario:', error);
                    });
            }
        },
        'general.cantidad_cotizar_1'(newValue) {
            // Aseguramos que la cantidad que ingrese sea un entero
            if (newValue !== '' && !Number.isInteger(Number(newValue))) {
                this.general.cantidad_cotizar_1 = Math.floor(Number(newValue));
            }
        },
        'general.cantidad_cotizar_2'(newValue) {
            // Aseguramos que la cantidad que ingrese sea un entero
            if (newValue !== '' && !Number.isInteger(Number(newValue))) {
                this.general.cantidad_cotizar_2 = Math.floor(Number(newValue));
            }
        },
        'general.cantidad_cotizar_3'(newValue) {
            // Aseguramos que la cantidad que ingrese sea un entero
            if (newValue !== '' && !Number.isInteger(Number(newValue))) {
                this.general.cantidad_cotizar_3 = Math.floor(Number(newValue));
            }
        },
        naEditorialForro(newValue) {
            // Si se activa el check todos los campos de Editorial Forro se marcan como no requeridos
            if (newValue) {
                this.comercial.num_paginas_forro = 0;
                this.comercial.sustrato_forro = this.buscarYSeleccionarCampoNA(this.comercialCatalogos.sustratosForro);
                this.comercial.gramaje_forro = this.buscarYSeleccionarCampoNA(this.comercialCatalogos.gramajesForro);
                this.comercial.tinta_frente_forro = this.buscarYSeleccionarCampoNA(this.comercialCatalogos.tintasFrentesForro);
                this.comercial.tinta_vuelta_forro = this.buscarYSeleccionarCampoNA(this.comercialCatalogos.tintasVueltaForro);
                this.comercial.barniz_frente_forro = this.buscarYSeleccionarCampoNA(this.comercialCatalogos.barnicesFrenteForro);
                this.comercial.barniz_vuelta_forro = this.buscarYSeleccionarCampoNA(this.comercialCatalogos.barnicesVueltaForro);
                this.comercial.medida_final_forro = '';
                this.comercial.medida_extendida_forro = '';
                
            } else {
                this.comercial.num_paginas_forro = '';
                this.comercial.sustrato_forro = '';
                this.comercial.gramaje_forro = '';
                this.comercial.tinta_frente_forro = '';
                this.comercial.tinta_vuelta_forro = '';
                this.comercial.barniz_frente_forro = '';
                this.comercial.barniz_vuelta_forro = '';
                this.comercial.medida_final_forro = '';
                this.comercial.medida_extendida_forro = '';
            }
        },
        naEditorialGuarda(newValue) {
            // Si se activa el check todos los campos de Editorial Guarda se marcan como no requeridos
            if (newValue) {
                this.comercial.num_paginas_guardas = 0;
                this.comercial.sustrato_guardas = this.buscarYSeleccionarCampoNA(this.comercialCatalogos.sustratosGuardas);
                this.comercial.gramaje_guardas = this.buscarYSeleccionarCampoNA(this.comercialCatalogos.gramajesGuardas);
                this.comercial.tinta_frente_guardas = this.buscarYSeleccionarCampoNA(this.comercialCatalogos.tintasFrentesGuardas);
                this.comercial.tinta_vuelta_guardas = this.buscarYSeleccionarCampoNA(this.comercialCatalogos.tintasVueltaGuardas);
                this.comercial.barniz_frente_guardas = this.buscarYSeleccionarCampoNA(this.comercialCatalogos.barnicesFrenteGuardas);
                this.comercial.barniz_vuelta_guardas = this.buscarYSeleccionarCampoNA(this.comercialCatalogos.barnicesVueltaGuardas);
                this.comercial.medida_final_guardas = '';
                this.comercial.medida_extendida_guardas = '';
            } else {
                this.comercial.num_paginas_guardas = '';
                this.comercial.sustrato_guardas = '';
                this.comercial.gramaje_guardas = '';
                this.comercial.tinta_frente_guardas = '';
                this.comercial.tinta_vuelta_guardas = '';
                this.comercial.barniz_frente_guardas = '';
                this.comercial.barniz_vuelta_guardas = '';
                this.comercial.medida_final_guardas = '';
                this.comercial.medida_extendida_guardas = '';
            }
        },
        naAcabadoForro(newValue) {
            // Si se activa el check todos los campos de Acabado Forro se marcan como no requeridos
            if (newValue) {
                this.comercial.metalizado_frente_forro = this.buscarYSeleccionarCampoNA(this.comercialCatalogos.metalizadosFrentesForro);
                this.comercial.metalizado_vuelta_forro = this.buscarYSeleccionarCampoNA(this.comercialCatalogos.metalizadosVueltaForro);
                this.comercial.laminado_frente_forro = this.buscarYSeleccionarCampoNA(this.comercialCatalogos.laminadosFrenteForro);
                this.comercial.laminado_vuelta_forro = this.buscarYSeleccionarCampoNA(this.comercialCatalogos.laminadosVueltaForro);
                this.comercial.barniz_sakurai_frente_forro = this.buscarYSeleccionarCampoNA(this.comercialCatalogos.barnicesSakuraiFrenteForro);
                this.comercial.barniz_sakurai_vuelta_forro = this.buscarYSeleccionarCampoNA(this.comercialCatalogos.barnicesSakuraiVueltaForro);
                this.comercial.realce_forro = this.buscarYSeleccionarCampoNA(this.comercialCatalogos.realcesForro);
                this.comercial.hot_stamping_forro = this.buscarYSeleccionarCampoNA(this.comercialCatalogos.hotStampingsForro);
                this.comercial.color_hot_stamping_forro = this.buscarYSeleccionarCampoNA(this.comercialCatalogos.coloresHotStampingForro);
                this.comercial.suaje_forro = this.buscarYSeleccionarCampoNA(this.comercialCatalogos.suajesForro);
                this.comercial.placa_realce_forro = '';
                this.comercial.placa_hot_stamping_forro = '';
                this.comercial.suajado_forro = this.buscarYSeleccionarCampoNA(this.comercialCatalogos.suajadosForroList);
            } else {
                this.comercial.metalizado_frente_forro = '';
                this.comercial.metalizado_vuelta_forro = '';
                this.comercial.laminado_frente_forro = '';
                this.comercial.laminado_vuelta_forro = '';
                this.comercial.barniz_sakurai_frente_forro = '';
                this.comercial.barniz_sakurai_vuelta_forro = '';
                this.comercial.realce_forro = '';
                this.comercial.hot_stamping_forro = '';
                this.comercial.color_hot_stamping_forro = '';
                this.comercial.suaje_forro = '';
                this.comercial.placa_realce_forro = '';
                this.comercial.placa_hot_stamping_forro = '';
                this.comercial.suajado_forro = '';
            }
        },
        naTacon1(newValue) {
            // Si esta activo el check es por que no aplica Tacon 1 y deben de estar vacios los campos relacionados
            if (newValue) {
                this.empaque.sustrato_t1 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.sustratos);
                this.empaque.gramaje_t1 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.gramajes);
                this.empaque.tinta_frente_t1 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.tintasFrente);
                this.empaque.tinta_vuelta_t1 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.tintasVuelta);
                this.empaque.medida_final_t1 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.medidasFinales);
                this.empaque.medida_extendida_t1 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.medidasExtendidas);
                this.empaque.barniz_frente_t1 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.barnicesFrente);
                this.empaque.barniz_vuelta_t1 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.barnicesVuelta);
                this.empaque.sustrato_empalme_t1 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.sustratosEmpalme);
                this.empaque.requiere_ventana_t1 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.requiereVentana);
                this.empaque.puntaje_ventana_t1 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.puntajesVentana);
                this.empaque.otros_empaque_t1 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.otrosEmpaque);
                this.empaque.metalizado_frente_t1 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.metalizadosFrente);
                this.empaque.metalizado_vuelta_t1 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.metalizadosVuelta);
                this.empaque.laminado_frente_t1 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.laminadosFrente);
                this.empaque.laminado_vuelta_t1 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.laminadosVuelta);
                this.empaque.barniz_sakurai_frente_t1 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.barnicesSakuraiFrente);
                this.empaque.barniz_sakurai_vuelta_t1 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.barnicesSakuraiVuelta);
                this.empaque.realce_t1 = '';
                this.empaque.placa_realce_t1 = '';
                this.empaque.hot_stamping_t1 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.hotStampings);
                this.empaque.placa_hot_stamping_t1 = '';
                this.empaque.color_hot_stamping_t1 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.coloresHotStamping);
                this.empaque.suaje_t1 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.suajes);
                this.empaque.suajado_t1 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.suajados);
                this.empaque.otros_acabados_t1 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.otrosAcabados);
            } else {
                this.empaque.sustrato_t1 = '';
                this.empaque.gramaje_t1 = '';
                this.empaque.tinta_frente_t1 = '';
                this.empaque.tinta_vuelta_t1 = '';
                this.empaque.medida_final_t1 = '';
                this.empaque.medida_final_largo_t1 = '';
                this.empaque.medida_final_ancho_t1 = '';
                this.empaque.medida_final_alto_t1 = '';

                this.empaque.medida_extendida_t1 = '';
                this.empaque.medida_extendida_largo_t1 = '';
                this.empaque.medida_extendida_ancho_t1 = '';
                this.empaque.medida_extendida_alto_t1 = '';

                this.empaque.barniz_frente_t1 = '';
                this.empaque.barniz_vuelta_t1 = '';
                this.empaque.sustrato_empalme_t1 = '';
                this.empaque.requiere_ventana_t1 = '';
                this.empaque.puntaje_ventana_t1 = '';
                this.empaque.otros_empaque_t1 = '';
                this.empaque.metalizado_frente_t1 = '';
                this.empaque.metalizado_vuelta_t1 = '';
                this.empaque.laminado_frente_t1 = '';
                this.empaque.laminado_vuelta_t1 = '';
                this.empaque.barniz_sakurai_frente_t1 = '';
                this.empaque.barniz_sakurai_vuelta_t1 = '';
                this.empaque.realce_t1 = '';
                this.empaque.placa_realce_t1 = '';
                this.empaque.hot_stamping_t1 = '';
                this.empaque.placa_hot_stamping_t1 = '';
                this.empaque.color_hot_stamping_t1 = '';
                this.empaque.suaje_t1 = '';
                this.empaque.suajado_t1 = '';
                this.empaque.otros_acabados_t1 = '';
            }
        },
        naTacon2(newValue) {
            // Si esta activo el check es por que no aplica Tacon 2 y deben de estar vacios los campos relacionados
            if (newValue) {
                this.empaque.sustrato_t2 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.sustratos);
                this.empaque.gramaje_t2 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.gramajes);
                this.empaque.tinta_frente_t2 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.tintasFrente);
                this.empaque.tinta_vuelta_t2 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.tintasVuelta);
                this.empaque.medida_final_t2 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.medidasFinales);
                this.empaque.medida_extendida_t2 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.medidasExtendidas);
                this.empaque.barniz_frente_t2 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.barnicesFrente);
                this.empaque.barniz_vuelta_t2 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.barnicesVuelta);
                this.empaque.sustrato_empalme_t2 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.sustratosEmpalme);
                this.empaque.requiere_ventana_t2 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.requiereVentana);
                this.empaque.puntaje_ventana_t2 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.puntajesVentana);
                this.empaque.otros_empaque_t2 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.otrosEmpaque);
                this.empaque.metalizado_frente_t2 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.metalizadosFrente);
                this.empaque.metalizado_vuelta_t2 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.metalizadosVuelta);
                this.empaque.laminado_frente_t2 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.laminadosFrente);
                this.empaque.laminado_vuelta_t2 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.laminadosVuelta);
                this.empaque.barniz_sakurai_frente_t2 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.barnicesSakuraiFrente);
                this.empaque.barniz_sakurai_vuelta_t2 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.barnicesSakuraiVuelta);
                this.empaque.realce_t2 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.realces);
                this.empaque.placa_realce_t2 = '';
                this.empaque.hot_stamping_t2 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.hotStampings);
                this.empaque.placa_hot_stamping_t2 = '';
                this.empaque.color_hot_stamping_t2 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.coloresHotStamping);
                this.empaque.suaje_t2 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.suajes);
                this.empaque.suajado_t2 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.suajados);
                this.empaque.otros_acabados_t2 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.otrosAcabados);
            } else {
                this.empaque.sustrato_t2 = '';
                this.empaque.gramaje_t2 = '';
                this.empaque.tinta_frente_t2 = '';
                this.empaque.tinta_vuelta_t2 = '';
                this.empaque.medida_final_t2 = '';
                this.empaque.medida_final_largo_t2 = '';
                this.empaque.medida_final_ancho_t2 = '';
                this.empaque.medida_final_alto_t2 = '';

                this.empaque.medida_extendida_t2 = '';
                this.empaque.medida_extendida_largo_t2 = '';
                this.empaque.medida_extendida_ancho_t2 = '';
                this.empaque.medida_extendida_alto_t2 = '';

                this.empaque.barniz_frente_t2 = '';
                this.empaque.barniz_vuelta_t2 = '';
                this.empaque.sustrato_empalme_t2 = '';
                this.empaque.requiere_ventana_t2 = '';
                this.empaque.puntaje_ventana_t2 = '';
                this.empaque.otros_empaque_t2 = '';
                this.empaque.metalizado_frente_t2 = '';
                this.empaque.metalizado_vuelta_t2 = '';
                this.empaque.laminado_frente_t2 = '';
                this.empaque.laminado_vuelta_t2 = '';
                this.empaque.barniz_sakurai_frente_t2 = '';
                this.empaque.barniz_sakurai_vuelta_t2 = '';
                this.empaque.realce_t2 = '';
                this.empaque.placa_realce_t2 = '';
                this.empaque.hot_stamping_t2 = '';
                this.empaque.placa_hot_stamping_t2 = '';
                this.empaque.color_hot_stamping_t2 = '';
                this.empaque.suaje_t2 = '';
                this.empaque.suajado_t2 = '';
                this.empaque.otros_acabados_t2 = '';
            }
        },
        'comercial.realce_forro'(newValue) {
            // Si se cambia la placa de realce forro, se permite editar el campo
            if (newValue) {
                this.canEditPlacaRealceForro = true;
            } else {
                this.canEditPlacaRealceForro = false;
            }
        },

        // General watchers
        ...Object.fromEntries(
            camposGeneral.map(([campo, tipo, tipoCampo, categoria, etiqueta]) => [
                `general.${campo}`,
                function(newValue) {
                    if (this.generalOriginal.solicitud && campo in this.generalOriginal.solicitud) {
                        let original = this.generalOriginal.solicitud[campo];
                        let nuevo = newValue;

                        if (!isNaN(original) && !isNaN(nuevo) && original !== '' && nuevo !== '') {
                            original = Math.floor(Number(original));
                            nuevo = Math.floor(Number(nuevo));
                        }

                        const cambio = original != nuevo;

                        if (tipo === 'especificaciones') {
                            this.seRealizoCambioEspecificaciones = cambio;
                        } else if (tipo === 'minimos') {
                            this.seRealizoCambioMinimo = cambio;
                        }

                        // Bitácora de COMERCIAL
                        if (cambio) {
                            const yaExiste = this.bitacoraCambiosGeneral.some(
                                cambio => cambio.campo === campo && cambio.tipo === tipo && cambio.tipoCampo === tipoCampo && cambio.categoria === categoria
                            );
                            if (!yaExiste) {
                                this.bitacoraCambiosGeneral.push({
                                    tipo,
                                    campo,
                                    valorAnterior: this.generalOriginal.solicitud[campo],
                                    valorNuevo: newValue,
                                    tipoCampo,
                                    categoria,
                                    campoEtiqueta: etiqueta
                                });
                            } else {
                                this.bitacoraCambiosGeneral = this.bitacoraCambiosGeneral.map(cambio =>
                                    (cambio.campo === campo && cambio.tipo === tipo && cambio.tipoCampo === tipoCampo && cambio.categoria === categoria)
                                        ? { ...cambio, valorNuevo: newValue }
                                        : cambio
                                );
                            }
                        } else {
                            this.bitacoraCambiosGeneral = this.bitacoraCambiosGeneral.filter(
                                cambio => !(cambio.campo === campo && cambio.tipo === tipo && cambio.tipoCampo === tipoCampo && cambio.categoria === categoria)
                            );
                        }
                    }
                }
            ])
        ),

        // Comercial watchers
        ...Object.fromEntries(
            camposComercial.map(([campo, tipo, tipoCampo, categoria, etiqueta]) => [
                `comercial.${campo}`,
                function(newValue) {
                    if (this.generalOriginal.solicitudComercial && campo in this.generalOriginal.solicitudComercial) {
                        let original = this.generalOriginal.solicitudComercial[campo];
                        let nuevo = newValue;

                        if (!isNaN(original) && !isNaN(nuevo) && original !== '' && nuevo !== '') {
                            original = Math.floor(Number(original));
                            nuevo = Math.floor(Number(nuevo));
                        }

                        const cambio = original != nuevo;

                        if (tipo === 'especificaciones') {
                            this.seRealizoCambioEspecificaciones = cambio;
                        } else if (tipo === 'minimos') {
                            this.seRealizoCambioMinimo = cambio;
                        }

                        // Bitácora de COMERCIAL
                        if (cambio) {
                            const yaExiste = this.bitacoraCambiosComercial.some(
                                cambio => cambio.campo === campo && cambio.tipo === tipo && cambio.tipoCampo === tipoCampo && cambio.categoria === categoria
                            );
                            if (!yaExiste) {
                                this.bitacoraCambiosComercial.push({
                                    tipo,
                                    campo,
                                    valorAnterior: this.generalOriginal.solicitudComercial[campo],
                                    valorNuevo: newValue,
                                    tipoCampo,
                                    categoria,
                                    campoEtiqueta: etiqueta
                                });
                            } else {
                                this.bitacoraCambiosComercial = this.bitacoraCambiosComercial.map(cambio =>
                                    (cambio.campo === campo && cambio.tipo === tipo && cambio.tipoCampo === tipoCampo && cambio.categoria === categoria)
                                        ? { ...cambio, valorNuevo: newValue }
                                        : cambio
                                );
                            }
                        } else {
                            this.bitacoraCambiosComercial = this.bitacoraCambiosComercial.filter(
                                cambio => !(cambio.campo === campo && cambio.tipo === tipo && cambio.tipoCampo === tipoCampo && cambio.categoria === categoria)
                            );
                        }
                    }
                }
            ])
        ),

        // Display watchers
        ...Object.fromEntries(
            camposDisplay.map(([campo, tipo, tipoCampo, categoria, etiqueta]) => [
                `display.${campo}`,
                function(newValue) {
                    if (this.generalOriginal.solicitudDisplay && campo in this.generalOriginal.solicitudDisplay) {
                        let original = this.generalOriginal.solicitudDisplay[campo];
                        let nuevo = newValue;

                        if (!isNaN(original) && !isNaN(nuevo) && original !== '' && nuevo !== '') {
                            original = Math.floor(Number(original));
                            nuevo = Math.floor(Number(nuevo));
                        }

                        const cambio = original != nuevo;

                        if (tipo === 'especificaciones') {
                            this.seRealizoCambioEspecificaciones = cambio;
                        } else if (tipo === 'minimos') {
                            this.seRealizoCambioMinimo = cambio;
                        }

                        // Bitácora de DISPLAY
                        if (cambio) {
                            const yaExiste = this.bitacoraCambiosDisplay.some(
                                cambio => cambio.campo === campo && cambio.tipo === tipo && cambio.tipoCampo === tipoCampo && cambio.categoria === categoria
                            );
                            if (!yaExiste) {
                                this.bitacoraCambiosDisplay.push({
                                    tipo,
                                    campo,
                                    valorAnterior: this.generalOriginal.solicitudDisplay[campo],
                                    valorNuevo: newValue,
                                    tipoCampo,
                                    categoria,
                                    campoEtiqueta: etiqueta
                                });
                            } else {
                                this.bitacoraCambiosDisplay = this.bitacoraCambiosDisplay.map(cambio =>
                                    (cambio.campo === campo && cambio.tipo === tipo && cambio.tipoCampo === tipoCampo && cambio.categoria === categoria)
                                        ? { ...cambio, valorNuevo: newValue }
                                        : cambio
                                );
                            }
                        } else {
                            this.bitacoraCambiosDisplay = this.bitacoraCambiosDisplay.filter(
                                cambio => !(cambio.campo === campo && cambio.tipo === tipo && cambio.tipoCampo === tipoCampo && cambio.categoria === categoria)
                            );
                        }
                    }
                }
            ])
        ),
        
        // Empaque watchers
        ...Object.fromEntries(
            camposEmpaque.map(([campo, tipo, tipoCampo, categoria, etiqueta]) => [
                `empaque.${campo}`,
                function(newValue) {
                    if (this.generalOriginal.solicitudEmpaque && campo in this.generalOriginal.solicitudEmpaque) {
                        let original = this.generalOriginal.solicitudEmpaque[campo];
                        let nuevo = newValue;

                        if (!isNaN(original) && !isNaN(nuevo) && original !== '' && nuevo !== '') {
                            original = Math.floor(Number(original));
                            nuevo = Math.floor(Number(nuevo));
                        }

                        const cambio = original != nuevo;

                        if (tipo === 'especificaciones') {
                            this.seRealizoCambioEspecificaciones = cambio;
                        } else if (tipo === 'minimos') {
                            this.seRealizoCambioMinimo = cambio;
                        }

                        // Bitácora de Empaque
                        if (cambio) {
                            const yaExiste = this.bitacoraCambiosEmpaque.some(
                                cambio => cambio.campo === campo && cambio.tipo === tipo && cambio.tipoCampo === tipoCampo && cambio.categoria === categoria
                            );
                            if (!yaExiste) {
                                this.bitacoraCambiosEmpaque.push({
                                    tipo,
                                    campo,
                                    valorAnterior: this.generalOriginal.solicitudEmpaque[campo],
                                    valorNuevo: newValue,
                                    tipoCampo,
                                    categoria,
                                    campoEtiqueta: etiqueta
                                });
                            } else {
                                this.bitacoraCambiosEmpaque = this.bitacoraCambiosEmpaque.map(cambio =>
                                    (cambio.campo === campo && cambio.tipo === tipo && cambio.tipoCampo === tipoCampo && cambio.categoria === categoria)
                                        ? { ...cambio, valorNuevo: newValue }
                                        : cambio
                                );
                            }
                        } else {
                            this.bitacoraCambiosEmpaque = this.bitacoraCambiosEmpaque.filter(
                                cambio => !(cambio.campo === campo && cambio.tipo === tipo && cambio.tipoCampo === tipoCampo && cambio.categoria === categoria)
                            );
                        }
                    }
                }
            ])
        ),
        
        // Pop watchers
        ...Object.fromEntries(
            camposPop.map(([campo, tipo, tipoCampo, categoria, etiqueta]) => [
                `pop.${campo}`,
                function(newValue) {
                    if (this.generalOriginal.solicitudPop && campo in this.generalOriginal.solicitudPop) {
                        let original = this.generalOriginal.solicitudPop[campo];
                        let nuevo = newValue;

                        if (!isNaN(original) && !isNaN(nuevo) && original !== '' && nuevo !== '') {
                            original = Math.floor(Number(original));
                            nuevo = Math.floor(Number(nuevo));
                        }

                        const cambio = original != nuevo;

                        if (tipo === 'especificaciones') {
                            this.seRealizoCambioEspecificaciones = cambio;
                        } else if (tipo === 'minimos') {
                            this.seRealizoCambioMinimo = cambio;
                        }

                        // Bitácora de Pop
                        if (cambio) {
                            const yaExiste = this.bitacoraCambiosPop.some(
                                cambio => cambio.campo === campo && cambio.tipo === tipo && cambio.tipoCampo === tipoCampo && cambio.categoria === categoria
                            );
                            if (!yaExiste) {
                                this.bitacoraCambiosPop.push({
                                    tipo,
                                    campo,
                                    valorAnterior: this.generalOriginal.solicitudPop[campo],
                                    valorNuevo: newValue,
                                    tipoCampo,
                                    categoria,
                                    campoEtiqueta: etiqueta
                                });
                            } else {
                                this.bitacoraCambiosPop = this.bitacoraCambiosPop.map(cambio =>
                                    (cambio.campo === campo && cambio.tipo === tipo && cambio.tipoCampo === tipoCampo && cambio.categoria === categoria)
                                        ? { ...cambio, valorNuevo: newValue }
                                        : cambio
                                );
                            }
                        } else {
                            this.bitacoraCambiosPop = this.bitacoraCambiosPop.filter(
                                cambio => !(cambio.campo === campo && cambio.tipo === tipo && cambio.tipoCampo === tipoCampo && cambio.categoria === categoria)
                            );
                        }
                    }
                }
            ])
        ),
        
        // Maquila watchers
        ...Object.fromEntries(
            camposMaquila.map(([campo, tipo, tipoCampo, categoria, etiqueta]) => [
                `maquila.${campo}`,
                function(newValue) {
                    if (this.generalOriginal.solicitudMaquila && campo in this.generalOriginal.solicitudMaquila) {
                        let original = this.generalOriginal.solicitudMaquila[campo];
                        let nuevo = newValue;

                        if (!isNaN(original) && !isNaN(nuevo) && original !== '' && nuevo !== '') {
                            original = Math.floor(Number(original));
                            nuevo = Math.floor(Number(nuevo));
                        }

                        const cambio = original != nuevo;

                        if (tipo === 'especificaciones') {
                            this.seRealizoCambioEspecificaciones = cambio;
                        } else if (tipo === 'minimos') {
                            this.seRealizoCambioMinimo = cambio;
                        }

                        // Bitácora de Maquila
                        if (cambio) {
                            const yaExiste = this.bitacoraCambiosMaquila.some(
                                cambio => cambio.campo === campo && cambio.tipo === tipo && cambio.tipoCampo === tipoCampo && cambio.categoria === categoria
                            );
                            if (!yaExiste) {
                                this.bitacoraCambiosMaquila.push({
                                    tipo,
                                    campo,
                                    valorAnterior: this.generalOriginal.solicitudMaquila[campo],
                                    valorNuevo: newValue,
                                    tipoCampo,
                                    categoria,
                                    campoEtiqueta: etiqueta
                                });
                            } else {
                                this.bitacoraCambiosMaquila = this.bitacoraCambiosMaquila.map(cambio =>
                                    (cambio.campo === campo && cambio.tipo === tipo && cambio.tipoCampo === tipoCampo && cambio.categoria === categoria)
                                        ? { ...cambio, valorNuevo: newValue }
                                        : cambio
                                );
                            }
                        } else {
                            this.bitacoraCambiosMaquila = this.bitacoraCambiosMaquila.filter(
                                cambio => !(cambio.campo === campo && cambio.tipo === tipo && cambio.tipoCampo === tipoCampo && cambio.categoria === categoria)
                            );
                        }
                    }
                }
            ])
        ),

        'general.observaciones_presupuesto'(newValue) {
            if (newValue != this.generalOriginal.solicitud.observaciones_presupuesto) {
                this.esParaCambioTexto = true;
            } else {
                this.esParaCambioTexto = false;
            }
        },

        'display.observacionesExtras'(newValue) {
            this.seRealizoCambioMinimo = Array.isArray(newValue) && newValue.some(obs => obs.trim() !== '');
        },

        'pop.observacionesExtras'(newValue) {
            this.seRealizoCambioMinimo = Array.isArray(newValue) && newValue.some(obs => obs.trim() !== '');
        },

        'empaque.observacionesExtras'(newValue) {
            this.seRealizoCambioMinimo = Array.isArray(newValue) && newValue.some(obs => obs.trim() !== '');
        },

        'maquila.observacionesExtras'(newValue) {
            this.seRealizoCambioMinimo = Array.isArray(newValue) && newValue.some(obs => obs.trim() !== '');
        },

        'comercial.observacionesExtras'(newValue) {
            this.seRealizoCambioMinimo = Array.isArray(newValue) && newValue.some(obs => obs.trim() !== '');
        }
    },
    methods: {
        toggleEmpaqueForm(section) {
            this.empaqueFormSection = section;
        },
        guardarCambios() {
            const formData = new FormData();
            formData.append('id', this.solicitud.solicitud.id);
            formData.append('mensaje', this.mensaje);

            axios.post(`${this.baseUrl}/elementosp/guardarCambios`, formData)
                .then(response => {

                    Alert.toastSuccess('Motivo de cambio de especificaciones guardado correctamente');

                    // Redirigir a la lista de elementos presupuestados
                    setTimeout(() => {
                        if (this.fromOport == 3) {
                            // Si regresa a la oportunidad, redirigir a la vista de edición de la oportunidad
                            window.location.href = `${this.baseUrl}/oportunidades/${this.solicitud.solicitud.oportunidad_id}/edit`;
                        } else {
                            this.regresar(); // Redirigir a la lista de elementos
                        }
                    }, 1000);
                })
                .finally(() => {
                    this.loading = false; // Ocultar el loader
                })
                .catch(error => {
                    if (error.response && error.response.status === 422) {

                        Alert.toastError('Verifica que todos los campos requeridos estén completos');
                        
                        this.errors = error.response.data.errors;
                    } else {
                        console.log('Error al guardar:', error);
                    }
                });
        },

        buscarYSeleccionarCampoNA(catalogo) {
            if (catalogo) {
                // Busca el campo con el valor 'N/A' en el catálogo y lo selecciona
                const campoNA = catalogo.find(item => item.descripcion === 'N/A');
                return campoNA ? campoNA.id : '';
            }
        },
        asignarPopData(data) {
            AsignacionPop(data, this.popCatalogos);
        },

        asignarEmpaqueData(data) {
            AsignacionEmpaque(data, this.empaqueCatalogos);
        },

        asignarDisplayData(data) {
            AsignacionDisplay(data, this.displayCatalogos);
        },

        asignarMaquilaData(data) {
            AsignacionMaquila(data, this.maquilaCatalogos);
        },

        asignarComercialData(data) {
            AsignacionComercial(data, this.comercialCatalogos);
        },

        regresar() {
            window.location.href = `${this.baseUrl}/elementosp/index`;
        },

        formatoFecha(fecha) {
            if (!fecha) return '';
            const opciones = { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit' };
            return new Date(fecha).toLocaleDateString('es-MX', opciones);
        },

        agregarComentarioExtra() {
            // Determinar el objeto y campo de observaciones según el tipo de producto
            let obj = null;
            let campo = '';
            switch (this.general.plantilla_id) {
                case '1': // Comercial
                    obj = this.comercial;
                    break;
                case '2': // Empaque
                    obj = this.empaque;
                    break;
                case '3': // Display
                    obj = this.display;
                    break;
                case '4': // POP
                    obj = this.pop;
                    break;
                case '6': // Maquila
                    obj = this.maquila;
                    break;
                    default:
                        Alert.toastError('Tipo de producto no soportado para comentarios extra.');
                        return;
            }
            campo = 'comentario-extra';

            if (!obj || !obj.observacionesExtras) {
                Alert.toastError('No se encontró el campo de observaciones extras.');
                return;
            }

            // Antes de agregar una nueva observación, verificamos si la última está vacía
            const ultObs = obj.observacionesExtras.length
                ? obj.observacionesExtras[obj.observacionesExtras.length - 1]
                : null;

            if (ultObs !== null && ultObs.trim() === '') {
                Alert.toastError('Por favor, llena la observación existente antes de agregar una nueva.');
                return;
            }

            obj.observacionesExtras.push('');

            // Colocamos el foco en el nuevo campo agregado
            this.$nextTick(() => {
                const index = obj.observacionesExtras.length - 1;
                const inputId = `${campo}-${index}`;
                const inputElement = document.getElementById(inputId);

                if (inputElement) {
                    inputElement.focus();
                }
            });
        },
        removerComentarioExtra(idx) {
            // Determinar el objeto según el tipo de producto
            let obj = null;
            switch (this.general.plantilla_id) {
                case '1': obj = this.comercial; break;
                case '2': obj = this.empaque; break;
                case '3': obj = this.display; break;
                case '4': obj = this.pop; break;
                case '6': obj = this.maquila; break;
                default:
                    Alert.toastError('Tipo de producto no soportado para eliminar comentario extra.');
                    return;
            }
            if (!obj || !obj.observacionesExtras) {
                Alert.toastError('No se encontró el campo de observaciones extras.');
                return;
            }
            obj.observacionesExtras.splice(idx, 1);
        },

        regresarOportunidad() {
            // if (this.fromOport == 1 || this.fromOport == 2 || this.fromOport == 3) {
            // }
            window.location.href = `${this.baseUrl}/oportunidades/${this.solicitud.solicitud.oportunidad_id}/edit/?fromElemento=1`;
        },

        limpiarCamposPop() {
            // Limpiar los campos de POP
            CamposPop.limpiarCampos();
            this.pop = CamposPop.pop;
            this.popCatalogos = CamposPop.popCatalogos;
        },

        limpiarCamposDisplay() {
            CamposDisplay.limpiarCampos();
            this.display = CamposDisplay.display;
            this.displayCatalogos = CamposDisplay.displayCatalogos;
        },

        limpiarCamposEmpaque() {
            // Limpiar los campos de Empaque
            CamposEmpaque.limpiarCampos();
            this.empaque = CamposEmpaque.empaque;
            this.empaqueCatalogos = CamposEmpaque.empaqueCatalogos;
        },

        limpiarCamposMaquila() {
            // Limpiar los campos de Maquila
            CamposMaquila.limpiarCampos();
            this.maquila = CamposMaquila.maquila;
            this.maquilaCatalogos = CamposMaquila.maquilaCatalogos;
        },

        limpiarCamposComercial() {
            // Limpiar los campos de Comercial
            CamposComercial.limpiarCampos();
            this.comercial = CamposComercial.comercial;
            this.comercialCatalogos = CamposComercial.comercialCatalogos;
        },

        limpiarFormulario() {
            this.errors = {};

            // Limpiar los campos generales
            CamposGeneral.limpiarCampos();
            this.general = CamposGeneral.general;
            this.generalDetail = CamposGeneral.generalDetail;

            this.limpiarCamposPop();
            this.limpiarCamposDisplay();
            this.limpiarCamposEmpaque();
            this.limpiarCamposMaquila();
            this.limpiarCamposComercial();
        },

        // ...CamposComercial.functions,

        generarPDF() {
            window.open(`${this.baseUrl}/elementosp/generarPdfElemento/${this.solicitud.solicitud.id}/pdf`, '_blank');
        },

        clonarElemento(id, nombreProyectoOrg, folioOrg) {
            const ultimoFolio = this.ultimoFolio || ''; // Incrementar el último folio registrado

            Swal.fire({
                title: 'Clonar Elemento Presupuestado',
                input: 'text',
                html: `
                    <p>Nombre del Proyecto Original: ${nombreProyectoOrg || 'N/A'}</p>
                    <p>Folio Original: ${folioOrg || 'N/A'}</p>
                    <p>Nuevo Folio: ${ultimoFolio}.</p>
                `,
                inputLabel: 'Nombre del Proyecto',
                inputPlaceholder: 'Ingrese el nombre del proyecto',
                inputValue: nombreProyectoOrg || '', // Prellenar el campo con el nombre del proyecto original
                showCancelButton: true,
                confirmButtonText: 'Clonar',
                cancelButtonText: 'Cancelar',
                inputValidator: (value) => {
                    if (!value) {
                        return 'El nombre del proyecto es obligatorio';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const nombreProyecto = result.value;
                    axios.post(`${this.baseUrl}/elementosp/clonar`, { id: id, nombre_proyecto: nombreProyecto })
                        .then(response => {
                            if (response.data.success) {
                                const Msg = Swal.mixin({
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000
                                });

                                Msg.fire({
                                    icon: 'success',
                                    title: `Elemento presupuestado clonado con éxito. Nuevo folio: ${ultimoFolio}`,
                                });

                                // Redirigir al nuevo elemento clonado
                                setTimeout(() => {
                                    window.location.href = `${this.baseUrl}/elementosp/${response.data.id}/edit`;
                                }, 1000);
                            }
                        })
                        .catch(error => {
                            console.error('Error al clonar el elemento presupuestado:', error);
                        });
                }
            });
        },

        async obtenerUltimoFolioRegistrado() {
            try {
                const response = await axios.get(`${this.baseUrl}/elementosp/ultimo-folio`);
                if (response.data.success) {
                    this.ultimoFolio = response.data.folio;
                } else {
                    console.error('No se pudo obtener el último folio registrado.');
                }
            } catch (error) {
                console.error('Error al obtener el último folio registrado');
            }
        },

        actualizar() {
            this.loading = true; // Mostrar el loader

            this.errors = {}; // Limpiar errores previos

            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            Object.keys(this.$data).forEach(key => {
                // Si es un objeto (como pop o empaque), lo envía como JSON
                if (typeof this[key] === 'object' && this[key] !== null) {
                    // Omitimos los catálogos y los errores
                    if (key.endsWith('Catalogos') || key.endsWith('errors') || key === 'generalDetail') {
                        return;
                    }

                    if (key === 'general') {
                        formData.append(key, JSON.stringify(this[key]));
                    }

                    switch (this.general.plantilla_id) {
                        case '1': // COMERCIAL
                            if (key === 'comercial') {
                                formData.append(key, JSON.stringify(this[key]));
                                const obsComercial = (this[key].observacionesExtras ?? []).filter(o => o.trim() !== '');
                                if (obsComercial.length > 0) formData.append('observacionesExtras', JSON.stringify(obsComercial));
                            }
                            break;
                        case '2': // EMPAQUE
                            if (key === 'empaque') {
                                formData.append(key, JSON.stringify(this[key]));
                                const obsEmpaque = (this[key].observacionesExtras ?? []).filter(o => o.trim() !== '');
                                if (obsEmpaque.length > 0) formData.append('observacionesExtras', JSON.stringify(obsEmpaque));
                            }
                            break;
                        case '3': // DISPLAY
                            if (key === 'display') {
                                formData.append(key, JSON.stringify(this[key]));
                                const obsDisplay = (this[key].observacionesExtras ?? []).filter(o => o.trim() !== '');
                                if (obsDisplay.length > 0) formData.append('observacionesExtras', JSON.stringify(obsDisplay));
                            }
                            break;
                        case '4': // POP
                            if (key === 'pop') {
                                formData.append(key, JSON.stringify(this[key]));
                                const obsPop = (this[key].observacionesExtras ?? []).filter(o => o.trim() !== '');
                                if (obsPop.length > 0) formData.append('observacionesExtras', JSON.stringify(obsPop));
                            }
                            break;
                        case '6': // MAQUILA
                            if (key === 'maquila') {
                                formData.append(key, JSON.stringify(this[key]));
                                const obsMaquila = (this[key].observacionesExtras ?? []).filter(o => o.trim() !== '');
                                if (obsMaquila.length > 0) formData.append('observacionesExtras', JSON.stringify(obsMaquila));
                            }
                            break;
                    }

                    if (key === 'bitacoraCambiosGeneral' && (this.seRealizoCambioMinimo || this.seRealizoCambioEspecificaciones) && (this.estatusEnQuePuedeActualizarCS.includes(parseInt(this.estatusOportunidad)))) {
                        formData.append(key, JSON.stringify(this[key]));
                    }

                    if (key === 'bitacoraCambiosComercial' && (this.seRealizoCambioMinimo || this.seRealizoCambioEspecificaciones) && this.estatusEnQuePuedeActualizarCS.includes(parseInt(this.estatusOportunidad))) {
                        formData.append(key, JSON.stringify(this[key]));
                    }

                    if (key === 'bitacoraCambiosDisplay' && (this.seRealizoCambioMinimo || this.seRealizoCambioEspecificaciones) && this.estatusEnQuePuedeActualizarCS.includes(parseInt(this.estatusOportunidad))) {
                        formData.append(key, JSON.stringify(this[key]));
                    }

                    if (key === 'bitacoraCambiosEmpaque' && (this.seRealizoCambioMinimo || this.seRealizoCambioEspecificaciones) && this.estatusEnQuePuedeActualizarCS.includes(parseInt(this.estatusOportunidad))) {
                        formData.append(key, JSON.stringify(this[key]));
                    }

                    if (key === 'bitacoraCambiosPop' && (this.seRealizoCambioMinimo || this.seRealizoCambioEspecificaciones) && this.estatusEnQuePuedeActualizarCS.includes(parseInt(this.estatusOportunidad))) {
                        formData.append(key, JSON.stringify(this[key]));
                    }

                    if (key === 'bitacoraCambiosMaquila' && (this.seRealizoCambioMinimo || this.seRealizoCambioEspecificaciones) && this.estatusEnQuePuedeActualizarCS.includes(parseInt(this.estatusOportunidad))) {
                        formData.append(key, JSON.stringify(this[key]));
                    }

                } else {
                    if (this[key] !== undefined && this[key] !== null && this[key] !== '') {
                        formData.append(key, this[key]);
                    }
                }
            });

            formData.append('estatusOportunidad', this.estatusOportunidad);

            axios.post(`${this.baseUrl}/elementosp/update/${this.solicitud.solicitud.id}`, formData)
                .then(response => {
                    // Limpiar los campos del formulario
                    // this.limpiarFormulario();

                    Alert.toastSuccess('Elemento actualizado correctamente');

                    // Redirigir a la lista de elementos presupuestados
                    setTimeout(() => {
                        if (this.fromOport == 1 || this.fromOport == 2 || this.fromOport == null) {
                            // Si regresa a la oportunidad, redirigir a la vista de edición de la oportunidad
                            window.location.href = `${this.baseUrl}/oportunidades/${this.solicitud.solicitud.oportunidad_id}/edit/?fromElemento=1`;
                        } else {
                            this.regresar(); // Redirigir a la lista de elementos
                        }
                    }, 1000);
                })
                .finally(() => {
                    this.loading = false; // Ocultar el loader

                    // Antes de borrar las bitácoras, colocamos todos los campos guardados en las bitacoras en this.camposQueCambiaron
                    this.camposQueCambiaron.push(
                        ...this.bitacoraCambiosGeneral.map(c => c.campo),
                        ...this.bitacoraCambiosComercial.map(c => c.campo),
                        ...this.bitacoraCambiosDisplay.map(c => c.campo),
                        ...this.bitacoraCambiosEmpaque.map(c => c.campo)
                    );

                    this.bitacoraCambiosGeneral = [];
                    this.bitacoraCambiosComercial = [];
                })
                .catch(error => {
                    if (error.response && error.response.status === 422) {

                        Alert.toastError('Verifica que todos los campos requeridos estén completos');
                        
                        this.errors = error.response.data.errors;
                    } else {
                        console.log('Error al guardar:', error);
                    }
                });
        },
    }
});