<?php

namespace App\Http\Controllers;

use App\Entidades\Sistema\Patente;
use App\Entidades\Sistema\Usuario;
use Session;

class ControladorWebNosotros extends Controller
{
    public function index()
    {
            return view('web.nosotros');
    }
}