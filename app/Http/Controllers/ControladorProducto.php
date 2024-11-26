<?php

namespace App\Http\Controllers;


class ControladorProducto extends Controller{

      public function nuevo(){
            $titulo = "Nuevo Producto";
            return view("sistema.producto-nuevo",compact("titulo"));
      }
}