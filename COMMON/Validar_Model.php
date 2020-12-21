<?php

class Validar{


	function __construct(){
	
	}
	function __destruct(){
}
//comprueba que un string no sobrepase la longitud maxima en base a un valor establecido
//si es correcta devuelve true
//si la longitud del string es mas larga que la del string devuelve false, el string no es valido
function Longitud_maxima($string,$valor){
	if(strlen($string)<$valor){
		return true;
	}else{
		return false;
	}
}

//comprueba que un string tenga la longitud minima en base al valor establecido
//si la longitud del string es correcta devuelve true
//si la longitud del string es mas corta que la del string devuelve false, el string no es valido
function Longitud_minima($string,$valor){
	if(strlen($string)<$valor){
		return false;
	}else{
		return true;
	}
}
//comprueba si un string tiene solo letras
//devuelve true si es corrrecto false en caso contrario
function Es_string($string){
	if (preg_match('/[^a-zA-Z]/',$string)){
		return false;
	}else{
		return true;
	}
}
//comprueba si un string tiene solo letras y números
//devuelve true si es corrrecto false en caso contrario
function Es_alfanumerico($string){
	if (preg_match('/[^a-zA-Z0-9]/',$string)){
		return false;
	}else{
		return true;
}
}
//comprueba si un string tiene solo numeros
//devuelve true si es corrrecto false en caso contrario
function Es_numerico($string){
	if (preg_match('/[^0-9]/',$string)){
		return false;
	}else{
		return true;
	}
}

//comprueba si un string tiene solo letras numeros y espacios
//devuelve true si es corrrecto false en caso contrario
function Es_string_espacios($string){
	if (preg_match('/[^a-zA-Z0-9\s]/',$string)){
		return false;
	}else{
		return true;
	}
}

function No_vacio($string){
	if(!preg_match('/[a-zA-Z0-9]+/', $string) || $string==''){
			return false;
	}else{
		return true;
	}
}


function Formato_email($string){
	 if(filter_var($string, FILTER_VALIDATE_EMAIL)){
	 	return true;
	 }else{
	 	return false;
	 }
}

function Formato_dni($dni){
	$letra = substr($dni, -1);
	$numeros = substr($dni, 0, -1);
	if ( substr("TRWAGMYFPDXBNJZSQVHLCKE", $numeros%23, 1) == $letra && strlen($letra) == 1 && strlen ($numeros) == 8 ){
		return true;
	}else{
		return false;
	}
}
function Formato_fecha($fecha){
	if(!preg_match('/([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))/', $fecha)){
			return false;
	}else{
		return true;
	}
}


}