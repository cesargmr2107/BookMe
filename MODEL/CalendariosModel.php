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
        "HORA_FIN_CALENDARIO"
    );

    // Define which atributes will be selected in search
    protected static $atributesForSearch = array (  "ID_CALENDARIO",
                                                    "NOMBRE_CALENDARIO",
                                                    "FECHA_INICIO_CALENDARIO",
                                                    "FECHA_FIN_CALENDARIO");

    function __construct (){

        // Call parent constructor
        parent::__construct();
        
        // Overwrite action codes
        $this->actionMsgs[parent::ADD_SUCCESS]["code"] = "333";
        $this->actionMsgs[parent::ADD_FAIL]["code"] = "333";

        $this->actionMsgs[parent::EDIT_SUCCESS]["code"] = "333";
        $this->actionMsgs[parent::EDIT_FAIL]["code"] = "333";

        $this->actionMsgs[parent::DELETE_SUCCESS]["code"] = "333";
        $this->actionMsgs[parent::EDIT_FAIL]["code"] = "333";

        // Define DB table
        $this->tableName = "CALENDARIOS_DE_USO";

        // Define primary key
        $this->primary_key = "ID_CALENDARIO";


        // Subscribe atributes to validations                          
        $this->checks = array (
            "ID_CALENDARIO" => array(
                "checkAutoKey" => array('ID_CALENDARIO', '222', 'El id del calendario (gestionado por el sistema) es un entero'),
            ),
            "NOMBRE_CALENDARIO" => array(
                "checkSize" => array('NOMBRE_CALENDARIO', 6, 40, '222', 'El nombre debe tener entre 6 y 40 caracteres'),
            ),
            "DESCRIPCION_CALENDARIO" => array(
                "checkSize" => array('NOMBRE_CALENDARIO', 10, 200, '222', 'La descripción debe tener entre 10 y 200 caracteres'),
            ),
            "FECHA_INICIO_CALENDARIO" => array(
                "checkDate" => array('FECHA_INICIO_CALENDARIO', '222', 'La fecha debe tener el formato dd-mm-yyyy')
            ),
            "FECHA_FIN_CALENDARIO" => array( 
                "checkDate" => array('FECHA_FIN_CALENDARIO', '222', 'La fecha debe tener el formato dd-mm-yyyy'),
                "checkDateInterval" => array('FECHA_INICIO_CALENDARIO', 'FECHA_FIN_CALENDARIO', '222', 'La fecha de inicio debe ser anterior a la fecha de fin')
            ),
            "HORA_INICIO_CALENDARIO" => array(
                "checkTime" => array('HORA_INICIO_CALENDARIO', '222', 'La hora debe tener el formato hh:mm')
            ),
            "HORA_FIN_CALENDARIO" => array( 
                "checkTime" => array('HORA_FIN_CALENDARIO', '222', 'La hora debe tener el formato hh:mm')
            )
        );


        $this->checksForDelete = array(
            "ID_CALENDARIO" => array(
                "checkNoAssoc" => array('ID_CALENDARIO', "RecursosModel", '222', 'No se puede borrar un calendario con recursos asociados')
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