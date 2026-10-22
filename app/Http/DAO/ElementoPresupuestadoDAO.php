<?php

namespace App\Http\DAO;

use App\Http\Modelos\Oportunidades;
use App\Http\Modelos\v_ElementosPresupuestados;
use App\Http\Modelos\v_ElementosPresupuestados_All;
use App\Http\Modelos\ElementosPresupuestados;
use App\Http\Modelos\ElementosPacabados;
use App\Http\Modelos\ElementosPacabadosGral;
use App\Http\Modelos\ElementosPempaque;
use App\Http\Modelos\ElementosPmedidas;
use App\catEPtipoItem;
use App\EPcomboSustratos;
use App\catEquipos;
use Illuminate\Support\Facades\DB;

class ElementoPresupuestadoDAO 
{
    public function index($Login, $id)
    {
        $dto=new \stdClass; 
        $dto->folio =(DB::table('ElementosP') ->max('idElementoP'))+1;
        $dto->equipos = catEquipos::all();
        $dto->combo_vendedores = DB::select('exec GetAgentesCombo');
        $dto->combo_cotizadores = DB::select('exec GetCotizadoresCombo');
        $dto->combo_clientes = DB::select('exec GetClientesCombo');
        $dto->combo_oportunidades = DB::select('exec GetComboOportunidades');
        $dto->combo_tipoitem = catEPtipoItem::orderBy('descTipoItem', 'asc')->where('estatusItem', 1)->get();
        $dto->combo_tipoitem_edit = catEPtipoItem::orderBy('descTipoItem', 'asc')->get();
        $dto->combo_pp= DB::table('catEPtipoPP')->get();
        $dto->combo_tipodistribucion= DB::table('catEPtipoDistribucion')->get();
        $dto->combo_tipobarniz= DB::table('catEPtipoBarnices')->get();
        $dto->combo_tipolaminado= DB::table('catEPtipoLaminado')->get();
        $dto->combo_tipoencuadernado= DB::table('catEPtipoEncuadernado')->get();
        $dto->combo_tipoPapelItem= EPcomboSustratos::select('clasificacion')->distinct()->get();
        $datos_usuario = DB::select('exec GetUsuario @Login=' . $Login);
        $dto->id_usuario = $datos_usuario[0]->Id_Usuario;
        $dto->idOportunidad = $id;
        return $dto;
    }
    public function  get($idElementoP)
    {
        $dto=new \stdClass; 
        $dto->title='Elemento Presupuestado';
        $dto->Elementos = v_ElementosPresupuestados_All::findOrFail($idElementoP);
        $dto->combo_tipoitem = catEPtipoItem::orderBy('descTipoItem', 'asc')->where('estatusItem', 1)->get();
        $dto->combo_tipoitem_edit = catEPtipoItem::orderBy('descTipoItem', 'asc')->get();
        // $dto->combo_tipoitem = catEPtipoItem::all();
        $dto->combo_pp= DB::table('catEPtipoPP')->get();
        $dto->combo_tipodistribucion= DB::table('catEPtipoDistribucion')->get();
        $dto->combo_tipoencuadernado= DB::table('catEPtipoEncuadernado')->get();
        $dto->combo_estadoitem= DB::table('catEPestadoItem')->get();
        $dto->tblAcabadosGrles= DB::table('v_EPacabadosGles')->where('idElementoP', $idElementoP)->get();
        $dto->tblEmpaque= DB::table('v_EPempaque')->where('idElementoP', $idElementoP)->get();
        $dto->combo_tipoPapelItem= EPcomboSustratos::select('clasificacion')->distinct()->get();
        $dto->combo_tblmedidas1 = DB::select(DB::raw('exec sp_EPtblPDF :evento,:idElementoP'),[':evento' => 'tblMedidas',':idElementoP' => $idElementoP]);
        $dto->combo_tblmedidas2 = DB::select(DB::raw('exec sp_EPtblPDF :evento,:idElementoP'),[':evento' => 'tblAcabados',':idElementoP' => $idElementoP]);
        $dto->notas = DB::table('ElementosPnotas')->where('idElementoP', $idElementoP)->get();
        return $dto;
    }
    public function create($data)
    {
        $idElementoP=0;//--cambiar id
        $tblmedidas1=[];$tblmedidas2=[];$tblmedidas3=[];$tblmedidasP=[];
        $tblacabados1=[];$tblacabados2=[];$tblacabados3=[];$tblacabadosP=[];
        $idUsuarioRegistro=session('idUsuario');
        $fecha = new \DateTime();$fecRegistro=$fecha->format('d-m-Y H:i');
        $data['fecRegistro'] = $fecRegistro;
        $data['idUsuarioRegistro'] = $idUsuarioRegistro;
        // DB::beginTransaction();

        // try {
            ElementosPresupuestados::create($data);
            $idElementoP=DB::getPdo()->lastInsertId();
            $data['idElementoP'] = $idElementoP;
        // return ($data );
            foreach ($data as $key => $value) 
            {
                
                $value=$value=='on'? 1:$value;
                if (isset($value ) && str_contains($key, 'idTipoEmpaque') )
                        {
                        $Empaque  ['idTipoEmpaque']= $value;
                        $Empaque  ['idElementoP']= $idElementoP;
                        $Empaque  ['obsEmpaque']= request('obsEmpaque');
                        ElementosPempaque::create($Empaque);
                        }
                    
                    
                else if (isset($value ) && str_contains($key, 'idTipoAcabadoGral') )
                        {
                            $AcabadosGral  ['idAcabado']= $value;
                            $AcabadosGral  ['idElementoP']= $idElementoP;
                            ElementosPacabadosGral::create($AcabadosGral);
                        }
                    
            ////----------------------- TABLA DE MEDIDAS Y ACABADOS --------------------------------
                else if (isset($value )  && str_contains($key, '_tbl-')  )//|| && str_contains($key, '_tbl-1')
                { //echo ($idElementoP);
                    
                    if (str_contains($key, '-acab') )
                    {
                        if (str_contains($key, '_tbl-P-acab'))
                            {
                                $campo=explode('_',$key);$numInterior=explode('-',$campo[1]);
                                $tblacabadosP['numInterior']=$numInterior[1];
                                $tblacabadosP['idElementoP']= $idElementoP;
                                $tblacabadosP[$campo[0]]=$value;
                            }
                        for ($i=1; $i <=3 ; $i++) 
                        { 
                            if (str_contains($key, ('_tbl-'.$i.'-acab')))
                            {
                                $campo=explode('_',$key);$numInterior=explode('-',$campo[1]);
                                ${'tblacabados'.$i}['numInterior']=$numInterior[1];
                                ${'tblacabados'.$i}['idElementoP']= $idElementoP;
                                ${'tblacabados'.$i}[$campo[0]]=$value;
                            }
                        }
                    }
                    else 
                    {
                        if (str_contains($key, '_tbl-P'))
                            {echo ($idElementoP);
                                if (!isset($data['nombreInterior_tbl-P'])) {$tblmedidasP['nombreInterior']= 'Portada';}//echo 'si existe nombre';
                                $campo=explode('_',$key);$numInterior=explode('-',$campo[1]);
                                $tblmedidasP['numInterior']=$numInterior[1];
                                $tblmedidasP['idElementoP']= $idElementoP; echo ($idElementoP);
                                $tblmedidasP[$campo[0]]=$value;
                            }
                        for ($i=1; $i <=3 ; $i++) 
                        { 
                            if (!isset($data['nombreInterior_tbl-'.$i])) {
                                ${'tblmedidas'.$i}['nombreInterior']= 'Interior '.$i;
                            }
                            
                            if (str_contains($key, ('_tbl-'.$i))) {
                                $campo=explode('_',$key);$numInterior=explode('-',$campo[1]);
                                ${'tblmedidas'.$i}['numInterior']=$numInterior[1];
                                ${'tblmedidas'.$i}['idElementoP']= $idElementoP;
                                ${'tblmedidas'.$i}[$campo[0]]=$value;
                            }
                        }
                    }
                
                }
            
            }
            if (!empty($tblmedidasP)) { ElementosPmedidas::create($tblmedidasP);}
            if (!empty($tblacabadosP)) { ElementosPacabados::create($tblacabadosP);}
            for ($i=1; $i <=3 ; $i++) { 
                if (!empty(${'tblmedidas'.$i})) { ElementosPmedidas::create(${'tblmedidas'.$i});}
                if (!empty(${'tblacabados'.$i})) { ElementosPacabados::create(${'tblacabados'.$i});}
            }
            
                //     DB::commit();
        // } catch (\Illuminate\Database\QueryException $ex) {
        //     DB::rollback();
        //     // DB::select('DBCC CHECKIDENT ("Emp", RESEED, 1)');
        //     // dd($ex->getMessage()); 
        //     echo('error');
        // }
        
            return true;
    }

    public function  addValor($idElementoP,$numPresupuestoMTC)
    {
        $dto=new \stdClass; 
      $dto->addValorOpo = DB::statement(DB::raw('SET NOCOUNT ON;exec sp_EPaddValorOportunidad :numPresupuestoMTC,:idElementoP'),[':numPresupuestoMTC' => $numPresupuestoMTC,':idElementoP' => $idElementoP]);
                        //   DB::statement(DB::raw('SET NOCOUNT ON;exec sp_EPaddValorOportunidad :numPresupuestoMTC,:idElementoP'),[':numPresupuestoMTC' => $numPresupuestoMTC,':idElementoP' => $idElementoP]);
        return 'ok';
    }
}
