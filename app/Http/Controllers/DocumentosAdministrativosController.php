<?php

namespace App\Http\Controllers;

use App\catRequisitos;
use App\ClienteRequisitos;
use App\Http\Modelos\ClienteRequisitosAsignados;
use App\Http\Modelos\DocumentosOport;
use App\Http\Modelos\DocumentosPedidos;
use App\Http\Modelos\DocumentosRequisitosAsignados;
use App\Http\Modelos\v_catRequisitosClientePedido;
use App\Http\Modelos\relOport_Documento;
use App\Traits\BitacoraTrait;
use App\Traits\PermisosTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\DataTables;

class DocumentosAdministrativosController extends Controller
{
    use PermisosTrait;
    use BitacoraTrait;

    public $modulo = 'Documentos Administrativos';

    public function index(Request $request)
    {
        $this->permisos();

        $Login = $this->loginUsuario();

        if ($Login == null) {
            return view('iniciar');
        }
        $fecha = new \DateTime();
        $dia = $fecha->format('D');
        // if (($dia == $this->diaSemanaEN() && count($this->entregasSinRespuesta()) > 0) || ($dia == $this->diaSemanaMX() && count($this->entregasSinRespuesta()) > 0)) {
        //     return view('lista_entregas');
        // } else if (count($this->entregasSinRespuesta()) > 0) {
        //     return view('lista_entregas');
        // } else if ($dia == $this->diaSemanaEN() || $dia == $this->diaSemanaMX() || count($this->entregasSinRespuesta()) == 0) {
            $permisos = $this->permisosModulo($this->modulo);
            $id_usuario = $this->idUsuario();

            $combo_clientes = DB::select('exec GetClientesCombo');

            $lista_requisitos = ClienteRequisitos::all();

            $cat_requisitos = catRequisitos::where('estatusRequisito', 1)->get();
            $permisos_count = count($permisos);

            for ($i = 0; $i < $permisos_count; $i++) {
                if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Todo') {
                    return view('documentos_administrativos.index', compact('combo_clientes', 'lista_requisitos', 'cat_requisitos'));
                } else if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Ninguno') {
                    return redirect()->route('Dashboard');
                }
            }
        // }
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function agregarRequisitos(Request $request)
    {
        $idCliente = $request->idCliente;
        $idPedido = $request->idPedido;

        $requisitos_agregados = [];

        $requisitos_cliente = ClienteRequisitosAsignados::where('idCliente', $idCliente)->get();

        $requisitos_asignados = DocumentosRequisitosAsignados::where('idCliente', $idCliente)->where('idPedido', $idPedido)->get();

        if ($requisitos_cliente != null) {
            if ($requisitos_asignados == null) {
                foreach ($requisitos_cliente as $requisito) {
                    $requisitos_agregados = [
                        'idCliente' => $idCliente,
                        'idPedido' => $idPedido,
                        'idRequisito' => $requisito->idRequisito,
                        'descRequisito' => $requisito->descRequisito,
                        'CXC' => $requisito->CXC,
                        'Entrega' => $requisito->Entrega,
                        'Factura' => $requisito->Factura,
                        'DocCXC' => 0,
                        'DocEntrega' => 0,
                        'DocFactura' => 0,
                    ];

                    DocumentosRequisitosAsignados::create($requisitos_agregados);
                }
            } else {
                foreach ($requisitos_cliente as $requisito) {

                    $requisitos_asignados = DocumentosRequisitosAsignados::where('idCliente', $idCliente)->where('idPedido', $idPedido)->where('idRequisito', $requisito->idRequisito)->first();

                    if ($requisitos_asignados == null) {
                        $requisitos_agregados = [
                            'idCliente' => $idCliente,
                            'idPedido' => $idPedido,
                            'idRequisito' => $requisito->idRequisito,
                            'descRequisito' => $requisito->descRequisito,
                            'CXC' => $requisito->CXC,
                            'Entrega' => $requisito->Entrega,
                            'Factura' => $requisito->Factura,
                            'DocCXC' => 0,
                            'DocEntrega' => 0,
                            'DocFactura' => 0,
                        ];

                        DocumentosRequisitosAsignados::create($requisitos_agregados);
                    }
                }
            }
        }
    }

    public function listaRequisitos(Request $request)
    {
        $idCliente = $request->idCliente;
        $idPedido = $request->idPedido;

        $lista_requisitos = v_catRequisitosClientePedido::orderBy('idRequisito', 'asc')->where('idCliente', $idCliente)->where('idPedido', $idPedido)->get();

        return DataTables::of($lista_requisitos)
            ->make(true);
    }

    public function getDocumento(Request $request)
    {
        $idDocumentosAsignados = $request->idDocumentosAsignados;

        $result = DocumentosPedidos::where('idDocumentosAsignados', $idDocumentosAsignados)->first();

        return $result;
    }

    public function apiPedidos(Request $request)
    {
        $idCliente = $request->idCliente;

        $pedidos = DB::select('exec GetPedidosXCliente @idCliente=' . $idCliente);

        return DataTables::of($pedidos)
            ->make(true);
    }

    public function deleteDocAdmins(Request $request)
    {
        $idDocumentodelete = $request->idDocumentodelete;
        $idDocumentodelete = (int)$idDocumentodelete;

        $documento = relOport_Documento::where('idDocumento', $idDocumentodelete)->first();

        DocumentosOport::where('idDocumento', $idDocumentodelete)->delete();

        $this->bitacora($this->modulo, 'Eliminar Documento ' . $documento->descDocumento . ' Tipo Documento ' . $documento->descTipoDocumento, ' Oportunidad ', $documento->idOportunidad, $this->loginUsuario(), 3);
    }

    public function updocadmins(Request $request)
    {

        $idDocumentosAsignados = $request->idDocumentosAsignados;

        $documento_pedido = DocumentosPedidos::where('idDocumentosAsignados', $idDocumentosAsignados)->first();

        $pedido_cliente = DocumentosRequisitosAsignados::where('idDocumentosAsignados', $idDocumentosAsignados)->first();

        $documento_x_pedido = [];
        $pedido_x_cliente = [];

        $docEntregaPedido = $pedido_cliente->DocEntrega;
        $docFacturaPedido = $pedido_cliente->DocFactura;
        $docCXCPedido = $pedido_cliente->DocCXC;
        $pedido = $pedido_cliente->idPedido;
        $cliente = $pedido_cliente->idCliente;

        $fileEntrega = '';
        $fileFactura = '';
        $fileCXC = '';
        $ExtensionEntrega = null;
        $ExtensionFactura = null;
        $ExtensionCXC = null;
        $SizeEntrega = null;
        $SizeFactura = null;
        $SizeCXC = null;
        $descDocumentoEntrega =  null;
        $descDocumentoFactura =  null;
        $descDocumentoCXC =  null;
        $DocumentoEntrega = null;
        $DocumentoFactura = null;
        $DocumentoCXC = null;

        if ($request->tipoDocumento == 'DocEntrega') {
            $docEntregaPedido = 1;
            $fileEntrega = $_FILES['file'];
            $ExtensionEntrega = $fileEntrega['type'];
            $SizeEntrega = $fileEntrega['size'];
            $descDocumentoEntrega =  $fileEntrega['name'];
            $DocumentoEntrega = base64_encode(file_get_contents($_FILES["file"]["tmp_name"]));

            if ($documento_pedido != null) {

                $ExtensionFactura = $documento_pedido->ExtensionFactura;
                $SizeFactura = $documento_pedido->SizeFactura;
                $descDocumentoFactura =  $documento_pedido->descDocumentoFactura;
                $DocumentoFactura = $documento_pedido->DocumentoFactura;

                $ExtensionCXC = $documento_pedido->ExtensionCXC;
                $SizeCXC = $documento_pedido->SizeCXC;
                $descDocumentoCXC =  $documento_pedido->descDocumentoCXC;
                $DocumentoCXC = $documento_pedido->DocumentoCXC;
            }
        } else if ($request->tipoDocumento == 'DocFactura') {
            $docFacturaPedido = 1;
            $fileFactura = $_FILES['file'];
            $ExtensionFactura = $fileFactura['type'];
            $SizeFactura = $fileFactura['size'];
            $descDocumentoFactura =  $fileFactura['name'];
            $DocumentoFactura = base64_encode(file_get_contents($_FILES["file"]["tmp_name"]));

            if ($documento_pedido != null) {
                $ExtensionEntrega = $documento_pedido->ExtensionEntrega;
                $SizeEntrega = $documento_pedido->SizeEntrega;
                $descDocumentoEntrega =  $documento_pedido->descDocumentoEntrega;
                $DocumentoEntrega = $documento_pedido->DocumentoEntrega;

                $ExtensionCXC = $documento_pedido->ExtensionCXC;
                $SizeCXC = $documento_pedido->SizeCXC;
                $descDocumentoCXC =  $documento_pedido->descDocumentoCXC;
                $DocumentoCXC = $documento_pedido->DocumentoCXC;
            }
        } else if ($request->tipoDocumento == 'DocCXC') {
            $docCXCPedido = 1;
            $fileCXC = $_FILES['file'];
            $ExtensionCXC = $fileCXC['type'];
            $SizeCXC = $fileCXC['size'];
            $descDocumentoCXC =  $fileCXC['name'];
            $DocumentoCXC = base64_encode(file_get_contents($_FILES["file"]["tmp_name"]));

            if ($documento_pedido != null) {

                $ExtensionFactura = $documento_pedido->ExtensionFactura;
                $SizeFactura = $documento_pedido->SizeFactura;
                $descDocumentoFactura =  $documento_pedido->descDocumentoFactura;
                $DocumentoFactura = $documento_pedido->DocumentoFactura;

                $ExtensionEntrega = $documento_pedido->ExtensionEntrega;
                $SizeEntrega = $documento_pedido->SizeEntrega;
                $descDocumentoEntrega =  $documento_pedido->descDocumentoEntrega;
                $DocumentoEntrega = $documento_pedido->DocumentoEntrega;
            }
        }

        $pedido_x_cliente = [
            'CXC' => $pedido_cliente->CXC,
            'DocCXC' => $docCXCPedido,
            'DocEntrega' => $docEntregaPedido,
            'DocFactura' => $docFacturaPedido,
            'Entrega' => $pedido_cliente->Entrega,
            'Factura' => $pedido_cliente->Factura,
            'descRequisito' => $pedido_cliente->descRequisito,
            'idCliente' => $pedido_cliente->idCliente,
            'idPedido' => $pedido_cliente->idPedido,
            'idRequisito' => $pedido_cliente->idRequisito,
        ];

        $pedido_cliente->update($pedido_x_cliente);

        if ($documento_pedido == null) {
            $documento_x_pedido = [
                'idDocumentosAsignados' => $idDocumentosAsignados,
                'descDocumentoCXC' => $descDocumentoCXC,
                'descDocumentoEntrega' => $descDocumentoEntrega,
                'descDocumentoFactura' => $descDocumentoFactura,
                'DocumentoCXC' => $DocumentoCXC,
                'DocumentoEntrega' => $DocumentoEntrega,
                'DocumentoFactura' => $DocumentoFactura,
                'ExtensionCXC' => $ExtensionCXC,
                'ExtensionEntrega' => $ExtensionEntrega,
                'ExtensionFactura' => $ExtensionFactura,
                'SizeCXC' => $SizeCXC,
                'SizeEntrega' => $SizeEntrega,
                'SizeFactura' => $SizeFactura,
            ];

            DocumentosPedidos::create($documento_x_pedido);

            $this->bitacora($this->modulo, 'Agregar Documento Pedido ' . (string)$pedido . ' Tipo Documento ' . $request->tipoDocumento . ' Documento ' . (int)$idDocumentosAsignados, $request->tipoDocumento, (int)$idDocumentosAsignados, $this->loginUsuario(), 5);
        } else {
            $documento_x_pedido = [
                'idDocumentosAsignados' => $documento_pedido->idDocumentosAsignados,
                'descDocumentoCXC' => $descDocumentoCXC,
                'descDocumentoEntrega' => $descDocumentoEntrega,
                'descDocumentoFactura' => $descDocumentoFactura,
                'DocumentoCXC' => $DocumentoCXC,
                'DocumentoEntrega' => $DocumentoEntrega,
                'DocumentoFactura' => $DocumentoFactura,
                'ExtensionCXC' => $ExtensionCXC,
                'ExtensionEntrega' => $ExtensionEntrega,
                'ExtensionFactura' => $ExtensionFactura,
                'SizeCXC' => $SizeCXC,
                'SizeEntrega' => $SizeEntrega,
                'SizeFactura' => $SizeFactura,
            ];


            $documento_pedido->update($documento_x_pedido);

            $this->bitacora($this->modulo, 'Actualizar Documento Pedido ' . (string)$pedido . ' Tipo Documento ' . $request->tipoDocumento . ' Documento ' . (int)$idDocumentosAsignados, $request->tipoDocumento, (int)$idDocumentosAsignados, $this->loginUsuario(), 2);
        }
    }

    public function downDocsAdmin($idDoc, $tipo)
    {
        $idDocumentosAsignados = $idDoc;
        $result = DocumentosPedidos::where('idDocumentosAsignados', $idDocumentosAsignados)->first();

        if ($tipo == 'DocEntrega') {
            $file = $result->DocumentoEntrega;
            $Tipo = $result->ExtensionEntrega;
            $descDocumento = $result->descDocumentoEntrega;
        } else if ($tipo == 'DocFactura') {
            $file = $result->DocumentoFactura;
            $Tipo = $result->ExtensionFactura;
            $descDocumento = $result->descDocumentoFactura;
        } else if ($tipo == 'DocCXC') {
            $file = $result->DocumentoCXC;
            $Tipo = $result->ExtensionCXC;
            $descDocumento = $result->descDocumentoCXC;
        }

        $base64 = base64_decode($file);

        return Response::make($base64, 200, ['Content-Type' => $Tipo, 'Content-Disposition' => 'attachment; filename="' . $descDocumento . '"']);
    }
}
