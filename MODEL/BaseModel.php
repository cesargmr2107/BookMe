<?php

class BaseModel {

    private $connection;
    protected $atributes;
    protected $primary_key;
    protected $tableName;

    private function openConnection(){
		return($this->connection = new mysqli('localhost', 'pma', 'iu', '53196285E') or die('fallo conexion'));
	}

    private function closeConnection(){
		$this->connection->close();
    }
    
    private function executeQuery($query){
        $isConnected = $this->openConnection();
        if (!$isConnected) {

        } else {
            $response = $this->connection->query($query);
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


    /*
        Adds a new ModelObject to the DB using the atributes stored in $this->atributes
    */
    public function ADD(){

        // Build the insert query
        $insertQuery = "INSERT INTO $this->tableName VALUES ( ";
        foreach ($this->atributes as $key => $value) {
            $insertQuery = $insertQuery . " '" . $value . "' ,";
        }
        $insertQuery = substr($insertQuery, 0, -1);
        $insertQuery =  $insertQuery . " )";

        // DEBUG: Show sql query
        // echo "<br/>" . $insertQuery . "<br/>";

        return $this->executeQuery($insertQuery);
        
    }

    public function EDIT(){

        // Get value that will be used for delete
        $updateKey = $this->atributes[$this->primary_key];
        
        if($updateKey){

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
            return $this->executeQuery($updateQuery);
        }
        
        return false;
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

            return $this->executeQuery($deleteQuery);
        }

        return false;		   			
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