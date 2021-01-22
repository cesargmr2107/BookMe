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
        "LOGIN_RESPONSABLE",
        "BORRADO_LOGICO"
    );

    // Define which atributes will be selected in search
    protected static $atributesForSearch = array (  "ID_RECURSO",
                                                    "NOMBRE_RECURSO",
                                                    "TARIFA_RECURSO",
                                                    "RANGO_TARIFA_RECURSO",
                                                    "LOGIN_RESPONSABLE");

    public static $priceRanges = array("HORA", "DIA", "SEMANA", "MES");

    function __construct (){
        
        // Call parent constructor
        parent::__construct();
               
        // Overwrite action codes
        
        $this->actionCodes[parent::ADD_SUCCESS]["code"] = "AC121";
        $this->actionCodes[parent::ADD_FAIL]["code"] = "AC021";
        
        $this->actionCodes[parent::EDIT_SUCCESS]["code"] = "AC122";
        $this->actionCodes[parent::EDIT_FAIL]["code"] = "AC022";
        
        $this->actionCodes[parent::DELETE_SUCCESS]["code"] = "AC123";
        $this->actionCodes[parent::EDIT_FAIL]["code"] = "AC023";
        
        $this->tableName = "RECURSOS";      

        $this->primary_key = "ID_RECURSO";

        $this->defaultValues = array( "BORRADO_LOGICO" => "NO" );
        
        // Subscribe atributes to validations
        $this->checks = array (
            "ID_RECURSO" => array(
                "checkAutoKey" => array('ID_RECURSO', 'AT201'),
            ),
            "NOMBRE_RECURSO" => array(
                "checkSize" => array('NOMBRE_RECURSO', 6, 40, 'AT211'),
                "checkRegex" => array('NOMBRE_RECURSO', '/^[ÁÉÍÓÚÜáéíóúüÑña-zA-Z ]+$/', 'AT212')
            ),
            "DESCRIPCION_RECURSO" => array(
                "checkSize" => array('DESCRIPCION_RECURSO', 0, 100, 'AT221'),
                "checkRegex" => array('DESCRIPCION_RECURSO', '/^[ÁÉÍÓÚÜáéíóúüÑña-zA-Z ]+$/', 'AT222')
            ),
            "TARIFA_RECURSO" => array(
                "checkRegex" => array('COSTE_RESERVA', '/^[0-9]+$/', 'AT231'),
                "checkRange" => array('TARIFA_RECURSO', 0, 999, 'AT232')
            ),
            "RANGO_TARIFA_RECURSO" => array(
                "checkEnum" => array('RANGO_TARIFA_RECURSO', static::$priceRanges, 'AT241')
            ),
            "ID_CALENDARIO" => array(
                "checkIsForeignKey" => array('ID_CALENDARIO', 'ID_CALENDARIO', 'CalendariosModel', 'AT251')
            ),
            "LOGIN_RESPONSABLE" => array(
                "checkIsForeignKey" => array('LOGIN_RESPONSABLE', 'LOGIN_RESPONSABLE', 'ResponsablesModel', 'AT261')
            ),
            "BORRADO_LOGICO" => array(
                "checkYesOrNo" => array('BORRADO_LOGICO', 'AT271')
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
                 "C.ESTADO_RESERVA = 'ACEPTADA' AND " .
                 "A.ID_RECURSO = '" . $this->atributes["ID_RECURSO"] . "'"; 
        
        // DEBUG: Check events
        // echo "<p>" . $query . "</p>";
        
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