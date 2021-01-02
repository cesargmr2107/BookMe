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
        $entitySearch->patchEntity();
        
        function editForm(){
            $entitySearch = new $this->model();
            
            $data = $entitySearch->SHOW();
            new $this->editView($data);
        }
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

        new $this->addView($data);
    }

    

}
?>