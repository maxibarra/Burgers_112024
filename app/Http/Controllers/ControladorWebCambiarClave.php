<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entidades\Cliente;
use App\Entidades\Sucursal;

use Session;

require app_path() . '/start/constants.php';
class ControladorWebCambiarClave extends Controller
{
    public function index()
    {
            return view('web.cambiar-clave');
    }

    public function cambiar(Request $request){
        $titulo = "Cambiar Clave";
        $idCliente = Session::get("idCliente");
        $cliente = new Cliente();
        $clave1=$request->input("txtClave1");
        $clave2=$request->input("txtClave2");
        $sucursal = new Sucursal();
        $aSucursales= $sucursal->obtenerTodos();

        if($clave1 != "" && $clave1 == $clave2){
            $cliente-> obtenerPorId($idCliente);
            $cliente->clave = password_hash($clave1, PASSWORD_DEFAULT);
            $cliente->guardar();
            $msg["ESTADO"] = MSG_SUCCESS;
            $msg["MSG"] = "Cambiado correctamente";
            return view('web.cambiar-clave', compact('msg', 'aSucursales'));
        }else{
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = "Las contraseñas no coinciden";
            return view('web.cambiar-clave', compact("titulo",'msg', 'aSucursales'));
        }
    }
}