<?php

include_once './MODEL/BaseModel.php';

class UsuariosModel extends BaseModel {

    // Define atributes
    public static $atributeNames = array(
        "LOGIN_USUARIO",
        "PASSWD_USUARIO",
        "NOMBRE_USUARIO",
        "EMAIL_USUARIO",
        "TIPO_USUARIO",
        "ES_ACTIVO"
    );

    function __construct (){
        
        // Call parent constructor
        parent::__construct();
               
        // Overwrite action codes
        
        $this->actionMsgs[parent::ADD_SUCCESS]["code"] = "111";
        $this->actionMsgs[parent::ADD_FAIL]["code"] = "111";
        
        $this->actionMsgs[parent::EDIT_SUCCESS]["code"] = "111";
        $this->actionMsgs[parent::EDIT_FAIL]["code"] = "111";
        
        $this->actionMsgs[parent::DELETE_SUCCESS]["code"] = "111";
        $this->actionMsgs[parent::EDIT_FAIL]["code"] = "111";
        
        // Set different user types
        $userTypes = array("NORMAL", "ADMINISTRADOR", "RESPONSABLE");
        
        $this->tableName = "USUARIOS";      
          
        $this->primary_key = "LOGIN_USUARIO";

        // Subscribe atributes to validations
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
            ),
            "EMAIL_USUARIO" => array(
                "checkRegex" => array('EMAIL_USUARIO', '/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix', '222', 'El correo electrónico es incorrecto')
            ),
            "TIPO_USUARIO" => array(
                "checkEnum" => array('TIPO_USUARIO', $userTypes, '222', 'El tipo de usuario no es válido')
            ),
            "ES_ACTIVO" => array(
                "checkYesOrNo" => array('ES_ACTIVO', '222', 'El usuario solo puede ser SI o NO activo')
            )
        );
    }

    public function checkCredentials(){

        $error = array("code" => '222', "msg" => 'Credenciales de usuario incorrectas');
        
        if($this->atributes["LOGIN_USUARIO"] == ""){
            return $error;
        }
        
        $userSearch= $this->SEARCH();
        
        if(!count($userSearch)){
            return $error;
        }

        $this->atributes = $userSearch[0];

        return true;
    }

}

?>