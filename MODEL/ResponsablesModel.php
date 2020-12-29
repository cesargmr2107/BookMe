<?php

include_once './MODEL/BaseModel.php';

class ResponsablesModel extends BaseModel {
    
    // Define atributes
    public static $atributeNames = array(
        "LOGIN_RESPONSABLE",
        "DIRECCION_RESPONSABLE",
        "TELEFONO_RESPONSABLE"
    );

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
        
        $this->primary_key = "LOGIN_RESPONSABLE";

        // Subscribe atributes to validations
        $this->checks = array (
            "LOGIN_RESPONSABLE" => array(
                "checkIsForeignKey" => array('LOGIN_RESPONSABLE', 'LOGIN_USUARIO', 'UsuariosModel', '222', 'El usuario responsable es desconocido')
            ),
            "DIRECCION_RESPONSABLE" => array(
                "checkSize" => array('DIRECCION_RESPONSABLE', 10, 100, '222', 'La dirección debe tener entre 10 y 100 caracteres')
            ),
            "TELEFONO_RESPONSABLE" => array(
                "checkRegex" => array('TELEFONO_RESPONSABLE', '/^[6|7|8|9][0-9]{8}$/', '222', 'Solo se aceptan teléfonos españoles')
            )
        );

        $this->checksForDelete = array(
            "LOGIN_RESPONSABLE" => array(
                "checkNoAssoc" => array('LOGIN_RESPONSABLE', "RecursosModel", '222', 'No se puede borrar un responsable con recursos asociados')
            )
        );
    }


}

?>