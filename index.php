<?php

include './COMMON/FuncionesGenerales.php';

session_start();

if (Autenticacion()===false){

	if (!($_POST)){
		include_once './CONTROLLER/LOGIN_CONTROLLER.php';
		$login = new LOGIN;
		$login->formlogin();
	}
	else{
		if ($_POST['controlador'] === 'REGISTRO'){
			if ($_REQUEST['action'] === 'formregistrar'){
				include './CONTROLLER/REGISTRO_CONTROLLER.php';
				$registro = new REGISTRO;
				$registro->formregistrar();
			}
			else{
				include './CONTROLLER/REGISTRO_CONTROLLER.php';
				$registro = new REGISTRO;
				$registro->registrar();	
			}
		}
		else{
			include_once './CONTROLLER/LOGIN_CONTROLLER.php';
			$login = new LOGIN;
			$login->login();
		}
	}
}
else{

	if (!isset($_REQUEST['controlador'])){
		$controlador = 'MENU';
	}
	else{
		$controlador = $_REQUEST['controlador'];	
	}

	if (!isset($_REQUEST['action'])) {
		$action = 'MENU';
	}
	else{
		$action = $_REQUEST['action'];
	}

	include_once './CONTROLLER/'.$controlador.'_CONTROLLER.php';

	echo $controlador.'-'.$action;


	$objcontrolador = new $controlador;
	$objcontrolador->$action();

}
?>