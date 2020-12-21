<?php

include './BaseModel.php';

class UsuariosModel extends BaseModel {

    function __construct (){

        $this->tableName = "USUARIOS";

        $this->primary_key = "LOGIN_USUARIO";

        $this->atributes = array( "LOGIN_USUARIO" => "",
                                  "PASSWD_USUARIO" => "",
                                  "NOMBRE_USUARIO" => "",
                                  "EMAIL_USUARIO" => "",
                                  "TIPO_USUARIO" => "",
                                  "ES_ACTIVO" => "" );
    }


}

?>