<?php

namespace App\Http\Controllers;

use App\Models\clientes;
use App\Models\creditos;
use App\Models\nominaprestamos;
use App\Models\nominavariassucursales;

use Illuminate\Http\Request;

use App\Models\nomina;
use App\Http\Requests\StorenominaRequest;
use App\Http\Requests\UpdatenominaRequest;
use Response;

class NominaController extends Controller
{

    function today()
    {
        return date("Y-m-d");
    }
    function delPersonalNomina(Request $req)
    {
        try {
            $id = $req->id;

            $setCargo = nomina::find($id)->delete();
            if ($setCargo) {
                return Response::json([
                    "msj" => "Éxito",
                    "estado" => true,
                ]);
            }
        } catch (\Exception $e) {
            return Response::json([
                "msj" => "Error: " . $e->getMessage(),
                "estado" => false,
            ]);
        }
    }
    function activarPersonal(Request $req) {
        $id = $req->id;

        $n = nomina::find($id);

        if ($n) {
            $es = 0;
            if (!$n->activo) {
                $es = 1;
            }
            $n->activo = $es;
            $n->save();
        }
    }
    function getNomina(Request $req)
    {
        $codigo_origen = $req->codigo_origen;
        $id_ruta = (new InventarioSucursalController)->retOrigenDestino($codigo_origen, $codigo_origen);
        $id_origen = $id_ruta["id_origen"];

        
        return nomina::with(["cargo","prestamos", "pagos"=>function ($q) {
            $q->with("sucursal")->orderBy("created_at","asc");
        }])
        ->selectRaw("*, round(DATEDIFF(NOW(), nominas.nominafechadenacimiento)/365.25, 2) as edad, round(DATEDIFF(NOW(), nominas.nominafechadeingreso)/365.25, 2) as tiempolaborado")
        ->where("activo",1)
        ->where("id_sucursal_disponible", $id_origen)
        ->orderBy("nominanombre", "asc")
        ->orderBy("activo", "desc")
        ->get()
        ->map(function($q) {
            $cedula = $q->nominacedula;
            
            $today = (new NominaController)->today();
            $mesDate = strtotime($today);
            $mesDate = date('Y-m' , $mesDate);
    
            $mespasadoDate = strtotime('-1 months', strtotime($today));
            $mespasadoDate = date('Y-m' , $mespasadoDate);
    
            $mesantepasadoDate = strtotime('-2 months', strtotime($today));
            $mesantepasadoDate = date('Y-m' , $mesantepasadoDate);
    
            $mes = $mesDate;
            $mespasado = $mespasadoDate;
            $mesantepasado = $mesantepasadoDate;
            $ids = clientes::where("identificacion", "=",  $cedula)->select("id");
            $creditos = creditos::with("sucursal")->whereIn("id_cliente",$ids);

            $q->pagos = $q->pagos->map(function($q) {
                $q->created_at = date("d-m-Y", strtotime($q->created_at));
                return $q;
            });

            $pagos = $q->pagos;

            $mesSum = 0;
            $mespasadoSum = 0;
            $mesantepasadoSum = 0;

            foreach ($pagos as $pago) {
                if (str_contains($pago["created_at"],$mes)) {
                    $mesSum += $pago["monto"];
                }
                if (str_contains($pago["created_at"],$mespasado)) {
                    $mespasadoSum += $pago["monto"];
                }
                if (str_contains($pago["created_at"],$mesantepasado)) {
                    $mesantepasadoSum += $pago["monto"];
                }
            }
            $bono = $q["cargo"]["cargossueldo"];
            
            $q->mes = $mesSum;
            $q->mespasado = $mespasadoSum;
            $q->mesantepasado = $mesantepasadoSum;
            $q->bono = $bono;

            $q->quincena = $bono;
            $q->sumprestamos = $q->prestamos->sum("monto");
            
            $q->sumPagos = $pagos->sum("monto");

            $b = (floatval($bono)*2)-abs(floatval($mesSum));
            $maxpagopersona = $b>0?$b:0;

            $q->maxpagopersona = $maxpagopersona;
            
            $q->creditos = $creditos
            ->get()
            ->map(function($q) {
                $q->created_at = date("d-m-Y", strtotime($q->created_at));
                return $q;
            }); 
            $q->sumCreditos = $creditos->get()->sum("saldo");
            return $q;
        });

    }
    function getPersonalNomina(Request $req)
    {

        $qNomina = isset($req->qNomina)? $req->qNomina: "";
        $qSucursalNomina = isset($req->qSucursalNomina)? $req->qSucursalNomina: "";
        $qCargoNomina = isset($req->qCargoNomina)? $req->qCargoNomina: "";

        $qSucursalNominaOrden = isset($req->qSucursalNominaOrden) ? $req->qSucursalNominaOrden:"desc";
        $qSucursalNominaOrdenCampo = isset($req->qSucursalNominaOrdenCampo) ? $req->qSucursalNominaOrdenCampo:"sumPrestamos";
        $qSucursalNominaEstatus = $req->qSucursalNominaEstatus;
        $qSucursalNominaFecha = $req->qSucursalNominaFecha;
        
        

        $fechasMain1 = isset($req->fechasMain1)? $req->fechasMain1: "";
        $fechasMain2 = isset($req->fechasMain2)? $req->fechasMain2: "";

        $type = isset($req->type)? $req->type: "";

        $today = $qSucursalNominaFecha? $qSucursalNominaFecha:(new NominaController)->today();
        $mesDate = strtotime($today);
        $mesDate = date('Y-m' , $mesDate);

        $mespasadoDate = strtotime('-1 months', strtotime($today));
        $mespasadoDate = date('Y-m' , $mespasadoDate);

        $mesantepasadoDate = strtotime('-2 months', strtotime($today));
        $mesantepasadoDate = date('Y-m' , $mesantepasadoDate);

        $mes = $mesDate;
        $mespasado = $mespasadoDate;
        $mesantepasado = $mesantepasadoDate;

        $personal = nomina::with(["sucursal", "cargo","prestamos"])->where(function ($q) use ($qNomina) {
            $q
            ->orWhere("nominanombre", "LIKE", "%$qNomina%")
            ->orWhere("nominacedula", "LIKE", "%$qNomina%");
        })
        ->with(["pagos" => function ($q) use ($today){
            $q->with("sucursal")->where("created_at","<","$today 23:59:59")->orderBy("created_at","asc");
        }])
        ->selectRaw("*, round(DATEDIFF(NOW(), nominas.nominafechadenacimiento)/365.25, 2) as edad, round(DATEDIFF(NOW(), nominas.nominafechadeingreso)/365.25, 2) as tiempolaborado")
        ->when($qSucursalNominaEstatus!="", function ($q) use ($qSucursalNominaEstatus) {
            $q->where("activo", $qSucursalNominaEstatus);
        })
        ->when($qCargoNomina, function ($q) use ($qCargoNomina) {
            $q->where("nominacargo", $qCargoNomina);
        })
        ->when($qSucursalNomina, function ($q) use ($qSucursalNomina) {
            $q->where("nominasucursal", $qSucursalNomina);
        })
        ->orderBy("activo","desc")
        ->get()
        ->map(function($q) use ($mes,$mespasado,$mesantepasado, $today){
            $cedula = $q->nominacedula;
            $ids = clientes::where("identificacion", "=",  $cedula)->select("id");
            $creditos = creditos::with("sucursal")->where("created_at","<","$today 23:59:59")->whereIn("id_cliente",$ids); 

            $q->pagos = $q->pagos->map(function($q) {
                $q->created_at = date("d-m-Y", strtotime($q->created_at));
                return $q;
            });

            
            

            $pagos = $q->pagos;

            $mesSum = 0;
            $mespasadoSum = 0;
            $mesantepasadoSum = 0;

            foreach ($pagos as $pago) {
                if (str_contains($pago["created_at"],$mes)) {
                    $mesSum += $pago["monto"];
                }
                if (str_contains($pago["created_at"],$mespasado)) {
                    $mespasadoSum += $pago["monto"];
                }
                if (str_contains($pago["created_at"],$mesantepasado)) {
                    $mesantepasadoSum += $pago["monto"];
                }
            }


            
            $q->mes = $mesSum;
            $q->mespasado = $mespasadoSum;
            $q->mesantepasado = $mesantepasadoSum;



            $q->quincena = $q->cargo->cargossueldo;
            $diario = ($q->cargo->cargossueldo*2)/30;
            $q->diario = $diario;




            $today = new \DateTime($today);
            $iniciodelmes = new \DateTime(date("$mes-00"));
            $fechaingreso = new \DateTime($q->nominafechadeingreso);
            $tiempotrabajado = $iniciodelmes->diff($today);

            $tiempotrabajadomes = $fechaingreso->diff($today);

            if ($fechaingreso < $today && $fechaingreso < $iniciodelmes) {
                $tiempotrabajadoVar = $tiempotrabajado->d;
            }
            else if ($fechaingreso <= $today && $fechaingreso > $iniciodelmes) {
                $tiempotrabajadoVar = $tiempotrabajadomes->days+1;
            }
            else{
                $tiempotrabajadoVar = 0;
            }



            
            
            $q->tiempotrabajado = $tiempotrabajadoVar;
           // if ($tiempotrabajadoVar<=30) {
                $q->mensual = $tiempotrabajadoVar*$diario;
           // }else{
                //$q->mensual = $q->cargo->cargossueldo*2;
           // }


            $q->sumPagos = $pagos->sum("monto");
            $q->sumPrestamos = $q->prestamos->sum("monto");
            
            $q->creditos = $creditos->get()->map(function($q) {
                $q->created_at = date("d-m-Y", strtotime($q->created_at));
                return $q;
            }); 
            $q->sumCreditos = $creditos->get()->sum("saldo");
            return $q;
        })
        ->toArray();
        
        array_multisort(array_column($personal,$qSucursalNominaOrdenCampo), $qSucursalNominaOrden=="desc"?SORT_DESC:SORT_ASC, $personal);
        

        $bysucursalFun = collect($personal)->groupBy("sucursal.codigo");
        $bysucursal = [];

        
        
        foreach ($bysucursalFun as $i => $e) {
            $corresponde = abs($e->sum("mensual"));
            $pago = abs($e->sum("mes"));
            array_push($bysucursal,[
                "codigo" => $i,
                "sum" => count($e),
                "corresponde" => $corresponde,
                "pago" => $pago,
                "cuadre" =>$corresponde-$pago,
                "prestamos" =>$e->sum("sumPrestamos")
            ]);
        }



        return [
            "personal" => $personal,
            "estadisticas" => $bysucursal,
        ];
    }
    function setPersonalNomina(Request $req)
    {
        try {

            $nominaNombre = $req->nominaNombre;
            $nominaCedula = $req->nominaCedula;
            $nominaTelefono = $req->nominaTelefono;
            $nominaDireccion = $req->nominaDireccion;
            $nominaFechadeNacimiento = $req->nominaFechadeNacimiento;
            $nominaFechadeIngreso = $req->nominaFechadeIngreso;
            $nominaGradoInstruccion = $req->nominaGradoInstruccion;
            $nominaCargo = $req->nominaCargo;
            $nominaSucursal = $req->nominaSucursal;
            $id_sucursal_disponible = $req->nominaid_sucursal_disponible;
            $activo = $req->nominaactivo;
            
            

            $id = $req->id;

            $setPersonal = $this->setPersonal([
                "nominanombre" => $nominaNombre,
                "nominacedula" => $nominaCedula,
                "nominatelefono" => $nominaTelefono,
                "nominadireccion" => $nominaDireccion,
                "nominafechadenacimiento" => $nominaFechadeNacimiento,
                "nominafechadeingreso" => $nominaFechadeIngreso,
                "nominagradoinstruccion" => $nominaGradoInstruccion,
                "nominacargo" => $nominaCargo,
                "nominasucursal" => $nominaSucursal,
                "id_sucursal_disponible" => $id_sucursal_disponible,
                "activo" => $activo,
                "id" => $id,
            ]);

            if ($setPersonal) {
                return Response::json([
                    "msj" => "Éxito",
                    "estado" => true,
                ]);
            }
        } catch (\Exception $e) {
            return Response::json([
                "msj" => "Error: " . $e->getMessage(),
                "estado" => false,
            ]);
        }

    }


    function setPersonal($arr)
    {
        return nomina::updateOrCreate([
            "id" => $arr["id"]
        ], [
            "nominanombre" => $arr["nominanombre"],
            "nominacedula" => $arr["nominacedula"],
            "nominatelefono" => $arr["nominatelefono"],
            "nominadireccion" => $arr["nominadireccion"],
            "nominafechadenacimiento" => $arr["nominafechadenacimiento"],
            "nominafechadeingreso" => $arr["nominafechadeingreso"],
            "nominagradoinstruccion" => $arr["nominagradoinstruccion"],
            "nominacargo" => $arr["nominacargo"],
            "nominasucursal" => $arr["nominasucursal"],
            "activo" => $arr["activo"],
            
            "id_sucursal_disponible" => isset($arr["id_sucursal_disponible"])?$arr["id_sucursal_disponible"]:""
        ]);
    }
}
