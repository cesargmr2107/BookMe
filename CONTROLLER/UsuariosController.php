<?php

class UsuariosController {	
	
	function __construct(){
        include './MODEL/UsuariosModel.php';
        /*foreach (glob("./VIEW/calendarios/*.php") as $filename)
        {
            include_once $filename;
        }*/
	}

	function logout(){
		session_destroy();
		header('Location:index.php');
	}


}
?>