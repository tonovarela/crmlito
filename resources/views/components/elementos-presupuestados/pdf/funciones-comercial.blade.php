@php
        /**
     * =========================
     * HELPERS (REUTILIZABLES)
     * =========================
     */

    function validoComercial($valor)
    {
        return !empty($valor) &&
            $valor !== 'N/A' &&
            $valor !== 'Si' &&
            $valor !== 'Otro (Especificar en Observaciones)' &&
            $valor !== 'Especificar en Observaciones';
    }

    function construirDescripcionBaseComercial($pres, $tipo)
    {
        $desc = [];

        if (validoComercial($pres->{"num_paginas_$tipo"}) && $pres->{"num_paginas_$tipo"} > 0) {
            $desc[] = "{$pres->{"num_paginas_$tipo"}} páginas.";
        }

        if (validoComercial($pres->{"sustrato_$tipo"})) {
            $desc[] = "Se ha seleccionado un sustrato {$pres->{"sustrato_$tipo"}}, garantizando calidad, resistencia y excelente presentación.";
        }

        // Tintas
        $tintas = [];

        if (validoComercial($pres->{"tinta_frente_$tipo"})) {
            $tintas[] = "impresión frontal con {$pres->{"tinta_frente_$tipo"}}";
        }

        if (validoComercial($pres->{"tinta_vuelta_$tipo"})) {
            $tintas[] = "impresión al reverso con {$pres->{"tinta_vuelta_$tipo"}}";
        }

        if (count($tintas)) {
            $desc[] =
                'El proceso de impresión contempla ' .
                implode(' y ', $tintas) .
                ', asegurando alta fidelidad en color y definición.';
        }

        // Barnices
        $barnices = [];

        if (validoComercial($pres->{"barniz_frente_$tipo"})) {
            $barnices[] = "barniz frontal {$pres->{"barniz_frente_$tipo"}}";
        }

        if (validoComercial($pres->{"barniz_vuelta_$tipo"})) {
            $barnices[] = "barniz al reverso {$pres->{"barniz_vuelta_$tipo"}}";
        }

        if (count($barnices)) {
            $desc[] =
                'Se integran acabados de protección mediante ' .
                implode(' y ', $barnices) .
                ', mejorando la durabilidad y apariencia del producto.';
        }

        return $desc;
    }

    /**
     * =========================
     * ACABADOS (FORMAL / VENTA)
     * =========================
     */

    function construirAcabadosForroComercial($pres)
    {
        $frases = [];

        if (validoComercial($pres->metalizado_frente_forro)) {
            $frases[] = "En la superficie frontal se ha aplicado un proceso de metalizado que aporta un acabado brillante, elegante y de alta gama, mediante la técnica {$pres->metalizado_frente_forro}";
        }

        if (validoComercial($pres->laminado_frente_forro)) {
            $frases[] = "La cara frontal cuenta con un laminado protector que incrementa la resistencia, mejora la textura y proporciona una apariencia uniforme, utilizando {$pres->laminado_frente_forro}";
        }

        if (validoComercial($pres->barniz_sakurai_frente_forro)) {
            $frases[] = "Se aplica barniz tipo Sakurai, el cual realza la impresión y protege contra el desgaste, con la variante {$pres->barniz_sakurai_frente_forro}";
        }

        if (validoComercial($pres->hot_stamping_forro)) {
            $frases[] = "Se incorpora hot stamping, añadiendo detalles metálicos que refuerzan la estética premium mediante {$pres->hot_stamping_forro}";
        }

        if (validoComercial($pres->suaje_forro)) {
            $frases[] = "Se realiza un proceso de {$pres->suaje_forro} que permite cortes y formas especiales, aportando valor visual y funcional.";
        }

        return $frases;
    }

    function construirAcabadosInterioresComercial($pres)
    {
        $frases = [];

        if (validoComercial($pres->metalizado_frente_interiores)) {
            $frases[] = "Se aplica metalizado en la superficie frontal, proporcionando un acabado brillante y sofisticado mediante {$pres->metalizado_frente_interiores}";
        }

        if (validoComercial($pres->laminado_frente_interiores)) {
            $frases[] = "Se incorpora un {$pres->laminado_frente_interiores} protector que mejora la durabilidad y la presentación.";
        }

        if (validoComercial($pres->barniz_sakurai_frente_interiores)) {
            $frases[] = "Se aplica barniz Sakurai que realza la impresión y protege la superficie ({$pres->barniz_sakurai_frente_interiores})";
        }

        if (validoComercial($pres->doblez)) {
            $frases[] = "El proceso de doblez se ejecuta con precisión para garantizar funcionalidad y presentación ({$pres->doblez})";
        }

        if (validoComercial($pres->encuadernado)) {
            $frases[] = "El sistema de encuadernado {$pres->encuadernado} asegura una unión firme y profesional de las páginas";
        }

        return $frases;
    }
@endphp