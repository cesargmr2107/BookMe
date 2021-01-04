<?php

include_once './VIEW/BaseView.php';

class RecursosSearchView extends BaseView{

    protected function body(){
        $this->includeTitle("Recursos en el sistema", "h1");
        $this->includeButton("ADD", "goToAddForm", "post", "RecursosController", "addForm");
        $optionsData = array(
            "idAtribute" => "ID_RECURSO",
            "nameAtribute" => "NOMBRE_RECURSO",
            "controller" => "RecursosController"
        );
        $this->includeCrudTable($optionsData);
    }
}
?>