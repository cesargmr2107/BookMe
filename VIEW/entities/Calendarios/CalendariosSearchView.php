<?php

include_once './VIEW/BaseView.php';

class CalendariosSearchView extends BaseView{

    protected function body(){
        $this->includeButton("ADD", "goToAddForm", "post", "CalendariosController", "addForm");
        $this->includeCrudTable("ID_CALENDARIO", "NOMBRE_CALENDARIO", "CalendariosController");
    }
}
?>