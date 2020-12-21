<?php

class BaseModel {

    private $connection;
    protected $atributes;
    protected $tableName;

   function __construct($atributes){
        $this->atributes = $atributes;
    }

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
            if($response) {
                
            } else {

            }

            $this->closeConnection();
        }
        return $response;
    }


    protected function ADD(){
             

    }

    protected function EDIT(){

    }

    protected function DELETE(){

    }

    public function SEARCH(){

        echo "I got here";

        $selectQuery = "SELECT * FROM $this->tableName WHERE (";
        foreach($this->atributes as $key => $value){
            $selectQuery = $selectQuery . "( " . $key . " LIKE '" .$value . "' ) and ";
        }
        
        $selectQuery = substr($selectQuery, 0, -4);

        $selectQuery =  $selectQuery . ")";

        return $this->executeQuery($selectQuery);
    }


}

?>