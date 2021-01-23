<?php

class BaseModel {

    public static $atributeNames;
    protected static $atributesForSearch = array("*");
    protected $tableName;
    protected $primary_key;
    protected $atributes;
    protected $nullAtributes;
    protected $defaultValues;

    protected $deleteAtribute;
    protected $deleteOptions = ["BORRADO_LOGICO" => "SI", "ES_ACTIVO" => "NO"];

    private $connection;

    public $actionCodes;
    
    public const CANNOT_CONNECT = "cannot_connect";
    public const BAD_QUERY = "bad_query";
    
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
        
        $this->nullAtributes = array();

        $this->actionCodes = array(
            self::CANNOT_CONNECT => array( "code" => "AC000" ),
            self::BAD_QUERY => array( "code" => "AC001" ),
            self::ADD_FAIL => array( "code" => "" ),
            self::EDIT_FAIL => array( "code" => "" ),
            self::DELETE_FAIL => array( "code" => "" ),
            self::ADD_SUCCESS => array( "code" => "" ),
            self::EDIT_SUCCESS => array( "code" => "" ),
            self::DELETE_SUCCESS => array( "code" => "" )
        );

        // Initialize atributes to empty string
        foreach(static::$atributeNames as $atribute){
            $this->atributes[$atribute]  = "";
        }

        // DEBUG: See actionCodes structure
        // echo '<pre>' . var_export($this->actionCodes, true) . '</pre>';

    }

    public function clear(){
        foreach(static::$atributeNames as $atribute){
            $this->atributes[$atribute] = "";
        }
    }

    public function getCode($action, $result){
        $key = $action . "_" . $result;
        return $this->actionCodes[$key]["code"];
    }

    public function doAtributeChecks($atribute, $value){

        if(!in_array($atribute, static::$atributeNames)){
            return false;
        }

        $class = get_class($this);
        $object = new $class();
        $object->setAtributes([$atribute => $value]);
        $errors = [];

        foreach ($object->checks[$atribute] as $check => $params) {
            if($check != "checkDateInterval" && $check != "checkNoOverlappings"){
                $result = call_user_func_array([$object,$check],$params);
                if($result !== true){
                    array_push($errors,$result);
                }
            }
        }

        if(empty($errors)){
            return true;
        }else{
            return $errors;
        }
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

    public function checkAutoKey($key_atribute, $errorCode){
        $value = $this->atributes[$key_atribute];
        if (intval($value)) {
            return true;
        } else {
            return $errorCode;
        }
    }

    public function checkIsForeignKey($foreignKeyThis, $foreignKeyOther, $otherModel, $errorCode ){
        include_once './MODEL/' . $otherModel . '.php';
        $atributesToSet = array($foreignKeyOther => $this->atributes[$foreignKeyThis]);
        $entity = new $otherModel();
        $entity->setAtributes($atributesToSet);
        $isForeignKey = count($entity->SEARCH());
        if($isForeignKey){
            return true;
        }else{
            return $errorCode;
        }
    }

    public function checkNoAssoc($foreignKey, $otherModel, $errorCode){
        $checkFK = $this->checkIsForeignKey($foreignKey,$foreignKey,$otherModel,"");
        if ($checkFK !== true){
            return true;
        }else{
            return $errorCode;
        }
    }

    public function checkRegex($atribute, $regex, $errorCode){
        $value = $this->atributes[$atribute];
        if (preg_match($regex, $value)) {
            return true;
        }else{
            return $errorCode;
        }
    }

    public function checkSize($atribute, $min, $max, $errorCode){
        $value = $this->atributes[$atribute];
        $length = strlen($value);
        if($length >= $min && $length <= $max) {
            return true;
        }else{
            return $errorCode;
        }
    }

    public function checkNumeric($atribute, $errorCode){
        $value = $this->atributes[$atribute];
        if(doubleval($value)) {
            return true;
        }else{
            return $errorCode;
        }
    }

    public function checkRange($atribute, $min, $max, $errorCode){
        $value = doubleval($this->atributes[$atribute]);
        if($value >= $min && $value <= $max ) {
            return true;
        }else{
            return $errorCode;
        }
    }

    public function checkEnum($atribute, $enumValues, $errorCode){
        $value = $this->atributes[$atribute];
        if(in_array($value, $enumValues)){
            return true;
        }else{
            return $errorCode;
        }
    }

    public function checkYesOrNo($atribute, $errorCode){
        $enum = array ("SI", "NO");
        return $this->checkEnum($atribute, $enum, $errorCode);
    }

    public function checkDateInterval($start_atribute, $end_atribute, $errorCode){
        $start = $this->atributes[$start_atribute];
        $end = $this->atributes[$end_atribute];
        $format = 'd/m/Y';
        $dStart = DateTime::createFromFormat($format, $start);
        $dEnd = DateTime::createFromFormat($format, $end);
    
        if($dStart && $dStart->format($format) === $start &&
           $dEnd && $dEnd->format($format) === $end &&
           $dStart->getTimestamp() <= $dEnd->getTimestamp()) {
               return true;
        }

        return $errorCode;
    }

    public function checkDate($atribute, $errorCode){
        $str_date = $this->atributes[$atribute];
        $format = 'd/m/Y';
        $d = DateTime::createFromFormat($format, $str_date);
        if($d && $d->format($format) === $str_date){
            return true;
        }else{
            return $errorCode;
        }
    }

    public function checkTime($atribute, $errorCode){
        $str_time = $this->atributes[$atribute];
        $format = 'H:00:00';
        $t = DateTime::createFromFormat($format, $str_time);
        if($t && $t->format($format) === $str_time){
            return true;
        }else{
            return $errorCode;
        }
    }

    private function openConnection(){
        return($this->connection = new mysqli('localhost', 'pma', 'iu', '53196285E') /*or die('fallo conexion')*/);
	}

    private function closeConnection(){
		$this->connection->close();
    }
    
    protected function executeQuery($query){
        $isConnected = $this->openConnection();
        if (!$isConnected) {
            return $this->actionCodes[self::CANNOT_CONNECT];
        } else {
            $response = array();
            $response["result"] = $this->connection->query($query);
            $response["last_insert_id"] = mysqli_insert_id($this->connection);
            $response["affected_rows"] = $this->connection->affected_rows;
            $this->closeConnection();
        }
        return $response;
    }

    public function parseDate($date){
        $dateArray = explode("/", $date);
        return $dateArray[2] . "-" . $dateArray[1] . "-" . $dateArray[0];
    }

    public function patchEntity(){
        foreach($_REQUEST as $key => $value){
            if(in_array($key,static::$atributeNames)) {
                if($key !== "controller" && $key !== "action"){
                    $this->atributes[$key] = $value;    
                }
            }
        }
        // DEBUG: Check patched atributes
        // echo '<pre>' . var_export($this->atributes, true) . '</pre>';
    }

    public function get($atribute){
        if(array_key_exists($atribute, $this->atributes)){
            return $this->atributes[$atribute];
        }
        return null;
    }

    public static function getFormattedAtributeNames(){
        
        $entityName = strtoupper(substr(get_called_class(), 0, -6));
        
        $formatted = array();
        $toExplore = in_array("*", static::$atributesForSearch) ? static::$atributeNames : static::$atributesForSearch;

        foreach($toExplore as $name){
            $toReplace = array($entityName);
            $replacement = array("");
            $name =  str_replace($toReplace, $replacement, $name);
            if( $name[strlen($name)-1] === "_"){
                $name = substr($name,0,-1);
            }
            $name = strtolower($name);
            array_push($formatted, $name);
        }

        return $formatted;
    }

    public function setAtributes($atributesToSet) {
        
        foreach($atributesToSet as $key => $value) {
            if(array_key_exists($key, $this->atributes)) {
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

    public function getIdAndNameArray($id, $name){
        $search = $this->SEARCH("SELECT $id, $name FROM $this->tableName");
        foreach ($search as $entity) {
            $result[$entity[$id]] = $entity[$name];
        }
        return $result;
    }

    protected function initialAddSettings(){
        // Set default values
        if($this->defaultValues){
            foreach($this->defaultValues as $atribute => $defValue){
                if($this->atributes[$atribute] === ""){
                    $this->atributes[$atribute] = $defValue;
                }
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
    }

    public function ADD(){

        $this->initialAddSettings();

        $validations = $this->checkAtributesForAdd();

        if($this->checkValidations($validations)){

            // Build the insert query
            $insertQuery = "INSERT INTO $this->tableName (";
            $values = " VALUES ( ";
            foreach ($this->atributes as $key => $value) {
                $canBeNull = in_array($key, $this->nullAtributes);
                if( !$canBeNull || $value != "" ){
                    // Parse dates to BD format
                    if (strpos($key, 'FECHA') !== false){ 
                        $value = $this->parseDate($value);
                    }
                    $insertQuery = $insertQuery . $key . ", ";
                    $values = $values . " '" . $value . "',";
                }
            }
            $insertQuery = substr($insertQuery, 0, -2);
            $values = substr($values, 0, -1);
            $insertQuery =  $insertQuery . ")" . $values . " )";
    
            // DEBUG: Show sql query
            // echo "<br/>" . $insertQuery . "<br/>";
    
            if( $this->executeQuery($insertQuery)["result"] ) {
                return $this->actionCodes[self::ADD_SUCCESS];
            }
            return $this->actionCodes[self::ADD_FAIL];    

        }else{
            $response = $this->actionCodes[self::ADD_FAIL];
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
                        // Parse dates to BD format
                        if (strpos($key, 'FECHA') !== false){ 
                            $value = $this->parseDate($value);
                        }
                        $updateQuery = $updateQuery . $key . " = '" . $value . "', " ;
                    }
                }
                $updateQuery = substr($updateQuery, 0, -2);
                $updateQuery = $updateQuery . $where;

                // DEBUG: Show sql query and affected rows 
                // echo "<br/>" . $updateQuery . "<br/>";

                if($this->executeQuery($updateQuery)["affected_rows"] === 1){
                    return $this->actionCodes[self::EDIT_SUCCESS];
                }

            }else{
                $response = $this->actionCodes[self::EDIT_FAIL];
                $response["atributeErrors"] = $validations;
                return $response;
            }
        }
        
        return $this->actionCodes[self::EDIT_FAIL];
    }

    public function DELETE(){

        // Do checks for delete if there are any
        if($this->checksForDelete){
            $validations = $this->checkAtributesForDelete();
            if(!$this->checkValidations($validations)){
                $response = $this->actionCodes[self::DELETE_FAIL];
                $response["atributeErrors"] = $validations;
                return $response;
            }
        }

        // If logic delete, only edit logic delete atribute
        if(isset($this->deleteAtribute)){
            $this->atributes[$this->deleteAtribute] = $this->deleteOptions[$this->deleteAtribute];
            $resultCode = $this->EDIT()["code"];
            if($resultCode === $this->getCode("edit","success")){
                return $this->actionCodes[self::DELETE_SUCCESS];
            }else{
                return $this->actionCodes[self::DELETE_FAIL];	   			
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
                return $this->actionCodes[self::DELETE_SUCCESS];
            }
        }

        return $this->actionCodes[self::DELETE_FAIL];	   			
    }

    public function SEARCH($selectQuery = ""){

        // Build the select query if not passed
        if($selectQuery == ""){

            $aux = "";
            foreach(static::$atributesForSearch as $atribute){
                $aux = $aux . $atribute . ",";
            }
            $aux = substr($aux, 0, -1);

            $selectQuery = "SELECT " . $aux . " FROM $this->tableName WHERE (";
            foreach($this->atributes as $key => $value){
                $canBeNull = in_array($key, $this->nullAtributes);
                if( !$canBeNull || $this->atributes[$key] != "" ){
                    $selectQuery = $selectQuery . "( " . $key . " LIKE '%" . $value . "%' ) and ";
                }
            }        
            $selectQuery = substr($selectQuery, 0, -4);
            
            // Add filter for logically deleted if necessary
            if(isset($this->deleteAtribute)){
                $atr = $this->deleteAtribute;
                $opt = $this->deleteOptions[$atr];
                $selectQuery = $selectQuery . " and ( $atr != '$opt' ) "; 
            }

            $selectQuery =  $selectQuery . ")";
        }
       
        // DEBUG: Show sql query
        // echo "<br/>" . $selectQuery . "<br/>";

        // Execute the select query
        $response = $this->executeQuery($selectQuery)["result"];

        if($response !== false){
            // Get tuples from query response 
            $tuples = array();
            while($row = $response->fetch_assoc()){
                array_push($tuples, $row);
            }
            return $tuples;
        } else {
            return $this->actionCodes[self::BAD_QUERY];
        }

    }

    public function SHOW(){

        // Build query
        $selectQuery = "SELECT * FROM $this->tableName WHERE (";
        foreach($this->atributes as $key => $value){
            $canBeNull = in_array($key, $this->nullAtributes);
            if( !$canBeNull || $this->atributes[$key] != "" ){
                $selectQuery = $selectQuery . "( " . $key . " LIKE '%" . $value . "%' ) and ";
            }
        }        
        $selectQuery = substr($selectQuery, 0, -4);
        $selectQuery =  $selectQuery . ")";

        // Execute the select query
        $response = $this->executeQuery($selectQuery)["result"];

        if($response !== false || count($response) > 1 ){
            return $response->fetch_assoc();
        } else {
            return $this->actionCodes[self::BAD_QUERY];
        }
    }

}

?>