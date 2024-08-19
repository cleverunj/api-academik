<?php

namespace App\Models;

use CodeIgniter\Model;

class AdmAmbienteModel extends Model
{
    public function lista()
    {
        $builder = $this->db->table('aca_ambiente a');
        $query = $builder->get();
        return $query->getResultArray();
    }
    public function modulo($datos)
    {
        $builder = $this->db->table("aca_ambiente a");        
        $builder->where("a.ambiente", $datos->id);
        $result = $builder->get();

        return $result;
    }
    public function guardar($datos)
    {
        $db = $this->db->table('aca_ambiente');
		$db->insert($datos);
        return $this->db->affectedRows();
    }
    public function modificar($datos)
    {
        $db = $this->db->table('aca_ambiente');
        $db->where('ambiente', $datos["ambiente"]);
        $db->update($datos);

        return $this->db->affectedRows();
    }
    public function eliminar($data)
    {
        $db = $this->db->table('aca_ambiente');
        $db->delete(array('ambiente' => $data->id));

        return $db;

    }
}
