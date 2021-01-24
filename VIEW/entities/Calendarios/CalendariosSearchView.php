<?php

include_once './VIEW/BaseView.php';

class CalendariosSearchView extends BaseView{

    protected function body(){
        $this->includeTitle("i18n-calendarsSearch", "h1");
        echo "<div id='search-bar'>";
            $this->includeSearchBar("NOMBRE_CALENDARIO","i18n-searchByNOMBRE_CALENDARIO", "CalendariosController");
            $this->includeButton("ADD", "goToAddForm", "post", "CalendariosController", "addForm");
        echo "</div>";
        $this->includeFilters("nombre");
        $optionsData = array(
            "idAtribute" => "ID_CALENDARIO",
            "nameAtribute" => "NOMBRE_CALENDARIO",
            "controller" => "CalendariosController"
        );
        $this->includeCrudTable($optionsData);
    }
}
?>