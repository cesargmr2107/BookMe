<?php

include_once './MODEL/BaseModel.php';

class SubreservasModel extends BaseModel {
    
    // Define atributes
    public static $atributeNames = array(
        "ID_RESERVA",
        "ID_SUBRESERVA",
        "FECHA_INICIO_SUBRESERVA",
        "FECHA_FIN_SUBRESERVA",
        "HORA_INICIO_SUBRESERVA",
        "HORA_FIN_SUBRESERVA",
        "COSTE_SUBRESERVA"
    );

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
                  
        $this->primary_key = array("parentKey" => "ID_RESERVA", "weakKey" => "ID_SUBRESERVA");

        // Subscribe atributes to validations
        $this->checks = array (
            "ID_RESERVA" => array(
                "checkIsForeignKey" => array('ID_RESERVA', 'ID_RESERVA', 'ReservasModel', '222', 'El id de la reserva es desconocido')
            ),
            "FECHA_INICIO_SUBRESERVA" => array(
                "checkDate" => array('FECHA_INICIO_SUBRESERVA', '222', 'La fecha debe tener el formato dd-mm-yyyy')
            ),
            "FECHA_FIN_SUBRESERVA" => array(
                "checkDate" => array('FECHA_FIN_SUBRESERVA', '222', 'La fecha debe tener el formato dd-mm-yyyy'),
                "checkDateInterval" => array('FECHA_INICIO_SUBRESERVA', 'FECHA_FIN_SUBRESERVA', '222', 'La fecha de fin debe ser posterior a la fecha de inicio'),
                "checkNoOverlappings" => array('', '222', 'El intervalo de reserva coincide con una reserva existente')
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

    public function checkNoOverlappings($id_recurso, $errorCode, $errorMsg){
        
        if($id_recurso == NULL){
            // Get resource id
            include_once './MODEL/ReservasModel.php';
            $atributesToSet = array ("ID_RESERVA" => $this->atributes["ID_RESERVA"]);
            $reservaSearch = new ReservasModel();
            $reservaSearch->setAtributes($atributesToSet);
            $reserva = $reservaSearch->SEARCH()[0];
        }
        
        // Build query
        $fechaInicio = $this->atributes["FECHA_INICIO_SUBRESERVA"];
        $fechaFin = $this->atributes["FECHA_FIN_SUBRESERVA"];
        $horaInicio = $this->atributes["HORA_INICIO_SUBRESERVA"];
        $horaFin = $this->atributes["HORA_FIN_SUBRESERVA"];
        $query = "SELECT * FROM RESERVAS R, SUBRESERVAS S " .
                 "WHERE R.ID_RESERVA = S.ID_RESERVA AND R.ID_RECURSO = " . $reserva["ID_RECURSO"] . 
                 " AND ( R.ESTADO_RESERVA = 'ACEPTADA' OR R.ID_RESERVA = " . $reserva["ID_RESERVA"]. ") AND (" .
                 "( S.FECHA_INICIO_SUBRESERVA BETWEEN '" . $fechaInicio . "' AND '" . $fechaFin . "' ) OR " .
                 "( S.FECHA_FIN_SUBRESERVA BETWEEN '" . $fechaInicio . "' AND '" . $fechaFin . "' ) ) AND ( " .
                 "( S.HORA_INICIO_SUBRESERVA > '" . $horaInicio . "' AND S.HORA_INICIO_SUBRESERVA < '" . $horaFin . "' ) OR " .
                 "( S.HORA_FIN_SUBRESERVA > '" . $horaInicio . "' AND S.HORA_FIN_SUBRESERVA < '" . $horaFin . "' ) )";

        // DEBUG: Check query and result
        // echo "<p>" . $query . "</p>";
        // echo '<pre style="color:red">' . var_export($this->SEARCH($query), true) . '</pre>';


        // Do check
        $noOverlappings = !count($this->SEARCH($query));
        if($noOverlappings){
            return true;
        }else{
            return array("code" => $errorCode, "msg" => $errorMsg); 
        }
    }

}

?>