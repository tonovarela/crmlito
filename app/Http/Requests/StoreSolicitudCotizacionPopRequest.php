<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSolicitudCotizacionPopRequest extends FormRequest
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
            'tinta_vuelta' => 'required|exists:cat_EP_detalle,id',
            'medida_final' => 'required|string|max:255',
            'medida_extendida' => 'required|string|max:255',
            'barniz_frente' => 'required|exists:cat_EP_detalle,id',
            'barniz_vuelta' => 'required|exists:cat_EP_detalle,id',
            'num_cambios' => 'required|exists:cat_EP_detalle,id',
            'trazo_cliente' => 'required|exists:cat_EP_detalle,id',
            'trazo_estructural' => 'required|exists:cat_EP_detalle,id',
            'sustrato_empalme' => 'required|exists:cat_EP_detalle,id',
            'otros_pop' => 'nullable|exists:cat_EP_detalle,id',

            'metalizado_frente' => 'required|exists:cat_EP_detalle,id',
            'metalizado_vuelta' => 'required|exists:cat_EP_detalle,id',
            'laminado_frente' => 'required|exists:cat_EP_detalle,id',
            'laminado_vuelta' => 'required|exists:cat_EP_detalle,id',
            'barniz_sakurai_frente' => 'required|exists:cat_EP_detalle,id',
            'barniz_sakurai_vuelta' => 'required|exists:cat_EP_detalle,id',
            'realce' => 'required|exists:cat_EP_detalle,id',
            'hot_stamping' => 'required|exists:cat_EP_detalle,id',
            'color_hot_stamping' => 'required|exists:cat_EP_detalle,id',
            'suaje' => 'required|exists:cat_EP_detalle,id',
            'termodoblez' => 'required|exists:cat_EP_detalle,id',
            'hilo_monofilamento' => 'required|exists:cat_EP_detalle,id',
            'cinta_doble_cara' => 'required|exists:cat_EP_detalle,id',
            'cantidad_cintas_x_pieza' => 'required|exists:cat_EP_detalle,id',
            'otros_acabados' => 'nullable|exists:cat_EP_detalle,id',

            'placa_realce' => 'required_unless:realce,288|string|max:255',
            'placa_hot_stamping' => 'required_unless:hot_stamping,288|string|max:255',
            'cm_hilo_x_pieza' => 'required_unless:hilo_monofilamento,432|numeric',
            'medida_cada_cinta' => 'required_unless:cantidad_cintas_x_pieza,446|numeric',

            'adicional' => 'required|exists:cat_EP_detalle,id',
            'empaque' => 'required|exists:cat_EP_detalle,id',
            'entrega' => 'required|exists:cat_EP_detalle,id',
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
            'sustrato.required' => 'Se requiere seleccionar un Sustrato.',
            'sustrato.exists' => 'El Sustrato seleccionado no existe.',
            'gramaje.required' => 'Se requiere seleccionar un Gramaje.',
            'gramaje.exists' => 'El Gramaje seleccionado no existe.',
            'tinta_frente.required' => 'Se requiere seleccionar una Tinta Frente.',
            'tinta_frente.exists' => 'La Tinta Frente seleccionada no existe.',
            'tinta_vuelta.required' => 'Se requiere seleccionar una Tinta Vuelta.',
            'tinta_vuelta.exists' => 'La Tinta Vuelta seleccionada no existe.',
            'medida_final.required' => 'Se requiere ingresar la Medida Final.',
            'medida_final.string' => 'La Medida Final es requerida',
            'medida_final.max' => 'La Medida Final no puede exceder los 255 caracteres.',
            'medida_extendida.required' => 'Se requiere ingresar la Medida Extendida.',
            'medida_extendida.string' => 'La Medida Extendida es requerida',
            'medida_extendida.max' => 'La Medida Extendida no puede exceder los 255 caracteres.',
            'barniz_frente.required' => 'Se requiere seleccionar un Barniz Frente.',
            'barniz_frente.exists' => 'El Barniz Frente seleccionado no existe.',
            'barniz_vuelta.required' => 'Se requiere seleccionar un Barniz Vuelta.',
            'barniz_vuelta.exists' => 'El Barniz Vuelta seleccionado no existe.',
            'num_cambios.required' => 'Se requiere seleccionar un Número de Cambios.',
            'num_cambios.exists' => 'El Número de Cambios seleccionado no existe.',
            'trazo_cliente.required' => 'Se requiere seleccionar un Trazo Cliente.',
            'trazo_cliente.exists' => 'El Trazo Cliente seleccionado no existe.',
            'trazo_estructural.required' => 'Se requiere seleccionar un Trazo Estructural.',
            'trazo_estructural.exists' => 'El Trazo Estructural seleccionado no existe.',
            'sustrato_empalme.required' => 'Se requiere seleccionar un Sustrato de Empalme.',
            'sustrato_empalme.exists' => 'El Sustrato de Empalme seleccionado no existe.',
            'otros_pop.exists' => 'El Otro POP seleccionado no existe.',
            'metalizado_frente.required' => 'Se requiere seleccionar un Metalizado Frente.',
            'metalizado_frente.exists' => 'El Metalizado Frente seleccionado no existe.',
            'metalizado_vuelta.required' => 'Se requiere seleccionar un Metalizado Vuelta.',
            'metalizado_vuelta.exists' => 'El Metalizado Vuelta seleccionado no existe.',
            'laminado_frente.required' => 'Se requiere seleccionar un Laminado Frente.',
            'laminado_frente.exists' => 'El Laminado Frente seleccionado no existe.',
            'laminado_vuelta.required' => 'Se requiere seleccionar un Laminado Vuelta.',
            'laminado_vuelta.exists' => 'El Laminado Vuelta seleccionado no existe.',
            'barniz_sakurai_frente.required' => 'Se requiere seleccionar un Barniz Sakurai Frente.',
            'barniz_sakurai_frente.exists' => 'El Barniz Sakurai Frente seleccionado no existe.',
            'barniz_sakurai_vuelta.required' => 'Se requiere seleccionar un Barniz Sakurai Vuelta.',
            'barniz_sakurai_vuelta.exists' => 'El Barniz Sakurai Vuelta seleccionado no existe.',
            'realce.required' => 'Se requiere seleccionar un Realce.',
            'realce.exists' => 'El Realce seleccionado no existe.',
            'placa_realce.required' => 'Se requiere ingresar la Placa de Realce.',
            'placa_realce.string' => 'La Placa de Realce se requiere.',
            'placa_realce.max' => 'La Placa de Realce no puede exceder los 255 caracteres.',
            'hot_stamping.required' => 'Se requiere seleccionar un Hot Stamping.',
            'hot_stamping.exists' => 'El Hot Stamping seleccionado no existe.',
            'placa_hot_stamping.required' => 'Se requiere ingresar la Placa de Hot Stamping.',
            'placa_hot_stamping.string' => 'La Placa de Hot se requiere.',
            'placa_hot_stamping.max' => 'La Placa de Hot no puede exceder los 255 caracteres.',
            'color_hot_stamping.required' => 'Se requiere seleccionar un Color de Hot Stamping.',
            'color_hot_stamping.exists' => 'El Color de Hot Stamping seleccionado no existe.',
            'suaje.required' => 'Se requiere seleccionar un Suaje.',
            'suaje.exists' => 'El Suaje seleccionado no existe.',
            'termodoblez.required' => 'Se requiere seleccionar un Termodoblez.',
            'termodoblez.exists' => 'El Termodoblez seleccionado no existe.',
            'hilo_monofilamento.required' => 'Se requiere seleccionar un Hilo Monofilamento.',
            'hilo_monofilamento.exists' => 'El Hilo Monofilamento seleccionado no existe.',
            'cm_hilo_x_pieza.required' => 'Se requiere ingresar el CM de Hilo por Pieza.',
            'cm_hilo_x_pieza.numeric' => 'El CM de Hilo por Pieza debe ser un número.',
            'cinta_doble_cara.required' => 'Se requiere seleccionar una Cinta Doble Cara.',
            'cinta_doble_cara.exists' => 'La Cinta Doble Cara seleccionada no existe.',
            'cantidad_cintas_x_pieza.required' => 'Se requiere seleccionar la Cantidad de Cintas por Pieza.',
            'cantidad_cintas_x_pieza.numeric' => 'La Cantidad de Cintas por Pieza debe ser un número.',
            'medida_cada_cinta.required' => 'Se requiere ingresar la Medida de Cada Cinta.',
            'medida_cada_cinta.numeric' => 'La Medida de Cada Cinta debe ser un número.',
            'otros_acabados.exists' => 'El Otro Acabado seleccionado no existe.',
            'adicional.required' => 'Se requiere seleccionar un Adicional.',
            'adicional.exists' => 'El Adicional seleccionado no existe.',
            'empaque.required' => 'Se requiere seleccionar un Empaque.',
            'empaque.exists' => 'El Empaque seleccionado no existe.',
            'entrega.required' => 'Se requiere seleccionar una Entrega.',
            'entrega.exists' => 'La Entrega seleccionada no existe.',
            'lugar_entrega.required' => 'Se requiere seleccionar un Lugar de Entrega.',
            'lugar_entrega.exists' => 'El Lugar de Entrega seleccionado no existe.',
            'observaciones.string' => 'Las Observaciones deben ser un texto.',
            'observaciones.max' => 'Las Observaciones no pueden exceder los 255 caracteres.',
            'suajado.required' => 'El Suajado es obligatorio.',
            'suajado.exists' => 'El Suajado seleccionado no es válido.',
        ];
    }
}
