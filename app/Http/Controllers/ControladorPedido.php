<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Entidades\Pedido;
use App\Entidades\Cliente;
use App\Entidades\Sucursal;
use App\Entidades\Estado_pedido;

require app_path() . '/start/constants.php';

class ControladorPedido extends Controller{

      public function nuevo(){
            $titulo = "Nuevo Pedido";
            $cliente = new Cliente();
            $aClientes = $cliente->obtenerTodos();
            $sucursal = new Sucursal();
            $aSucursales = $sucursal->obtenerTodos();
            $estadoPedido = new Estado_pedido();
            $aEstadoPedidos = $estadoPedido->obtenerTodos();
            return view("sistema.pedido-nuevo",compact("titulo","aEstadoPedidos","aClientes","aSucursales"));
      }

      public function index(){
            $titulo = "Listado de Pedidos";
            return view("sistema.pedido-listar", compact("titulo"));
      }

      public function guardar(Request $request)
      {
            try {
                  //Define la entidad servicio
                  $titulo = "Modificar Pedido";
                  $entidad = new Pedido();
                  $entidad->cargarDesdeRequest($request);

                  //validaciones
                  if ($entidad->fecha == "" || $entidad->total == "") {
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

                        $_POST["id"] = $entidad->idpedido;
                        return view('sistema.pedido-listar', compact('titulo', 'msg'));
                  }
            } catch (Exception $e) {
                  $msg["ESTADO"] = MSG_ERROR;
                  $msg["MSG"] = ERRORINSERT;
            }

            $id = $entidad->idpedido;
            $pedido = new Pedido();
            $pedido->obtenerPorId($id);

            return view('sistema.pedido-nuevo', compact('msg', 'pedido', 'fecha')) . '?id=' . $pedido->idpedido;
      }

      public function cargarGrilla(Request $request)
      {
          $request = $_REQUEST;
  
          $entidad = new Pedido();
          $aPedidos = $entidad->obtenerFiltrado();
  
          $data = array();
          $cont = 0;
          $inicio = $request['start'];
          $registros_por_pagina = $request['length'];
          
          for ($i = $inicio; $i < count($aPedidos) && $cont < $registros_por_pagina; $i++) {
              $row = array();
              $row[] = '<a href="/admin/pedidos/' . $aPedidos[$i]->idpedido. '">' . $aPedidos[$i]->cliente_nombre . '</a>';
              $row[] = $aPedidos[$i]->nombre_sucursal;
              $row[] = $aPedidos[$i]->estadopedido_nombre;
              $row[] = date('d/m/Y', strtotime($aPedidos[$i]->fecha));
              $row[] = '$' . " " . number_format($aPedidos[$i]->total,2,',','.');
              $cont++;
              $data[] = $row;
          }
  
          $json_data = array(
              "draw" => intval($request['draw']),
              "recordsTotal" => count($aPedidos), //cantidad total de registros sin paginar
              "recordsFiltered" => count($aPedidos), //cantidad total de registros en la paginacion
              "data" => $data,
          );
          return json_encode($json_data);
      }
}