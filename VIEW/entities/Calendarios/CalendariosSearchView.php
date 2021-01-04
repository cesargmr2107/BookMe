<?php

include_once './VIEW/BaseView.php';

class CalendariosSearchView extends BaseView{

    protected function body(){
        $this->includeTitle("Calendarios en el sistema", "h1");
        $this->includeButton("ADD", "goToAddForm", "post", "CalendariosController", "addForm");
        $optionsData = array(
            "idAtribute" => "ID_CALENDARIO",
            "nameAtribute" => "NOMBRE_CALENDARIO",
            "controller" => "CalendariosController"
        );
        $this->includeCrudTable($optionsData);
    }
}
?>