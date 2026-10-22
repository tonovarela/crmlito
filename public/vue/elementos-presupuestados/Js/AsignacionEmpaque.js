export default function (data, empaque) {
    empaque.sustratos = data[50];
    empaque.gramajes = data[15];
    empaque.tintasFrente = data[16];
    empaque.tintasVuelta = data[16];
    empaque.medidasFinal = data[4];
    empaque.medidasExtendida = data[4];
    empaque.barnicesFrente = data[7];
    empaque.barnicesVuelta = data[7];
    empaque.sustratosParaEmpalme = data[51];
    empaque.requieresVentana = data[17];
    empaque.puntajesVentana = data[18];
    empaque.suajes = data[27];
    empaque.otrosEmpaque = data[9];

    empaque.metalizadosFrente = data[5];
    empaque.metalizadosVuelta = data[5];
    empaque.laminadosFrente = data[6];
    empaque.laminadosVuelta = data[6];
    empaque.barnicesSakuraiFrente = data[7];
    empaque.barnicesSakuraiVuelta = data[7];
    empaque.realces = data[19];
    empaque.hotStampings = data[9];
    empaque.coloresHotStamping = data[20];
    empaque.otrosAcabados = data[9];
    empaque.medidasFinales = data[4];
    empaque.medidasExtendidas = data[4];

    empaque.tiposPeguesArmado = data[21];
    empaque.empaques = data[22];
    empaque.entregas = data[23];
    empaque.lugarEntregas = data[13];

    empaque.suajadosList = data[37];
}