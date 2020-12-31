<?php

class CalendariosController {	
	
	function __construct(){
		include './MODEL/CalendariosModel.php';
		include './VIEW/MessageView.php';
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
        new CalendariosShowView($calendario->SHOW());
	}

	function delete(){
		$calendario = new CalendariosModel();
		$calendario->patchEntity();
		$data["result"] = $calendario->DELETE();
		$data["controller"] = "CalendariosController";
		$data["action"] = "search";
		new MessageView($data);
	}

	function addForm(){
		new CalendariosAddView();
	}

	function add(){
		$calendario = new CalendariosModel();
		$calendario->patchEntity();
		$data["result"] = $calendario->ADD();
		$data["controller"] = "CalendariosController";
		$data["action"] = "search";
		new MessageView($data);
	}

	function editForm(){
		$calendarioSearch = new CalendariosModel();
		$calendarioSearch->patchEntity();
		$data = $calendarioSearch->SHOW();
		new CalendariosEditView($data);
	}

	function edit(){
		$calendario = new CalendariosModel();
		$calendario->patchEntity();
		$data["result"] = $calendario->EDIT();
		$data["controller"] = "CalendariosController";
		$data["action"] = "search";
		new MessageView($data);
	}
}
?>