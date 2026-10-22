<?php

namespace App\Http\Controllers;

use App\catRequisitos;
use App\Traits\BitacoraTrait;
use App\Traits\PermisosTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RequisitosController extends Controller
{
    use PermisosTrait;
    use BitacoraTrait;

    public function index()
    {
        if ($this->loginUsuario() == null) {
            return view('iniciar');
        }
        $fecha = new \DateTime();
        $dia = $fecha->format('D');
        // if (($dia == $this->diaSemanaEN() && count($this->entregasSinRespuesta()) > 0) || ($dia == $this->diaSemanaMX() && count($this->entregasSinRespuesta()) > 0)) {
        //     return view('lista_entregas');
        // } else if (count($this->entregasSinRespuesta()) > 0) {
        //     return view('lista_entregas');
        // } else if ($dia == $this->diaSemanaEN() || $dia == $this->diaSemanaMX() || count($this->entregasSinRespuesta()) == 0) {
            return view('requisitos.index');
        // }
    }

    public function store(Request $request)
    {
        // return $request;

        $estatus = 1;

        $data = [
            'descRequisito' => $request['descRequisito'],
            'estatusRequisito' => $estatus,
        ];

        return catRequisitos::create($data);
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
        $requisito = catRequisitos::where('idRequisito', $id)->first();
        return $requisito;
    }

    public function update(Request $request, $id)
    {

        $estatus = 0;

        if ($request->estatusRequisito == 'on') {
            $estatus = 1;
        }

        $requisito = catRequisitos::find($id);
        $requisito->descRequisito = $request['descRequisito'];
        // $requisito->estatusRequisito = $estatus;

        $requisito->update();

        return $requisito;
    }

    public function destroy($id)
    {
    }

    public function apiRequisitos()
    {
        $requisitos = catRequisitos::all();

        return DataTables::of($requisitos)
            ->addColumn('actionDetail', function ($requisito) {
                $btnDetail = '<a onclick="detalle(' . $requisito->idRequisito . ')" type="button"><i class="fas fa-eye"></i></a>';

                return $btnDetail;
            })
            ->addColumn('inputEditar', function () {
                $sesionEditar = trim(session('editar'));
                $inputEditar = $sesionEditar;

                return $inputEditar;
            })
            ->addColumn('inputEliminar', function () {
                $sesionEliminar = trim(session('eliminar'));
                $inputEliminar = $sesionEliminar;

                return $inputEliminar;
            })
            ->rawColumns(['actionDetail', 'inputEditar', 'inputEliminar'])
            ->make(true);
    }

    public function updateEstatus(Request $request)
    {
        // return $request;

        $estatus = 0;
        $id = $request->id;

        if ($request->estatus == 1) {
            $estatus = 0;
        } else {
            $estatus = 1;
        }

        $requisito = catRequisitos::find($id);
        $requisito->estatusRequisito = $estatus;

        $requisito->update();

        return $requisito;
    }
}
