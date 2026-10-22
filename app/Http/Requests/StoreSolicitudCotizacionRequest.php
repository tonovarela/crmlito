<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSolicitudCotizacionRequest extends FormRequest
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
            'oportunidad_id' => 'required|exists:oportunidades,idOportunidad',
            'vendedor_id' => 'required|exists:v_catAgentesCLogin,Id_Usuario',
            'vendedor_abr' => 'required|exists:v_catAgentesCLogin,Agente',
            'cliente_id' => 'required|exists:v_catClientes,idCliente',
            'contacto_id' => 'required|exists:v_catContactos,idContacto',
            'nombre_proyecto' => 'required|string|max:255',
            'comision_externa' => 'nullable|numeric',
            'cantidad_cotizar_1' => 'required|numeric|min:1',
            'cantidad_cotizar_2' => 'nullable|numeric',
            'cantidad_cotizar_3' => 'nullable|numeric',
            'cat_EP_tipoProducto_id' => 'required|exists:cat_EP_tipoProducto,id',
            'plantilla_id' => 'required|exists:cat_EP_plantillas,id',
            'observaciones_presupuesto' => 'nullable|string',
        ];
    }

    /**
     * Get the custom messages for the validation rules.
     * 
     * @return array
     **/
    public function messages()
    {
        return [
            'oportunidad_id.required' => 'Se requiere seleccionar una Oportunidad.',
            'oportunidad_id.exists' => 'La Oportunidad seleccionada no existe.',
            'vendedor_id.required' => 'Se requiere seleccionar un Vendedor.',
            'vendedor_id.exists' => 'El Vendedor seleccionado no existe.',
            'vendedor_abr.required' => 'Se requiere seleccionar un Vendedor Abreviado.',
            'vendedor_abr.exists' => 'El Vendedor Abreviado seleccionado no existe.',
            'cliente_id.required' => 'Se requiere seleccionar un Cliente.',
            'cliente_id.exists' => 'El Cliente seleccionado no existe.',
            'contacto_id.required' => 'Se requiere seleccionar un Contacto.',
            'contacto_id.exists' => 'El Contacto seleccionado no existe.',
            'nombre_proyecto.required' => 'Se requiere seleccionar un Nombre del Proyecto.',
            'nombre_proyecto.exists' => 'El Nombre del Proyecto seleccionado no existe.',
            'comision_externa.numeric' => 'Se requiere que la Comisión Externa sea un número.',
            'cantidad_cotizar_1.required' => 'Se requiere ingresar la Cantidad a Cotizar 1.',
            'cantidad_cotizar_1.numeric' => 'Se requiere que la Cantidad a Cotizar 1 sea un número.',
            'cantidad_cotizar_1.min' => 'La Cantidad a Cotizar 1 debe ser al menos 1.',
            'cantidad_cotizar_2.numeric' => 'Se requiere que la Cantidad a Cotizar 2 sea un número.',
            'cantidad_cotizar_3.numeric' => 'Se requiere que la Cantidad a Cotizar 3 sea un número.',
            'cat_EP_tipoProducto_id.required' => 'Se requiere seleccionar un Tipo de Producto.',
            'cat_EP_tipoProducto_id.exists' => 'El Tipo de Producto seleccionado no existe.',
            'plantilla_id.required' => 'Se requiere seleccionar una Plantilla.',
            'plantilla_id.exists' => 'La Plantilla seleccionada no existe.',
        ];
    }
}
