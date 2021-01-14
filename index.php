<?php

// DEBUG: SHOW ALL ERRORS
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once './COMMON/utils.php';

session_start();

if (isAuthenticated() === false){

	include_once './CONTROLLER/AuthenticationController.php';
	$authController = new AuthenticationController();
	$authMethods = get_class_methods("AuthenticationController");
	if (!($_POST) || !in_array($_POST['action'], $authMethods)){
		$authController->loginForm();
	} else{
		$action = $_POST['action'];
		$authController->$action();
	}

} else {

	if( isset($_GET) && array_key_exists("token", $_GET)){

		include_once './CONTROLLER/MessageController.php';
		(new MessagesController())->render();
		
	}else{
		
		$default = array(
			"ADMINISTRADOR" => array("controller" => "RecursosController" , "action" => "search"),
			"RESPONSABLE" => array("controller" => "ReservasController" , "action" => "searchPending"),
			"NORMAL" => array("controller" => "ReservasController" , "action" => "searchOwn"),
		);

		$userType = $_SESSION["TIPO_USUARIO"];

		// Get and include controller
		$controller = isset($_REQUEST['controller']) ? $_REQUEST['controller'] : $default[$userType]["controller"]; 
		include_once './CONTROLLER/'. $controller . '.php';
		
		// Get action
		$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : $default[$userType]["action"]; 

		// DEBUG: Check values
		// echo $controller.' - '.$action;

		$controllerObject = new $controller();
		$controllerObject->$action();
	}



}

?>