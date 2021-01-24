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

    }

    protected function includeResourceInfo($info){
        echo "<div id='resource-details'>";
            echo "<div>";
                $this->includeTitle('i18n-resourceInfo', 'h4');
                $this->includeShowInfo('i18n-tarifa', $info['TARIFA_RECURSO'], 'TARIFA_RECURSO');
                $this->includeShowInfo('i18n-rango_tarifa', $info['RANGO_TARIFA_RECURSO'], 'RANGO_TARIFA_RECURSO');
            echo "</div>";
            echo "<div>";
                $this->includeTitle('i18n-calendarInfo', 'h4');
                $startDate = $this->formatDate($info["calendar-info"]['FECHA_INICIO_CALENDARIO']);
                $endDate = $this->formatDate($info["calendar-info"]['FECHA_FIN_CALENDARIO']);
                ?>
                    <p><strong class='i18n-nombre'></strong>: <?=$info["calendar-info"]['NOMBRE_CALENDARIO']?></p>
                    <p><strong class='i18n-fechas'></strong><span id="cal-start-date"><?=$startDate?></span> - <span id="cal-end-date"><?=$endDate?></span></p>
                    <p><strong class='i18n-horas'></strong><span id="cal-start-time"><?=$info["calendar-info"]['HORA_INICIO_CALENDARIO']?></span> - <span id="cal-end-time"><?=$info["calendar-info"]['HORA_FIN_CALENDARIO']?></span></p>
                <?php
            echo "</div>";
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
                        $minDate = $this->getMinDate();
                        $maxDate = $this->data["resource_info"]["calendar-info"]['FECHA_FIN_CALENDARIO'];
                        $this->includeDateField("i18n-fecha_inicio", "FECHA_INICIO_SUBRESERVA", $minDate, $maxDate);
                        $this->includeDateField("i18n-fecha_fin", "FECHA_FIN_SUBRESERVA", $minDate, $maxDate);
                    echo "</div>";
                    echo "<div>";
                        $minHour = $this->getTime($this->data["resource_info"]["calendar-info"]['HORA_INICIO_CALENDARIO']);
                        $maxHour = $this->getTime($this->data["resource_info"]["calendar-info"]['HORA_FIN_CALENDARIO']);
                        $this->includeTimeField("i18n-hora_inicio", "HORA_INICIO_SUBRESERVA", $minHour, $maxHour);
                        $this->includeTimeField("i18n-hora_fin", "HORA_FIN_SUBRESERVA", $minHour, $maxHour);
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
                    <span class="<?=$this->icons["BOOKING"]?>" onclick="sendForm(document.addForm, 'ReservasController', 'add', checkAddBookingForm())"></span>
                </h4>
            </form>
        <?php
    }

    private function getTime($time){
        return intval(explode(":",$time)[0]);
    }

    private function getMinDate(){
        $calendarMinDate = $this->data["resource_info"]["calendar-info"]['FECHA_INICIO_CALENDARIO'];
        $d = DateTime::createFromFormat('Y-m-d', $calendarMinDate);
        $today = new DateTime();
        return ($d > $today) ? $calendarMinDate : date_format($today,'Y-m-d') ;
    }

}
?>