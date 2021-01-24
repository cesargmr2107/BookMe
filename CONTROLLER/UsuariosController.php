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
        if( $data["result"]["code"] === $user->getCode("add", "success") &&
            $user->get("TIPO_USUARIO") === "RESPONSABLE"){
                include_once './MODEL/ResponsablesModel.php';
                $responsable = new ResponsablesModel();
                $responsable->patchEntity();
                $responsable->setAtributes(array("LOGIN_RESPONSABLE" => $user->get("LOGIN_USUARIO")));
                $data["result"] = $responsable->ADD();
                if($data["result"]["code"] !== $responsable->getCode("add", "success")){
                    $user->DELETE();
                }
        }
		$data["controller"] = $this->controller;
		$data["action"] = "search";
        $this->redirectToMsg($data);
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
        if($user->get("TIPO_USUARIO") === "RESPONSABLE"){
            include_once './MODEL/ResponsablesModel.php';
            $responsable = new ResponsablesModel();
            $responsable->patchEntity();
            $responsable->setAtributes(array("LOGIN_RESPONSABLE" => $user->get("LOGIN_USUARIO")));
            $data["result"] = $responsable->EDIT();
        }
		$data["controller"] = $this->controller;
		$data["action"] = "search";
        $this->redirectToMsg($data);
    }

    function delete(){
        $user = new UsuariosModel();
        $user->patchEntity();
        if($user->SHOW()["normal_info"]["TIPO_USUARIO"] === "RESPONSABLE"){
            include_once './MODEL/ResponsablesModel.php';
            $responsable = new ResponsablesModel();
            $responsable->patchEntity();
            $responsable->setAtributes(array("LOGIN_RESPONSABLE" => $user->get("LOGIN_USUARIO")));
            $data["result"] = $responsable->DELETE();
            if($data["result"]["code"] === $responsable->getCode("delete","success")){
                $user->DELETE();
            }
        }else{
            $data["result"] = $user->DELETE();
        }
        if($_SESSION["LOGIN_USUARIO"] === $user->get("LOGIN_USUARIO")){
            session_destroy();
            header("Location: index.php");
        }else{
            $data["controller"] = $this->controller;
            $data["action"] = "search";
            $this->redirectToMsg($data);
        }
	}

}
?>