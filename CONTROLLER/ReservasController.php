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

    function add(){
        
        $reserva = new ReservasModel();

        $atributesToSet = array(
            "LOGIN_USUARIO" => $_SESSION["LOGIN_USUARIO"],
            "ID_RECURSO" => $_POST["ID_RECURSO"],
            "FECHA_SOLICITUD_RESERVA" => date_format(new DateTime(), 'Y-m-d'),
            "COSTE_RESERVA" => $_POST["COSTE_RESERVA"]
        );

        $reserva->setAtributes($atributesToSet);
        $reserva->setInfoSubreservas($_POST["INFO_SUBRESERVAS"]);
                
		$data["result"] = $reserva->ADD();
		$data["controller"] = $this->controller;
		$data["action"] = "searchOwn";
		new MessageView($data);
    }
}
?>