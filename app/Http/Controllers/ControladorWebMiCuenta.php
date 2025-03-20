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
             $aPedidos = $pedido->obtenerPedidosPorCliente($idCliente);


                return view('web.mi-cuenta',compact('cliente','pedido','aPedidos','aSucursales'));
        }else{
                return redirect('/login');
        	}
    }

    public function guardar(Request $request){
        $cliente = new Cliente();
        $idCliente = Session::get("idCliente");
        $cliente->idcliente = $idCliente;
        $cliente->nombre = $request->input("txtNombre");
        $cliente->apellido = $request->input("txtApellido");
        $cliente->correo = $request->input("txtCorreo");
        $cliente->dni = $request->input("txtDni");
        $cliente->celular = $request->input("txtCelular");
        $cliente->direccion = $request->input("txtDireccion");
        $cliente->guardar();

        $sucursal = new Sucursal();
        $aSucursales = $sucursal->obtenerTodos();

        $pedido = new Pedido();
        $aPedidos = $pedido->obtenerPedidosPorCliente($idCliente);

        return view('web.mi-cuenta', compact('cliente', 'aPedidos', 'aSucursales'));
    }
}