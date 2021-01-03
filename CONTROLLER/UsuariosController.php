<?php

include_once './CONTROLLER/BaseController.php';

class UsuariosController extends BaseController{

    // Overriding addForm method
    function addForm(){
        $data["userTypes"] = UsuariosModel::$userTypes;
		new $this->addView($data);
    }

    // Overriding add
    function add(){
        $user = new UsuariosModel();
        $user->patchEntity();
        $data["result"] = $user->ADD();
        if($data["result"]["code"] === "111" && $user->get("TIPO_USUARIO") === "RESPONSABLE"){
            include_once './MODEL/ResponsablesModel.php';
            $responsable = new ResponsablesModel();
            $responsable->patchEntity();
            $responsable->setAtributes(array("LOGIN_RESPONSABLE" => $user->get("LOGIN_USUARIO")));
            $data["result"] = $responsable->ADD();
            if($data["result"]["code"] !== "555"){
                $user->DELETE();
            }
        }
		$data["controller"] = $this->controller;
		$data["action"] = "search";
		new MessageView($data);
    }
    
    // Overriding addForm method
    function editForm(){
        $userSearch = new UsuariosModel();
        $userSearch->patchEntity();
        new $this->editView($userSearch->SHOW());
    }

    // Overriding edit
    function edit(){
        $user = new UsuariosModel();
        $user->patchEntity();
        $data["result"] = $user->EDIT();
        if($data["result"]["code"] === "111" && $user->get("TIPO_USUARIO") === "RESPONSABLE"){
            include_once './MODEL/ResponsablesModel.php';
            $responsable = new ResponsablesModel();
            $responsable->patchEntity();
            $responsable->setAtributes(array("LOGIN_RESPONSABLE" => $user->get("LOGIN_USUARIO")));
            $data["result"] = $responsable->EDIT();
        }
		$data["controller"] = $this->controller;
		$data["action"] = "search";
		new MessageView($data);
    }

}
?>