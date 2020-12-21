<?php

include './UsuariosModel.php';

$atributes = array( "LOGIN_USUARIO" => "admin",
                    "PASSWD_USUARIO" => "",
                    "NOMBRE_USUARIO" => "",
                    "EMAIL_USUARIO" => "",
                    "TIPO_USUARIO" => "",
                    "ES_ACTIVO");

$usuario = new UsuariosModel($atributes);

$usuario->SEARCH();

?>