<?php

include_once './VIEW/BaseView.php';

class ReservasPendientesManageView extends BaseView{

    protected $jsFiles = array("./VIEW/webroot/libraries/fullcalendar-5.4.0/lib/main.js" , "./VIEW/webroot/js/bookings.js");
    protected $cssFiles = array("./VIEW/webroot/libraries/fullcalendar-5.4.0/lib/main.css");

    protected function body(){
        
        // DEBUG: Check data passed to view
        // echo '<pre>' . var_export($this->data, true) . '</pre>';
        
        $this->includeTitle("i18n-pendingBookingsByResource", "h1");
        if(empty($this->data["pending"])){
            $this->includeTitle("i18n-allPendingManaged", "h4");
        }else{
            ?>
                <div id='pending-container'>
                    <div id='colored-calendar-container'>
                        <?= $this->includeColoredCalendar() ?>
                    </div>
                    <div id='pending-list-container'>
                        <?= $this->includeTitle("i18n-pendingList", "h3") ?>
                        <div id='pending-list'>
                            <?= $this->includePendingList() ?>
                        </div>
                    </div>
                </div>
            <?php
        }

    }

    private function includePendingList(){
        foreach($this->data["pending"] as $reserva){
            if($reserva[0]["ESTADO_RESERVA"] === "PENDIENTE"){
                echo "<div class='pending-booking'>";
                    $fechaSolicitud = $this->formatDate($reserva[0]["FECHA_SOLICITUD_RESERVA"]);
                    $user = $reserva[0]["LOGIN_USUARIO"];
                    ?>
                        <h4>
                            <span class="i18n-requestedOn"></span>
                            <span><?=$fechaSolicitud?></span>
                            <span class="i18n-by"></span>
                            <strong><?=$user?></strong>
                        </h4>
                    <?php
                    echo "<ul>";
                    foreach ($reserva as $subreserva) {
                        $startDate = $this->formatDate($subreserva["FECHA_INICIO_SUBRESERVA"]);
                        $endDate = $this->formatDate($subreserva["FECHA_FIN_SUBRESERVA"]);
                        $startTime = $subreserva["HORA_INICIO_SUBRESERVA"];
                        $endTime = $subreserva["HORA_FIN_SUBRESERVA"];
                        echo "<li>" . $startDate . " - " . $endDate . ", " . $startTime . " - " . $endTime . "</li>";
                    }
                    echo "</ul>";
                    echo "<p><strong class='i18n-cost'></strong>" . $subreserva["COSTE_RESERVA"] . "€</p>";
                    echo "<div id='options'>";
                        $this->includeAcceptButtonAndModal($reserva[0]["ID_RESERVA"], $reserva[0]["ID_RECURSO"], $fechaSolicitud, $user);
                        $this->includeRejectButtonAndModal($reserva[0]["ID_RESERVA"], $reserva[0]["ID_RECURSO"], $fechaSolicitud, $user);
                    echo "</div>";
                echo "</div>";
            }
        }

    }

    private function includeAcceptButtonAndModal($bookingId, $resourceId, $date, $user){
        ?>
            <!-- Accept button -->
            <span class="<?= $this->icons["ACCEPT"]?>" data-toggle="modal" href="#acceptModal<?= $bookingId ?>"></span>

            <!-- Accept modal -->
            <div class="modal" id="acceptModal<?= $bookingId ?>">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                    
                        <!-- Modal Header  -->
                        <div class="modal-header">
                        <span class="i18n-acceptConfirm"></span>
                                <strong><?=$user?></strong>
                                <span class="i18n-the"></span>
                                <strong><?=$date?></strong>?
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            <p class="i18n-automaticReject"></p>
                            <div class="options">
                                <?php
                                    $data = array(
                                        "ID_RESERVA" => $bookingId,
                                        "ID_RECURSO" => $resourceId,
                                    );
                                    $this->includeButton("ACCEPT", "acceptModal$bookingId", "post", "ReservasController", "acceptPending", $data)
                                ?>
                                <span class="<?= $this->icons["CANCEL"] ?>" data-dismiss="modal"></span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        <?php
    }

    private function includeRejectButtonAndModal($bookingId, $resourceId, $date, $user){
        ?>
            <!-- Reject button -->
            <span class="<?= $this->icons["CANCEL"]?>" data-toggle="modal" href="#rejectModal<?= $bookingId ?>"></span>

            <!-- Reject modal -->
            <div class="modal" id="rejectModal<?= $bookingId ?>">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                    
                        <!-- Modal Header  -->
                        <div class="modal-header">
                            <h4 class="modal-title">
                                <span class="i18n-rejectConfirm"></span>
                                <strong><?=$user?></strong>
                                <span class="i18n-the"></span>
                                <strong><?=$date?></strong>?
                            </h4>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            <form class='reject-form' name='rejectForm<?=$bookingId?>' action='index.php' method='post'>
                                <?php
                                    $this->includeHiddenField("ID_RESERVA", $bookingId);
                                    $this->includeHiddenField("ID_RECURSO", $resourceId);
                                    $this->includeTextArea("i18n-bookingRejection", "MOTIVO_RECHAZO_RESERVA");
                                ?>
                                <div class="options">
                                    <span class="<?=$this->icons["ACCEPT"]?>" onclick="sendForm(document.rejectForm<?=$bookingId?>, 'ReservasController', 'rejectPending', true)"></span>
                                    <span class="<?= $this->icons["CANCEL"] ?>" data-dismiss="modal"></span>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        <?php
    }

    private function includeColoredCalendar(){

        $eventsString = '';

        $color = '4B62BF';

        foreach($this->data["pending"] as $reserva){
            foreach ($reserva as $subreserva) {
                $eventsString = $eventsString .
                            "{" .
                            "id: 'event-" . $subreserva["ID_RESERVA"] . "'," .
                            "startRecur: '" . $subreserva["FECHA_INICIO_SUBRESERVA"] . "'," .
                            "endRecur: new Date ('" . $subreserva["FECHA_FIN_SUBRESERVA"] . "')," .
                            "startTime: '" . $subreserva["HORA_INICIO_SUBRESERVA"] . "'," .
                            "endTime: '" . $subreserva["HORA_FIN_SUBRESERVA"] . "'," .
                            "color: '#$color'," .
                            "textColor: 'black'" .
                            "},";
            }
            // Get new color
            $hex = hexdec($color);
            $n = (int) ($hex += 10000);
            $color = dechex($n);
        }

        if (strpos($eventsString, '{') !== false){
            $eventsString = substr($eventsString,0,-1);
        }
        
        $this->includeCalendar($eventsString, false);
        
    }

}
?>