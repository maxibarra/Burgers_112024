<?php

namespace App\Entidades;

use DB;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
      protected $table = 'productos';
      public $timestamps = false;

      protected $fillable = [
            'idproducto',
            'nombre',
            'descripcion',
            'precio',
            'cantidad',
            'imagen',
            'fk_idtipoproducto',
      ];

      protected $hidden = [];

      public function obtenerTodos()
      {
            $sql = "SELECT
                  idproducto,
                  nombre,
                  descripcion,
                  precio,
                  cantidad,
                  imagen,
                  fk_idtipoproducto
                FROM productos ORDER BY nombre ASC";
            $lstRetorno = DB::select($sql);
            return $lstRetorno;
      }

      public function obtenerPorId($idproducto)
      {
            $sql = "SELECT
                  idproducto,
                  nombre,
                  descripcion,
                  precio,
                  cantidad,
                  imagen,
                  fk_idtipoproducto
                FROM productos WHERE idproducto = $idproducto";
            $lstRetorno = DB::select($sql);

            if (count($lstRetorno) > 0) {
                  $this->idproducto = $lstRetorno[0]->idproducto;
                  $this->nombre = $lstRetorno[0]->nombre;
                  $this->descripcion = $lstRetorno[0]->descripcion;
                  $this->precio = $lstRetorno[0]->precio;
                  $this->cantidad = $lstRetorno[0]->cantidad;
                  $this->imagen = $lstRetorno[0]->imagen;
                  $this->fk_idtipoproducto = $lstRetorno[0]->fk_idtipoproducto;
                  return $this;
            }
            return null;
      }

      public function guardar()
      {
            $sql = "UPDATE productos SET
          nombre='$this->nombre',
          descripcion='$this->descripcion',
          precio=$this->precio,
          cantidad=$this->cantidad,
          imagen='$this->imagen',
          fk_idtipoproducto=$this->fk_idtipoproducto
          WHERE idproducto=?";
            $affected = DB::update($sql, [$this->idproducto]);
      }

      public function eliminar()
    {
        $sql = "DELETE FROM productos WHERE
            idproducto=?";
        $affected = DB::delete($sql, [$this->idproducto]);
    }

    public function insertar()
    {
        $sql = "INSERT INTO productos (
                  nombre,
                  descripcion,
                  precio,
                  cantidad,
                  imagen,
                  fk_idtipoproducto
            ) VALUES (?, ?, ?, ?, ?, ?);";
        $result = DB::insert($sql, [
            $this->nombre,
            $this->descripcion,
            $this->precio,
            $this->cantidad,
            $this->imagen,
            $this->fk_idtipoproducto
        ]);
        return $this->idproducto = DB::getPdo()->lastInsertId();
    }
}
