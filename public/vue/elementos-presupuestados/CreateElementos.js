import Alert from "./componentes/alerts/Alert.js";
import Input from "./componentes/forms/Input.js";
import SelectSearch from "./componentes/forms/SelectSearch.js";
import SelectFormApi from "./componentes/forms/SelectApi.js";
import SelectForm from "./componentes/forms/Select.js";
import TextArea from "./componentes/forms/TextArea.js";
import Loader from "./componentes/Loader.js";
import FormCheckbox from "./componentes/forms/Checkbox.js";

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

new Vue({
    el: '#formElementos',
    data: {
        errors: {
            general: {},
            pop: {},
            empaque: {},
            display: {},
            maquila: {},
            comercial: {},
        },
        general: CamposGeneral.general, // Importamos los campos del objeto CamposGeneral
        generalDetail: CamposGeneral.generalDetail, // Importamos los campos del objeto CamposGeneral
        tiposProductos: [],

        baseUrl: window.Laravel.baseUrl, // Base URL de la aplicación
        loading: false, // Estado de carga para mostrar el loader

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
        oportunidad: window.oportunidad || '', // ID de la oportunidad, si se pasa desde el backend
        defaultOportunidad: '',
        isDisabled: false, // Estado de deshabilitado para el campo de oportunidad

        naEditorialForro: false, // Estado para el checkbox de Editorial Forro
        naEditorialGuarda: false, // Estado para el checkbox de Editorial Guarda
        naAcabadoForro: false, // Estado para el checkbox de Acabado Forro

        naTacon1: false, // Estado para el checkbox de Tacon 1
        naTacon2: false, // Estado para el checkbox de Tacon 2

        regresaOport: window.regresaOport || 0, // 1 Crear desde Oportunidad, 2 Editar desde Oportunidad, 3 Registrar cambio de especificaciones

        esParaCambioTexto: false, // Variable para saber si el cambio de especificaciones es solo de texto o también de campos

        empaqueFormSection: 'general', // Sección del formulario de empaque que se muestra

        bitacoraCambiosGeneral: [],
        bitacoraCambiosComercial: [],
        bitacoraCambiosDisplay: [],
        bitacoraCambiosEmpaque: [],
        bitacoraCambiosPop: [],
        bitacoraCambiosMaquila: [],

        camposQueCambiaron: [],
        estatusOportunidad: null,
        // Actualizar general
        estatusEnQuePuedeActualizar: [2, 1, 8, 7],

        // Sin solicitud de cambios
        estatusEnQuePuedeActualizarSSC: [2, 1],

        // Con solicitud de cambios
        estatusEnQuePuedeActualizarCS: [8, 7],
        role: window.role || 0, // Rol del usuario, si existe
        tipoForm: 1,
        validacionDisabled: false, // Variable para controlar la validación de campos al deshabilitar el formulario
    },
    created() {
        if (this.oportunidad != undefined && this.oportunidad != '') {
            this.general.oportunidad_id = this.oportunidad.idOportunidad; // Asignar el ID de la oportunidad al campo general
            this.defaultOportunidad = this.oportunidad.folio + ' - ' + this.oportunidad.nomOportunidad; // Asignar el valor por defecto para la oportunidad
            this.isDisabled = true; // Deshabilitar el campo de oportunidad
            this.estatusOportunidad = this.oportunidad.idEstatus;
        } else {
            // Eliminamos todo lo que este despues de /create/
            const urlParts = window.location.href.split('/');
            const createIndex = urlParts.indexOf('create');
            if (createIndex !== -1) {
                urlParts.splice(createIndex + 1);
                const newUrl = urlParts.join('/');
                window.history.replaceState({}, '', newUrl);
            }
        }
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

                this.empaqueFormSection = 'general';

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

                axios.get(`${this.baseUrl}/get-data-form`, { params: { idPlantilla: newValue } })
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
                                this.naTacon1 = true;
                                this.naTacon2 = true;
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
                this.comercial.suajado_forro = this.buscarYSeleccionarCampoNA(this.comercialCatalogos.suajadosForroList);
                this.comercial.placa_realce_forro = '';
                this.comercial.placa_hot_stamping_forro = '';
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
            }
        },
        naTacon1(newValue) {
            // Si esta activo el check es por que no aplica Tacon 1 y deben de estar vacios los campos relacionados
            if (newValue) {
                this.empaque.sustrato_t1 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.sustratos);
                this.empaque.gramaje_t1 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.gramajes);
                this.empaque.tinta_frente_t1 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.tintasFrente);
                this.empaque.tinta_vuelta_t1 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.tintasVuelta);
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
                this.empaque.realce_t1 = this.buscarYSeleccionarCampoNA(this.empaqueCatalogos.realces);
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
                this.empaque.medida_final_ancho_t1 = '';
                this.empaque.medida_final_largo_t1 = '';
                this.empaque.medida_final_alto_t1 = '';

                this.empaque.medida_extendida_t1 = '';
                this.empaque.medida_extendida_ancho_t1 = '';
                this.empaque.medida_extendida_largo_t1 = '';
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
                this.empaque.medida_final_ancho_t2 = '';
                this.empaque.medida_final_largo_t2 = '';
                this.empaque.medida_final_alto_t2 = '';

                this.empaque.medida_extendida_t2 = '';
                this.empaque.medida_extendida_ancho_t2 = '';
                this.empaque.medida_extendida_largo_t2 = '';
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
        }
    },
    methods: {
        toggleEmpaqueForm(section) {
            this.empaqueFormSection = section;
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

        regresarOportunidad() {
            if (this.regresaOport == 1 || this.regresaOport == 2) {
                window.location.href = `${this.baseUrl}/oportunidades/${this.oportunidad.idOportunidad}/edit`;
            }
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

        guardar() {
            this.loading = true; // Mostrar el loader

            this.errors = {}; // Limpiar errores previos

            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            Object.keys(this.$data).forEach(key => {
                // Si es un objeto (como pop o empaque), lo envía como JSON
                if (typeof this[key] === 'object' && this[key] !== null) {
                    // Omitimos los catálogos y los errores
                    if (key.endsWith('Catalogos') || key.endsWith('errors')) {
                        return;
                    }

                    if (key === 'general') {
                        formData.append(key, JSON.stringify(this[key]));
                    }

                    switch (this.general.plantilla_id) {
                        case '1': // COMERCIAL
                            if (key === 'comercial') {
                                formData.append(key, JSON.stringify(this[key]));
                            }
                            break;
                        case '2': // EMPAQUE
                            if (key === 'empaque') {
                                formData.append(key, JSON.stringify(this[key]));
                            }
                            break;
                        case '3': // DISPLAY
                            if (key === 'display') {
                                formData.append(key, JSON.stringify(this[key]));
                            }
                            break;
                        case '4': // POP
                            if (key === 'pop') {
                                formData.append(key, JSON.stringify(this[key]));
                            }
                            break;
                        case '6': // MAQUILA
                            if (key === 'maquila') {
                                formData.append(key, JSON.stringify(this[key]));
                            }
                            break;
                    }
                } else {
                    if (this[key] !== undefined && this[key] !== null && this[key] !== '') {
                        formData.append(key, this[key]);
                    }
                }
            });

            axios.post(`${this.baseUrl}/elementosp/store`, formData)
                .then(response => {
                    // Limpiar los campos del formulario
                    this.limpiarFormulario();

                    Alert.toastSuccess('Elemento guardado correctamente');

                    setTimeout(() => {
                        if (this.regresaOport == 1) {
                            // Si regresa a la oportunidad, redirigir a la vista de edición de la oportunidad
                            window.location.href = `${this.baseUrl}/oportunidades/${this.oportunidad.idOportunidad}/edit`;
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
    }
});