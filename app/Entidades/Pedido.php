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

}

?>
