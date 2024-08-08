<?php

namespace App\Models;

use CodeIgniter\Model;

class HomeModel extends Model
{
    public function lista_menu($id)
    {
        $builder = $this->db->table('men_menuprincipal a');
        $builder->select('a.dominio, a.menu');
        $builder->select('a.nombre as desc_menu');        
        $builder->where('a.dominio', $id);
        $builder->orderBy('a.orden');
        $query = $builder->get();
        return $query->getResultArray();
    }
    public function lista_menu_opcion($id)
    {

        $sql="SELECT  rp.perfil, d.dominio, d.nombre, mp.menu, mp.nombre, CONCAT(o.dominio,o.menu,o.opcion) as cod_opcion, o.nombre as desc_opcion,";
        $sql.=" o.url, o.url, mp.orden, o.orden";
        $sql.=" FROM men_perfilopcion rp";
        $sql.=" INNER JOIN men_opciones o ON o.dominio=rp.dominio AND o.menu=rp.menu AND o.opcion=rp.opcion";
        $sql.=" INNER JOIN men_menuprincipal mp ON mp.dominio=o.dominio AND mp.menu=o.menu";        
        $sql.=" INNER JOIN men_dominio d ON d.dominio=mp.dominio";
        $sql.=" WHERE rp.perfil = :perfil: ";
        $sql.=" AND d.dominio = :domninio: ";
        $sql.=" AND o.activo=1";
        $sql.=" AND mp.activo=1";
        $sql.=" AND d.activo=1";
         $query = $this->db->query($sql, [
                    'perfil' => session()->get("data")["perfil"],
                    'domninio' =>  $id,
                ]);


        // $builder = $this->db->table('men_opciones a');
        // $builder->select('b.dominio, a.opcion, b.nombre, b.menu');
        // $builder->select('a.nombre as desc_opcion');
        // $builder->join('men_menuprincipal b', 'b.menu = a.menu', 'inner');
        // $builder->join('men_perfilopcion rp', 'rp.menu = a.menu', 'inner');
        
        // $builder->where('b.dominio', $id);        
        // $builder->where('a.activo', '1');
        // $builder->where('a.menu', '001');
        // $builder->orderBy('b.menu');
        // $builder->orderBy('a.nombre');
        return $query->getResultArray();
    }
    public function lista_modulo()
    {
        $builder = $this->db->table('men_dominio a');
        $builder->distinct();        
        $builder->select(' a.dominio, a.nombre as desc_dominio');        
        $builder->join('men_perfilopcion b', 'b.dominio = a.dominio', 'inner');        
        $builder->where('b.perfil', session()->get("data")["perfil"]);
        $builder->where('a.activo', '1');
        $query = $builder->get();

        return $query->getResultArray();
    }
}
