<?php

namespace App\Entidades;

use DB;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';
    public $timestamps = false;

    protected $fillable = [
        'idproveedor', 'nombre', 'domicilio', 'cuit','fk_idrubro',
    ];

    protected $hidden = [

    ];
    public function cargarDesdeRequest($request) {
        $this->idproveedor = $request->input('id') != "0" ? $request->input('id') : $this->idproveedor;
        $this->nombre = $request->input('txtNombre');
        $this->domicilio = $request->input('txtDomicilio');
        $this->cuit = $request->input('txtCuit');
        $this->fk_idrubro = $request->input('lstRubro');
    }

    public function obtenerTodos()
    {
        $sql = "SELECT
                  idproveedor,
                  nombre,
                  domicilio,
                  cuit,
                  fk_idrubro
                FROM proveedores ORDER BY nombre";
        $lstRetorno = DB::select($sql);
        return $lstRetorno;
    }

    public function obtenerPorId($idProveedor) 
    {
        $sql = "SELECT
                  idproveedor,
                  nombre,
                  domicilio,
                  cuit,
                  fk_idrubro
                FROM proveedores WHERE idproveedor = $idProveedor";
        $lstRetorno = DB::select($sql);

        if (count($lstRetorno) > 0) {
            $this->idproveedor = $lstRetorno[0]->idproveedor;
            $this->nombre = $lstRetorno[0]->nombre;
            $this->domicilio = $lstRetorno[0]->domicilio;
            $this->cuit = $lstRetorno[0]->cuit;
            $this->fk_idrubro = $lstRetorno[0]->fk_idrubro;
            return $this;
        }
        return null;
    }

    public function guardar() {
      $sql = "UPDATE proveedores SET
          nombre='$this->nombre',
          domicilio='$this->domicilio',
          cuit=$this->cuit,
          fk_idrubro=$this->fk_idrubro   
          WHERE idproveedor=?";
      $affected = DB::update($sql, [$this->idproveedor]);
  }

  public function eliminar()
    {
        $sql = "DELETE FROM proveedores WHERE
            idproveedor=?";
        $affected = DB::delete($sql, [$this->idproveedor]);
    }

    public function insertar()
    {
        $sql = "INSERT INTO proveedores (
                  nombre,
                  domicilio,
                  cuit,
                  fk_idrubro
            ) VALUES (?, ?, ?, ?);";
        $result = DB::insert($sql, [
            $this->nombre,
            $this->domicilio,
            $this->cuit,
            $this->fk_idrubro,
        ]);
        return $this->idproveedor = DB::getPdo()->lastInsertId();
    }

    public function obtenerFiltrado()
    {
        $request = $_REQUEST;
        $columns = array(
            0=> 'nombre',
            1=> 'domicilio',
            2=> 'cuit',
            3=> 'r.nombre',
            
        );
        $sql = "SELECT DISTINCT
                    p.idproveedor,
                    p.nombre,
                    p.domicilio,
                    p.cuit,
                    r.nombre AS nombre_rubro 
                FROM proveedores  p
                INNER JOIN rubros r ON p.fk_idrubro = r.idrubro
                WHERE 1=1";

        //Realiza el filtrado
        if (!empty($request['search']['value'])) {
            $sql .= " AND ( nombre LIKE '%" . $request['search']['value'] . "%' ";
            $sql .= " OR domicilio LIKE '%" . $request['search']['value'] . "%' ";
            $sql .= " OR cuit LIKE '%" . $request['search']['value'] . "%' )";
            $sql .= " OR r.nombre LIKE '%" . $request['search']['value'] . "%' )";
        }
        $sql .= " ORDER BY " . $columns[$request['order'][0]['column']] . "   " . $request['order'][0]['dir'];

        $lstRetorno = DB::select($sql);

        return $lstRetorno;
    }

}

?>
