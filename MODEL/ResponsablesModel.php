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
        
        $this->actionCodes[parent::ADD_SUCCESS]["code"] = "AC141";
        $this->actionCodes[parent::ADD_FAIL]["code"] = "AC041";
        
        $this->actionCodes[parent::EDIT_SUCCESS]["code"] = "AC142";
        $this->actionCodes[parent::EDIT_FAIL]["code"] = "AC042";
        
        $this->actionCodes[parent::DELETE_SUCCESS]["code"] = "AC143";
        $this->actionCodes[parent::EDIT_FAIL]["code"] = "AC043";
        
        $this->tableName = "RESPONSABLES_RECURSO";      
        
        $this->primary_key = "LOGIN_RESPONSABLE";

        // Subscribe atributes to validations
        $this->checks = array (
            "LOGIN_RESPONSABLE" => array(
                "checkIsForeignKey" => array('LOGIN_RESPONSABLE', 'LOGIN_USUARIO', 'UsuariosModel', 'AT401')
            ),
            "DIRECCION_RESPONSABLE" => array(
                "checkSize" => array('DIRECCION_RESPONSABLE', 10, 60, 'AT411'),
                "checkRegex" => array('DIRECCION_RESPONSABLE', '/^[a-zA-Z0-9/&ºª ]+$/', 'AT412')
            ),
            "TELEFONO_RESPONSABLE" => array(
                "checkRegex" => array('TELEFONO_RESPONSABLE', '/^[6|7|8|9][0-9]{8}$/', 'AT421')
            )
        );

        $this->checksForDelete = array(
            "LOGIN_RESPONSABLE" => array(
                "checkNoAssoc" => array('LOGIN_RESPONSABLE', "RecursosModel", '222', 'No se puede borrar un responsable con recursos asociados')
            )
        );
    }


    public function SHOW(){
        $result = parent::SHOW();

        include_once './MODEL/RecursosModel.php';
		$resourcesSearch = new RecursosModel();
        $query = "SELECT ID_RECURSO, NOMBRE_RECURSO FROM RECURSOS WHERE LOGIN_RESPONSABLE LIKE '%" . $this->atributes["LOGIN_RESPONSABLE"] . "%'";
        $result["resources"] = $resourcesSearch->SEARCH($query);

        return $result;
    }
}

?>