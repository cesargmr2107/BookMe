<?php

class CalendariosController {	
	
	function __construct(){
        include './MODEL/CalendariosModel.php';
        foreach (glob("./VIEW/calendarios/*.php") as $filename)
        {
            include_once $filename;
        }
	}

	function search(){
		$calendario = new CalendariosModel();
		$calendario->patchEntity();
		$data["atributeNames"] = CalendariosModel::getFormattedAtributeNames();
		$data["result"] = $calendario->SEARCH();
        new CalendariosSearchView($data);
	}

	function show(){
		$calendario = new CalendariosModel();
		$calendario->patchEntity();
		$data["result"] = $calendario->SHOW();
        new CalendariosShowView($data);
	}

}
?>