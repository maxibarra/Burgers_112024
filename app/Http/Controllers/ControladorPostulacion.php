<?php

namespace App\Http\Controllers;


class ControladorPostulacion extends Controller{

      public function nuevo(){
            $titulo = "Nueva Postulación";
            return view("sistema.postulacion-nuevo",compact("titulo"));
      }
}