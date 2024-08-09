<?php

namespace App\Controllers;

use App\Models\AdministracionModel;
use App\Models\FuncionesModel;

class Administracion extends BaseController
{
    private function tabla($data)
    {
        if ($data->modulo=="beca"){
            return array(
                'tabla' => "aca_beca",
                'id' => "beca",
                'principal' => "descripcion"
            );
        }
        if ($data->modulo=="depaca"){
            return array(
                'tabla' => "aca_departamentoacademico",
                'id' => "departamentoacademico",
                'principal' => "descripcion"
            );
        }
        if ($data->modulo=="catdocente"){
            return array(
                'tabla' => "aca_categoriadocente",
                'id' => "categoria",
                'principal' => "descripcion"
            );
        }
        if ($data->modulo=="condocente"){
            return array(
                'tabla' => "aca_condiciondocente",
                'id' => "condicion",
                'principal' => "descripcion"
            );
        }
        if ($data->modulo=="dedocente"){
            return array(
                'tabla' => "aca_dedicaciondocente",
                'id' => "dedicacion",
                'principal' => "descripcion"
            );
        }
        if ($data->modulo=="condalumno"){
            return array(
                'tabla' => "aca_condicion",
                'id' => "condicion",
                'principal' => "descripcion"
            );
        }
        if ($data->modulo=="gradoacademico"){
            return array(
                'tabla' => "aca_grado",
                'id' => "grado",
                'principal' => "descripcion"
            );
        }
        if ($data->modulo=="modingreso"){
            return array(
                'tabla' => "aca_modalidadingreso",
                'id' => "modalidadingreso",
                'principal' => "descripcion"
            );
        }
    }
    public function lista()
    {
        $data = json_decode(file_get_contents('php://input'));
       
        $tabla = $this->tabla($data);

        $model = new AdministracionModel();
        $data = $model->lista($tabla);

        $data = array('items' => $data);
        echo json_encode($data);
    }
    public function modulo()
    {
        $data = json_decode(file_get_contents('php://input'));
        $tabla = $this->tabla($data);

        $model = new AdministracionModel();
        $data = $model->modulo($data, $tabla);

        $data = array('items' => $data);
        echo json_encode($data);
    }
    private function valores($data, $tabla)
    {
        if (empty($data->id)) {
            $model = new FuncionesModel();
            $dato = $model->correlativo($tabla["tabla"], $tabla["id"], -2);
        } else {
            $dato = $data->id;
        }


        return array(
            $tabla["id"] => strtoupper($dato),
            $tabla["principal"] => $data->descripcion
        );
    }
    private function validar($data, $tabla)
    {
        $errors = array();
        $model = new AdministracionModel();
        if (empty($data->id)) {
            $t = $model->existe($data , $tabla);
            if ($t > 0) {
                $errors[] = "La beca ya esta registrada";
            }
        } else {
            $t = $model->existe_for_modificar($data , $tabla);
            if ($t > 0) {
                $errors[] = "La beca ya esta registrada";
            }
        }

        return $errors;
    }
    public function guardar()
    {
        $data = json_decode(file_get_contents('php://input'));
        $tabla = $this->tabla($data);
        $data = $data->data;
        
        

        $errors = $this->validar($data, $tabla );
        if (!empty($errors)) {
            $rpta = array('rpta' => '0', 'msg' => $errors);
            echo json_encode($rpta);
            return false;
        }


        $datos = $this->valores($data, $tabla);

        $model = new AdministracionModel();
        $t = $model->guardar($datos, $tabla);
        $rpta = array('rpta' => '0', 'msg' => 'Error al guardar!');
        if ($t > 0) {
            $rpta = array('rpta' => '1', 'msg' => '¡Su registro se ha completado con éxito!');
        }

        echo json_encode($rpta);
    }
    public function modificar()
    {
        $data = json_decode(file_get_contents('php://input'));
        $tabla = $this->tabla($data);
        $data = $data->data;

        $model = new AdministracionModel();
        $errors = $this->validar($data, $tabla);
        if (!empty($errors)) {
            $rpta = array('rpta' => '0', 'msg' => $errors);
            echo json_encode($rpta);
            return false;
        }



        $datos = $this->valores($data, $tabla);
        $t = $model->modificar($datos, $tabla);
        $rpta = array('rpta' => '1', 'msg' => "Guardado correctamente");
        if ($t > 0) {
            $rpta = array('rpta' => '1', 'msg' => '¡Su registro se ha completado con éxito!');
        }

        echo json_encode($rpta);
    }
    public function eliminar()
    {
        $data = json_decode(file_get_contents('php://input'));
        $tabla = $this->tabla($data);

        $model = new AdministracionModel();
        $sw = $model->eliminar($data, $tabla);
        $data = array('rpta' => '0', 'msg' => "El registro no se pudo eliminar");
        if ($sw) {
            $data = array('rpta' => '1', 'msg' => "El registro fue eliminado");
        }
        echo json_encode($data);
    }
}
