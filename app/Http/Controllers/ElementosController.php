<?php

namespace App\Http\Controllers;

use App\Http\Modelos\v_ElementosPresupuestados;
use App\Http\Modelos\v_ElementosPresupuestados_All;
use App\Http\Modelos\ElementosPresupuestados;
use App\Http\Modelos\ElementosPacabados;
use App\Http\Modelos\ElementosPacabadosGral;
use App\Http\Modelos\ElementosPempaque;
use App\Http\Modelos\ElementosPmedidas;
use App\Http\Modelos\ElementosPnotas;
use App\Http\Modelos\relOport_ElementoP;
use App\catEquipos;
use App\catEstatus;
use App\catOportExclusion;
use App\catOportMotivoPerdida;
use App\catOportProbabilidad;
use App\catOportTipos;
use App\Traits\PermisosTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\App;
use App\Http\DAO\ElementoPresupuestadoDAO;
use App\Traits\BitacoraTrait;
use Illuminate\Support\Facades\View;

class ElementosController extends Controller
{
    use PermisosTrait;
    use BitacoraTrait;

    private $_elementoPresupuestadoDAO;
    function __construct(ElementoPresupuestadoDAO $elementoPresupuestado)
    {
        $this->_elementoPresupuestadoDAO = $elementoPresupuestado;
    }



    public function index()
    {
        $this->permisos();

        $modulo = 'Elementos Presupuestados';
        $Login = session('usuario');

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
            $permisos = DB::select('exec GetPermisos ?, ?', [$Login, $modulo]);
            // $oportunidades = DB::select('exec GetOportunidades');
            $folio = DB::select('exec spMaxFolio');
            $combo_clientes = DB::select('exec GetClientesCombo');
            $estatus = catEstatus::all();
            $tipos = catOportTipos::all();
            $previsiones = catOportExclusion::all();
            $probabilidades = catOportProbabilidad::all();
            $equipos = catEquipos::all();
            $combo_vendedores = DB::select('exec GetAgentesCombo');
            $combo_cotizadores = DB::select('exec GetCotizadoresCombo');
            $motivoPerdida = catOportMotivoPerdida::all();

            $permisos_count = count($permisos);

            $datos_usuario = DB::select('exec GetUsuario @Login=:Login', ['Login' => $Login]);

            $id_usuario = $datos_usuario[0]->Id_Usuario;

            for ($i = 0; $i < $permisos_count; $i++) {

                $crearElemento = session('crearElemento');
                $editarElemento = session('editarElemento');
                $editarElementosP = session('editarElementosP');

                if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Todo') {
                    return view('elementos_presupuestados.index', compact('crearElemento', 'editarElemento', 'editarElementosP', 'combo_clientes', 'estatus', 'folio', 'tipos', 'previsiones', 'probabilidades', 'equipos', 'combo_vendedores', 'motivoPerdida', 'id_usuario', 'combo_cotizadores'));
                } else if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Ninguno') {
                    return redirect()->route('Dashboard');
                }
            }
        // }
    }

    public function create($id)
    {
        $modulo = 'Elementos Presupuestados';
        $Login = session('usuario');

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
            $permisos = DB::select('exec GetPermisos ?, ?', [$Login, $modulo]);
            $dto = $this->_elementoPresupuestadoDAO->index($Login, $id);

            foreach ($permisos as $permiso) {
                if ($permiso->descAccion == 'Crear' && $permiso->descNivelPerm == 'Todo') {
                    return View('elementos_presupuestados.create', ['dto' => $dto]);
                } else if ($permiso->descAccion == 'Crear' && $permiso->descNivelPerm == 'Ninguno') {
                    return back();
                }
            }
        // }
    }

    public function store(Request $request)
    {
        $id_oportunidad = $request->idOportunidad;
        $fecha = new \DateTime();

        $data = request()->all();
        $dto = $this->_elementoPresupuestadoDAO->create($data);

        if ($id_oportunidad > 0) {
            $id_EP = DB::select('exec spMaxFolioEP');

            $dto_Rel['idElementoP'] = $id_EP[0]->idElementoP;
            $dto_Rel['idOportunidad'] = $id_oportunidad;
            $dto_Rel['idUsuarioRelaciona'] = $this->idUsuario();
            $dto_Rel['fecRelacion'] = $fecha->format('d-m-Y H:i:s');

            relOport_ElementoP::insert($dto_Rel);
        }
        return redirect()->route('ElementosPresupuestados');
    }


    public function show(Request $request)
    {
        // $id_etapa = $request->get('idEtapa');
        // $estatus = DB::select('exec GetEstatusCombo @idEtapa=' . $id_etapa);

        // return $estatus;
    }

    public function edit($idDocumentoP)
    {
        $Login = session('usuario');

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
            // $dto = $this->_elementoPresupuestado->get($idDocumentoP);
            $dto = $this->_elementoPresupuestadoDAO->get($idDocumentoP);
            return View::make('elementos_presupuestados.edit', ['dto' => $dto]);
        // }
    }


    public function update(Request $request, $idDocumentoP)
    {
        // return $request;
        $tblmedidas1 = [];
        $tblmedidas2 = [];
        $tblmedidas3 = [];
        $tblmedidasP = [];
        $tblacabados1 = [];
        $tblacabados2 = [];
        $tblacabados3 = [];
        $tblacabadosP = [];
        // $data = request()->except(['_token', '_method']);
        $data = request()->all();
        $idElementoP = $idDocumentoP; //--cambiar id
        $idUsuarioRegistro = session('idUsuario');
        $data['idUsuarioEdit'] = $idUsuarioRegistro;
        $fecha = new \DateTime();
        $fecRegistro = $fecha->format('d-m-Y H:i');
        // return $id;
        // $data['fecRegistro'] = $fecRegistro;
        // DB::beginTransaction();
        // $idElementoP=$request->idElementoP;
        $numPresupuestoMTC = $request->numPresupuestoMTC;
        // try {
        // return $data;
        
        //$request->_token = null;

        ElementosPresupuestados::findOrFail($idDocumentoP)->update($data);

        //$elPr = ElementosPresupuestados::findOrFail($idDocumentoP);
        //return $elPr;
        
        DB::statement('SET NOCOUNT ON;exec sp_EPaddValorOportunidad :numPresupuestoMTC,:idElementoP', [':numPresupuestoMTC' => $numPresupuestoMTC, ':idElementoP' => $idElementoP]);

        // return ($UPDATE);

        // return $data;

        foreach ($data as $key => $value) {

            $value = $value == 'on' ? 1 : $value;
            if (isset($value) && str_contains($key, 'idTipoEmpaque')) {
                $Empaque['idTipoEmpaque'] = $value;
                $Empaque['idElementoP'] = $idElementoP;
                $Empaque['obsEmpaque'] = request('obsEmpaque');
                ElementosPempaque::create($Empaque);
            } else if (isset($value) && str_contains($key, 'idTipoAcabadoGral')) {
                $AcabadosGral['idAcabado'] = $value;
                $AcabadosGral['idElementoP'] = $idElementoP;
                ElementosPacabadosGral::create($AcabadosGral);
            }
            ////----------------------- TABLA DE MEDIDAS Y ACABADOS --------------------------------

            /// CHECAR SI EXISTE, UPDAT, SI NO UN INSERT
            else if (str_contains($key, '_tbl-')) //|| && str_contains($key, '_tbl-1')
            { //echo ($idElementoP);

                if (str_contains($key, '-acab')) {
                    if (str_contains($key, '_tbl-P-acab')) {
                        $campo = explode('_', $key);
                        $numInterior = explode('-', $campo[1]);
                        $tblacabadosP['numInterior'] = $numInterior[1];
                        $tblacabadosP['idElementoP'] = $idElementoP;
                        $tblacabadosP[$campo[0]] = $value;
                    }
                    for ($i = 1; $i <= 3; $i++) {
                        if (str_contains($key, ('_tbl-' . $i . '-acab'))) {
                            $campo = explode('_', $key);
                            $numInterior = explode('-', $campo[1]);
                            ${'tblacabados' . $i}['numInterior'] = $numInterior[1];
                            ${'tblacabados' . $i}['idElementoP'] = $idElementoP;
                            ${'tblacabados' . $i}[$campo[0]] = $value;
                        }
                    }
                } else {
                    if (str_contains($key, '_tbl-P')) { //echo ($idElementoP);
                        if (!isset($data['nombreInterior_tbl-P'])) {
                            $tblmedidasP['nombreInterior'] = 'Portada';
                        } //echo 'si existe nombre';
                        $campo = explode('_', $key);
                        $numInterior = explode('-', $campo[1]);
                        $tblmedidasP['numInterior'] = $numInterior[1];
                        $tblmedidasP['idElementoP'] = $idElementoP; //echo ($idElementoP);
                        $tblmedidasP[$campo[0]] = $value;
                    }

                    for ($i = 1; $i <= 3; $i++) {
                        if (!isset($data['nombreInterior_tbl-' . $i])) {
                            ${'tblmedidas' . $i}['nombreInterior'] = 'Interior ' . $i;
                        }
                        
                        if (str_contains($key, ('_tbl-' . $i))) {
                            $campo = explode('_', $key);
                            $numInterior = explode('-', $campo[1]);
                            ${'tblmedidas' . $i}['numInterior'] = $numInterior[1];
                            ${'tblmedidas' . $i}['idElementoP'] = $idElementoP;
                            ${'tblmedidas' . $i}[$campo[0]] = $value;
                        }
                    }
                }
            }
        }

        // ========================= INTERIOR 1 ======================================================

        if (empty($tblmedidas1['largoExtendido']) || $tblmedidas1['largoExtendido'] == 0) {
            $tblmedidas1['largoExtendido'] = null;
        }

        if (empty($tblmedidas1['anchoExtendido']) || $tblmedidas1['anchoExtendido'] == 0) {
            $tblmedidas1['anchoExtendido'] = null;
        }

        if (empty($tblmedidas1['largoFinal']) || $tblmedidas1['largoFinal'] == 0) {
            $tblmedidas1['largoFinal'] = null;
        }

        if (empty($tblmedidas1['anchoFinal']) || $tblmedidas1['anchoFinal'] == 0) {
            $tblmedidas1['anchoFinal'] = null;
        }
        
        if (empty($tblmedidas1['numTintasF']) || $tblmedidas1['numTintasF'] == 0) {
            $tblmedidas1['numTintasF'] = null;
        }
        
        if (empty($tblmedidas1['numTintasV']) || $tblmedidas1['numTintasV'] == 0) {
            $tblmedidas1['numTintasV'] = null;
        }
        
        if (empty($tblmedidas1['idTipoPapel'])) {
            $tblmedidas1['idTipoPapel'] = null;
        }
        
        if (empty($tblmedidas1['numPaginas']) || $tblmedidas1['numPaginas'] == 0) {
            $tblmedidas1['numPaginas'] = null;
        }
        
        if (empty($tblacabados1['conDoblado'])) {
            $tblacabados1['conDoblado'] = 0;
        }
        
        if (empty($tblacabados1['conPerforado'])) {
            $tblacabados1['conPerforado'] = 0;
        }
        
        if (empty($tblacabados1['conPersonalizado'])) {
            $tblacabados1['conPersonalizado'] = 0;
        }
        
        if (empty($tblacabados1['conSuajado'])) {
            $tblacabados1['conSuajado'] = 0;
        }
        
        if (empty($tblacabados1['conPegado'])) {
            $tblacabados1['conPegado'] = 0;
        }
        
        if (empty($tblacabados1['conPlecado'])) {
            $tblacabados1['conPlecado'] = 0;
        }
        
        if (empty($tblacabados1['conGrabado'])) {
            $tblacabados1['conGrabado'] = 0;
        }
        
        if (empty($tblacabados1['conHotStamping'])) {
            $tblacabados1['conHotStamping'] = 0;
        }
        
        if (empty($tblacabados1['idTipoGatefold'])) {
            $tblacabados1['idTipoGatefold'] = null;
        }
        
        if (empty($tblacabados1['descOtro'])) {
            $tblacabados1['descOtro'] = null;
        }
        
        if (empty($tblmedidas1['largoExtendido']) 
            && empty($tblmedidas1['anchoExtendido']) 
            && empty($tblmedidas1['largoFinal']) 
            && empty($tblmedidas1['anchoFinal']) 
            && empty($tblmedidas1['numTintasF']) 
            && empty($tblmedidas1['numTintasV']) 
            && empty($tblmedidas1['idTipoPapel'])
            && empty($tblmedidas1['numPaginas'])
            ) {
            $tblmedidas1['nombreInterior'] = null;
        }

        // ========================= INTERIOR 2 ======================================================

        if (empty($tblmedidas2['largoExtendido']) || $tblmedidas2['largoExtendido'] == 0) {
            $tblmedidas2['largoExtendido'] = null;
        }

        if (empty($tblmedidas2['anchoExtendido']) || $tblmedidas2['anchoExtendido'] == 0) {
            $tblmedidas2['anchoExtendido'] = null;
        }

        if (empty($tblmedidas2['largoFinal']) || $tblmedidas2['largoFinal'] == 0) {
            $tblmedidas2['largoFinal'] = null;
        }

        if (empty($tblmedidas2['anchoFinal']) || $tblmedidas2['anchoFinal'] == 0) {
            $tblmedidas2['anchoFinal'] = null;
        }
        
        if (empty($tblmedidas2['numTintasF']) || $tblmedidas2['numTintasF'] == 0) {
            $tblmedidas2['numTintasF'] = null;
        }
        
        if (empty($tblmedidas2['numTintasV']) || $tblmedidas2['numTintasV'] == 0) {
            $tblmedidas2['numTintasV'] = null;
        }
        
        if (empty($tblmedidas2['idTipoPapel'])) {
            $tblmedidas2['idTipoPapel'] = null;
        }
        
        if (empty($tblmedidas2['numPaginas']) || $tblmedidas2['numPaginas'] == 0) {
            $tblmedidas2['numPaginas'] = null;
        }
        
        if (empty($tblacabados2['conDoblado'])) {
            $tblacabados2['conDoblado'] = 0;
        }
        
        if (empty($tblacabados2['conPerforado'])) {
            $tblacabados2['conPerforado'] = 0;
        }
        
        if (empty($tblacabados2['conPersonalizado'])) {
            $tblacabados2['conPersonalizado'] = 0;
        }
        
        if (empty($tblacabados2['conSuajado'])) {
            $tblacabados2['conSuajado'] = 0;
        }
        
        if (empty($tblacabados2['conPegado'])) {
            $tblacabados2['conPegado'] = 0;
        }
        
        if (empty($tblacabados2['conPlecado'])) {
            $tblacabados2['conPlecado'] = 0;
        }
        
        if (empty($tblacabados2['conGrabado'])) {
            $tblacabados2['conGrabado'] = 0;
        }
        
        if (empty($tblacabados2['conHotStamping'])) {
            $tblacabados2['conHotStamping'] = 0;
        }
        
        if (empty($tblacabados2['idTipoGatefold'])) {
            $tblacabados2['idTipoGatefold'] = null;
        }
        
        if (empty($tblacabados2['descOtro'])) {
            $tblacabados2['descOtro'] = null;
        }
        
        if (empty($tblmedidas2['largoExtendido']) 
            && empty($tblmedidas2['anchoExtendido']) 
            && empty($tblmedidas2['largoFinal']) 
            && empty($tblmedidas2['anchoFinal']) 
            && empty($tblmedidas2['numTintasF']) 
            && empty($tblmedidas2['numTintasV']) 
            && empty($tblmedidas2['idTipoPapel'])
            && empty($tblmedidas2['numPaginas'])
            ) {
            $tblmedidas2['nombreInterior'] = null;
        }

        // ========================= INTERIOR 3 ======================================================

        if (empty($tblmedidas3['largoExtendido']) || $tblmedidas3['largoExtendido'] == 0) {
            $tblmedidas3['largoExtendido'] = null;
        }

        if (empty($tblmedidas3['anchoExtendido']) || $tblmedidas3['anchoExtendido'] == 0) {
            $tblmedidas3['anchoExtendido'] = null;
        }

        if (empty($tblmedidas3['largoFinal']) || $tblmedidas3['largoFinal'] == 0) {
            $tblmedidas3['largoFinal'] = null;
        }

        if (empty($tblmedidas3['anchoFinal']) || $tblmedidas3['anchoFinal'] == 0) {
            $tblmedidas3['anchoFinal'] = null;
        }
        
        if (empty($tblmedidas3['numTintasF']) || $tblmedidas3['numTintasF'] == 0) {
            $tblmedidas3['numTintasF'] = null;
        }
        
        if (empty($tblmedidas3['numTintasV']) || $tblmedidas3['numTintasV'] == 0) {
            $tblmedidas3['numTintasV'] = null;
        }
        
        if (empty($tblmedidas3['idTipoPapel'])) {
            $tblmedidas3['idTipoPapel'] = null;
        }
        
        if (empty($tblmedidas3['numPaginas']) || $tblmedidas3['numPaginas'] == 0) {
            $tblmedidas3['numPaginas'] = null;
        }
        
        if (empty($tblacabados3['conDoblado'])) {
            $tblacabados3['conDoblado'] = 0;
        }
        
        if (empty($tblacabados3['conPerforado'])) {
            $tblacabados3['conPerforado'] = 0;
        }
        
        if (empty($tblacabados3['conPersonalizado'])) {
            $tblacabados3['conPersonalizado'] = 0;
        }
        
        if (empty($tblacabados3['conSuajado'])) {
            $tblacabados3['conSuajado'] = 0;
        }
        
        if (empty($tblacabados3['conPegado'])) {
            $tblacabados3['conPegado'] = 0;
        }
        
        if (empty($tblacabados3['conPlecado'])) {
            $tblacabados3['conPlecado'] = 0;
        }
        
        if (empty($tblacabados3['conGrabado'])) {
            $tblacabados3['conGrabado'] = 0;
        }
        
        if (empty($tblacabados3['conHotStamping'])) {
            $tblacabados3['conHotStamping'] = 0;
        }
        
        if (empty($tblacabados3['idTipoGatefold'])) {
            $tblacabados3['idTipoGatefold'] = null;
        }
        
        if (empty($tblacabados3['descOtro'])) {
            $tblacabados3['descOtro'] = null;
        }
        
        if (empty($tblmedidas3['largoExtendido']) 
            && empty($tblmedidas3['anchoExtendido']) 
            && empty($tblmedidas3['largoFinal']) 
            && empty($tblmedidas3['anchoFinal']) 
            && empty($tblmedidas3['numTintasF']) 
            && empty($tblmedidas3['numTintasV']) 
            && empty($tblmedidas3['idTipoPapel'])
            && empty($tblmedidas3['numPaginas'])
            ) {
            $tblmedidas3['nombreInterior'] = null;
        }

        // ========================= PORTADA ======================================================

        if (empty($tblmedidasP['largoExtendido']) || $tblmedidasP['largoExtendido'] == 0) {
            $tblmedidasP['largoExtendido'] = null;
        }

        if (empty($tblmedidasP['anchoExtendido']) || $tblmedidasP['anchoExtendido'] == 0) {
            $tblmedidasP['anchoExtendido'] = null;
        }

        if (empty($tblmedidasP['largoFinal']) || $tblmedidasP['largoFinal'] == 0) {
            $tblmedidasP['largoFinal'] = null;
        }

        if (empty($tblmedidasP['anchoFinal']) || $tblmedidasP['anchoFinal'] == 0) {
            $tblmedidasP['anchoFinal'] = null;
        }
        
        if (empty($tblmedidasP['numTintasF']) || $tblmedidasP['numTintasF'] == 0) {
            $tblmedidasP['numTintasF'] = null;
        }
        
        if (empty($tblmedidasP['numTintasV']) || $tblmedidasP['numTintasV'] == 0) {
            $tblmedidasP['numTintasV'] = null;
        }
        
        if (empty($tblmedidasP['idTipoPapel'])) {
            $tblmedidasP['idTipoPapel'] = null;
        }
        
        if (empty($tblmedidasP['numPaginas']) || $tblmedidasP['numPaginas'] == 0) {
            $tblmedidasP['numPaginas'] = null;
        }
        
        if (empty($tblacabadosP['conDoblado'])) {
            $tblacabadosP['conDoblado'] = 0;
        }
        
        if (empty($tblacabadosP['conPerforado'])) {
            $tblacabadosP['conPerforado'] = 0;
        }
        
        if (empty($tblacabadosP['conPersonalizado'])) {
            $tblacabadosP['conPersonalizado'] = 0;
        }
        
        if (empty($tblacabadosP['conSuajado'])) {
            $tblacabadosP['conSuajado'] = 0;
        }
        
        if (empty($tblacabadosP['conPegado'])) {
            $tblacabadosP['conPegado'] = 0;
        }
        
        if (empty($tblacabadosP['conPlecado'])) {
            $tblacabadosP['conPlecado'] = 0;
        }
        
        if (empty($tblacabadosP['conGrabado'])) {
            $tblacabadosP['conGrabado'] = 0;
        }
        
        if (empty($tblacabadosP['conHotStamping'])) {
            $tblacabadosP['conHotStamping'] = 0;
        }
        
        if (empty($tblacabadosP['idTipoGatefold'])) {
            $tblacabadosP['idTipoGatefold'] = null;
        }
        
        if (empty($tblacabadosP['descOtro'])) {
            $tblacabadosP['descOtro'] = null;
        }
        
        if (empty($tblmedidasP['largoExtendido']) 
            && empty($tblmedidasP['anchoExtendido']) 
            && empty($tblmedidasP['largoFinal']) 
            && empty($tblmedidasP['anchoFinal']) 
            && empty($tblmedidasP['numTintasF']) 
            && empty($tblmedidasP['numTintasV']) 
            && empty($tblmedidasP['idTipoPapel'])
            && empty($tblmedidasP['numPaginas'])
            ) {
            $tblmedidasP['nombreInterior'] = null;
        }

        //return $request;

        if (!empty($tblmedidasP)) {
            ElementosPmedidas::updateOrInsert(['idElementoP' => $idElementoP, 'numInterior' => 'P'], $tblmedidasP);
        }

        if (!empty($tblacabadosP)) {
            ElementosPacabados::updateOrInsert(['idElementoP' => $idElementoP, 'numInterior' => 'P'], $tblacabadosP);
        }

        if (!empty($tblmedidas1)) {
            ElementosPmedidas::updateOrInsert(['idElementoP' => $idElementoP, 'numInterior' => '1'], $tblmedidas1);
        }

        if (!empty($tblmedidas2)) {
            ElementosPmedidas::updateOrInsert(['idElementoP' => $idElementoP, 'numInterior' => '2'], $tblmedidas2);
        }

        if (!empty($tblmedidas3)) {
            ElementosPmedidas::updateOrInsert(['idElementoP' => $idElementoP, 'numInterior' => '3'], $tblmedidas3);
        }

        if (!empty($tblacabados1)) {
            ElementosPacabados::updateOrInsert(['idElementoP' => $idElementoP, 'numInterior' => '1'], $tblacabados1);
        }

        if (!empty($tblacabados2)) {
            ElementosPacabados::updateOrInsert(['idElementoP' => $idElementoP, 'numInterior' => '2'], $tblacabados2);
        }

        if (!empty($tblacabados3)) {
            ElementosPacabados::updateOrInsert(['idElementoP' => $idElementoP, 'numInterior' => '3'], $tblacabados3);
        }




        //     DB::commit();
        // } catch (\Illuminate\Database\QueryException $ex) {
        //     DB::rollback();
        //     // DB::select('DBCC CHECKIDENT ("Emp", RESEED, 1)');
        //     // dd($ex->getMessage()); 
        //     echo('error');
        // }
        return view('elementos_presupuestados.index');
    }

    public function destroy($id)
    {
    }

    public function apiElementos($id)
    {
        $rol = session('rol');
        if ($id > 0) {
            $ElementosP = v_ElementosPresupuestados::where('idOportunidad', $id)->limit(20000);
        } else {
            switch ($rol) {
                case "9":
                    $equipos = DB::table('v_EquiposAux')->where('idAuxiliar', session('idUsuario'))->orWhere('idAuxiliar1', session('idUsuario'))->orWhere('idAuxiliar2', session('idUsuario'))->orWhere('idAuxiliar3', session('idUsuario'))->pluck('idequipo');
                    $ElementosP = v_ElementosPresupuestados::whereIn('idEquipo', $equipos)->orwhere('idUsuarioRegistro', session('idUsuario'))->limit(20000);
                    break;
                case "1":
                case "7":
                case "8":
                case "10":
                case "11":
                    $ElementosP = v_ElementosPresupuestados::where('idElementoP', '!=', null)->limit(20000);
                    break;
                default:
                    $equipos = DB::table('IntegrantesEquipos')->where('idUsuario', session('idUsuario'))->pluck('idequipo');
                    $ElementosP = v_ElementosPresupuestados::whereIn('idEquipo', $equipos)->orwhere('idUsuarioRegistro', session('idUsuario'))->limit(20000);
                    break;
            }
        }
        $modulo = 'Elementos Presupuestados';
        $Login = session('usuario');
        // $equipos = DB::table('IntegrantesEquipos')->where('idUsuario', $idUsuario)->pluck('idequipo');
        // return $equipos;
        $permisos = DB::select('exec GetPermisos ?, ?', [$Login, $modulo]);
        session(['rol' => $permisos[0]->idRol]);

        return DataTables::of($ElementosP)
            ->addColumn('inputEditar', function () {
                $sesionEditar = trim(session('editarElemento'));
                $inputEditar = $sesionEditar;

                return $inputEditar;
            })
            ->addColumn('inputEliminar', function () {
                $sesionEliminar = trim(session('eliminarElemento'));
                $inputEliminar = $sesionEliminar;

                return $inputEliminar;
            })
            ->addColumn('inputEditarCliente', function () {
                $sesionEditarCliente = trim(session('editar'));
                $inputEditarCliente = $sesionEditarCliente;

                return $inputEditarCliente;
            })
            ->addColumn('inputLogin', function () {
                $sesionLogin = trim(session('usuario'));
                $inputLogin = $sesionLogin;

                return $inputLogin;
            })
            ->addColumn('inputRol', function () {
                $sesionRol = session('rol');
                $inputRol = $sesionRol;

                return $inputRol;
            })
            ->addColumn('inputequipos', function () {
                if (session('rol') == 9) {
                    $equipos = DB::table('v_EquiposAux')->where('idAuxiliar', session('idUsuario'))->orWhere('idAuxiliar1', session('idUsuario'))->orWhere('idAuxiliar2', session('idUsuario'))->orWhere('idAuxiliar3', session('idUsuario'))->pluck('idequipo');
                } else {
                    $equipos = DB::table('IntegrantesEquipos')->where('idUsuario', session('idUsuario'))->pluck('idequipo');
                }
                return $equipos;
            })
            ->rawColumns(['inputEditar', 'inputEliminar', 'inputEditarCliente', 'inputLogin', 'inputRol', 'inputequipos'])
            ->make(true);
    }


    public function catalogoPapel(Request $request)
    {
        $evento = $request->evento;
        $id = $request->id;
        try {
            $combo = DB::select('exec sp_CatalogoPapel :evento, :id', [':evento' => $evento, ':id' => $id]);
        } catch (\Illuminate\Database\QueryException $ex) {
            dd($ex->getMessage());
            // Note any method of class PDOException can be called on $ex.
        }

        return $combo;
    }
    public function getCombos(Request $request)
    {
        $evento = $request->evento;
        try {
            $combo = DB::select('exec sp_EPCombos :evento', [':evento' => $evento]);
        } catch (\Illuminate\Database\QueryException $ex) {
            dd($ex->getMessage());
            // Note any method of class PDOException can be called on $ex.
        }

        return $combo;
    }
    public function getCombosInfoGral(Request $request)
    {
        $evento = $request->evento;
        $idElementoP = $request->idElementoP;
        $numInterior = $request->numInterior;
        try {
            $combo = DB::select('exec sp_EPCombosInfoGral :evento ,:idElementoP,:numInterior', [':evento' => $evento, ':idElementoP' => $idElementoP, ':numInterior' => $numInterior]);
        } catch (\Illuminate\Database\QueryException $ex) {
            dd($ex->getMessage());
            // Note any method of class PDOException can be called on $ex.
        }

        return $combo;
    }
    public function gettblelementosp(Request $request)
    {
        $evento = $request->evento;
        $idElementoP = $request->idElementoP;
        try {
            $combo = DB::select('exec sp_EPtbl :evento,:idElementoP', [':evento' => $evento, ':idElementoP' => $idElementoP]);
        } catch (\Illuminate\Database\QueryException $ex) {
            dd($ex->getMessage());
            // Note any method of class PDOException can be called on $ex.
        }

        return $combo;
    }
    public function EPrelation(Request $request)
    {
        $data = request()->all();
        $ep = $data['EPrelations'];
        $dto = '';
        $idUsuarioRegistro = session('idUsuario');
        $fecha = new \DateTime();
        $fecRegistro = $fecha->format('d-m-Y H:i');
        // return($data['EPrelations']);
        foreach ($ep as $key => $value) {
            // return($key['idElementoP']);
            foreach ($value as $key1 => $value1) {
                // echo$value1['idElementoP'] ;
                $dto['idElementoP'] = $value1['idElementoP'];
                $dto['idOportunidad'] = $value1['idOportunidad'];
                $dto['idUsuarioRelaciona'] = $idUsuarioRegistro;
                $dto['fecRelacion'] = $fecRegistro;

                relOport_ElementoP::insert($dto);
            }
        }
    }
    public function download($idElementoP)
    {

        // $DAO=new ElementoPresupuestadoDAO();
        // $dto=$DAO->get($idElementoP);
        $dto = $this->_elementoPresupuestadoDAO->get($idElementoP);
        //   return response()->json(['dto'=>$dto->combo_tblmedidas2]);
        $dompdf = App::make("dompdf.wrapper");
        $dompdf->loadView("pdf/elemento_presupuestado", ['dto' => $dto]);

        // return $dompdf->download("ElementoPresupuestado.pdf");//Para descargar
        return $dompdf->stream(); //Para visualizar en el navegador


    }
    public function addValorOportunidad(Request $request)
    {
        $idElementoP = $request->idElementoP;
        $numPresupuestoMTC = $request->numPresupuestoMTC;
        $dto = $this->_elementoPresupuestadoDAO->addValor($idElementoP, $numPresupuestoMTC);
    }
    public function obtNotas(Request $request)
    {
        $idElementoP = $request->idElementoP;
        $idElementoP = (int) $idElementoP;
        // var_dump($idElementoP);//exit();
        $datos = DB::table('v_ElementosPnotas')->where('idElementoP', $idElementoP)->get();
        return $datos;
    }
    public function Addnotas(Request $request)
    {

        // $data = request()->all();
        $idElementoP = $request->idElemento;
        $idUsuarioRegistro = session('idUsuario');
        $fecha = new \DateTime();
        $fecRegistro = $fecha->format('d-m-Y H:i');
        $data['Nota'] = $request->Nota;
        $data['FechaRegistro'] = $fecRegistro;
        $data['idUsuarioRegistro'] = (int)$idUsuarioRegistro;
        $data['idElementoP'] = (int) $idElementoP;
        $idNota = ElementosPnotas::insertGetId($data);
        $datos = DB::table('v_ElementosPnotas')->where('idNota', $idNota)->get();
        // var_dump($datos);exit();
        return $datos;
    }

    public function clonarElemento(Request $request)
    {
        $fecha = new \DateTime();
        $elemetoP = v_ElementosPresupuestados_All::where('idElementoP', $request->idElemento)->first();

        // return $elemetoP;

        $idTipoItem = $elemetoP->idTipoItem;
        // $nomElementoP = 'TEST CLONADO';
        $nomElementoP = $elemetoP->nomElementoP;
        $cant1 = $elemetoP->cant1;
        $cant2 = $elemetoP->cant2;
        $cant3 = $elemetoP->cant3;
        $cant4 = $elemetoP->cant4;
        $cant5 = 0;
        $numPresupuesto = null;
        $fecTopeCot = null;
        $fecReqEntProd = null;
        $esNuevo = $elemetoP->esNuevo;
        $preObjetivo = $elemetoP->preObjetivo;
        $preCompetencia = $elemetoP->preCompetencia;
        $nomCompetencia = $elemetoP->nomCompetencia;
        $idEstadoItem = $elemetoP->idEstadoItem;
        $porcMargen = $elemetoP->porcMargen;
        $porcFinanciamiento = $elemetoP->porcFinanciamiento;
        $porcComisionExt = $elemetoP->porcComisionExt;
        $porcComIsionInt = $elemetoP->porcComisionInt;
        $idTipoPreprensa = $elemetoP->idTipoPreprensa;
        $conRetoque = $elemetoP->conRetoque;
        $idTipoEncuadernado = $elemetoP->idTipoEncuadernado;
        $otroEncuadernado = $elemetoP->otroEncuadernado;
        $obsEncuadernado = $elemetoP->obsEncuadernado;
        $conFullfilment = $elemetoP->conFullfilment;
        $fecRegistro = $fecha->format('d-m-Y H:i:s');
        $idUsuarioRegistro = $this->idUsuario();
        $Decripcion = $elemetoP->Decripcion;
        $Observaciones = $elemetoP->Observaciones;
        $idTipoDistribucion = $elemetoP->idTipoDistribucion;
        $numPresupuestoMTC = null;
        $fecEdit = null;
        $idUsuarioEdit = null;
        $Cantidad = 0;
        $preUnit = 0;
        $Importe = 0;

        DB::statement(
            'exec spInsertElemento ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                                   ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                                   ?, ?, ?, ?, ?, ?',
            [
                $idTipoItem,
                $nomElementoP,
                $cant1,
                $cant2,
                $cant3,
                $cant4,
                $cant5,
                $numPresupuesto,
                $fecTopeCot,
                $fecReqEntProd,
                $esNuevo,
                $preObjetivo,
                $preCompetencia,
                $nomCompetencia,
                $idEstadoItem,
                $porcMargen,
                $porcFinanciamiento,
                $porcComisionExt,
                $porcComIsionInt,
                $idTipoPreprensa,
                $conRetoque,
                $idTipoEncuadernado,
                $otroEncuadernado,
                $obsEncuadernado,
                $conFullfilment,
                $fecRegistro,
                $idUsuarioRegistro,
                $Decripcion,
                $Observaciones,
                $idTipoDistribucion,
                $numPresupuestoMTC,
                $fecEdit,
                $idUsuarioEdit,
                $Cantidad,
                $preUnit,
                $Importe,
            ]
        );

        $elemento_id = DB::table('ElementosP')->max('idElementoP');
        $elemento_presupuestado_acabados = ElementosPacabados::where('idElementoP', $request->idElemento)->get();

        // return $elemento_presupuestado_acabados;

        if (count($elemento_presupuestado_acabados) > 0) {

            foreach ($elemento_presupuestado_acabados as $ep_acabado) {
                $idElementoP = $elemento_id;
                $numInterior = $ep_acabado->numInterior;
                $conDoblado = $ep_acabado->conDoblado;
                $conPerforado = $ep_acabado->conPerforado;
                $conPersonalizado = $ep_acabado->conPersonalizado;
                $conSuajado = $ep_acabado->conSuajado;
                $conPegado = $ep_acabado->conPegado;
                $conPlecado = $ep_acabado->conPlecado;
                $conOtro = $ep_acabado->conOtro;
                $descOtro = strtoupper($ep_acabado->descOtro);
                $conGrabado = $ep_acabado->conGrabado;
                $conHotStamping = $ep_acabado->conHotStamping;
                $conGatefold = $ep_acabado->conGatefold;
                $idTipoGatefold = $ep_acabado->idTipoGatefold;

                DB::statement(
                    'exec spInsertEPAcabados ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?',
                    [
                        $idElementoP,
                        $numInterior,
                        $conDoblado,
                        $conPerforado,
                        $conPersonalizado,
                        $conSuajado,
                        $conPegado,
                        $conPlecado,
                        $conOtro,
                        $descOtro,
                        $conGrabado,
                        $conHotStamping,
                        $conGatefold,
                        $idTipoGatefold,
                    ]
                );
            }
        }

        $elemento_presupuestado_acabados_general = ElementosPacabadosGral::where('idElementoP', $request->idElemento)->get();

        if (count($elemento_presupuestado_acabados_general) > 0) {
            foreach ($elemento_presupuestado_acabados_general as $ep_acabados_general) {
                $idElementoP = $elemento_id;
                $idAcabado = $ep_acabados_general->idAcabado;

                DB::statement('exec spInsertEPAcabadosGral ?, ?', [
                    $idElementoP,
                    $idAcabado,
                ]);
            }
        }

        $elemento_presupuestado_empaque = ElementosPempaque::where('idElementoP', $request->idElemento)->get();

        if (count($elemento_presupuestado_empaque) > 0) {
            foreach ($elemento_presupuestado_empaque as $ep_empaque) {
                $idElementoP = $elemento_id;
                $idTipoEmpaque = $ep_empaque->idTipoEmpaque;
                $obsEmpaque = $ep_empaque->obsEmpaque;

                DB::statement('exec spInsertEPEmpaque ?, ?, ?', [
                    $idElementoP,
                    $idTipoEmpaque,
                    $obsEmpaque,
                ]);
            }
        }

        $elemento_presupuestado_medidas = ElementosPmedidas::where('idElementoP', $request->idElemento)->get();

        if (count($elemento_presupuestado_medidas) > 0) {
            foreach ($elemento_presupuestado_medidas as $ep_medidas) {
                $idElementoP = $elemento_id;
                $numInterior = $ep_medidas->numInterior;
                $largoExtendido = $ep_medidas->largoExtendido;
                $anchoExtendido = $ep_medidas->anchoExtendido;
                $largoFinal = $ep_medidas->largoFinal;
                $anchoFinal = $ep_medidas->anchoFinal;
                $numPaginas = $ep_medidas->numPaginas;
                $idTipoPapel = $ep_medidas->idTipoPapel;
                $PapelEspecial = strtoupper($ep_medidas->PapelEspecial);
                $numTintasF = $ep_medidas->numTintasF;
                $numTintasV = $ep_medidas->numTintasV;
                $barniz = $ep_medidas->barniz;
                $idTipoBarniz = $ep_medidas->idTipoBarniz;
                $numCarasLaminado = $ep_medidas->numCarasLaminado;
                $idTipoLaminado = $ep_medidas->idTipoLaminado;
                $nombreInterior = strtoupper($ep_medidas->nombreInterior);
                $TipoPapelgrs = $ep_medidas->TipoPapelgrs;

                DB::statement('exec spInsertEPMedidas ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', [
                    $idElementoP,
                    $numInterior,
                    $largoExtendido,
                    $anchoExtendido,
                    $largoFinal,
                    $anchoFinal,
                    $numPaginas,
                    $idTipoPapel,
                    $PapelEspecial,
                    $numTintasF,
                    $numTintasV,
                    $barniz,
                    $idTipoBarniz,
                    $numCarasLaminado,
                    $idTipoLaminado,
                    $nombreInterior,
                    $TipoPapelgrs,
                ]);
            }
        }

        // $elemento_presupuestado_notas = ElementosPnotas::where('idElementoP', $request->idElemento)->get();

        // if (count($elemento_presupuestado_notas) > 0) {
        //     foreach ($elemento_presupuestado_notas as $ep_nota) {
        //         $idElementoP = $elemento_id;
        //         $Nota = $ep_nota->Nota;
        //         $FechaRegistro = $fecha->format('d-m-Y H:i:s');
        //         $idUsuarioRegistro = $this->idUsuario();

        //         DB::statement('exec spInsertEPNotas ?, ?, ?, ?', [
        //             $idElementoP,
        //             $Nota,
        //             $FechaRegistro,
        //             $idUsuarioRegistro,
        //         ]);
        //     }
        // }

        $this->bitacora('Elementos P', 'Clonar ', 'Elemento ', $elemento_id, $this->loginUsuario(), 5);
    }
}
