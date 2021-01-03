<?php

class AuthenticationController {	
	
	function __construct(){
		include './MODEL/UsuariosModel.php';
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
			include './VIEW/MessageView.php';
			$data["result"] = $result;
			$data["link"] = 'index.php';
			new MessageView($data);
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
			include './VIEW/MessageView.php';
			$data["result"] = $result;
			$data["link"] = 'index.php';
			new MessageView($data);
		}
		
    }
    
    function logout(){
		session_destroy();
		header('Location:index.php');
	}

}
?>