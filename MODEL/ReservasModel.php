<?php

include_once './MODEL/BaseModel.php';

class ReservasModel extends BaseModel {
    
    // Define atributes
    public static $atributeNames = array(
        "ID_RESERVA",
        "LOGIN_USUARIO",
        "ID_RECURSO",
        "FECHA_SOLICITUD_RESERVA",
        "FECHA_RESPUESTA_RESERVA",
        "MOTIVO_RECHAZO_RESERVA",
        "ESTADO_RESERVA",
        "COSTE_RESERVA"
    );

    // Define which atributes will be selected in search
    protected static $atributesForSearch = array (  "ID_RESERVA",
                                                    "LOGIN_USUARIO",
                                                    "ID_RECURSO",
                                                    "FECHA_SOLICITUD_RESERVA",
                                                    "ESTADO_RESERVA");

    // Set booking status
    public static $bookingStatus = array("PENDIENTE", "ACEPTADA", "RECHAZADA", "CANCELADA", "RECURSO_USADO", "RECURSO_NO_USADO");

    public $infoSubreservas;

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
        
        
        $this->tableName = "RESERVAS";
                
        $this->defaultValues = array( "ESTADO_RESERVA" => "PENDIENTE" );
        
        $this->nullAtributes = array ("FECHA_RESPUESTA_RESERVA", "MOTIVO_RECHAZO_RESERVA");

        $this->primary_key = "ID_RESERVA";

        // Subscribe atributes to validations
        $this->checks = array (
            "ID_RESERVA" => array(
                "checkAutoKey" => array('ID_RESERVA', '222', 'El id de la reserva (gestionado por el sistema) es un entero'),
            ),
            "LOGIN_USUARIO" => array(
                "checkIsForeignKey" => array('LOGIN_USUARIO', 'LOGIN_USUARIO', 'UsuariosModel', '222', 'El usuario es desconocido')
            ),
            "ID_RECURSO" => array(
                "checkIsForeignKey" => array('ID_RECURSO', 'ID_RECURSO', 'RecursosModel', '222', 'El recurso es desconocido')
            ),
            "FECHA_SOLICITUD_RESERVA" => array(
                "checkDate" => array('FECHA_SOLICITUD_RESERVA', '222', 'La fecha debe tener el formato dd-mm-yyyy')
            ),
            "FECHA_RESPUESTA_RESERVA" => array( 
                "checkDate" => array('FECHA_RESPUESTA_RESERVA', '222', 'La fecha debe tener el formato dd-mm-yyyy'),
                /*"checkDateInterval" => array('FECHA_SOLICITUD_RESERVA', 'FECHA_RESPUESTA_RESERVA', '222', 'La fecha de respuesta debe ser posterior a la fecha de solicitud')*/
            ),
            "MOTIVO_RECHAZO_RESERVA" => array(
                "checkSize" => array('MOTIVO_RECHAZO_RESERVA', 0, 200, '222', 'El motivo de rechazo no puede superar los 200 caracteres'),
            ),
            "ESTADO_RESERVA" => array( 
                "checkEnum" => array('ESTADO_RESERVA', static::$bookingStatus, '222', 'El estado de la reserva no es válido'),
                "checkNoOverlappings" => array('222', 'El intervalo de reserva coincide con una reserva existente')
            ),
            "COSTE_RESERVA" => array(
                "checkNumeric" => array('COSTE_RESERVA', '222', 'El coste de la reserva debe ser un valor numérico'),
            )
        );
    }

    public function setInfoSubreservas($jsonString){
        $this->infoSubreservas = json_decode($jsonString, true)["subreservas"];
    }
    
    public function checkValidBooking(){
        return true;
    }

    public function checkNoOverlappings($errorCode, $errorMsg){

        if($this->atributes["ESTADO_RESERVA"] == "ACEPTADA"){

            // Get booking intervals
            include_once './MODEL/SubreservasModel.php';
            $atributesToSet = array ("ID_RESERVA" => $this->atributes["ID_RESERVA"]);
            $subreserva = new SubreservasModel();
            $subreserva->setAtributes($atributesToSet);
            $subreservas = $subreserva->SEARCH();

            // DEBUG: Check result
            // echo '<pre>' . var_export($subreservas, true) . '</pre>';

            foreach($subreservas as $sr){
                $subreserva->setAtributes($sr);
                $check = $subreserva->checkNoOverlappings($this->atributes["ID_RECURSO"], "", "");
                
                // DEBUG: Check result
                // echo '<pre>' . var_export($check, true) . '</pre>';

                if(is_array($check)){
                    return array("code" => $errorCode, "msg" => $errorMsg);
                }
            }
        }
        
        return true;
    }

    public function ADD(){

        $validations = $this->checkValidBooking();

        if($validations === true){

            // Build the insert query
            $insertQuery = "INSERT INTO RESERVAS (LOGIN_USUARIO, ID_RECURSO, FECHA_SOLICITUD_RESERVA, COSTE_RESERVA ) " .
                           "VALUES ('" . 
                                $this->atributes["LOGIN_USUARIO"] . "', '" .
                                $this->atributes["ID_RECURSO"] . "', '" .
                                $this->atributes["FECHA_SOLICITUD_RESERVA"] . "', '" .
                                $this->atributes["COSTE_RESERVA"] . "'" .
                            ")";

            // DEBUG: Show sql query
            // echo "<br/>" . $insertQuery . "<br/>";
    
            // Execute query
            $exec = $this->executeQuery($insertQuery);

            if($exec["result"] === true){

                include_once './MODEL/SubreservasModel.php';

                foreach($this->infoSubreservas as $intervalId => $info){
                    $info["ID_RESERVA"] = $exec["last_insert_id"];
                    $subreserva = new SubreservasModel();
                    $subreserva->setAtributes($info);
                    $subreserva->ADD();
                }
            }
            return $this->actionMsgs[self::ADD_SUCCESS];
        }else{
            return $this->actionMsgs[self::ADD_FAIL];
        }
    }

    public function SHOW(){
        $result = parent::SHOW();

        // Bookings info 
        include_once './MODEL/SubreservasModel.php';
        $subreservasSearch = new SubreservasModel();
        
        $subreservasSearch->setAtributes(
            array("ID_RESERVA" => $result["ID_RESERVA"])
        );

        $result["subreservas"] = $subreservasSearch->SEARCH();

        // Resource info
        include_once './MODEL/RecursosModel.php';
        $resourcesSearch = new RecursosModel();
        
        $resourcesSearch->setAtributes(
            array("ID_RECURSO" => $result["ID_RECURSO"])
        );

        $result["resource"] = $resourcesSearch->SEARCH()[0];

        // User info if necessary
        if($_SESSION["LOGIN_USUARIO"] !== $result["LOGIN_USUARIO"]){
            include_once './MODEL/UsuariosModel.php';
            $usersSearch = new UsuariosModel();
            $usersSearch->setAtributes(
                array("LOGIN_USUARIO" => $result["LOGIN_USUARIO"])
            );
            $result["user"] = $usersSearch->SEARCH()[0];
        }

        return $result;
    }

    public function SEARCH_OWN($login){
        $query = "SELECT REC.NOMBRE_RECURSO, RES.ID_RESERVA, RES.FECHA_SOLICITUD_RESERVA, RES.ESTADO_RESERVA " .
                 "FROM RESERVAS RES, RECURSOS REC WHERE REC.ID_RECURSO = RES.ID_RECURSO AND " .
                 "RES.LOGIN_USUARIO = '$login'";
        $bookings = $this->SEARCH($query);
        
        // DEBUG: Check bookings    
        // echo '<pre>' . var_export($bookings, true) . '</pre>';

        $result = array();
        foreach (static::$bookingStatus as $status) {
            $result[$status] = array();
        }
        foreach ($bookings as $booking) {
            array_push(
                $result[$booking["ESTADO_RESERVA"]],
                $booking
            );
        }

        // DEBUG: Check bookings    
        // echo '<pre>' . var_export($result, true) . '</pre>';

        return $result;
    }

    public function SEARCH_PENDING(){
        
        $query = "SELECT REC.ID_RECURSO, REC.NOMBRE_RECURSO, COUNT(*) AS COUNT FROM RESERVAS RES, RECURSOS REC " .
                 "WHERE RES.ID_RECURSO = REC.ID_RECURSO AND RES.ESTADO_RESERVA = 'PENDIENTE' GROUP BY ID_RECURSO";
        
        $result = $this->SEARCH($query);
        
        // DEBUG: Check bookings    
        // echo '<pre>' . var_export($query, true) . '</pre>';

        return $result;
    }

    public function SEARCH_CONFIRM(){
        $query = "SELECT RES.ID_RESERVA, RES.FECHA_SOLICITUD_RESERVA, RES.LOGIN_USUARIO,  REC.NOMBRE_RECURSO " .
                 "FROM RESERVAS RES, RECURSOS REC WHERE RES.ID_RECURSO = REC.ID_RECURSO AND RES.ESTADO_RESERVA = 'ACEPTADA'";
        return $this->SEARCH($query);
    }

    public function SHOW_PENDING(){
        
        $subreservasQuery = "SELECT C.ID_RESERVA, C.ESTADO_RESERVA, C.FECHA_SOLICITUD_RESERVA,  C.COSTE_RESERVA, C.LOGIN_USUARIO, C.ID_RECURSO, B.FECHA_INICIO_SUBRESERVA, B.FECHA_FIN_SUBRESERVA, B.HORA_INICIO_SUBRESERVA, B.HORA_FIN_SUBRESERVA " .
                            "FROM RECURSOS A, SUBRESERVAS B, RESERVAS C " .
                            "WHERE A.ID_RECURSO = C.ID_RECURSO AND B.ID_RESERVA = C.ID_RESERVA AND " .
                            "C.ESTADO_RESERVA = 'PENDIENTE' AND " .
                            "A.ID_RECURSO = '" . $this->atributes["ID_RECURSO"] . "'"; 

        $subreservas = $this->SEARCH($subreservasQuery);

        $result = array();
        foreach ($subreservas as $subreserva) {
            if(!array_key_exists($subreserva["ID_RESERVA"], $result)){
                $result[$subreserva["ID_RESERVA"]] = array();
            }
            array_push(
                $result[$subreserva["ID_RESERVA"]],
                $subreserva
            );
        }
        // DEBUG: Check bookings    
        // echo '<pre>' . var_export($result, true) . '</pre>';
        

        return $result;
    }

    public function ACCEPT_PENDING(){
        
        // Update this booking's info
        parent::EDIT();
        
        $subreservas = $this->SHOW()["subreservas"];
        
        // DEBUG: Check subreservas    
        // echo '<pre>' . var_export($this->atributes, true) . '</pre>';

        foreach ($subreservas as $subreserva) {

            $fechaRespuesta = $this->atributes["FECHA_RESPUESTA_RESERVA"];
            $fechaInicio = $subreserva["FECHA_INICIO_SUBRESERVA"];
            $fechaFin = $subreserva["FECHA_FIN_SUBRESERVA"];
            $horaInicio = $subreserva["HORA_INICIO_SUBRESERVA"];
            $horaFin = $subreserva["HORA_FIN_SUBRESERVA"];
            $defaultRejectMsg = "Tu reserva ha sido rechazada porque se solapaba en el tiempo con otra de mayor prioridad";
            
            $query = "UPDATE RESERVAS R, SUBRESERVAS S " .
                 "SET R.ID_RESERVA = S.ID_RESERVA, R.ESTADO_RESERVA = 'RECHAZADA', " .
                 "R.MOTIVO_RECHAZO_RESERVA = '$defaultRejectMsg', R.FECHA_RESPUESTA_RESERVA = '$fechaRespuesta'" .
                 "WHERE R.ID_RESERVA = S.ID_RESERVA AND " .
                 "R.ESTADO_RESERVA = 'PENDIENTE' AND (" .
                 "( S.FECHA_INICIO_SUBRESERVA >= '" . $fechaInicio . "' AND S.FECHA_INICIO_SUBRESERVA <= '" . $fechaFin . "' ) OR " .
                 "( S.FECHA_FIN_SUBRESERVA >= '" . $fechaInicio . "' AND S.FECHA_FIN_SUBRESERVA <= '" . $fechaFin . "' ) ) AND ( " .
                 "( S.HORA_INICIO_SUBRESERVA >= '" . $horaInicio . "' AND S.HORA_INICIO_SUBRESERVA <= '" . $horaFin . "' ) OR " .
                 "( S.HORA_FIN_SUBRESERVA >= '" . $horaInicio . "' AND S.HORA_FIN_SUBRESERVA <= '" . $horaFin . "' ) )";

            // DEBUG: Check query    
            echo '<p>' . $query . '</p>';

            $this->executeQuery($query);
        }

    }

}

?>