<?php

include_once './BaseModel.php';

class CalendariosModel extends BaseModel {

    function __construct (){

        // Call parent constructor
        parent::__construct();
        
        // Overwrite action codes
        $this->actionMsgs[parent::ADD_SUCCESS]["code"] = "333";
        $this->actionMsgs[parent::ADD_FAIL]["code"] = "333";

        $this->actionMsgs[parent::EDIT_SUCCESS]["code"] = "333";
        $this->actionMsgs[parent::EDIT_FAIL]["code"] = "333";

        $this->actionMsgs[parent::DELETE_SUCCESS]["code"] = "333";
        $this->actionMsgs[parent::EDIT_FAIL]["code"] = "333";

        // Define DB table
        $this->tableName = "CALENDARIOS_DE_USO";

        // Define primary key
        $this->primary_key = "ID_CALENDARIO";

        // Define atributes
        $this->atributes = array( "ID_CALENDARIO" => "",
                                  "NOMBRE_CALENDARIO" => "",
                                  "DESCRIPCION_CALENDARIO" => "",
                                  "FECHA_INICIO_CALENDARIO" => "",
                                  "FECHA_FIN_CALENDARIO" => "",
                                  "HORA_INICIO_CALENDARIO" => "", 
                                  "HORA_FIN_CALENDARIO" => "" );

        // Subscribe atributes to validations                          
        $this->checks = array (
            "ID_CALENDARIO" => array(
                "checkAutoKey" => array('ID_CALENDARIO', '222', 'El id del calendario (gestionado por el sistema) es un entero'),
            ),
            "NOMBRE_CALENDARIO" => array(
                "checkSize" => array('NOMBRE_CALENDARIO', 6, 40, '222', 'El nombre debe tener entre 6 y 40 caracteres'),
            ),
            "DESCRIPCION_CALENDARIO" => array(
                "checkSize" => array('NOMBRE_CALENDARIO', 10, 200, '222', 'La descripción debe tener entre 10 y 200 caracteres'),
            ),
            "FECHA_INICIO_CALENDARIO" => array(
                "checkDate" => array('FECHA_INICIO_CALENDARIO', '222', 'La fecha debe tener el formato yyyy-mm-dd')
            ),
            "FECHA_FIN_CALENDARIO" => array( 
                "checkDate" => array('FECHA_FIN_CALENDARIO', '222', 'La fecha debe tener el formato yyyy-mm-dd'),
                "checkDateInterval" => array('FECHA_INICIO_CALENDARIO', 'FECHA_FIN_CALENDARIO', '222', 'La fecha de inicio debe ser anterior a la fecha de fin')
            ),
            "HORA_INICIO_CALENDARIO" => array(
                "checkTime" => array('HORA_INICIO_CALENDARIO', '222', 'La hora debe tener el formato hh:mm')
            ),
            "HORA_FIN_CALENDARIO" => array( 
                "checkTime" => array('HORA_FIN_CALENDARIO', '222', 'La hora debe tener el formato hh:mm')
            ),
        );

    }


}

?>