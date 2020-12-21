<?php

include './BaseModel.php';

class UsuariosModel extends BaseModel {

    function __construct ($atributes){
        parent::__construct($atributes);
        $this->tableName = "USUARIOS";
    }

    /*function SEARCH()
    {
        parent::SEARCH();
    }*/

}

$atributes = array( "LOGIN_USUARIO" => "admin",
                    "PASSWD_USUARIO" => "",
                    "NOMBRE_USUARIO" => "",
                    "EMAIL_USUARIO" => "",
                    "TIPO_USUARIO" => "",
                    "ES_ACTIVO");

$usuario = new UsuariosModel($atributes);

$usuario->SEARCH();

?>