<?php

namespace App\Http\Controllers;


class ControladorProveedor extends Controller{

      public function nuevo(){
            $titulo = "Nuevo Proveedor";
            return view("sistema.proveedor-nuevo",compact("titulo"));
      }
}