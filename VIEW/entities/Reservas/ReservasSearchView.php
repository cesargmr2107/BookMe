<?php

include_once './VIEW/BaseView.php';

class ReservasSearchView extends BaseView{

    protected function body(){
        $this->includeTitle("Reservas en el sistema", "h1");
        $this->includeCrudTable(array("ID_RESERVA"));
    }
}
?>