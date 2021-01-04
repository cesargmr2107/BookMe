<?php

include_once './VIEW/BaseView.php';

class RecursosShowView extends BaseView{

    protected $jsFiles = array("./VIEW/libraries/fullcalendar-5.4.0/lib/main.js");
    protected $cssFiles = array("./VIEW/libraries/fullcalendar-5.4.0/lib/main.css");

    protected function body(){
        
        // DEBUG: Check data passed to view
        echo '<pre>' . var_export($this->data, true) . '</pre>';
        
        $this->includeTitle("Detalles de recurso", "h1");

        // Links
        echo "<div>";
            $this->includeButton("CHART", "goToStats", "post", "RecursosController", "stats", array("ID_RECURSO" => $this->data["ID_RECURSO"]));
        echo "</div>";

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

        echo "<div>";
            $this->includeTitle("Disponibilidad y Ocupación", "h3");
            $this->includeCalendar($this->data["events"], false);
        echo "</div>";
 
    }
}
?>