<?php

include_once './VIEW/BaseView.php';

class RecursosSearchView extends BaseView{

    protected function body(){
        $this->includeTitle("Recursos en el sistema", "h1");
        $this->includeButton("ADD", "goToAddForm", "post", "RecursosController", "addForm");
        $this->includeCrudTable("ID_RECURSO", "NOMBRE_RECURSO", "RecursosController");
    }
}
?>