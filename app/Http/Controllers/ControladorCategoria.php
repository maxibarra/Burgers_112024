<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entidades\Tipo_Producto;
require app_path() . '/start/constants.php';

class ControladorCategoria extends Controller{

      public function nuevo(){
            $titulo = "Nueva Categoría";
            return view("sistema.categoria-nuevo",compact("titulo"));
      }

      public function index(){
            $titulo = "Listado de Categorias";
            return view("sistema.categoria-listar",compact("titulo"));
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

            return view('sistema.categoria-nuevo', compact('msg', 'categoria', 'fecha')) . '?id=' . $categoria->idtipoproducto;
      }

      public function cargarGrilla(Request $request)
      {
          $request = $_REQUEST;
  
          $entidad = new Tipo_Producto();
          $aCategorias = $entidad->obtenerFiltrado();
  
          $data = array();
          $cont = 0;
          $inicio = $request['start'];
          $registros_por_pagina = $request['length'];
          
          for ($i = $inicio; $i < count($aCategorias) && $cont < $registros_por_pagina; $i++) {
              $row = array();
              $row[] = '<a href="/admin/categorias/' . $aCategorias[$i]->idtipoproducto. '">' . $aCategorias[$i]->idtipoproducto . '</a>';
              $row[] = $aCategorias[$i]->nombre;
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
}