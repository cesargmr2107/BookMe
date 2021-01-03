<?php

include_once './CONTROLLER/BaseController.php';

class RecursosController extends BaseController {	
    
    private function getData(){
        // Get price ranges
        $data["priceRanges"] = RecursosModel::$priceRanges;
        
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
        $data = $this->getData();
		new $this->addView($data);
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