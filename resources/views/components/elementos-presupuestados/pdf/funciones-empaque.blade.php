@php
    /**
 * =========================
 * HELPERS
 * =========================
 */

function validoEmpaque($valor) {
    return !empty($valor)
        && $valor !== 'N/A'
        && $valor !== 'Si'
        && $valor !== 'Otro (Especificar en Observaciones)'
        && $valor !== 'Especificar en Observaciones';
}


/**
 * =========================
 * DESCRIPCIÓN GENERAL
 * =========================
 */

function construirEmpaqueGeneralEmpaque($pres, $sufijo = '') {

    $desc = [];

    if (validoEmpaque($pres->{"sustrato$sufijo"})) {
        $desc[] = "Se ha seleccionado un sustrato {$pres->{"sustrato$sufijo"}}, garantizando resistencia estructural y excelente presentación.";
    }

    // Tintas
    $tintas = [];

    if (validoEmpaque($pres->{"tinta_frente$sufijo"})) {
        $tintas[] = "impresión frontal con {$pres->{"tinta_frente$sufijo"}}";
    }

    if (validoEmpaque($pres->{"tinta_vuelta$sufijo"})) {
        $tintas[] = "impresión al reverso con {$pres->{"tinta_vuelta$sufijo"}}";
    }

    if (count($tintas)) {
        $desc[] = "El proceso de impresión contempla " . implode(' y ', $tintas) . ", asegurando precisión y fidelidad cromática.";
    }

    // Barnices
    $barnices = [];

    if (validoEmpaque($pres->{"barniz_frente$sufijo"})) {
        $barnices[] = "{$pres->{"barniz_frente$sufijo"}} frontal";
    }

    if (validoEmpaque($pres->{"barniz_vuelta$sufijo"})) {
        $barnices[] = "{$pres->{"barniz_vuelta$sufijo"}} al reverso";
    }

    if (count($barnices)) {
        $desc[] = "Se incorporan acabados de protección mediante " . implode(' y ', $barnices) . ", incrementando la durabilidad del empaque.";
    }

    if (validoEmpaque($pres->{"sustrato_empalme$sufijo"})) {
        $desc[] = "Se contempla un proceso de empalme sobre {$pres->{"sustrato_empalme$sufijo"}}, reforzando la estructura del producto.";
    }

    if (validoEmpaque($pres->{"requiere_ventana$sufijo"})) {
        $desc[] = "El diseño considera ventana {$pres->{"requiere_ventana$sufijo"}}, aportando visibilidad al contenido.";
    }

    if (validoEmpaque($pres->{"puntaje_ventana$sufijo"})) {
        $desc[] = "Se integra puntaje especializado para ventana ({$pres->{"puntaje_ventana$sufijo"}}), asegurando precisión en el troquelado.";
    }

    if (validoEmpaque($pres->{"otros_empaque$sufijo"})) {
        $desc[] = "Se incorporan características adicionales como {$pres->{"otros_empaque$sufijo"}}, adaptándose a requerimientos específicos del proyecto.";
    }

    return $desc;
}


/**
 * =========================
 * ACABADOS (VENDEDOR)
 * =========================
 */

function construirAcabadosEmpaqueEmpaque($pres, $sufijo = '') {

    $frases = [];

    if (validoEmpaque($pres->{"metalizado_frente$sufijo"})) {
        $frases[] = "Se aplica {$pres->{"metalizado_frente$sufijo"}} metalizado frontal que aporta un acabado brillante, elegante y de alta gama";
    }

    if (validoEmpaque($pres->{"metalizado_vuelta$sufijo"})) {
        $frases[] = "Se incorpora {$pres->{"metalizado_vuelta$sufijo"}} metalizado al reverso, elevando la percepción visual del producto";
    }

    if (validoEmpaque($pres->{"laminado_frente$sufijo"})) {
        $frases[] = "La cara frontal cuenta con {$pres->{"laminado_frente$sufijo"}} protector que mejora la resistencia y la presentación";
    }

    if (validoEmpaque($pres->{"laminado_vuelta$sufijo"})) {
        $frases[] = "Se integra {$pres->{"laminado_vuelta$sufijo"}} al reverso, optimizando la durabilidad";
    }

    if (validoEmpaque($pres->{"barniz_sakurai_frente$sufijo"})) {
        $frases[] = "Se aplica barniz tipo Sakurai ({$pres->{"barniz_sakurai_frente$sufijo"}}) que realza la impresión y protege la superficie";
    }

    if (validoEmpaque($pres->{"barniz_sakurai_vuelta$sufijo"})) {
        $frases[] = "Se incorpora barniz Sakurai ({$pres->{"barniz_sakurai_vuelta$sufijo"}}) al reverso, incrementando protección y acabado visual";
    }

    if (validoEmpaque($pres->{"realce$sufijo"})) {
        $frases[] = "Se realiza un proceso de realce que genera relieve y aporta valor táctil y visual ({$pres->{"realce$sufijo"}})";
    }

    if (validoEmpaque($pres->{"hot_stamping$sufijo"})) {
        $frases[] = "Se incorpora hot stamping, aportando detalles metálicos de alta calidad ({$pres->{"hot_stamping$sufijo"}})";
    }

    if (validoEmpaque($pres->{"color_hot_stamping$sufijo"})) {
        $frases[] = "El hot stamping se ejecuta con acabado en color {$pres->{"color_hot_stamping$sufijo"}}, alineado al diseño del producto";
    }

    if (validoEmpaque($pres->{"suaje$sufijo"})) {
        $frases[] = "Se realiza {$pres->{"suaje$sufijo"}} que permite cortes especiales y mejora la funcionalidad";
    }

    return $frases;
}


/**
 * =========================
 * ADICIONALES
 * =========================
 */

function construirAdicionalesEmpaque($pres) {

    $extra = [];

    if (validoEmpaque($pres->tipo_pegue_armado)) {
        $extra[] = "El proceso de armado contempla {$pres->tipo_pegue_armado}";
    }

    if (validoEmpaque($pres->empaque)) {
        $extra[] = "El producto será entregado bajo esquema de empaque {$pres->empaque}";
    }

    if (validoEmpaque($pres->entrega)) {
        $extra[] = "La entrega se realizará bajo modalidad {$pres->entrega}";
    }

    if (validoEmpaque($pres->lugar_entrega)) {
        $extra[] = "Lugar de entrega: {$pres->lugar_entrega}";
    }

    return $extra;
}

@endphp