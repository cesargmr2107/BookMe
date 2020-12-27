<?php

include_once './BaseModel.php';

class ResponsablesModel extends BaseModel {
    
    function __construct (){
        
        // Call parent constructor
        parent::__construct();
               
        // Overwrite action codes
        
        $this->actionMsgs[parent::ADD_SUCCESS]["code"] = "555";
        $this->actionMsgs[parent::ADD_FAIL]["code"] = "555";
        
        $this->actionMsgs[parent::EDIT_SUCCESS]["code"] = "555";
        $this->actionMsgs[parent::EDIT_FAIL]["code"] = "555";
        
        $this->actionMsgs[parent::DELETE_SUCCESS]["code"] = "555";
        $this->actionMsgs[parent::EDIT_FAIL]["code"] = "555";
        
        $this->tableName = "RESPONSABLES_RECURSO";      
        
        $this->atributes = array( "LOGIN_RESPONSABLE" => "",
                                  "DIRECCION_RESPONSABLE" => "",
                                  "TELEFONO_RESPONSABLE" => "" );
  
        $this->primary_key = "LOGIN_RESPONSABLE";

        // Subscribe atributes to validations
        $this->checks = array (
            "LOGIN_RESPONSABLE" => array(
                "checkSize" => array('LOGIN_RESPONSABLE', 3, 15, '222', 'El login debe tener de 3 a 15 caracteres'),
                "checkRegex" => array('LOGIN_RESPONSABLE', '/^[a-z][a-z][a-z]+[0-9]*$/', '222', 'El login solo puede letras minúsculas y números, pero no puede empezar por números')
            ),
            "DIRECCION_RESPONSABLE" => array(
                "checkSize" => array('DIRECCION_RESPONSABLE', 10, 100, '222', 'La dirección debe tener entre 10 y 100 caracteres')
            ),
            "TELEFONO_RESPONSABLE" => array(
                "checkRegex" => array('TELEFONO_RESPONSABLE', '/^[6|7|8|9][0-9]{8}$/', '222', 'Solo se aceptan teléfonos españoles')
            )
        );
    }


}

?>