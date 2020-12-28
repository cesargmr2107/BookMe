<?php

class BaseModel {

    protected $tableName;
    protected $primary_key;
    protected $atributes;
    protected $nullAtributes;
    protected $defaultValues;

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
    public $checksForDelete;

    function __construct() {

        $entityName = substr(get_class($this), 0, -6);

        $this->nullAtributes = array();

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
            $canBeNull = in_array($atribute, $this->nullAtributes);
            if( !$canBeNull || $this->atributes[$atribute] != "" ){
                foreach($checks as $check => $args){
                    //var_dump($args);
                    $result = call_user_func_array(array($this, $check), $args);
                    if($result !== true) {
                        $validations[$atribute][$check] = $result;
                    }
                }
            }
         }
        return $validations;
    }

    public function checkAtributesForEdit(){
        $validations = array();
        foreach($this->checks as $atribute => $checks){
            if($this->atributes[$atribute] !== ""){ // Not all atributes have to be present in edit
                foreach($checks as $check => $args){
                    //var_dump($args);
                    $result = call_user_func_array(array($this, $check), $args);
                    if($result !== true) {
                        $validations[$atribute][$check] = $result;
                    }
                }
            }
         }
        return $validations;
    }

    public function checkAtributesForDelete(){
        $validations = array();
        foreach($this->checksForDelete as $atribute => $checks){
                foreach($checks as $check => $args){
                    //var_dump($args);
                    $result = call_user_func_array(array($this, $check), $args);
                    if($result !== true) {
                        $validations[$atribute][$check] = $result;
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

    public function checkIsForeignKey($foreignKeyThis, $foreignKeyOther, $otherModel, $errorCode, $errorMsg ){
        include_once './' . $otherModel . '.php';
        $atributesToSet = array($foreignKeyOther => $this->atributes[$foreignKeyThis]);
        $entity = new $otherModel();
        $entity->setAtributes($atributesToSet);
        $isForeignKey = count($entity->SEARCH());
        if($isForeignKey){
            return true;
        }else{
            return array("code" => $errorCode, "msg" => $errorMsg);
        }
    }

    public function checkNoAssoc($foreignKey, $otherModel, $errorCode, $errorMsg){
        $noAssoc = is_array($this->checkIsForeignKey($foreignKey,$foreignKey,$otherModel,"",""));
        if ($noAssoc){
            return true;
        }else{
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

    private function getNewWeakKey(){
        $className = get_class($this);
        $parentKey = $this->primary_key["parentKey"];
        $atributesToSet = array ( $parentKey => $this->atributes[$parentKey]);
        $entity = new $className();
        $entity->setAtributes($atributesToSet);
        return count($entity->SEARCH()) + 1;
    }

    private function getWhere(){
        if(is_array($this->primary_key)){
            $parentKey = $this->primary_key["parentKey"];
            $parentValue = $this->atributes[$parentKey];
            $weakKey = $this->primary_key["weakKey"];
            $weakValue = $this->atributes[$weakKey];
            $where = " WHERE " . $parentKey ." = '" . $parentValue . "' and " . $weakKey . " = '" . $weakValue . "'";
        }else{
            $where =  " WHERE " . $this->primary_key . " = '" . $this->atributes[$this->primary_key] . "'";
        }
        return $where;
    }

    public function ADD(){

        // Set default values
        if($this->defaultValues){
            foreach($this->defaultValues as $atribute => $defValue){
                $this->atributes[$atribute] = $defValue;
            }
        }

        if(is_array($this->primary_key)){
            $this->atributes[$this->primary_key["weakKey"]] = $this->getNewWeakKey();
        }
        
        // Remove primary key if autogenerated
        else if(array_key_exists($this->primary_key, $this->checks) &&
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
                $canBeNull = in_array($key, $this->nullAtributes);
                if( !$canBeNull || $value != "" ){
                    $insertQuery = $insertQuery . $key . ", ";
                    $values = $values . " '" . $value . "' ,";
                }
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
        $where = $this->getWhere();

        if(preg_match("/WHERE .+ = '.+'( and .+ = '.+')?/", $where)){

            $validations = $this->checkAtributesForEdit();
            if($this->checkValidations($validations)){

                // Build the insert query
                $updateQuery = "UPDATE ". $this->tableName . " SET ";
                foreach ($this->atributes as $key => $value) {
                    if ($key != $this->primary_key && $value != "") {
                        $updateQuery = $updateQuery . $key . " = '" . $value . "', " ;
                    }
                }
                $updateQuery = substr($updateQuery, 0, -2);
                $updateQuery = $updateQuery . $where;

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

        // Do checks for delete if there are any
        if($this->checksForDelete){
            $validations = $this->checkAtributesForDelete();
            if(!$this->checkValidations($validations)){
                $response = $this->actionMsgs[self::ADD_FAIL];
                    $response["atributeErrors"] = $validations;
                    return $response;
            }
        }

        // Get value that will be used for delete
        $where = $this->getWhere();

        if(preg_match("/WHERE .+ = '.+'( and .+ = '.+')?/", $where)){

            // Build delete query
            $deleteQuery = "DELETE FROM " . $this->tableName . $where;

            // DEBUG: Show sql query
            // echo "<br/>" . $deleteQuery . "<br/>";

            if($this->executeQuery($deleteQuery)["affected_rows"] === 1){
                return $this->actionMsgs[self::DELETE_SUCCESS];
            }
        }

        return $this->actionMsgs[self::DELETE_FAIL];	   			
    }

    public function SEARCH($selectQuery = ""){

        // Build the select query if not passed
        if($selectQuery == ""){
            $selectQuery = "SELECT * FROM $this->tableName WHERE (";
            foreach($this->atributes as $key => $value){
                $canBeNull = in_array($key, $this->nullAtributes);
                if( !$canBeNull || $this->atributes[$key] != "" ){
                    $selectQuery = $selectQuery . "( " . $key . " LIKE '%" . $value . "%' ) and ";
                }
            }        
            $selectQuery = substr($selectQuery, 0, -4);
            $selectQuery =  $selectQuery . ")";
        }
       
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