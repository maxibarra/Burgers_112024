<?php

namespace App\Entidades;

use DB;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedidos';
    public $timestamps = false;

    protected $fillable = [
        'idpedido',
        'fk_idcliente',
        'fk_idsucursal',
        'fk_idestadopedido',
        'fecha',
        'total',
        'pago'
    ];

    protected $hidden = [];

    public function cargarDesdeRequest($request)
    {
        $this->idpedido = $request->input('id') != "0" ? $request->input('id') : $this->idpedido;
        $this->fk_idcliente = $request->input('lstCliente');
        $this->fk_idsucursal = $request->input('lstSucursal');
        $this->fk_idestadopedido = $request->input('lstEstadoPedido');
        $this->fecha = $request->input('txtFecha');
        $this->total = $request->input('txtTotal');
        $this->pago= $request->input('lstPago');
    }

    public function obtenerTodos()
    {
        $sql = "SELECT
                  idpedido,
                  fk_idcliente,
                  fk_idsucursal,
                  fk_idestadopedido,
                  fecha,
                  total,
                  pago
                FROM pedidos ORDER BY fecha ASC";
        $lstRetorno = DB::select($sql);
        return $lstRetorno;
    }

    public function obtenerPorId($idpedido)
    {
        $sql = "SELECT
                  idpedido,
                  fk_idcliente,
                  fk_idsucursal,
                  fk_idestadopedido,
                  fecha,
                  total,
                  pago
                FROM pedidos WHERE idpedido = $idpedido";
        $lstRetorno = DB::select($sql);

        if (count($lstRetorno) > 0) {
            $this->idpedido = $lstRetorno[0]->idpedido;
            $this->fk_idcliente = $lstRetorno[0]->fk_idcliente;
            $this->fk_idsucursal = $lstRetorno[0]->fk_idsucursal;
            $this->fk_idestadopedido = $lstRetorno[0]->fk_idestadopedido;
            $this->fecha = $lstRetorno[0]->fecha;
            $this->total = $lstRetorno[0]->total;
            $this->pago = $lstRetorno[0]->pago;
            return $this;
        }
        return null;
    }

    public function guardar()
    {
        $sql = "UPDATE pedidos SET
          fk_idcliente=$this->fk_idcliente,
          fk_idsucursal='$this->fk_idsucursal',
          fk_idestadopedido='$this->fk_idestadopedido',
          fecha='$this->fecha',
          total=$this->total,
          pago='$this->pago'      
          WHERE idpedido=?";
        $affected = DB::update($sql, [$this->idpedido]);
    }

    public function eliminar()
    {
        $sql = "DELETE FROM pedidos WHERE
            idpedido=?";
        $affected = DB::delete($sql, [$this->idpedido]);
    }

    public function insertar()
    {
        $sql = "INSERT INTO pedidos (
                  fk_idcliente,
                  fk_idsucursal,
                  fk_idestadopedido,
                  fecha,
                  total,
                  pago
            ) VALUES (?, ?, ?, ?, ?,?);";
        $result = DB::insert($sql, [
            $this->fk_idcliente,
            $this->fk_idsucursal,
            $this->fk_idestadopedido,
            $this->fecha,
            $this->total,
            $this->pago
        ]);
        return $this->idpedido = DB::getPdo()->lastInsertId();
    }

    public function obtenerFiltrado()
    {
        $request = $_REQUEST;
        $columns = array(
            0 => 'fk_idcliente',
            1 => 'fk_idsucursal',
            2 => 'fk_idestadopedido',
            3 => 'fecha',
            4 => 'total',
            5 => 'pago',
        );
        $sql = "SELECT DISTINCT
                    a.idpedido,
                    a.fk_idcliente,
                    a.fk_idsucursal, 
                    a.fk_idestadopedido,
                    a.fecha,
                    a.total,
                    a.pago,
                    b.nombre AS cliente,
                    c.nombre AS sucursal,
                    d.nombre AS estadopedido
                FROM pedidos a
                INNER JOIN clientes b ON a.fk_idcliente = b.idcliente
                INNER JOIN sucursales c ON a.fk_idsucursal = c.idsucursal
                INNER JOIN estado_pedidos d ON a.fk_idestadopedido = d.idestadopedido
                WHERE 1=1 ";

        //Realiza el filtrado
        if (!empty($request['search']['value'])) {
            $sql .= " AND ( b.nombre LIKE '%" . $request['search']['value'] . "%' ";
            $sql .= " OR c.nombre LIKE '%" . $request['search']['value'] . "%' ";
            $sql .= " OR d.nombre LIKE '%" . $request['search']['value'] . "%' )";
            $sql .= " OR fecha LIKE '%" . $request['search']['value'] . "%' )";
            $sql .= " OR total LIKE '%" . $request['search']['value'] . "%' )";
            $sql .= " OR pago LIKE '%" . $request['search']['value'] . "%' )";
        }
        $sql .= " ORDER BY " . $columns[$request['order'][0]['column']] . "   " . $request['order'][0]['dir'];

        $lstRetorno = DB::select($sql);

        return $lstRetorno;
    }


    public function existePedidosPorCliente($idCliente)
    {
        $sql = "SELECT
                  idpedido,
                  fk_idcliente,
                  fk_idsucursal,
                  fk_idestadopedido,
                  fecha,
                  total
                FROM pedidos WHERE fk_idcliente = $idCliente";
        $lstRetorno = DB::select($sql);
        return (count($lstRetorno) > 0);
    }

    public function existePedidosPorProducto($idProducto)
    {
        $sql = "SELECT
                  idpedidoproducto,
                  fk_idproducto,
                  fk_idpedido
                FROM pedidos_productos WHERE fk_idproducto = $idProducto";
        $lstRetorno = DB::select($sql);
        return (count($lstRetorno) > 0);
    }

    public function existePedidosParaSucursal($idSucursal)
    {
        $sql = "SELECT
                  idpedido,
                  fk_idcliente,
                  fk_idsucursal,
                  fk_idestadopedido,
                  fecha,
                  total,
                  pago
                FROM pedidos WHERE fk_idsucursal = $idSucursal";
        $lstRetorno = DB::select($sql);
        return (count($lstRetorno) > 0);
    }

    public function obtenerPedidosPorCliente($idCliente)
    {
        $sql = "SELECT
                  A.idpedido,
                  A.fk_idsucursal,
                  A.fk_idestadopedido,
                  A.fecha,
                  A.total,
                  A.pago,
                  c.nombre AS sucursal,
                  d.nombre AS estadopedido
                FROM pedidos A
                INNER JOIN sucursales c ON A.fk_idsucursal = c.idsucursal
                INNER JOIN estado_pedidos d ON A.fk_idestadopedido = d.idestadopedido
                WHERE A.fk_idcliente = '$idCliente'
                AND d.idestadopedido != 3"; 
        $lstRetorno = DB::select($sql);
        return $lstRetorno;
    }
}
