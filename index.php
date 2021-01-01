<?php

// DEBUG: SHOW ALL ERRORS
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include './COMMON/FuncionesGenerales.php';

session_start();

if (isAuthenticated() === false){
	
	if (!($_POST)){

		include_once './CONTROLLER/LoginController.php';
		$loginController = new LoginController();
		$loginController->loginForm();

	} else{

		$authControllers = array(
			"RegisterController" => array( "registerForm", "register" ),
			"LoginController" => array( "loginForm", "login" )
		);
		
		$controllerName = $_POST['controller'];
		$action = $_POST['action'];

		if( array_key_exists($controllerName, $authControllers) &&
			in_array($action, $authControllers[$controllerName]) ){

			include_once './CONTROLLER/' . $controllerName . '.php';
			$controller = new $controllerName();
			$controller->$action();
			
		}
		
	}

} else {

	// DEBUG: Check SESSION variable
	// echo '<pre>' . var_export($_SESSION, true) . '</pre>';

	// Get and include controller
	$controller = isset($_REQUEST['controller']) ? $_REQUEST['controller'] : 'RecursosController'; 
	include_once './CONTROLLER/'. $controller . '.php';
	
	// Get action
	$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'search'; 

	// DEBUG: Check values
	// echo $controller.' - '.$action;

	$controllerObject = new $controller();
	$controllerObject->$action();

}

?>