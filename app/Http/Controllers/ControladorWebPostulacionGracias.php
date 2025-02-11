<?php

namespace App\Http\Controllers;

use App\Entidades\Sucursal;


class ControladorWebPostulacionGracias extends Controller
{
    public function index()
    {
        $sucursal = new Sucursal();
        $aSucursales = $sucursal->obtenerTodos();
            return view('web.postulacion-gracias',compact("aSucursales"));
    }
}