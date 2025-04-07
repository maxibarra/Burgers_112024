<?php

namespace App\Http\Controllers;

use App\Entidades\Pedido;
use Illuminate\Http\Request;

require app_path() . '/start/constants.php';

class ControladorMercadoPago extends Controller{

      public function aprobar($idPedido){
            $pedido = new Pedido();
            $pedido->obtenerPorId($idPedido);
            $pedido->fk_idestadopedido = 1;
            $pedido->guardar();
            return redirect("/mi-cuenta");
        }

      public function pendiente($idPedido){
            $pedido = new Pedido();
            $pedido->obtenerPorId($idPedido);
            $pedido->fk_idestadopedido = 5;
            $pedido->guardar();
            return redirect("/mi-cuenta");
        }

      public function error($idPedido){
            $pedido = new Pedido();
            $pedido->obtenerPorId($idPedido);
            $pedido->fk_idestadopedido = 4;
            $pedido->guardar();
            return redirect("/mi-cuenta");
        }
}