<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSolicitudCotizacionDisplayRequest extends FormRequest
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
            'sustrato' => 'required|exists:cat_EP_detalle,id',
            // 'gramaje' => 'required|exists:cat_EP_detalle,id',
            'tinta_frente' => 'required|exists:cat_EP_detalle,id',
            'medida_final' => 'required|exists:cat_EP_detalle,id',
            'medida_extendida' => 'required|exists:cat_EP_detalle,id',
            'sustrato_empalme' => 'required|exists:cat_EP_detalle,id',
            'otros_display' => 'nullable|exists:cat_EP_detalle,id',
            'metalizado_frente' => 'required|exists:cat_EP_detalle,id',
            'metalizado_vuelta' => 'required|exists:cat_EP_detalle,id',
            'laminado_frente' => 'required|exists:cat_EP_detalle,id',
            'laminado_vuelta' => 'required|exists:cat_EP_detalle,id',
            'barniz_sakurai_frente' => 'required|exists:cat_EP_detalle,id',
            'barniz_sakurai_vuelta' => 'required|exists:cat_EP_detalle,id',
            'otros_metalizado_frente' => 'nullable|exists:cat_EP_detalle,id',
            // 'otros_metalizado_vuelta' => 'nullable|exists:cat_EP_detalle,id',
            // 'otros_laminado_frente' => 'nullable|exists:cat_EP_detalle,id',
            // 'otros_laminado_vuelta' => 'nullable|exists:cat_EP_detalle,id',
            // 'otros_barniz_frente' => 'nullable|exists:cat_EP_detalle,id',
            // 'otros_barniz_vuelta' => 'nullable|exists:cat_EP_detalle,id',
            'tipo_pegue_armado' => 'required|exists:cat_EP_detalle,id',
            'empaque_primario' => 'required|exists:cat_EP_detalle,id',
            'empaque_secundario' => 'required|exists:cat_EP_detalle,id',
            'lugar_entrega' => 'required|exists:cat_EP_detalle,id',
            'observaciones' => 'nullable|string',
            'observaciones_presupuesto' => 'nullable|string',
            
            'suajado' => 'required|exists:cat_EP_detalle,id',
        ];
    }

    /**
     * Get the custom messages for the validation rules.
     * 
     * @return array
     */
    public function messages()
    {
        return [
            'sustrato.required' => 'Se requiere seleccionar un sustrato.',
            'sustrato.exists' => 'El sustrato seleccionado no es válido.',
            'gramaje.required' => 'Se requiere seleccionar un gramaje.',
            'gramaje.exists' => 'El gramaje seleccionado no es válido.',
            'tinta_frente.required' => 'Se requiere seleccionar una tinta para el frente.',
            'tinta_frente.exists' => 'La tinta del frente seleccionada no es válida.',
            'medida_final.required' => 'Se requiere seleccionar una medida final.',
            'medida_final.exists' => 'La medida final seleccionada no es válida.',
            'medida_extendida.required' => 'Se requiere seleccionar una medida extendida.',
            'medida_extendida.exists' => 'La medida extendida seleccionada no es válida.',
            'sustrato_empalme.required' => 'Se requiere seleccionar un sustrato de empalme.',
            'sustrato_empalme.exists' => 'El sustrato de empalme seleccionado no es válido.',
            'otros_display.required' => 'Se requiere seleccionar una opción para otros display.',
            'otros_display.exists' => 'La opción seleccionada para otros display no es válida.',
            'metalizado_frente.required' => 'Se requiere seleccionar un metalizado para el frente.',
            'metalizado_frente.exists' => 'El metalizado del frente seleccionado no es válido.',
            'metalizado_vuelta.required' => 'Se requiere seleccionar un metalizado para la vuelta.',
            'metalizado_vuelta.exists' => 'El metalizado de la vuelta seleccionado no es válido.',
            'laminado_frente.required' => 'Se requiere seleccionar un laminado para el frente.',
            'laminado_frente.exists' => 'El laminado del frente seleccionado no es válido.',
            'laminado_vuelta.required' => 'Se requiere seleccionar un laminado para la vuelta.',
            'laminado_vuelta.exists' => 'El laminado de la vuelta seleccionado no es válido.',
            'barniz_sakurai_frente.required' => 'Se requiere seleccionar un barniz sakurai para el frente.',
            'barniz_sakurai_frente.exists' => 'El barniz sakurai del frente seleccionado no es válido.',
            'barniz_sakurai_vuelta.required' => 'Se requiere seleccionar un barniz sakurai para la vuelta.',
            'barniz_sakurai_vuelta.exists' => 'El barniz sakurai de la vuelta seleccionado no es válido.',
            'otros_metalizado_frente.exists' => 'La opción seleccionada para otros metalizado del frente no es válida.',
            'otros_metalizado_vuelta.exists' => 'La opción seleccionada para otros metalizado de la vuelta no es válida.',
            'otros_laminado_frente.exists' => 'La opción seleccionada para otros laminado del frente no es válida.',
            'otros_laminado_vuelta.exists' => 'La opción seleccionada para otros laminado de la vuelta no es válida.',
            'otros_barniz_frente.exists' => 'La opción seleccionada para otros barniz del frente no es válida.',
            'otros_barniz_vuelta.exists' => 'La opción seleccionada para otros barniz de la vuelta no es válida.',
            'tipo_pegue_armado.required' => 'Se requiere seleccionar un tipo de pegue/armado.',
            'tipo_pegue_armado.exists' => 'El tipo de pegue/armado seleccionado no es válido.',
            'empaque_primario.required' => 'Se requiere seleccionar un empaque primario.',
            'empaque_primario.exists' => 'El empaque primario seleccionado no es válido.',
            'empaque_secundario.required' => 'Se requiere seleccionar un empaque secundario.',
            'empaque_secundario.exists' => 'El empaque secundario seleccionado no es válido.',
            'lugar_entrega.required' => 'Se requiere seleccionar un lugar de entrega.',
            'lugar_entrega.exists' => 'El lugar de entrega seleccionado no es válido.',
            'observaciones.string' => 'Las observaciones deben ser un texto.',
            'observaciones.max' => 'Las observaciones no pueden exceder los 255 caracteres.',
            'suajado.required' => 'El suajado es obligatorio.',
            'suajado.exists' => 'El suajado seleccionado no es válido.',
        ];
    }
}
