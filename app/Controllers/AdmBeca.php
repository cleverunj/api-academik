<?php

namespace App\Controllers;

use App\Models\AdmBecaModel;
use App\Models\FuncionesModel;

class AdmBeca extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }
    public function lista()
    {
        $model = new AdmBecaModel();
        $data = $model->lista();

        $data = array('items' => $data);
        echo json_encode($data);
    }
    public function modulo()
    {
        $data = json_decode(file_get_contents('php://input'));
        $model = new AdmBecaModel();
        $query = $model->modulo($data);
        $data = $query->getResultArray();
        $data = array('items' => $data);
        echo json_encode($data);
    }
    private function valores($data)
    {
        if (empty($data->beca)){
            $model = new FuncionesModel();
            $dato = $model->correlativo("aca_beca", "beca", -2);
        }else{
            $dato=$data->beca;
        }


        return array(
            'beca' => strtoupper($dato),
            'descripcion' => $data->descripcion
        );
    }
    private function validar($data)
    {
        $errors = array();
        $model = new AdmBecaModel();
        if (empty($data->beca)) {
            $t = $model->existe($data);
            if ($t > 0) {
                $errors[] = "La beca ya esta registrada";
            }
        } else {
            $t = $model->existe_for_modificar($data);
            if ($t > 0) {
                $errors[] = "La beca ya esta registrada";
            }
        }
       
        return $errors;
    }
    public function guardar()
    {
        $data = json_decode(file_get_contents('php://input'));
        $data = $data->data;

        $errors = $this->validar($data);
        if (!empty($errors)) {
            $rpta = array('rpta' => '0', 'msg' =>$errors);
            echo json_encode($rpta);
            return false;
        }
       
        $datos = $this->valores($data); 

        $model = new AdmBecaModel();
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
        $model = new AdmBecaModel();

        $errors = $this->validar($data);
        if (!empty($errors)) {
            $rpta = array('rpta' => '0', 'msg' =>$errors);
            echo json_encode($rpta);
            return false;
        }

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
        $model = new AdmBecaModel();
        $sw = $model->eliminar($data);
        $data = array('rpta' => '0', 'msg' =>"El registro no se pudo eliminar");
        if ($sw){
            $data = array('rpta' => '1', 'msg' =>"El registro fue eliminado");
        }
        echo json_encode($data);
    }
}
