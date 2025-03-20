<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entidades\Cliente;
use App\Entidades\Sucursal;

require app_path() . '/start/constants.php';
class ControladorWebRegistrarse extends Controller
{
    public function index()
    {
            $sucursaal = new Sucursal();
            $aSucursales = $sucursaal->obtenerTodos();
            return view('web.registrarse', compact('aSucursales'));
    }

    public function registrar(Request $request)
    {
        $titulo = "Nuevo Registro";
        $cliente = new Cliente();
        $cliente->nombre = $request->input('txtNombre');
        $cliente->apellido = $request->input('txtApellido');
        $cliente->correo = $request->input('txtCorreo');
        $cliente->dni = $request->input('txtDni');
        $cliente->celular = $request->input('txtCelular');
        $cliente->direccion = $request->input('txtDireccion');
        $cliente->clave = password_hash($request->input('txtClave'), PASSWORD_DEFAULT);

        $sucursaal = new Sucursal();
        $aSucursales = $sucursaal->obtenerTodos();

        if ($cliente->nombre == "" || $cliente->apellido == "" || $cliente->correo == "" || $cliente->dni == ""|| $cliente->celular == ""|| $cliente->direccion == ""|| $cliente->clave == "") {
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = "Complete todos los datos";
            return view('web.registrarse', compact('titulo', 'msg', 'aSucursales'));
        }else {
            $cliente->insertar();
            $msg["ESTADO"] = MSG_SUCCESS;
            $msg["MSG"] = "Registro exitoso. Puede loguearse";
            return view('web.login', compact('titulo', 'msg', 'aSucursales'));
            }
         
    }
}