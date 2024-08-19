<?php

namespace App\Controllers;

use App\Models\AdmAmbienteModel;
use App\Models\AdministracionModel;
use App\Models\FuncionesModel;

class AdmAmbiente extends BaseController
{
    public function lista()
    {
        $model = new AdmAmbienteModel();
        $vista["lista"] = $model->lista();

        $model = new AdministracionModel();
        $vista["lista_local"] = $model->lista(array('tabla' => "aca_local",'id' => "local",'principal' => "descripcion"));

        $data = array('items' => $vista);
        echo json_encode($data);
    }
    public function modulo()
    {
        $data = json_decode(file_get_contents('php://input'));
        $model = new AdmAmbienteModel();
        $query = $model->modulo($data);
        $data = $query->getResultArray();
        $data = array('items' => $data);
        echo json_encode($data);
    }
    private function valores($data)
    {
        return array(
            'ambiente' => $data->ambiente,
            'local' => $data->local,
            'descripcion' => $data->descripcion,
            'capacidad' => $data->capacidad,
            'observacion' => $data->observacion
        );
    }
    public function guardar()
    {
        $data = json_decode(file_get_contents('php://input'));
        $data = $data->data;
        $model = new AdmAmbienteModel();

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
        $model = new AdmAmbienteModel();

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
        $model = new AdmAmbienteModel();
        $sw = $model->eliminar($data);
        $data = array('rpta' => '0', 'msg' =>"El registro no se pudo eliminar");
        if ($sw){
            $data = array('rpta' => '1', 'msg' =>"El registro fue eliminado");
        }
        echo json_encode($data);
    }
}
