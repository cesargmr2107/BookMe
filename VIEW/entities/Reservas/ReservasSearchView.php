<?php

include_once './VIEW/BaseView.php';

class ReservasSearchView extends BaseView{

    protected function body(){
        $this->includeTitle("i18n-bookingHistory", "h1");
        $optionsData = array(
            "idAtribute" => "ID_RESERVA",
            "nameAtribute" => "ID_RESERVA",
            "controller" => "ReservasController"
        );
        $this->includeCrudTable($optionsData);
    }
}
?>