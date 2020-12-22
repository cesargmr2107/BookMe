<?php

include './BaseModel.php';

class CalendariosModel extends BaseModel {

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

        $this->atributes = array( "ID_CALENDARIO" => "",
                                  "NOMBRE_CALENDARIO" => "",
                                  "DESCRIPCION_CALENDARIO" => "",
                                  "FECHA_INICIO_CALENDARIO" => "",
                                  "FECHA_FIN_CALENDARIO" => "",
                                  "HORA_INICIO_CALENDARIO" => "", 
                                  "HORA_FIN_CALENDARIO" => "" );
    }


}

?>