<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include './UsuariosModel.php';
include './CalendariosModel.php';
include './ResponsablesModel.php';
include './RecursosModel.php';
include './ReservasModel.php';
include './SubreservasModel.php';

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

function testResponsablesModel(){
    
    // Testing proper behaviour

    // Testing ADD
    $actionsAndCodes = array(   "ADD" => "555",
                                "SEARCH" => "none",
                                "DELETE" => "555" );

    $atributes = array( "LOGIN_RESPONSABLE" => "emmolina15",
                        "DIRECCION_RESPONSABLE" => "Avenida Castelao 15 3A, 36209 Vigo",
                        "TELEFONO_RESPONSABLE" => "666555111" );

    testModel("ResponsablesModel", $atributes, $actionsAndCodes);

    // Testing EDIT
    $actionsAndCodes = array(   "SEARCH" => "none",
                                "EDIT" => "555",
                                "SEARCH" => "none" );

    $atributes = array( "LOGIN_RESPONSABLE" => "resp1",
                        "DIRECCION_RESPONSABLE" => "Avenida Castelao 15 3A, 36209 Vigo",
                        "TELEFONO_RESPONSABLE" => "666555111" );

    testModel("ResponsablesModel", $atributes, $actionsAndCodes);

    // Testing error behaviour

    $actionsAndCodes = array(   "ADD" => "222",
                                "SEARCH" => "none",
                                "EDIT" => "222",
                                "DELETE" => "222" );

    $atributes = array( "LOGIN_RESPONSABLE" => "cesarino",
                        "DIRECCION_RESPONSABLE" => "Avenida Castelao 15 3A, 36209 Vigo",
                        "TELEFONO_RESPONSABLE" => "666555111" );

    testModel("ResponsablesModel", $atributes, $actionsAndCodes);

    // Testing DELETE

    $actionsAndCodes = array(   "SEARCH" => "none",
                                "DELETE" => "333",
                                "SEARCH" => "none" );

    $atributes = array( "LOGIN_RESPONSABLE" => "resp1" );

    testModel("ResponsablesModel", $atributes, $actionsAndCodes);

}

function testCalendariosModel(){

    // Testing proper behaviour

    // Testing ADD

    $actionsAndCodes = array(   "ADD" => "333",
                                "SEARCH" => "none" );

    $atributes = array( "NOMBRE_CALENDARIO" => "Calendario curso 2020/21",
                        "DESCRIPCION_CALENDARIO" => "Este es el calendario para el curso académico 2020/21",
                        "FECHA_INICIO_CALENDARIO" => "2020-09-21",
                        "FECHA_FIN_CALENDARIO" => "2021-06-21",
                        "HORA_INICIO_CALENDARIO" => "08:00", 
                        "HORA_FIN_CALENDARIO" => "22:00" );

    testModel("CalendariosModel", $atributes, $actionsAndCodes);

    // Testing EDIT

    $actionsAndCodes = array(   "EDIT" => "333",
                                "SEARCH" => "none" );

    $atributes = array( "ID_CALENDARIO" => "1",
                        "HORA_FIN_CALENDARIO" => "20:00" );

    testModel("CalendariosModel", $atributes, $actionsAndCodes);

    // Testing DELETE
    
    $actionsAndCodes = array(   "SEARCH" => "none",
                                "DELETE" => "333",
                                "SEARCH" => "none" );

    $atributes = array( "ID_CALENDARIO" => "5" );

    testModel("CalendariosModel", $atributes, $actionsAndCodes);

    // Testing error behaviour

    $actionsAndCodes = array(   "ADD" => "222",
    "SEARCH" => "none",
    "EDIT" => "222",
    "DELETE" => "222" );

    $actionsAndCodes = array(   "SEARCH" => "none",
                                "DELETE" => "333",
                                "SEARCH" => "none" );

    $atributes = array( "ID_CALENDARIO" => "1" );

    testModel("CalendariosModel", $atributes, $actionsAndCodes);

}

function testRecursosModel(){

    // Testing proper behaviour

    // Testing ADD

    $actionsAndCodes = array(   "ADD" => "333",
                                "SEARCH" => "none" );

    $atributes = array( "NOMBRE_RECURSO" => "Nuevo recurso",
                        "DESCRIPCION_RECURSO" => "Este es un nuevo recurso",
                        "TARIFA_RECURSO" => "10",
                        "RANGO_TARIFA_RECURSO" => "SEMANA",
                        "ID_CALENDARIO" => "1",
                        "LOGIN_RESPONSABLE" => "resp1" );

    testModel("RecursosModel", $atributes, $actionsAndCodes);

    // Testing EDIT

    $actionsAndCodes = array(   "EDIT" => "333",
                                "SEARCH" => "none" );

    $atributes = array( "ID_RECURSO" => "1",
                        "TARIFA_RECURSO" => "100" );

    testModel("RecursosModel", $atributes, $actionsAndCodes);

    // Testing DELETE
    
    $actionsAndCodes = array(   "SEARCH" => "none",
                                "DELETE" => "333",
                                "SEARCH" => "none" );

    $atributes = array( "ID_RECURSO" => "7" );

    testModel("RecursosModel", $atributes, $actionsAndCodes);

    // Testing error behaviour

    $actionsAndCodes = array(   "ADD" => "222",
    "SEARCH" => "none",
    "EDIT" => "222",
    "DELETE" => "222" );

    // Testing ADD

    $actionsAndCodes = array(   "ADD" => "333",
                                "SEARCH" => "none" );

    $atributes = array( "NOMBRE_RECURSO" => "Nuevo recurso incorrecto",
                        "DESCRIPCION_RECURSO" => "Este es un nuevo recurso incorrecto",
                        "TARIFA_RECURSO" => "10",
                        "RANGO_TARIFA_RECURSO" => "SEMANA",
                        "ID_CALENDARIO" => "10",
                        "LOGIN_RESPONSABLE" => "resp10" );

    testModel("RecursosModel", $atributes, $actionsAndCodes);

    // Testing DELETE

    $actionsAndCodes = array(   "SEARCH" => "none",
                                "DELETE" => "333",
                                "SEARCH" => "none" );

    $atributes = array( "ID_RECURSO" => "1" );

    testModel("RecursosModel", $atributes, $actionsAndCodes);

}

function testReservasModel(){

    // Testing proper behaviour

    // Testing ADD

    $actionsAndCodes = array(   "ADD" => "999",
                                "SEARCH" => "none" );

    $atributes = array( "LOGIN_USUARIO" => "emmolina15",
                        "ID_RECURSO" => "1",
                        "FECHA_SOLICITUD_RESERVA" => "2020-12-27",
                        "COSTE_RESERVA" => "5" );

    testModel("ReservasModel", $atributes, $actionsAndCodes);

    // Testing EDIT

    $actionsAndCodes = array(   "EDIT" => "999",
                                "SEARCH" => "none" );

    $atributes = array( "ID_RESERVA" => "3",
                        "ESTADO_RESERVA" => "ACEPTADA"  );

    testModel("ReservasModel", $atributes, $actionsAndCodes);

    // Testing DELETE
    
    $actionsAndCodes = array(   "SEARCH" => "none",
                                "DELETE" => "333",
                                "SEARCH" => "none" );

    $atributes = array( "ID_RESERVA" => "3" );

    testModel("ReservasModel", $atributes, $actionsAndCodes);

    // Testing error behaviour

    $actionsAndCodes = array(   "ADD" => "222",
                                "SEARCH" => "none",
                                "EDIT" => "222",
                                "DELETE" => "222" );

}

function testSubreservasModel(){

    // Testing proper behaviour

    // Testing ADD

    $actionsAndCodes = array(   "ADD" => "1111",
                                "SEARCH" => "none" );

    $atributes = array( "ID_RESERVA" => "1",
                        "FECHA_INICIO_SUBRESERVA" => "2021-01-21",
                        "FECHA_FIN_SUBRESERVA" => "2021-01-25",
                        "HORA_INICIO_SUBRESERVA" => "10:00",
                        "HORA_FIN_SUBRESERVA" => "14:00",
                        "COSTE_SUBRESERVA" => "30" );

    testModel("SubreservasModel", $atributes, $actionsAndCodes);

    // Testing EDIT

    $actionsAndCodes = array(   "EDIT" => "1111",
                                "SEARCH" => "none" );

    $atributes = array( "ID_RESERVA" => "1",
                        "ID_SUBRESERVA" => "3",
                        "HORA_FIN_SUBRESERVA" => "15:00" );

    testModel("SubreservasModel", $atributes, $actionsAndCodes);

    // Testing DELETE
    
    $actionsAndCodes = array(   "SEARCH" => "none",
                                "DELETE" => "1111",
                                "SEARCH" => "none" );

    $atributes = array( "ID_RESERVA" => "1",
                        "ID_SUBRESERVA" => "3", );

    testModel("SubreservasModel", $atributes, $actionsAndCodes);

    // Testing error behaviour

    $actionsAndCodes = array(   "ADD" => "222",
                                "SEARCH" => "none",
                                "EDIT" => "222",
                                "DELETE" => "222" );

}

function test(){

    //testUsuariosModel();
    //testCalendariosModel();    
    //testResponsablesModel();    
    //testRecursosModel();
    //testReservasModel();
    //testSubreservasModel();

}

test();

?>