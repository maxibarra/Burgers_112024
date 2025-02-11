<?php

namespace App\Http\Controllers;

use App\Entidades\Cliente;
use App\Entidades\Pedido;
use App\Entidades\Sucursal;
use Illuminate\Http\Request;
use Session;

class ControladorWebMiCuenta extends Controller
{
    public function index()
    {   
        $idCliente = session("idCliente");
        if($idCliente != ""){
            
            $cliente = new Cliente();
            $cliente->obtenerPorId($idCliente);

            $sucursal = new Sucursal();
            $aSucursales = $sucursal->obtenerTodos();

            $pedido = new Pedido();
             $aPedidos = $pedido->obtenerPorCliente($idCliente);


                return view('web.mi-cuenta',compact('cliente','pedido','aPedidos','aSucursales'));
        }else{
                return redirect('/login');
        	}
    }

    public function guardar(Request $request){
        $cliente = new Cliente();
        $cliente->idcliente = Session::get("idCliente");
        $cliente->nombre = $request->input("txtNombre");
        $cliente->correo = $request->input("txtCorreo");
        $cliente->telefono = $request->input("txtTelefono");
    }
}