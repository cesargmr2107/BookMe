<?php

class RegisterController{

	function __construct(){
		include './MODEL/UsuariosModel.php';
	}

	function registerForm(){
		include './VIEW/authentication/RegisterView.php';
		new RegisterView();
	}

	function register(){

		$usuario = new UsuariosModel();
		$usuario->patchEntity();

		$check = $usuario->ADD();
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

} //FIN DE CLASS
?>
