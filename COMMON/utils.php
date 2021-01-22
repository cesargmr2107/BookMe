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

function encrypt($string){
	
	// Set method and key
	$cipherMethod = "aes-128-gcm";
	$cipherKey = '6v9y$B&E)H@MbQeThWmZq4t7w!z%C*F-JaNdRfUjXn2r5u8x/A?D(G+KbPeShVkY';
	
	// Encrypt string into secret
	$ivlen = openssl_cipher_iv_length($cipherMethod);
	$iv = openssl_random_pseudo_bytes($ivlen);
	$secret = openssl_encrypt($string, $cipherMethod, $cipherKey, $options = 0, $iv, $tag);
	
	// Store iv and tag for decryption later
	$_SESSION["iv"] = $iv;
	$_SESSION["tag"] = $tag;

	return $secret;
}

function decrypt($secret){

	// If possible, decrypt
	if( $secret != '' && array_key_exists("iv", $_SESSION) && array_key_exists("tag", $_SESSION) ){
		
		// Set method and key
		$cipherMethod = "aes-128-gcm";
		$cipherKey = '6v9y$B&E)H@MbQeThWmZq4t7w!z%C*F-JaNdRfUjXn2r5u8x/A?D(G+KbPeShVkY';

		// Get secret, iv and tag
		$secret = str_replace(" ", "+", $secret);
		$iv = $_SESSION["iv"];
		$tag = $_SESSION["tag"];

		// Decrypt json and convert to assoc array which will be passed to view 
		return openssl_decrypt($secret, $cipherMethod, $cipherKey, $options = 0, $iv, $tag);
	}
	
	return false;
}

?>