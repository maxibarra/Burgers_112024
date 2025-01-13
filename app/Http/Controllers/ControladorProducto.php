<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Entidades\Producto;
use App\Entidades\Tipo_Producto;
require app_path() . '/start/constants.php';
class ControladorProducto extends Controller{

      public function nuevo(){
            $titulo = "Nuevo Producto";
            $producto = new Producto();
            $producto->obtenerTodos();
            $categoria = new Tipo_Producto();
            $aCategorias = $categoria->obtenerTodos();
            return view("sistema.producto-nuevo",compact("titulo","producto", "aCategorias"));
      }

      public function index(){
            $titulo = "Listado de Productos";
            return view("sistema.producto-listar", compact("titulo"));
      }

      public function guardar(Request $request)
      {
            try {
                  //Define la entidad servicio
                  $titulo = "Modificar producto";
                  $entidad = new Producto();
                  $categoria = new Tipo_Producto();
                  $aCategorias = $categoria->obtenerTodos();
                  $entidad->cargarDesdeRequest($request);

                  //validaciones
                  if ($entidad->nombre == "" || $entidad->descripcion == "" || $entidad->precio == "" || $entidad->cantidad == "" || $entidad->fk_idtipoproducto== "" ) {
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

                        $_POST["id"] = $entidad->idproducto;
                        return view('sistema.producto-listar', compact('titulo', 'msg'));
                  }
            } catch (Exception $e) {
                  $msg["ESTADO"] = MSG_ERROR;
                  $msg["MSG"] = ERRORINSERT;
            }

            $id = $entidad->idproducto;
            $producto = new Producto();
            $producto->obtenerPorId($id);
            $categoria = new Tipo_Producto();
            $aCategorias = $categoria->obtenerTodos();
            

            return view('sistema.producto-nuevo', compact('msg',"producto",'titulo','aCategorias')) . '?id=' . $producto->idproducto;
      }

      public function cargarGrilla(Request $request)
    {
        $request = $_REQUEST;

        $producto = new Producto();
        $aProductos = $producto->obtenerFiltrado();

        $data = array();
        $cont = 0;
        $inicio = $request['start'];
        $registros_por_pagina = $request['length'];
        
        for ($i = $inicio; $i < count($aProductos) && $cont < $registros_por_pagina; $i++) {
            $row = array();
            $row[] = '<a href="/admin/producto/' . $aProductos[$i]->idproducto. '">' . $aProductos[$i]->nombre . '</a>';
            $row[] = "$" . " " . $aProductos[$i]->precio;
            $row[] = $aProductos[$i]->cantidad;
            $row[] = $aProductos[$i]->descripcion;
            $row[] = $aProductos[$i]->tipoProducto;
            $row[] = $aProductos[$i]->imagen;
           
            $cont++;
            $data[] = $row;
        }

        $json_data = array(
            "draw" => intval($request['draw']),
            "recordsTotal" => count($aProductos), //cantidad total de registros sin paginar
            "recordsFiltered" => count($aProductos), //cantidad total de registros en la paginacion
            "data" => $data,
        );
        return json_encode($json_data);
    }

    public function editar($idProducto){
      $titulo = "Edición de Producto";
      $producto = new Producto();
      $producto->obtenerPorId($idProducto);
      $categoria = new Tipo_Producto();
      $aCategorias = $categoria->obtenerTodos();
      return view("sistema.producto-nuevo", compact("titulo","producto","aCategorias"));
    }

}