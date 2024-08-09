<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AuthModel;
use CodeIgniter\RESTful\ResourceController;
use Config\Services;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Auth extends ResourceController
{

    protected $format = 'json';

    public function auth()
    {
        $data = json_decode(file_get_contents('php://input'));
        $msg = " El usuario o password son incorrectos";
        if ($data->nivel == '' or $data->usuario == '' or $data->password == '') {
            $data = array('rpta' => '0', 'icon' => "error", 'msg' => $msg);
            return false;
        }
        $model = new AuthModel();
        $row = $model->auth($data);

        if (isset($row)) {
            if ($data->nivel=="P01"){
                $nivel="Alumno";
            }elseif ($data->nivel=="P02"){
                $nivel="Docente";
            }if ($data->nivel=="S"){
                $nivel="Administrativo";
            }

            $datos = [
                'nivel' => $nivel,                
                'persona' => $row->persona,
                'usuario' => $row->usuario,
                'nombrecompleto' => $row->nombrecompleto,
                'nombre' => $row->primernombre,
                'sede' => $row->sede,
                'perfil' => $row->perfil
            ];
            session()->set('data',(array) $datos);


            $time = time();
            $key = Services::getSecretKey();
            $payload = [
                'iat' => $time,
                //'exp' => $time + 43200, //43200
                'data' => $datos,
            ];


            $jwt = JWT::encode($payload, $key, 'HS256');
            $model->setToken($row->persona, $jwt);
            $datos['token'] = $jwt;
            $rpta = array('rpta' => '1', 'icon' => "info", 'msg' => 'Bienvenido', 'data' => $datos);

            return $this->respond($rpta, 200);
        }
        $rpta = array('rpta' => '0', 'icon' => "info", 'msg' => 'Datos de login invalidos');
        return $this->respond($rpta, 200);
    }
    protected function validateToken($token)
    {
        try {
            $key = Services::getSecretKey();
            return JWT::decode($token, new Key($key, 'HS256'));
        } catch (\Exception $e) {
            return false;
        }
    }

    public function verifyToken()
    {
        $key = Services::getSecretKey();

        //$token=$this->request->getPost("token");
        $data = json_decode(file_get_contents('php://input'));

        if ($this->validateToken($data->token) == false) {
            return $this->respond(['msg' => 'Token invalido'], 401);
        } else {
            $data = JWT::decode($data->token, new Key($key, 'HS256'));
            return $this->respond(['msg' => $data], 200);
        }
    }
}
