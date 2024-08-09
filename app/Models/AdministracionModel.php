<?php

namespace App\Models;

use CodeIgniter\Model;

class AdministracionModel extends Model
{
    public function lista($tabla)
    {
        $builder = $this->db->table($tabla["tabla"]);
        $builder->select($tabla["id"] . " as id, " . $tabla["principal"]);
        $query = $builder->get();
        return $query->getResultArray();
    }
    public function modulo($datos, $tabla)
    {
        $builder = $this->db->table($tabla["tabla"]);
        $builder->select($tabla["id"] . " as id, " . $tabla["principal"]);
        $builder->where($tabla["id"], $datos->id);
        $result = $builder->get();
        $data = $result->getResultArray();
        return $data;
    }
    public function guardar($datos, $tabla)
    {
        $db = $this->db->table($tabla["tabla"]);
        $db->insert($datos);
        return $this->db->affectedRows();
    }
    public function modificar($datos, $tabla)
    {
        //obtiene el valor por el indice del array, sino hago esto obtiene nombre del campo
        $claves = array_keys($datos);
        $primerClave  = $claves[0];
        $id = $datos[$primerClave];
        //***

        $db = $this->db->table($tabla["tabla"]);
        $db->where($tabla["id"], $id);
        $db->update($datos);


        return $this->db->affectedRows();
    }
    public function eliminar($data, $tabla)
    {
        $db = $this->db->table('aca_beca');
        $db->delete(array($tabla["principal"] => $data->id));

        return $db;
    }
    public function existe($datos, $tabla)
    {
        $query = $this->db->table($tabla["tabla"])
            ->where($tabla["principal"], $datos->id)
            ->get();
        return $query->getNumRows();
    }
    public function existe_for_modificar($datos, $tabla)
    {
        $query = $this->db->table($tabla["tabla"])
            ->where($tabla["principal"], $datos->descripcion)
            ->where($tabla["id"] . ' !=', $datos->id)
            ->get();
        return $query->getNumRows();
    }
}
