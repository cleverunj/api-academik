<?php

namespace App\Models;

use CodeIgniter\Model;

class AuthModel extends Model
{

    public function auth($data)
    {
 
        $builder = $this->db->table("mae_persona m");
        $builder->select('m.persona, m.usuario, m.nombrecompleto, m.primernombre ');
        if ($data->nivel == "S") {  //Administrativo
            $builder->select("t.estructura, t.nivel, t.trabajador, t.sede");
            $builder->select("p.perfil");
            $builder->join('per_trabajador t', 't.persona = m.persona', 'LEFT');
            $builder->join('men_personaperfil P', 'p.persona = m.persona', 'inner');
            $builder->where('m.usuario', $data->usuario);
            $builder->where('m.password', MD5($data->password));
        }elseif ($data->nivel == "P01") { //Alumno
            $builder->select("'' as estructura, '1' as nivel, '' as trabajador, t.sede");
            $builder->select("'". $data->nivel ."' as perfil");
            $builder->join('mae_alumno t', 't.persona = m.persona', 'inner');
            $builder->where('t.alumno', $data->usuario);
            $builder->where('m.password', MD5($data->password));
        }elseif ($data->nivel == "P02") {  //Docente
            $builder->select("'' as estructura, '1' as nivel, '' as trabajador, '01' as sede");
            $builder->select("'". $data->nivel ."'  as perfil");
            $builder->join('aca_docente t', 't.persona = m.persona', 'inner');
            $builder->where('m.usuario', $data->usuario);
            $builder->where('m.password', MD5($data->password));
        }else{
            return null;
        }
        $result = $builder->get();
        $row = $result->getRow();

        return $row;
    }

    public function setToken($id, $jwt)
    {
        $datos = array(
            'token' => $jwt
        );
        $db = $this->db->table('mae_persona');
        $db->where('persona', $id);
        $db->update($datos);
    }
}
