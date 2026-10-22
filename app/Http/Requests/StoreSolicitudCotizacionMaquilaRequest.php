<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSolicitudCotizacionMaquilaRequest extends FormRequest
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
            'empaque' => 'required|exists:cat_EP_detalle,id',
            'entrega' => 'required|exists:cat_EP_detalle,id',
            'lugar_entrega' => 'required|exists:cat_EP_detalle,id',
            'descripcion' => 'nullable|string',
            'observaciones' => 'nullable|string',
            'observaciones_presupuesto' => 'nullable|string',
            
            'instalacion' => 'nullable|string',
            'proveedor_sugerido' => 'nullable|string',
            // 'suajado' => 'required|exists:cat_EP_detalle,id',
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
            'empaque.required' => 'El campo empaque es obligatorio.',
            'entrega.required' => 'El campo entrega es obligatorio.',
            'lugar_entrega.required' => 'El campo lugar de entrega es obligatorio.',
            'descripcion.string' => 'La descripción debe ser una cadena de texto.',
            'observaciones.string' => 'Las observaciones deben ser una cadena de texto.',
            'instalacion.string' => 'La instalación debe ser una cadena de texto.',
            'proveedor_sugerido.string' => 'El proveedor sugerido debe ser una cadena de texto.',
            'suajado.required' => 'El suajado es obligatorio.',
            'suajado.exists' => 'El suajado seleccionado no es válido.',
        ];
    }
}
