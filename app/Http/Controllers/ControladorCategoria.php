<?php

namespace App\Http\Controllers;

use App\Entidades\Producto;
use Illuminate\Http\Request;
use App\Entidades\Tipo_Producto;
use App\Entidades\Sistema\Usuario;
use App\Entidades\Sistema\Patente;

require app_path() . '/start/constants.php';

class ControladorCategoria extends Controller{

      public function nuevo(){
            $titulo = "Nueva Categoría";
            if (Usuario::autenticado() == true) {
                  if (!Patente::autorizarOperacion("CATEGORIAALTA")) {
                        $codigo = "CATEGORIAALTA";
                        $mensaje = "No tiene permisos para la operación.";
                        return view('sistema.pagina-error', compact('titulo', 'codigo', 'mensaje'));
                  } else {
                        $categoria = new Tipo_Producto();
            return view("sistema.categoria-nuevo",compact("titulo","categoria"));
                  }
            } else {
                  return redirect('admin/login');
            }
            
      }

      public function index(){
            $titulo = "Listado de Categorias";
            if (Usuario::autenticado() == true) {
                  if (!Patente::autorizarOperacion("CATEGORIACONSULTA")) {
                        $codigo = "CATEGORIACONSULTA";
                        $mensaje = "No tiene permisos para la operación.";
                        return view('sistema.pagina-error', compact('titulo', 'codigo', 'mensaje'));
                  } else {
                        return view("sistema.categoria-listar",compact("titulo"));
                  }
            } else {
                  return redirect('admin/login');
            }
           
      }


      public function guardar(Request $request)
      {
            try {
                  //Define la entidad servicio
                  $titulo = "Modificar Categoria";
                  $entidad = new Tipo_Producto();
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

                        $_POST["id"] = $entidad->idtipoproducto;
                        return view('sistema.categoria-listar', compact('titulo', 'msg'));
                  }
            } catch (Exception $e) {
                  $msg["ESTADO"] = MSG_ERROR;
                  $msg["MSG"] = ERRORINSERT;
            }

            $id = $entidad->idtipoproducto;
            $categoria = new Tipo_Producto();
            $categoria->obtenerPorId($id);

            return view('sistema.categoria-nuevo', compact('msg', 'categoria')) . '?id=' . $categoria->idtipoproducto;
      }

      public function cargarGrilla(Request $request)
      {
          $request = $_REQUEST;
  
          $categoria = new Tipo_Producto();
          $aCategorias = $categoria->obtenerFiltrado();
  
          $data = array();
          $cont = 0;
          $inicio = $request['start'];
          $registros_por_pagina = $request['length'];
          
          for ($i = $inicio; $i < count($aCategorias) && $cont < $registros_por_pagina; $i++) {
              $row = array();
              $row[] = '<a href="/admin/categoria/' . $aCategorias[$i]->idtipoproducto. '">' . $aCategorias[$i]->nombre . '</a>';
              $cont++;
              $data[] = $row;
          }
  
          $json_data = array(
              "draw" => intval($request['draw']),
              "recordsTotal" => count($aCategorias), //cantidad total de registros sin paginar
              "recordsFiltered" => count($aCategorias), //cantidad total de registros en la paginacion
              "data" => $data,
          );
          return json_encode($json_data);
      }

      public function editar($idCategoria){
            $titulo = "Edición de categoria";
            if (Usuario::autenticado() == true) {
                  if (!Patente::autorizarOperacion("CATEGORIAEDITAR")) {
                        $codigo = "CATEGORIAEDITAR";
                        $mensaje = "No tiene permisos para la operación.";
                        return view('sistema.pagina-error', compact('titulo', 'codigo', 'mensaje'));
                  } else {
                        $categoria = new Tipo_Producto();
                        $categoria->obtenerPorId($idCategoria);  
                        return view("sistema.categoria-nuevo", compact("titulo","categoria"));
                  }
            } else {
                  return redirect('admin/login');
            }
            
      }

      public function eliminar(Request $request)
      {

            if (Usuario::autenticado() == true) {
                  if (!Patente::autorizarOperacion("CATEGORIAELIMINAR")) {
                        $resultado["err"] = EXIT_FAILURE;
                        $resultado["mensaje"] = "No tiene permisos para la operación";
                  } else {
                        $idCategoria = $request->input("id");
                        $producto = new Producto();
                        //Si el cliente tiene un pedido asociado no se tiene que poder eliminar
                        if($producto->existeProductosConCate($idCategoria)){
                              $resultado["err"] = EXIT_FAILURE;
                              $resultado["mensaje"] = "No se puede eliminar una categoria con productos asociados";
                        }else{
                              //sino si
                              $categoria = new Tipo_Producto();
                              $categoria->idtipoproducto = $idCategoria;
                              $categoria->eliminar();
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