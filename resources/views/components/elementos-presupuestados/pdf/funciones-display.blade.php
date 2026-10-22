@php
    /**
 * =========================
 * HELPERS
 * =========================
 */

function validoDisplay($valor) {
    return !empty($valor)
        && $valor !== 'N/A'
        && $valor !== 'Si'
        && $valor !== 'Otro (Especificar en Observaciones)'
        && $valor !== 'Especificar en Observaciones';
}


/**
 * =========================
 * DESCRIPCIÓN GENERAL DISPLAY
 * =========================
 */

function construirDisplayGeneralDisplay($pres) {

    $desc = [];

    if (validoDisplay($pres->sustrato)) {
        $desc[] = "Se ha seleccionado un sustrato {$pres->sustrato}, garantizando resistencia estructural y una presentación óptima del display.";
    }

    // Tintas
    if (validoDisplay($pres->tinta_frente)) {
        $desc[] = "El proceso de impresión se realiza al frente con {$pres->tinta_frente}, asegurando alta calidad en color y definición.";
    }

    // Empalme
    if (validoDisplay($pres->sustrato_empalme)) {
        $desc[] = "Se incorpora un proceso de empalme sobre {$pres->sustrato_empalme}, reforzando la estabilidad y durabilidad del producto.";
    }

    return $desc;
}


/**
 * =========================
 * ACABADOS DISPLAY (VENDEDOR)
 * =========================
 */

function construirAcabadosDisplayDisplay($pres) {

    $frases = [];

    if (validoDisplay($pres->metalizado_frente)) {
        $frases[] = "Se aplica {$pres->metalizado_frente} metalizado al frente aportando un acabado brillante, elegante y de alta gama";
    }

    if (validoDisplay($pres->metalizado_vuelta)) {
        $frases[] = "Se incorpora {$pres->metalizado_vuelta} metalizado al reverso, mejorando la estética general del producto";
    }

    if (validoDisplay($pres->laminado_frente)) {
        $frases[] = "La superficie frontal cuenta con {$pres->laminado_frente} protector que incrementa la resistencia y mejora la presentación";
    }

    if (validoDisplay($pres->laminado_vuelta)) {
        $frases[] = "Se integra {$pres->laminado_vuelta} al reverso, optimizando la durabilidad del display";
    }

    if (validoDisplay($pres->barniz_sakurai_frente)) {
        $frases[] = "Se aplica barniz tipo Sakurai ({$pres->barniz_sakurai_frente}) al frente, realzando la impresión y protegiendo la superficie";
    }

    if (validoDisplay($pres->barniz_sakurai_vuelta)) {
        $frases[] = "Se incorpora barniz Sakurai ({$pres->barniz_sakurai_vuelta}) al reverso, proporcionando mayor protección y acabado visual";
    }

    if (validoDisplay($pres->suajado)) {
        $frases[] = "Se realiza un proceso de {$pres->suajado} que permite cortes precisos y funcionales, optimizando el armado y presentación";
    }

    return $frases;
}
@endphp