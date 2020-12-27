<?php

class BaseModel {

    protected $atributes;
    protected $primary_key;
    protected $tableName;

    private $connection;

    public $actionMsgs;
    
    public const CANNOT_CONNECT = "cannot_connect";
    
    public const ADD_FAIL = "add_fail";
    public const EDIT_FAIL = "edit_fail";    
    public const DELETE_FAIL = "delete_fail";
    
    public const ADD_SUCCESS = "add_success";
    public const EDIT_SUCCESS = "edit_success";    
    public const DELETE_SUCCESS = "delete_success";
    
    public $formatMsgs;

    public $checks;

    function __construct() {

        $entityName = substr(get_class($this), 0, -6);

        $this->actionMsgs = array(
            self::CANNOT_CONNECT => array("000", "No se ha podido conectar a la base de datos"),
            self::ADD_FAIL => array( "code" => "000", "msg" => $entityName . " no añadido correctamente a la base de datos"),
            self::EDIT_FAIL => array( "code" => "000", "msg" => $entityName . " no editado correctamente en la base de datos"),
            self::DELETE_FAIL => array( "code" => "000", "msg" => $entityName . " no borrado correctamente de la base de datos"),
            self::ADD_SUCCESS => array( "code" => "000", "msg" => $entityName . " añadido correctamente a la base de datos"),
            self::EDIT_SUCCESS => array( "code" => "000", "msg" => $entityName . " editado correctamente en la base de datos"),
            self::DELETE_SUCCESS => array( "code" => "000", "msg" => $entityName . " borrado correctamente de la base de datos")
        );

        // DEBUG: See actionMsgs structure
        // echo '<pre>' . var_export($this->actionMsgs, true) . '</pre>';

    }

    public function checkValidations($validations){
        foreach($validations as $atribute => $checks){
            foreach($checks as $check => $result){
                if($result !== true){
                    return false;
                }
            }
         }
         return true;
    }

    public function checkAtributesForAdd(){
        $validations = array();
        foreach($this->checks as $atribute => $checks){
            $atributeValidation = array();
            foreach($checks as $check => $args){
                //var_dump($args);
                $result = call_user_func_array("self::" . $check, $args);
                if($result !== true) {
                    $validations[$atribute][$check] = $result;
                }
            }
         }
        return $validations;
    }

    public function checkAtributesForEdit(){
        $validations = array();
        foreach($this->checks as $atribute => $checks){
            if($this->atributes[$atribute] !== ""){ // Not all atributes have to be present in edit
                $atributeValidation = array();
                foreach($checks as $check => $args){
                    //var_dump($args);
                    $result = call_user_func_array("self::" . $check, $args);
                    if($result !== true) {
                        $validations[$atribute][$check] = $result;
                    }
                }
            }
         }
        return $validations;
    }

    public function checkAutoKey($key_atribute, $errorCode, $errorMsg){
        $value = $this->atributes[$key_atribute];
        if (intval($value)) {
            return true;
        } else {
            return array("code" => $errorCode, "msg" => $errorMsg);
        }
    }

    public function checkRegex($atribute, $regex, $errorCode, $errorMsg){
        $value = $this->atributes[$atribute];
        if (preg_match($regex, $value)) {
            return true;
        }else{
            return array("code" => $errorCode, "msg" => $errorMsg);
        }
    }

    public function checkSize($atribute, $min, $max, $errorCode, $errorMsg){
        $value = $this->atributes[$atribute];
        $length = strlen($value);
        if($length >= $min && $length <= $max) {
            return true;
        }else{
            return array("code" => $errorCode, "msg" => $errorMsg);
        }
    }

    public function checkNumeric($atribute, $errorCode, $errorMsg){
        $value = $this->atributes[$atribute];
        if(doubleval($value)) {
            return true;
        }else{
            return array("code" => $errorCode, "msg" => $errorMsg);
        }
    }

    public function checkRange($atribute, $min, $max, $errorCode, $errorMsg){
        $value = doubleval($this->atributes[$atribute]);
        if($value > $min && $value < $max ) {
            return true;
        }else{
            return array("code" => $errorCode, "msg" => $errorMsg);
        }
    }

    public function checkEnum($atribute, $enumValues, $errorCode, $errorMsg){
        $value = $this->atributes[$atribute];
        if(in_array($value, $enumValues)){
            return true;
        }else{
            return array("code" => $errorCode, "msg" => $errorMsg);
        }
    }

    public function checkYesOrNo($atribute, $errorCode, $errorMsg){
        $enum = array ("SI", "NO");
        return $this->checkEnum($atribute, $enum, $errorCode, $errorMsg);
    }

    public function checkDateInterval($start_atribute, $end_atribute, $errorCode, $errorMsg){
        $start = $this->atributes[$start_atribute];
        $end = $this->atributes[$end_atribute];
        $format = 'Y-m-d';
        $dStart = DateTime::createFromFormat($format, $start);
        $dEnd = DateTime::createFromFormat($format, $end);
    
        if($dStart && $dStart->format($format) === $start &&
           $dEnd && $dEnd->format($format) === $end &&
           $dStart->getTimestamp() < $dEnd->getTimestamp()) {
               return true;
        }

        return array("code" => $errorCode, "msg" => $errorMsg);
    }

    public function checkDate($atribute, $errorCode, $errorMsg){
        $str_date = $this->atributes[$atribute];
        $format = 'Y-m-d';
        $d = DateTime::createFromFormat($format, $str_date);
        if($d && $d->format($format) === $str_date){
            return true;
        }else{
            return array("code" => $errorCode, "msg" => $errorMsg);
        }
    }

    public function checkTime($atribute, $errorCode, $errorMsg){
        $str_time = $this->atributes[$atribute];
        $format = 'H:i';
        $t = DateTime::createFromFormat($format, $str_time);
        if($t && $t->format($format) === $str_time){
            return true;
        }else{
            return array("code" => $errorCode, "msg" => $errorMsg);
        }
    }

    private function openConnection(){
        return($this->connection = new mysqli('localhost', 'pma', 'iu', '53196285E') /*or die('fallo conexion')*/);
	}

    private function closeConnection(){
		$this->connection->close();
    }
    
    private function executeQuery($query){
        $isConnected = $this->openConnection();
        if (!$isConnected) {
            return $this->actionMsgs[self::CANNOT_CONNECT];
        } else {
            $response = array();
            $response["result"] = $this->connection->query($query);
            $response["affected_rows"] = $this->connection->affected_rows;
            $this->closeConnection();
        }
        return $response;
    }

    public function patchEntity(){
        if ($_POST) {
            foreach($this->atributes as $key => $value){
                $this->atributes[$key] = $_POST[$key]; 
            }
        }
    }

    public function setAtributes($atributesToSet) {
        foreach($this->atributes as $key => $value) {
            if(array_key_exists($key, $atributesToSet)) {
                $this->atributes[$key] = $atributesToSet[$key];
            }
        }

        // DEBUG: Show atributes
        // echo '<pre>' . var_export($this->atributes, true) . '</pre>';
    }

    public function ADD(){

        // Remove primary key if autogenerated
        if(array_key_exists($this->primary_key, $this->checks) &&
           array_key_exists("checkAutoKey", $this->checks[$this->primary_key])){
            unset($this->atributes[$this->primary_key]);
            unset($this->checks[$this->primary_key]);
        }

        $validations = $this->checkAtributesForAdd();

        if($this->checkValidations($validations)){

            // Build the insert query
            $insertQuery = "INSERT INTO $this->tableName (";
            $values = " VALUES ( ";
            foreach ($this->atributes as $key => $value) {
                $insertQuery = $insertQuery . $key . ", ";
                $values = $values . " '" . $value . "' ,";
            }
            $insertQuery = substr($insertQuery, 0, -2);
            $values = substr($values, 0, -1);
            $insertQuery =  $insertQuery . ")" . $values . " )";
    
            // DEBUG: Show sql query
            // echo "<br/>" . $insertQuery . "<br/>";
    
            if( $this->executeQuery($insertQuery)["result"] ) {
                return $this->actionMsgs[self::ADD_SUCCESS];
            }
            return $this->actionMsgs[self::ADD_FAIL];    

        }else{
            $response = $this->actionMsgs[self::ADD_FAIL];
            $response["atributeErrors"] = $validations;
            return $response;
        }

    }

    public function EDIT(){

        // Get value that will be used for update
        $updateKey = $this->atributes[$this->primary_key];
        
        if($updateKey){

            $validations = $this->checkAtributesForEdit();
            if($this->checkValidations($validations)){

                // Build the insert query
                $updateQuery = "UPDATE ". $this->tableName . " SET ";
                foreach ($this->atributes as $key => $value) {
                    if ($key != $this->primary_key && $value != "") {
                        $updateQuery = $updateQuery . $key . " = '" . $value . "'," ;
                    }
                }
                $updateQuery = substr($updateQuery, 0, -1);
                $updateQuery =  $updateQuery . " WHERE " . $this->primary_key . " = '" . $updateKey . "'";

                // DEBUG: Show sql query and affected rows 
                // echo "<br/>" . $updateQuery . "<br/>";

                if($this->executeQuery($updateQuery)["affected_rows"] === 1){
                    return $this->actionMsgs[self::EDIT_SUCCESS];
                }

            }else{
                $response = $this->actionMsgs[self::EDIT_FAIL];
                $response["atributeErrors"] = $validations;
                return $response;
            }
        }
        
        return $this->actionMsgs[self::EDIT_FAIL];
    }

    public function DELETE(){
        
        // Get value that will be used for delete
        $deleteValue = $this->atributes[$this->primary_key];

        if($deleteValue){

            // Build delete query
            $deleteQuery = "DELETE FROM " . $this->tableName .
                           " WHERE " . $this->primary_key . " = '" . $deleteValue . "'";

            // DEBUG: Show sql query
            // echo "<br/>" . $deleteQuery . "<br/>";

            if($this->executeQuery($deleteQuery)["affected_rows"] === 1){
                return $this->actionMsgs[self::DELETE_SUCCESS];
            }
        }

        return $this->actionMsgs[self::DELETE_FAIL];	   			
    }

    public function SEARCH(){

        // Build the select query
        $selectQuery = "SELECT * FROM $this->tableName WHERE (";
        foreach($this->atributes as $key => $value){
            $selectQuery = $selectQuery . "( " . $key . " LIKE '%" . $value . "%' ) and ";
        }        
        $selectQuery = substr($selectQuery, 0, -4);
        $selectQuery =  $selectQuery . ")";

        // DEBUG: Show sql query
        // echo "<br/>" . $selectQuery . "<br/>";

        // Execute the select query
        $response = $this->executeQuery($selectQuery)["result"];

        // Get tuples from query response 
        $tuples = array();
        while($row = $response->fetch_assoc()){
            array_push($tuples, $row);
        }

        return $tuples;
    }


}

?>