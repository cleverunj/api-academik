<?php

namespace App\Models;

use CodeIgniter\Model;

class FuncionesModel extends Model
{
    public function correlativo($tabla, $principal, $cont)
    {
        $sql = "SELECT MAX(CAST($principal AS UNSIGNED)) AS $principal
        FROM $tabla
        WHERE $principal REGEXP '^[0-9]+$'";
        $query = $this->db->query($sql);
        $row = $query->getRow();

        $data = "0000000001";
        $data = substr($data, floatval($cont));
        if (isset($row)) {
            $cad = floatval($row->$principal) + 1;
            $data = "000000000" . $cad;
            $data = substr($data, floatval($cont));
        }
        return $data;
    }
}
