<?php

namespace App\Controllers;

use App\Models\AdmCarreraModel;
use App\Models\FuncionesModel;

class AdmCarrera extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }
    public function lista()
    {
        $model = new AdmCarreraModel();
        $data = $model->lista();

        $data = array('items' => $data);
        echo json_encode($data);
    }
    public function modulo()
    {
        $data = json_decode(file_get_contents('php://input'));
        $model = new AdmCarreraModel();
        $query = $model->modulo($data);
        $data = $query->getResultArray();
        $data = array('items' => $data);
        echo json_encode($data);
    }
    private function valores($data)
    {
        if (empty($data->estructura)){
            $model = new FuncionesModel();
            $dato = $model->correlativo("mae_estructura", "estructura", -2);
        }else{
            $dato=$data->estructura;
        }

        return array(
            'estructura' => $dato,
            'descripcion' => $data->descripcion,
            'responsable' => $data->responsable,
            'resolucion' => $data->resolucion
        );
    }
    public function guardar()
    {
        $data = json_decode(file_get_contents('php://input'));
        $data = $data->data;
        $model = new AdmCarreraModel();

        $datos = $this->valores($data); 
        $t = $model->guardar($datos);
        $rpta = array('rpta' => '0', 'msg' => 'Error al guardar!');
        if ($t>0) {
            $rpta = array('rpta' => '1', 'msg' => '¡Su registro se ha completado con éxito!');
        }

        echo json_encode($rpta);
    }
    public function modificar()
    {

        $data = json_decode(file_get_contents('php://input'));
        $data = $data->data;
        $model = new AdmCarreraModel();

        $datos = $this->valores($data);
        $t = $model->modificar($datos);
        $rpta = array('rpta' => '1', 'msg' => "Guardado correctamente");        
        if ($t>0) {
            $rpta = array('rpta' => '1', 'msg' => '¡Su registro se ha completado con éxito!');
        }

        echo json_encode($rpta);
    }
    public function eliminar()
    {
        $data = json_decode(file_get_contents('php://input'));
        $model = new AdmCarreraModel();
        $sw = $model->eliminar($data);
        $data = array('rpta' => '0', 'msg' =>"El registro no se pudo eliminar");
        if ($sw){
            $data = array('rpta' => '1', 'msg' =>"El registro fue eliminado");
        }
        echo json_encode($data);
    }
}
