<?php

// DEBUG: SHOW ALL ERRORS
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include './COMMON/FuncionesGenerales.php';

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

	// DEBUG: Check SESSION variable
	// echo '<pre>' . var_export($_SESSION, true) . '</pre>';

	// Get and include controller
	$controller = isset($_REQUEST['controller']) ? $_REQUEST['controller'] : 'ReservasController'; 
	include_once './CONTROLLER/'. $controller . '.php';
	
	// Get action
	$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'confirm'; 

	// DEBUG: Check values
	// echo $controller.' - '.$action;

	$controllerObject = new $controller();
	$controllerObject->$action();

}

?>