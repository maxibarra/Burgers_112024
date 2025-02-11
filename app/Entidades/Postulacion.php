<?php

namespace App\Entidades;

use DB;
use Illuminate\Database\Eloquent\Model;

class Postulacion extends Model
{
      protected $table = 'postulaciones';
      public $timestamps = false;

      protected $fillable = [
            'idpostulacion',
            'nombre',
            'apellido',
            'whatsapp',
            'correo',
            'linkcv'
                  ];

      protected $hidden = [];

      public function cargarDesdeRequest($request) {
            $this->idpostulacion = $request->input('id') != "0" ? $request->input('id') : $this->idpostulacion;
            $this->nombre = $request->input('txtNombre');
            $this->apellido = $request->input('txtApellido');
            $this->whatsapp = $request->input('txtWhatsapp');
            $this->correo = $request->input('txtCorreo');
            $this->linkcv = $request->input('archivo');
        }
      public function obtenerTodos()
      {
            $sql = "SELECT
                  idpostulacion,
                  nombre,
                  apellido,
                  whatsapp,
                  correo,
                  linkcv               
                FROM postulaciones ORDER BY nombre ASC";
            $lstRetorno = DB::select($sql);
            return $lstRetorno;
      }

      public function obtenerPorId($idpostulacion)
      {
            $sql = "SELECT
                  idpostulacion,
                  nombre,
                  apellido,
                  whatsapp,
                  correo,
                  linkcv
                FROM postulaciones WHERE idpostulacion = $idpostulacion";
            $lstRetorno = DB::select($sql);

            if (count($lstRetorno) > 0) {
                  $this->idpostulacion = $lstRetorno[0]->idpostulacion;
                  $this->nombre = $lstRetorno[0]->nombre;
                  $this->apellido = $lstRetorno[0]->apellido;
                  $this->whatsapp = $lstRetorno[0]->whatsapp;
                  $this->correo = $lstRetorno[0]->correo;
                  $this->linkcv = $lstRetorno[0]->linkcv;
                  return $this;
            }
            return null;
      }

     public function guardar()
      {
            $sql = "UPDATE postulaciones SET
          nombre='$this->nombre',
          apellido='$this->apellido',
          whatsapp='$this->whatsapp',
          correo='$this->correo',
          linkcv='$this->linkcv'
          WHERE idpostulacion=?";
            $affected = DB::update($sql, [$this->idpostulacion]);
      }

      public function eliminar()
    {
        $sql = "DELETE FROM postulaciones WHERE
            idpostulacion=?";
        $affected = DB::delete($sql, [$this->idpostulacion]);
    }

    public function insertar()
    {
        $sql = "INSERT INTO postulaciones(
                  nombre,
                  apellido,
                  whatsapp,
                  correo,
                  linkcv
            ) VALUES (?, ?, ?, ?, ?);";
        $result = DB::insert($sql, [
            $this->nombre,
            $this->apellido,
            $this->whatsapp,
            $this->correo,
            $this->linkcv
        ]);
        return $this->idpostulacion = DB::getPdo()->lastInsertId();
    }

    public function obtenerFiltrado()
    {
        $request = $_REQUEST;
        $columns = array(
            0 => 'nombre',
            1 => 'apellido',
            2 => 'whatsapp',
            3 => 'correo',
            4 => 'linkcv',
        );
        $sql = "SELECT DISTINCT
                    idpostulacion,
                    nombre,
                    apellido,
                    whatsapp,
                    correo,
                    linkcv
                FROM postulaciones
                WHERE 1=1";

        //Realiza el filtrado
        if (!empty($request['search']['value'])) {
            $sql .= " AND ( nombre LIKE '%" . $request['search']['value'] . "%' ";
            $sql .= " OR apellido LIKE '%" . $request['search']['value'] . "%' ";
            $sql .= " OR whatsapp LIKE '%" . $request['search']['value'] . "%' )";
            $sql .= " OR correo LIKE '%" . $request['search']['value'] . "%' )";
            $sql .= " OR linkcv LIKE '%" . $request['search']['value'] . "%' )";
        }
        $sql .= " ORDER BY " . $columns[$request['order'][0]['column']] . "   " . $request['order'][0]['dir'];

        $lstRetorno = DB::select($sql);

        return $lstRetorno;
    }
}
