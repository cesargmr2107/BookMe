<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include './UsuariosModel.php';


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

function test(){

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

    $actionsAndCodes = array(   /*"ADD" => "222",
                        "SEARCH" => "none",
                        "EDIT" => "222",*/
                        "DELETE" => "222" );

    $atributes = array( "LOGIN_USUARIO" => "12newuser",
                        "PASSWD_USUARIO" => "",
                        "NOMBRE_USUARIO" => "New_User",
                        "EMAIL_USUARIO" => "newuser@mail.com",
                        "TIPO_USUARIO" => "NORMAL",
                        "ES_ACTIVO" => "SI" );

    testModel("UsuariosModel", $atributes, $actionsAndCodes);

}

test();

/*echo "<br/>TEST SEARCH<br/>";
$usuarioSearch = new UsuariosModel();
echo '<pre>' . var_export($usuarioSearch->SEARCH(), true) . '</pre>';*/

/*echo "<br/>TEST ADD<br/>";
$usuarioAdd = new UsuariosModel();
$atributesToSet = array( "LOGIN_USUARIO" => "newuser",
                          "PASSWD_USUARIO" => "0354d89c28ec399c00d3cb2d094cf093",
                          "NOMBRE_USUARIO" => "New User",
                          "EMAIL_USUARIO" => "newuser@mail.com",
                          "TIPO_USUARIO" => "NORMAL",
                          "ES_ACTIVO" => "SI" );
$usuarioAdd->setAtributes($atributesToSet);
echo '<pre>' . var_export($usuarioAdd->ADD(), true) . '</pre>';*/

/*echo "<br/>TEST UPDATE<br/>";
$usuarioAdd = new UsuariosModel();
$atributesToSet = array( "LOGIN_USUARIO" => "newuser",
                          "PASSWD_USUARIO" => "",
                          "NOMBRE_USUARIO" => "New User Updated",
                          "EMAIL_USUARIO" => "",
                          "TIPO_USUARIO" => "",
                          "ES_ACTIVO" => "" );
$usuarioAdd->setAtributes($atributesToSet);
echo '<pre>' . var_export($usuarioAdd->EDIT(), true) . '</pre>';

echo "<br/>TEST DELETE<br/>";
$usuarioDelete = new UsuariosModel();
$atributesToSet = array ("LOGIN_USUARIO" => "newuser");
$usuarioDelete->setAtributes($atributesToSet);
echo '<pre>' . var_export($usuarioDelete->DELETE(), true) . '</pre>';*/


/*$usuarioCheck = new UsuariosModel();
$atributesToSet = array( "LOGIN_USUARIO" => "5hola",
                          "PASSWD_USUARIO" => "",
                          "NOMBRE_USUARIO" => "New User Updated",
                          "EMAIL_USUARIO" => "",
                          "TIPO_USUARIO" => "",
                          "ES_ACTIVO" => "" );
$usuarioCheck->setAtributes($atributesToSet);
$result = $usuarioCheck->checkAtributes();
echo '<pre>' . var_export($result, true) . '</pre>';
if ($usuarioCheck->checkValidations($result)){
    echo "All good <br/>";
}else{
    echo "Problems <br/>";
}
*/
?>