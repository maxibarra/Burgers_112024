<?php

namespace App\Entidades;

use DB;
use Illuminate\Database\Eloquent\Model;

class Rubro extends Model
{
    protected $table = 'rubros';
    public $timestamps = false;

    protected $fillable = [
        'idrubro', 'nombre',
    ];

    protected $hidden = [

    ];

    public function obtenerTodos()
    {
        $sql = "SELECT
                  idrubro,
                  nombre     
                FROM rubros ORDER BY nombre";
        $lstRetorno = DB::select($sql);
        return $lstRetorno;
    }

    public function obtenerPorId($idRubro) 
    {
        $sql = "SELECT
                 idrubro,
                  nombre  
                FROM rubros WHERE idrubro = $idRubro";
        $lstRetorno = DB::select($sql);

        if (count($lstRetorno) > 0) {
            $this->idrubro = $lstRetorno[0]->idrubro;
            $this->nombre = $lstRetorno[0]->nombre;
            return $this;
        }
        return null;
    }

    public function guardar() {
      $sql = "UPDATE rubros SET
          nombre='$this->nombre' 
          WHERE idrubro=?";
      $affected = DB::update($sql, [$this->idrubro]);
  }

  public function eliminar()
    {
        $sql = "DELETE FROM rubros WHERE
            idrubro=?";
        $affected = DB::delete($sql, [$this->idrubro]);
    }

    public function insertar()
    {
        $sql = "INSERT INTO rubros (
                  nombre,
            ) VALUES (?);";
        $result = DB::insert($sql, [
            $this->nombre,
        ]);
        return $this->idrubro = DB::getPdo()->lastInsertId();
    }

}

?>
