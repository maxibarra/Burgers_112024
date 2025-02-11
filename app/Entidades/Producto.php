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
            'fk_idtipoproducto'
      ];

      protected $hidden = [];

      public function cargarDesdeRequest($request) {
            $this->idproducto = $request->input('id') != "0" ? $request->input('id') : $this->idproducto;
            $this->nombre = $request->input('txtNombre');
            $this->descripcion = $request->input('txtDescripcion');
            $this->precio = $request->input('txtPrecio');
            $this->cantidad = $request->input('txtCantidad');
            $this->fk_idtipoproducto = $request->input('lstTipoProducto');
            $this->imagen = $request->input('archivo');
        }
      public function obtenerTodos()
      {
            $sql = "SELECT
                  A.idproducto,
                  A.nombre,  
                  A.descripcion,
                  A.precio,
                  A.cantidad,
                  A.imagen,
                  A.fk_idtipoproducto,
                  B.nombre AS tipoProducto
                FROM productos A 
                INNER JOIN tipo_productos b ON A.fk_idtipoproducto = B.idtipoproducto
                ORDER BY nombre";
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
          imagen= '$this->imagen',
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

    public function obtenerFiltrado()
    {
        $request = $_REQUEST;
        $columns = array(
            0 => 'nombre',
            1 => 'descripcion',
            2 => 'precio',
            3 => 'cantidad',
            4 => 'imagen',
            5 => 'fk_idtipoproducto'
        );
        $sql = "SELECT DISTINCT
                    a.idproducto,
                    a.nombre,
                    a.descripcion,
                    a.precio,
                    a.cantidad,
                    a.imagen,
                    a.fk_idtipoproducto,
                    b.nombre AS tipoProducto
                FROM productos a
                INNER JOIN tipo_productos b ON a.fk_idtipoproducto = b.idtipoproducto
                WHERE 1=1
                ";

        //Realiza el filtrado
        if (!empty($request['search']['value'])) {
            $sql .= " AND ( nombre LIKE '%" . $request['search']['value'] . "%' ";
            $sql .= " OR descripcion LIKE '%" . $request['search']['value'] . "%' ";
            $sql .= " OR precio LIKE '%" . $request['search']['value'] . "%' )";
            $sql .= " OR cantidad LIKE '%" . $request['search']['value'] . "%' )";
            $sql .= " OR imagen LIKE '%" . $request['search']['value'] . "%' )";
            $sql .= " OR b.nombre LIKE '%" . $request['search']['value'] . "%' )";
        }

        $lstRetorno = DB::select($sql);

        return $lstRetorno;
    }

    public function existeProductosConCate($idCategoria){
        $sql = "SELECT
        idproducto,
        nombre,
        descripcion,
        precio,
        cantidad,
        imagen,
        fk_idtipoproducto
      FROM productos WHERE fk_idtipoproducto = $idCategoria";
      $lstRetorno = DB::select($sql);
      return(count($lstRetorno) > 0);
    }
}
