<?php

include_once './VIEW/BaseView.php';

class RecursosShowView extends BaseView{

    protected function body(){
        
        // DEBUG: Check data passed to view
        // echo '<pre>' . var_export($this->data, true) . '</pre>';
        
        $this->includeTitle("Detalles de recurso", "h1");

        echo "<div>";
            $this->includeTitle("Datos del recurso", "h3");
            $this->includeShowInfo("ID", $this->data["ID_RECURSO"]);
            $this->includeShowInfo("Nombre", $this->data["NOMBRE_RECURSO"]);
            $this->includeShowInfo("Responsable", $this->data["LOGIN_RESPONSABLE"]);
            $this->includeShowInfo("Calendario de uso", $this->data["ID_CALENDARIO"]);
            $this->includeShowInfo("Tarifa (€)", $this->data["TARIFA_RECURSO"]);
            $this->includeShowInfo("Rango de tarifa", $this->data["RANGO_TARIFA_RECURSO"]);
            $this->includeShowInfo("Descripción", $this->data["DESCRIPCION_RECURSO"]);
        echo "</div>";
    }
}
?>