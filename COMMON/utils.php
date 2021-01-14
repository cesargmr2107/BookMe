<?php

function conectarBD(){

	$conexion = new mysqli('localhost', 'pma', 'iu', 'EJEMPLO4') or die('fallo conexion');
	return $conexion;

}

function isAuthenticated(){
	return isset($_SESSION['LOGIN_USUARIO']) && $_SESSION['LOGIN_USUARIO'] != '';	
}

function isAdminUser(){
	return $_SESSION["TIPO_USUARIO"] === "ADMINISTRADOR";
}

function isNormalUser(){
	return $_SESSION["TIPO_USUARIO"] === "NORMAL";
}

function isRespUser(){
	return $_SESSION["TIPO_USUARIO"] === "RESPONSABLE";
}

?>