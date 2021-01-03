<?php

include_once './CONTROLLER/BaseController.php';

class UsuariosController extends BaseController{

	private function getData(){
        // Get price ranges
        $data["normal"] = RecursosModel::$priceRanges;
        
        // Get all available calendars
        include_once './MODEL/CalendariosModel.php';
        $calendarSearch = new CalendariosModel();
        $data["calendars"] = $calendarSearch->getIdAndNameArray("ID_CALENDARIO", "NOMBRE_CALENDARIO");

        // Get all available responsables
        include_once './MODEL/ResponsablesModel.php';
        $responsablesSearch = new ResponsablesModel();
        $data["responsables"] = $responsablesSearch->getIdAndNameArray("LOGIN_RESPONSABLE", "LOGIN_RESPONSABLE");

        return $data;
    }

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
        $resourceSearch = new RecursosModel();
        $resourceSearch->patchEntity();
        $data = $this->getData();
        $data["resource"] = $resourceSearch->SHOW();
        new $this->editView($data);
    }

}
?>