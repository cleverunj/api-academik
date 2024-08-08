<?php

namespace App\Controllers;

use App\Models\HomeModel;

class Home extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }
    public function lista_menu()
    {
        $do = "01";
        if (!empty($_GET["do"])) {
            $do == $_GET["do"];
        }
        $model = new HomeModel();
        $data = $model->lista_menu($do);
        $data_opcion = $model->lista_menu_opcion($do);

        $data = array('items' => $data, 'items_opcion'=> $data_opcion);
        echo json_encode($data);
    }
    public function lista_modulo()
    {
        $model = new HomeModel();
        $data = $model->lista_modulo();

        $data = array('items' => $data);
        echo json_encode($data);
    }
}
