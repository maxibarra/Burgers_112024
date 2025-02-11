<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Entidades\Sucursal;
use App\Entidades\Cliente;
use App\Entidades\Carrito;
use Session;


class ControladorWebLogin extends Controller
{
    public function index()
    {
        $sucursal = new Sucursal();
        $aSucursales = $sucursal->obtenerTodos();

    
        return view('web.login', compact('aSucursales'));
    }

    public function ingresar(Request $request){

        $sucursal = new Sucursal();
        $aSucursales = $sucursal->obtenerTodos();
        $correo = $request->input("txtCorreo");
        $clave = $request->input("txtClave");

        $cliente = new Cliente();
        $cliente->obtenerPorCorreo($correo);
        if($cliente->correo != ""){
            if(password_verify($clave,$cliente->clave)){
                Session::put('idCliente', $cliente->idcliente);
                return redirect('/');
            }
        }else{
            $mensaje = 'Credenciales incorrectas';
            return view('web.login', compact('aSucursales'));
        }    
    }

    public function logout(){
        Session::put("idCliente","");
        return redirect('/');
    }
}