<?php

namespace App\Models;

use CodeIgniter\Model;

class AdmCarreraModel extends Model
{
    public function lista()
    {
        $builder = $this->db->table('mae_estructura a');
        $query = $builder->get();
        return $query->getResultArray();
    }
    public function modulo($datos)
    {
        $builder = $this->db->table("mae_estructura a");        
        $builder->where("a.estructura", $datos->id);
        $result = $builder->get();

        return $result;
    }
    public function guardar($datos)
    {
        $db = $this->db->table('mae_estructura');
		$db->insert($datos);
        return $this->db->affectedRows();
    }
    public function modificar($datos)
    {
        $db = $this->db->table('mae_estructura');
        $db->where('estructura', $datos["estructura"]);
        $db->update($datos);

        return $this->db->affectedRows();
    }
    public function eliminar($data)
    {
        $db = $this->db->table('mae_estructura');
        $db->delete(array('estructura' => $data->id));

        return $db;

    }
}
