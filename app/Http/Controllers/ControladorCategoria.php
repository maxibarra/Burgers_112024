<?php

namespace App\Http\Controllers;


class ControladorCategoria extends Controller{

      public function nuevo(){
            $titulo = "Nueva Categoría";
            return view("sistema.categoria-nuevo",compact("titulo"));
      }
}