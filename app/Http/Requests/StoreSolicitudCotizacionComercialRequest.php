<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSolicitudCotizacionComercialRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'num_paginas_interiores' => 'required|numeric',
            'sustrato_interiores' => 'required|exists:cat_EP_detalle,id',
            // 'gramaje_interiores' => 'required|exists:cat_EP_detalle,id',
            'tinta_frente_interiores' => 'required|exists:cat_EP_detalle,id',
            'tinta_vuelta_interiores' => 'required|exists:cat_EP_detalle,id',
            'medida_final_interiores' => 'required|string|max:255',
            'medida_extendida_interiores' => 'required|string|max:255',
            'barniz_frente_interiores' => 'required|exists:cat_EP_detalle,id',
            'barniz_vuelta_interiores' => 'required|exists:cat_EP_detalle,id',

            // campo solo numerico cuando sea requerido 
            'num_paginas_forro' => 'required|numeric',
            'sustrato_forro' => 'required|exists:cat_EP_detalle,id',
            // 'gramaje_forro' => 'required|exists:cat_EP_detalle,id',
            'tinta_frente_forro' => 'required|exists:cat_EP_detalle,id',
            'tinta_vuelta_forro' => 'required|exists:cat_EP_detalle,id',
            'medida_final_forro' => 'required_if:naEditorialForro,false|string|max:255',
            'medida_extendida_forro' => 'required_if:naEditorialForro,false|string|max:255',
            'barniz_frente_forro' => 'required|exists:cat_EP_detalle,id',
            'barniz_vuelta_forro' => 'required|exists:cat_EP_detalle,id',

            'num_paginas_guardas' => 'required|numeric',
            'sustrato_guardas' => 'required|exists:cat_EP_detalle,id',
            // 'gramaje_guardas' => 'required|exists:cat_EP_detalle,id',
            'tinta_frente_guardas' => 'required|exists:cat_EP_detalle,id',
            'tinta_vuelta_guardas' => 'required|exists:cat_EP_detalle,id',
            'medida_final_guardas' => 'required_if:naEditorialGuarda,false|string|max:255',
            'medida_extendida_guardas' => 'required_if:naEditorialGuarda,false|string|max:255',
            'barniz_frente_guardas' => 'required|exists:cat_EP_detalle,id',
            'barniz_vuelta_guardas' => 'required|exists:cat_EP_detalle,id',

            'metalizado_frente_interiores' => 'required|exists:cat_EP_detalle,id',
            'metalizado_vuelta_interiores' => 'required|exists:cat_EP_detalle,id',
            'laminado_frente_interiores' => 'required|exists:cat_EP_detalle,id',
            'laminado_vuelta_interiores' => 'required|exists:cat_EP_detalle,id',
            'barniz_sakurai_frente_interiores' => 'required|exists:cat_EP_detalle,id',
            'barniz_sakurai_vuelta_interiores' => 'required|exists:cat_EP_detalle,id',
            'doblez' => 'required|exists:cat_EP_detalle,id',
            'embolsado_retractilado' => 'required|exists:cat_EP_detalle,id',
            'perforado' => 'required|exists:cat_EP_detalle,id',
            'suaje_interiores' => 'required|exists:cat_EP_detalle,id',
            'otros_interiores' => 'required|exists:cat_EP_detalle,id',

            'metalizado_frente_forro' => 'required|exists:cat_EP_detalle,id',
            'metalizado_vuelta_forro' => 'required|exists:cat_EP_detalle,id',
            'laminado_frente_forro' => 'required|exists:cat_EP_detalle,id',
            'laminado_vuelta_forro' => 'required|exists:cat_EP_detalle,id',
            'barniz_sakurai_frente_forro' => 'required|exists:cat_EP_detalle,id',
            'barniz_sakurai_vuelta_forro' => 'required|exists:cat_EP_detalle,id',
            'realce_forro' => 'required|exists:cat_EP_detalle,id',
            'placa_realce_forro' => 'required_unless:realce_forro,288|string|max:255',
            'hot_stamping_forro' => 'required|exists:cat_EP_detalle,id',
            'placa_hot_stamping_forro' => 'required_unless:hot_stamping_forro,288|string|max:255',
            'color_hot_stamping_forro' => 'required|exists:cat_EP_detalle,id',
            'suaje_forro' => 'required|exists:cat_EP_detalle,id',

            'encuadernado' => 'required|exists:cat_EP_detalle,id',
            'empaque' => 'required|exists:cat_EP_detalle,id',
            'entrega' => 'required|exists:cat_EP_detalle,id',
            'lugar_entrega' => 'required|exists:cat_EP_detalle,id',
            'observaciones' => 'nullable|string',
            'observaciones_presupuesto' => 'nullable|string',

            'suajado' => 'required|exists:cat_EP_detalle,id',
            'suajado_forro' => 'required|exists:cat_EP_detalle,id',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'num_paginas_forro.required' => 'El número de páginas del forro es obligatorio.',
            'num_paginas_forro.numeric' => 'El número de páginas del forro debe ser un número.',
            'num_paginas_forro.min' => 'El número de páginas del forro debe ser al menos 1.',
            'sustrato_forro.required' => 'El sustrato del forro es obligatorio.',
            'sustrato_forro.exists' => 'El sustrato del forro seleccionado no existe.',
            'gramaje_forro.required' => 'El gramaje del forro es obligatorio.',
            'gramaje_forro.exists' => 'El gramaje del forro seleccionado no existe.',
            'tinta_frente_forro.required' => 'La tinta del frente del forro es obligatoria.',
            'tinta_frente_forro.exists' => 'La tinta del frente del forro seleccionada no existe.',
            'tinta_vuelta_forro.required' => 'La tinta de la vuelta del forro es obligatoria.',
            'tinta_vuelta_forro.exists' => 'La tinta de la vuelta del forro seleccionada no existe.',
            'medida_final_forro.required' => 'La medida final del forro es obligatoria.',
            'medida_final_forro.string' => 'La medida final del forro debe ser una cadena de texto.',
            'medida_final_forro.max' => 'La medida final del forro no puede exceder los 255 caracteres.',
            'medida_extendida_forro.required' => 'La medida extendida del forro es obligatoria.',
            'medida_extendida_forro.string' => 'La medida extendida del forro debe ser una cadena de texto.',
            'medida_extendida_forro.max' => 'La medida extendida del forro no puede exceder los 255 caracteres.',
            'barniz_frente_forro.required' => 'El barniz del frente del forro es obligatorio.',
            'barniz_frente_forro.exists' => 'El barniz del frente del forro seleccionado no existe.',
            'barniz_vuelta_forro.required' => 'El barniz de la vuelta del forro es obligatorio.',
            'barniz_vuelta_forro.exists' => 'El barniz de la vuelta del forro seleccionado no existe.',
            'num_paginas_interiores.required' => 'El número de páginas de los interiores es obligatorio.',
            'num_paginas_interiores.numeric' => 'El número de páginas de los interiores debe ser un número.',
            'num_paginas_interiores.min' => 'El número de páginas de los interiores debe ser al menos 1.',
            'sustrato_interiores.required' => 'El sustrato de los interiores es obligatorio.',
            'sustrato_interiores.exists' => 'El sustrato de los interiores seleccionado no existe.',
            'gramaje_interiores.required' => 'El gramaje de los interiores es obligatorio.',
            'gramaje_interiores.exists' => 'El gramaje de los interiores seleccionado no existe.',
            'tinta_frente_interiores.required' => 'La tinta del frente de los interiores es obligatoria.',
            'tinta_frente_interiores.exists' => 'La tinta del frente de los interiores seleccionada no existe.',
            'tinta_vuelta_interiores.required' => 'La tinta de la vuelta de los interiores es obligatoria.',
            'tinta_vuelta_interiores.exists' => 'La tinta de la vuelta de los interiores seleccionada no existe.',
            'medida_final_interiores.required' => 'La medida final de los interiores es obligatoria.',
            'medida_final_interiores.string' => 'La medida final de los interiores debe ser una cadena de texto.',
            'medida_final_interiores.max' => 'La medida final de los interiores no puede exceder los 255 caracteres.',
            'medida_extendida_interiores.required' => 'La medida extendida de los interiores es obligatoria.',
            'medida_extendida_interiores.string' => 'La medida extendida de los interiores debe ser una cadena de texto.',
            'medida_extendida_interiores.max' => 'La medida extendida de los interiores no puede exceder los 255 caracteres.',
            'barniz_frente_interiores.required' => 'El barniz del frente de los interiores es obligatorio.',
            'barniz_frente_interiores.exists' => 'El barniz del frente de los interiores seleccionado no existe.',
            'barniz_vuelta_interiores.required' => 'El barniz de la vuelta de los interiores es obligatorio.',
            'barniz_vuelta_interiores.exists' => 'El barniz de la vuelta de los interiores seleccionado no existe.',
            'num_paginas_guardas.required' => 'El número de páginas de las guardas es obligatorio.',
            'num_paginas_guardas.numeric' => 'El número de páginas de las guardas debe ser un número.',
            'num_paginas_guardas.min' => 'El número de páginas de las guardas debe ser al menos 1.',
            'sustrato_guardas.required' => 'El sustrato de las guardas es obligatorio.',
            'sustrato_guardas.exists' => 'El sustrato de las guardas seleccionado no existe.',
            'gramaje_guardas.required' => 'El gramaje de las guardas es obligatorio.',
            'gramaje_guardas.exists' => 'El gramaje de las guardas seleccionado no existe.',
            'tinta_frente_guardas.required' => 'La tinta del frente de las guardas es obligatoria.',
            'tinta_frente_guardas.exists' => 'La tinta del frente de las guardas seleccionada no existe.',
            'tinta_vuelta_guardas.required' => 'La tinta de la vuelta de las guardas es obligatoria.',
            'tinta_vuelta_guardas.exists' => 'La tinta de la vuelta de las guardas seleccionada no existe.',
            'medida_final_guardas.required' => 'La medida final de las guardas es obligatoria.',
            'medida_final_guardas.string' => 'La medida final de las guardas debe ser una cadena de texto.',
            'medida_final_guardas.max' => 'La medida final de las guardas no puede exceder los 255 caracteres.',
            'medida_extendida_guardas.required' => 'La medida extendida de las guardas es obligatoria.',
            'medida_extendida_guardas.string' => 'La medida extendida de las guardas debe ser una cadena de texto.',
            'medida_extendida_guardas.max' => 'La medida extendida de las guardas no puede exceder los 255 caracteres.',
            'barniz_frente_guardas.required' => 'El barniz del frente de las guardas es obligatorio.',
            'barniz_frente_guardas.exists' => 'El barniz del frente de las guardas seleccionado no existe.',
            'barniz_vuelta_guardas.required' => 'El barniz de la vuelta de las guardas es obligatorio.',
            'barniz_vuelta_guardas.exists' => 'El barniz de la vuelta de las guardas seleccionado no existe.',
            'metalizado_frente_forro.required' => 'El metalizado del frente del forro es obligatorio.',
            'metalizado_frente_forro.exists' => 'El metalizado del frente del forro seleccionado no existe.',
            'metalizado_vuelta_forro.required' => 'El metalizado de la vuelta del forro es obligatorio.',
            'metalizado_vuelta_forro.exists' => 'El metalizado de la vuelta del forro seleccionado no existe.',
            'laminado_frente_forro.required' => 'El laminado del frente del forro es obligatorio.',
            'laminado_frente_forro.exists' => 'El laminado del frente del forro seleccionado no existe.',
            'laminado_vuelta_forro.required' => 'El laminado de la vuelta del forro es obligatorio.',
            'laminado_vuelta_forro.exists' => 'El laminado de la vuelta del forro seleccionado no existe.',
            'barniz_sakurai_frente_forro.required' => 'El barniz Sakurai del frente del forro es obligatorio.',
            'barniz_sakurai_frente_forro.exists' => 'El barniz Sakurai del frente del forro seleccionado no existe.',
            'barniz_sakurai_vuelta_forro.required' => 'El barniz Sakurai de la vuelta del forro es obligatorio.',
            'barniz_sakurai_vuelta_forro.exists' => 'El barniz Sakurai de la vuelta del forro seleccionado no existe.',
            'realce_forro.required' => 'El realce del forro es obligatorio.',
            'realce_forro.exists' => 'El realce del forro seleccionado no existe.',
            'placa_realce_forro.required' => 'La placa de realce del forro es obligatoria.',
            'placa_realce_forro.string' => 'La placa de realce del forro debe ser una cadena de texto.',
            'placa_realce_forro.max' => 'La placa de realce del forro no puede exceder los 255 caracteres.',
            'hot_stamping_forro.required' => 'El hot stamping del forro es obligatorio.',
            'hot_stamping_forro.exists' => 'El hot stamping del forro seleccionado no existe.',
            'placa_hot_stamping_forro.required' => 'La placa de hot stamping del forro es obligatoria.',
            'placa_hot_stamping_forro.string' => 'La placa de hot stamping del forro debe ser una cadena de texto.',
            'placa_hot_stamping_forro.max' => 'La placa de hot stamping del forro no puede exceder los 255 caracteres.',
            'color_hot_stamping_forro.required' => 'El color de hot stamping del forro es obligatorio.',
            'color_hot_stamping_forro.exists' => 'El color de hot stamping del forro seleccionado no existe.',
            'suaje_forro.required' => 'El suaje del forro es obligatorio.',
            'suaje_forro.exists' => 'El suaje del forro seleccionado no existe.',
            'metalizado_frente_interiores.required' => 'El metalizado del frente de los interiores es obligatorio.',
            'metalizado_frente_interiores.exists' => 'El metalizado del frente de los interiores seleccionado no existe.',
            'metalizado_vuelta_interiores.required' => 'El metalizado de la vuelta de los interiores es obligatorio.',
            'metalizado_vuelta_interiores.exists' => 'El metalizado de la vuelta de los interiores seleccionado no existe.',
            'laminado_frente_interiores.required' => 'El laminado del frente de los interiores es obligatorio.',
            'laminado_frente_interiores.exists' => 'El laminado del frente de los interiores seleccionado no existe.',
            'laminado_vuelta_interiores.required' => 'El laminado de la vuelta de los interiores es obligatorio.',
            'laminado_vuelta_interiores.exists' => 'El laminado de la vuelta de los interiores seleccionado no existe.',
            'barniz_sakurai_frente_interiores.required' => 'El barniz Sakurai del frente de los interiores es obligatorio.',
            'barniz_sakurai_frente_interiores.exists' => 'El barniz Sakurai del frente de los interiores seleccionado no existe.',
            'barniz_sakurai_vuelta_interiores.required' => 'El barniz Sakurai de la vuelta de los interiores es obligatorio.',
            'barniz_sakurai_vuelta_interiores.exists' => 'El barniz Sakurai de la vuelta de los interiores seleccionado no existe.',
            'doblez.required' => 'El dobladillo es obligatorio.',
            'doblez.exists' => 'El dobladillo seleccionado no existe.',
            'embolsado_retractilado.required' => 'El embolsado o retractilado es obligatorio.',
            'embolsado_retractilado.exists' => 'El embolsado o retractilado seleccionado no existe.',
            'perforado.required' => 'El perforado es obligatorio.',
            'perforado.exists' => 'El perforado seleccionado no existe.',
            'otros_interiores.required' => 'Los otros interiores son obligatorios.',
            'otros_interiores.exists' => 'Los otros interiores seleccionados no existen.',
            'encuadernado.required' => 'El encuadernado es obligatorio.',
            'encuadernado.exists' => 'El encuadernado seleccionado no existe.',
            'empaque.required' => 'El empaque es obligatorio.',
            'empaque.exists' => 'El empaque seleccionado no existe.',
            'entrega.required' => 'La entrega es obligatoria.',
            'entrega.exists' => 'La entrega seleccionada no existe.',
            'lugar_entrega.required' => 'El lugar de entrega es obligatorio.',
            'lugar_entrega.exists' => 'El lugar de entrega seleccionado no existe.',
            'observaciones.string' => 'Las observaciones deben ser una cadena de texto.',
            'observaciones.max' => 'Las observaciones no pueden exceder los 255 caracteres.',
            'suaje_interiores.required' => 'El suaje de los interiores es obligatorio.',
            'suaje_interiores.exists' => 'El suaje de los interiores seleccionado no existe.',
            'suajado.required' => 'El suajado es obligatorio.',
            'suajado.exists' => 'El suajado seleccionado no existe.',
            'suajado_forro.required' => 'El suajado del forro es obligatorio.',
            'suajado_forro.exists' => 'El suajado del forro seleccionado no existe.',
            'observaciones_presupuesto.nullable' => 'Las observaciones para el presupuesto son obligatorias.',
            'observaciones_presupuesto.string' => 'Las observaciones para el presupuesto deben ser una cadena de texto.',
        ];
    }
}
