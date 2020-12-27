<?php

include_once './BaseModel.php';

class ReservasModel extends BaseModel {
    
    function __construct (){
        
        // Call parent constructor
        parent::__construct();
               
        // Overwrite action codes
        
        $this->actionMsgs[parent::ADD_SUCCESS]["code"] = "999";
        $this->actionMsgs[parent::ADD_FAIL]["code"] = "999";
        
        $this->actionMsgs[parent::EDIT_SUCCESS]["code"] = "999";
        $this->actionMsgs[parent::EDIT_FAIL]["code"] = "999";
        
        $this->actionMsgs[parent::DELETE_SUCCESS]["code"] = "999";
        $this->actionMsgs[parent::EDIT_FAIL]["code"] = "999";
        
        // Set booking status
        $bookingStatus = array("PENDIENTE", "ACEPTADA", "RECHAZADA", "CANCELADA", "RECURSO_USADO", "RECURSO_NO_USADO");
        
        $this->tableName = "RESERVAS";      
        
        $this->atributes = array( "ID_RESERVA" => "",
                                  "LOGIN_USUARIO" => "",
                                  "ID_RECURSO" => "",
                                  "FECHA_SOLICITUD_RESERVA" => "",
                                  "FECHA_RESPUESTA_RECURSO" => "",
                                  "MOTIVO_RECHAZO_RESERVA" => "",
                                  "ESTADO_RESERVA" => "",
                                  "COSTE_RESERVA" => "" );
  
    
        $this->defaultValues = array( "ESTADO_RESERVA" => "PENDIENTE" );
        
        $this->nullAtributes = array ("FECHA_RESPUESTA_RECURSO", "MOTIVO_RECHAZO_RESERVA");

        $this->primary_key = "ID_RESERVA";

        // Subscribe atributes to validations
        $this->checks = array (
            "ID_RESERVA" => array(
                "checkAutoKey" => array('ID_RESERVA', '222', 'El id de la reserva (gestionado por el sistema) es un entero'),
            ),
            "LOGIN_USUARIO" => array(
                "checkSize" => array('LOGIN_USUARIO', 3, 15, '222', 'El login debe tener de 3 a 15 caracteres'),
                "checkRegex" => array('LOGIN_USUARIO', '/^[a-z][a-z][a-z]+[0-9]*$/', '222', 'El login solo puede letras minúsculas y números, pero no puede empezar por números')
            ),
            "ID_RECURSO" => array(
                "checkAutoKey" => array('ID_RECURSO', '222', 'El id de la reserva (gestionado por el sistema) es un entero'),
            ),
            "FECHA_SOLICITUD_RESERVA" => array(
                "checkDate" => array('FECHA_SOLICITUD_RESERVA', '222', 'La fecha debe tener el formato yyyy-mm-dd')
            ),
            "FECHA_RESPUESTA_RECURSO" => array( 
                "checkDate" => array('FECHA_RESPUESTA_RECURSO', '222', 'La fecha debe tener el formato yyyy-mm-dd'),
                "checkDateInterval" => array('FECHA_SOLICITUD_RESERVA', 'FECHA_RESPUESTA_RECURSO', '222', 'La fecha de respuesta debe ser posterior a la fecha de solicitud')
            ),
            "MOTIVO_RECHAZO_RESERVA" => array(
                "checkSize" => array('MOTIVO_RECHAZO_RESERVA', 0, 200, '222', 'El motivo de rechazo no puede superar los 200 caracteres'),
            ),
            "ESTADO_RESERVA" => array( 
                "checkEnum" => array('ESTADO_RESERVA', $bookingStatus, '222', 'El estado de la reserva no es válido')
            ),
            "COSTE_RESERVA" => array(
                "checkNumeric" => array('COSTE_RESERVA', '222', 'El coste de la reserva debe ser un valor numérico'),
            )
        );
    }


}

?>