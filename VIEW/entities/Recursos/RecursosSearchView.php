<?php

include_once './VIEW/BaseView.php';

class RecursosSearchView extends BaseView{

    protected function body(){
        $this->includeTitle("i18n-resourcesSearch", "h1");
        echo "<div id='search-bar'>";
            $this->includeSearchBar("NOMBRE_RECURSO","i18n-searchByNOMBRE_RECURSO", "RecursosController");
            $this->includeButton("ADD", "goToAddForm", "post", "RecursosController", "addForm");
        echo "</div>";
        $this->includeFilters("nombre");
        $optionsData = array(
            "idAtribute" => "ID_RECURSO",
            "nameAtribute" => "NOMBRE_RECURSO",
            "controller" => "RecursosController"
        );
        $this->includeCrudTable($optionsData);
    }
}
?>