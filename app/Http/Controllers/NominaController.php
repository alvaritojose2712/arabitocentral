<?php

namespace App\Http\Controllers;

use App\Models\clientes;
use App\Models\creditos;
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
    function getNomina(Request $req)
    {
        $codigo_origen = $req->codigo_origen;

        $id_ruta = (new InventarioSucursalController)->retOrigenDestino($codigo_origen, $codigo_origen);
        $id_origen = $id_ruta["id_origen"];


        return nomina::with("cargo")->orwhere("nominasucursal", $id_origen)
            ->orwhereIn("id", nominavariassucursales::where("id_sucursal", $id_origen)->select("id_nomina"))
            ->orderBy("nominanombre", "asc")
            ->get();
    }
    function getPersonalNomina(Request $req)
    {
       


        $qNomina = isset($req->qNomina)? $req->qNomina: "";
        $qSucursalNomina = isset($req->qSucursalNomina)? $req->qSucursalNomina: "";
        $qCargoNomina = isset($req->qCargoNomina)? $req->qCargoNomina: "";

        $fechasMain1 = isset($req->fechasMain1)? $req->fechasMain1: "";
        $fechasMain2 = isset($req->fechasMain2)? $req->fechasMain2: "";

        $type = isset($req->type)? $req->type: "";

        $personal = nomina::with(["sucursal", "cargo"])->where(function ($q) use ($qNomina) {
            $q
                ->orWhere("nominanombre", "LIKE", "%$qNomina%")
                ->orWhere("nominacedula", "LIKE", "%$qNomina%");
        })
        ->selectRaw("*, round(DATEDIFF(NOW(), nominas.nominafechadenacimiento)/365.25, 2) as edad, round(DATEDIFF(NOW(), nominas.nominafechadeingreso)/365.25, 2) as tiempolaborado")
        ->when($qCargoNomina, function ($q) use ($qCargoNomina) {
            $q->where("nominacargo", $qCargoNomina);
        })
        ->when($qSucursalNomina, function ($q) use ($qSucursalNomina) {
            $q->where("nominasucursal", $qSucursalNomina);
        })
        ->when($type == "pagos", function ($q) use ($fechasMain1, $fechasMain2) {
            $q->with(["pagos" => function ($q) {
                $q->with("sucursal")->orderBy("created_at","desc");
            }]);
        })
        ->get()
        ->map(function($q) {
            $q->sumPagos = $q->pagos->sum("monto");
            $cedula = $q->nominacedula;


            $ids = clientes::where("identificacion", "=",  $cedula)->select("id");
            $creditos = creditos::with("sucursal")->whereIn("id_cliente",$ids)->get();
            $q->creditos = $creditos; 
            $q->sumCreditos = $creditos->sum("saldo");
            return $q;
        })
        ->toArray();
        
        array_multisort(array_column($personal,"sumPagos"), SORT_ASC, $personal);

        $estadisticas = [];

        return [
            "personal" => $personal,
            "estadisticas" => $estadisticas,
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
        ]);
    }
}
