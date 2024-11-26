<?php

namespace App\Http\Controllers;


class ControladorPedido extends Controller{

      public function nuevo(){
            $titulo = "Nuevo Pedido";
            return view("sistema.pedido-nuevo",compact("titulo"));
      }
}