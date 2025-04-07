<?php

namespace App\Http\Controllers;

use App\Entidades\Carrito;
use App\Entidades\Sucursal;
use App\Entidades\Pedido;
use App\Entidades\Cliente;
use App\Entidades\Pedido_producto;
use Illuminate\Http\Request;

use  MercadoPago\Item;
use  MercadoPago\MerchantOrder;
use  MercadoPago\Payer;
use  MercadoPago\Payment;
use  MercadoPago\Preference;
use  MercadoPago\SDK;

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
            return $this->eliminar($request);
        } else if (isset($_POST["btnActualizar"])) {
            return $this->actualizar($request);
        } else if (isset($_POST["btnFinalizar"])) {
            return $this->insertarPedido($request);
        }
    }

    public function actualizar(Request $request)
    {
        $carrito = new Carrito();
        $idCarrito = $request->input("txtCarrito");
        $cantidad = $request->input("txtCantidad");
        $idProducto = $request->input("txtProducto");
        $idCliente = Session::get("idCliente");

        $carrito->idcarrito = $idCarrito;
        $carrito->cantidad = $cantidad;
        $carrito->fk_idcliente = $idCliente;
        $carrito->fk_idproducto = $idProducto;
        $carrito->guardar();
        $msg["ESTADO"] = MSG_SUCCESS;
        $msg["MSG"] = "Producto actualizado correctamente";


        $sucursal = new Sucursal();
        $aSucursales = $sucursal->obtenerTodos();
        $carrito = new Carrito();
        $aCarritos = $carrito->obtenerPorCliente($idCliente);

        return view('web.carrito', compact('msg', "aSucursales", "aCarritos"));
    }

    public function eliminar(Request $request)
    {

        $idCliente = Session::get("idCliente");
        $idCarrito = $request->input("txtCarrito");
        $carrito = new Carrito();
        $carrito->idcarrito = $idCarrito;
        $carrito->eliminar();
        $msg["ESTADO"] = MSG_SUCCESS;
        $msg["MSG"] = "Producto eliminado exitosamente";
        $carrito = new Carrito();
        $aCarritos = $carrito->obtenerPorCliente($idCliente);
        $sucursal = new Sucursal();
        $aSucursales = $sucursal->obtenerTodos();
        return view('web.carrito', compact('msg', "aSucursales", "aCarritos"));
    }

    public function insertarPedido(Request $request)
    {


        $idCliente = Session::get("idCliente");
        $idSucursal = $request->input("lstSucursal");
        $pago = $request->input("lstPago");

        if ($pago == "Mercadopago") {
            $this->procesarMercadopago($request);
        } else {


            $carrito = new Carrito();
            $aCarritos = $carrito->obtenerPorCliente($idCliente);

            $sucursal = new Sucursal();
            $aSucursales = $sucursal->obtenerTodos();

            $total = 0;
            foreach ($aCarritos as $item) {
                $total += $item->precio * $item->cantidad;
            }


            $fecha = date("Y-m-d");

            $pedido = new Pedido();
            $pedido->fk_idcliente = $idCliente;
            $pedido->fk_idsucursal = $idSucursal;
            $pedido->fk_idestadopedido = 1;
            $pedido->fecha = $fecha;
            $pedido->total = $total;
            $pedido->pago = $pago;
            $pedido->insertar();

            $pedidoProducto = new Pedido_producto();
            foreach ($aCarritos as $item) {
                $pedidoProducto->fk_idproducto = $item->fk_idproducto;
                $pedidoProducto->fk_idpedido = $pedido->idpedido;
                $pedidoProducto->cantidad = $item->cantidad;
                $pedidoProducto->insertar();
            }
            $carrito->eliminarPorCliente($idCliente);


            $msg["ESTADO"] = MSG_SUCCESS;
            $msg["MSG"] = "Pedido realizado correctamente";
            return view('web.carrito', compact("msg", "aSucursales", "aCarritos"));
        }
    }

    public function ProcesarMercadopago(Request $request)
    {
        $access_token= "";
          SDK::setClientId(config("payment-methods.mercadopago.client"));
          SDK::setClientSecret(config("payment-methods.mercadopago.secret"));
          SDK::setAccessToken($access_token); // es el token de la cuenta de MP donde se deposita el dinero.

        
        $idCliente = Session::get("idCliente");
        $idSucursal = $request->input("lstSucursal");
        $pago = $request->input("lstPago");

        $cliente = new Cliente();
        $cliente->obtenerPorId($idCliente);

        $carrito = new Carrito();
        $aCarritos = $carrito->obtenerPorCliente($idCliente);

        $sucursal = new Sucursal();
        $aSucursales = $sucursal->obtenerTodos();

        $total = 0;
        foreach ($aCarritos as $item) {
            $total += $item->precio * $item->cantidad;
        }


        $fecha = date("Y-m-d");


        //Armado del producto 'Item'
        $item = new Item();
          $item->id = 1234;
          $item->title = "Compra web Burger SRL";
          $item->quantity = 1;
          $item->unit_price = $total;
          $item->currency_id = "ARS";    
          
        $preference = new Preference();
        $preference->items = array($item);

        //datos del comprador
        $payer = new Payer();
        $payer->nombre = $cliente->nombre;
        $payer->apellido = $cliente->apellido;
        $payer->email = $cliente->correo;
        $payer->date_created = date("Y-m-d H:m:s");
        $payer->identification = array(
            "type" => "DNI",
            "number" => $cliente->dni
        );
        $preference->payer = $payer;

        $pedido = new Pedido();
        $pedido->fk_idcliente = $idCliente;
        $pedido->fk_idsucursal = $idSucursal;
        $pedido->fk_idestadopedido = 5;
        $pedido->fecha = $fecha;
        $pedido->total = $total;
        $pedido->pago = $pago;
        $pedido->insertar();

        $pedidoProducto = new Pedido_producto();
        foreach ($aCarritos as $item) {
            $pedidoProducto->fk_idproducto = $item->fk_idproducto;
            $pedidoProducto->fk_idpedido = $pedido->idpedido;
            $pedidoProducto->cantidad = $item->cantidad;
            $pedidoProducto->insertar();
        }
        $carrito->eliminarPorCliente($idCliente);

        $preference->back_urls = [
            "success" => "http://localhost:8000/mercado-pago/aprobado/" . $pedido->idpedido,
            "pending" => "http://localhost:8000/mercado-pago/pendiente/" . $pedido->idpedido,
            "failure" => "http://localhost:8000/mercado-pago/rechazado/" . $pedido->idpedido
        ];

        $preference->payment_methods = array("installments" => 6);
        $preference->auto_return = "all";
        $preference->notification_url = "";
        $preference->save(); //ejecuta la transacción

    }
    
}
