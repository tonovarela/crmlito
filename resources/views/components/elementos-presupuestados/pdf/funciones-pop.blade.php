@php

/**
 * =========================
 * HELPERS
 * =========================
 */

function validoPop($valor) {
    return !empty($valor)
        && $valor !== 'N/A'
        && $valor !== 'Si'
        && $valor !== 'Otro (Especificar en Observaciones)'
        && $valor !== 'Especificar en Observaciones';
}

function construirParrafoPop($items) {
    return empty($items)
        ? null
        : "<div style='margin-top:5px;'>" . implode(' ', $items) . "</div>";
}

/**
 * =========================
 * GENERAL (NARRATIVO)
 * =========================
 */

function construirGeneralPop($pres) {

    $desc = [];

    // Sustrato + gramaje
    if (validoPop($pres->sustrato)) {
        $texto = "Se ha seleccionado un sustrato {$pres->sustrato}";
        
        if (validoPop($pres->gramaje)) {
            $texto .= " con un gramaje de {$pres->gramaje}";
        }

        $texto .= ", garantizando resistencia estructural y excelente presentación.";
        $desc[] = $texto;
    }

    // Tintas
    $tintas = [];

    if (validoPop($pres->tinta_frente)) {
        $tintas[] = "impresión frontal con {$pres->tinta_frente}";
    }

    if (validoPop($pres->tinta_vuelta)) {
        $tintas[] = "impresión al reverso con {$pres->tinta_vuelta}";
    }

    if ($tintas) {
        $desc[] = "El proceso de impresión contempla " . implode(' y ', $tintas) . ", asegurando alta fidelidad cromática.";
    }

    // Barnices
    $barnices = [];

    if (validoPop($pres->barniz_frente)) {
        $barnices[] = "barniz frontal {$pres->barniz_frente}";
    }

    if (validoPop($pres->barniz_vuelta)) {
        $barnices[] = "barniz al reverso {$pres->barniz_vuelta}";
    }

    if ($barnices) {
        $desc[] = "Se incorporan acabados de protección mediante " . implode(' y ', $barnices) . ".";
    }

    // Cambios
    if (validoPop($pres->num_cambios)) {
        $desc[] = "El proyecto contempla {$pres->num_cambios} cambios durante el proceso de producción.";
    }

    // Trazo
    if (validoPop($pres->trazo_cliente)) {
        $desc[] = "Se trabajará con trazo proporcionado por el cliente.";
    } elseif (validoPop($pres->trazo_estructural)) {
        $desc[] = "Se desarrollará trazo estructural conforme a requerimientos del proyecto.";
    }

    // Empalme
    if (validoPop($pres->sustrato_empalme)) {
        $desc[] = "Se contempla empalme sobre {$pres->sustrato_empalme}.";
    }

    return $desc;
}


/**
 * =========================
 * ACABADOS (NARRATIVO PRO)
 * =========================
 */

function construirAcabadosPop($pres) {

    $desc = [];

    /**
     * =========================
     * METALIZADOS
     * =========================
     */
    $metalizados = [];

    if (validoPop($pres->metalizado_frente)) {
        $metalizados[] = "metalizado frontal {$pres->metalizado_frente}";
    }

    if (validoPop($pres->metalizado_vuelta)) {
        $metalizados[] = "metalizado al reverso {$pres->metalizado_vuelta}";
    }

    if ($metalizados) {
        $desc[] = "Se aplican procesos de " . implode(' y ', $metalizados) . ", aportando un acabado visual de alto impacto y sofisticación.";
    }


    /**
     * =========================
     * LAMINADOS
     * =========================
     */
    $laminados = [];

    if (validoPop($pres->laminado_frente)) {
        $laminados[] = "{$pres->laminado_frente} frontal";
    }

    if (validoPop($pres->laminado_vuelta)) {
        $laminados[] = "{$pres->laminado_vuelta} al reverso";
    }

    if ($laminados) {
        $desc[] = "Se incorporan " . implode(' y ', $laminados) . ", brindando mayor protección y durabilidad al producto.";
    }


    /**
     * =========================
     * BARNIZ SAKURAI
     * =========================
     */
    $sakurai = [];

    if (validoPop($pres->barniz_sakurai_frente)) {
        $sakurai[] = "barniz Sakurai frontal {$pres->barniz_sakurai_frente}";
    }

    if (validoPop($pres->barniz_sakurai_vuelta)) {
        $sakurai[] = "barniz Sakurai al reverso {$pres->barniz_sakurai_vuelta}";
    }

    if ($sakurai) {
        $desc[] = "Se aplican acabados con " . implode(' y ', $sakurai) . ", mejorando la resistencia superficial y el atractivo visual.";
    }


    /**
     * =========================
     * REALCE
     * =========================
     */
    if (validoPop($pres->realce)) {

        $texto = "El producto incorpora realce {$pres->realce}";

        if (validoPop($pres->placa_realce)) {
            $texto .= " mediante placa {$pres->placa_realce}";
        }

        $texto .= ", generando un efecto táctil y visual distintivo.";

        $desc[] = $texto;
    }


    /**
     * =========================
     * HOT STAMPING
     * =========================
     */
    if (validoPop($pres->hot_stamping)) {

        $texto = "Se integra hot stamping {$pres->hot_stamping}";

        if (validoPop($pres->color_hot_stamping)) {
            $texto .= " en color {$pres->color_hot_stamping}";
        }

        if (validoPop($pres->placa_hot_stamping)) {
            $texto .= " utilizando placa {$pres->placa_hot_stamping}";
        }

        $texto .= ", aportando un acabado premium y alta percepción de valor.";

        $desc[] = $texto;
    }


    /**
     * =========================
     * SUAJE
     * =========================
     */
    if (validoPop($pres->suaje)) {
        $desc[] = "El proceso contempla {$pres->suaje}, asegurando cortes precisos y uniformes.";
    }


    /**
     * =========================
     * TERMODOBLEZ
     * =========================
     */
    if (validoPop($pres->termodoblez)) {
        $desc[] = "Se realiza termodoblez {$pres->termodoblez}, facilitando el armado y mejorando la funcionalidad estructural.";
    }


    return $desc;
}


/**
 * =========================
 * ADICIONALES (PRO)
 * =========================
 */

function construirAdicionalesPop($pres) {

    $extra = [];

    // Hilo
    if (validoPop($pres->hilo_monofilamento)) {
        $texto = "Incorporación de hilo monofilamento ({$pres->hilo_monofilamento})";

        if (validoPop($pres->cm_hilo_x_pieza)) {
            $texto .= " con {$pres->cm_hilo_x_pieza} cm por pieza";
        }

        $texto .= ".";
        $extra[] = $texto;
    }

    // Cinta doble cara
    if (validoPop($pres->cinta_doble_cara)) {
        $texto = "Aplicación de cinta doble cara ({$pres->cinta_doble_cara})";

        if (validoPop($pres->cantidad_cintas_x_pieza)) {
            $texto .= ", {$pres->cantidad_cintas_x_pieza} por pieza";
        }

        if (validoPop($pres->medida_cada_cinta)) {
            $texto .= " de {$pres->medida_cada_cinta}";
        }

        $texto .= ".";
        $extra[] = $texto;
    }

    return $extra;
}

@endphp
