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

    protected function includeOptions($optionsData){
        // Get data
        $idAtribute = $optionsData["idAtribute"];
        $id = $optionsData["row"][$idAtribute];
        $nameAtribute = $optionsData["nameAtribute"];
        $name = $optionsData["row"][$nameAtribute];
        $controller = $optionsData["controller"];

        echo "<td id='row-options'>";
            $this->includeButton("SHOW", "goToShow$id", "post", $controller, "show", array ($idAtribute => $id) );
            $this->includeButton("CHART", "goToStats$id", "post", $controller, "stats", array ($idAtribute => $id));
            if(isAdminUser()){
                $this->includeButton("EDIT", "editBt$id", "post", $controller, "editForm", array ($idAtribute => $id));
                $this->includeDeleteButtonAndModal($idAtribute, $id, $name, $controller);
            }else if(isRespUser() && $_SESSION["LOGIN_USUARIO"] === $optionsData["row"]["LOGIN_RESPONSABLE"]){
                $this->includeButton("EDIT", "editBt$id", "post", $controller, "editForm", array ($idAtribute => $id));
            }
        echo '</td>';
    }
}
?>