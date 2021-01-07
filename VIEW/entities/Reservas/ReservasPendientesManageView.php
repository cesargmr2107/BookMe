<?php

include_once './VIEW/BaseView.php';

class ReservasPendientesManageView extends BaseView{

    protected $jsFiles = array("./VIEW/libraries/fullcalendar-5.4.0/lib/main.js" , "./VIEW/js/bookings.js");
    protected $cssFiles = array("./VIEW/libraries/fullcalendar-5.4.0/lib/main.css");

    protected function body(){
        
        // DEBUG: Check data passed to view
        // echo '<pre>' . var_export($this->data, true) . '</pre>';
        
        $this->includeTitle("Solicitudes pendientes", "h1");
        if(empty($this->data["pending"])){
            $this->includeTitle("Ya se han procesado todas las solicitudes pendientes", "h4");
        }else{
            $this->includePendingList();
            $this->includeColoredCalendar();
        }

    }

    private function includePendingList(){
        $this->includeTitle("Lista de solicitudes", "h4");

        foreach($this->data["pending"] as $reserva){
            if($reserva[0]["ESTADO_RESERVA"] === "PENDIENTE"){
                echo "<div>";
                    $fechaSolicitud = $this->formatDate($reserva[0]["FECHA_SOLICITUD_RESERVA"]);
                    $user = $reserva[0]["LOGIN_USUARIO"];
                    $this->includeTitle("Solicitud del $fechaSolicitud de $user", "h5");
                    echo "<ul>";
                    foreach ($reserva as $subreserva) {
                        $startDate = $this->formatDate($subreserva["FECHA_INICIO_SUBRESERVA"]);
                        $endDate = $this->formatDate($subreserva["FECHA_FIN_SUBRESERVA"]);
                        $startTime = $subreserva["HORA_INICIO_SUBRESERVA"];
                        $endTime = $subreserva["HORA_FIN_SUBRESERVA"];
                        echo "<li>" . $startDate . " - " . $endDate . ", " . $startTime . " - " . $endTime . "</li>";
                    }
                    echo "<li><strong>Coste:</strong>" . $subreserva["COSTE_RESERVA"] . "€</li>";
                    $this->includeAcceptButtonAndModal($reserva[0]["ID_RESERVA"], $reserva[0]["ID_RECURSO"], $fechaSolicitud, $user);
                    $this->includeRejectButtonAndModal($reserva[0]["ID_RESERVA"], $reserva[0]["ID_RECURSO"], $fechaSolicitud, $user);
                    echo "</ul>";
                echo "</div>";
            }
        }

    }

    private function includeAcceptButtonAndModal($bookingId, $resourceId, $fechaSolicitud, $user){
        ?>
            <!-- Accept button -->
            <span class="<?= $this->icons["ACCEPT"]?>" data-toggle="modal" href="#acceptModal<?= $bookingId ?>"></span>

            <!-- Accept modal -->
            <div class="modal" id="acceptModal<?= $bookingId ?>">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                    
                        <!-- Modal Header  -->
                        <div class="modal-header">
                            <h4 class="modal-title">¿Estás seguro de que quieres aceptar la reserva de '<?= $user?>' para el <?= $fechaSolicitud?>?</h4>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            <p>Se rechazarán automáticamente todas las reservas que coincidan con esta en el tiempo utilizando como motivo de rechazo el mensaje "Tu reserva ha sido rechazada porque se solapaba en el tiempo con otra de mayor prioridad"</p>
                            <span class="<?= $this->icons["CANCEL"] ?>" data-dismiss="modal"></span>
                            <?php
                                $data = array(
                                    "ID_RESERVA" => $bookingId,
                                    "ID_RECURSO" => $resourceId
                                );
                                $this->includeButton("ACCEPT", "acceptModal$bookingId", "post", "ReservasController", "acceptPending", $data)
                            ?>
                        </div>

                    </div>
                </div>
            </div>
        <?php
    }

    private function includeRejectButtonAndModal($bookingId, $resourceId, $fechaSolicitud, $user){
        ?>
            <!-- Reject button -->
            <span class="<?= $this->icons["CANCEL"]?>" data-toggle="modal" href="#rejectModal<?= $bookingId ?>"></span>

            <!-- Reject modal -->
            <div class="modal" id="rejectModal<?= $bookingId ?>">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                    
                        <!-- Modal Header  -->
                        <div class="modal-header">
                            <h4 class="modal-title">¿Estás seguro de que quieres aceptar la reserva de '<?= $user?>' para el <?= $fechaSolicitud?>?</h4>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            <form id='rejectForm<?=$bookingId?>' name='rejectForm<?=$bookingId?>' action='index.php' method='post'>
                                <?php
                                    $this->includeHiddenField("ID_RESERVA", $bookingId);
                                    $this->includeHiddenField("ID_RECURSO", $resourceId);
                                    $this->includeTextField("Motivo de rechazo", "MOTIVO_RECHAZO_RESERVA");
                                ?>
                                <span class="<?= $this->icons["CANCEL"] ?>" data-dismiss="modal"></span>
                                <span class="<?=$this->icons["ACCEPT"]?>" onclick="sendForm(document.rejectForm<?=$bookingId?>, 'ReservasController', 'rejectPending', true)"></span>
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