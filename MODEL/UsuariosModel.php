<?php

include './BaseModel.php';

class UsuariosModel extends BaseModel {


    function __construct (){

        parent::__construct();

        $this->msgs[parent::ADD_SUCCESS]["code"] = "111";
        $this->msgs[parent::ADD_FAIL]["code"] = "111";

        $this->msgs[parent::EDIT_SUCCESS]["code"] = "111";
        $this->msgs[parent::EDIT_FAIL]["code"] = "111";

        $this->msgs[parent::DELETE_SUCCESS]["code"] = "111";
        $this->msgs[parent::EDIT_FAIL]["code"] = "111";

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