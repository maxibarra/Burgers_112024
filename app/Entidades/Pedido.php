<?php

namespace App\Entidades;

use DB;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedidos';
    public $timestamps = false;

    protected $fillable = [
        'idpedido', 'fk_idcliente', 'fk_idsucursal', 'fk_idestadopedido', 'fecha', 'total'
    ];

    protected $hidden = [

    ];

    public function cargarDesdeRequest($request) {
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
                FROM pedidos ORDER BY fecha DESC";
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

    public function guardar() {
      $sql = "UPDATE pedidos SET
          fk_idcliente=$this->fk_idcliente,
          fk_idsucursal=$this->fk_idsucursal,
          fk_idestadopedido=$this->fk_idestadopedido,
          fecha=$this->fecha,
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
            0 => 'a.nombre',
            1 => 'b.nombre',
            2 => 'c.nombre',
            3 => 'fecha',
            4 => 'total',
        );
        $sql = "SELECT DISTINCT
                    p.idpedido,
                    a.nombre AS cliente_nombre,
                    b.nombre AS nombre_sucursal,
                    c.nombre AS estadopedido_nombre,
                    p.fecha,
                    p.total
                FROM pedidos p
                INNER JOIN clientes a ON p.fk_idcliente = a.idcliente
                INNER JOIN sucursales b ON p.fk_idsucursal = b.idsucursal
                INNER JOIN estado_pedidos c ON p.fk_idestadopedido = c.idestadopedido
                WHERE 1=1 ";

        //Realiza el filtrado
        if (!empty($request['search']['value'])) {
            $sql .= " AND ( a.nombre LIKE '%" . $request['search']['value'] . "%' ";
            $sql .= " OR b.nombre LIKE '%" . $request['search']['value'] . "%' ";
            $sql .= " OR c.nombre LIKE '%" . $request['search']['value'] . "%' )";
            $sql .= " OR fecha LIKE '%" . $request['search']['value'] . "%' )";
            $sql .= " OR total LIKE '%" . $request['search']['value'] . "%' )";
        }
        $sql .= " ORDER BY " . $columns[$request['order'][0]['column']] . "   " . $request['order'][0]['dir'];

        $lstRetorno = DB::select($sql);

        return $lstRetorno;
    }
}

?>
