<?php

include_once './VIEW/BaseView.php';

class UsuariosSearchView extends BaseView{

    protected function body(){
        $this->includeTitle("Usuarios en el sistema", "h1");
        $this->includeButton("ADD", "goToAddForm", "post", "UsuariosController", "addForm");
        $optionsData = array(
            "idAtribute" => "LOGIN_USUARIO",
            "nameAtribute" => "LOGIN_USUARIO",
            "controller" => "UsuariosController"
        );
        $this->includeCrudTable($optionsData);
    }
}
?>