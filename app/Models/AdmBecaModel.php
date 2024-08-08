<?php

namespace App\Models;

use CodeIgniter\Model;

class AdmBecaModel extends Model
{
    public function lista()
    {
        $builder = $this->db->table('aca_beca a');
        $query = $builder->get();
        return $query->getResultArray();
    }
    public function modulo($datos)
    {
        $builder = $this->db->table("aca_beca a");        
        $builder->where("a.beca", $datos->id);
        $result = $builder->get();

        return $result;
    }
    public function guardar($datos)
    {
        $db = $this->db->table('aca_beca');
		$db->insert($datos);
        return $this->db->affectedRows();
    }
    public function modificar($datos)
    {
        $db = $this->db->table('aca_beca');
        $db->where('beca', $datos["beca"]);
        $db->update($datos);

        return $this->db->affectedRows();
    }
    public function eliminar($data)
    {
        $db = $this->db->table('aca_beca');
        $db->delete(array('beca' => $data->id));

        return $db;
    }
    public function existe($data)
	{
		$query = $this->db->table('aca_beca')
			->where('descripcion', $data->descripcion)
			->get();
		return $query->getNumRows();
	}
	public function existe_for_modificar($data)
	{
		$query = $this->db->table('aca_beca')
			->where('descripcion', $data->descripcion)
			->where('beca !=', $data->beca)
			->get();
		return $query->getNumRows();
	}
}
