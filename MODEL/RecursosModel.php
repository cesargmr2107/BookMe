<?php

include_once './BaseModel.php';

class RecursosModel extends BaseModel {
    
    function __construct (){
        
        // Call parent constructor
        parent::__construct();
               
        // Overwrite action codes
        
        $this->actionMsgs[parent::ADD_SUCCESS]["code"] = "777";
        $this->actionMsgs[parent::ADD_FAIL]["code"] = "777";
        
        $this->actionMsgs[parent::EDIT_SUCCESS]["code"] = "777";
        $this->actionMsgs[parent::EDIT_FAIL]["code"] = "777";
        
        $this->actionMsgs[parent::DELETE_SUCCESS]["code"] = "777";
        $this->actionMsgs[parent::EDIT_FAIL]["code"] = "777";
        
        
        $this->tableName = "RECURSOS";      
        
        $this->atributes = array( "ID_RECURSO" => "",
                                  "NOMBRE_RECURSO" => "",
                                  "DESCRIPCION_RECURSO" => "",
                                  "TARIFA_RECURSO" => "",
                                  "RANGO_TARIFA_RECURSO" => "",
                                  "ID_CALENDARIO" => "",
                                  "LOGIN_RESPONSABLE" => "" );
        
        $this->primary_key = "ID_RECURSO";
        
        // Set different user types
        $priceRanges = array("HORA", "DIA", "SEMANA", "MES");

        // Subscribe atributes to validations
        $this->checks = array (
            "ID_RECURSO" => array(
                "checkAutoKey" => array('ID_RECURSO', '222', 'El id del recurso (gestionado por el sistema) es un entero'),
            ),
            "NOMBRE_RECURSO" => array(
                "checkSize" => array('NOMBRE_RECURSO', 4, 40, '222', 'El nombre del recurso debe tener entre 4 y 40 caracteres')
            ),
            "DESCRIPCION_RECURSO" => array(
                "checkSize" => array('DESCRIPCION_RECURSO', 10, 200, '222', 'La descripción debe tener entre 10 y 200 caracteres'),
            ),
            "TARIFA_RECURSO" => array(
                "checkNumeric" => array('TARIFA_RECURSO', '222', 'La tarifa del recurso debe ser un valor numérico'),
                "checkRange" => array('TARIFA_RECURSO', 0, 1000, '222', 'La tarifa del recurso debe estar entre 0€ y 1000€')
            ),
            "RANGO_TARIFA_RECURSO" => array(
                "checkEnum" => array('RANGO_TARIFA_RECURSO', $priceRanges, '222', 'El rango de tarifa del recurso no es válido')
            ),
            /*"ID_CALENDARIO" => array(
                "checkYesOrNo" => array('ES_ACTIVO', '222', 'El usuario solo puede ser SI o NO activo')
            ),
            "LOGIN_RESPONSABLE" => array(
                "checkYesOrNo" => array('ES_ACTIVO', '222', 'El usuario solo puede ser SI o NO activo')
            )*/
        );
    }


}

?>