<?php

include_once './VIEW/BaseView.php';

class ReservasAddView extends BaseView{
    
    protected $jsFiles = array(
        "./VIEW/webroot/libraries/fullcalendar-5.4.0/lib/main.min.js",
        "./VIEW/webroot/libraries/fullcalendar-5.4.0/lib/locales-all.min.js",
        "./VIEW/webroot/js/bookings.js"
    );
    
    protected $cssFiles = array("./VIEW/webroot/libraries/fullcalendar-5.4.0/lib/main.css");

    protected function body(){
        $this->includeTitle("i18n-newBooking", "h1");
        $this->includeValidationModal();
      
        ?>
        <div id='booking-container'>
            <div id='add-form-container'>
                <form id="searchResource" name="searchResource" action="index.php" method="post">
                    <?php
                        $id = (array_key_exists("resource_info", $this->data)) ? $this->data["resource_info"]["ID_RECURSO"] : null ;
                        $this->includeSelectField("i18n-selectedResource", "ID_RECURSO", $this->data["resources"], true, $id);
                    ?>
                    <span class="<?=$this->icons["SEARCH"]?>" onclick="sendForm(document.searchResource, 'ReservasController', 'addForm', checkSearchResource())"></span>
                </form>
                <?php
                    if(array_key_exists("resource_info", $this->data)){
                        $this->includeResourceInfo($this->data["resource_info"]);
                        $this->includeAddForm();
                    }
                ?>
            </div>
            <?php
                if(array_key_exists("resource_info", $this->data)){
                    echo "<div id='calendar-container'>";
                        $this->includeCalendar($this->data["resource_info"]["events"], false);
                    echo "</div>";
                }
            ?>
        </div>
        <?php
/*
        if(array_key_exists("resource_info", $this->data)){
            echo "<div id='booking-container'>";
                
                echo "<div id='add-form-container'>";
                    $this->includeResourceInfo($this->data["resource_info"]);
                    $this->includeAddForm();
                echo "</div>";
            echo "</div>";
        }*/
    }

    protected function includeResourceInfo($info){
        echo "<div id='resource-info'>";
        $this->includeTitle('i18n-resourceInfo', 'h4');
        $this->includeShowInfo('i18n-tarifa', $info['TARIFA_RECURSO'], 'TARIFA_RECURSO');
        $this->includeShowInfo('i18n-rango_tarifa', $info['RANGO_TARIFA_RECURSO'], 'RANGO_TARIFA_RECURSO');
        echo "</div>";
    }

    protected function includeAddForm(){
        ?>
            <div>
                <div class="add-booking-title">
                    <?= $this->includeTitle("i18n-addInterval" , "h4") ?>
                    <span class="<?=$this->icons["ADD"]?>" onclick="if(checkAddIntervalForm()) addBooking()"></span>
                </div>
                <form name="addIntervalForm">
                    <?php
                    echo "<div>";
                        $this->includeDateField("i18n-fecha_inicio", "FECHA_INICIO_SUBRESERVA", true);
                        $this->includeDateField("i18n-fecha_fin", "FECHA_FIN_SUBRESERVA", true);
                    echo "</div>";
                    echo "<div>";
                        $this->includeTimeField("i18n-hora_inicio", "HORA_INICIO_SUBRESERVA");
                        $this->includeTimeField("i18n-hora_fin", "HORA_FIN_SUBRESERVA");
                    echo "</div>";
                    ?>
                </form>
            </div>

            <?= $this->includeTitle("i18n-selectedIntervals" , "h4") ?>
            <div id="intervals"></div>

            <form id="addForm" name="addForm" action="index.php" method="post">
                <?php $this->includeHiddenField("ID_RECURSO", $this->data["resource_info"]["ID_RECURSO"])?>
                <?php $this->includeHiddenField("INFO_SUBRESERVAS","{ \"subreservas\" : {} }")?>
                <h4>
                    <span class="i18n-bookingTotalCost"></span>
                    <span id="totalCost">0.00</span>
                    <span class="<?=$this->icons["BOOKING"]?>" onclick="sendForm(document.addForm, 'ReservasController', 'add', true)"></span>
                </h4>
            </form>
        <?php
    }

}
?>