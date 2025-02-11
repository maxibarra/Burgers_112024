<?php

namespace App\Http\Controllers;

use App\Entidades\Carrito;
use App\Entidades\SUcursal;
use Illuminate\Http\Request;
use Session;

require app_path() . '/start/constants.php';


class ControladorWebCarrito extends Controller
{
    public function index()
    {
        $idCliente = Session::get("idCliente");
        $carrito = new Carrito();
        $aCarritos = $carrito->obtenerPorCliente($idCliente);
        $sucursal = new Sucursal();
        $aSucursales = $sucursal->obtenerTodos();

        return view('web.carrito', compact("aSucursales", "aCarritos"));
    }

    public function procesar(Request $request)
    {
        if (isset($_POST["btnBorrar"])) {
            $this->eliminar($request);
        } else if (isset($_POST["btnActualizar"])) {
            $this->actualizar($request);
        } else if (isset($_POST["btnFinalizar"])) {
            $this->insertarPedido($request);
        }
    }

    public function actualizar(Request $request)
    {
        $cantidad = $request->input("txtCantidad");
        $carrito = new Carrito();
        $carrito->cantidad = $cantidad;
        $carrito->guardar();
        $msg["ESTADO"] = MSG_SUCCESS;
        $msg["MSG"] = "Producto actualizado exitosamente";
        return view('web.carrito', compact('msg'));
    }

    public function eliminar(Request $request)
    {
        $idCarrito = $request->input("txtCarrito");
        $carrito = new Carrito();
        $carrito->idcarrito = $idCarrito;
        $carrito->eliminar();
        $msg["ESTADO"] = MSG_SUCCESS;
        $msg["MSG"] = "Producto eliminado exitosamente";
        return view('web.carrito', compact('msg'));
    
    }

    public function insertarPedido(Request $request) {}
}
