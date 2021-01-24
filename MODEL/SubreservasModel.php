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
        
        $this->actionCodes[parent::ADD_SUCCESS]["code"] = "AC151";
        $this->actionCodes[parent::ADD_FAIL]["code"] = "AC051";
        
        $this->actionCodes[parent::EDIT_SUCCESS]["code"] = "AC152";
        $this->actionCodes[parent::EDIT_FAIL]["code"] = "AC052";
        
        $this->actionCodes[parent::DELETE_SUCCESS]["code"] = "AC153";
        $this->actionCodes[parent::DELETE_FAIL]["code"] = "AC053";
        
        $this->tableName = "SUBRESERVAS";      
                  
        $this->primary_key = array("parentKey" => "ID_RESERVA", "weakKey" => "ID_SUBRESERVA");

        // Subscribe atributes to validations
        $this->checks = array (
            "ID_RESERVA" => array(
                "checkIsForeignKey" => array('ID_RESERVA', 'ID_RESERVA', 'ReservasModel', 'AT501'),
                "checkNoOverlappings" => array('', 'AT502')
            ),
            "FECHA_INICIO_SUBRESERVA" => array(
                "checkDate" => array('FECHA_INICIO_SUBRESERVA', 'AT521')
            ),
            "FECHA_FIN_SUBRESERVA" => array(
                "checkDate" => array('FECHA_FIN_SUBRESERVA', 'AT531'),
                "checkDateInterval" => array('FECHA_INICIO_SUBRESERVA', 'FECHA_FIN_SUBRESERVA', 'AT532')
            ),
            "HORA_INICIO_SUBRESERVA" => array(
                "checkTime" => array('HORA_INICIO_SUBRESERVA', 'AT541')
            ),
            "HORA_FIN_SUBRESERVA" => array(
                "checkTime" => array('HORA_FIN_SUBRESERVA', 'AT551')
            ),
            "COSTE_SUBRESERVA" => array(
                "checkRegex" => array('COSTE_SUBRESERVA', '/^[0-9]+\.[0-9]+$/', 'AT561'),
                "checkRange" => array('COSTE_SUBRESERVA', 0.00, 9999.99, 'AT562')
            )
        );
    }

    public function checkNoOverlappings($id_recurso, $errorCode){
        
        if($id_recurso == NULL){
            // Get resource id
            include_once './MODEL/ReservasModel.php';
            $atributesToSet = array ("ID_RESERVA" => $this->atributes["ID_RESERVA"]);
            $reservaSearch = new ReservasModel();
            $reservaSearch->setAtributes($atributesToSet);
            $id_recurso = $reservaSearch->SEARCH()[0]["ID_RECURSO"];
        }
        
        // Build query
        $fechaInicio = $this->parseDate($this->atributes["FECHA_INICIO_SUBRESERVA"]);
        $fechaFin = $this->parseDate($this->atributes["FECHA_FIN_SUBRESERVA"]);
        $horaInicio = $this->atributes["HORA_INICIO_SUBRESERVA"];
        $horaFin = $this->atributes["HORA_FIN_SUBRESERVA"];
        $query = "SELECT * FROM RESERVAS R, SUBRESERVAS S " .
                 "WHERE R.ID_RESERVA = S.ID_RESERVA AND R.ID_RECURSO = $id_recurso " . 
                 "AND ( R.ESTADO_RESERVA = 'ACEPTADA' OR R.ID_RESERVA = " . $this->atributes["ID_RESERVA"]. ") AND (" .
                 "( S.FECHA_INICIO_SUBRESERVA <= '" . $fechaFin . "' AND S.FECHA_FIN_SUBRESERVA >= '" . $fechaInicio . "' ) AND " .
                 "( S.HORA_INICIO_SUBRESERVA < '" . $horaFin . "' AND S.HORA_FIN_SUBRESERVA > '" . $horaInicio . "' ) )";

        // DEBUG: Check query and result
        // echo "<p>" . $query . "</p>";
        // echo '<pre style="color:red">' . var_export($this->SEARCH($query), true) . '</pre>';


        // Do check
        $noOverlappings = empty($this->SEARCH($query));
        if($noOverlappings){
            return true;
        }else{
            return $errorCode; 
        }
    }

}

?>