<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Entidades\Rubro;
use App\Entidades\Proveedor;
use App\Entidades\Sistema\Usuario;
use App\Entidades\Sistema\Patente;
require app_path() . '/start/constants.php';
class ControladorRubro extends Controller{

      public function nuevo(){
            $titulo = "Nuevo Rubro";
            if (Usuario::autenticado() == true) {
                  if (!Patente::autorizarOperacion("RUBROALTA")) {
                        $codigo = "RUBROALTA";
                        $mensaje = "No tiene permisos para la operación.";
                        return view('sistema.pagina-error', compact('titulo', 'codigo', 'mensaje'));
                  } else {
                        $rubro = new Rubro();
                        return view("sistema.rubro-nuevo",compact("titulo","rubro"));
                  }
            } else {
                  return redirect('admin/login');
            }
           
      }
      
      public function index(){
            $titulo = "Listado de Rubros";
            if (Usuario::autenticado() == true) {
                  if (!Patente::autorizarOperacion("RUBROCONSULTA")) {
                        $codigo = "RUBROCONSULTA";
                        $mensaje = "No tiene permisos para la operación.";
                        return view('sistema.pagina-error', compact('titulo', 'codigo', 'mensaje'));
                  } else {
                        return view("sistema.rubro-listar",compact("titulo"));
                  }
            } else {
                  return redirect('admin/login');
            }
            
      }

      public function guardar(Request $request)
      {
            try {
                  //Define la entidad servicio
                  $titulo = "Modificar Rubro";
                  $entidad = new Rubro();
                  $entidad->cargarDesdeRequest($request);

                  //validaciones
                  if ($entidad->nombre == "") {
                        $msg["ESTADO"] = MSG_ERROR;
                        $msg["MSG"] = "Complete todos los datos";
                  } else {
                        if ($_POST["id"] > 0) {
                              //Es actualizacion
                              $entidad->guardar();

                              $msg["ESTADO"] = MSG_SUCCESS;
                              $msg["MSG"] = OKINSERT;
                        } else {
                              //Es nuevo
                              $entidad->insertar();

                              $msg["ESTADO"] = MSG_SUCCESS;
                              $msg["MSG"] = OKINSERT;
                        }

                        $_POST["id"] = $entidad->idrubro;
                        return view('sistema.rubro-listar', compact('titulo', 'msg'));
                  }
            } catch (Exception $e) {
                  $msg["ESTADO"] = MSG_ERROR;
                  $msg["MSG"] = ERRORINSERT;
            }

            $id = $entidad->idrubro;
            $rubro = new Rubro();
            $rubro->obtenerPorId($id);

            return view('sistema.rubro-nuevo', compact('msg', 'rubro', 'titulo')) . '?id=' . $rubro->idrubro;
      }

      public function cargarGrilla(Request $request)
      {
          $request = $_REQUEST;
  
          $entidad = new Rubro();
          $aRubros = $entidad->obtenerFiltrado();
  
          $data = array();
          $cont = 0;
          $inicio = $request['start'];
          $registros_por_pagina = $request['length'];
          
          for ($i = $inicio; $i < count($aRubros) && $cont < $registros_por_pagina; $i++) {
              $row = array();
              $row[] = '<a href="/admin/rubro/' . $aRubros[$i]->idrubro. '">' . $aRubros[$i]->nombre. '</a>';
              $cont++;
              $data[] = $row;
          }
  
          $json_data = array(
              "draw" => intval($request['draw']),
              "recordsTotal" => count($aRubros), //cantidad total de registros sin paginar
              "recordsFiltered" => count($aRubros), //cantidad total de registros en la paginacion
              "data" => $data,
          );
          return json_encode($json_data);
      }

      public function editar($idRubro){
            $titulo = "Edición de Rubro";
            if (Usuario::autenticado() == true) {
                  if (!Patente::autorizarOperacion("RUBROEDITAR")) {
                        $codigo = "RUBROEDITAR";
                        $mensaje = "No tiene permisos para la operación.";
                        return view('sistema.pagina-error', compact('titulo', 'codigo', 'mensaje'));
                  } else {
                        $rubro = new Rubro();
                        $rubro->obtenerPorId($idRubro);
                        return view("sistema.rubro-nuevo", compact("titulo","rubro"));
                  }
            } else {
                  return redirect('admin/login');
            }
            
      }

      public function eliminar(Request $request)
      {
            if (Usuario::autenticado() == true) {
                  if (!Patente::autorizarOperacion("RUBROELIMINAR")) {
                        $resultado["err"] = EXIT_FAILURE;
                        $resultado["mensaje"] = "No tiene permisos para la operación";
                  } else {
                        $idRubro = $request->input("id");
                        $proveedor = new Proveedor();
                        //Si el rubro tiene un proveedor asociado no se tiene que poder eliminar
                        if($proveedor->existeProveedoresConRubro($idRubro)){
                              $resultado["err"] = EXIT_FAILURE;
                              $resultado["mensaje"] = "No se puede eliminar un rubro con proveedores asociados";
                        }else{
                              //sino si
                              $rubro = new Rubro();
                              $rubro->idrubro = $idRubro;
                              $rubro->eliminar();
                              $resultado["err"] = EXIT_SUCCESS;
                              $resultado["mensaje"] = "Registro eliminado exitosamente.";
                        }
                  }
            } else {
                  $resultado["err"] = EXIT_FAILURE;
                  $resultado["mensaje"] = "Usuario no autenticado";
            }
            return json_encode($resultado);
      }
}