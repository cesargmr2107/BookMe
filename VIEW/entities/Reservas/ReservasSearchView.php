<?php

include_once './VIEW/BaseView.php';

class ReservasSearchView extends BaseView{

    protected function body(){
        $this->includeTitle("i18n-bookingHistory", "h1");
        echo "<div id='search-bar'>";
            $this->includeSearchBar("ID_RESERVA", "i18n-searchByID_RESERVA", "ReservasController");
            $this->includeButton("ADD", "goToAddForm", "post", "ReservasController", "addForm");
        echo "</div>";
        $this->includeFilters("id");
        $optionsData = array(
            "idAtribute" => "ID_RESERVA",
            "nameAtribute" => "ID_RESERVA",
            "controller" => "ReservasController"
        );
        $this->includeCrudTable($optionsData);
    }

    protected function includeOptions($optionsData)
    {
        // Get data
        $idAtribute = $optionsData["idAtribute"];
        $id = $optionsData["row"][$idAtribute];
        $nameAtribute = $optionsData["nameAtribute"];
        $name = $optionsData["row"][$nameAtribute];
        $controller = $optionsData["controller"];

        echo "<td id='row-options'>";
            $this->includeButton("SHOW", "goToShow$id", "post", $controller, "show", array ($idAtribute => $id) );
        echo '</td>';
    }
}
?>