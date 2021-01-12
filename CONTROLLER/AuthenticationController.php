<?php

class AuthenticationController {	
	
	function __construct(){
		include './MODEL/UsuariosModel.php';
	}

	function redirectToMsg($data){

		// Encode data to JSON
		$jsonString = json_encode($data);

		// Encrypt JSON into token
		$ivlen = openssl_cipher_iv_length(self::$cipherMethod);
		$iv = openssl_random_pseudo_bytes($ivlen);
		$token = openssl_encrypt($jsonString, self::$cipherMethod, self::$cipherKey, $options = 0, $iv, $tag);

		// Store iv and tag for decryption later
		$_SESSION["iv"] = $iv;
		$_SESSION["tag"] = $tag;

		// Redirect
		header("Location: index.php?token=$token");
	}

	function loginForm(){
		include './VIEW/authentication/LoginView.php';
		new LoginView();
	}

	function login(){

		$usuario = new UsuariosModel();
		$usuario->patchEntity();

		$result = $usuario->checkCredentials();
		if ($result === true){
			session_start(); 
			$_SESSION['LOGIN_USUARIO'] = $usuario->get("LOGIN_USUARIO");
			$_SESSION['TIPO_USUARIO'] = $usuario->get("TIPO_USUARIO");
			header('location: index.php');
		}
		else{
			$data["result"] = $result;
			$data["link"] = 'index.php';
			$this->redirectToMsg($data);
		}
		
    }
    
    function registerForm(){
		include './VIEW/authentication/RegisterView.php';
		new RegisterView();
	}

	function register(){

		$usuario = new UsuariosModel();
		$usuario->patchEntity();

		$result = $usuario->ADD();
		if ($result["code"] === "111"){
			session_start(); 
			$_SESSION['LOGIN_USUARIO'] = $usuario->get("LOGIN_USUARIO");
			$_SESSION['TIPO_USUARIO'] = $usuario->get("TIPO_USUARIO");
			header('location: index.php');
		}
		else{
			$data["result"] = $result;
			$data["link"] = 'index.php';
			$this->redirectToMsg($data);
		}
		
    }
    
    function logout(){
		session_destroy();
		header('Location:index.php');
	}

}
?>