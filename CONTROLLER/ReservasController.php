<?php

include_once './CONTROLLER/BaseController.php';

class ReservasController extends BaseController{	
    
    function searchOwn(){
        $reservasSearch = new ReservasModel();
        $data = $reservasSearch->SEARCH_OWN($_SESSION["LOGIN_USUARIO"]);
        new ReservasPropiasView($data);
    }

    function addForm(){
        // Get resources
        include_once './MODEL/RecursosModel.php';
        $resourcesSearch = new RecursosModel();
        $data["resources"] = $resourcesSearch->getIdAndNameArray("ID_RECURSO", "NOMBRE_RECURSO");

        if(array_key_exists("ID_RECURSO", $_POST)){
            $resourcesSearch->setAtributes(array("ID_RECURSO" => $_POST["ID_RECURSO"] ));
            $data["resource_info"] = $resourcesSearch->SHOW();
        }

        new $this->addView($data);
    }
}
?>