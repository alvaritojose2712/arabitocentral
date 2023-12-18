<?php

namespace App\Http\Controllers;

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


        return nomina::orwhere("nominasucursal", $id_origen)
            ->orwhereIn("id", nominavariassucursales::where("id_sucursal", $id_origen)->select("id_nomina"))
            ->orderBy("nominanombre", "asc")
            ->get()->map(function ($q) {
                $q->nominacedula = $q->nominacedula . "=" . $q->nominanombre;
                return $q;
            });
    }
    function getPersonalNomina(Request $req)
    {
        $update = [
            ["24602347", "9"],	
            ["27721175", "5"],	
            ["27653133", "5"],	
            ["28029636", "5"],	
            ["27291818", "5"],	
            ["28029882", "5"],	
            ["24986853", "5"],	
            ["29946263", "5"],	
            ["30049527", "5"],	
            ["28280580", "5"],	
            ["20335405", "5"],	
            ["27653469", "5"],	
            ["30388637", "5"],	
            ["28680747", "5"],	
            ["29558107", "5"],	
            ["27231479", "5"],	
            ["23567366", "5"],	
            ["30649335", "5"],	
            ["30325763", "5"],	
            ["29647768", "5"],	
            ["31849362", "5"],	
            ["28662763", "5"],	
            ["27721183", "5"],	
            ["26345137", "5"],	
            ["25617470", "5"],	
            ["29946263", "5"],	
            ["31031692", "5"],	
            ["29716608", "5"],	
            ["21658405", "5"],	
            ["31660312", "5"],	
            ["26944205", "5"],	
            ["27338236", "5"],	
            ["26961582", "5"],	
            ["29791191", "5"],	
            ["28519007", "5"],	
            ["28662645", "5"],	
            ["25617470", "5"],	
            ["29782243", "5"],	
            ["30432252", "5"],	
            ["22615693", "4"],	
            ["29716907", "4"],	
            ["24238890", "4"],	
            ["21315658", "4"],	
            ["31067229", "4"],	
            ["26980585", "4"],	
            ["19405011", "4"],	
            ["26216812", "4"],	
            ["24539738", "4"],	
            ["24967623", "4"],	
            ["25968918", "4"],	
            ["26231210", "4"],	
            ["31084346", "4"],	
            ["29559750", "4"],	
            ["28408819", "4"],	
            ["29894019", "4"],	
            ["21605296", "4"],	
            ["5555555", "4"], 
            ["31793082", "4"],	
            ["27697640", "4"],	
            ["19816381", "4"],	
            ["31174863", "4"],	
            ["26980433", "4"],	
            ["24838593", "4"],	
            ["921673745", "4"],	
            ["29941383", "4"],	
            ["24755801", "4"],	
            ["27231667", "4"],	
            ["31730992", "4"],	
            ["27313750", "4"],	
            ["21279739", "4"],	
            ["30649237", "4"],	
            ["30732611", "4"],	
            ["32160004", "4"],	
            ["29791813", "4"],	
            ["30207188", "4"],	
            ["27697473", "4"],	
            ["27665645", "4"],	
            ["23600301", "4"],	
            ["26328015", "4"],	
            ["19050345", "4"],	
            ["31171730", "4"],	
            ["26848615", "4"],	
            ["24239874", "4"],	
            ["26464487", "4"],	
            ["30418309", "4"],	
            ["25634746", "6"],	
            ["30689652", "6"],	
            ["30207413", "6"],	
            ["27721112", "6"],	
            ["14520871", "6"],	
            ["15608847", "6"],	
            ["21315111", "6"],	
            ["20722506", "6"],	
            ["19877456", "9"],	
            ["19818841", "7"],	
            ["24823411", "7"],	
            ["21317698", "7"],	
            ["28421316", "7"],	
            ["21316180", "7"],	
            ["27338604", "7"],	
            ["19918980", "7"],	
            ["18251950", "7"],	
            ["21146220", "7"],	
            ["21005686", "7"],	
            ["25420892", "7"],	
            ["27009020", "2"],	
            ["24944934", "2"],	
            ["20089821", "2"],	
            ["17199009", "2"],	
            ["30168173", "2"],	
            ["19962933", "2"],	
            ["19816381", "1"],	
            ["16528669", "1"],	
            ["13150630", "1"],	
            ["7263302", "1"], 
            ["27541844", "1"],	
            ["29865868", "1"],	
            ["21147882", "1"],	
            ["26518044", "8"],	
            ["19552701", "8"],	
            ["29716771", "8"],	
            ["17607397", "8"],	
            ["20716746", "3"],	
            ["28169397", "3"],	
            ["30388530", "3"],	
            ["26088100", "3"],	
            ["26220785", "3"],	
            ["24539203", "3"],	
            ["25796046", "3"],	
            ["33981560", "3"],	
            ["25864501", "3"],	
            ["26088100", "3"],	
            ["28485962", "3"],	
            ["19222206", "3"],	
            ["28001610", "3"],	
            ["27697449", "3"],	
            ["28236899", "3"],	
            ["31347153", "3"],	
            ["24985722", "3"],	
            ["30772406", "3"],	
            ["27338054", "3"],	
            ["29781038", "3"],	
            ["30483067", "3"],	
            ["25419409", "3"],	
            ["29941371", "3"],	
            ["29782817", "3"],	
            ["22888063", "3"],	
            ["27541886", "3"],	
            ["30375069", "3"],	
            ["30627539", "5"],	
            ["24240041", "3"],	
            ["32075175", "4"],	
            ["25382917", "4"],	
            ["24199380", "1"],	
            ["30291102", "5"],	
            ["20957642", "2"],	
            ["22292348", "1"],	
            ["26717412", "4"],	
            ["26027496", "4"],	
            ["30627540", "4"],	
            ["22882946", "3"],	
            ["28442201", "6"],	
            ["30264417", "4"],	
            ["30689878", "4"],	
            ["28442202", "1"],	
            ["24620118", "1"],	
            ["19580153", "4"],	
            ["27907080", "3"],	
            ["37375109", "1"],	
            ["28680575", "6"],	
            ["27338249", "5"],	
            ["31573406", "3"],	
            ["213130091", "4"],	
            ["29865518", "4"],	
            ["28292480", "4"],	
            ["26088100", "3"],	
            ["18405409", "4"],	
            ["21581668", "1"],	
            ["26316358", "6"],	
            ["31174863", "4"],
    
    ];

    foreach ($update as $key => $e) {
        $wh = nomina::where("nominacedula",$e[0]);

        if ($wh) {
            $wh->nominacargo = $e[1];
            $wh->save();
        }
    }


        $qNomina = $req->qNomina;
        $qSucursalNomina = $req->qSucursalNomina;
        $qCargoNomina = $req->qCargoNomina;

        $fechasMain1 = $req->fechasMain1;
        $fechasMain2 = $req->fechasMain2;

        $type = $req->type;

        $personal = nomina::with(["sucursal", "cargo"])->where(function ($q) use ($qNomina) {
            $q
                ->orWhere("nominanombre", "LIKE", "$qNomina%")
                ->orWhere("nominacedula", "LIKE", "$qNomina%");
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
                    $q->with("sucursal");
                }]);
            })
            ->get();

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
