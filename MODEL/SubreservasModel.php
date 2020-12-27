<?php

include_once './BaseModel.php';

class SubreservasModel extends BaseModel {
    
    function __construct (){
        
        // Call parent constructor
        parent::__construct();
               
        // Overwrite action codes
        
        $this->actionMsgs[parent::ADD_SUCCESS]["code"] = "1111";
        $this->actionMsgs[parent::ADD_FAIL]["code"] = "1111";
        
        $this->actionMsgs[parent::EDIT_SUCCESS]["code"] = "1111";
        $this->actionMsgs[parent::EDIT_FAIL]["code"] = "1111";
        
        $this->actionMsgs[parent::DELETE_SUCCESS]["code"] = "1111";
        $this->actionMsgs[parent::EDIT_FAIL]["code"] = "1111";
        
    
        $this->tableName = "SUBRESERVAS";      
        
        $this->atributes = array( "ID_RESERVA" => "",
                                  "ID_SUBRESERVA" => "",
                                  "FECHA_INICIO_SUBRESERVA" => "",
                                  "FECHA_FIN_SUBRESERVA" => "",
                                  "HORA_INICIO_SUBRESERVA" => "",
                                  "HORA_FIN_SUBRESERVA" => "",
                                  "COSTE_SUBRESERVA" => "" );
          
        $this->primary_key = array("parentKey" => "ID_RESERVA", "weakKey" => "ID_SUBRESERVA");

        // Subscribe atributes to validations
        $this->checks = array (
            "FECHA_INICIO_SUBRESERVA" => array(
                "checkDate" => array('FECHA_INICIO_SUBRESERVA', '222', 'La fecha debe tener el formato yyyy-mm-dd')
            ),
            "FECHA_FIN_SUBRESERVA" => array(
                "checkDate" => array('FECHA_FIN_SUBRESERVA', '222', 'La fecha debe tener el formato yyyy-mm-dd'),
                "checkDateInterval" => array('FECHA_INICIO_SUBRESERVA', 'FECHA_FIN_SUBRESERVA', '222', 'La fecha de fin debe ser posterior a la fecha de inicio')
            ),
            "HORA_INICIO_SUBRESERVA" => array(
                "checkTime" => array('HORA_INICIO_SUBRESERVA', '222', 'La hora debe tener el formato hh:mm')
            ),
            "HORA_FIN_SUBRESERVA" => array(
                "checkTime" => array('HORA_FIN_SUBRESERVA', '222', 'La hora debe tener el formato hh:mm')
            ),
            "COSTE_SUBRESERVA" => array(
                "checkNumeric" => array('COSTE_SUBRESERVA', '222', 'El coste de la reserva debe ser un valor numérico'),
            )
        );
    }


}

?>