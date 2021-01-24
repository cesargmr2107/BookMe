<?php

include_once './VIEW/BaseView.php';

class UsuariosSearchView extends BaseView{

    protected function body(){
        $this->includeTitle("i18n-usersSearch", "h1");
        echo "<div id='search-bar'>";
            $this->includeSearchBar("LOGIN_USUARIO", "i18n-searchByLOGIN_USUARIO", "UsuariosController");
            $this->includeButton("ADD", "goToAddForm", "post", "UsuariosController", "addForm");
        echo "</div>";
        $this->includeFilters("login");
        $optionsData = array(
            "idAtribute" => "LOGIN_USUARIO",
            "nameAtribute" => "LOGIN_USUARIO",
            "controller" => "UsuariosController"
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
            if(isAdminUser()){
                $this->includeButton("EDIT", "editBt$id", "post", $controller, "editForm", array ($idAtribute => $id));
                if($_SESSION["LOGIN_USUARIO"] !== $optionsData["row"]["LOGIN_USUARIO"]){
                    $this->includeDeleteButtonAndModal($idAtribute, $id, $name, $controller);
                }
            }
        echo '</td>';
    }
}
?>