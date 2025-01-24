<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Entidades\Pedido;
use App\Entidades\Cliente;
use App\Entidades\Sucursal;
use App\Entidades\Estado_pedido;
use App\Entidades\Sistema\Usuario;
use App\Entidades\Sistema\Patente;

require app_path() . '/start/constants.php';

class ControladorPedido extends Controller{

      public function nuevo(){
            $titulo = "Nuevo Pedido";
            if (Usuario::autenticado() == true) {
                  if (!Patente::autorizarOperacion("PEDIDOALTA")) {
                        $codigo = "PEDIDOALTA";
                        $mensaje = "No tiene permisos para la operación.";
                        return view('sistema.pagina-error', compact('titulo', 'codigo', 'mensaje'));
                  } else {
                        $pedido = new Pedido();
                        $cliente = new Cliente();
                        $aClientes = $cliente->obtenerTodos();
                        $sucursal = new Sucursal();
                        $aSucursales = $sucursal->obtenerTodos();
                        $estadoPedido = new Estado_pedido();
                        $aEstadoPedidos = $estadoPedido->obtenerTodos();
                        return view("sistema.pedido-nuevo",compact("titulo","pedido","aClientes","aEstadoPedidos","aSucursales"));
                  }
            } else {
                  return redirect('admin/login');
            }
      }

      public function index(){
            $titulo = "Listado de Pedidos";
            if (Usuario::autenticado() == true) {
                  if (!Patente::autorizarOperacion("PEDIDOCONSULTA")) {
                        $codigo = "PEDIDOCONSULTA";
                        $mensaje = "No tiene permisos para la operación.";
                        return view('sistema.pagina-error', compact('titulo', 'codigo', 'mensaje'));
                  } else {
                        return view("sistema.pedido-listar", compact("titulo"));
                  }
            } else {
                  return redirect('admin/login');
            }
            
      }

      public function guardar(Request $request)
      {
            try {
                  //Define la entidad servicio
                  $titulo = "Modificar Pedido";
                  $entidad = new Pedido();
                  $entidad->cargarDesdeRequest($request);

                  //validaciones
                  if ($entidad->fk_idcliente=="" || $entidad->fk_idsucursal== "" ||$entidad->fk_idestadopedido =="" || $entidad->fecha == "" || $entidad->total == "") {
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
            $cliente = new Cliente();
            $aClientes = $cliente->obtenerTodos();
            $sucursal = new Sucursal();
            $aSucursales = $sucursal->obtenerTodos();
            $estadoPedido = new Estado_pedido();
            $aEstadoPedidos = $estadoPedido->obtenerTodos();

            return view('sistema.pedido-nuevo', compact('msg', 'pedido', 'fecha',"aEstadoPedidos","aClientes","aSucursales")) . '?id=' . $pedido->idpedido;
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
              $row[] = date('d/m/Y',strtotime($aPedidos[$i]->fecha));
              $row[] = '<a href="/admin/pedido/' . $aPedidos[$i]->idpedido. '">' . $aPedidos[$i]->cliente . '</a>';
              $row[] = $aPedidos[$i]->sucursal;
              $row[] = $aPedidos[$i]->estadopedido;
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

      public function editar($idPedido){
            $titulo = "Edición de Pedido";
            if (Usuario::autenticado() == true) {
                  if (!Patente::autorizarOperacion("PEDIDOEDITAR")) {
                        $codigo = "PEDIDOEDITAR";
                        $mensaje = "No tiene permisos para la operación.";
                        return view('sistema.pagina-error', compact('titulo', 'codigo', 'mensaje'));
                  } else {
                        $pedido = new Pedido();
                        $pedido->obtenerPorId($idPedido);
                        $cliente = new Cliente();
                        $aClientes = $cliente->obtenerTodos();
                        $sucursal = new Sucursal();
                        $aSucursales = $sucursal->obtenerTodos();
                        $estadoPedido = new Estado_pedido();
                        $aEstadoPedidos = $estadoPedido->obtenerTodos();
                        return view("sistema.pedido-nuevo", compact("titulo","pedido","aClientes","aSucursales","aEstadoPedidos"));
                  }
            } else {
                  return redirect('admin/login');
            }
      }

      public function eliminar(Request $request)
      {
            if (Usuario::autenticado() == true) {
                  if (!Patente::autorizarOperacion("PEDIDOBAJA")) {
                        $resultado["err"] = EXIT_FAILURE;
                        $resultado["mensaje"] = "No tiene permisos para la operación";
                  } else {
                        $idPedido = $request->input("id");
                        $pedido = new Pedido();
                        $pedido->idpedido = $idPedido;
                        $pedido->eliminar();
                        $resultado["err"] = EXIT_SUCCESS;
                        $resultado["mensaje"] = "Registro eliminado exitosamente.";
                        
                  }
            } else {
                  $resultado["err"] = EXIT_FAILURE;
                  $resultado["mensaje"] = "Usuario no autenticado";
            }
            return json_encode($resultado);

      }
}