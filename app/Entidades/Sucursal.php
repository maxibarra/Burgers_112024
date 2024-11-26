<?php

namespace App\Entidades;

use DB;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
      protected $table = 'sucursales';
      public $timestamps = false;

      protected $fillable = [
            'idsucursal',
            'nombre',
            'direccion',
            'telefono',
            'mapa',
            'horario'
      ];

      protected $hidden = [];

      public function obtenerTodos()
      {
            $sql = "SELECT
                  idsucursal,
                  nombre,
                  direccion,
                  telefono,
                  mapa,
                  horario
                FROM sucursales ORDER BY nombre";
            $lstRetorno = DB::select($sql);
            return $lstRetorno;
      }

      public function obtenerPorId($idsucursal)
      {
            $sql = "SELECT
                  idsucursal,
                  nombre,
                  direccion,
                  telefono,
                  mapa,
                  horario
                FROM sucursales WHERE idsucursal = $idsucursal";
            $lstRetorno = DB::select($sql);

            if (count($lstRetorno) > 0) {
                  $this->idsucursal = $lstRetorno[0]->idsucursal;
                  $this->nombre = $lstRetorno[0]->nombre;
                  $this->direccion = $lstRetorno[0]->direccion;
                  $this->telefono = $lstRetorno[0]->telefono;
                  $this->mapa = $lstRetorno[0]->mapa;
                  $this->horario = $lstRetorno[0]->horario;
                  return $this;
            }
            return null;
      }

      public function guardar()
      {
            $sql = "UPDATE productos SET
          nombre='$this->nombre',
          direccion='$this->direccion',
          telefono='$this->telefono',
          mapa='$this->mapa',
          horario='$this->horario' 
          WHERE idsucursal=?";
            $affected = DB::update($sql, [$this->idsucursal]);
      }

      public function eliminar()
    {
        $sql = "DELETE FROM sucursales WHERE
            idsucursal=?";
        $affected = DB::delete($sql, [$this->idsucursal]);
    }

    public function insertar()
    {
        $sql = "INSERT INTO sucursales (
                  nombre,
                  direccion,
                  telefono,
                  mapa,
                  horario
            ) VALUES (?, ?, ?, ?, ?);";
        $result = DB::insert($sql, [
            $this->nombre,
            $this->direccion,
            $this->telefono,
            $this->mapa,
            $this->horario
           
        ]);
        return $this->idsucursal = DB::getPdo()->lastInsertId();
    }
}