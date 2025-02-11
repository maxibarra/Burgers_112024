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
        'total'
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
    }

    public function obtenerTodos()
    {
        $sql = "SELECT
                  idpedido,
                  fk_idcliente,
                  fk_idsucursal,
                  fk_idestadopedido,
                  fecha,
                  total
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
                  total
                FROM pedidos WHERE idpedido = $idpedido";
        $lstRetorno = DB::select($sql);

        if (count($lstRetorno) > 0) {
            $this->idpedido = $lstRetorno[0]->idpedido;
            $this->fk_idcliente = $lstRetorno[0]->fk_idcliente;
            $this->fk_idsucursal = $lstRetorno[0]->fk_idsucursal;
            $this->fk_idestadopedido = $lstRetorno[0]->fk_idestadopedido;
            $this->fecha = $lstRetorno[0]->fecha;
            $this->total = $lstRetorno[0]->total;
            return $this;
        }
        return null;
    }

    public function guardar()
    {
        $sql = "UPDATE pedidos SET
          fk_idcliente=$this->fk_idcliente,
          fk_idsucursal=$this->fk_idsucursal,
          fk_idestadopedido=$this->fk_idestadopedido,
          fecha='$this->fecha',
          total=$this->total
          
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
                  total
            ) VALUES (?, ?, ?, ?, ?);";
        $result = DB::insert($sql, [
            $this->fk_idcliente,
            $this->fk_idsucursal,
            $this->fk_idestadopedido,
            $this->fecha,
            $this->total
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
        );
        $sql = "SELECT DISTINCT
                    a.idpedido,
                    a.fk_idcliente,
                    a.fk_idsucursal, 
                    a.fk_idestadopedido,
                    a.fecha,
                    a.total,
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
                  total
                FROM pedidos WHERE fk_idsucursal = $idSucursal";
        $lstRetorno = DB::select($sql);
        return (count($lstRetorno) > 0);
    }
}
