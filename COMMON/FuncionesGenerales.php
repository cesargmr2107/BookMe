<?php

function conectarBD(){

	$conexion = new mysqli('localhost', 'pma', 'iu', 'EJEMPLO4') or die('fallo conexion');
	return $conexion;

}

function Autenticacion(){

	if ((isset($_SESSION['login_usuario'])) && ($_SESSION['login_usuario']!='')) {
		return true;
	}
	else{
		return false;
	}
	
}

?>