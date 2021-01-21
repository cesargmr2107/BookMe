<?php

include_once './VIEW/BaseView.php';

class RecursosShowView extends BaseView{

    protected $jsFiles = array(
        "./VIEW/webroot/libraries/fullcalendar-5.4.0/lib/main.min.js",
        "./VIEW/webroot/libraries/fullcalendar-5.4.0/lib/locales-all.min.js",
        "./VIEW/webroot/js/bookings.js"
    );
    protected $cssFiles = array("./VIEW/webroot/libraries/fullcalendar-5.4.0/lib/main.css");

    protected function body(){
        
        // DEBUG: Check data passed to view
        // echo '<pre>' . var_export($this->data, true) . '</pre>';
        
        $this->includeTitle("i18n-resourceInfo", "h1");



        echo "<div id='resource-info-container'>";
            echo "<div id='resource-info'>";
                $this->includeTitle("i18n-resourceData", "h3");
                echo "<div>";
                $this->includeShowInfo("i18n-id", $this->data["ID_RECURSO"]);
                $this->includeShowInfo("i18n-nombre", $this->data["NOMBRE_RECURSO"]);
                $this->includeShowInfo("i18n-login_responsable", $this->data["LOGIN_RESPONSABLE"]);
                $this->includeShowInfo("i18n-calendar", $this->data["ID_CALENDARIO"]);
                $this->includeShowInfo("i18n-tarifa", $this->data["TARIFA_RECURSO"]);
                $this->includeShowInfo("i18n-rango_tarifa", $this->data["RANGO_TARIFA_RECURSO"]);
                $this->includeShowInfo("i18n-descripcion", $this->data["DESCRIPCION_RECURSO"]);
                echo "</div>";
                // Links
                echo "<div>";
                    $this->includeTitle("i18n-options", "h3");
                    $this->includeButton("CHART", "goToStats", "post", "RecursosController", "stats", array("ID_RECURSO" => $this->data["ID_RECURSO"]));
                echo "</div>";
            echo "</div>";

            echo "<div id='calendar-container'>";
                $this->includeTitle("i18n-availability", "h3");
                $this->includeCalendar($this->data["events"], false);
            echo "</div>";
        echo "</div>";
    }
}
?>