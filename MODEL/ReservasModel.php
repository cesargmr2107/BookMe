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
    public static $atributesForSearch = array (  "ID_RESERVA",
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
        
        $this->actionCodes[parent::ADD_SUCCESS]["code"] = "AC131";
        $this->actionCodes[parent::ADD_FAIL]["code"] = "AC031";
        
        $this->actionCodes[parent::EDIT_SUCCESS]["code"] = "AC132";
        $this->actionCodes[parent::EDIT_FAIL]["code"] = "AC032";
        
        $this->actionCodes[parent::DELETE_SUCCESS]["code"] = "AC133";
        $this->actionCodes[parent::DELETE_FAIL]["code"] = "AC033";
        
        
        $this->tableName = "RESERVAS";
                
        $this->defaultValues = array( "ESTADO_RESERVA" => "PENDIENTE" );
        
        $this->nullAtributes = array ("FECHA_RESPUESTA_RESERVA", "MOTIVO_RECHAZO_RESERVA");

        $this->primary_key = "ID_RESERVA";

        // Subscribe atributes to validations
        $this->checks = array (
            "ID_RESERVA" => array(
                "checkAutoKey" => array('ID_RESERVA', 'AT301'),
            ),
            "LOGIN_USUARIO" => array(
                "checkIsForeignKey" => array('LOGIN_USUARIO', 'LOGIN_USUARIO', 'UsuariosModel', 'AT311')
            ),
            "ID_RECURSO" => array(
                "checkIsForeignKey" => array('ID_RECURSO', 'ID_RECURSO', 'RecursosModel', 'AT321')
            ),
            "FECHA_SOLICITUD_RESERVA" => array(
                "checkDate" => array('FECHA_SOLICITUD_RESERVA', 'AT331')
            ),
            "FECHA_RESPUESTA_RESERVA" => array( 
                "checkDate" => array('FECHA_RESPUESTA_RESERVA', 'AT341')
            ),
            "MOTIVO_RECHAZO_RESERVA" => array(
                "checkSize" => array('MOTIVO_RECHAZO_RESERVA', 0, 100, 'AT351'),
                "checkRegex" => array('MOTIVO_RECHAZO_RESERVA', '/^[ÁÉÍÓÚÜáéíóúüÑña-zA-Z ]+$/', 'AT352')
            ),
            "ESTADO_RESERVA" => array( 
                "checkEnum" => array('ESTADO_RESERVA', static::$bookingStatus, 'AT361'),
            ),
            "COSTE_RESERVA" => array(
                "checkRegex" => array('COSTE_RESERVA', '/^[0-9]+\.[0-9]+$/', 'AT371'),
                "checkRange" => array('COSTE_RESERVA', 0.00, 9999.99, 'AT372')
            )
        );

    }

    public function checkHasSubreservas($errorCode){
        if(isset($this->infoSubreservas) && !empty($this->infoSubreservas)){
            return true;
        }else{
            return [ "ID_RESERVA" => [ "checkHasSubreservas" => $errorCode] ];
        }
    }

    public function setInfoSubreservas($jsonString){
        $this->infoSubreservas = json_decode($jsonString, true)["subreservas"];
    }
    
    public function calculateCosts(){

        // Get resource info: price and range
        include_once './MODEL/RecursosModel.php';
        $resourcesSearch = new RecursosModel();
        $query = "SELECT TARIFA_RECURSO, RANGO_TARIFA_RECURSO FROM RECURSOS " .
                 "WHERE ID_RECURSO = " . $this->atributes["ID_RECURSO"];
        $result = $resourcesSearch->SEARCH($query)[0];
        $price = $result["TARIFA_RECURSO"];
        $range = $result["RANGO_TARIFA_RECURSO"];
        
        // Calculate cost for each interval
        $factors = array ('HORA' => 8.64e+4, 'DIA' => 8.64e+4, 'SEMANA' => 6.048e+5, 'MES' => 2.628e+6);
        $totalCost = 0.0;
        foreach ($this->infoSubreservas as $intervalId => $info) {

            // Build dates and times
            $startDate = strtotime($this->parseDate($info["FECHA_INICIO_SUBRESERVA"]));
            $endDate = strtotime($this->parseDate($info["FECHA_FIN_SUBRESERVA"]) . " + 1 day");

            // Calculate numberOf unit
            $numberOf = ($endDate - $startDate) / $factors[$range];

            if($range === 'HORA'){
                $startTime = strtotime($info["HORA_INICIO_SUBRESERVA"]);
                $endTime = strtotime($info["HORA_FIN_SUBRESERVA"]);
                $numberOf *= ($endTime - $startTime) / (3.6e+3);
            }
            
            $intervalCost = $numberOf * $price;
            $this->infoSubreservas[$intervalId]["COSTE_SUBRESERVA"] = sprintf("%.2f", $intervalCost);
            $totalCost += $intervalCost;
        }

        $this->atributes["COSTE_RESERVA"] = sprintf("%.2f", $totalCost);
    }

    public function ADD(){

        $checkSubreservas = $this->checkHasSubreservas('AT302');
        if($checkSubreservas !== true ){
            $response = $this->actionCodes[self::ADD_FAIL];
            $response["atributeErrors"] = $checkSubreservas;
            return $response;
        }

        $this->initialAddSettings();

        $this->calculateCosts();

        $validations = $this->checkAtributesForAdd();

        if($this->checkValidations( $validations )){

            // Build the insert query
            $insertQuery = "INSERT INTO RESERVAS (LOGIN_USUARIO, ID_RECURSO, FECHA_SOLICITUD_RESERVA, COSTE_RESERVA ) " .
                           "VALUES ('" . 
                                $this->atributes["LOGIN_USUARIO"] . "', '" .
                                $this->atributes["ID_RECURSO"] . "', '" .
                                $this->parseDate($this->atributes["FECHA_SOLICITUD_RESERVA"]) . "', '" .
                                $this->atributes["COSTE_RESERVA"] . "'" .
                            ")";

            // DEBUG: Show sql query
            // echo "<br/>" . $insertQuery . "<br/>";
    
            // Execute query
            $exec = $this->executeQuery($insertQuery);

            if($exec["result"] === true){

                $id_reserva = $exec["last_insert_id"];

                include_once './MODEL/SubreservasModel.php';
                
                // echo '<pre>' . var_export($this, true) . "</pre>";
                
                foreach($this->infoSubreservas as $intervalId => $info){
                    $info["ID_RESERVA"] = $id_reserva;
                    $subreserva = new SubreservasModel();
                    $subreserva->setAtributes($info);
                    $result = $subreserva->ADD();
                    if($result["code"] === $subreserva->getCode("add", "fail")){

                        // Clean DB
                        $this->executeQuery("DELETE FROM SUBRESERVAS WHERE ID_RESERVA = $id_reserva");
                        $this->executeQuery("DELETE FROM RESERVAS WHERE ID_RESERVA = $id_reserva");

                        // Build and return response
                        $response = $this->actionCodes[self::ADD_FAIL];
                        $response["atributeErrors"] = $result["atributeErrors"];
                        return $response;
                    }
                }
                return $this->actionCodes[self::ADD_SUCCESS];
            }
        }else{
            $response = $this->actionCodes[self::ADD_FAIL];
            $response["atributeErrors"] = $validations;
            return $response;
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

        if(array_key_exists("ID_RECURSO", $result)){
            // Resource info
            include_once './MODEL/RecursosModel.php';
            $resourcesSearch = new RecursosModel();
            
            $resourcesSearch->setAtributes(
                array("ID_RECURSO" => $result["ID_RECURSO"])
            );

            $result["resource"] = $resourcesSearch->SEARCH()[0];
        }

        // User info if necessary
        if(array_key_exists("LOGIN_USUARIO", $result) && $_SESSION["LOGIN_USUARIO"] !== $result["LOGIN_USUARIO"]){
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
        
        if(isAdminUser()){
            $query = "SELECT REC.ID_RECURSO, REC.NOMBRE_RECURSO, COUNT(*) AS COUNT FROM RESERVAS RES, RECURSOS REC " .
                     "WHERE RES.ID_RECURSO = REC.ID_RECURSO AND RES.ESTADO_RESERVA = 'PENDIENTE' GROUP BY ID_RECURSO";
        }else{
            $login = $_SESSION["LOGIN_USUARIO"];
            $query = "SELECT REC.ID_RECURSO, REC.NOMBRE_RECURSO, COUNT(*) AS COUNT FROM RESERVAS RES, RECURSOS REC " .
                     "WHERE RES.ID_RECURSO = REC.ID_RECURSO AND RES.ESTADO_RESERVA = 'PENDIENTE' AND REC.LOGIN_RESPONSABLE = '$login' GROUP BY ID_RECURSO";
        }

        $result = $this->SEARCH($query);
        
        // DEBUG: Check bookings    
        // echo '<pre>' . var_export($query, true) . '</pre>';

        return $result;
    }

    public function SEARCH_CONFIRM(){

        if(isAdminUser()){
            $query = "SELECT RES.ID_RESERVA, RES.FECHA_SOLICITUD_RESERVA, RES.LOGIN_USUARIO,  REC.NOMBRE_RECURSO " .
                     "FROM RESERVAS RES, RECURSOS REC WHERE RES.ID_RECURSO = REC.ID_RECURSO AND RES.ESTADO_RESERVA = 'ACEPTADA'";
        }else{
            $login = $_SESSION["LOGIN_USUARIO"];
            $query = "SELECT RES.ID_RESERVA, RES.FECHA_SOLICITUD_RESERVA, RES.LOGIN_USUARIO,  REC.NOMBRE_RECURSO " .
                     "FROM RESERVAS RES, RECURSOS REC " . 
                     "WHERE RES.ID_RECURSO = REC.ID_RECURSO AND RES.ESTADO_RESERVA = 'ACEPTADA' AND REC.LOGIN_RESPONSABLE = '$login'";
        }

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

            $idRecurso = $this->atributes["ID_RECURSO"];
            $fechaRespuesta = $this->parseDate($this->atributes["FECHA_RESPUESTA_RESERVA"]);
            $fechaInicio = $subreserva["FECHA_INICIO_SUBRESERVA"];
            $fechaFin = $subreserva["FECHA_FIN_SUBRESERVA"];
            $horaInicio = $subreserva["HORA_INICIO_SUBRESERVA"];
            $horaFin = $subreserva["HORA_FIN_SUBRESERVA"];
            $defaultRejectMsg = "Tu reserva ha sido rechazada porque se solapaba en el tiempo con otra de mayor prioridad";
            
            $query = "UPDATE RESERVAS R, SUBRESERVAS S " .
                 "SET R.ID_RESERVA = S.ID_RESERVA, R.ESTADO_RESERVA = 'RECHAZADA', " .
                 "R.MOTIVO_RECHAZO_RESERVA = '$defaultRejectMsg', R.FECHA_RESPUESTA_RESERVA = '$fechaRespuesta'" .
                 "WHERE R.ID_RESERVA = S.ID_RESERVA AND R.ID_RECURSO = $idRecurso AND " .
                 "R.ESTADO_RESERVA = 'PENDIENTE' AND (" .
                 "( S.FECHA_INICIO_SUBRESERVA >= '" . $fechaInicio . "' AND S.FECHA_INICIO_SUBRESERVA <= '" . $fechaFin . "' ) OR " .
                 "( S.FECHA_FIN_SUBRESERVA >= '" . $fechaInicio . "' AND S.FECHA_FIN_SUBRESERVA <= '" . $fechaFin . "' ) ) AND ( " .
                 "( S.HORA_INICIO_SUBRESERVA >= '" . $horaInicio . "' AND S.HORA_INICIO_SUBRESERVA <= '" . $horaFin . "' ) OR " .
                 "( S.HORA_FIN_SUBRESERVA >= '" . $horaInicio . "' AND S.HORA_FIN_SUBRESERVA <= '" . $horaFin . "' ) )";

            // DEBUG: Check query    
            // echo '<p>' . $query . '</p>';

            $this->executeQuery($query);
        }

    }

}

?>