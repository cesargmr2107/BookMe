<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include './UsuariosModel.php';


echo "<br/>TEST SEARCH<br/>";
$usuarioSearch = new UsuariosModel();
echo '<pre>' . var_export($usuarioSearch->SEARCH(), true) . '</pre>';

echo "<br/>TEST ADD<br/>";
$usuarioAdd = new UsuariosModel();
$atributesToSet = array( "LOGIN_USUARIO" => "newuser",
                          "PASSWD_USUARIO" => "newuser",
                          "NOMBRE_USUARIO" => "New User",
                          "EMAIL_USUARIO" => "newuser@mail.com",
                          "TIPO_USUARIO" => "NORMAL",
                          "ES_ACTIVO" => "SI" );
$usuarioAdd->setAtributes($atributesToSet);
echo '<pre>' . var_export($usuarioAdd->ADD(), true) . '</pre>';

echo "<br/>TEST UPDATE<br/>";
$usuarioAdd = new UsuariosModel();
$atributesToSet = array( "LOGIN_USUARIO" => "newuser",
                          "PASSWD_USUARIO" => "",
                          "NOMBRE_USUARIO" => "New User Updated",
                          "EMAIL_USUARIO" => "",
                          "TIPO_USUARIO" => "",
                          "ES_ACTIVO" => "" );
$usuarioAdd->setAtributes($atributesToSet);
echo '<pre>' . var_export($usuarioAdd->EDIT(), true) . '</pre>';

/*echo "<br/>TEST DELETE<br/>";
$usuarioDelete = new UsuariosModel();
$atributesToSet = array ("LOGIN_USUARIO" => "newuser");
$usuarioDelete->setAtributes($atributesToSet);
echo '<pre>' . var_export($usuarioDelete->DELETE(), true) . '</pre>';*/

//echo '<pre>' . var_export($usuario->SEARCH(), true) . '</pre>';

?>