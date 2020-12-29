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
	}
	else{
		if ($_POST['controller'] === 'RegisterController') {
			if ($_REQUEST['action'] === 'registerForm') {
				include './CONTROLLER/RegisterController.php';
				$registerController = new RegisterController();
				$registerController->registerForm();
			}
			else{
				include './CONTROLLER/RegisterController.php';
				$registerController = new RegisterController();
				$registerController->registrar();	
			}
		}
		else{
			include_once './CONTROLLER/LoginController.php';
			$loginController = new LoginController();
			$loginController->login();
		}
	}
}
else{

	// session_destroy();

	// DEBUG: Check SESSION variable
	echo '<pre>' . var_export($_SESSION, true) . '</pre>';

	// Get controller
	$controller = $_REQUEST['controller'];

	// Get action
	$action = $_REQUEST['action'];

	// DEBUG: Check values
	// echo $controlador.'-'.$action;

	$controllerObject = new $controller();
	$controllerObject->$action();

}

?>