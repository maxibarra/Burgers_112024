<?php

namespace App\Http\Controllers;



class ControladorWebCambiarClave extends Controller
{
    public function index()
    {
            return view('web.cambiar-clave');
    }
}