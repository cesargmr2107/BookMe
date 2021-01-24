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
}
?>