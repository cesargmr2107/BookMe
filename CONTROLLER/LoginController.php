<?php

class LoginController {	
	
	function __construct(){
		include './MODEL/UsuariosModel.php';
	}

	function loginForm(){
		include './VIEW/LoginView.php';
		new LoginView();
	}

	function login(){

		$usuario = new UsuariosModel();
		$usuario->patchEntity();

		$check = $usuario->checkCredentials();
		if ($check === true){
			session_start(); 
			$_SESSION['LOGIN_USUARIO'] = $usuario->get("LOGIN_USUARIO");
			$_SESSION['TIPO_USUARIO'] = $usuario->get("TIPO_USUARIO");
			header('location: index.php');
		}
		else{
			include './VIEW/MessageView.php';
			MessageView::withLink($check, 'index.php');
		}
		
	}	

}
?>