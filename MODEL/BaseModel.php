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
            if($this->atributes[$atribute] !== NULL){ // Not all atributes have to be present in edit
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

    public function checkDateFormat($str_date, $errorCode, $errorMsg){
        $obj_date = DateTime::createFromFormat('d/m/Y', $str_date);
        if($obj_date){
            return true;
        }else{
            return array("code" => $errorCode, "msg" => $errorMsg);
        }
    }

    public function checkTimeFormat($str_time, $errorCode, $errorMsg){
        $obj_time = DateTime::createFromFormat('H:i', $str_time);
        if($obj_time){
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
            $response = $this->connection->query($query);
            //$response->affected_rows = $this->connection->affected_rows;
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

        $validations = $this->checkAtributesForAdd();
        if($this->checkValidations($validations)){
            // Build the insert query
            $insertQuery = "INSERT INTO $this->tableName VALUES ( ";
            foreach ($this->atributes as $key => $value) {
                $insertQuery = $insertQuery . " '" . $value . "' ,";
            }
            $insertQuery = substr($insertQuery, 0, -1);
            $insertQuery =  $insertQuery . " )";
    
            // DEBUG: Show sql query
            // echo "<br/>" . $insertQuery . "<br/>";
    
            if( $this->executeQuery($insertQuery) ) {
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
                    if ($key != $this->primary_key && $value != NULL) {
                        $updateQuery = $updateQuery . $key . " = '" . $value . "'," ;
                    }
                }
                $updateQuery = substr($updateQuery, 0, -1);
                $updateQuery =  $updateQuery . " WHERE " . $this->primary_key . " = '" . $updateKey . "'";

                // DEBUG: Show sql query
                // echo "<br/>" . $updateQuery . "<br/>";

                if($this->executeQuery($updateQuery)){
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
             echo "<br/>" . $deleteQuery . "<br/>";

            if($this->executeQuery($deleteQuery)->mysql_affected_rows == 1){
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
        $response = $this->executeQuery($selectQuery);

        // Get tuples from query response 
        $tuples = array();
        while($row = $response->fetch_assoc()){
            array_push($tuples, $row);
        }

        return $tuples;
    }


}

?>