<?php

include_once './MODEL/BaseModel.php';

class RecursosModel extends BaseModel {
    
    // Define atributes
    public static $atributeNames = array(
        "ID_RECURSO",
        "NOMBRE_RECURSO",
        "DESCRIPCION_RECURSO",
        "TARIFA_RECURSO",
        "RANGO_TARIFA_RECURSO",
        "ID_CALENDARIO",
        "LOGIN_RESPONSABLE"
    );

    // Define which atributes will be selected in search
    protected static $atributesForSearch = array (  "ID_RECURSO",
                                                    "NOMBRE_RECURSO",
                                                    "TARIFA_RECURSO",
                                                    "LOGIN_RESPONSABLE");

    public static $priceRanges = array("HORA", "DIA", "SEMANA", "MES");

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

        $this->primary_key = "ID_RECURSO";
        
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
                "checkEnum" => array('RANGO_TARIFA_RECURSO', static::$priceRanges, '222', 'El rango de tarifa del recurso no es válido')
            ),
            "ID_CALENDARIO" => array(
                "checkIsForeignKey" => array('ID_CALENDARIO', 'ID_CALENDARIO', 'CalendariosModel', '222', 'El id del calendario es desconocido')
            ),
            "LOGIN_RESPONSABLE" => array(
                "checkIsForeignKey" => array('LOGIN_RESPONSABLE', 'LOGIN_RESPONSABLE', 'ResponsablesModel', '222', 'El usuario responsable es desconocido')
            )
        );

        $this->checksForDelete = array(
            "ID_RECURSO" => array(
                "checkNoReservas" => array('222', 'No se pueden borrar recursos con reservas activas asociadas')
            )
        );
    }

    public function checkNoReservas($errorCode, $errorMsg){
        include_once './MODEL/ReservasModel.php';
        $booking = new ReservasModel();
        $query = "SELECT * FROM RESERVAS WHERE ID_RECURSO = " . $this->atributes["ID_RECURSO"] .
                 " and ESTADO_RESERVA IN ('PENDIENTE','ACEPTADA')";
        $hasNoAssoc = !count($booking->SEARCH($query));
        if($hasNoAssoc){
            return true;
        }else{
            return array("code" => $errorCode, "msg" => $errorMsg);
        }
    }

    public function SHOW(){
        $result = parent::SHOW();
        
        $query = "SELECT A.NOMBRE_RECURSO, B.FECHA_INICIO_SUBRESERVA, B.FECHA_FIN_SUBRESERVA, B.HORA_INICIO_SUBRESERVA, B.HORA_FIN_SUBRESERVA " .
                 "FROM RECURSOS A, SUBRESERVAS B, RESERVAS C " .
                 "WHERE A.ID_RECURSO = C.ID_RECURSO AND B.ID_RESERVA = C.ID_RESERVA AND " .
                 "A.ID_RECURSO = '" . $this->atributes["ID_RECURSO"] . "'"; 
        
        $result["events"] = $this->SEARCH($query);

        return $result;
    }

    public function STATS($formStartDate = null, $formEndDate = null){

        // Get this resource's calendar ID
        $result = $this->SEARCH(
            "SELECT ID_CALENDARIO FROM RECURSOS WHERE ID_RECURSO = '" . $this->atributes["ID_RECURSO"] . "'"
        )[0];

        // Get total available hours
        include_once './MODEL/CalendariosModel.php';
        $calendarSearch = new CalendariosModel();
        $calendar = $calendarSearch->SEARCH(
            "SELECT * FROM CALENDARIOS_DE_USO WHERE ID_CALENDARIO = '" . $result['ID_CALENDARIO'] . "'"
        )[0];

        // Set dates if null and transform to DB format if necessary
        if($formStartDate === null){
            $formStartDate = $calendar["FECHA_INICIO_CALENDARIO"];
        } else {
            $d = DateTime::createFromFormat('d/m/Y', $formStartDate);
            $formStartDate = date_format($d,'Y-m-d');
        }
        if($formEndDate === null){
            $formEndDate = $calendar["FECHA_FIN_CALENDARIO"];
        } else {
            $d = DateTime::createFromFormat('d/m/Y', $formEndDate);
            $formEndDate = date_format($d,'Y-m-d');
        }

        $totalAvailableHours = $this->calcHours(
            $formStartDate,
            $formEndDate,
            $calendar["HORA_INICIO_CALENDARIO"],
            $calendar["HORA_FIN_CALENDARIO"]
        );

        // Get events info
        $query = "SELECT B.ID_RESERVA, C.ESTADO_RESERVA, B.FECHA_INICIO_SUBRESERVA, B.FECHA_FIN_SUBRESERVA, B.HORA_INICIO_SUBRESERVA, B.HORA_FIN_SUBRESERVA " .
                 "FROM RECURSOS A, SUBRESERVAS B, RESERVAS C " .
                 "WHERE A.ID_RECURSO = C.ID_RECURSO AND B.ID_RESERVA = C.ID_RESERVA AND " .
                 "A.ID_RECURSO = '" . $this->atributes["ID_RECURSO"] . "' AND " .
                 "B.FECHA_INICIO_SUBRESERVA >= '$formStartDate' AND " . 
                 "B.FECHA_FIN_SUBRESERVA <= '$formEndDate' " ; 

        $events = $this->SEARCH($query);

        include_once './MODEL/ReservasModel.php';
        foreach (ReservasModel::$bookingStatus as $status) {
            $count[$status] = 0;
        }

        $visited = array();
        $totalBookedHours = 0;
        foreach ($events as $event) {
            $bookedHours = $this->calcHours(
                $event["FECHA_INICIO_SUBRESERVA"],
                $event["FECHA_FIN_SUBRESERVA"],
                $event["HORA_INICIO_SUBRESERVA"],
                $event["HORA_FIN_SUBRESERVA"]
            );
            $totalBookedHours = $totalBookedHours + $bookedHours;
            if(!in_array($event["ID_RESERVA"], $visited)){
                $count[$event["ESTADO_RESERVA"]]++;
                array_push($visited, $event["ID_RESERVA"]);
            }
        }

        return array(
            "id" => $this->atributes["ID_RECURSO"],
            "defaultStartDate" => $formStartDate,
            "defaultEndDate" => $formEndDate,
            "count" => $count,
            "totalBookedHours" => $totalBookedHours,
            "totalNonBookedHours" => ($totalAvailableHours - $totalBookedHours)
        );                                 
        
    }

    private function calcHours($startDateStr, $endDateStr, $startTimeStr, $endTimeStr){

        $startDate = DateTime::createFromFormat('Y-m-d', $startDateStr);
        $endDate = DateTime::createFromFormat('Y-m-d', $endDateStr);
        $startTime = DateTime::createFromFormat('H:i:00', $startTimeStr);
        $endTime = DateTime::createFromFormat('H:i:00', $endTimeStr);

        $t1 = date_diff( $endDate, $startDate );
        $t1_hours = $t1->h + ( ($t1->days+1) * 24);
        $t2 = date_diff( $endTime, $startTime );
        $t2_hours = $t2->h + ($t2->days * 24);
        return $t1_hours * ($t2_hours / 24);
    }
}

?>