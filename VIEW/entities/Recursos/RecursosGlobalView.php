<?php

include_once './VIEW/BaseView.php';

class RecursosGlobalView extends BaseView{

    protected $jsFiles = array(
        "./VIEW/webroot/libraries/fullcalendar-5.4.0/lib/main.min.js",
        "./VIEW/webroot/libraries/fullcalendar-5.4.0/lib/locales-all.min.js",
        "./VIEW/webroot/js/bookings.js"
    );

    protected $cssFiles = array("./VIEW/webroot/libraries/fullcalendar-5.4.0/lib/main.css");

    protected function body(){
        $this->includeTitle("i18n-resourcesGlobal", "h1");
        echo "<div id='global-calendar'>";
        $this->includeCalendar($this->data["events"], true);
        echo "</div>";

    }

}
?>