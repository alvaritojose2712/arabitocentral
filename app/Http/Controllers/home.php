<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\usuarios;

use Response;


class home extends Controller
{
    public function index()
    {
        return view("home.index");
    }

    public function today()
    {
        return date("Y-m-d");
    }

    public function selectRedirect()
    {
      $selectRedirect = "/";
        switch(session("tipo_usuario")){
            case 1:
                $selectRedirect = '/admin';
                break;
            case 2:
                $selectRedirect = '/cajero';
                break;
            default:
                $selectRedirect = '/login';
        }
      return $selectRedirect;
         
        // return $next($request);
    } 
    public function verificarLogin(Request $req)
    {
        if (session()->has("id_usuario")) {
            return Response::json( ["estado"=>true] );
        }else{
            return Response::json( ["estado"=>false] );
        }
    }
    public function logout(Request $request)
    {
        $request->session()->flush();

    }
    public function login(Request $req)
    {
        try {

            $d = usuarios::where(function($query) use ($req){
                $query->orWhere('usuario', $req->usuario);
            })
            ->first();
            
            if ($d&&\Hash::check($req->clave, $d->clave)) {
                 session([
                    "id_usuario" => $d->id,
                    "tipo_usuario" => $d->tipo_usuario,
                    "usuario" => $d->usuario,
                    "nombre" => $d->nombre,
                ]);
                
                $estado = $this->selectRedirect();
            }else{
                throw new \Exception("¡Datos Incorrectos!", 1);
                
            } 
            
            return Response::json( ["estado"=>true,"msj"=>"¡Inicio exitoso! Bienvenido/a, ".$d->nombre] );
        } catch (\Exception $e) {
            return Response::json(["msj"=>"Error: ".$e->getMessage(),"estado"=>false]);
        }
        
        
        return Response::json(["estado"=>$estado,"user"=>$d]);
       
    }

    
}
