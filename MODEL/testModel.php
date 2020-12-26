<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include './UsuariosModel.php';
include './CalendariosModel.php';


function testAction($atributes, $entityName, $action, $expectedCode) {

    // Show results
    echo '<h1>--------------------------------------------------------------------</h1>';
    /*echo '<h2>Testing ' . $action . ' for ' . $entityName . '</h2>';
    echo '<h4>Input model object:</h4>';*/
    echo '<pre>' . var_export($atributes, true) . '</pre>';   

}

function testAtributes(){

}

function testModel($entityName, $atributes, $actionsAndCodes){
    
    $entity = new $entityName();
    $entity->setAtributes($atributes);
    
    echo '<h2>Testing model "' . $entityName . '"</h2>';
    echo '<h4>Input model object:</h4>';
    echo '<pre>' . var_export($atributes, true) . '</pre>';
    
    foreach ($actionsAndCodes as $action => $expectedCode) {
        echo '<h4>Result for action ' . $action . ':</h4>';
        $result = $entity->$action();
        if ($action !== 'SEARCH') {
            echo '<p>Expected code is <b>' . $expectedCode . '</b> and obtained code was <b>' . $result["code"] . '</b></p>';
        }
        echo '<pre>' . var_export($result, true) . '</pre>';
    }
}

function testUsuariosModel(){
    
    // Testing proper behaviour

    $actionsAndCodes = array(   "ADD" => "111",
                                "SEARCH" => "none",
                                "EDIT" => "111",
                                "DELETE" => "111" );

    $atributes = array( "LOGIN_USUARIO" => "newuser",
                        "PASSWD_USUARIO" => "0354d89c28ec399c00d3cb2d094cf093",
                        "NOMBRE_USUARIO" => "New User",
                        "EMAIL_USUARIO" => "newuser@mail.com",
                        "TIPO_USUARIO" => "NORMAL",
                        "ES_ACTIVO" => "SI" );

    testModel("UsuariosModel", $atributes, $actionsAndCodes);

    // Testing error behaviour

    $actionsAndCodes = array(   "ADD" => "222",
                                "SEARCH" => "none",
                                "EDIT" => "222",
                                "DELETE" => "222" );

    $atributes = array( "LOGIN_USUARIO" => "15logindemasiadolargo",
                        "PASSWD_USUARIO" => "",
                        "NOMBRE_USUARIO" => "New User",
                        "EMAIL_USUARIO" => "newuser",
                        "TIPO_USUARIO" => "BLA",
                        "ES_ACTIVO" => "BLA" );

    testModel("UsuariosModel", $atributes, $actionsAndCodes);
}

function testCalendariosModel(){

    // Testing proper behaviour

    $actionsAndCodes = array(   "ADD" => "333",
                                "SEARCH" => "none",
                                "EDIT" => "333",
                                "DELETE" => "333" );

    $atributes = array( "NOMBRE_CALENDARIO" => "Calendario curso 2020/21",
                        "DESCRIPCION_CALENDARIO" => "Este es el calendario para el curso académico 2020/21",
                        "FECHA_INICIO_CALENDARIO" => "2020-09-21",
                        "FECHA_FIN_CALENDARIO" => "2021-06-21",
                        "HORA_INICIO_CALENDARIO" => "08:00", 
                        "HORA_FIN_CALENDARIO" => "22:00" );

    testModel("CalendariosModel", $atributes, $actionsAndCodes);

    // Testing error behaviour

    $actionsAndCodes = array(   "ADD" => "222",
    "SEARCH" => "none",
    "EDIT" => "222",
    "DELETE" => "222" );

}


function test(){

    //testUsuariosModel();
    testCalendariosModel();    

}

test();

?>