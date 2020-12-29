<?php

function conectarBD(){

	$conexion = new mysqli('localhost', 'pma', 'iu', 'EJEMPLO4') or die('fallo conexion');
	return $conexion;

}

function isAuthenticated(){
	return isset($_SESSION['LOGIN_USUARIO']) && $_SESSION['LOGIN_USUARIO'] != '';	
}

?>