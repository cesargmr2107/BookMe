<?php

include_once './VIEW/BaseView.php';

class ReservasShowView extends BaseView{

    protected function body(){
        
        // DEBUG: Check data passed to view
        // echo '<pre>' . var_export($this->data, true) . '</pre>';
        
        $this->includeTitle("i18n-bookingInfo", "h1");

        if(array_key_exists("user", $this->data)){
            echo '<div>';
                $this->includeTitle("i18n-bookingUser", "h4");
                $this->includeShowInfo("i18n-login", $this->data["user"]["LOGIN_USUARIO"]);
                $this->includeShowInfo("i18n-nombre", $this->data["user"]["NOMBRE_USUARIO"]);
                $this->includeShowInfo("i18n-email", $this->data["user"]["EMAIL_USUARIO"]);
            echo '</div>';           
        }

        echo '<div>';
            $this->includeTitle("i18n-bookingRequest", "h4");
            $this->includeShowDate("i18n-fecha_solicitud", $this->data["FECHA_SOLICITUD_RESERVA"]);
            $this->includeShowInfo("i18n-estado", $this->data["ESTADO_RESERVA"]);
            if ($this->data["FECHA_RESPUESTA_RESERVA"] != '') {
                $this->includeShowInfo("i18n-bookingResponseDate", $this->data["FECHA_RESPUESTA_RESERVA"]);
            } else {
                ?>
                    <p>
                        <strong class='i18n-bookingResponseDate'></strong>
                        <span class='i18n-bookingNoResponse'></span>
                    </p>
                <?php
            }

            if($this->data["MOTIVO_RECHAZO_RESERVA"] !== null){
                $this->includeShowInfo("i18n-bookingRejection", $this->data["MOTIVO_RECHAZO_RESERVA"]);
            }
        echo '</div>';

        echo '<div>';
            $this->includeTitle("i18n-resourceInfo", "h4");
            $this->includeShowInfo("i18n-id", $this->data["resource"]["ID_RECURSO"]);
            $this->includeShowInfo("i18n-nombre", $this->data["resource"]["NOMBRE_RECURSO"]);
            $this->includeShowInfo("i18n-login_responsable", $this->data["resource"]["LOGIN_RESPONSABLE"]);
        echo '</div>';

        foreach ($this->data["subreservas"] as $subreserva) {
            $startDate = $this->formatDate($subreserva["FECHA_INICIO_SUBRESERVA"]);
            $endDate = $this->formatDate($subreserva["FECHA_FIN_SUBRESERVA"]);
            ?>
                <div>
                    <p><?=$startDate?> - <?=$endDate?></p>
                    <p><?=$subreserva["HORA_INICIO_SUBRESERVA"]?> - <?=$subreserva["HORA_FIN_SUBRESERVA"]?></p>
                    <p><?=$subreserva["COSTE_SUBRESERVA"]?>€</p>
                </div>
            <?php
        }

        echo "<h4><strong class='i18n-bookingTotalCost'></strong>" . $this->data["COSTE_RESERVA"] . "€</h4>";

    }
}
?>