<?php

namespace App\Http\Controllers;

use App\Models\clientes;
use App\Models\creditos;
use App\Models\nominaprestamos;
use App\Models\nominavariassucursales;

use Illuminate\Http\Request;

use App\Models\nomina;
use App\Models\puntosybiopagos;
use App\Models\bancos_list;

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

    function cargarNomina2dajulio() {
        $nominas = [
        ["2024-07-30","18251950","309.97","1513.11","12820.52","0102","36.6090"],
        ["2024-07-30","29716608","309.97","1513.11","2753.05","0102","36.6090"],
        ["2024-07-30","30207188","309.97","1513.11","922.60","0102","36.6090"],
        ["2024-07-30","26088061","309.97","1513.11","922.60","0102","36.6090"],
        ["2024-07-30","27009020","309.97","1513.11","1471.73","0102","36.6090"],
        ["2024-07-30","23600301","309.97","1513.11","190.42","0102","36.6090"],
        ["2024-07-31","25419409","309.97","1513.11","1105.94","0102","36.6128"],
        ["2024-07-31","20089821","309.97","1513.11","1105.94","0102","36.6128"],
        ["2024-07-31","27963951","309.97","1513.11","190.62","0102","36.6128"],
        ["2024-07-31","27231986","309.97","1513.11","7.56","0102","36.6128"],
        ["2024-07-31","21146894","309.97","1513.11","1105.94","0102","36.6128"],
        ["2024-07-31","30686708","309.97","1513.11","190.62","0102","36.6128"],
        ["2024-07-31","32428983","309.97","1513.11","922.88","0102","36.6128"],
        ["2024-07-31","25836310","309.97","1513.11","556.75","0102","36.6128"],
        ["2024-07-31","27945688","309.97","1513.11","7.56","0102","36.6128"],
        ["2024-07-31","30733133","309.97","1513.11","1105.94","0102","36.6128"],
        ["2024-07-31","21316180","309.97","1513.11","7330.12","0102","36.6128"],
        ["2024-07-31","31517468","309.97","1513.11","7.56","0102","36.6128"],
        ["2024-07-31","28585257","309.97","1513.11","7.56","0102","36.6128"],
        ["2024-07-31","31067317","309.97","1513.11","7.56","0102","36.6128"],
        ["2024-07-31","20335405","309.97","1513.11","9160.76","0102","36.6128"],
        ["2024-07-31","24239890","309.97","1513.11","3668.84","0102","36.6128"],
        ["2024-07-31","30388530","309.97","1513.11","1838.20","0102","36.6128"],
        ["2024-07-31","22882537","309.97","1513.11","44.17","0102","36.6128"],
        ["2024-07-31","26220785","309.97","1513.11","1838.20","0102","36.6128"],
        ["2024-07-31","29716907","309.97","1513.11","1362.23","0102","36.6128"],
        ["2024-07-31","24539738","309.97","1513.11","7.56","0102","36.6128"],
        ["2024-07-31","26088031","309.97","1513.11","922.88","0102","36.6128"],
        ["2024-07-31","29894019","309.97","1513.11","1838.20","0102","36.6128"],
        ["2024-07-31","30686019","309.97","1513.11","190.62","0102","36.6128"],
        ["2024-07-31","20233779","309.97","1513.11","1105.94","0102","36.6128"],
        ["2024-07-31","27370913","309.97","1513.11","922.88","0102","36.6128"],
        ["2024-07-31","31659875","309.97","1513.11","922.88","0102","36.6128"],
        ["2024-07-31","27861578","309.97","1513.11","263.85","0102","36.6128"],
        ["2024-07-31","20233181","309.97","1513.11","446.91","0102","36.6128"],
        ["2024-07-31","31133312","309.97","1513.11","922.88","0102","36.6128"],
        ["2024-07-31","30649514","309.97","1513.11","922.88","0102","36.6128"],
        ["2024-07-31","28298144","309.97","1513.11","739.82","0102","36.6128"],
        ["2024-07-31","28421316","309.97","1513.11","12822.04","0102","36.6128"],
        ["2024-07-31","22577315","309.97","1513.11","7.56","0102","36.6128"],
        ["2024-07-31","31174863","309.97","1513.11","3668.84","0102","36.6128"],
        ["2024-07-31","28485962","309.97","1513.11","4767.22","0102","36.6128"],
        ["2024-07-31","29653706","309.97","1513.11","922.88","0102","36.6128"],
        ["2024-07-31","31203809","309.97","1513.11","922.88","0102","36.6128"],
        ["2024-07-31","27653469","309.97","1513.11","1838.20","0102","36.6128"],
        ["2024-07-31","30055120","309.97","1513.11","190.62","0102","36.6128"],
        ["2024-07-31","15608847","309.97","1513.11","10259.14","0102","36.6128"],
        ["2024-07-31","30346531","309.97","1513.11","2936.58","0102","36.6128"],
        ["2024-07-31","30280502","309.97","1513.11","190.62","0134","36.6128"],
        ["2024-07-31","21146220","309.97","1513.11","1838.20","0134","36.6128"],
        ["2024-07-31","27338236","309.97","1513.11","922.88","0134","36.6128"],
        ["2024-07-31","28680575","309.97","1513.11","922.88","0134","36.6128"],
        ["2024-07-31","27338279","309.97","1513.11","7.56","0134","36.6128"],
        ["2024-07-31","21277104","309.97","1513.11","922.88","0134","36.6128"],
        ];

        foreach ($nominas as $key => $e) {
            $id_banco = bancos_list::where("codigo",$e[5])->first()->id;
            $ci = $e[1];
            $tasa = $e[6];
            $id_nomina = nomina::where("nominacedula",$ci)->first("id");
            
            $monto_bs = $e[2];
            $monto = $e[2]/$tasa;
            $p = puntosybiopagos::updateOrCreate(["id"=>null],[
                "loteserial" => "NOMINA ADMIN ".$ci,
                "banco" => $e[5],
                "id_banco" => $id_banco,
                "categoria" => 29,
                "fecha" => $e[0],
                "fecha_liquidacion" => $e[0],
                "tipo" => "Transferencia",

                "id_sucursal" => 13,
                "id_beneficiario" => $id_nomina,
                "tasa" => $tasa,
                
                "monto" => $monto_bs*-1,
                "monto_liquidado" => $monto_bs*-1,
                "monto_dolar" => 0,

                "origen" => 2,
                "id_usuario" => session("id_usuario"),
            ]);
            (new NominapagosController)->setPagoNomina($ci, $monto, 13, $p->id, $e[0]);



            $monto_bs = $e[3];
            $monto = $e[3]/$tasa;
            $p = puntosybiopagos::updateOrCreate(["id"=>null],[
                "loteserial" => "NOMINA ADMIN ".$ci,
                "banco" => $e[5],
                "id_banco" => $id_banco,
                "categoria" => 29,
                "fecha" => $e[0],
                "fecha_liquidacion" => $e[0],
                "tipo" => "Transferencia",

                "id_sucursal" => 13,
                "id_beneficiario" => $id_nomina,
                "tasa" => $tasa,
                
                "monto" => $monto_bs*-1,
                "monto_liquidado" => $monto_bs*-1,
                "monto_dolar" => 0,

                "origen" => 2,
                "id_usuario" => session("id_usuario"),
            ]);
            (new NominapagosController)->setPagoNomina($ci, $monto, 13, $p->id, $e[0]);



            $monto_bs = $e[4];
            $monto = $e[4]/$tasa;
            $p = puntosybiopagos::updateOrCreate(["id"=>null],[
                "loteserial" => "NOMINA ADMIN ".$ci,
                "banco" => $e[5],
                "id_banco" => $id_banco,
                "categoria" => 29,
                "fecha" => $e[0],
                "fecha_liquidacion" => $e[0],
                "tipo" => "Transferencia",

                "id_sucursal" => 13,
                "id_beneficiario" => $id_nomina,
                "tasa" => $tasa,
                
                "monto" => $monto_bs*-1,
                "monto_liquidado" => $monto_bs*-1,
                "monto_dolar" => 0,

                "origen" => 2,
                "id_usuario" => session("id_usuario"),
            ]);
            (new NominapagosController)->setPagoNomina($ci, $monto, 13, $p->id, $e[0]);
        }
    }
}
