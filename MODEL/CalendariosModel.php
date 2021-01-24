<?php

include_once './MODEL/BaseModel.php';

class CalendariosModel extends BaseModel {

    // Define atributes
    public static $atributeNames = array(
        "ID_CALENDARIO",
        "NOMBRE_CALENDARIO",
        "DESCRIPCION_CALENDARIO",
        "FECHA_INICIO_CALENDARIO",
        "FECHA_FIN_CALENDARIO",
        "HORA_INICIO_CALENDARIO", 
        "HORA_FIN_CALENDARIO",
        "BORRADO_LOGICO",
    );

    // Define which atributes will be selected in search
    public static $atributesForSearch = array (  "ID_CALENDARIO",
                                                    "NOMBRE_CALENDARIO",
                                                    "FECHA_INICIO_CALENDARIO",
                                                    "FECHA_FIN_CALENDARIO");

    function __construct (){

        // Call parent constructor
        parent::__construct();
        
        // Overwrite action codes
        $this->actionCodes[parent::ADD_SUCCESS]["code"] = "AC111";
        $this->actionCodes[parent::ADD_FAIL]["code"] = "AC011";

        $this->actionCodes[parent::EDIT_SUCCESS]["code"] = "AC112";
        $this->actionCodes[parent::EDIT_FAIL]["code"] = "AC012";

        $this->actionCodes[parent::DELETE_SUCCESS]["code"] = "AC113";
        $this->actionCodes[parent::DELETE_FAIL]["code"] = "AC013";

        // Define DB table
        $this->tableName = "CALENDARIOS_DE_USO";

        // Define primary key
        $this->primary_key = "ID_CALENDARIO";

        $this->defaultValues = array( "BORRADO_LOGICO" => "NO" );

        $this->deleteAtribute = "BORRADO_LOGICO";

        // Subscribe atributes to validations                          
        $this->checks = array (
            "ID_CALENDARIO" => array(
                "checkAutoKey" => array('ID_CALENDARIO', 'AT101'),
            ),
            "NOMBRE_CALENDARIO" => array(
                "checkSize" => array('NOMBRE_CALENDARIO', 6, 40, 'AT111'),
                "checkRegex" => array('NOMBRE_CALENDARIO', '/^[ÁÉÍÓÚÜáéíóúüÑña-zA-Z ]+$/', 'AT112')
            ),
            "DESCRIPCION_CALENDARIO" => array(
                "checkSize" => array('DESCRIPCION_CALENDARIO', 0, 100, 'AT121'),
                "checkRegex" => array('DESCRIPCION_CALENDARIO', '/^[ÁÉÍÓÚÜáéíóúüÑña-zA-Z ]+$/', 'AT122')
            ),
            "FECHA_INICIO_CALENDARIO" => array(
                "checkDate" => array('FECHA_INICIO_CALENDARIO', 'AT131')
            ),
            "FECHA_FIN_CALENDARIO" => array( 
                "checkDate" => array('FECHA_FIN_CALENDARIO', 'AT141'),
                "checkDateInterval" => array('FECHA_INICIO_CALENDARIO', 'FECHA_FIN_CALENDARIO', 'AT142')
            ),
            "HORA_INICIO_CALENDARIO" => array(
                "checkTime" => array('HORA_INICIO_CALENDARIO', 'AT151')
            ),
            "HORA_FIN_CALENDARIO" => array( 
                "checkTime" => array('HORA_FIN_CALENDARIO', 'AT161')
            ),
            "BORRADO_LOGICO" => array(
                "checkYesOrNo" => array('BORRADO_LOGICO', 'AT171')
            )
        );

        $this->checksForDelete = array(
            "ID_CALENDARIO" => array(
                "checkNoAssoc" => array('ID_CALENDARIO', "RecursosModel", 'AT102')
            )
        );
        
    }

    public function SHOW(){
        $result = parent::SHOW();

        include_once './MODEL/RecursosModel.php';
		$resourcesSearch = new RecursosModel();
        $query = "SELECT ID_RECURSO, NOMBRE_RECURSO FROM RECURSOS WHERE ID_CALENDARIO = " . $this->atributes["ID_CALENDARIO"];
        $result["resources"] = $resourcesSearch->SEARCH($query);

        return $result;
    }



}

?>