<?php

include './BaseModel.php';

class UsuariosModel extends BaseModel {


    function __construct (){

        parent::__construct();


        // Overwrite action codes

        $this->actionMsgs[parent::ADD_SUCCESS]["code"] = "111";
        $this->actionMsgs[parent::ADD_FAIL]["code"] = "111";

        $this->actionMsgs[parent::EDIT_SUCCESS]["code"] = "111";
        $this->actionMsgs[parent::EDIT_FAIL]["code"] = "111";

        $this->actionMsgs[parent::DELETE_SUCCESS]["code"] = "111";
        $this->actionMsgs[parent::EDIT_FAIL]["code"] = "111";

        // Set validations
        $this->checks = array (
            "LOGIN_USUARIO" => array(
                "checkSize" => array('LOGIN_USUARIO', 3, 15, '222', 'El login debe tener de 3 a 15 caracteres'),
                "checkRegex" => array('LOGIN_USUARIO', '/^[a-z][a-z][a-z]+[0-9]*$/', '222', 'El login solo puede letras minúsculas y números, pero no puede empezar por números')
            ),
            "PASSWD_USUARIO" => array(
                "checkSize" => array('PASSWD_USUARIO', 32, 32, '222', 'La contraseña se debe guardar como un hash MD5')
            ),
            "NOMBRE_USUARIO" => array(
                "checkSize" => array('NOMBRE_USUARIO', 8, 60, '222', 'El nombre debe tener entre 8 y 60 caracteres'),
                "checkRegex" => array('NOMBRE_USUARIO', '/^[a-zA-Z ]+$/', '222', 'El nombre solo puede tener letras y espacios')
            )
        );

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