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

            $d = usuarios::where('usuario', $req->usuario)->first();
            
            if ($d) {
                if (\Hash::check($req->clave, $d->clave)) {
                    session([
                       "id_usuario" => $d->id,
                       "tipo_usuario" => $d->tipo_usuario,
                       "usuario" => $d->usuario,
                       "nombre" => $d->nombre,
                       "id_sucursal" => $d->id_sucursal,
                    ]);
                   
                   $estado = $this->selectRedirect();
                   return Response::json( [
                       "id_usuario" => $d->id,
                       "id_sucursal" => $d->id_sucursal,
                       "tipo_usuario" => $d->tipo_usuario,
                       "usuario" => $d->usuario,
                       "nombre" => $d->nombre,
                       "estado"=>true,
                       "msj"=>"¡Inicio exitoso! Bienvenido/a, ".$d->nombre
                   ] );
                }else{
                    throw new \Exception("Clave Incorrecta!", 1);
                }
            }else{
                throw new \Exception("Usuario Incorrecto!", 1);
            } 
            
        } catch (\Exception $e) {
            $req->session()->flush();

            return Response::json(["msj"=>"Error: ".$e->getMessage(),"estado"=>false]);
        }
       
    }

    
}
