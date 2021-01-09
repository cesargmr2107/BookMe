<?php

include_once './VIEW/BaseView.php';

class ReservasAddView extends BaseView{
    
    protected $jsFiles = array(
        "./VIEW/libraries/fullcalendar-5.4.0/lib/main.min.js",
        "./VIEW/libraries/fullcalendar-5.4.0/lib/locales-all.min.js",
        "./VIEW/js/bookings.js"
    );
    
    protected $cssFiles = array("./VIEW/libraries/fullcalendar-5.4.0/lib/main.css");

    protected function body(){
        $this->includeTitle("i18n-newBooking", "h1");

        ?>
            <form id="searchResource" name="searchResource" action="index.php" method="post">
                <?php
                    $id = (array_key_exists("resource_info", $this->data)) ? $this->data["resource_info"]["ID_RECURSO"] : null ;
                    $this->includeSelectField("i18n-selectedResource", "ID_RECURSO", $this->data["resources"], true, $id);
                ?>
                <span class="<?=$this->icons["SEARCH"]?>" onclick="sendForm(document.searchResource, 'ReservasController', 'addForm', true)"></span>
            </form>
        
        <?php
            if(array_key_exists("resource_info", $this->data)){
        ?>
            <div>
                <?php
                    $this->includeTitle("i18n-addInterval" , "h4");
                    $this->includeDateField("i18n-fecha_inicio", "FECHA_INICIO_SUBRESERVA", true);
                    $this->includeDateField("i18n-fecha_fin", "FECHA_FIN_SUBRESERVA", true);
                    $this->includeTimeField("i18n-hora_inicio", "HORA_INICIO_SUBRESERVA", true);
                    $this->includeTimeField("i18n-hora_fin", "HORA_FIN_SUBRESERVA", true);
                ?>
                <span class="<?=$this->icons["ADD"]?>" onclick="addBooking()"></span>
            <div>

            <div id="intervals">
                
            </div>

            <form id="addForm" name="addForm" action="index.php" method="post">
                <?php $this->includeHiddenField("ID_RECURSO", $this->data["resource_info"]["ID_RECURSO"])?>
                <?php $this->includeHiddenField("COSTE_RESERVA", "5")?>
                <?php $this->includeHiddenField("INFO_SUBRESERVAS","{ \"subreservas\" : {} }")?>
                <span class="<?=$this->icons["BOOKING"]?>" onclick="sendForm(document.addForm, 'ReservasController', 'add', true)"></span>
            </form>
            <?php
            
            $events = (array_key_exists("resource_info", $this->data)) ? $this->data["resource_info"]["events"] : array() ;

            // DEBUG: Check    
            // echo '<pre>' . var_export($events, true) . '</pre>';

            $this->includeCalendar($events, false);

        }

    }

}
?>