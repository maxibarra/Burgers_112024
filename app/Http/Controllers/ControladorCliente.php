<?php

namespace App\Http\Controllers;


class ControladorCliente extends Controller{

      public function nuevo(){
            $titulo = "Nuevo CLiente";
            return view("sistema.cliente-nuevo",compact("titulo"));
      }
}